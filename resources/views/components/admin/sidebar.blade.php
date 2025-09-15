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

    <!-- Sidebar Menu -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                <!-- Dashboard -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}"><i class="ti ti-layout-grid fs-16 me-2"></i>Dashboard</a></li>
                    </ul>
                </li>

                <!-- Brands -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Brands</h6>
                    <ul>
                        <li><a href="{{ route('admin.brand.index') }}">All Brands</a></li>
                        <li><a href="{{ route('admin.brand.create') }}">Create Brand</a></li>
                    </ul>
                </li>

                <!-- Products -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Products</h6>
                    <ul>
                        <li><a href="{{ route('admin.product.index') }}">All Products</a></li>
                        <li><a href="{{ route('admin.product.create') }}">Create Product</a></li>
                    </ul>
                </li>

                <!-- Suppliers -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Suppliers</h6>
                    <ul>
                        <li><a href="{{ route('admin.supplier.index') }}">All Suppliers</a></li>
                        <li><a href="{{ route('admin.supplier.create') }}">Create Supplier</a></li>
                    </ul>
                </li>

                <!-- Customers -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Customers</h6>
                    <ul>
                        <li><a href="{{ route('admin.customer.index') }}">All Customers</a></li>
                        <li><a href="{{ route('admin.customer.create') }}">Create Customer</a></li>
                    </ul>
                </li>

                <!-- Purchases -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li><a href="{{ route('admin.purchase.index') }}">All Purchases</a></li>
                        <li><a href="{{ route('admin.purchase.create') }}">Create Purchase</a></li>
                    </ul>
                </li>

                <!-- Purchase Returns -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchase Returns</h6>
                    <ul>
                        <li><a href="{{ route('admin.purchase_return.index') }}">All Returns</a></li>
                        <li><a href="{{ route('admin.purchase_return.create') }}">Create Return</a></li>
                    </ul>
                </li>

                <!-- Sales -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li><a href="{{ route('admin.sale.index') }}">All Sales (POS)</a></li>
                        <li><a href="{{ route('admin.sale.create') }}">Create Sale</a></li>
                    </ul>
                </li>

                <!-- Sale Returns -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sale Returns</h6>
                    <ul>
                        <li><a href="{{ route('admin.sale_return.index') }}">All Sale Returns</a></li>
                        <li><a href="{{ route('admin.sale_return.create') }}">Create Sale Return</a></li>
                    </ul>
                </li>

                <!-- Balance -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Balance</h6>
                    <ul>
                        <li><a href="{{ route('admin.balance.index') }}">All Balances</a></li>
                        {{-- Optional: edit balance requires an {id}, so no direct link --}}
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
