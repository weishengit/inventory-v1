<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserEditEvent;
use App\Events\UserDeleteEvent;
use App\Events\UserRestoreEvent;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log as FacadesLog;
use App\Http\Requests\Accounts\StoreAccountRequest;
use App\Http\Requests\Accounts\UpdateAccountRequest;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Index] Without Proper Authorization');
            abort(403);
        }

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
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Create] Without Proper Authorization');
            abort(403);
        }

        $roles = Role::all();

        return view('admin.accounts.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountRequest $request)
    {
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Store] Without Proper Authorization', ['user' => $request->validated()]);
            abort(403);
        }

        try {
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'avatar' => ''
            ]);

            $path = $request->file('avatar')->storeAs(
                'avatars',
                $user->id . '-avatar.'. $request->file('avatar')->extension(),
                'public'
            );

            $user->avatar = $path;
            $user->save();
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Account]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('accounts.index')->with('message', "$user->name was not created.");
        }


        return redirect()->route('accounts.index')->with('message', "$user->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('admin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Show] User['.$id.'] Without Proper Authorization');
            abort(403);
        }
        try {
            $user = User::withTrashed()->findOrFail($id);

            $logs = Log::where('user_id', $id)->orderBy('id', 'DESC')->paginate(5);

            if ($last_login = Log::where('user_id', $id)->where('type', 'Login')->orderBy('id', 'DESC')->first()) {
                $last_login = $last_login->created_at->toDayDateTimeString();
            }
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [View Account]', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('accounts.index')->with('message', "$user->name could not be found" );
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
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Edit] User['.$account->id.'] Without Proper Authorization');
            abort(403);
        }

        $roles = Role::all();

        return view('admin.accounts.edit', [
            'user' => $account,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, User $account)
    {
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Update] User['.$account->id.'] Without Proper Authorization');
            abort(403);
        }

        $values = $request->validated();
        try {
            // Check If Password Changed
            if ($values['password'] == null) {
                unset($values['password']);
            }

            // Check If Avatar Changed
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->storeAs(
                    'avatars',
                    $account->id . '-avatar.'. $request->file('avatar')->extension(),
                    'public'
                );

                $values['avatar'] = $path;
            }

            $account->update($values);
            event(new UserEditEvent(auth()->user(), $account->getOriginal(), $account));

        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Account]', [
                'error' => $th->getMessage()
            ]);

            return redirect()->route('accounts.index')->with('message', "$account->name could not be updated.");
        }

        return redirect()->route('accounts.index')->with('message', "$account->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $account)
    {
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Destroy] User['.$account->id.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $account->delete();
            event(new UserDeleteEvent($account, auth()->user()));
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Account]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('accounts.index')->with('message', "$account->name could not be disabled" );
        }

        return redirect()->route('accounts.index')->with('message', "$account->name is now disabled" );
    }

    public function restore(Request $request)
    {
        if (!Gate::allows('superadmin')) {
            FacadesLog::channel('dailysuspicious')->alert('User['.auth()->user()->id.'] Tried To Enter Page [Account.Restore] User['.$request->account.'] Without Proper Authorization');
            abort(403);
        }

        try {
            $account = User::withTrashed()->where('deleted_at', '!=', null)->findOrFail($request->account);
            $account->restore();
            event(new UserRestoreEvent($account, auth()->user()));
        } catch (\Throwable $th) {
            FacadesLog::channel('dailyerror')->alert('Error : User['.auth()->user()->id.'] Encountered An Error To [Update Account]', [
                'error' => $th->getMessage()
            ]);
            return redirect()->route('accounts.index')->with('message', "Error: Could Not Find Account" );
        }

        return redirect()->route('accounts.index')->with('message', "$account->name is now active" );
    }
}
