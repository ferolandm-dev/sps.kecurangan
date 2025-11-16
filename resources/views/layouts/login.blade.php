<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $namePage ?? 'Login' }}</title>

    {{-- Font Awesome untuk ikon mata --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-UMQe5SNDVtJZ... (biarkan CDN mengisi)"
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Injected CSS dari halaman login --}}
    @stack('styles')

    <style>
        /* =====================================================
           🚫 FIX: Blok seluruh interferensi template Soft UI
        ====================================================== */

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important; /* Tidak bisa scroll */
            height: 100vh !important;
            background: #ffffff !important;
        }

        /* Matikan semua background bawaan Soft UI */
        .full-page,
        .full-page-background,
        .panel-header,
        .navbar,
        .sidebar,
        .main-panel {
            display: none !important;
        }

        /* Base wrapper */
        #app {
            width: 100%;
            height: 100vh;
            display: block;
            position: relative;
            background: #ffffff !important;
        }
    </style>
</head>

<body class="login-clean {{ $class ?? '' }}">

    <div id="app">
        @yield('content')
    </div>

    {{-- Inject JS dari halaman login --}}
    @stack('js')

</body>

</html>
