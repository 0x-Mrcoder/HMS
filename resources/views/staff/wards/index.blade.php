@extends('layouts.staff')

@section('title', 'Ward Management')

@section('content')
<div class="container-xxl">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h4 class="fw-bold mb-1">Ward & Bed Management</h4>
            <p class="text-muted small mb-0">Monitor hospital occupancy and bed availability in real-time.</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i> Refresh</button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-hospital fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted text-uppercase fs-11 fw-semibold mb-1">Total Beds</p>
                            <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-lg fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted text-uppercase fs-11 fw-semibold mb-1">Available</p>
                            <h4 class="mb-0 fw-bold">{{ $stats['available'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-bed fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted text-uppercase fs-11 fw-semibold mb-1">Occupied</p>
                            <h4 class="mb-0 fw-bold">{{ $stats['occupied'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Occupancy Rate -->
        <div class="col-6 col-md-3">
             <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                           <div class="avatar-sm bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-pie-chart fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted text-uppercase fs-11 fw-semibold mb-1">Occupancy</p>
                            <h4 class="mb-0 fw-bold">
                                {{ $stats['total'] > 0 ? round(($stats['occupied'] / $stats['total']) * 100) : 0 }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wards Grid -->
    <div class="row">
        @foreach($wards as $ward)
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title fw-bold mb-0 text-primary">{{ $ward->name }}</h6>
                        <small class="text-muted">{{ ucfirst($ward->type) }} Ward</small>
                    </div>
                    <span class="badge bg-light text-dark border">Capacity: {{ $ward->capacity }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($ward->beds as $bed)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="p-3 border rounded text-center position-relative overflow-hidden {{ $bed->status == 'available' ? 'bg-success-subtle border-success-subtle' : ($bed->status == 'occupied' ? 'bg-danger-subtle border-danger-subtle' : 'bg-secondary-subtle') }}">
                                    <!-- Status Indicator Dot -->
                                    <span class="position-absolute top-0 end-0 p-1">
                                        <span class="p-1 rounded-circle d-inline-block {{ $bed->status == 'available' ? 'bg-success' : ($bed->status == 'occupied' ? 'bg-danger' : 'bg-secondary') }}"></span>
                                    </span>
                                    
                                    <i class="bi bi-hospital fs-4 mb-2 d-block {{ $bed->status == 'available' ? 'text-success' : ($bed->status == 'occupied' ? 'text-danger' : 'text-muted') }}"></i>
                                    <h6 class="fs-14 fw-bold mb-0">{{ $bed->number }}</h6>
                                    <small class="d-block mt-1 fs-11 text-uppercase fw-semibold {{ $bed->status == 'available' ? 'text-success' : ($bed->status == 'occupied' ? 'text-danger' : 'text-muted') }}">
                                        {{ $bed->status }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
