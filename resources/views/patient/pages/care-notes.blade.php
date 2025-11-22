@extends('layouts.patient')

@section('title', 'Care Notes')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Nursing Notes &amp; Care Updates</h4>
            <small class="text-muted">Updates shared by your care team.</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse ($nursingNotes as $note)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1 fw-semibold">{{ $note->note_type ?? 'General' }}</p>
                                <p class="mb-0">{{ $note->note }}</p>
                                <small class="text-muted">Nurse: {{ $note->nurse_name }}</small>
                            </div>
                            <small class="text-muted">{{ $note->recorded_at?->format('d M, h:ia') }}</small>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">No nursing notes shared.</li>
                @endforelse
            </ul>
        </div>
        <div class="card-footer">
            {{ $nursingNotes->links() }}
        </div>
    </div>
</div>
@endsection
