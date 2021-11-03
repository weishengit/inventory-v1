@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
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

                        {{-- <div class="text-center mb-4">
                            @if(isset($user->avatar))
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                style="object-fit: contain;
                                object-position: center;
                                max-height: 100px;
                                margin-bottom: 1rem;"
                                class="profile-user-img img-fluid img-circle" alt="User Image">
                            @else
                            <img src="{{ asset('dist/img/default-150x150.png') }}"
                                class="profile-user-img img-fluid img-circle" alt="User Image">
                            @endif
                        </div> --}}
                        <!-- form start -->
                        <form
                            action="{{ route('accounts.update', ['account' => $user]) }}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="user-email">Email address</label>
                                    <input
                                        id="user-email"
                                        class="form-control"
                                        type="email"
                                        name="email"
                                        required
                                        value="{{ $user->email }}"
                                        placeholder="Enter email">
                                </div>
                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="user-name">Name</label>
                                    <input
                                        id="user-name"
                                        class="form-control"
                                        type="text"
                                        name="name"
                                        required
                                        value="{{ $user->name }}"
                                        placeholder="Enter name">
                                </div>
                                {{-- Password --}}
                                <div class="form-group">
                                    <label for="user-password">Password</label>
                                    <input
                                        id="user-password"
                                        class="form-control"
                                        type="password"
                                        name="password"
                                        placeholder="Password">
                                </div>
                                {{-- Password Confirm --}}
                                <div class="form-group">
                                    <label for="user-password-confirm">Re-type Password</label>
                                    <input
                                        id="user-password-confirm"
                                        class="form-control"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Re-type Password">
                                </div>
                                {{-- Profile Picture --}}
                                {{-- <div class="form-group">
                                    <label for="user-avatar">Profile Picture</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input
                                                id="user-avatar"
                                                class="custom-file-input"
                                                type="file"
                                                name="avatar">
                                            <label class="custom-file-label" for="user-avatar">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- Role --}}
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="user-role">Role</label>
                                    </div>
                                    <select
                                        id="user-role"
                                        class="custom-select"
                                        name="role_id"
                                        required>
                                        @forelse ($roles as $role)
                                            <option
                                                value="{{ $role->id }}"
                                                @if($user->role_id == $role->id) selected @endif>
                                                {{ $role->name }}
                                            </option>
                                        @empty
                                            <option>Could Not Load Roles</option>
                                        @endforelse
                                    </select>
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
