@php
    $isSurgeon = \App\Models\Doctor::where('user_id', Auth::id())->first()?->department?->name === 'Surgery';
@endphp
<x-doctor-layout>
    <x-slot name="header">
        {{ $isSurgeon ? 'Theatre Management' : 'Theatre Requests' }}
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($isSurgeon)
                        <div class="alert alert-soft-primary d-flex align-items-center mb-4">
                            <i class="bi bi-info-circle me-2 fs-4"></i>
                            <div>
                                <strong>Surgeon's Workspace:</strong> Manage your operation list, start surgeries, and document post-op notes here.
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                         <!-- <h5 class="card-title mb-0">Surgery Schedule</h5> -->
                         <form method="GET" action="{{ route('doctor.portal.theatre-requests') }}" class="d-flex w-100 w-md-auto">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search patient name..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                         </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Scheduled Date</th>
                                    <th>Patient</th>
                                    <th>Procedure</th>
                                    <th>Surgeon</th>
                                    <th>Status</th>
                                    @if($isSurgeon) <th>Actions</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($surgeries as $surgery)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $surgery->scheduled_at?->format('d M Y') ?? 'TBD' }}</div>
                                            <small class="text-muted">{{ $surgery->scheduled_at?->format('h:ia') }}</small>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $surgery->patient->full_name }}</h6>
                                            <small class="text-muted">{{ $surgery->patient->hospital_id }}</small>
                                        </td>
                                        <td>{{ $surgery->procedure_name }}</td>
                                        <td>{{ $surgery->surgeon_name ?? 'Unassigned' }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($surgery->status) {
                                                    'completed' => 'bg-success-subtle text-success',
                                                    'in_progress' => 'bg-info-subtle text-info',
                                                    'scheduled' => 'bg-warning-subtle text-warning',
                                                    'cancelled' => 'bg-danger-subtle text-danger',
                                                    default => 'bg-secondary-subtle text-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-capitalize">{{ str_replace('_', ' ', $surgery->status) }}</span>
                                        </td>
                                        @if($isSurgeon)
                                            <td>
                                                @if($surgery->status === 'scheduled')
                                                    <a href="{{ route('doctor.portal.surgeries.manage', $surgery) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-play-circle"></i> Start
                                                    </a>
                                                @elseif($surgery->status === 'in_progress')
                                                    <a href="{{ route('doctor.portal.surgeries.manage', $surgery) }}" class="btn btn-sm btn-success">
                                                        <i class="bi bi-pencil-square"></i> Continue
                                                    </a>
                                                @elseif($surgery->status === 'completed')
                                                    <a href="{{ route('doctor.portal.surgeries.manage', $surgery) }}" class="btn btn-sm btn-soft-secondary">
                                                        <i class="bi bi-file-text"></i> Report
                                                    </a>
                                                    <button class="btn btn-sm btn-soft-dark ms-1" onclick="window.open('{{ route('doctor.portal.surgeries.print', $surgery) }}', '_blank')" title="Print Report">
                                                        <i class="bi bi-printer"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isSurgeon ? 6 : 5 }}" class="text-center text-muted py-4">No theatre requests found.</td>
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
