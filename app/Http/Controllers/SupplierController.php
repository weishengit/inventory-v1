<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Events\SupplierEditEvent;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log as FacadesLog;
use App\Http\Requests\Suppliers\StoreSupplierRequest;
use App\Http\Requests\Suppliers\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Index] Without Proper Authorization');
            abort(403);
        }

        if (isset($request->search)) {
            $request->validate([
                'search' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->search;
            $suppliers = Supplier::withTrashed()->where(function ($query) use ($keyword) {
                $query->where('company_name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
            })
            ->orderBy('id')->paginate(10)->withQueryString();
        } else {
            $suppliers = Supplier::withTrashed()->orderBy('id')->paginate(10);
        }

        return view('admin.suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Create] Without Proper Authorization');
            abort(403);
        }

        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Store] Without Proper Authorization', ['supplier' => $request->validated()]);
            abort(403);
        }

        try {
            $supplier = Supplier::create($request->validated());
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Store Supplier]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('suppliers.index')->with('message', "Error: [Supplier] was not created.");
        }


        return redirect()->route('suppliers.index')->with('message', "$supplier->company_name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Show] Supplier['.$id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $supplier = Supplier::withTrashed()->findOrFail($id);
            $orders = PurchaseOrder::with('status')->where('supplier_id', $id)->orderBy('id', 'DESC')->paginate(5);

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [View Account]', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('suppliers.index')->with('message', "$supplier->name could not be found" );
        }

        return view('admin.suppliers.show', [
            'supplier' => $supplier,
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Edit] Supplier['.$supplier->id.'] Without Proper Authorization');
            abort(403);
        }

        return view('admin.suppliers.edit', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Update] Supplier['.$supplier->id.'] Without Proper Authorization');
            abort(403);
        }

        $values = $request->validated();
        try {
            $supplier->update($values);
            event(new SupplierEditEvent(auth()->user(), $supplier));
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Supplier] Supplier['.$supplier->id.']', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('suppliers.index')->with('message', "$supplier->company_name could not be updated.");
        }

        return redirect()->route('suppliers.index')->with('message', "$supplier->company_name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Destroy] Supplier['.$supplier->id.'] Without Proper Authorization');
            abort(403);
        }

        // Cancel If Supplier Is In Use
        if (Item::where('supplier_id', $supplier->id)->where('deleted_at', null)->count() > 0) {
            return redirect()->route('suppliers.index')->with('message', "$supplier->company_name is in use by items, and cannot be disabled" );
        }

        try {
            $supplier->delete();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Disable Account]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('suppliers.index')->with('message', "$supplier->company_name could not be disabled" );
        }

        return redirect()->route('suppliers.index')->with('message', "$supplier->company_name is now disabled" );
    }

    public function restore(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Restore] Supplier['.$request->supplier.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $supplier = Supplier::withTrashed()->where('deleted_at', '!=', null)->findOrFail($request->supplier);
            $supplier->restore();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Restore Supplier]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('suppliers.index')->with('message', "Error: Could Not Find Supplier" );
        }

        return redirect()->route('suppliers.index')->with('message', "$supplier->company_name is now active" );
    }
}
