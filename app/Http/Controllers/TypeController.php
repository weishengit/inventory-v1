<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Types\StoreTypeRequest;
use App\Http\Requests\Types\UpdateTypeRequest;
use Illuminate\Support\Facades\Log as FacadesLog;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Index] Without Proper Authorization');
            abort(403);
        }

        if (isset($request->search)) {
            $request->validate([
                'search' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->search;
            $types = Type::withTrashed()->withCount('items')->where('name', 'like', '%' . $keyword . '%')
            ->orderBy('id')->paginate(10)->withQueryString();
        } else {
            $types = Type::withTrashed()->withCount('items')->orderBy('id')->paginate(10);
        }

        return view('admin.types.index', [
            'types' => $types
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
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Create] Without Proper Authorization');
            abort(403);
        }

        return view('admin.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeRequest $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Store] Without Proper Authorization', ['supplier' => $request->validated()]);
            abort(403);
        }

        try {
            $type = Type::create($request->validated());
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Store Type]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('types.index')->with('message', "Error: [Type] was not created.");
        }

        return redirect()->route('types.index')->with('message', "$type->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return redirect()->route('types.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Edit] Supplier['.$type->id.'] Without Proper Authorization');
            abort(403);
        }

        return view('admin.types.edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Update] Supplier['.$type->id.'] Without Proper Authorization');
            abort(403);
        }

        $values = $request->validated();
        try {
            $type->update($values);
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Type] Type['.$type->id.']', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('types.index')->with('message', "$type->name could not be updated.");
        }

        return redirect()->route('types.index')->with('message', "$type->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Type.Destroy] Type['.$type->id.'] Without Proper Authorization');
            abort(403);
        }

        // Cancel If Supplier Is In Use
        if (Item::where('type_id', $type->id)->where('deleted_at', null)->count() > 0) {
            return redirect()->route('types.index')->with('message', "$type->name is in use by items, and cannot be disabled" );
        }

        try {
            $type->delete();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Disable Type]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('types.index')->with('message', "$type->name could not be disabled" );
        }

        return redirect()->route('types.index')->with('message', "$type->name is now disabled" );
    }

    public function restore(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Supplier.Restore] Supplier['.$request->supplier.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $type = Type::withTrashed()->where('deleted_at', '!=', null)->findOrFail($request->type);
            $type->restore();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Restore Type]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('types.index')->with('message', "Error: Could Not Find Type" );
        }

        return redirect()->route('types.index')->with('message', "$type->name is now active" );
    }
}
