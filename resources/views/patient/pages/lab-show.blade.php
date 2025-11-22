@extends('layouts.patient')

@section('title', 'Lab Test Details')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Lab Test Details</h4>
            <small class="text-muted">Dept: {{ $labTest->visit?->department?->name ?? 'Laboratory' }}</small>
        </div>
        <a href="{{ route('patient.portal.labs') }}" class="btn btn-outline-secondary btn-sm">Back to labs</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Test</p>
                    <h5 class="mb-0">{{ $labTest->test_name }}</h5>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Status</p>
                    <span class="badge bg-warning-subtle text-capitalize">{{ str_replace('_', ' ', $labTest->status) }}</span>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Charge Amount</p>
                    <h6 class="mb-0">₦{{ number_format($labTest->charge_amount ?? 0, 2) }}</h6>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Result Time</p>
                    <h6 class="mb-0">{{ $labTest->result_at?->format('d M Y, h:ia') ?? 'Pending' }}</h6>
                </div>
                <div class="col-12">
                    <p class="text-muted mb-1">Result Summary</p>
                    <p class="mb-0">{{ $labTest->result_summary ?? 'Awaiting result.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
