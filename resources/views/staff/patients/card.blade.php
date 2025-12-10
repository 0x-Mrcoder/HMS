@extends('layouts.staff')

@section('title', 'Patient Registration Slip')

@section('content')
<div class="container-xxl">
    <!-- Action Bar -->
    <div class="row mb-4 d-print-none">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="{{ route('staff.portal.patients.index') }}" class="btn btn-outline-secondary">
                <i class="iconoir-arrow-left me-1"></i> Back to List
            </a>
            <div>
                @if($password)
                    <div class="alert alert-warning d-inline-block py-1 px-3 mb-0 me-3">
                        <i class="iconoir-warning-triangle me-1"></i>
                        Values are sensitive. Print immediately.
                    </div>
                @endif
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="iconoir-printer me-1"></i> Print Slip
                </button>
                <a href="{{ route('staff.portal.patients.create') }}" class="btn btn-secondary ms-2">
                    <i class="iconoir-plus-circle me-1"></i> Register Another
                </a>
            </div>
        </div>
    </div>

    <!-- Printable Area -->
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-none border print-border">
                <div class="card-body p-5">
                    
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                            <img src="{{ asset('rizz-assets/images/logo-sm.png') }}" alt="Logo" height="60">
                            <div class="text-start">
                                <h2 class="mb-0 fw-bold text-dark">HMS Hospital</h2>
                                <p class="text-muted mb-0">Excellence in Healthcare</p>
                            </div>
                        </div>
                        <h5 class="text-uppercase letter-spacing-2 fw-bold mt-4">Patient Registration Slip</h5>
                        <p class="text-muted small">Generated on {{ now()->format('F d, Y h:i A') }}</p>
                    </div>

                    <!-- Main ID & QR -->
                    <div class="row align-items-center mb-5 border-bottom pb-5">
                        <div class="col-8">
                            <h6 class="text-uppercase text-muted letter-spacing-1 mb-1">Hospital ID</h6>
                            <h1 class="display-4 fw-bold text-primary mb-0">{{ $patient->hospital_id }}</h1>
                        </div>
                        <div class="col-4 text-end">
                            <!-- JS Generated QR Code Container -->
                            <div id="qrcode" class="d-inline-block border p-2 rounded"></div>
                        </div>
                    </div>

                    <!-- Credentials & Wallet (The "Value" Section) -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 bg-light border border-dashed">
                                <h6 class="fw-bold text-danger mb-3">
                                    <i class="iconoir-lock-key me-2"></i>Login Credentials
                                </h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block uppercase">Portal URL</small>
                                    <span class="fw-medium font-monospace">http://hospital.com/login</span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted d-block uppercase">Username / Email</small>
                                    <span class="fw-bold">{{ $patient->email }}</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block uppercase">Password</small>
                                    @if($password)
                                        <span class="fs-4 fw-bold font-monospace bg-white px-2 rounded border">{{ $password }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Standard Security (Hidden)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 bg-light border border-dashed">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="iconoir-wallet me-2"></i>Wallet & Funding
                                </h6>
                                <div class="mb-2">
                                    <small class="text-muted d-block uppercase">Bank Name</small>
                                    <span class="fw-bold">{{ $patient->wallet->bank_name ?? 'CyberBank' }}</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block uppercase">Virtual Account Number</small>
                                    <span class="fs-4 fw-bold font-monospace text-dark">{{ $patient->wallet->virtual_account_number ?? 'PENDING' }}</span>
                                </div>
                                <div class="mt-2 text-muted small">
                                    <i class="iconoir-info-circle me-1"></i> Transfer to this account to fund wallet.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Details Grid -->
                    <h6 class="text-uppercase text-muted border-bottom pb-2 mb-4">Patient Information</h6>
                    <div class="row g-3 mb-5">
                        <div class="col-6 col-sm-4">
                            <small class="text-muted d-block uppercase">Full Name</small>
                            <span class="fw-medium">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                        </div>
                        <div class="col-6 col-sm-4">
                            <small class="text-muted d-block uppercase">Date of Birth</small>
                            <span class="fw-medium">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-6 col-sm-4">
                            <small class="text-muted d-block uppercase">Gender</small>
                            <span class="fw-medium">{{ ucfirst($patient->gender) }}</span>
                        </div>
                        <div class="col-6 col-sm-4">
                            <small class="text-muted d-block uppercase">Phone</small>
                            <span class="fw-medium">{{ $patient->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="col-12 col-sm-8">
                            <small class="text-muted d-block uppercase">Address</small>
                            <span class="fw-medium">{{ $patient->address ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-auto pt-5 border-top">
                        <div class="row align-items-end">
                            <div class="col-6">
                                <p class="mb-1 text-muted small">Processed By</p>
                                <p class="fw-bold mb-0">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <div style="border-bottom: 2px solid #eee; width: 150px; display: inline-block; margin-bottom: 5px;"></div>
                                <p class="mb-0 text-muted small">Authorized Signature</p>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <small class="text-muted">HMS Hospital System &bull; {{ date('Y') }}</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Library (Lightweight) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Generate QR Code with Patient ID and basic info
        var qrData = "ID:{{ $patient->hospital_id }}|Name:{{ $patient->first_name }} {{ $patient->last_name }}";
        new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 100,
            height: 100,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-border, .print-border * {
            visibility: visible;
        }
        .print-border {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection
