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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @can('superadmin')
                                <a href="{{ route('accounts.create') }}"class="btn btn-primary btn-sm btn-flat">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create New User
                                </a>
                                @endcan
                            </h3>
                            <div class="card-tools">
                                <form action="{{ route('accounts.index') }}">
                                <div class="input-group input-group-sm pt-1" style="width: 150px;">
                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control float-right"
                                        value="{{ $_GET['search'] ?? '' }}"
                                        placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
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
                                            @if ($account->id == 1)
                                                <button class="btn btn-primary btn-block btn-sm" type="button">
                                                    <i class="fas fa-user-tie">
                                                    </i>
                                                    Superadmin
                                                </button>
                                            @else
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('accounts.show', ['account' => $account]) }}">
                                                    <i class="fas fa-file">
                                                    </i>
                                                    View
                                                </a>
                                                @if (auth()->user()->id == 1)
                                                    @if ($account->deleted_at == null)
                                                    <form class="px-1" method="POST"
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
                                                    <form class="px-1" method="POST"
                                                        action="{{ route('accounts.restore', ['account' => $account->id]) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-success btn-sm" type="submit">
                                                            <i class="fas fa-check">
                                                            </i>
                                                            Reactivate
                                                        </button>
                                                    </form>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><th colspan="6" class="text-center">Could Not Load Accounts</th></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $accounts->onEachSide(1)->links() }}
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
