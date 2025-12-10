<x-lab-layout>
    <x-slot name="header">Lab Analytics & Reports</x-slot>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-white-50">Tests Today</p>
                            <h3 class="mb-0 fw-bold">{{ $testsToday }}</h3>
                        </div>
                        <div class="avatar-sm bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-test-tube fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-white-50">Revenue Today</p>
                            <h3 class="mb-0 fw-bold">₦{{ number_format($revenueToday) }}</h3>
                        </div>
                        <div class="avatar-sm bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-hand-cash fs-24 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted">Tests This Month</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $testsMonth }}</h3>
                        </div>
                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-calendar fs-24 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted">Revenue This Month</p>
                            <h3 class="mb-0 fw-bold text-dark">₦{{ number_format($revenueMonth) }}</h3>
                        </div>
                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                            <i class="iconoir-graph-up fs-24 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Tests Table -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="card-title text-dark mb-0">Top 5 Most Frequent Tests</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Test Name</th>
                                    <th class="text-end pe-4">Total Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topTests as $test)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $test->test_name }}</td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $test->total }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No data available yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Completed Tests -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="card-title text-dark mb-0">Recently Completed</h5>
                </div>
                <div class="card-body p-0">
                     <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Patient</th>
                                    <th>Test</th>
                                    <th class="text-end pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCompleted as $recent)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($recent->visit->patient->first_name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-13">{{ $recent->visit->patient->first_name ?? 'Unknown' }}</h6>
                                                    <span class="text-muted fs-11">{{ $recent->visit->patient->hospital_id ?? '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($recent->test_name, 20) }}</td>
                                        <td class="text-end pe-4 text-muted fs-12">
                                            {{ $recent->updated_at->format('M d, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No recently completed tests.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-lab-layout>
