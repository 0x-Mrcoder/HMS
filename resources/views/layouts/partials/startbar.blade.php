<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
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
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <span>Main Menu</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patients.index') }}">
                            <i class="iconoir-user-badge menu-icon"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('visits.index') }}">
                            <i class="iconoir-stethoscope menu-icon"></i>
                            <span>Visits &amp; Clinical</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pharmacy.prescriptions.index') }}">
                            <i class="iconoir-pill menu-icon"></i>
                            <span>Pharmacy</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('laboratory.tests.index') }}">
                            <i class="iconoir-lab menu-icon"></i>
                            <span>Laboratory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('nursing.notes.index') }}">
                            <i class="iconoir-heartbeat menu-icon"></i>
                            <span>Nursing</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('theatre.surgeries.index') }}">
                            <i class="iconoir-scissors menu-icon"></i>
                            <span>Theatre</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('insurance.claims.index') }}">
                            <i class="iconoir-hospital menu-icon"></i>
                            <span>Insurance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('accounts.index') }}">
                            <i class="iconoir-receipt-pound menu-icon"></i>
                            <span>Accounts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('administration.index') }}">
                            <i class="iconoir-group menu-icon"></i>
                            <span>Administration</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">
                            <i class="iconoir-coins menu-icon"></i>
                            <span>Wallet Transactions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="iconoir-lock-key-hole menu-icon"></i>
                            <span>Auth Preview</span>
                        </a>
                    </li>
                </ul>
                
            </div>
        </div>
    </div>
</div>
