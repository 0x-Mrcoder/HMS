<x-doctor-layout>
    <x-slot name="header">
        Lab Results
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Test Name</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($labs as $lab)
                                    <tr>
                                        <td>{{ $lab->created_at->format('d M Y') }}</td>
                                        <td>
                                            <h6 class="mb-0">{{ $lab->visit->patient->full_name }}</h6>
                                        </td>
                                        <td>{{ $lab->test_name }}</td>
                                        <td>
                                            <span class="badge {{ $lab->priority === 'emergency' ? 'bg-danger-subtle text-danger' : ($lab->priority === 'urgent' ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info') }}">
                                                {{ ucfirst($lab->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $lab->status === 'completed' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                {{ ucfirst($lab->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $lab->result_summary ?? 'Pending' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No lab tests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $labs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-doctor-layout>
