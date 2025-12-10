<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('lab.portal.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                CyberHausa Lab
            </span>
        </a>
    </div>
    <div class="startbar-menu" >
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-center flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <span>Main Menu</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lab.portal.dashboard') ? 'active' : '' }}" href="{{ route('lab.portal.dashboard') }}">
                            <i class="iconoir-dashboard menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lab.portal.requests.*') ? 'active' : '' }}" href="#">
                            <i class="iconoir-test-tube menu-icon"></i>
                            <span>Test Requests</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lab.portal.results.*') ? 'active' : '' }}" href="{{ route('lab.portal.requests.index', ['status' => 'in_progress']) }}">
                            <i class="iconoir-page-edit menu-icon"></i>
                            <span>Results Entry</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lab.portal.reports.*') ? 'active' : '' }}" href="{{ route('lab.portal.reports.index') }}">
                            <i class="iconoir-reports menu-icon"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
