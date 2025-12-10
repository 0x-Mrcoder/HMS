<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('pharmacy.portal.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                CyberHausa Pharmacy
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
                        <a class="nav-link {{ request()->routeIs('pharmacy.portal.dashboard') ? 'active' : '' }}" href="{{ route('pharmacy.portal.dashboard') }}">
                            <i class="iconoir-dashboard menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.portal.prescriptions.*') ? 'active' : '' }}" href="{{ route('pharmacy.portal.prescriptions.index') }}">
                            <i class="iconoir-rx menu-icon"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.portal.inventory.*') ? 'active' : '' }}" href="{{ route('pharmacy.portal.inventory.index') }}">
                            <i class="iconoir-box-iso menu-icon"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacy.portal.reports.*') ? 'active' : '' }}" href="{{ route('pharmacy.portal.reports.index') }}">
                            <i class="iconoir-reports menu-icon"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
