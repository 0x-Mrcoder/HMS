<x-doctor-layout>
    <x-slot name="header">
        Theatre Requests
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Scheduled Date</th>
                                    <th>Patient</th>
                                    <th>Procedure</th>
                                    <th>Surgeon</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($surgeries as $surgery)
                                    <tr>
                                        <td>{{ $surgery->scheduled_at?->format('d M Y, h:ia') ?? 'TBD' }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $surgery->patient->full_name }}</h6>
                                        </td>
                                        <td>{{ $surgery->procedure_name }}</td>
                                        <td>{{ $surgery->surgeon_name ?? 'Unassigned' }}</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-capitalize">{{ str_replace('_', ' ', $surgery->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No theatre requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $surgeries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
