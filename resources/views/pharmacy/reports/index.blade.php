<x-pharmacy-layout>
    <x-slot name="header">Sales Reports</x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pharmacy.portal.reports.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="iconoir-filter me-1"></i> Filter Reports
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total Revenue</p>
                    <h3 class="fw-bold text-success">₦{{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total Items Dispensed</p>
                    <h3 class="fw-bold text-primary">{{ number_format($totalItems) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detailed Sales History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Drug</th>
                                    <th>Patient</th>
                                    <th>Qty</th>
                                    <th>Total (₦)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->dispensed_at->format('M d, H:i') }}</td>
                                        <td class="fw-medium">{{ $sale->drug_name }}</td>
                                        <td>{{ $sale->visit->patient->first_name }} {{ $sale->visit->patient->last_name }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td class="fw-bold">₦{{ number_format($sale->total_cost, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No sales found for this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Selling Drugs</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($salesByDrug->take(5) as $name => $data)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-medium d-block">{{ $name }}</span>
                                    <small class="text-muted">{{ $data['quantity'] }} units sold</small>
                                </div>
                                <span class="fw-bold">₦{{ number_format($data['revenue'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-pharmacy-layout>
