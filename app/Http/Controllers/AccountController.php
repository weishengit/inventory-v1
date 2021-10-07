<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('superadmin');

        if (isset($request->search)) {
            $keyword = $request->validate([
                'search' => 'required', 'string', 'max:250'
            ]);
            $accounts = User::with(['role'])->withTrashed()->where(function ($query) use ($keyword) {
                $query->where('email', 'like', '%' . $keyword . '%')->orWhere('name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('id')->paginate(10)->withQueryString();
        } else {
            $accounts = User::with(['role'])->withTrashed()->orderBy('id')->paginate(10);
        }

        return view('admin.accounts.index', [
            'accounts' => $accounts
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $account)
    {
        $this->authorize('admin');

        $logs = Log::where('user_id', $account->id)->orderBy('id', 'DESC')->paginate(5);

        return view('admin.accounts.show', [
            'user' => $account,
            'logs' => $logs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $account)
    {
        $this->authorize('superadmin');
        dd('edited');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $account)
    {
        $this->authorize('superadmin');
        dd('Deleted');
    }
}
