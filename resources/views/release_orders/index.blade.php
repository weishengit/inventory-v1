@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Release Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Release Orders</li>
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
                                @can('admin')
                                <a href="{{ route('release_orders.create') }}"class="btn btn-primary btn-sm btn-flat">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add New Release Order
                                </a>
                                @endcan
                            </h3>
                            <div class="card-tools">
                                <form action="{{ route('release_orders.index') }}">
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
                                <div class="input-group-append">
                                    <a href="{{ route('release_orders.index') }}" type="button" class="btn btn-default btn-sm">
                                        All
                                    </a>
                                    <a href="{{ route('release_orders.index', ['filter' => '1']) }}" type="button" class="btn btn-default btn-sm">
                                        New
                                    </a>
                                    <a href="{{ route('release_orders.index', ['filter' => '2']) }}" type="button" class="btn btn-default btn-sm">
                                        Approved
                                    </a>
                                    <a href="{{ route('release_orders.index', ['filter' => '3']) }}" type="button" class="btn btn-default btn-sm">
                                        Pending
                                    </a>
                                    <a href="{{ route('release_orders.index', ['filter' => '4']) }}" type="button" class="btn btn-default btn-sm">
                                        Closed
                                    </a>
                                    <a href="{{ route('release_orders.index', ['filter' => '5']) }}" type="button" class="btn btn-default btn-sm">
                                        Void
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>RO Num</th>
                                        <th>Released To</th>
                                        <th>Created By</th>
                                        <th>Approved By</th>
                                        <th>Released By</th>
                                        <th>Memo</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ros as $ro)
                                    <tr>
                                        <td>{{ $ro->id }}</td>
                                        <td>{{ $ro->ro_num }}</td>
                                        <td>{{ $ro->released_to }}</td>
                                        <td>{{ "[".$ro->creator->id."]" . $ro->creator->name }}</td>
                                        @if (isset($ro->approver))
                                            <td>{{ "[".$ro->approver->id."]" . $ro->approver->name }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if (isset($ro->releaser))
                                            <td>{{ "[".$ro->releaser->id."]" . $ro->releaser->name }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $ro->memo }}</td>
                                        <td>{{ $ro->created_at->toDayDateTimeString() }}</td>
                                        <td>{{ $ro->updated_at->toDayDateTimeString() }}</td>
                                        <td>{{ $ro->status->status }}</td>
                                        <td class="d-flex p-2">
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('release_orders.show', $ro) }}">
                                                <i class="fas fa-file">
                                                </i>
                                                <br>
                                                View
                                            </a>
                                            @can('admin')
                                            @if (!$ro->trashed())
                                                @if ($ro->status_id == 1)
                                                <form class="px-1" method="POST"
                                                    action="{{ route('release_orders.approve', $ro) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm" type="submit">
                                                        <i class="fas fa-check">
                                                        </i>
                                                        Approve
                                                    </button>
                                                </form>
                                                @elseif ($ro->status_id == 2)
                                                <form class="px-1" method="POST"
                                                    action="{{ route('release_orders.receive', $ro) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm" type="submit">
                                                        <i class="fas fa-check">
                                                        </i>
                                                        Receive
                                                    </button>
                                                </form>
                                                @elseif ($ro->status_id == 3)
                                                <form class="px-1" method="POST"
                                                    action="{{ route('release_orders.close', $ro) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm" type="submit">
                                                        <i class="fas fa-check">
                                                        </i>
                                                        <br>
                                                        Close
                                                    </button>
                                                </form>
                                            @endif
                                                @elseif ($ro->status_id == 4)
                                                <div class="px-1">
                                                    <button class="btn btn-secondary btn-sm disabled" type="button">
                                                        <i class="fas fa-check">
                                                        </i>
                                                        Closed
                                                    </button>
                                                </div>
                                                @endif
                                                @if (!$ro->trashed())
                                                <form class="px-1" method="POST"
                                                    action="{{ route('release_orders.destroy', $ro) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" type="submit">
                                                        <i class="fas fa-trash">
                                                        </i>
                                                        <br>
                                                        Void
                                                    </button>
                                                </form>
                                                @endif
                                            @if ($ro->trashed())
                                                <div class="px-1">
                                                <button class="btn btn-secondary btn-sm disabled" type="submit">
                                                    <i class="fas fa-trash">
                                                    </i>
                                                    <br>
                                                    Voided
                                                </button>
                                                </div>
                                            @endif
                                        @endcan
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><th colspan="11" class="text-center">Could Not Load Release Orders</th></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $ros->onEachSide(1)->links() }}
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
