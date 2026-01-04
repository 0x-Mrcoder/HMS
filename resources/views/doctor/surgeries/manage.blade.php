<x-doctor-layout>
    <x-slot name="header">
        Surgery Execution: {{ $surgery->procedure_name }}
    </x-slot>

    <div class="row g-3">
        <!-- Patient Overview -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg bg-soft-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                            <span class="fs-1 fw-bold text-primary">{{ substr($surgery->patient->first_name, 0, 1) }}</span>
                        </div>
                        <h4 class="mb-1">{{ $surgery->patient->full_name }}</h4>
                        <p class="text-muted">{{ $surgery->patient->gender }}, {{ \Carbon\Carbon::parse($surgery->patient->date_of_birth)->age }} yrs</p>
                    </div>

                    <div class="border-top pt-3">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h6 class="mb-1">Blood</h6>
                                <p class="text-muted mb-0">{{ $surgery->patient->blood_group ?? 'N/A' }}</p>
                            </div>
                            <div class="col-4 border-end">
                                <h6 class="mb-1">Weight</h6>
                                <p class="text-muted mb-0">-- kg</p>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1">Allergies</h6>
                                <p class="text-danger mb-0">{{ $surgery->patient->allergies ?? 'None' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-uppercase fs-12 text-muted">Vitals (Pre-Op)</h6>
                        <ul class="list-group list-group-flush">
                            <!-- Placeholder Vitals from Nursing Notes ideally -->
                            <li class="list-group-item d-flex justify-content-between">
                                <span>BP</span> <span class="fw-bold">120/80</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Heart Rate</span> <span class="fw-bold">72 bpm</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Temp</span> <span class="fw-bold">36.5 °C</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surgery Workspace -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#intra-op" role="tab">Intra-Operative</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#labs" role="tab">Labs & Imaging</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#checklist" role="tab">Safety Checklist</a>
                        </li>
                    </ul>
                    <div>
                         @if($surgery->status === 'scheduled')
                            <span class="badge bg-warning text-dark">Not Started</span>
                         @elseif($surgery->status === 'in_progress')
                            <span class="badge bg-danger animate-pulse">In Progress</span>
                         @elseif($surgery->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                         @endif
                    </div>
                </div>
                <div class="card-body tab-content">
                    <!-- INTRA-OPERATIVE TAB -->
                    <div class="tab-pane active" id="intra-op" role="tabpanel">
                        <form action="{{ route('doctor.portal.surgeries.update-notes', $surgery) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Surgical Notes / Findings</label>
                                <textarea name="notes" class="form-control" rows="10" placeholder="Record incision time, procedure details, findings, closure...">{{ $surgery->notes }}</textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-soft-primary">Save Draft</button>
                                
                                @if($surgery->status !== 'completed')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completeSurgeryModal">
                                        <i class="bi bi-check-lg me-1"></i> Finish Surgery
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- LABS TAB -->
                    <div class="tab-pane" id="labs" role="tabpanel">
                        <h6 class="mb-3">Pre-Operative Lab Results</h6>
                        <ul class="list-group">
                            @forelse($surgery->visit->labTests as $test)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $test->test_name }}</div>
                                        <small class="text-muted">{{ $test->created_at->format('d M h:ia') }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ $test->result_summary ?? 'Pending' }}</span>
                                </li>
                            @empty
                                <div class="text-center text-muted py-4">No lab tests found for this visit.</div>
                            @endforelse
                        </ul>
                    </div>

                    <!-- CHECKLIST TAB -->
                    <div class="tab-pane" id="checklist" role="tabpanel">
                        <div class="form-check mb-2">
                             <input class="form-check-input" type="checkbox" id="check1" checked>
                             <label class="form-check-label" for="check1">Patient Identity Confirmed</label>
                        </div>
                        <div class="form-check mb-2">
                             <input class="form-check-input" type="checkbox" id="check2">
                             <label class="form-check-label" for="check2">Site Marked</label>
                        </div>
                        <div class="form-check mb-2">
                             <input class="form-check-input" type="checkbox" id="check3">
                             <label class="form-check-label" for="check3">Anesthesia Safety Check Completed</label>
                        </div>
                        <div class="form-check mb-2">
                             <input class="form-check-input" type="checkbox" id="check4">
                             <label class="form-check-label" for="check4">Pulse Oximeter on Patient</label>
                        </div>
                        <div class="form-check mb-2">
                             <input class="form-check-input" type="checkbox" id="check5">
                             <label class="form-check-label" for="check5">Allergies Known</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Modal -->
    <div class="modal fade" id="completeSurgeryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('doctor.portal.surgeries.complete', $surgery) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Complete Surgery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this surgery as <strong>Completed</strong>?</p>
                        <p class="text-muted small">This will move the patient to Post-Op Recovery list.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm Completion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-doctor-layout>
