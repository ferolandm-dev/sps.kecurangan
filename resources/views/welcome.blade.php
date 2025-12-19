@extends('layouts.app', [
'namePage' => '',
'class' => 'sidebar-mini background-none',
'activePage' => 'welcome',
])

@section('content')

<!-- =========================================
     MAIN WRAPPER (FULL BACKGROUND)
========================================= -->
<div class="welcome-content-bg">

    <!-- =========================================
         LOGO SPS
    ========================================== -->
    <div class="welcome-logo mt-4 fadeUp fade-delay-1">
        <img src="{{ asset('assets/img/SPS LOGO.png') }}" class="welcome-logo-img">
    </div>

    <!-- =========================================
         FANCY DATE (TANGGAL)
    ========================================== -->
    <div id="wibDate" class="welcome-date fancy-date fadeUp fade-delay-2"></div>

    <!-- =========================================
         CLOCK (WIB)
    ========================================== -->
    <div id="wibClock" class="welcome-clock fadeUp fade-delay-2"></div>

    <!-- =========================================
         GREETING UTAMA (PAGI/SIANG/SORE/MALAM)
    ========================================== -->
    <h1 class="welcome-title fadeUp fade-delay-3">
        <span id="greetingText"></span>, {{ strtoupper(auth()->user()->name) }}!
    </h1>

    <!-- =========================================
         MOOD TEXT (SESUAI WAKTU)
    ========================================== -->
    <p id="greetingMood" class="welcome-mood fadeUp fade-delay-4"></p>


    <!-- =========================================
         QUICK MENU (RANDOM 5 MENU SESUAI AKSES)
    ========================================== -->
    <div class="quick-menu fadeUp fade-delay-4">

        @php
        use Illuminate\Support\Str;
        use Illuminate\Support\Facades\Route;

        // Ambil menu sesuai akses user
        $accessibleMenus = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->where('can_access', 1)
        ->whereNotNull('sub_menu')
        ->pluck('sub_menu')
        ->toArray();

        // Acak dan ambil max 5 menu
        $randomMenus = collect($accessibleMenus)->shuffle()->take(5)->values();

        // Ambil semua route name
        $allRoutes = collect(Route::getRoutes())
        ->map(fn($r) => $r->getName())
        ->filter()
        ->values();
        @endphp

        @if(!$randomMenus->isEmpty())
        @foreach($randomMenus as $label)

        @php
        $label = trim((string) $label);
        $labelLower = strtolower($label);

        /* =========================================
        RULE KHUSUS UNTUK MENU ASS
        ========================================== */
        if (Str::contains($labelLower, 'ass')) {

        // Data ASS -> /ass/data
        if (Str::startsWith($labelLower, 'data ')) {
        $url = url('/ass/data');
        }
        // Master ASS -> /ass
        else {
        $url = url('/ass/data');
        }
        }

        /* =========================================
        RULE KHUSUS UNTUK MENU DATA LAIN
        ========================================== */
        elseif (Str::startsWith($labelLower, 'data ')) {

        $dataSlug = Str::slug(str_replace('data ', '', $labelLower));

        // Khusus Distributor -> plural
        if ($dataSlug === 'distributor') {
        $url = url('/distributor/data');
        } else {
        $url = url('/' . $dataSlug . '/data');
        }
        }

        /* =========================================
        RULE KHUSUS MASTER SALESMAN
        ========================================= */
        elseif ($labelLower === 'master salesman') {
            $url = url('/salesman/data');
        }
        
        /* =========================================
        RULE KHUSUS UNTUK "Report Kasus" & "Transaksi Kasus"
        ========================================= */
        elseif (Str::contains($labelLower, 'kasus')) {

        // Report Kasus -> kecurangan.data
        if (Str::contains($labelLower, 'report')) {
        $url = route('kecurangan.data');
        }

        // Transaksi Kasus -> kecurangan.index
        else {
        $url = route('kecurangan.index');
        }
        }

        /* =========================================
        ROUTE OTOMATIS NORMAL (DEFAULT)
        ========================================== */
        else {
        $words = array_filter(explode(' ', $labelLower));
        $filtered = array_values(array_filter($words, fn($w) => $w !== 'master'));

        $main = empty($filtered)
        ? (end($words) ?: $labelLower)
        : end($filtered);

        // Cari route berdasarkan kata terakhir
        $bestRoute = $allRoutes->first(fn($r) => Str::contains(strtolower($r), $main));

        // Jika tidak ketemu, coba berdasarkan kata pertama
        if (!$bestRoute && !empty($filtered)) {
        $first = $filtered[0];
        $bestRoute = $allRoutes->first(fn($r) =>
        Str::contains(strtolower($r), $first)
        );
        }

        $slug = Str::slug($main);

        // Jika route valid
        if ($bestRoute && Route::has($bestRoute)) {
        try {
        $url = route($bestRoute);
        } catch (\Throwable $e) {
        $url = url('/' . $slug);
        }
        }
        // Jika tidak ada route → manual slug
        else {
        $url = url('/' . $slug);
        }
        }
        @endphp

        <!-- Link menu -->
        <a href="{{ $url }}" class="quick-item">
            {{ $label }}
        </a>

        @endforeach
        @endif
    </div>


    <!-- =========================================
         CARD WELCOME / AKSES USER
    ========================================== -->
    <div class="welcome-card-container fadeUp fade-delay-3">

        @php
        // Cek apakah user punya minimal satu akses
        $hasAccess = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->where(fn($q) =>
        $q->where('can_access', 1)
        ->orWhere('can_create', 1)
        ->orWhere('can_edit', 1)
        ->orWhere('can_delete', 1)
        ->orWhere('can_print', 1)
        )
        ->exists();
        @endphp

        <div class="welcome-card fadeUp fade-delay-4">

            <i class="now-ui-icons emoticons_satisfied welcome-icon"></i>

            @if(!$hasAccess)
            <p class="welcome-desc">
                Anda berhasil masuk, namun akun Anda belum memiliki izin akses menu apa pun.
                Silakan hubungi administrator untuk mengaktifkan akses pada akun Anda.
            </p>
            @else
            <p class="welcome-desc">
                Selamat datang kembali! Waktunya tunjukkan performa terbaik dan maju bersama!
            </p>
            @endif

            <!-- Tombol logout -->
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="welcome-btn">
                <i class="now-ui-icons media-1_button-power"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>

        </div>
    </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/js/welcome.js') }}"></script>
@endpush