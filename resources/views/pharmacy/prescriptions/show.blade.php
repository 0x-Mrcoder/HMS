<x-pharmacy-layout>
    <x-slot name="header">Dispense Prescription</x-slot>

    <div class="row">
        <!-- Patient Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Patient Details</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $prescription->visit->patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" alt="" class="thumb-lg rounded-circle">
                        <h5 class="mt-2 mb-0">{{ $prescription->visit->patient->first_name }} {{ $prescription->visit->patient->last_name }}</h5>
                        <p class="text-muted">{{ $prescription->visit->patient->hospital_id }}</p>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="iconoir-wallet me-2"></i> Wallet Balance: 
                            <span class="fw-bold {{ $prescription->visit->patient->wallet->balance < ($price * $prescription->quantity) ? 'text-danger' : 'text-success' }}">
                                ₦{{ number_format($prescription->visit->patient->wallet->balance, 2) }}
                            </span>
                        </li>
                        <li class="mb-2"><i class="iconoir-calendar me-2"></i> DOB: {{ $prescription->visit->patient->date_of_birth?->format('Y-m-d') }}</li>
                        <li><i class="iconoir-male-female me-2"></i> Gender: {{ ucfirst($prescription->visit->patient->gender) }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Prescription Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Prescription Details</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Drug Name</p>
                            <h5 class="fw-bold">{{ $prescription->drug_name }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Dosage</p>
                            <p class="fw-medium">{{ $prescription->dosage }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Frequency</p>
                            <p class="fw-medium">{{ $prescription->frequency }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Prescribed Quantity</p>
                            <h5 class="fw-bold">{{ $prescription->quantity }} units</h5>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Duration</p>
                            <p class="fw-medium">{{ $prescription->duration }}</p>
                        </div>
                    </div>

                    <div class="alert alert-light border-dashed mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Unit Price:</span>
                            <span class="fw-medium">₦{{ number_format($price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Cost:</span>
                            <span class="fw-bold">₦{{ number_format($price * $prescription->quantity, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Current Stock:</span>
                            <span class="fw-bold {{ $stock < $prescription->quantity ? 'text-danger' : 'text-success' }}">
                                {{ $stock }} units
                            </span>
                        </div>
                    </div>

                    @if($prescription->status === 'pending')
                        <form action="{{ route('pharmacy.portal.dispense', $prescription) }}" method="POST">
                            @csrf
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2" 
                                    {{ ($stock < $prescription->quantity || $prescription->visit->patient->wallet->balance < ($price * $prescription->quantity)) ? 'disabled' : '' }}>
                                    <i class="iconoir-check-circle me-1"></i> Confirm Dispense & Deduct Payment
                                </button>
                                <button type="button" class="btn btn-outline-danger py-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="iconoir-cancel me-1"></i> Reject Prescription
                                </button>
                            </div>
                            @if($stock < $prescription->quantity)
                                <small class="text-danger d-block text-center mt-2">Insufficient Stock</small>
                            @elseif($prescription->visit->patient->wallet->balance < ($price * $prescription->quantity))
                                <small class="text-danger d-block text-center mt-2">Insufficient Wallet Balance</small>
                            @endif
                        </form>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Prescription</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('pharmacy.portal.prescriptions.reject', $prescription) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reason for Rejection</label>
                                                <textarea class="form-control" name="rejection_reason" rows="3" required placeholder="e.g. Out of stock, Incorrect dosage..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success text-center">
                            <i class="iconoir-check-circle fs-4 d-block mb-1"></i>
                            Dispensed on {{ $prescription->dispensed_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-pharmacy-layout>
