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

                    <!-- Main ID & QR Enchanced -->
                    <div class="card bg-primary text-white border-0 shadow-sm mb-5 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="d-flex flex-column text-start">
                                        <small class="text-white-50 text-uppercase letter-spacing-1 mb-1">Card Number</small>
                                        <h2 class="display-6 fw-bold mb-0 font-monospace">{{ trim(chunk_split($patient->hospital_id, 4, ' ')) }}</h2>
                                        <div class="mt-3">
                                            <span class="badge bg-white text-primary bg-opacity-10 fw-normal pe-3 ps-2 py-1 rounded-pill">
                                                <i class="bi bi-person-badge-fill me-2"></i>Patient ID
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="bg-white p-2 rounded d-inline-block">
                                        <div id="qrcode"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Decorator -->
                            <div class="position-absolute top-0 end-0 opacity-10">
                                <i class="bi bi-hospital fs-1" style="font-size: 10rem !important; transform: rotate(-15deg) translate(20px, -20px);"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Wallet & Funding (Full Width) -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="p-4 rounded-3 bg-white border border-dashed text-center shadow-sm">
                                <h6 class="fw-bold text-success text-uppercase letter-spacing-1 mb-4">
                                    <i class="bi bi-wallet2 me-2"></i>Account Details
                                </h6>
                                <div class="row g-4 justify-content-center align-items-center">
                                    <div class="col-md-4 border-end">
                                        <small class="text-muted d-block uppercase mb-1 fs-11">Account Name</small>
                                        <span class="fw-bold text-dark d-block text-truncate px-2">{{ $patient->first_name }} {{ $patient->last_name }}</span>
                                    </div>
                                    <div class="col-md-4 border-end">
                                        <small class="text-muted d-block uppercase mb-1 fs-11">Bank Name</small>
                                        <span class="fw-bold text-dark fs-5">{{ $patient->wallet->bank_name ?? 'FCMB' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block uppercase mb-1 fs-11">Virtual Account Number</small>
                                        <span class="fs-4 fw-bold font-monospace text-primary letter-spacing-1">{{ $patient->wallet->virtual_account_number ?? 'PENDING' }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-inline-flex align-items-center gap-2 text-muted small">
                                        <i class="bi bi-info-circle-fill text-success"></i> 
                                        <span>Use these details to fund your wallet instantly via bank transfer.</span>
                                    </div>
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
