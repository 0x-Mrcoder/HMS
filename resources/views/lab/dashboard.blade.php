<x-lab-layout>
    <x-slot name="header">Lab Dashboard</x-slot>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 fw-medium mb-1">Pending Tests</p>
                            <h3 class="mb-0 fw-bold text-white">{{ $metrics['pending_tests'] }}</h3>
                        </div>
                        <div class="avatar-md bg-white text-primary rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-test-tube fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 fw-medium mb-1">Completed Today</p>
                            <h3 class="mb-0 fw-bold text-white">{{ $metrics['completed_today'] }}</h3>
                        </div>
                        <div class="avatar-md bg-white text-success rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-check-circle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-medium mb-1">Today's Revenue</p>
                            <h3 class="mb-0 fw-bold">₦{{ number_format($metrics['revenue_today'], 2) }}</h3>
                        </div>
                        <div class="avatar-md bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-coin fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Recent Test Requests</h4>
                    <a href="{{ route('lab.portal.requests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Test Name</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $test)
                                    <tr>
                                        <td>{{ $test->created_at->format('M d, H:i') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $test->visit->patient->photo_url ?? asset('rizz-assets/images/users/avatar-1.jpg') }}" class="thumb-sm rounded-circle me-2" alt="">
                                                <div>
                                                    <h6 class="mb-0 fs-14">{{ $test->visit->patient->first_name }} {{ $test->visit->patient->last_name }}</h6>
                                                    <small class="text-muted">{{ $test->visit->patient->hospital_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-medium">{{ $test->test_name }}</td>
                                        <td>
                                            @if($test->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                            @elseif($test->status == 'in_progress')
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">In Progress</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('lab.portal.requests.show', $test->id) }}" class="btn btn-sm btn-soft-primary">
                                                Process <i class="iconoir-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No pending test requests. Good job!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-lab-layout>
