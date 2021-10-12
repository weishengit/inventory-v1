@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Supplier Info</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                        <li class="breadcrumb-item active">Supplier Info</li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @if (isset($supplier))
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">

                            <h3 class="profile-username text-center">{{ $supplier->company_name }}</h3>

                            @if ($supplier->deleted_at == null)
                            <a class="btn btn-primary btn-block"><b>Active</b></a>
                            @else
                            <a class="btn btn-danger btn-block"><b>Inactive</b></a>
                            @endif

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Created</b> <a
                                        class="float-right">{{ $supplier->created_at->toDayDateTimeString() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Updated</b> <a
                                        class="float-right">{{ $supplier->updated_at->toDayDateTimeString() }}</a>
                                </li>
                            </ul>

                            @can('superadmin')
                            <a class="btn btn-warning btn-block" href="{{ route('suppliers.edit', ['supplier' => $supplier]) }}"><b>Edit Supplier</b></a>
                            @endcan

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2 d-flex">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active mx-1" href="#activity" data-toggle="tab">Activity
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mx-1" href="#info" data-toggle="tab">Info
                                    </a>
                                </li>
                            </ul>
                            <div class="ml-auto px-2">
                                {{ $orders->links() }}
                            </div>

                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="tab-pane active" id="activity">
                                        <!-- The timeline -->
                                        <div class="timeline timeline-inverse">
                                            <!-- timeline time label -->

                                            @forelse ($orders as $order)
                                            <div>
                                                <i class="fas fa-user bg-info"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i>
                                                        {{ $order->created_at->toDayDateTimeString() }}</span>

                                                    <h3 class="timeline-header border-0">
                                                        <a>
                                                            Purchase Order #{{ $order->id }} -
                                                        </a>
                                                        Status : {{ $order->status->status }}
                                                    </h3>
                                                </div>
                                            </div>
                                            @empty
                                            <h4 class="text-center p-4">Could Not Load Orders Log</h4>
                                            @endforelse

                                            <div>
                                                <i class="far fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-pane -->
                                <div class="tab-pane" id="info">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><b>Company TIN</b>     :  {{ $supplier->tin }}</li>
                                        <li class="list-group-item"><b>Company BIR</b>     :  {{ $supplier->bir }}</li>
                                        <li class="list-group-item"><b>Company VAT</b>     :  {{ $supplier->vat }}</li>
                                        <li class="list-group-item"><b>Company Name</b>    :  {{ $supplier->company_name }}</li>
                                        <li class="list-group-item"><b>Representative</b>  :  {{ $supplier->contact_person }}</li>
                                        <li class="list-group-item"><b>Company Contact</b> :  {{ $supplier->contact }}</li>
                                        <li class="list-group-item"><b>Company Email</b>   :  {{ $supplier->email }}</li>
                                        <li class="list-group-item"><b>Company Address</b> :  {{ $supplier->address }}</li>
                                    </ul>
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->

                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    @else
    <h4>Could Not Load User Profile</h4>
    @endif
    <!-- /.content -->
</div>

@endsection
