<x-doctor-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Consultation Room</h4>
            <span class="text-muted">Visit ID: #{{ $visit->id }} | {{ $visit->scheduled_at->format('M d, Y') }}</span>
        </div>
    </x-slot>

    <div class="row g-3">
        <!-- Patient Header -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</h3>
                            <p class="text-muted mb-0">{{ ucfirst($visit->patient->gender) }}, {{ $visit->patient->date_of_birth?->age }} years</p>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-1">Wallet Balance</p>
                            <h3 class="mb-0 {{ $visit->patient->wallet?->balance < 1000 ? 'text-danger' : 'text-success' }}">
                                ₦{{ number_format($visit->patient->wallet?->balance ?? 0, 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LEFT COLUMN: Clinical Notes & History -->
        <div class="col-lg-7">
            <div class="row g-3">
                <!-- Nursing Notes / Vitals -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Nursing Notes & Vitals</h4>
                        </div>
                        <div class="card-body">
                            @forelse($visit->nursingNotes as $note)
                                <div class="alert alert-info mb-2" role="alert">
                                    <p class="mb-1">{{ $note->note }}</p>
                                    <small class="text-muted">Recorded by {{ $note->nurse_name }} at {{ $note->recorded_at->format('H:i') }}</small>
                                </div>
                            @empty
                                <p class="text-muted italic mb-0">No nursing notes recorded for this visit.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Doctor's Notes -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Doctor's Notes & Diagnosis</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('doctor.portal.visits.diagnosis', $visit) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="diagnosis" class="form-label">Diagnosis</label>
                                    <input type="text" class="form-control" id="diagnosis" name="diagnosis" value="{{ $visit->diagnosis }}" placeholder="Primary diagnosis..." required>
                                </div>
                                <div class="mb-3">
                                    <label for="clinical_notes" class="form-label">Clinical Notes</label>
                                    <textarea class="form-control" id="clinical_notes" name="clinical_notes" rows="5" placeholder="Enter detailed clinical notes here...">{{ $visit->clinical_notes }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save Notes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Order Lab Test -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Lab Test</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('doctor.portal.visits.lab-test', $visit) }}" method="POST">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5">
                                        <label for="test_name" class="form-label">Test Name</label>
                                        <input type="text" class="form-control" id="test_name" name="test_name" placeholder="e.g. Malaria Parasite" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="priority" class="form-label">Priority</label>
                                        <select name="priority" class="form-select">
                                            <option value="routine">Routine</option>
                                            <option value="urgent">Urgent</option>
                                            <option value="emergency">Emergency</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">Order Test</button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <input type="text" class="form-control form-control-sm" id="lab_clinical_notes" name="clinical_notes" placeholder="Clinical notes for lab technician (optional)">
                                </div>
                            </form>

                            <!-- Recent Labs List -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-muted mb-3">Ordered in this visit:</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($visit->labTests as $test)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span>{{ $test->test_name }}</span>
                                            <span class="badge {{ $test->status === 'completed' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info' }}">
                                                {{ ucfirst($test->status) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Actions (Prescribe) -->
        <div class="col-lg-5">
            <!-- Clinical Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Clinical Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#admitModal">
                            <i class="iconoir-hospital-bed"></i> Admit Patient
                        </button>
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#referModal">
                            <i class="iconoir-share-android"></i> Refer Patient
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#surgeryModal">
                            <i class="iconoir-scalpel"></i> Book Surgery
                        </button>
                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary-subtle">
                    <h4 class="card-title text-primary mb-0">Prescription Pad</h4>
                    <button type="button" class="btn btn-sm btn-light text-primary" onclick="window.print()">
                        <i class="iconoir-printer"></i> Print
                    </button>
                </div>
                <div class="card-body">
                    @if($errors->has('wallet'))
                        <div class="alert alert-danger">
                            {{ $errors->first('wallet') }}
                        </div>
                    @endif

                    <form action="{{ route('doctor.portal.visits.prescribe', $visit) }}" method="POST" id="prescriptionForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Add Medications</label>
                            <div id="drug-items">
                                <!-- Dynamic Items will be added here -->
                            </div>
                            <button type="button" class="btn btn-soft-primary btn-sm w-100 dashed-border" id="add-drug-btn">
                                <i class="iconoir-plus-circle"></i> Add Another Drug
                            </button>
                        </div>

                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="buy_from_hospital" name="buy_from_hospital">
                                    <label class="form-check-label fw-medium" for="buy_from_hospital">
                                        Dispense from Hospital Pharmacy
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1 ms-4">
                                    Checking this will verify the patient's wallet balance (₦{{ number_format($visit->patient->wallet?->balance ?? 0, 2) }}) before sending.
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="iconoir-send-diagonal me-1"></i> Send Prescription
                        </button>
                    </form>

                    <!-- Recent Prescriptions List -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3">Current Prescriptions:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead class="text-muted">
                                    <tr>
                                        <th>Drug</th>
                                        <th>Dosage</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visit->prescriptions as $rx)
                                        <tr>
                                            <td class="fw-medium">{{ $rx->drug_name }}</td>
                                            <td>{{ $rx->dosage }} ({{ $rx->frequency }})</td>
                                            <td>
                                                <span class="badge {{ $rx->status === 'dispensed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                                    {{ ucfirst($rx->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admit Modal -->
    <div class="modal fade" id="admitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admit Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.portal.visits.admit', $visit) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Ward</label>
                            <select class="form-select" name="ward_id" id="wardSelect" required>
                                <option value="">Choose Ward...</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" data-beds="{{ json_encode($ward->beds) }}">
                                        {{ $ward->name }} ({{ $ward->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Bed</label>
                            <select class="form-select" name="bed_id" id="bedSelect" required disabled>
                                <option value="">Select Ward First...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admission Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Admit Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Refer Modal -->
    <div class="modal fade" id="referModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Refer Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.portal.visits.refer', $visit) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Referral Type</label>
                            <select class="form-select" name="type" required>
                                <option value="external">External Hospital/Clinic</option>
                                <option value="internal">Internal Department</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Destination (Hospital/Clinic Name)</label>
                            <input type="text" class="form-control" name="destination" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Doctor Name (Optional)</label>
                            <input type="text" class="form-control" name="doctor_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason for Referral</label>
                            <textarea class="form-control" name="reason" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info text-white">Create Referral</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Surgery Modal -->
    <div class="modal fade" id="surgeryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Surgery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.portal.visits.surgery', $visit) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Procedure Name</label>
                            <input type="text" class="form-control" name="procedure_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scheduled Date & Time</label>
                            <input type="datetime-local" class="form-control" name="scheduled_at" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pre-op Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning text-white">Book Surgery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ward/Bed Logic
            const wardSelect = document.getElementById('wardSelect');
            const bedSelect = document.getElementById('bedSelect');

            if (wardSelect) {
                wardSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const beds = JSON.parse(selectedOption.dataset.beds || '[]');
                    
                    bedSelect.innerHTML = '<option value="">Select Bed...</option>';
                    
                    if (beds.length > 0) {
                        bedSelect.disabled = false;
                        beds.forEach(bed => {
                            const option = document.createElement('option');
                            option.value = bed.id;
                            option.textContent = bed.number;
                            bedSelect.appendChild(option);
                        });
                    } else {
                        bedSelect.disabled = true;
                        const option = document.createElement('option');
                        option.textContent = 'No available beds';
                        bedSelect.appendChild(option);
                    }
                });
            }

            // ... Existing Drug Logic ...
            const container = document.getElementById('drug-items');

            const addBtn = document.getElementById('add-drug-btn');
            let itemIndex = 0;

            function addDrugItem() {
                const itemHtml = `
                    <div class="drug-item card mb-2 border shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-light text-dark border">Drug #${itemIndex + 1}</span>
                                <button type="button" class="btn-close btn-sm remove-item" aria-label="Close"></button>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm drug-search fw-bold" name="items[${itemIndex}][drug_name]" list="drugList" placeholder="Search Drug Name..." required autocomplete="off">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][dosage]" placeholder="Dosage (e.g. 500mg)" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][frequency]" placeholder="Freq (e.g. 2x daily)" required>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][duration]" placeholder="Dur (e.g. 5 days)" required>
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control form-control-sm" name="items[${itemIndex}][quantity]" min="1" value="1" placeholder="Qty" required>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][notes]" placeholder="Note">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
                itemIndex++;
            }

            // Add first item by default
            addDrugItem();

            addBtn.addEventListener('click', addDrugItem);

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.drug-item').remove();
                }
            });

            // Simple datalist population
            const datalist = document.createElement('datalist');
            datalist.id = 'drugList';
            document.body.appendChild(datalist);

            // Fetch drugs for autocomplete
            fetch('{{ route("doctor.portal.drugs.search") }}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(drug => {
                        const option = document.createElement('option');
                        option.value = drug.name;
                        datalist.appendChild(option);
                    });
                });
        });
    </script>
    @endpush
</x-doctor-layout>
