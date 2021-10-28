@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Release Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('release_orders.index') }}">Release Order</a></li>
                        <li class="breadcrumb-item active">Create</li>
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

                    <form action="{{ route('release_orders.store') }}" method="POST">
                    @csrf
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Create Release Order</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="bs-stepper">
                                <div class="bs-stepper-header" role="tablist">
                                    <!-- your steps here -->
                                    <div class="step" data-target="#supplier-part">
                                        <button type="button" class="step-trigger" role="tab"
                                            aria-controls="supplier-part" id="supplier-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Recipient</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#items-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="items-part"
                                            id="items-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Items</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    {{-- Part 1 --}}
                                    <div id="supplier-part" class="content" role="tabpanel"
                                            aria-labelledby="supplier-part-trigger">
                                        {{-- Supplier --}}
                                            <div class="form-group">
                                                <label for="release-to">Release To</label>
                                                <input
                                                    id="release-to"
                                                    class="form-control form-control-border "
                                                    type="text"
                                                    name="released_to"
                                                    required
                                                    value="{{ old('released_to') }}"
                                                    placeholder="Enter Release Recipient">
                                            </div>
                                        {{-- Ro Num --}}
                                        <div class="form-group">
                                            <label for="ro-num">RO #</label>
                                            <input
                                                id="ro-num"
                                                class="form-control form-control-border "
                                                type="text"
                                                name="ro_num"
                                                required
                                                value="{{ old('ro_num') }}"
                                                placeholder="Enter RO Number">
                                        </div>
                                        {{-- Memo --}}
                                        <div class="form-group">
                                            <label for="memo">Memo <small>(Optional)</small></label>
                                            <input
                                                id="memo"
                                                class="form-control form-control-border "
                                                type="text"
                                                name="memo"
                                                value="{{ old('memo') }}"
                                                placeholder="Enter Memo">
                                        </div>
                                        <button class="btn btn-primary" type="button" onclick="stepper.next();getSupplierItems();">Next</button>
                                    </div>
                                    {{-- Part 2 --}}
                                    <div id="items-part" class="content" role="tabpanel"
                                        aria-labelledby="items-part-trigger">
                                        <h4 id="supplier-name"></h4>
                                        <div class="card-body table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">#</th>
                                                        <th>Type</th>
                                                        <th>SKU</th>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Current Quantity</th>
                                                        <th>Quantity To Release</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="supplier-items">
                                                    <tr>
                                                        <th colspan="6" class="text-center">Could Not Load Items</th>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer clearfix">
                                            <ul class="pagination pagination-sm m-0 float-right">
                                            </ul>
                                        </div>
                                        <button class="btn btn-warning" type="button" onclick="stepper.previous()">Back</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                    </form>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script>
    // BS-Stepper Init
document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
});

async function getSupplierItems() {
    const itemsDiv = document.querySelector('#supplier-items')
    const url = "{!! route('fetch.items') !!}" + `/0`;
    const res = await fetch(url);
    const data = await res.json();

    let template = '';
    data.forEach((item) => {
        template += `
        <tr>
            <td>${item.id}</td>
            <td>${item.type.name}</td>
            <td>${item.sku}</td>
            <td>${item.name}</td>
            <td>${item.unit_price}</td>
            <td>${item.quantity}</td>
            <td>
            <div class="form-group">
                <input name="items[${item.id}]" type="text" min="0" class="form-control" value="0">
            </div>
            </td>
        </tr>
        `
    });

    itemsDiv.innerHTML = template;
}
</script>
@endsection
