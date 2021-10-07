@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Accounts</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Accounts</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Users Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($accounts as $account)
                                    <tr>
                                        <td>{{ $account->id }}</td>
                                        <td>{{ $account->email }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->role->name }}</td>
                                        <td>{{ $account->created_at->toDayDateTimeString() }}</td>
                                        <td class="d-flex p-2 justify-content-end">
                                            <a class="btn btn-info btn-sm" href="{{ route('accounts.show', ['account' => $account]) }}">
                                                <i class="fas fa-file">
                                                </i>
                                                View
                                            </a>
                                            @if (auth()->user()->id == 1)
                                                @if ($account->delete_at == null)
                                                <form
                                                    class="px-1"
                                                    method="POST"
                                                    action="{{ route('accounts.destroy', ['account' => $account]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit">
                                                        <i class="fas fa-trash">
                                                        </i>
                                                        Deactivate
                                                    </button>
                                                </form>
                                                @else
                                                <form
                                                    class="px-1"
                                                    method="POST"
                                                    action="{{ route('accounts.restore', ['account' => $account]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm" href="{{ route('accounts.restore', ['account' => $account]) }}">
                                                        <i class="fas fa-check">
                                                        </i>
                                                        Activate
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <h4>Could Not Load Accounts</h4>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $accounts->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
