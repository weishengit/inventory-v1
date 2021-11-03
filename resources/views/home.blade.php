@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="{{ route('report.statistics') }}">
                        <div class="info-box">

                            <span class="info-box-icon bg-info"><i class="fas fa-cubes"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Stock</span>
                                <span class="info-box-number">{{ number_format($totalStocks) ?? '.' }}</span>
                            </div>

                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <div class="col-md-3 col-sm-6 col-12">
                    <a href="{{ route('purchase_orders.index') }}">
                        <div class="info-box">

                            <span class="info-box-icon bg-success"><i class="fas fa-dolly-flatbed"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending Purchase Orders</span>
                                <span class="info-box-number">{{ $pendingPO ?? '.' }}</span>
                            </div>

                            <!-- /.info-box-content -->
                        </div>
                    </a>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="{{ route('release_orders.index') }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-shipping-fast"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pending Release Orders</span>
                                <span class="info-box-number">{{ $pendingRO ?? '.' }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="{{ route('items.index') }}">

                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-ruler-vertical"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Critial Level</span>
                                <span class="info-box-number">{{ $criticalLevel ?? '.' }}</span>
                            </div>
                        <!-- /.info-box-content -->
                        </div>
                    <!-- /.info-box -->
                    </a>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
