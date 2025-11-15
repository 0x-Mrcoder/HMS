@extends('layouts.admin')

@section('title', 'Visits')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Visits &amp; Clinical Queue</h4>
                        <small class="text-muted">Monitor OPD, IPD and Emergency activities</small>
                    </div>
                    <div>
                        <form method="GET" class="d-flex">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'billed' => 'Billed'] as $value => $label)
                                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Department</th>
                                <th>Doctor</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Scheduled</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visits as $visit)
                                <tr>
                                    <td>{{ $visit->patient->full_name }}</td>
                                    <td>{{ $visit->department->name }}</td>
                                    <td>{{ $visit->doctor_name ?? '—' }}</td>
                                    <td class="text-uppercase">{{ $visit->visit_type }}</td>
                                    <td><span class="badge bg-info-subtle text-capitalize">{{ str_replace('_', ' ', $visit->status) }}</span></td>
                                    <td>{{ $visit->scheduled_at?->format('d M, h:ia') ?? '—' }}</td>
                                    <td class="text-end"><a href="{{ route('visits.show', $visit) }}" class="btn btn-soft-primary btn-sm">Open</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No visit records.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
