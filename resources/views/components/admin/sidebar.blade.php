<div class="sidebar" id="sidebar">
    <!-- Sidebar Logo Section -->
    <div class="sidebar-logo active">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
            <img src="/assets/img/logo.svg" alt="Logo">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo logo-white">
            <img src="/assets/img/logo-white.svg" alt="Logo">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo-small">
            <img src="/assets/img/logo-small.png" alt="Logo">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>

    <!-- User Profile Section -->
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="/assets/img/customer/customer15.jpg" alt="User Avatar" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-14 fw-bold mb-1">Dummy</h6>
            <p class="fs-12 mb-0">Admin</p>
        </div>
        <div class="sidebar-nav mb-3">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active border-0" href="#">Menu</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Sidebar Header Profile -->
    <div class="sidebar-header p-3 pb-0 pt-2">
        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
            <div class="avatar avatar-md online">
                <img src="/assets/img/customer/customer15.jpg" alt="User Avatar" class="img-fluid rounded-circle">
            </div>
            <div class="text-start sidebar-profile-info ms-2">
                <h6 class="fs-14 fw-bold mb-1">Dummy</h6>
                <p class="fs-12">Admin</p>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                <!-- Main Dashboard -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li>
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="ti ti-layout-grid fs-16 me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Inventory Menu -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li><a href="{{ route('admin.product.index') }}"><i class="ti ti-box fs-16 me-2"></i><span>Products</span></a></li>
                        <li><a href="{{ route('admin.brand.index') }}"><i class="ti ti-triangles fs-16 me-2"></i><span>Brands</span></a></li>
                    </ul>
                </li>

                <!-- Purchases Menu -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li><a href="{{ route('admin.purchase.index') }}"><i class="ti ti-shopping-bag fs-16 me-2"></i><span>Purchases</span></a></li>
                        <li><a href="{{ route('admin.purchase_return.index') }}"><i class="ti ti-refresh fs-16 me-2"></i><span>Purchase Returns</span></a></li>
                    </ul>
                </li>

                <!-- Peoples Menu -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li><a href="{{ route('admin.supplier.index') }}"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Suppliers</span></a></li>
                        <li><a href="{{ route('admin.customer.index') }}"><i class="ti ti-users-group fs-16 me-2"></i><span>Customers</span></a></li>
                    </ul>
                </li>

                <!-- Sales Menu -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li><a href="{{ route('admin.sale.index') }}"><i class="ti ti-device-laptop fs-16 me-2"></i><span>POS</span></a></li>
                        <li><a href="{{ route('admin.sale_return.index') }}"><i class="ti ti-rotate fs-16 me-2"></i><span>Sale Returns</span></a></li>
                    </ul>
                </li>

                <!-- Reports Menu (commented out) -->
                {{--
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li><a href="{{ route('report.sale') }}"><i class="ti ti-chart-bar fs-16 me-2"></i><span>Sale Report</span></a></li>
                        <li><a href="{{ route('report.purchase') }}"><i class="ti ti-chart-bar fs-16 me-2"></i><span>Purchase Report</span></a></li>
                    </ul>
                </li>
                --}}

            </ul>
        </div>
    </div>
</div>
