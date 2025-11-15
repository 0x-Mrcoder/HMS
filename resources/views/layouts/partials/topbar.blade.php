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
                    <h3 class="mb-0 fw-bold text-truncate">Good Morning, James!</h3>
                </li>
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li class="hide-phone app-search">
                    <form role="search" action="#" method="get">
                        <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search here...">
                        <button type="button"><i class="iconoir-search"></i></button>
                    </form>
                </li>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('rizz-assets/images/flags/us_flag.jpg') }}" alt="" class="thumb-sm rounded-circle">
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#"><img src="{{ asset('rizz-assets/images/flags/us_flag.jpg') }}" alt="" height="15" class="me-2">English</a>
                        <a class="dropdown-item" href="#"><img src="{{ asset('rizz-assets/images/flags/spain_flag.jpg') }}" alt="" height="15" class="me-2">Spanish</a>
                        <a class="dropdown-item" href="#"><img src="{{ asset('rizz-assets/images/flags/germany_flag.jpg') }}" alt="" height="15" class="me-2">German</a>
                        <a class="dropdown-item" href="#"><img src="{{ asset('rizz-assets/images/flags/french_flag.jpg') }}" alt="" height="15" class="me-2">French</a>
                    </div>
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
                        <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-1" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link mx-0 active" data-bs-toggle="tab" href="#All" role="tab" aria-selected="true">
                                    All <span class="badge bg-primary-subtle text-primary badge-pill ms-1">24</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link mx-0" data-bs-toggle="tab" href="#Projects" role="tab" aria-selected="false">
                                    Projects
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link mx-0" data-bs-toggle="tab" href="#Teams" role="tab" aria-selected="false">
                                    Team
                                </a>
                            </li>
                        </ul>
                        <div class="ms-0" style="max-height:230px;" data-simplebar>
                            <div class="tab-content" id="notification-tabContent">
                                <div class="tab-pane fade show active" id="All" role="tabpanel">
                                    <a href="#" class="dropdown-item p-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="bg-primary me-3 thumb-md rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="iconoir-bag-check text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">Your order is placed</p>
                                                <small class="text-muted">Dummy text of the printing and typesetting industry.</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item py-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="d-flex align-items-center bg-secondary-subtle me-3 thumb-md rounded-circle justify-content-center">
                                                    <img src="{{ asset('rizz-assets/images/users/user-4.jpg') }}" alt="" class="thumb-sm rounded-circle">
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">Meeting with designers</p>
                                                <small class="text-muted">It is a long established fact that a reader.</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item py-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="bg-primary-subtle me-3 thumb-md rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="iconoir-telegram-circled text-primary fs-3"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">Receiving New Messages</p>
                                                <small class="text-muted">Whenever you need a job done.</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-pane fade" id="Projects" role="tabpanel">
                                    <a href="#" class="dropdown-item py-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="bg-primary me-3 thumb-md rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="iconoir-task-list text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">New Project Approved</p>
                                                <small class="text-muted">Dummy text of the printing and typesetting industry.</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-pane fade" id="Teams" role="tabpanel">
                                    <a href="#" class="dropdown-item py-3 notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="notify">
                                                <div class="bg-secondary-subtle me-3 thumb-md rounded-circle d-flex align-items-center justify-content-center">
                                                    <img src="{{ asset('rizz-assets/images/users/user-5.jpg') }}" alt="" class="thumb-sm rounded-circle">
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">Kristina joined the team</p>
                                                <small class="text-muted">It is a long established fact that a reader.</small>
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
                                <img src="{{ asset('rizz-assets/images/users/user-4.jpg') }}" alt="profile-user" class="thumb-lg rounded-circle">
                            </div>
                            <div class="d-sm-flex d-none ms-2">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold fs-14 mb-0">James</h6>
                                    <small class="text-muted fw-medium">Admin</small>
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
                                <img src="{{ asset('rizz-assets/images/users/user-4.jpg') }}" alt="profile" class="thumb-lg rounded-circle">
                            </span>
                            <p class="mb-0 fs-14 fw-semibold">James Warner</p>
                            <small class="text-muted">James@example.com</small>
                        </div>
                        <a class="dropdown-item" href="javascript:void(0);"><i class="bi bi-person fs-18 align-text-bottom me-2"></i> Profile</a>
                        <a class="dropdown-item" href="javascript:void(0);"><i class="bi bi-chat-left-text fs-18 align-text-bottom me-2"></i> Messages</a>
                        <a class="dropdown-item" href="javascript:void(0);"><i class="bi bi-gear fs-18 align-text-bottom me-2"></i> Settings</a>
                        <a class="dropdown-item" href="javascript:void(0);"><i class="bi bi-lock fs-18 align-text-bottom me-2"></i> Lock screen</a>
                        <div class="dropdown-divider mb-0"></div>
                        <a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-right fs-18 align-text-bottom me-2"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
