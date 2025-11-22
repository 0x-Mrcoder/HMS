@extends('layouts.patient')

@section('title', 'Insurance Claims')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Insurance Claims</h4>
            <small class="text-muted">Claims and co-pay deductions</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Policy</th>
                            <th>Provider</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Approved</th>
                            <th>Co-Pay</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                            <tr>
                                <td>{{ $claim->policy_number }}</td>
                                <td>{{ $claim->provider }}</td>
                                <td><span class="badge bg-secondary-subtle text-capitalize">{{ $claim->claim_status }}</span></td>
                                <td>₦{{ number_format($claim->total_amount ?? 0, 2) }}</td>
                                <td>₦{{ number_format($claim->approved_amount ?? 0, 2) }}</td>
                                <td>₦{{ number_format($claim->co_pay_amount ?? 0, 2) }}</td>
                                <td>{{ $claim->updated_at->format('d M Y, h:ia') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No claims recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $claims->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
