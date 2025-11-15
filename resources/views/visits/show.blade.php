@extends('layouts.admin')

@section('title', 'Visit #' . $visit->id)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase text-muted mb-1">Patient</p>
                    <h4 class="mb-1">{{ $visit->patient->full_name }}</h4>
                    <p class="text-muted">{{ $visit->patient->hospital_id }} · {{ $visit->patient->phone }}</p>
                    <hr>
                    <p class="text-uppercase text-muted mb-1">Department</p>
                    <h5>{{ $visit->department->name }}</h5>
                    <p class="text-muted mb-0">{{ $visit->service?->name ?? 'Custom service' }}</p>
                    <hr>
                    <p class="text-muted mb-1">Assigned doctor</p>
                    <p class="fw-semibold">{{ $visit->doctor_name ?? 'Awaiting assignment' }}</p>
                    <p class="text-muted mb-0">Visit Type: <span class="text-uppercase">{{ $visit->visit_type }}</span></p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('visits.status.update', $visit) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Update status</label>
                            <select name="status" class="form-select">
                                @foreach(['pending', 'in_progress', 'completed', 'billed'] as $state)
                                    <option value="{{ $state }}" @selected($visit->status === $state)>{{ ucwords(str_replace('_', ' ', $state)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Clinical Notes</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">Reason for visit</p>
                    <p>{{ $visit->reason ?? 'Not documented.' }}</p>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Vitals</p>
                            <ul class="list-unstyled mb-0">
                                @foreach(($visit->vitals ?? []) as $key => $value)
                                    <li>{{ strtoupper($key) }}: <span class="fw-semibold">{{ $value }}</span></li>
                                @endforeach
                                @if(empty($visit->vitals))
                                    <li class="text-muted">No vitals captured.</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Estimated Cost</p>
                            <h4>₦{{ number_format($visit->estimated_cost, 2) }}</h4>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Amount Charged</p>
                            <h4>₦{{ number_format($visit->amount_charged, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Prescriptions</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visit->prescriptions as $prescription)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $prescription->drug_name }}</p>
                                    <small class="text-muted">{{ $prescription->dosage }} · {{ $prescription->frequency }} · {{ $prescription->duration }}</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No prescriptions added.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Lab Tests</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visit->labTests as $test)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $test->test_name }}</p>
                                    <small class="text-muted">{{ $test->result_summary ?? 'Awaiting result' }}</small>
                                </div>
                                <span class="badge bg-warning-subtle text-capitalize">{{ $test->status }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No lab orders.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Nursing Notes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visit->nursingNotes as $note)
                            <li class="list-group-item">
                                <p class="mb-1 fw-semibold">{{ $note->note_type ?? 'General' }} · {{ $note->recorded_at->format('d M, h:ia') }}</p>
                                <p class="mb-0">{{ $note->note }}</p>
                                <small class="text-muted">By {{ $note->nurse_name }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No nursing documentation.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
