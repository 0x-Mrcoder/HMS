@extends('layouts.patient')

@section('title', 'My Care Dashboard')

@section('content')
@php
    $photo = $patient->photo_url;
    $photoUrl = $photo
        ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://'])
            ? $photo
            : asset(ltrim($photo, '/')))
        : asset('rizz-assets/images/users/user-4.jpg');
    $firstInitial = $patient->first_name ? strtoupper(mb_substr($patient->first_name, 0, 1)) : '';
    $lastInitial = $patient->last_name ? strtoupper(mb_substr($patient->last_name, 0, 1)) : '';
    $initials = trim($firstInitial . $lastInitial) ?: 'P';
@endphp
<div class="container-xxl py-4">
    <div class="row g-3" id="overview">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle mb-3 shadow-sm d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width:110px;height:110px;object-fit:cover; font-size:36px; font-weight:700; position:relative; overflow:hidden;">
                        @if($photo)
                            <img src="{{ $photoUrl }}" alt="patient photo" class="w-100 h-100 rounded-circle" style="object-fit:cover; position:absolute; inset:0;">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <h4 class="mb-0">{{ $patient->full_name }}</h4>
                    <p class="text-muted mb-3">{{ ucfirst($patient->gender) }} · {{ $patient->date_of_birth?->format('d M Y') ?? 'DOB not set' }}</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap mb-3">
                        <div>
                            <small class="text-muted d-block">Hospital ID</small>
                            <span class="fw-semibold">{{ $patient->hospital_id }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Card Number</small>
                            <span class="fw-semibold">{{ $patient->card_number }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Wallet Min.</small>
                            <span class="fw-semibold">₦{{ number_format($patient->wallet_minimum_balance ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="text-start">
                        <p class="mb-1"><i class="iconoir-phone rounded-circle bg-primary-subtle text-primary me-2 p-1"></i>{{ $patient->phone }}</p>
                        <p class="mb-1"><i class="iconoir-mail rounded-circle bg-primary-subtle text-primary me-2 p-1"></i>{{ $patient->email }}</p>
                        <p class="mb-0"><i class="iconoir-pin rounded-circle bg-primary-subtle text-primary me-2 p-1"></i>{{ $patient->address }}, {{ $patient->city }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100" id="wallet">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1">Wallet Balance</p>
                            <h2 class="mb-0">₦{{ number_format($wallet?->balance ?? 0, 2) }}</h2>
                            @if($wallet?->virtual_account_number)
                                <p class="mb-0 small text-muted">Virtual Account: <strong>{{ $wallet->virtual_account_number }}</strong></p>
                            @endif
                            @if(!is_null($walletAlert) && $walletAlert > 0)
                                <span class="badge bg-danger-subtle text-danger mt-1">Top up ₦{{ number_format($walletAlert, 2) }} to reach minimum balance</span>
                            @else
                                <span class="badge bg-success-subtle text-success mt-1">Wallet is healthy</span>
                            @endif
                        </div>
                        <div class="text-end">
                            <a class="btn btn-outline-secondary me-2" href="{{ route('patient.portal.wallet.transactions') }}"><i class="iconoir-doc-text me-1"></i>History</a>
                            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#fund-wallet"><i class="iconoir-wallet me-1"></i>Add Funds</button>
                        </div>
                    </div>
                    <div class="collapse mb-3" id="fund-wallet">
                        <form class="row g-2 align-items-end" method="POST" action="{{ route('patient.portal.wallet.deposit') }}">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label mb-1">Amount (₦)</label>
                                <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="pos">POS</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Reference</label>
                                <input type="text" name="reference" class="form-control" placeholder="Optional ref">
                            </div>
                            <div class="col-md-1 d-grid">
                                <button class="btn btn-success" type="submit">Top Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted text-uppercase">Visits Logged</small>
                                <h4 class="mb-0">{{ $patient->visits_count }}</h4>
                                <span class="badge bg-info-subtle text-info mt-2">In hospital</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted text-uppercase">Upcoming Visit</small>
                                <h5 class="mb-0">{{ $upcomingVisit?->service?->name ?? $upcomingVisit?->department?->name ?? 'Not scheduled' }}</h5>
                                <small class="text-muted">{{ $upcomingVisit?->scheduled_at?->format('D, d M h:ia') ?? 'Select a date with reception' }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted text-uppercase">NHIS Number</small>
                                <h5 class="mb-0">{{ $patient->nhis_number ?? 'Not enrolled' }}</h5>
                                <small class="text-muted">Emergency Contact: {{ $patient->emergency_contact_name ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-xl-8" id="appointments">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Visits &amp; Appointments</h5>
                        <small class="text-muted">Latest hospital interactions</small>
                    </div>
                    <a class="btn btn-soft-primary btn-sm" href="{{ route('patient.portal.visits.request') }}">Request Visit</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($visits as $visit)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $visit->department?->name ?? 'General' }}</p>
                                        <small class="text-muted">{{ $visit->service?->name ?? 'Custom service' }} · {{ $visit->scheduled_at?->format('d M, h:ia') ?? 'Pending schedule' }}</small>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                </div>
                                @if($visit->doctor_name)
                                    <small class="text-muted">Doctor: {{ $visit->doctor_name }}</small>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No visits recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
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

    <div class="row g-3 mt-1" id="medications">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Medications &amp; Prescriptions</h5>
                    <a href="#!" class="btn btn-soft-secondary btn-sm">View all</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($prescriptions as $prescription)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $prescription->drug_name }}</p>
                                        <small class="text-muted">{{ $prescription->dosage }} · {{ $prescription->frequency }} · {{ $prescription->duration }}</small>
                                        <br>
                                        <small class="text-muted">Clinic: {{ $prescription->visit?->department?->name }}</small>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No prescriptions yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6" id="labs">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lab Results &amp; Diagnostics</h5>
                    <a href="#!" class="btn btn-soft-secondary btn-sm">Download PDF</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($labTests as $test)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $test->test_name }}</p>
                                        <small class="text-muted">{{ $test->result_summary ?? 'Awaiting result' }}</small>
                                        <br>
                                        <small class="text-muted">Dept: {{ $test->visit?->department?->name ?? 'Laboratory' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning-subtle text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span>
                                        @if($test->result_at)
                                            <p class="mb-0 text-muted small mt-1">{{ $test->result_at->format('d M h:ia') }}</p>
                                        @endif
                                    </div>
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

    <div class="row g-3 mt-1" id="care-notes">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nursing Notes &amp; Care Updates</h5>
                    <a href="#!" class="btn btn-soft-secondary btn-sm">All notes</a>
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
        <div class="col-lg-6" id="profile">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Profile &amp; Support</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted mb-1">Blood Group</p>
                            <h5>{{ $patient->blood_group ?? 'Not captured' }}</h5>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-1">Genotype</p>
                            <h5>{{ $patient->genotype ?? 'Not captured' }}</h5>
                        </div>
                        <div class="col-sm-12">
                            <p class="text-muted mb-1">Allergies</p>
                            <p class="mb-0">{{ $patient->allergies ?? 'No allergies recorded.' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div id="support">
                        <p class="text-muted mb-1">Emergency Contact</p>
                        <p class="mb-0 fw-semibold">{{ $patient->emergency_contact_name ?? 'Not provided' }}</p>
                        <small class="text-muted">{{ $patient->emergency_contact_phone ?? '--' }}</small>
                        <div class="mt-3">
                            <p class="text-muted mb-1">24/7 Support</p>
                            @php
                                $supportPhone = $hospitalConfig['phone'] ?? null;
                                $supportEmail = $hospitalConfig['email'] ?? null;
                            @endphp
                            <p class="mb-0">
                                @if($supportPhone)
                                    Call <strong>{{ $supportPhone }}</strong>
                                @endif
                                @if($supportPhone && $supportEmail)
                                    or
                                @endif
                                @if($supportEmail)
                                    email <strong>{{ $supportEmail }}</strong>
                                @endif
                                @if(!$supportPhone && !$supportEmail)
                                    Contact the hospital team for assistance.
                                @endif
                            </p>
                            <button class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-chat-dots me-1"></i>Chat with Care Team</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
