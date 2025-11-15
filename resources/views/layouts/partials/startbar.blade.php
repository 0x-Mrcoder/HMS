<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
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
                <div class="startbar-footer">
                    <div class="card bg-primary-subtle text-center border-0 shadow-none">
                        <div class="card-body">
                            <div class="thumb-xl mx-auto">
                                <img src="{{ asset('rizz-assets/images/widgets/lightbulb.png') }}" alt="idea" class="img-fluid d-block">
                            </div>
                            <h5>Need Help?</h5>
                            <p class="fs-13 text-muted mb-4">Take a tour through the documentation to start building your pages.</p>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Documentation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
