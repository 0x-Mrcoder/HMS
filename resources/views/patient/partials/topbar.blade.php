@php($patient = auth()->user()?->patient)
<div class="topbar d-print-none patient-topbar border-0 shadow-sm">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between align-items-center" id="patient-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                    <i class="iconoir-menu-scale fs-4"></i>
                </button>
                <div>
                    <p class="mb-0 text-muted small text-uppercase">My Care Hub</p>
                    <h3 class="mb-0 fw-semibold">Hello, {{ $patient?->full_name ?? 'Patient' }} 👋</h3>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <small class="text-muted">Hospital ID</small>
                    <p class="mb-0 fw-semibold">{{ $patient?->hospital_id }}</p>
                </div>
                <a href="#wallet" class="btn btn-outline-primary btn-sm d-none d-md-inline-flex align-items-center">
                    <i class="iconoir-wallet me-2"></i>Fund Wallet
                </a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user d-flex align-items-center" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ $patient?->photo_url ?? asset('rizz-assets/images/users/user-4.jpg') }}" alt="profile-user" class="thumb-lg rounded-circle me-2">
                        <div class="d-none d-sm-block text-start">
                            <h6 class="fw-semibold fs-14 mb-0">{{ $patient?->full_name }}</h6>
                            <small class="text-muted">Patient</small>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item text-center">
                            <span class="mb-2 d-inline-block">
                                <img src="{{ $patient?->photo_url ?? asset('rizz-assets/images/users/user-4.jpg') }}" alt="profile" class="thumb-lg rounded-circle">
                            </span>
                            <p class="mb-0 fs-14 fw-semibold">{{ $patient?->full_name }}</p>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <a class="dropdown-item" href="#profile"><i class="bi bi-person fs-18 align-text-bottom me-2"></i> Profile</a>
                        <a class="dropdown-item" href="#support"><i class="bi bi-life-preserver fs-18 align-text-bottom me-2"></i> Support</a>
                        <div class="dropdown-divider mb-0"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right fs-18 align-text-bottom me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
