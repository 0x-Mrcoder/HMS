<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-startbar="light">

<head>
    @php
        $hospitalName = $hospitalConfig['name'] ?? 'Hospital';
        $hospitalTagline = $hospitalConfig['tagline'] ?? 'Staff Portal';
        $hospitalLogo = $hospitalConfig['logo_path'] ? asset($hospitalConfig['logo_path']) : asset('rizz-assets/images/favicon.ico');
    @endphp
    <meta charset="utf-8" />
    <title>@yield('title', 'Staff Portal') | {{ $hospitalName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ $hospitalLogo }}">
    <link href="{{ asset('rizz-assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('rizz-assets/css/app.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/iconoir@7.7.0/css/iconoir.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <script>
        // Check for saved theme preference on load
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme) {
            document.documentElement.setAttribute('data-bs-theme', storedTheme);
        }

        // Observer to save changes whenever the theme attribute gets updated by the template's existing JS
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-bs-theme') {
                    const newTheme = document.documentElement.getAttribute('data-bs-theme');
                    localStorage.setItem('theme', newTheme);
                }
            });
        });

        // Start observing the HTML element for attribute changes
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    </script>
    @stack('styles')
</head>

<body class="@yield('body-class', 'staff-portal')">
    @include('staff.partials.topbar')
    @include('staff.partials.startbar')

    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('rizz-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('rizz-assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
