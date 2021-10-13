@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Items</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Items</li>
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
                                <a href="{{ route('items.create') }}"class="btn btn-primary btn-sm btn-flat">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add New Item
                                </a>
                                @endcan
                            </h3>
                            <div class="card-tools">
                                <form action="{{ route('items.index') }}">
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
                                        <th>Supplier</th>
                                        <th>Type</th>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Critical Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    <tr
                                        class="
                                            @if($item->trashed())
                                                bg-secondary
                                            @elseif($item->quantity <= $item->critical_level)
                                                bg-warning
                                            @endif">
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->supplier->company_name }}</td>
                                        <td>{{ $item->type->name }}</td>
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->unit_price }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->critical_level }}</td>
                                        <td class="d-flex p-2 justify-content-end">
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('items.edit', $item) }}">
                                                <i class="fas fa-file">
                                                </i>
                                                Edit
                                            </a>
                                            @if (!$item->trashed())
                                            <form class="px-1" method="POST"
                                                action="{{ route('items.destroy', $item) }}">
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
                                                action="{{ route('items.restore', ['item' => $item->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit">
                                                    <i class="fas fa-check">
                                                    </i>
                                                    Reactivate
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><th colspan="6" class="text-center">Could Not Load Items</th></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $items->onEachSide(1)->links() }}
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
