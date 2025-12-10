<x-lab-layout>
    <x-slot name="header">Test Requests</x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <ul class="nav nav-pills bg-light rounded p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'pending' || !request('status') ? 'active' : '' }}" href="{{ route('lab.portal.requests.index', ['status' => 'pending']) }}">
                                    Pending
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'in_progress' ? 'active' : '' }}" href="{{ route('lab.portal.requests.index', ['status' => 'in_progress']) }}">
                                    In Progress
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('lab.portal.requests.index', ['status' => 'completed']) }}">
                                    History
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}" href="{{ route('lab.portal.requests.index', ['status' => 'all']) }}">
                                    All Tests
                                </a>
                            </li>
                        </ul>

                        <form action="{{ route('lab.portal.requests.index') }}" method="GET" class="d-flex" style="max-width: 300px;">
                            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search Patient ID or Name..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit"><i class="iconoir-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Test Name</th>
                                    <th>Ref. Doctor</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($labTests as $test)
                                    <tr>
                                        <td>
                                            <span class="text-body fw-bold">{{ $test->created_at->format('M d') }}</span><br>
                                            <small class="text-muted">{{ $test->created_at->format('H:i A') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('lab.portal.requests.index', ['patient_id' => $test->visit->patient_id, 'status' => 'all']) }}" class="text-dark fw-medium text-decoration-underline" title="View Patient History">
                                                {{ $test->visit->patient->first_name }} {{ $test->visit->patient->last_name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $test->visit->patient->hospital_id }}</small>
                                        </td>
                                        <td class="fw-medium">{{ $test->test_name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-1">
                                                    <span class="fs-10 fw-bold">Dr</span>
                                                </div>
                                                <span>{{ $test->visit->doctor->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($test->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending Payment</span>
                                            @elseif($test->status == 'in_progress')
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">Processing</span>
                                            @elseif($test->status == 'completed')
                                                 <span class="badge bg-success-subtle text-success border border-success-subtle">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($test->status == 'completed')
                                                <a href="{{ route('lab.portal.requests.print', $test->id) }}" target="_blank" class="btn btn-sm btn-soft-secondary me-1" title="Print Receipt">
                                                    <i class="iconoir-printer"></i>
                                                </a>
                                                <a href="{{ route('lab.portal.requests.show', $test->id) }}" class="btn btn-sm btn-soft-primary">
                                                    View
                                                </a>
                                            @else
                                                <a href="{{ route('lab.portal.requests.show', $test->id) }}" class="btn btn-sm btn-primary">
                                                    Process <i class="iconoir-arrow-right"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="iconoir-test-tube fs-30 text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No requests found</h5>
                                            <p class="text-muted fs-12">There are no requests with this status currently.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $labTests->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-lab-layout>
