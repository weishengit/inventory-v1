<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ReleaseOrder;
use Illuminate\Http\Request;
use App\Events\VoidReleaseOrder;
use App\Events\CloseReleaseOrder;
use App\Events\CreateReleaseOrder;
use App\Models\ReleaseOrderDetail;
use Illuminate\Support\Facades\DB;
use App\Events\ApproveReleaseOrder;
use App\Events\ReleaseReleaseOrder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log as FacadesLog;
use App\Http\Requests\ReleaseOrder\StoreReleaseOrderRequest;

class ReleaseOrderController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->filter)) {
            $request->validate([
                'filter' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->filter;
            $ros = ReleaseOrder::withTrashed()
            ->with(['creator', 'approver', 'releaser', 'status'])
            ->where('status_id',$keyword)
            ->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        }
        else {
            $ros = ReleaseOrder::withTrashed()
            ->with(['creator', 'approver', 'releaser', 'status'])
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }

        return view('release_orders.index', [
            'ros' => $ros
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::all();
        return view('release_orders.create', [
            'items' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReleaseOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            // Verify Selected Items
            $input = $request->validated();
            $selectedItems = [];
            foreach ($input['items'] as $key => $value) {
                if ($value > 0) {
                    $selectedItems[$key] = $value;
                }
            }

            $ro = ReleaseOrder::create([
                'released_to' => $input['released_to'],
                'created_by' => $request->user()->id,
                'status_id' => 1,
                'ro_num' => $input['ro_num'],
                'memo' => $input['memo'],
            ]);

            foreach ($selectedItems as $key => $value) {
                ReleaseOrderDetail::create([
                    'ro_id' => $ro->id,
                    'item_id' => $key,
                    'quantity' => $value,
                ]);
            }

            DB::commit();

            event(new CreateReleaseOrder(auth()->user(), $ro));

        } catch (\Throwable $th) {
            DB::rollBack();
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Store Release Order]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "Error: [Release Order] was not created.");
        }

        return redirect()->route('release_orders.index')->with('message', "$ro->ro_num has been created.");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReleaseOrder  $ReleaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $ro = ReleaseOrder::with(['creator', 'approver', 'releaser', 'status', 'ro_details'])->withTrashed()->findOrFail($id);
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [View Release Order]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "RO could not be found" );
        }

        return view('release_orders.show', [
            'ro' => $ro,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReleaseOrder  $ReleaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReleaseOrder $releaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [ReleaseOrder.Destroy] Type['.$releaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $ro_num = $releaseOrder->ro_num;
            $releaseOrder->status_id = 5;
            $releaseOrder->save();

            event(new VoidReleaseOrder(auth()->user(), $releaseOrder));

            ReleaseOrderDetail::where('ro_id', $releaseOrder->id)->delete();
            $releaseOrder->delete();

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Void ReleaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "$ro_num could not be disabled" );
        }

        return redirect()->route('release_orders.index')->with('message', "$ro_num is now disabled" );
    }

    public function approve(ReleaseOrder $releaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Release_Order.Approve] Type['.$releaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $releaseOrder->update([
                'approved_by' => auth()->user()->id,
                'status_id' => 2
            ]);
            event(new ApproveReleaseOrder(auth()->user(), $releaseOrder));

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Approve ReleaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num could not be approved" );
        }

        return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num is now approved" );
    }

    public function receive(ReleaseOrder $releaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Release_Order.Receive] Type['.$releaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $releaseOrder->released_by = auth()->user()->id;
            $releaseOrder->status_id = 3;
            $releaseOrder->save();

            // Add Purchase Order Quantity To Global Item Quantity
            $itemsToAdd = [];
            $items = ReleaseOrderDetail::select(['item_id', 'quantity'])->where('ro_id', $releaseOrder->id)->get()->toArray();

            foreach ($items as $item) {
                $itemsToAdd[$item['item_id']] = $item['quantity'];
            }

            foreach ($itemsToAdd as $key => $value) {
                $item = Item::find($key);
                if ($item->quantity - $value < 0) {
                    return redirect()->route('release_orders.index')->with('message', "Not enough $item->name in stock to release" );
                }
            }

            foreach ($itemsToAdd as $key => $value) {
                $item = Item::find($key);
                $item->quantity -= $value;
                $item->save();
            }

            event(new ReleaseReleaseOrder(auth()->user(), $releaseOrder));

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Receive ReleaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num could not be released" );
        }

        return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num is now released" );
    }

    public function close(ReleaseOrder $releaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Purchase_Order.Close] Type['.$releaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $releaseOrder->status_id = 4;
            $releaseOrder->save();

            event(new CloseReleaseOrder(auth()->user(), $releaseOrder));

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Close ReleaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num could not be closed" );
        }

        return redirect()->route('release_orders.index')->with('message', "$releaseOrder->ro_num is now closed" );
    }
}
