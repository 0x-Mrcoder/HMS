@extends('layouts.admin')

@section('title', 'Surgery #' . $surgery->id)

@section('content')
<div class="container-xxl">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1">Patient</p>
                    <h4 class="mb-0">{{ $surgery->patient->full_name }}</h4>
                    <small class="text-muted">{{ $surgery->patient->hospital_id }} · {{ $surgery->patient->phone }}</small>
                    <hr>
                    <p class="text-muted text-uppercase mb-1">Wallet Balance</p>
                    <h5 class="mb-0">₦{{ number_format(optional($surgery->patient->wallet)->balance ?? 0, 2) }}</h5>
                    <small class="text-muted">Min: ₦{{ number_format(optional($surgery->patient->wallet)->low_balance_threshold ?? 0, 2) }}</small>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Procedure Info</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><span class="text-muted">Procedure:</span> {{ $surgery->procedure_name }}</p>
                    <p class="mb-1"><span class="text-muted">Surgeon:</span> {{ $surgery->surgeon_name ?? 'TBD' }}</p>
                    <p class="mb-1"><span class="text-muted">Scheduled:</span> {{ $surgery->scheduled_at?->format('d M, h:ia') ?? 'N/A' }}</p>
                    <p class="mb-1"><span class="text-muted">Started:</span> {{ $surgery->started_at?->format('d M, h:ia') ?? 'N/A' }}</p>
                    <p class="mb-1"><span class="text-muted">Completed:</span> {{ $surgery->completed_at?->format('d M, h:ia') ?? 'Pending' }}</p>
                    <p class="mb-0"><span class="text-muted">Materials:</span>
                        @if ($surgery->materials_used)
                            <ul class="ps-3 mb-0">
                                @foreach ($surgery->materials_used as $material)
                                    <li>{{ $material }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span>Not documented</span>
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
                    <h5 class="mb-0">Update Status</h5>
                    <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $surgery->status) }}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('theatre.surgeries.update', $surgery) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['scheduled' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'billed' => 'Billed'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('status', $surgery->status) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Surgeon</label>
                                <input type="text" name="surgeon_name" class="form-control" value="{{ old('surgeon_name', $surgery->surgeon_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Actual Cost (₦)</label>
                                <input type="number" step="0.01" min="0" name="actual_cost" class="form-control" value="{{ old('actual_cost', $surgery->actual_cost) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Materials (one per line)</label>
                                <textarea name="materials_used" class="form-control" rows="3">{{ old('materials_used', $surgery->materials_used ? implode("\n", $surgery->materials_used) : '') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="4">{{ old('notes', $surgery->notes) }}</textarea>
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
