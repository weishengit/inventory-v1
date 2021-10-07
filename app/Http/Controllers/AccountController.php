<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserDeleteEvent;
use App\Events\UserRestoreEvent;

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
            $request->validate([
                'search' => 'required', 'string', 'max:250'
            ]);
            $keyword = $request->search;
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
    public function show($id)
    {
        $this->authorize('admin');

        $user = User::withTrashed()->findOrFail($id);

        $logs = Log::where('user_id', $id)->orderBy('id', 'DESC')->paginate(5);

        if ($last_login = Log::where('user_id', $id)->where('type', 'Login')->orderBy('id', 'DESC')->first()) {
            $last_login = $last_login->created_at->toDayDateTimeString();
        }


        return view('admin.accounts.show', [
            'user' => $user,
            'logs' => $logs,
            'last_login' => $last_login
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
        $this->authorize('superadmin');

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

        event(new UserDeleteEvent($account, auth()->user()));

        $account->delete();

        return redirect()->route('accounts.index')->with('message', "$account->name is now disabled" );
    }

    public function restore(Request $request)
    {
        $this->authorize('superadmin');

        $account = User::withTrashed()->findOrFail($request->account);
        $account->restore();

        event(new UserRestoreEvent($account, auth()->user()));

        return redirect()->route('accounts.index')->with('message', "$account->name is now active" );
    }
}
