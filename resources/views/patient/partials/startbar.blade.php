@php
    $brandLogo = $hospitalConfig['logo_path'] ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/logo-sm.png');
    $brandName = $hospitalConfig['name'] ?? 'Hospital';
@endphp
<div class="startbar patient-startbar d-print-none">
    <div class="brand">
        <a href="{{ route('patient.portal.dashboard') }}" class="logo d-flex align-items-center gap-2">
            <span>
                <img src="{{ $brandLogo }}" alt="logo" class="logo-sm" style="height:36px;">
            </span>
            <span class="fw-semibold text-dark">{{ $brandName }}</span>
        </a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse" id="patientStartbar" data-simplebar>
            <div class="d-flex flex-column h-100 w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <span>Patient Menu</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.dashboard') }}">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.wallet') }}">
                            <i class="iconoir-wallet menu-icon"></i>
                            <span>My Wallet</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.visits') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.prescriptions') }}">
                            <i class="iconoir-pill menu-icon"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.labs') }}">
                            <i class="iconoir-lab-flask menu-icon"></i>
                            <span>Lab Results</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.care-notes') }}">
                            <i class="iconoir-heartbeat menu-icon"></i>
                            <span>Care Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.profile') }}">
                            <i class="iconoir-user menu-icon"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
