@extends('layouts.admin')

@section('title', 'Claim #' . $claim->id)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1">Patient</p>
                    <h4 class="mb-0">{{ $claim->patient->full_name }}</h4>
                    <small class="text-muted">{{ $claim->patient->hospital_id }} · {{ $claim->patient->phone }}</small>
                    <hr>
                    <p class="text-muted text-uppercase mb-1">Wallet Balance</p>
                    <h5 class="mb-0">₦{{ number_format(optional($claim->patient->wallet)->balance ?? 0, 2) }}</h5>
                    <small class="text-muted">Minimum: ₦{{ number_format(optional($claim->patient->wallet)->low_balance_threshold ?? 0, 2) }}</small>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Claim Reference</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><span class="text-muted">Policy:</span> {{ $claim->policy_number }}</p>
                    <p class="mb-1"><span class="text-muted">Provider:</span> {{ $claim->provider }}</p>
                    <p class="mb-1"><span class="text-muted">Submitted:</span> {{ $claim->submitted_at?->format('d M, h:ia') ?? 'Pending' }}</p>
                    <p class="mb-1"><span class="text-muted">Responded:</span> {{ $claim->responded_at?->format('d M, h:ia') ?? 'Pending' }}</p>
                    <p class="mb-0"><span class="text-muted">Documents:</span>
                        @if ($claim->documents)
                            <code class="d-block">{{ json_encode($claim->documents) }}</code>
                        @else
                            <span>None</span>
                        @endif
                    </p>
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
                    <h5 class="mb-0">Process Claim</h5>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ $claim->claim_status }}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('insurance.claims.update', $claim) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="claim_status" class="form-select">
                                    @foreach(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected', 'paid' => 'Paid'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('claim_status', $claim->claim_status) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Approved Amount (₦)</label>
                                <input type="number" step="0.01" min="0" name="approved_amount" class="form-control" value="{{ old('approved_amount', $claim->approved_amount) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Co-Pay Amount (₦)</label>
                                <input type="number" step="0.01" min="0" name="co_pay_amount" class="form-control" value="{{ old('co_pay_amount', $claim->co_pay_amount) }}">
                                <small class="text-muted">Wallet will be debited once per claim.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Documents (JSON)</label>
                                <textarea name="documents" class="form-control" rows="3">{{ old('documents', $claim->documents ? json_encode($claim->documents) : '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="4">{{ old('remarks', $claim->remarks) }}</textarea>
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
