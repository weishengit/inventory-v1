<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log as FacadesLog;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->filter)) {
            $request->validate([
                'filter' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->filter;
            $pos = PurchaseOrder::withTrashed()
            ->with(['supplier', 'creator', 'approver', 'receiver', 'status'])
            ->where('status_id',$keyword)
            ->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        }
        else {
            $pos = PurchaseOrder::withTrashed()
            ->with(['supplier', 'creator', 'approver', 'receiver', 'status'])
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }

        return view('purchase_orders.index', [
            'pos' => $pos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchase_orders.create', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseOrderRequest $request)
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

            $po = PurchaseOrder::create([
                'supplier_id' => $input['supplier_id'],
                'created_by' => $request->user()->id,
                'status_id' => 1,
                'po_num' => $input['po_num'],
                'memo' => $input['memo'],
            ]);

            foreach ($selectedItems as $key => $value) {
                PurchaseOrderDetail::create([
                    'po_id' => $po->id,
                    'item_id' => $key,
                    'quantity' => $value,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Store Purchase Order]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('purchase_orders.index')->with('message', "Error: [Purchase Order] was not created.");
        }

        return redirect()->route('purchase_orders.index')->with('message', "$po->po_num has been created.");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $po = PurchaseOrder::with(['supplier', 'creator', 'approver', 'receiver', 'status', 'po_details'])->withTrashed()->findOrFail($id);
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [View Purchase Order]', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('purchase_orders.index')->with('message', "PO could not be found" );
        }

        return view('purchase_orders.show', [
            'po' => $po,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Purchase_Order.Destroy] Type['.$purchaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $po_num = $purchaseOrder->po_num;
            $purchaseOrder->status_id = 5;
            $purchaseOrder->save();
            PurchaseOrderDetail::where('po_id', $purchaseOrder->id)->delete();
            $purchaseOrder->delete();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Void PurchaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('purchase_orders.index')->with('message', "$po_num could not be disabled" );
        }

        return redirect()->route('purchase_orders.index')->with('message', "$po_num is now disabled" );
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Purchase_Order.Approve] Type['.$purchaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $purchaseOrder->update([
                'approved_by' => auth()->user()->id,
                'status_id' => 2
            ]);
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Approve PurchaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num could not be approved" );
        }

        return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num is now approved" );
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Purchase_Order.Receive] Type['.$purchaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $purchaseOrder->received_by = auth()->user()->id;
            $purchaseOrder->status_id = 3;
            $purchaseOrder->update();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Receive PurchaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num could not be received" );
        }

        return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num is now received" );
    }

    public function close(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Purchase_Order.Close] Type['.$purchaseOrder->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $purchaseOrder->status_id = 4;
            $purchaseOrder->update();

            // Add Purchase Order Quantity To Global Item Quantity
            $itemsToAdd = [];
            $items = PurchaseOrderDetail::select(['item_id', 'quantity'])->where('po_id', $purchaseOrder->id)->get()->toArray();

            foreach ($items as $item) {
                $itemsToAdd[$item['item_id']] = $item['quantity'];
            }

            foreach ($itemsToAdd as $key => $value) {
                $item = Item::find($key);
                $item->quantity += $value;
                $item->save();
            }

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Close PurchaseOrder]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num could not be closed" );
        }

        return redirect()->route('purchase_orders.index')->with('message', "$purchaseOrder->po_num is now closed" );
    }
}
