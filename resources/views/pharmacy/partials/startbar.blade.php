<div class="startbar pharmacy-startbar d-print-none">
    <div class="brand">
        <a href="{{ route('pharmacy.portal.dashboard') }}" class="logo">
            <span><img src="{{ asset('rizz-assets/images/logo-sm.png') }}" class="logo-sm" alt=""></span>
            <span>
                <img src="{{ asset('rizz-assets/images/logo-light.png') }}" class="logo-lg logo-light" alt="">
                <img src="{{ asset('rizz-assets/images/logo-dark.png') }}" class="logo-lg logo-dark" alt="">
            </span>
        </a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse" data-simplebar>
            <div class="d-flex flex-column h-100 w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0"><span>Pharmacy Menu</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.portal.dashboard') }}#overview">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.portal.dashboard') }}#dispense">
                            <i class="iconoir-pill menu-icon"></i>
                            <span>Dispense Queue</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.portal.dashboard') }}#visits">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Patient Visits</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.portal.dashboard') }}#recent-dispensed">
                            <i class="iconoir-check-circle menu-icon"></i>
                            <span>Recent Dispense</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.portal.dashboard') }}#inventory">
                            <i class="iconoir-box menu-icon"></i>
                            <span>Inventory Alerts</span>
                        </a>
                    </li>
                </ul>
                <div class="startbar-footer">
                    <div class="card bg-info-subtle border-0 text-center">
                        <div class="card-body">
                            <p class="fw-semibold mb-1">Need Supplies?</p>
                            <p class="text-muted small mb-3">Contact procurement to restock essential drugs.</p>
                            <a href="mailto:procurement@hms.com" class="btn btn-sm btn-info text-white">Contact Procurement</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
