<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/SPS LOGO.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/SPS LOGO.png') }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>SCS</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"
        name="viewport" />

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous">

    <!-- CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/now-ui-dashboard.css?v=1.3.0') }}" rel="stylesheet" />

    <!-- Demo CSS -->
    <link href="{{ asset('assets/demo/demo.css') }}" rel="stylesheet" />

    {{-- STACK: Custom Styles --}}
    @stack('styles')
</head>

<body class="{{ $class ?? '' }}">

    <div class="wrapper">
        @auth
            @include('layouts.page_template.auth')
        @endauth

        @guest
            @include('layouts.page_template.guest')
        @endguest
    </div>

    <!-- ============================================ -->
    <!--              CORE JS FILES                   -->
    <!-- ============================================ -->
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- ============================================================= -->
    <!--   IMPORTANT: custom JS (ui-lock.js & sidebar-fix.js) MUST RUN FIRST -->
    <!-- ============================================================= -->

    {{-- STACK: Custom JS --}}
    @stack('js')

    <!-- ============================================================= -->
    <!--                PERFECT SCROLLBAR (if needed)                  -->
    <!--      (Letakkan SETELAH custom JS agar tidak menimpa scroll)   -->
    <!-- ============================================================= -->
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

    <!-- Notifications -->
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

    <!-- Now UI Dashboard Core -->
    <script src="{{ asset('assets/js/now-ui-dashboard.min.js?v=1.3.0') }}"></script>

    <!-- Demo (optional) -->
    <script src="{{ asset('assets/demo/demo.js') }}"></script>
</body>

</html>
