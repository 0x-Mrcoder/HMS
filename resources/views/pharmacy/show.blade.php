@extends('layouts.admin')

@section('title', 'Prescription #' . $prescription->id)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1">Patient</p>
                    <h4 class="mb-0">{{ $prescription->visit->patient->full_name }}</h4>
                    <small class="text-muted">{{ $prescription->visit->patient->hospital_id }} · {{ $prescription->visit->patient->phone }}</small>
                    <hr>
                    <p class="text-muted text-uppercase mb-1">Wallet Balance</p>
                    <h5 class="mb-0">₦{{ number_format(optional($prescription->visit->patient->wallet)->balance ?? 0, 2) }}</h5>
                    <small class="text-muted">Minimum: ₦{{ number_format(optional($prescription->visit->patient->wallet)->low_balance_threshold ?? 0, 2) }}</small>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Medication Details</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><span class="text-muted">Drug:</span> {{ $prescription->drug_name }}</p>
                    <p class="mb-1"><span class="text-muted">Dosage:</span> {{ $prescription->dosage }}</p>
                    <p class="mb-1"><span class="text-muted">Frequency:</span> {{ $prescription->frequency }}</p>
                    <p class="mb-1"><span class="text-muted">Duration:</span> {{ $prescription->duration ?? 'N/A' }}</p>
                    <p class="mb-1"><span class="text-muted">Quantity:</span> {{ $prescription->quantity }}</p>
                    <p class="mb-0"><span class="text-muted">Cost:</span> ₦{{ number_format($prescription->total_cost, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Update Fulfilment</h5>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ $prescription->status }}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pharmacy.prescriptions.update', $prescription) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['pending' => 'Pending', 'dispensed' => 'Dispensed', 'rejected' => 'Rejected'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('status', $prescription->status) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unit Price (₦)</label>
                                <input type="number" step="0.01" min="0" name="unit_price" class="form-control" value="{{ old('unit_price', $prescription->unit_price) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quantity</label>
                                <input type="number" min="1" name="quantity" class="form-control" value="{{ old('quantity', $prescription->quantity) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Charge Amount (₦)</label>
                                <input type="number" step="0.01" min="0" name="charge_amount" class="form-control" value="{{ old('charge_amount', $prescription->total_cost) }}">
                                <small class="text-muted">Used when deducting from wallet.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dispensed By</label>
                                <input type="text" name="dispensed_by" class="form-control" value="{{ old('dispensed_by', $prescription->dispensed_by) }}" placeholder="Eg. Pharm. John Doe">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" rows="3" name="notes" placeholder="Add remarks or rejection reason">{{ old('notes', $prescription->notes) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
