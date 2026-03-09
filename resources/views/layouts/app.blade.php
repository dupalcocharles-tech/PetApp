<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PawPoint</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Page-specific styles --}}
    @yield('styles')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        html.dark-theme,
        body.dark-theme {
            background-color: #000;
        }
        body {
            border-top: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
        body > *:first-child {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Chart.js (must be loaded before your chart code) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- AngularJS only if needed --}}
    @yield('angular-scripts')

    {{-- Page-specific scripts --}}
    @yield('scripts')
</body>
</html>
