<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />

    <link rel="stylesheet" href="{{ asset('css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hope-ui.css?v=1.0') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=1.1.0') }}">
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari 6 – 15.3, Edge */
            color-adjust: exact !important;
            /* Firefox 48 – 96 */
            print-color-adjust: exact !important;
            /* Firefox 97+, Safari 15.4+ */
        }
    </style>
    @stack('styles')
</head>

<body style="background-color: white; margin: 2em;">
    <div class="d-flex justify-content-center">
        <svg width="50" class="text-primary" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
            <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
        </svg>
        <h1 class="ms-2 logo-title">{{ config('app.name')}}</h1>
    </div>
    <hr>
    <div class="header-title">
        <h3 class="card-title text-center">@yield('report-title')</h3>
    </div>
    <div id="wrapper">
        {{ $slot }}
    </div>
    <script src="{{ asset('js/libs.min.js') }}"></script>
    <script src="{{ asset('js/charts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/hope-ui.js') }}"></script>
    <script src="{{ asset('js/modelview.js') }}"></script>
    @stack('scripts')
</body>

</html>
