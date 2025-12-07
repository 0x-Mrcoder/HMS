@php
    $user = auth()->user();
    $displayName = $user->name ?? 'Doctor';
    $displayEmail = $user->email ?? '';
    $photo = $user->photo_url ?? null;
    $photoUrl = $photo
        ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://', '/storage']) ? $photo : asset(ltrim($photo, '/')))
        : asset('rizz-assets/images/users/avatar-1.jpg');
@endphp
<div class="topbar d-print-none">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                        <i class="iconoir-menu-scale"></i>
                    </button>
                </li>
                <li class="mx-3 welcome-text">
                    <h3 class="mb-0 fw-bold text-truncate">Good Morning, Dr. {{ $displayName }}!</h3>
                </li>
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li class="hide-phone app-search">
                    <form role="search" action="#" method="get">
                        <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search patient...">
                        <button type="button"><i class="iconoir-search"></i></button>
                    </form>
                </li>
                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="icofont-sun dark-mode"></i>
                        <i class="icofont-moon light-mode"></i>
                    </a>
                </li>
                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="icofont-bell-alt"></i>
                        <span class="alert-badge"></span>
                    </a>
                    <div class="dropdown-menu stop dropdown-menu-end dropdown-lg py-0">
                        <h5 class="dropdown-item-text m-0 py-3 d-flex justify-content-between align-items-center">
                            Notifications <a href="#" class="badge text-body-tertiary badge-pill"><i class="iconoir-plus-circle fs-4"></i></a>
                        </h5>
                        <div class="ms-0" style="max-height:230px;" data-simplebar>
                            <div class="tab-content" id="notification-tabContent">
                                <div class="tab-pane fade show active" id="All" role="tabpanel">
                                    <!-- Placeholder Notifications -->
                                    <a href="#" class="dropdown-item p-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="bg-primary-subtle me-3 thumb-md rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="iconoir-flask text-primary fs-3"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">New Lab Results</p>
                                                <small class="text-muted">Results for Patient #123 are ready.</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="dropdown-item text-center text-primary fw-bold py-2">View all <i class="icofont-rounded-right"></i></a>
                    </div>
                </li>
                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle nav-user" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ $photoUrl }}" alt="profile-user" class="thumb-lg rounded-circle">
                            </div>
                            <div class="d-sm-flex d-none ms-2">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold fs-14 mb-0">{{ $displayName }}</h6>
                                    <small class="text-muted fw-medium">{{ $displayEmail }}</small>
                                </div>
                                <div class="ms-2 align-self-center">
                                    <i class="iconoir-nav-arrow-down text-muted fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">                        
                        <div class="dropdown-item text-center">
                            <span class="mb-2 d-inline-block">
                                <img src="{{ $photoUrl }}" alt="profile" class="thumb-lg rounded-circle">
                            </span>
                            <p class="mb-0 fs-14 fw-semibold">{{ $displayName }}</p>
                            <small class="text-muted">{{ $displayEmail }}</small>
                        </div>
                        <a class="dropdown-item" href="#"><i class="bi bi-person fs-18 align-text-bottom me-2"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="bi bi-gear fs-18 align-text-bottom me-2"></i> Settings</a>
                        <div class="dropdown-divider mb-0"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right fs-18 align-text-bottom me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
