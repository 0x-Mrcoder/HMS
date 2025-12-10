@extends('layouts.auth')

@section('title', $portalConfig['label'] . ' Login')

@section('content')
@php
    $isAdminPortal = $portal === 'admin';
    $hospitalName = $hospitalConfig['name'] ?? 'Hospital';
    $hospitalLogo = $hospitalConfig['logo_path'] ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/logo-sm.png');
@endphp
    @if($isAdminPortal)
    <div class="container-xxl">
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
    </div>
    @elseif($portal === 'patient')
    <div class="container-xxl">
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
    </div>
    @else
    <div class="container-fluid p-0">
        <div class="row g-0 vh-100">
            <!-- Left Side: Brand/Welcome -->
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden">
                 <!-- Background Image with Parallax Effect -->
                 <div class="position-absolute w-100 h-100" style="background-image: url('{{ asset('rizz-assets/images/auth-bg.jpg') }}'); background-size: cover; background-position: center; z-index: -2;"></div>
                 
                 <!-- Colored Overlay - Using strict CSS Variable for Color Matching -->
                 <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, var(--bs-{{ $portalConfig['accent'] ?? 'primary' }}) 0%, #000000 100%); opacity: 0.85; z-index: -1;"></div>
                 
                 <!-- Decorative Pattern -->
                 <div class="position-absolute w-100 h-100" style="background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 20px 20px; opacity: 0.3; z-index: -1;"></div>

                 <div class="text-center z-1 text-white p-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4 shadow-lg" style="width:100px;height:100px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        <i class="{{ $portalConfig['icon'] ?? 'iconoir-shield' }} fs-48"></i>
                    </div>
                    <h1 class="fw-bold display-5 mb-3">{{ $portalConfig['label'] }} Portal</h1>
                    <p class="lead text-white-50 fs-18" style="max-width: 400px; margin: 0 auto;">{{ $portalConfig['tagline'] ?? 'Secure access to your dashboard.' }}</p>
                 </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center bg-white">
                <div class="card-body p-5" style="max-width: 500px; width: 100%;">
                    <div class="mb-5">
                        <a href="{{ route('home') }}" class="d-block mb-4">
                            <img src="{{ $hospitalLogo }}" alt="logo" height="40">
                        </a>
                        <h3 class="fw-bold mb-1">Welcome Back!</h3>
                        <p class="text-muted">Sign in to continue to {{ $hospitalName }}.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('portal.login.' . $portal . '.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <label class="form-label" for="password">Password</label>
                                <a href="javascript:void(0);" class="text-muted small">Forgot password?</a>
                            </div>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-{{ $portalConfig['accent'] ?? 'primary' }} btn-lg fw-medium" type="submit">
                                Log In
                            </button>
                        </div>
                    </form>

                    <div class="mt-5 text-center">
                        <p class="text-muted mb-0">© {{ date('Y') }} {{ $hospitalName }}. <br><span class="small">Design & Develop by Rizz</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
