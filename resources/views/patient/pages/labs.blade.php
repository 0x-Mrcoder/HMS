@extends('layouts.patient')

@section('title', 'Lab Results')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Lab Results &amp; Diagnostics</h4>
            <small class="text-muted">Track your test orders and results.</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Test</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Department</th>
                            <th>When</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labTests as $test)
                            <tr>
                                <td class="fw-semibold">{{ $test->test_name }}</td>
                                <td><span class="badge bg-warning-subtle text-capitalize">{{ str_replace('_', ' ', $test->status) }}</span></td>
                                <td>{{ $test->result_summary ?? 'Awaiting result' }}</td>
                                <td>{{ $test->visit?->department?->name ?? 'Laboratory' }}</td>
                                <td>{{ $test->result_at?->format('d M Y, h:ia') ?? $test->created_at->format('d M Y, h:ia') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('patient.portal.labs.show', $test) }}" class="btn btn-soft-secondary btn-sm">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No lab records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $labTests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
