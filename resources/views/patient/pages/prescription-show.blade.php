@extends('layouts.patient')

@section('title', 'Prescription Details')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Prescription Details</h4>
            <small class="text-muted">Clinic: {{ $prescription->visit?->department?->name ?? 'N/A' }}</small>
        </div>
        <a href="{{ route('patient.portal.prescriptions') }}" class="btn btn-outline-secondary btn-sm">Back to prescriptions</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Drug</p>
                    <h5 class="mb-0">{{ $prescription->drug_name }}</h5>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Status</p>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Dosage</p>
                    <h6 class="mb-0">{{ $prescription->dosage }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Frequency</p>
                    <h6 class="mb-0">{{ $prescription->frequency }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Duration</p>
                    <h6 class="mb-0">{{ $prescription->duration }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Unit Price</p>
                    <h6 class="mb-0">₦{{ number_format($prescription->unit_price ?? 0, 2) }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Quantity</p>
                    <h6 class="mb-0">{{ $prescription->quantity ?? 1 }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Total Cost</p>
                    <h6 class="mb-0">₦{{ number_format($prescription->total_cost ?? 0, 2) }}</h6>
                </div>
                <div class="col-12">
                    <p class="text-muted mb-1">Notes</p>
                    <p class="mb-0">{{ $prescription->notes ?? 'No notes provided.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
