@php($user = auth()->user())
<div class="topbar d-print-none pharmacy-topbar shadow-sm">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                    <i class="iconoir-menu-scale fs-4"></i>
                </button>
                <div>
                    <small class="text-muted text-uppercase">Pharmacy Desk</small>
                    <h3 class="mb-0 fw-semibold">Hello, {{ $user?->name ?? 'Pharmacist' }}!</h3>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="#dispense" class="btn btn-soft-primary btn-sm"><i class="iconoir-pill me-1"></i>Dispense Queue</a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('rizz-assets/images/users/user-6.jpg') }}" class="thumb-lg rounded-circle me-2" alt="pharmacy">
                            <div class="text-start d-none d-sm-block">
                                <h6 class="mb-0 fw-semibold">{{ $user?->name }}</h6>
                                <small class="text-muted">{{ $user?->email }}</small>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#pending"><i class="bi bi-clipboard-check me-2"></i>Pending Orders</a>
                        <a class="dropdown-item" href="#inventory"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
