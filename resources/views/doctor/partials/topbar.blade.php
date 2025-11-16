@php($user = auth()->user())
<div class="topbar d-print-none doctor-topbar shadow-sm">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                    <i class="iconoir-menu-scale fs-4"></i>
                </button>
                <div>
                    <small class="text-muted text-uppercase">Consulting Physician</small>
                    <h3 class="mb-0 fw-semibold">Good day, {{ $user?->name ?? 'Doctor' }}!</h3>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="#clinic-queue" class="btn btn-soft-primary btn-sm"><i class="iconoir-clock me-1"></i>Today's Clinic</a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('rizz-assets/images/users/user-5.jpg') }}" class="thumb-lg rounded-circle me-2" alt="doctor">
                            <div class="text-start d-none d-sm-block">
                                <h6 class="mb-0 fw-semibold">{{ $user?->name }}</h6>
                                <small class="text-muted">{{ $user?->email }}</small>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#clinic-queue"><i class="bi bi-calendar4-week me-2"></i>Clinic Schedule</a>
                        <a class="dropdown-item" href="#labs"><i class="bi bi-flask me-2"></i>Lab Requests</a>
                        <a class="dropdown-item" href="#prescriptions"><i class="bi bi-capsule me-2"></i>Prescriptions</a>
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
