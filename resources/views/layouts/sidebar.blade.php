<!-- Main Sidebar Container -->
<aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('dist/img/InvLogo.png') }}" alt="Inventory Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Inventory System') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(false)
                    <img
                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                        class="img-circle elevation-2"
                        alt="User Image"
                        style="object-fit: contain;
                        object-position: center;
                        max-height: 100px;
                        margin-bottom: 1rem;">
                @else
                    <img
                    src="https://img.icons8.com/fluency-systems-filled/48/000000/guest-male.png"
                    class="img-circle elevation-2"
                    alt="User Image"
                    style="object-fit: contain;
                    object-position: center;
                    max-height: 100px;
                    margin-bottom: 1rem;">
                    {{-- <img src="{{ asset('dist/img/default-150x150.png') }}" class="img-circle elevation-2" alt="User Image"> --}}
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                <small class="d-block text-light">{{ auth()->user()->role->name }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- Info --}}
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon far fa-keyboard"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('new.index') }}" class="nav-link">
                        <i class="nav-icon far fa-newspaper"></i>
                        <p>
                            What's New?
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>

                {{-- ADMIN --}}
                @can('admin')
                <li class="nav-item menu-closed">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('accounts.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Accounts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('suppliers.index') }}" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('types.index') }}" class="nav-link">
                                <i class="fas fa-sitemap nav-icon"></i>
                                <p>Types</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="nav-item menu-closed">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Manage Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('items.index') }}" class="nav-link">
                                <i class="fas fa-box nav-icon"></i>
                                <p>View Items</p>
                            </a>
                        </li>
                        <li class="nav-item menu-closed">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-invoice nav-icon"></i>
                                <p>
                                    View Orders
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('purchase_orders.index') }}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Purchase Order</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('release_orders.index') }}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Release Order</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase_orders.create') }}" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Create Purchase Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('release_orders.create') }}" class="nav-link">
                                <i class="fas fa-minus nav-icon"></i>
                                <p>Create Release Order</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- STATS --}}
                <li class="nav-item menu-closed">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Inventory Statistics
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link menu-closed">
                                <i class="fas fa-dolly-flatbed nav-icon"></i>
                                <p>Inventory Movement<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('inventory.incoming') }}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Incoming</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('inventory.outgoing') }}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>Outgoing</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('report.statistics') }}" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Item Statistics</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- LOGS --}}
                <li class="nav-item">
                    <a href="{{ route('logs') }}" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Logs
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

    <div class="sidebar-custom">
        <form action="{{ route('logout') }}" method="POST">
        @csrf
            <button class="btn btn-block btn-danger " type="submit">
                <i class="nav-icon fas fa-book"></i>
                Logout
            </button>
        </form>
    </div>
    <!-- /.sidebar -->
</aside>
