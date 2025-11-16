<div class="startbar doctor-startbar d-print-none">
    <div class="brand">
        <a href="{{ route('doctor.portal.dashboard') }}" class="logo">cyber
            <span><img src="{{ asset('rizz-assets/images/logo-sm.png') }}" class="logo-sm" alt="logo-small"></span>
            <span>
                <img src="{{ asset('rizz-assets/images/logo-light.png') }}" class="logo-lg logo-light" alt="logo-light">
                <img src="{{ asset('rizz-assets/images/logo-dark.png') }}" class="logo-lg logo-dark" alt="logo-dark">
            </span>
        </a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse" data-simplebar>
            <div class="d-flex flex-column h-100 w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0"><span>Doctor Menu</span></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#clinic-queue">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#clinic-queue">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Clinic Queue</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#appointments">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#prescriptions">
                            <i class="iconoir-pill menu-icon"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#labs">
                            <i class="iconoir-lab menu-icon"></i>
                            <span>Lab Results</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#nursing">
                            <i class="iconoir-heartbeat menu-icon"></i>
                            <span>Nursing Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.portal.dashboard') }}#theatre">
                            <i class="iconoir-scissors menu-icon"></i>
                            <span>Theatre Requests</span>
                        </a>
                    </li>
                </ul>
                <div class="startbar-footer">
                    <div class="card bg-primary-subtle border-0 text-center">
                        <div class="card-body">
                            <p class="mb-1 fw-semibold">Need Assistance?</p>
                            <p class="text-muted small mb-3">Contact the medical director for escalations.</p>
                            <a href="mailto:support@hms.com" class="btn btn-sm btn-primary">Email Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
