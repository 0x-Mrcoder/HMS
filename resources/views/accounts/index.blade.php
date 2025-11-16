@extends('layouts.admin')

@section('title', 'Accounts & Billing')

@section('content')
<div class="container-xxl">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Income</p>
                    <h3 class="mb-0">₦{{ number_format($summary['income'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Expense</p>
                    <h3 class="mb-0">₦{{ number_format($summary['expense'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-widget">
                <div class="card-body">
                    <p class="text-muted mb-1">Net Cash Flow</p>
                    <h3 class="mb-0">₦{{ number_format(($summary['income'] ?? 0) - ($summary['expense'] ?? 0), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Capture Transaction</h5>
            @if (session('status'))
                <span class="text-success">{{ session('status') }}</span>
            @endif
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('accounts.records.store') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-select">
                        <option value="">Optional</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Optional</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Channel</label>
                    <select name="payment_channel" class="form-select">
                        @foreach(['wallet','cash','pos','transfer','online'] as $channelOption)
                            <option value="{{ $channelOption }}">{{ strtoupper($channelOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="record_type" class="form-select">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reference" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Recorded By</label>
                    <input type="text" name="recorded_by" class="form-control" placeholder="Accounts">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Recorded At</label>
                    <input type="datetime-local" name="recorded_at" class="form-control">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Save Entry</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
                <div class="col-12">
                    <label class="form-label">Channel</label>
                    <select name="channel" class="form-select">
                        <option value="">All</option>
                        @foreach(['wallet','cash','pos','transfer','online'] as $channelOption)
                            <option value="{{ $channelOption }}" @selected($channel === $channelOption)>{{ strtoupper($channelOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="income" @selected($type === 'income')>Income</option>
                        <option value="expense" @selected($type === 'expense')>Expense</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">From</label>
                    <input type="date" name="from" class="form-control" value="{{ optional($from)->format('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">To</label>
                    <input type="date" name="to" class="form-control" value="{{ optional($to)->format('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Financial Records</h5>
            <span class="text-muted small">{{ $records->total() }} entries</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Details</th>
                        <th>Patient</th>
                        <th>Channel</th>
                        <th>Type</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>{{ $record->recorded_at->format('d M, h:ia') }}</td>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $record->description ?? 'No description' }}</p>
                                <small class="text-muted">Ref: {{ $record->reference ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $record->patient?->full_name ?? 'N/A' }}</td>
                            <td>{{ strtoupper($record->payment_channel) }}</td>
                            <td><span class="badge {{ $record->record_type === 'income' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">{{ ucfirst($record->record_type) }}</span></td>
                            <td class="text-end">₦{{ number_format($record->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No financial records captured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
