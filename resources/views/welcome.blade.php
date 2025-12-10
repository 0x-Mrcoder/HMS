<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Hospital Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('rizz-assets/images/favicon.ico') }}">
    <link href="{{ asset('rizz-assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/app.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/iconoir@7.7.0/css/iconoir.css">
</head>

<body class="bg-light">

    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 text-center py-5">
                <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="logo" height="60" class="mb-3">
                <h1 class="fw-bold text-dark">Hospital Management System</h1>
                <p class="text-muted fs-16">Select a portal to login to your dashboard.</p>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            
            <!-- Patient Portal -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-primary-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-user-heart fs-32 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Patient Portal</h4>
                        <p class="text-muted">Access your medical records, appointments, and wallet.</p>
                        <a href="{{ route('portal.login.patient') }}" class="btn btn-primary w-100">Login as Patient</a>
                        <div class="mt-3">
                            <a href="{{ route('staff.portal.patients.create') }}" class="text-muted small text-decoration-underline">Not registered? Visit Front Desk</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff / Front Desk -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-info-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-computer fs-32 text-info"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Front Desk</h4>
                        <p class="text-muted">Manage patient registrations, appointments, and check-ins.</p>
                        <a href="{{ route('portal.login.staff') }}" class="btn btn-info text-white w-100">Login as Staff</a>
                    </div>
                </div>
            </div>

            <!-- Doctor Portal -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-success-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-stethoscope fs-32 text-success"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Doctor Portal</h4>
                        <p class="text-muted">Manage consultations, diagnoses, and prescriptions.</p>
                        <a href="{{ route('portal.login.doctor') }}" class="btn btn-success w-100">Login as Doctor</a>
                    </div>
                </div>
            </div>

             <!-- Pharmacy Portal -->
             <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-warning-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-medicine-bottle fs-32 text-warning"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Pharmacy</h4>
                        <p class="text-muted">Dispense medication and manage drug inventory.</p>
                        <a href="{{ route('portal.login.pharmacy') }}" class="btn btn-warning text-white w-100">Login as Pharmacist</a>
                    </div>
                </div>
            </div>

            <!-- Laboratory Portal -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-danger-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-test-tube fs-32 text-danger"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Laboratory</h4>
                        <p class="text-muted">Process tests and generate results.</p>
                        <a href="{{ route('portal.login.laboratory') }}" class="btn btn-danger w-100">Login as Lab Scientist</a>
                    </div>
                </div>
            </div>

            <!-- Admin Portal -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 mb-0 shadow-sm hover-scale border-0">
                    <div class="card-body text-center p-4">
                        <div class="thumb-xl bg-dark-subtle rounded-circle mx-auto mb-4 d-flex justify-content-center align-items-center">
                            <i class="iconoir-settings fs-32 text-dark"></i>
                        </div>
                        <h4 class="fw-bold mt-0">Admin</h4>
                        <p class="text-muted">Manage system settings, users, and finances.</p>
                        <a href="{{ route('portal.login.admin') }}" class="btn btn-dark w-100">Login as Admin</a>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center text-muted">
                <p>&copy; {{ date('Y') }} Hospital Management System. All rights reserved.</p>
            </div>
        </div>
    </div>

</body>

</html>
