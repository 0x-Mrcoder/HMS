<x-pharmacy-layout>
    <x-slot name="header">Stock Movement Logs: {{ $drug->name }}</x-slot>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">History for {{ $drug->name }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Change</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i') }}</td>
                                <td>{{ $log->user_name }}</td>
                                <td>
                                    @if($log->type == 'in')
                                        <span class="badge bg-success-subtle text-success">Stock In</span>
                                    @elseif($log->type == 'out')
                                        <span class="badge bg-danger-subtle text-danger">Stock Out</span>
                                    @elseif($log->type == 'dispensed')
                                        <span class="badge bg-info-subtle text-info">Dispensed</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">Adjustment</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $log->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $log->quantity_change > 0 ? '+' : '' }}{{ $log->quantity_change }}
                                </td>
                                <td>{{ $log->notes }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No history found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-pharmacy-layout>
