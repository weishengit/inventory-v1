<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log as FacadesLog;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
