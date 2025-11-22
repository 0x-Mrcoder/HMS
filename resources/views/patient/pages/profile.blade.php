@extends('layouts.patient')

@section('title', 'Profile & Contacts')

@section('content')
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Profile &amp; Contacts</h4>
            <small class="text-muted">Your personal and emergency details.</small>
        </div>
        <a href="{{ route('patient.portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <img src="{{ $patient->photo_url ?? asset('rizz-assets/images/users/user-4.jpg') }}" alt="patient photo" class="rounded-circle mb-3 shadow-sm" style="width:110px;height:110px;object-fit:cover;">
                    <h5 class="mb-0">{{ $patient->full_name }}</h5>
                    <p class="text-muted mb-3">{{ ucfirst($patient->gender) }} · {{ $patient->date_of_birth?->format('d M Y') ?? 'DOB not set' }}</p>
                    <p class="mb-1"><i class="iconoir-phone me-2"></i>{{ $patient->phone }}</p>
                    <p class="mb-1"><i class="iconoir-mail me-2"></i>{{ $patient->email }}</p>
                    <p class="mb-0"><i class="iconoir-pin me-2"></i>{{ $patient->address }}, {{ $patient->city }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.portal.profile.update') }}" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name', $patient->middle_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $patient->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                            <small class="text-muted">JPG/PNG, max 2MB.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address', $patient->address) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="{{ old('city', $patient->city) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="state" value="{{ old('state', $patient->state) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" class="form-control" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Hospital ID</p>
                            <h6 class="mb-0">{{ $patient->hospital_id }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Card Number</p>
                            <h6 class="mb-0">{{ $patient->card_number }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Wallet Minimum Balance</p>
                            <h6 class="mb-0">₦{{ number_format($patient->wallet_minimum_balance ?? 0, 2) }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Blood Group</p>
                            <h6 class="mb-0">{{ $patient->blood_group ?? 'Not captured' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Genotype</p>
                            <h6 class="mb-0">{{ $patient->genotype ?? 'Not captured' }}</h6>
                        </div>
                        <div class="col-12">
                            <p class="text-muted mb-1">Allergies</p>
                            <p class="mb-0">{{ $patient->allergies ?? 'No allergies recorded.' }}</p>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
