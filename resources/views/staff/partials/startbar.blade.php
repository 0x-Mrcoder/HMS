<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('staff.portal.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                Front Desk
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
                        <a class="nav-link {{ request()->routeIs('staff.portal.dashboard') ? 'active' : '' }}" href="{{ route('staff.portal.dashboard') }}">
                            <i class="iconoir-dashboard menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.portal.patients.index') ? 'active' : '' }}" href="{{ route('staff.portal.patients.index') }}">
                            <i class="iconoir-group menu-icon"></i>
                            <span>Manage Patients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.portal.appointments.index') ? 'active' : '' }}" href="{{ route('staff.portal.appointments.index') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Schedule</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.portal.appointments.new') ? 'active' : '' }}" href="{{ route('staff.portal.appointments.new') }}">
                            <i class="iconoir-plus-square menu-icon"></i>
                            <span>New Booking</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('staff.portal.patients.create') ? 'active' : '' }}" href="{{ route('staff.portal.patients.create') }}">
                            <i class="iconoir-add-user menu-icon"></i>
                            <span>Register New</span>
                        </a>
                    </li>
                    <!-- Future: Book Appointment -->
                </ul>
            </div>
        </div>
    </div>
</div>
