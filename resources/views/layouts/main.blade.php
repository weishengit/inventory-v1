<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (strpos(request()->path(), '/') > 0)
            {{ ucwords(str_replace('_', ' ', substr(request()->path(), 0, strpos(request()->path(), '/')))) }}
        @else
            {{ ucwords(str_replace('_', ' ', request()->path())); }}
        @endif
        |
        {{
            config('app.name', 'Inventory System')
        }}
    </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    {{-- Sweet Alert --}}
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">


    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('layouts.nav')

        @include('layouts.sidebar')

        @if (isset($errors))
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        @endif

        @yield('content')

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                v1.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2021 Inventory System</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
    const MainToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        showCloseButton: true,
        timer: 0,
    });
    </script>

    @if ($errors->any())
    <script>
    let requestErrors = @json($errors->all());
    requestErrors.forEach((error) => {
        MainToast.fire({
            icon: 'error',
            title: `${error}`,
            timer: 10000,
            timerProgressBar: true,
        });
    });
    </script>
    @endif

    {{-- Webhook --}}
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 0,
        })

        Echo.channel(`alert-notification`)
            .listen('UserLoggedIn', (e) => {
                Toast.fire({
                    icon: 'info',
                    title: ` ${e.user.name} has logged in.`,
                    timer: 10000,
                    timerProgressBar: true,
                })
            })
            .listen('UserLoggedOutEvent', (e) => {
                Toast.fire({
                    icon: 'info',
                    title: ` ${e.user.name} has logged out.`,
                    timer: 10000,
                    timerProgressBar: true,
                })
            });
    </script>

    @can('admin')
    <script>
    Echo.private(`admin-channel`)
        .listen('SupplierEditEvent', (e) => {
            Toast.fire({
                icon: 'info',
                title: ` ${e.supplier.company_name} was edited by ${e.editor.name}.`,
                timer: 10000,
                timerProgressBar: true,
            })
        })
        .listen('CreatedPurchaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `PO# ${e.purchaseOrder.po_num} was created by ${e.creator.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('ApprovedPurchaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `PO# ${e.purchaseOrder.po_num} was approved by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('ReceivePurchaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `PO# ${e.purchaseOrder.po_num} was received by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('ClosePurchaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `PO# ${e.purchaseOrder.po_num} was closed by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('VoidPurchaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `PO# ${e.purchaseOrder.po_num} was voided by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('CreateReleaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `RO# ${e.releaseOrder.ro_num} was created by ${e.creator.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('ApproveReleaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `RO# ${e.releaseOrder.ro_num} was approved by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('ReleaseReleaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `RO# ${e.releaseOrder.ro_num} was released by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('CloseReleaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `RO# ${e.releaseOrder.ro_num} was closed by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
        .listen('VoidReleaseOrder', (e) => {
            console.log(e);
            Toast.fire({
                icon: 'info',
                title: `RO# ${e.releaseOrder.ro_num} was voided by ${e.approver.name}.`,
                timer: 30000,
                timerProgressBar: true,
            })
        })
    </script>
    @endcan

    @yield('scripts')
</body>

</html>
