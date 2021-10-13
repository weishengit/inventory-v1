@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Item Type</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('types.index') }}">Types</a></li>
                        <li class="breadcrumb-item active">Add Item Type</li>
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
                            <h3 class="card-title">Create Form</h3>
                        </div>

                        <!-- form start -->
                        <form
                            action="{{ route('types.store') }}"
                            method="POST">
                            @csrf
                            <div class="card-body">
                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="type-name">Item Type Name</label>
                                    <input
                                        id="type-name"
                                        class="form-control form-control-border"
                                        type="text"
                                        name="name"
                                        required
                                        value="{{ old('name') }}"
                                        placeholder="Enter Type Name">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Add Type</button>
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
