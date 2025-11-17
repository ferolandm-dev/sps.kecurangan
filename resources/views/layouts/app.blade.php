<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/SPS LOGO.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SPS LOGO.png') }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>SPS KECURANGAN</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"
          name="viewport" />

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

    <!-- Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/now-ui-dashboard.css?v=1.3.0') }}" rel="stylesheet" />
    <link href="{{ asset('assets/demo/demo.css') }}" rel="stylesheet" />

    @stack('styles')
</head>

<body class="{{ $class ?? '' }}">
    <div class="wrapper">

        @auth

            @if( isset($hideNavbar) && $hideNavbar === true )

                {{-- SIDEBAR TETAP --}}
                @include('layouts.navbars.sidebar')

                {{-- MAIN CONTENT TANPA NAVBAR --}}
                <div class="main-panel" style="width: calc(100% - 260px);">
                    @yield('content')
                </div>

            @else
                {{-- NORMAL AUTH LAYOUT (sidebar + navbar lengkap) --}}
                @include('layouts.page_template.auth')
            @endif

        @endauth


        @guest
            {{-- LAYOUT GUEST (login, register) --}}
            @include('layouts.page_template.guest')
        @endguest

    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

    <!-- Now UI Dashboard -->
    <script src="{{ asset('assets/js/now-ui-dashboard.min.js?v=1.3.0') }}"></script>

    <!-- Demo -->
    <script src="{{ asset('assets/demo/demo.js') }}"></script>

    @stack('js')
</body>

</html>
