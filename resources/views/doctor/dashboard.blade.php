@extends('layouts.doctor')

@section('title', 'Doctor Workspace')

@section('content')
<div class="container-xxl py-4">
    <div class="row g-3" id="overview">
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Today's Appointments</p>
                    <h3 class="mb-0">{{ number_format($metrics['today_appointments']) }}</h3>
                    <span class="badge bg-info-subtle text-info mt-2">Clinic list</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Active Patients</p>
                    <h3 class="mb-0">{{ number_format($metrics['active_patients']) }}</h3>
                    <span class="badge bg-success-subtle text-success mt-2">In progress</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Prescriptions</p>
                    <h3 class="mb-0">{{ number_format($metrics['pending_prescriptions']) }}</h3>
                    <span class="badge bg-warning-subtle text-warning mt-2">Awaiting review</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Lab Results</p>
                    <h3 class="mb-0">{{ number_format($metrics['pending_labs']) }}</h3>
                    <span class="badge bg-danger-subtle text-danger mt-2">Follow-up</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1" id="clinic-queue">
        <div class="col-lg-8" id="appointments">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Clinic Queue</h5>
                        <small class="text-muted">Patients waiting for consultation</small>
                    </div>
                    <button class="btn btn-soft-primary btn-sm">Start next consult</button>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($activeQueue as $visit)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $visit->patient->full_name }}</p>
                                        <small class="text-muted">{{ $visit->department?->name }} · {{ $visit->scheduled_at?->format('h:ia') ?? 'Walk-in' }}</small>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No patients waiting.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Visits</h5>
                    <button class="btn btn-soft-secondary btn-sm">View calendar</button>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($visits as $visit)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $visit->patient->full_name }}</p>
                                    <small class="text-muted">{{ $visit->department?->name }} · {{ $visit->scheduled_at?->format('D, h:ia') ?? 'Pending' }}</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                            </li>
                        @empty
                            <li class="text-center text-muted py-4">No scheduled visits.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6" id="prescriptions">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Prescription Board</h5>
                    <button class="btn btn-soft-secondary btn-sm">Create Rx</button>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($pendingPrescriptions as $prescription)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $prescription->drug_name }}</p>
                                        <small class="text-muted">{{ $prescription->visit->patient->full_name }} · {{ $prescription->dosage }} {{ $prescription->frequency }}</small>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No prescriptions waiting.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6" id="labs">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laboratory Feed</h5>
                    <button class="btn btn-soft-secondary btn-sm">Order test</button>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($recentLabTests as $test)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $test->test_name }}</p>
                                        <small class="text-muted">{{ $test->visit->patient->full_name }} · {{ $test->result_summary ?? 'Awaiting summary' }}</small>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No lab updates.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
