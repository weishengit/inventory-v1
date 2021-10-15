<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Type;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\ReleaseOrderDetail;
use App\Models\PurchaseOrderDetail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use Illuminate\Support\Facades\Log as FacadesLog;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $request->validate([
                'search' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->search;
            $items = Item::withTrashed()->with(['supplier', 'type'])->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')->orWhere('sku', 'like', '%' . $keyword . '%');
            })->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        }
        else {
            $items = Item::withTrashed()->with(['supplier', 'type'])->orderBy('id', 'DESC')->paginate(10);
        }

        return view('items.index', [
            'items' => $items
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
        $types = Type::all();

        return view('items.create', [
            'suppliers' => $suppliers,
            'types' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        try {
            $item = Item::create($request->validated());
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Store Item]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('items.index')->with('message', "Error: [Item] was not created.");
        }


        return redirect()->route('items.index')->with('message', "$item->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $suppliers = Supplier::all();
        $types = Type::all();

        return view('items.edit', [
            'item' => $item,
            'suppliers' => $suppliers,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $values = $request->validated();
        try {
            $item->update($values);
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Item] Type['.$item->id.']', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('items.index')->with('message', "$item->name could not be updated.");
        }

        return redirect()->route('items.index')->with('message', "$item->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Destroy] Type['.$item->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            // Cancel If Item Has Pending Purchase/Release Orders
            $pos = PurchaseOrderDetail::with('purchaseOrder')->where('item_id', $item->id)->where('deleted_at', null)->get();
            foreach($pos as $po) {
                if (!in_array($po->purchaseOrder->status_id, [4, 5])) {
                    return redirect()->route('items.index')->with('message', "$item->name could not be disabled because of unfinished purchase order/s" );
                }
            }
            $ros = ReleaseOrderDetail::with('releaseOrder')->where('item_id', $item->id)->where('deleted_at', null)->get();
            foreach($ros as $ro) {
                if (!in_array($ro->releaseOrder->status_id, [4, 5])) {
                    return redirect()->route('items.index')->with('message', "$item->name could not be disabled because of unfinished release order/s" );
                }
            }

            $item->delete();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Disable Item]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('items.index')->with('message', "$item->name could not be disabled" );
        }

        return redirect()->route('items.index')->with('message', "$item->name is now disabled" );
    }

    public function restore(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Item.Restore] Item['.$request->item.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $item = Item::withTrashed()->where('deleted_at', '!=', null)->findOrFail($request->item);
            $item->restore();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Restore Item]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('items.index')->with('message', "Error: Could Not Find Item" );
        }

        return redirect()->route('items.index')->with('message', "$item->name is now active" );
    }
}
