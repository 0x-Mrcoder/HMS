@extends('layouts.admin')

@section('title', 'Laboratory Queue')

@section('content')
<div class="container-xxl">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-3 gap-3">
        <div>
            <h3 class="mb-0">Laboratory Workbench</h3>
            <small class="text-muted">Track diagnostics workflow and wallet deductions</small>
        </div>
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end">
            <div class="col-12">
                <input type="search" class="form-control" name="q" value="{{ $search }}" placeholder="Search test or patient">
            </div>
            <div class="col-12">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $key => $label)
                        <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Filter</button>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-3">
        @foreach(['pending' => 'warning', 'in_progress' => 'info', 'completed' => 'success'] as $state => $color)
            <div class="col-md-4">
                <div class="card border-{{ $color }}-subtle">
                    <div class="card-body">
                        <p class="text-muted text-uppercase mb-1">{{ ucwords(str_replace('_', ' ', $state)) }}</p>
                        <h3 class="mb-0">{{ number_format($statusCounts[$state] ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Test Orders</h5>
            <span class="text-muted small">{{ $labTests->total() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Test</th>
                        <th>Technician</th>
                        <th>Charge</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($labTests as $test)
                        <tr>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $test->visit->patient->full_name }}</p>
                                <small class="text-muted">{{ $test->visit->patient->hospital_id }}</small>
                            </td>
                            <td>
                                <p class="mb-0">{{ $test->test_name }}</p>
                                <small class="text-muted">{{ $test->result_summary ?? 'Awaiting result' }}</small>
                            </td>
                            <td>{{ $test->technician_name ?? '—' }}</td>
                            <td>₦{{ number_format($test->charge_amount, 2) }}</td>
                            <td><span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('laboratory.tests.show', $test) }}" class="btn btn-soft-primary btn-sm">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No test orders logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $labTests->links() }}
        </div>
    </div>
</div>
@endsection
