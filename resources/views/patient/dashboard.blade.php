@extends('layouts.patient')

@section('title', 'My Care Dashboard')

@section('content')
@php
    $photo = $patient->photo_url;
    $photoUrl = $photo
        ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://']) ? $photo : asset(ltrim($photo, '/')))
        : asset('rizz-assets/images/users/user-4.jpg');
    $firstInitial = $patient->first_name ? strtoupper(mb_substr($patient->first_name, 0, 1)) : '';
    $lastInitial = $patient->last_name ? strtoupper(mb_substr($patient->last_name, 0, 1)) : '';
    $initials = trim($firstInitial . $lastInitial) ?: 'P';
    $walletBalance = $wallet?->balance ?? 0;
@endphp
<div class="container-xxl py-4">
    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body d-flex">
                    <div class="me-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width:78px;height:78px;font-weight:700;font-size:26px;position:relative;overflow:hidden;">
                            @if($photo)
                                <img src="{{ $photoUrl }}" alt="patient photo" class="w-100 h-100 rounded-circle" style="object-fit:cover;position:absolute;inset:0;">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Welcome back</p>
                        <h4 class="mb-1">{{ $patient->full_name }}</h4>
                        <small class="text-muted">{{ $patient->email ?? 'No email on file' }}</small>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            <span class="badge bg-light text-dark">Hospital ID: {{ $patient->hospital_id }}</span>
                            <span class="badge bg-light text-dark">Card: {{ $patient->card_number }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Wallet Balance</p>
                            <h3 class="mb-1">₦{{ number_format($walletBalance, 2) }}</h3>
                            @if($wallet?->virtual_account_number)
                                <small class="text-muted">Virtual Acct: {{ $wallet->virtual_account_number }}</small>
                            @endif
                            @if(!is_null($walletAlert) && $walletAlert > 0)
                                <div class="mt-1"><span class="badge bg-danger-subtle text-danger">Top up ₦{{ number_format($walletAlert, 2) }}</span></div>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('patient.portal.wallet') }}" class="btn btn-primary btn-sm me-1">Fund Wallet</a>
                                <a href="{{ route('patient.portal.wallet.transactions') }}" class="btn btn-outline-secondary btn-sm">History</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Upcoming Visit</p>
                            <h6 class="mb-0">{{ $upcomingVisit?->service?->name ?? $upcomingVisit?->department?->name ?? 'Not scheduled' }}</h6>
                            <small class="text-muted">{{ $upcomingVisit?->scheduled_at?->format('D, d M h:ia') ?? 'Request an appointment' }}</small>
                            <div class="mt-2">
                                <a href="{{ route('patient.portal.visits.request') }}" class="btn btn-outline-primary btn-sm">Request/Reschedule</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Health Snapshot</p>
                            <h6 class="mb-0">Blood: {{ $patient->blood_group ?? 'N/A' }}</h6>
                            <small class="text-muted">Genotype: {{ $patient->genotype ?? 'N/A' }}</small><br>
                            <small class="text-muted">Allergies: {{ $patient->allergies ? \Illuminate\Support\Str::limit($patient->allergies, 40) : 'None recorded' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Recent Visits</h5>
                        <small class="text-muted">Latest appointments</small>
                    </div>
                    <a class="btn btn-soft-primary btn-sm" href="{{ route('patient.portal.visits') }}">See all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visits as $visit)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $visit->department?->name ?? 'General' }}</p>
                                    <small class="text-muted">{{ $visit->service?->name ?? 'Custom service' }} · {{ $visit->scheduled_at?->format('d M, h:ia') ?? 'Pending' }}</small><br>
                                    @if($visit->doctor_name)
                                        <small class="text-muted">Doctor: {{ $visit->doctor_name }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span><br>
                                    <a href="{{ route('patient.portal.visits.show', $visit) }}" class="btn btn-link btn-sm p-0">Details</a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No visits recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Wallet Activity</h5>
                    <small class="text-muted">{{ $transactions->count() }} recent</small>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($transactions as $transaction)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $transaction->service ?? ucfirst($transaction->transaction_type) }}</p>
                                    <small class="text-muted">{{ $transaction->transacted_at?->diffForHumans() }}</small>
                                </div>
                                <span class="fw-semibold {{ $transaction->transaction_type === 'deduction' ? 'text-danger' : 'text-success' }}">₦{{ number_format($transaction->amount, 2) }}</span>
                            </li>
                        @empty
                            <li class="text-center text-muted py-4">No wallet activity yet.</li>
                        @endforelse
                    </ul>
                    <div class="mt-3 text-end">
                        <a href="{{ route('patient.portal.wallet.transactions') }}" class="btn btn-soft-secondary btn-sm">View full history</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Prescriptions</h5>
                    <a href="{{ route('patient.portal.prescriptions') }}" class="btn btn-soft-secondary btn-sm">See all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($prescriptions as $prescription)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $prescription->drug_name }}</p>
                                    <small class="text-muted">{{ $prescription->dosage }} · {{ $prescription->frequency }} · {{ $prescription->duration }}</small><br>
                                    <small class="text-muted">Clinic: {{ $prescription->visit?->department?->name }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span><br>
                                    <a href="{{ route('patient.portal.prescriptions.show', $prescription) }}" class="btn btn-link btn-sm p-0">Details</a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No prescriptions yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lab Results</h5>
                    <a href="{{ route('patient.portal.labs') }}" class="btn btn-soft-secondary btn-sm">See all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($labTests as $test)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $test->test_name }}</p>
                                    <small class="text-muted">{{ $test->result_summary ?? 'Awaiting result' }}</small><br>
                                    <small class="text-muted">Dept: {{ $test->visit?->department?->name ?? 'Laboratory' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning-subtle text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span><br>
                                    <a href="{{ route('patient.portal.labs.show', $test) }}" class="btn btn-link btn-sm p-0">Details</a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No lab results available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Care Notes</h5>
                    <a href="{{ route('patient.portal.care-notes') }}" class="btn btn-soft-secondary btn-sm">See all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($nursingNotes as $note)
                            <li class="list-group-item">
                                <p class="mb-1 fw-semibold">{{ $note->note_type ?? 'General' }} · {{ $note->recorded_at?->format('d M, h:ia') }}</p>
                                <p class="mb-0">{{ $note->note }}</p>
                                <small class="text-muted">Nurse: {{ $note->nurse_name }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No nursing notes shared.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Insurance &amp; Support</h5>
                    <a href="{{ route('patient.portal.claims') }}" class="btn btn-soft-secondary btn-sm">Claims</a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">NHIS Number</p>
                        <h6 class="mb-0">{{ $patient->nhis_number ?? 'Not enrolled' }}</h6>
                    </div>
                    <div class="border-top pt-3">
                        <p class="text-muted mb-1">Emergency Contact</p>
                        <p class="mb-0 fw-semibold">{{ $patient->emergency_contact_name ?? 'Not provided' }}</p>
                        <small class="text-muted">{{ $patient->emergency_contact_phone ?? '--' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
