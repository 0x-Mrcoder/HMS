<x-doctor-layout>
    <x-slot name="header">
        {{ isset($isSurgeon) && $isSurgeon ? 'Theatre Command Center' : 'Doctor Workspace' }}
    </x-slot>

    @if(isset($isSurgeon) && $isSurgeon)
        <!-- SURGEON DASHBOARD -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-white-50 mb-1">Surgeries Today</p>
                                <h3 class="mb-0 text-white">{{ $metrics['surgeries_today'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-scissors fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Pending Requests</p>
                                <h3 class="mb-0">{{ $metrics['pending_requests'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Post-Op Monitoring</p>
                                <h3 class="mb-0">{{ $metrics['post_op_active'] ?? 0 }}</h3>
                            </div>
                            <i class="bi bi-heart-pulse fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-danger border-opacity-25">
                    <div class="card-header bg-danger-subtle d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-danger"><i class="bi bi-card-checklist me-2"></i>Upcoming Theatre List</h5>
                        <a href="{{ route('doctor.portal.theatre-requests') }}" class="btn btn-sm btn-danger">Manage All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Schedule</th>
                                        <th>Procedure</th>
                                        <th>Patient</th>
                                        <th>Surgeon</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($upcomingSurgeries as $surgery)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $surgery->scheduled_at?->format('h:ia') }}</div>
                                                <small class="text-muted">{{ $surgery->scheduled_at?->format('M d, Y') }}</small>
                                            </td>
                                            <td class="fw-medium text-primary">{{ $surgery->procedure_name }}</td>
                                            <td>
                                                <div>{{ $surgery->patient->full_name }}</div>
                                                <small class="text-muted">{{ $surgery->patient->hospital_id }}</small>
                                            </td>
                                            <td>{{ $surgery->surgeon_name ?? 'Me' }}</td>
                                            <td><span class="badge bg-warning-subtle text-warning">Scheduled</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-soft-danger" onclick="alert('Start functionality requires Theatre Module integration')">
                                                    <i class="bi bi-play-circle me-1"></i> Start Op
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-5">No upcoming surgeries found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Post-Op Recovery (Recent)</h5>
                    </div>
                    <div class="card-body">
                         <ul class="list-group list-group-flush">
                            @forelse($postOpPatients as $surgery)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $surgery->patient->full_name }}</h6>
                                        <small class="text-muted">{{ $surgery->procedure_name }} ({{ $surgery->updated_at->diffForHumans() }})</small>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Recovering</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No recent post-op patients.</li>
                            @endforelse
                         </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                 <!-- Keep Lab Results for Surgeons too as they need to check labs -->
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Laboratory Feed (Pre-Op Labs)</h5>
                        <a href="{{ route('doctor.portal.labs') }}" class="btn btn-soft-secondary btn-sm">View All</a>
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
                                        <span class="badge {{ in_array($test->status, ['completed']) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span>
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

    @else
        <!-- REGULAR DOCTOR DASHBOARD -->
    <div class="row g-3">
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

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Clinic Queue</h4>
                        <small class="text-muted">Patients waiting for consultation</small>
                    </div>
                    <a href="{{ route('doctor.portal.queue') }}" class="btn btn-sm btn-soft-primary">View Full Queue</a>
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
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ in_array($visit->status, ['completed', 'paid']) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
                                        <a href="{{ route('doctor.portal.visits.show', $visit) }}" class="btn btn-sm btn-primary">Start Consultation</a>
                                    </div>
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
                    <a href="{{ route('doctor.portal.appointments') }}" class="btn btn-soft-secondary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse ($visits as $visit)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $visit->patient->full_name }}</p>
                                    <small class="text-muted">{{ $visit->department?->name }} · {{ $visit->scheduled_at?->format('D, h:ia') ?? 'Pending' }}</small>
                                </div>
                                <span class="badge {{ in_array($visit->status, ['completed', 'paid']) ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span>
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
        <div class="col-lg-6">
            @if(isset($isWardDoctor) && $isWardDoctor && isset($myAdmissions))
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center bg-info-subtle">
                        <h5 class="mb-0 text-info">My Inpatients (Ward)</h5>
                        <button class="btn btn-sm btn-info">View Rounds</button>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse ($myAdmissions as $admission)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $admission->patient->full_name }}</p>
                                            <small class="text-muted">
                                                {{ $admission->ward->name }} · Bed {{ $admission->bed->number }}
                                            </small>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">Admitted</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No active inpatients.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @else
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Prescription Board</h5>
                        <a href="{{ route('doctor.portal.prescriptions') }}" class="btn btn-soft-secondary btn-sm">View History</a>
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
            @endif
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laboratory Feed</h5>
                    <a href="{{ route('doctor.portal.labs') }}" class="btn btn-soft-secondary btn-sm">View Results</a>
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
    @endif
</x-doctor-layout>
