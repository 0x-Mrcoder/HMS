<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('doctor.portal.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                CyberHausa New Clinic
                {{-- <img src="{{ asset('rizz-assets/images/logo-light.png') }}" alt="logo-light" class="logo-lg logo-light"> --}}
                {{-- <img src="{{ asset('rizz-assets/images/logo-dark.png') }}" alt="logo-dark" class="logo-lg logo-dark"> --}}
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
                        <a class="nav-link {{ request()->routeIs('doctor.portal.dashboard') ? 'active' : '' }}" href="{{ route('doctor.portal.dashboard') }}">
                            <i class="iconoir-dashboard menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.queue') ? 'active' : '' }}" href="{{ route('doctor.portal.queue') }}">
                            <i class="iconoir-group menu-icon"></i>
                            <span>Clinic Queue</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.appointments') ? 'active' : '' }}" href="{{ route('doctor.portal.appointments') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.patients.*') ? 'active' : '' }}" href="{{ route('doctor.portal.patients.index') }}">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.prescriptions') ? 'active' : '' }}" href="{{ route('doctor.portal.prescriptions') }}">
                            <i class="iconoir-rx menu-icon"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.labs') ? 'active' : '' }}" href="{{ route('doctor.portal.labs') }}">
                            <i class="iconoir-flask menu-icon"></i>
                            <span>Lab Results</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.nursing-notes') ? 'active' : '' }}" href="{{ route('doctor.portal.nursing-notes') }}">
                            <i class="iconoir-notes menu-icon"></i>
                            <span>Nursing Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.theatre-requests') ? 'active' : '' }}" href="{{ route('doctor.portal.theatre-requests') }}">
                            <i class="iconoir-scissors menu-icon"></i>
                            <span>Theatre Requests</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
