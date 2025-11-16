@extends('layouts.admin')

@section('title', 'NHIS / Insurance Desk')

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Submit Claim</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('insurance.claims.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-select">
                                <option value="">Select patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>
                                        {{ $patient->first_name }} {{ $patient->last_name }} · {{ $patient->hospital_id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visit ID</label>
                            <input type="number" name="visit_id" class="form-control" value="{{ old('visit_id') }}" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Policy Number</label>
                            <input type="text" name="policy_number" class="form-control" value="{{ old('policy_number') }}">
                            @error('policy_number')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Provider</label>
                            <input type="text" name="provider" class="form-control" value="{{ old('provider') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Amount (₦)</label>
                            <input type="number" step="0.01" min="0" name="total_amount" class="form-control" value="{{ old('total_amount') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Co-Pay Amount (₦)</label>
                            <input type="number" step="0.01" min="0" name="co_pay_amount" class="form-control" value="{{ old('co_pay_amount') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supporting Documents (JSON)</label>
                            <textarea name="documents" class="form-control" rows="3" placeholder='{"lab_request":"url"}'>{{ old('documents') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Claim</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected', 'paid' => 'Paid'] as $key => $label)
                                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Search</label>
                            <input type="search" class="form-control" name="q" value="{{ $search }}" placeholder="Policy, provider or patient">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-secondary" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row g-3 mb-2">
                @foreach(['draft' => 'secondary', 'submitted' => 'info', 'approved' => 'success', 'paid' => 'primary', 'rejected' => 'danger'] as $state => $color)
                    <div class="col-md-4">
                        <div class="card border-{{ $color }}-subtle">
                            <div class="card-body">
                                <p class="text-muted text-uppercase mb-1">{{ ucfirst($state) }}</p>
                                <h3 class="mb-0">{{ number_format($statusCounts[$state] ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Claims Queue</h5>
                    <span class="text-muted small">{{ $claims->total() }} records</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Policy</th>
                                <th>Amounts</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($claims as $claim)
                                <tr>
                                    <td>
                                        <p class="mb-0 fw-semibold">{{ $claim->patient->full_name }}</p>
                                        <small class="text-muted">{{ $claim->patient->hospital_id }}</small>
                                    </td>
                                    <td>
                                        <p class="mb-0">{{ $claim->policy_number }}</p>
                                        <small class="text-muted">{{ $claim->provider }}</small>
                                    </td>
                                    <td>
                                        <small class="d-block text-muted">Total: ₦{{ number_format($claim->total_amount, 2) }}</small>
                                        <small class="d-block text-muted">Approved: ₦{{ number_format($claim->approved_amount, 2) }}</small>
                                        <small class="text-muted">Co-Pay: ₦{{ number_format($claim->co_pay_amount, 2) }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-capitalize">{{ $claim->claim_status }}</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('insurance.claims.show', $claim) }}" class="btn btn-soft-primary btn-sm">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No claims submitted.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $claims->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
