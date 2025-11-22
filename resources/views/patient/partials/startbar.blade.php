<div class="startbar patient-startbar d-print-none">
    <div class="brand">
        <a href="{{ route('patient.portal.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                <img src="{{ asset('rizz-assets/images/logo-light.png') }}" alt="logo-light" class="logo-lg logo-light">
                <img src="{{ asset('rizz-assets/images/logo-dark.png') }}" alt="logo-dark" class="logo-lg logo-dark">
            </span>
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
                            <span>Wallet &amp; Funding</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.portal.visits') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Visits &amp; Appointments</span>
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
                            <i class="iconoir-lab menu-icon"></i>
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
                            <span>Profile &amp; Contacts</span>
                        </a>
                    </li>
                </ul>
                <div class="startbar-footer">
                    <div class="card bg-primary-subtle text-center border-0 shadow-none">
                        <div class="card-body">
                            <p class="mb-1 fw-semibold">Need Help?</p>
                            @php($supportPhone = $hospitalConfig['phone'] ?? null)
                            <p class="fs-13 text-muted mb-3">
                                @if($supportPhone)
                                    Reach our care team 24/7 on {{ $supportPhone }}.
                                @else
                                    Reach our care team anytime for support.
                                @endif
                            </p>
                            <a href="#support" class="btn btn-sm btn-primary">Contact Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
