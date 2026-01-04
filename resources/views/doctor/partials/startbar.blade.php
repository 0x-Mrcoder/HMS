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
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @php
                        $isSurgeon = \App\Models\Doctor::where('user_id', Auth::id())->first()?->department?->name === 'Surgery';
                    @endphp

                    @if(!$isSurgeon)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.queue') ? 'active' : '' }}" href="{{ route('doctor.portal.queue') }}">
                            <i class="bi bi-people menu-icon"></i>
                            <span>Clinic Queue</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.appointments') ? 'active' : '' }}" href="{{ route('doctor.portal.appointments') }}">
                            <i class="bi bi-calendar-event menu-icon"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.patients.*') ? 'active' : '' }}" href="{{ route('doctor.portal.patients.index') }}">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.prescriptions') ? 'active' : '' }}" href="{{ route('doctor.portal.prescriptions') }}">
                            <i class="bi bi-capsule menu-icon"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.labs') ? 'active' : '' }}" href="{{ route('doctor.portal.labs') }}">
                            <i class="bi bi-eyedropper menu-icon"></i>
                            <span>Lab Results</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.nursing-notes') ? 'active' : '' }}" href="{{ route('doctor.portal.nursing-notes') }}">
                            <i class="bi bi-journal-text menu-icon"></i>
                            <span>Nursing Notes</span>
                        </a>
                    </li>
                    @if($isSurgeon)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.portal.theatre-requests') ? 'active' : '' }}" href="{{ route('doctor.portal.theatre-requests') }}">
                            <i class="bi bi-scissors menu-icon"></i>
                            <span>Theatre Manager</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
