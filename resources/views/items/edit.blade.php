@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Items</a></li>
                        <li class="breadcrumb-item active">Edit Item</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Form</h3>
                        </div>

                        <!-- form start -->
                        <form
                            action="{{ route('items.update', $item) }}"
                            method="POST">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                {{-- Supplier --}}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="item-supplier">Supplier</label>
                                    </div>
                                    <select
                                        id="user-supplier"
                                        class="custom-select"
                                        name="supplier_id"
                                        required>
                                        @forelse ($suppliers as $supplier)
                                            <option
                                                @if($item->supplier_id == $supplier->id) selected @endif
                                                value="{{ $supplier->id }}">
                                                {{ "[$supplier->id] - $supplier->company_name" }}
                                            </option>
                                        @empty
                                            <option>Could Not Load Companies</option>
                                        @endforelse
                                    </select>
                                </div>
                                {{-- Type --}}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="item-type">Type</label>
                                    </div>
                                    <select
                                        id="item-type"
                                        class="custom-select"
                                        name="type_id"
                                        required>
                                        @forelse ($types as $type)
                                            <option
                                                @if($item->type_id == $type->id) selected @endif
                                                value="{{ $type->id }}">
                                                {{ "[$type->id] - $type->name" }}
                                            </option>
                                        @empty
                                            <option>Could Not Load Item Type</option>
                                        @endforelse
                                    </select>
                                </div>
                                {{-- SKU --}}
                                <div class="form-group">
                                    <label for="item-sku">SKU</label>
                                    <input
                                        id="item-sku"
                                        class="form-control form-control-border "
                                        type="text"
                                        name="sku"
                                        required
                                        value="{{ $item->sku }}"
                                        placeholder="Enter SKU">
                                </div>
                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="item-name">Item Name</label>
                                    <input
                                        id="item-name"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="name"
                                        required
                                        value="{{ $item->name }}"
                                        placeholder="Enter Item Name">
                                </div>
                                {{-- Price --}}
                                <div class="form-group">
                                    <label for="item-price">Unit Price</label>
                                    <input
                                        id="item-price"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="unit_price"
                                        required
                                        value="{{ $item->unit_price }}"
                                        placeholder="Enter Unit Price">
                                </div>
                                {{-- Critical Level --}}
                                <div class="form-group">
                                    <label for="item-critical">Critical Level</label>
                                    <input
                                        id="item-critical"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="critical_level"
                                        required
                                        value="{{ $item->critical_level }}"
                                        placeholder="Enter Critical Level">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" onclick="history.back()" class="btn btn-warning">Back</button>
                            </div>
                        </form>
                    </div><!-- /.card -->
                </div><!-- /.col-12 -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

</div><!-- /.content-wrapper -->

@endsection
