@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Logs</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Logs</li>
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
                    <div class="card">
                        <div class="card-header p-2 d-flex">
                            <div class="ml-auto px-2">
                                {{ $logs->links() }}
                            </div>
                        </div><!-- /.card-header -->

                        <div class="card-body">
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
                                <!-- /.tab-pane -->
                            </div>
                        </div><!-- /.card-body -->

                    </div><!-- /.card -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

</div><!-- /.content-wrapper -->

@endsection
