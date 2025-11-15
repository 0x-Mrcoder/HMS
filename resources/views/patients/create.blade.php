@extends('layouts.admin')

@section('title', 'Register Patient')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('patients.store') }}" class="card">
                @csrf
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">New Patient</h4>
                        <small class="text-muted">Capture demographic and wallet preferences</small>
                    </div>
                    <a href="{{ route('patients.index') }}" class="btn btn-soft-secondary">Back to list</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                            @error('first_name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                            @error('last_name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Other Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="" disabled selected>Select</option>
                                <option value="male" @selected(old('gender')==='male')>Male</option>
                                <option value="female" @selected(old('gender')==='female')>Female</option>
                            </select>
                            @error('gender')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
                            @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Residential Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">City/Town</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <select name="state" class="form-select">
                                <option value="" selected disabled>Select state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" @selected(old('state')===$state)>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">LGA</label>
                            <input type="text" name="lga" value="{{ old('lga') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group</label>
                            <input type="text" name="blood_group" value="{{ old('blood_group') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Genotype</label>
                            <input type="text" name="genotype" value="{{ old('genotype') }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Allergies / Medical Notes</label>
                            <textarea name="allergies" rows="2" class="form-control">{{ old('allergies') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NHIS Number</label>
                            <input type="text" name="nhis_number" value="{{ old('nhis_number') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Minimum Wallet Balance (₦)</label>
                            <input type="number" step="0.01" name="wallet_minimum_balance" value="{{ old('wallet_minimum_balance', 2000) }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="submit">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
