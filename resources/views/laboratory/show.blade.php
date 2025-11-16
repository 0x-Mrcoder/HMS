@extends('layouts.admin')

@section('title', 'Lab Test #' . $labTest->id)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1">Patient</p>
                    <h4 class="mb-0">{{ $labTest->visit->patient->full_name }}</h4>
                    <small class="text-muted">{{ $labTest->visit->patient->hospital_id }} · {{ $labTest->visit->patient->phone }}</small>
                    <hr>
                    <p class="text-muted text-uppercase mb-1">Wallet Balance</p>
                    <h5 class="mb-0">₦{{ number_format(optional($labTest->visit->patient->wallet)->balance ?? 0, 2) }}</h5>
                    <small class="text-muted">Minimum: ₦{{ number_format(optional($labTest->visit->patient->wallet)->low_balance_threshold ?? 0, 2) }}</small>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Test Metadata</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><span class="text-muted">Test:</span> {{ $labTest->test_name }}</p>
                    <p class="mb-1"><span class="text-muted">Technician:</span> {{ $labTest->technician_name ?? 'Unassigned' }}</p>
                    <p class="mb-1"><span class="text-muted">Charge:</span> ₦{{ number_format($labTest->charge_amount, 2) }}</p>
                    <p class="mb-1"><span class="text-muted">Status:</span> {{ ucwords(str_replace('_', ' ', $labTest->status)) }}</p>
                    <p class="mb-0"><span class="text-muted">Result at:</span> {{ $labTest->result_at?->format('d M, h:ia') ?? 'Pending' }}</p>
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
                    <h5 class="mb-0">Record Results</h5>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $labTest->status) }}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('laboratory.tests.update', $labTest) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('status', $labTest->status) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Technician</label>
                                <input type="text" name="technician_name" class="form-control" value="{{ old('technician_name', $labTest->technician_name) }}" placeholder="Eg. MLS Jane Doe">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Charge Amount (₦)</label>
                                <input type="number" step="0.01" min="0" name="charge_amount" class="form-control" value="{{ old('charge_amount', $labTest->charge_amount) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Result Summary</label>
                                <textarea name="result_summary" class="form-control" rows="3" placeholder="Overview of findings">{{ old('result_summary', $labTest->result_summary) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Result Payload (JSON)</label>
                                <textarea name="result_payload" class="form-control" rows="4" placeholder='{"hb":"12 g/dl"}'>{{ old('result_payload', $labTest->result_data ? json_encode($labTest->result_data) : '') }}</textarea>
                                <small class="text-muted">Optional structured data saved with the result.</small>
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
