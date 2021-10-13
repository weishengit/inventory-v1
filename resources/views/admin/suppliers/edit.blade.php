@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Supplier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                        <li class="breadcrumb-item active">Edit Supplier</li>
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
                            action="{{ route('suppliers.update', ['supplier' => $supplier]) }}"
                            method="POST">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                {{-- TIN --}}
                                <div class="form-group">
                                    <label for="tin">TIN</label>
                                    <input
                                        id="tin"
                                        class="form-control form-control-border "
                                        type="text"
                                        name="tin"
                                        required
                                        value="{{ $supplier->tin }}"
                                        placeholder="Enter TIN">
                                </div>
                                {{-- BIR --}}
                                <div class="form-group">
                                    <label for="bir">BIR</label>
                                    <input
                                        id="bir"
                                        class="form-control form-control-border "
                                        type="text"
                                        name="bir"
                                        required
                                        value="{{ $supplier->bir }}"
                                        placeholder="Enter BIR">
                                </div>
                                {{-- VAT --}}
                                <div class="form-group">
                                    <label for="vat">VAT</label>
                                    <input
                                        id="vat"
                                        class="form-control form-control-border "
                                        type="text"
                                        name="vat"
                                        required
                                        value="{{ $supplier->vat }}"
                                        placeholder="Enter Vat">
                                </div>
                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="company-name">Company Name</label>
                                    <input
                                        id="company-name"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="company_name"
                                        required
                                        value="{{ $supplier->company_name }}"
                                        placeholder="Enter Company Name">
                                </div>
                                {{-- Contact Person --}}
                                <div class="form-group">
                                    <label for="company-rep">Company Representative</label>
                                    <input
                                        id="company-rep"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="contact_person"
                                        required
                                        value="{{ $supplier->contact_person }}"
                                        placeholder="Enter Contact Person">
                                </div>
                                {{-- Address --}}
                                <div class="form-group">
                                    <label for="company-address">Company Address</label>
                                    <input
                                        id="company-address"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="address"
                                        required
                                        value="{{$supplier->address }}"
                                        placeholder="Enter Company Address">
                                </div>
                                {{-- Contact --}}
                                <div class="form-group">
                                    <label for="company-contact">Company Number</label>
                                    <input
                                        id="company-contact"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="contact"
                                        required
                                        value="{{ $supplier->contact }}"
                                        placeholder="Enter Company Number">
                                </div>
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="company-email">Company Email</label>
                                    <input
                                        id="company-email"
                                        class="form-control form-control-border"
                                        type="email"
                                        name="email"
                                        required
                                        value="{{ $supplier->email }}"
                                        placeholder="Enter Company Email">
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
