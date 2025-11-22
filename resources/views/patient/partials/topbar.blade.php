@php
    $patient = auth()->user()?->patient ?? $patient ?? null;
    $photo = $patient?->photo_url;
    $photoUrl = $photo
        ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://'])
            ? $photo
            : asset(ltrim($photo, '/')))
        : asset('rizz-assets/images/users/user-4.jpg');
    $firstInitial = $patient?->first_name ? strtoupper(mb_substr($patient->first_name, 0, 1)) : '';
    $lastInitial = $patient?->last_name ? strtoupper(mb_substr($patient->last_name, 0, 1)) : '';
    $initials = trim($firstInitial . $lastInitial) ?: 'P';
@endphp
<div class="topbar d-print-none patient-topbar border-0 shadow-sm">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between align-items-center py-2 px-2 px-md-0" id="patient-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="nav-link mobile-menu-btn nav-icon d-flex align-items-center justify-content-center" id="togglemenu" style="width:38px;height:38px;">
                    <i class="iconoir-menu-scale fs-4"></i>
                </button>
                <div class="lh-sm">
                    <p class="mb-1 text-muted small text-uppercase">My Care Hub</p>
                    <h5 class="mb-0 fw-semibold">Hello, {{ $patient?->full_name ?? 'Patient' }} 👋</h5>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle nav-user d-flex align-items-center px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width:40px;height:40px;font-weight:700; font-size:14px; position:relative; overflow:hidden;">
                            @if($photo)
                                <img src="{{ $photoUrl }}" alt="profile-user" class="w-100 h-100 rounded-circle" style="object-fit:cover; position:absolute; inset:0;">
                            @else
                                {{ $initials }}
                            @endif
                        </span>
                        <div class="d-none d-sm-block text-start">
                            <h6 class="fw-semibold fs-14 mb-0">{{ $patient?->full_name }}</h6>
                            <small class="text-muted">Patient</small>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item text-center">
                            <span class="mb-2 d-inline-block">
                                <span class="thumb-lg rounded-circle d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width:64px;height:64px;font-weight:700; font-size:18px; position:relative; overflow:hidden;">
                                    @if($photo)
                                        <img src="{{ $photoUrl }}" alt="profile" class="w-100 h-100 rounded-circle" style="object-fit:cover; position:absolute; inset:0;">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </span>
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
