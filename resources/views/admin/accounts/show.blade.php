@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Log</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li>
                        <li class="breadcrumb-item active">User Log</li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @if (isset($user))
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">

                            {{-- <div class="text-center">
                                @if(isset($user->avatar))
                                <img src="{{ asset('storage/'. $user->avatar) }}"
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

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>

                            <p class="text-muted text-center">{{ $user->role->name }}</p>
                            @if ($user->deleted_at == null)
                            <a class="btn btn-primary btn-block"><b>Active</b></a>
                            @else
                            <a class="btn btn-danger btn-block"><b>Inactive</b></a>
                            @endif
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Created</b> <a
                                        class="float-right">{{ $user->created_at->toDayDateTimeString() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Updated</b> <a
                                        class="float-right">{{ $user->updated_at->toDayDateTimeString() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Login</b>
                                    <a class="float-right">
                                        {{ $last_login ?? 'N/A' }}
                                    </a>
                                </li>
                            </ul>
                            @can('superadmin')
                            <a class="btn btn-warning btn-block" href="{{ route('accounts.edit', ['account' => $user]) }}"><b>Edit Account</b></a>
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
                                    <a class="nav-link active mx-1" href="#activity" data-toggle="tab">Latest Activity
                                    </a>
                                </li>
                            </ul>
                            <div class="ml-auto px-2">
                                {{ $logs->links() }}
                            </div>

                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="tab-pane active" id="timeline">
                                        <!-- The timeline -->
                                        <div class="timeline timeline-inverse">
                                            <!-- timeline time label -->

                                            @forelse ($logs as $log)
                                            <div>
                                                <i class="fas fa-user bg-info"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i>
                                                        {{ $log->created_at->toDayDateTimeString() }}</span>

                                                    <h3 class="timeline-header border-0">
                                                        <a>
                                                            {{ $log->type }} :
                                                        </a>
                                                        {{ $log->info }}
                                                    </h3>
                                                </div>
                                            </div>
                                            @empty
                                                <h4 class="text-center p-4">Could Not Load Logs</h4>
                                            @endforelse

                                            <div>
                                                <i class="far fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
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
