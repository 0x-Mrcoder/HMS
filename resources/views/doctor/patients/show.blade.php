<x-doctor-layout>
    <x-slot name="header">
        Patient File: {{ $patient->first_name }} {{ $patient->last_name }}
    </x-slot>

    <div class="row g-3">
        <!-- Patient Info Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <h5 class="text-uppercase text-muted fs-12 mb-2">Demographics</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><span class="fw-medium">ID:</span> {{ $patient->hospital_id }}</li>
                                <li class="mb-1"><span class="fw-medium">DOB:</span> {{ $patient->date_of_birth?->format('Y-m-d') }} ({{ $patient->date_of_birth?->age }} yrs)</li>
                                <li class="mb-1"><span class="fw-medium">Gender:</span> {{ ucfirst($patient->gender) }}</li>
                                <li><span class="fw-medium">Blood Group:</span> {{ $patient->blood_group ?? 'Unknown' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-uppercase text-muted fs-12 mb-2">Contact</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><i class="las la-phone me-2"></i> {{ $patient->phone }}</li>
                                <li class="mb-1"><i class="las la-envelope me-2"></i> {{ $patient->email ?? 'N/A' }}</li>
                                <li><i class="las la-map-marker me-2"></i> {{ $patient->address }}</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <h5 class="text-uppercase text-muted fs-12 mb-2">Wallet Balance</h5>
                            <h2 class="mb-0 text-success fw-bold">₦{{ number_format($patient->wallet?->balance ?? 0, 2) }}</h2>
                            <small class="text-muted">Current Available Funds</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient History Tabs -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="visits-tab" data-bs-toggle="tab" data-bs-target="#visits" type="button" role="tab">Visits</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admissions-tab" data-bs-toggle="tab" data-bs-target="#admissions" type="button" role="tab">Admissions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="surgeries-tab" data-bs-toggle="tab" data-bs-target="#surgeries" type="button" role="tab">Surgeries</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="referrals-tab" data-bs-toggle="tab" data-bs-target="#referrals" type="button" role="tab">Referrals</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="patientTabsContent">
                        <!-- Visits Tab -->
                        <div class="tab-pane fade show active" id="visits" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->visits as $visit)
                                            <tr>
                                                <td>{{ $visit->scheduled_at?->format('Y-m-d H:i') }}</td>
                                                <td>{{ $visit->department?->name ?? 'General' }}</td>
                                                <td><span class="badge bg-secondary-subtle text-capitalize">{{ ucfirst($visit->status) }}</span></td>
                                                <td>
                                                    <a href="{{ route('doctor.portal.visits.show', $visit) }}" class="btn btn-sm btn-soft-primary">Open Consultation</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No visits recorded.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Admissions Tab -->
                        <div class="tab-pane fade" id="admissions" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Admitted Date</th>
                                            <th>Ward</th>
                                            <th>Bed</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->admissions as $admission)
                                            <tr>
                                                <td>{{ $admission->admitted_at->format('Y-m-d H:i') }}</td>
                                                <td>{{ $admission->ward->name }}</td>
                                                <td>{{ $admission->bed->number }}</td>
                                                <td><span class="badge bg-info-subtle text-capitalize">{{ ucfirst($admission->status) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No admissions recorded.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Surgeries Tab -->
                        <div class="tab-pane fade" id="surgeries" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Procedure</th>
                                            <th>Scheduled Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->surgeries as $surgery)
                                            <tr>
                                                <td>{{ $surgery->procedure_name }}</td>
                                                <td>{{ $surgery->scheduled_at?->format('Y-m-d H:i') }}</td>
                                                <td><span class="badge bg-warning-subtle text-capitalize">{{ ucfirst($surgery->status) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted py-4">No surgeries recorded.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Referrals Tab -->
                        <div class="tab-pane fade" id="referrals" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Destination</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patient->referrals as $referral)
                                            <tr>
                                                <td>{{ $referral->referred_at->format('Y-m-d') }}</td>
                                                <td>{{ ucfirst($referral->type) }}</td>
                                                <td>{{ $referral->destination }}</td>
                                                <td><span class="badge bg-primary-subtle text-capitalize">{{ ucfirst($referral->status) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No referrals recorded.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
