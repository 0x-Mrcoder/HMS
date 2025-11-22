@extends('layouts.auth')

@section('title', $portalConfig['label'] . ' Login')

@section('content')
@php
    $isAdminPortal = $portal === 'admin';
    $hospitalName = $hospitalConfig['name'] ?? 'Hospital';
    $hospitalLogo = $hospitalConfig['logo_path'] ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/logo-sm.png');
@endphp
<div class="container-xxl">
    @if($isAdminPortal)
        <div class="row vh-100 align-items-center">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="row g-0">
                        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center text-white p-5" style="background: radial-gradient(circle at 20% 20%, rgba(239,68,68,0.4), transparent 45%), linear-gradient(135deg, #0f172a, #111827);">
                            <div class="text-center">
                                <img src="{{ $hospitalLogo }}" alt="logo" class="mb-3" style="max-height:96px;">
                                <h1 class="fw-bold mb-2">{{ $hospitalName }}</h1>
                                <p class="lead text-light mb-4">{{ $portalConfig['tagline'] ?? 'Admin access to the HMS' }}</p>
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white bg-opacity-10 border border-light border-opacity-25">
                                    <i class="iconoir-shield-check fs-5"></i>
                                    <span class="fw-semibold">Administrator Sign In</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-4 p-lg-5">
                                <h4 class="fw-semibold mb-1">Admin Console</h4>
                                <p class="text-muted mb-4">Secure access for {{ $hospitalName }} administrators.</p>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="alert alert-soft-primary d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle fs-5"></i>
                                    <span>Demo: <strong>{{ $portal }}@hms.com</strong> / <strong>123456</strong></span>
                                </div>
                                <form class="mt-3" method="POST" action="{{ route('portal.login.' . $portal . '.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="username">Email address</label>
                                        <input type="email" class="form-control form-control-lg" id="username" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Password</label>
                                        <input type="password" class="form-control form-control-lg" name="password" id="userpassword" placeholder="••••••••" required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        <a href="javascript:void(0);" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-dark btn-lg" type="submit">Enter Admin Console</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($portal === 'patient')
        @php
            $primary = $hospitalConfig['primary_color'] ?? '#ef4444';
            $secondary = $hospitalConfig['secondary_color'] ?? '#0f172a';
        @endphp
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="rounded-4 h-100 p-5 d-flex align-items-center justify-content-center text-white position-relative overflow-hidden" style="background: radial-gradient(circle at 25% 25%, rgba(239,68,68,0.35), transparent 38%), radial-gradient(circle at 80% 10%, rgba(239,68,68,0.25), transparent 40%), linear-gradient(135deg, {{ $secondary }}, #0b1221);">
                    <div class="position-absolute bottom-0 start-0 end-0" style="height: 140px; background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.25) 100%);"></div>
                    <div class="text-center position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:88px;height:88px;background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);">
                            <img src="{{ $hospitalLogo }}" alt="logo" style="max-height:60px;">
                        </div>
                        <h2 class="fw-bold mb-2">Welcome to {{ $hospitalName }}</h2>
                        <p class="lead text-light mb-4">{{ $portalConfig['tagline'] ?? 'Stay close to your care team.' }}</p>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border border-light border-opacity-25" style="background: rgba(255,255,255,0.08);">
                            <i class="iconoir-heart fs-5"></i>
                            <span class="fw-semibold">Patient Portal</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;background: {{ $primary }}22; color: {{ $primary }};">
                                <i class="iconoir-user-badge fs-5"></i>
                            </span>
                            <div>
                                <p class="mb-0 text-muted text-uppercase small fw-semibold">Patient Login</p>
                                <h4 class="mb-0 fw-bold">{{ $hospitalName }}</h4>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Access appointments, wallet, labs, and prescriptions in one place.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="alert alert-soft-primary d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle fs-5"></i>
                            <span>Demo: <strong>{{ $portal }}@hms.com</strong> / <strong>123456</strong></span>
                        </div>
                        <form class="mt-3" method="POST" action="{{ route('portal.login.' . $portal . '.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="username">Email address</label>
                                <input type="email" class="form-control form-control-lg" id="username" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="userpassword">Password</label>
                                <input type="password" class="form-control form-control-lg" name="password" id="userpassword" placeholder="••••••••" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="javascript:void(0);" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" type="submit">Access Patient Hub</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">Need help? {{ $hospitalConfig['phone'] ?? 'Contact support' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="{{ route('dashboard') }}" class="logo logo-admin">
                                            <img src="{{ $hospitalLogo }}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $portalConfig['accent'] ?? 'primary' }}-subtle text-{{ $portalConfig['accent'] ?? 'primary' }} mb-2" style="width:56px;height:56px;">
                                            <i class="{{ $portalConfig['icon'] ?? 'iconoir-shield' }} fs-24"></i>
                                        </div>
                                        <h4 class="mt-2 mb-1 fw-semibold text-white fs-18">{{ $portalConfig['label'] }}</h4>
                                        <p class="text-muted fw-medium mb-0">{{ $portalConfig['tagline'] }}</p>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="alert alert-info border-info d-flex align-items-center gap-2">
                                        <i class="bi bi-info-circle fs-4"></i>
                                        <span>Demo credentials: <strong>{{ $portal }}@hms.com</strong> / <strong>123456</strong></span>
                                    </div>
                                    <form class="my-4" method="POST" action="{{ route('portal.login.' . $portal . '.store') }}">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Email address</label>
                                            <input type="email" class="form-control" id="username" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" required>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                                    <label class="form-check-label" for="remember">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <a href="javascript:void(0);" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="text-center mb-2">
                                        <p class="text-muted mb-0">All {{ $hospitalConfig['name'] ?? 'hospital' }} portals share the same credentials format.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
