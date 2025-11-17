@extends('layouts.app', [
'namePage' => 'Welcome',
'class' => 'login-page sidebar-mini',
'activePage' => 'welcome',
'hideNavbar' => true,
])

@section('content')

<div class="welcome-content-bg d-flex flex-column align-items-center justify-content-start pt-5">

    {{-- LOGO SPS --}}
    <div class="welcome-logo mt-3 mb-2">
        <img src="{{ asset('assets/img/SPS LOGO.png') }}" class="welcome-logo-img fadeUp fade-delay-1">
    </div>

    <div class="welcome-top-text text-center mt-1">
        <h1 class="welcome-title fadeUp fade-delay-2">
            WELCOME, {{ strtoupper(auth()->user()->name) }}!
        </h1>
    </div>

    <div class="container d-flex justify-content-center mt-4">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="welcome-card fadeUp fade-delay-3">

                @php
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

                <div class="text-center welcome-inner fadeUp fade-delay-4">
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

                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="welcome-btn">
                        Keluar dari Akun
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
/* FULL PAGE GRADIENT */
.welcome-content-bg {
    width: 100%;
    min-height: 100vh;
    padding-bottom: 80px;
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%);
}

/* LOGO */
.welcome-logo-img {
    height: 95px;
    width: auto;
    display: block;
    margin: 0 auto;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.25));
}

/* default fadeUp: invisible -> slide up -> visible */
.fadeUp {
    opacity: 0;
    transform: translateY(18px);
    /* gunakan custom property --delay sehingga mudah diatur */
    animation-name: fadeUp;
    animation-duration: 0.65s;
    animation-timing-function: ease;
    animation-fill-mode: forwards;
    /* penting: pertahankan state akhir */
    animation-delay: var(--delay, 0s);
    will-change: transform, opacity;
}

/* predefined delay helpers */
.fade-delay-0 {
    --delay: 0s;
}

.fade-delay-1 {
    --delay: 0.08s;
}

.fade-delay-2 {
    --delay: 0.16s;
}

.fade-delay-3 {
    --delay: 0.28s;
}

.fade-delay-4 {
    --delay: 0.40s;
}

/* keyframes mengatur visibility juga supaya tidak "flash" sebelum animasi dimulai */
@keyframes fadeUp {
    0% {
        opacity: 0;
        transform: translateY(18px);
        visibility: hidden;
    }

    1% {
        /* supaya saat animasi mulai elemen jadi visible */
        visibility: visible;
    }

    100% {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
    }
}

/* TITLE */
.welcome-title {
    font-size: 42px;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 2px;
    text-shadow:
        0 3px 6px rgba(0, 0, 0, 0.20),
        0 6px 12px rgba(0, 0, 0, 0.15);
}

/* CARD */
.welcome-card {
    background: rgba(255, 255, 255, 0.90);
    backdrop-filter: blur(12px);
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 45px 38px;
    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.06),
        0 22px 45px rgba(41, 177, 74, 0.10);
}

/* ICON */
.welcome-icon {
    font-size: 62px;
    color: #29b14a;
    margin-bottom: 17px;
}

/* TEXT */
.welcome-desc {
    color: #333;
    font-size: 16.8px;
    line-height: 1.55;
    margin-bottom: 32px;
}

/* BUTTON */
.welcome-btn {
    background: linear-gradient(135deg, #29b14a 0%, #dbd300 100%) !important;
    border: none;
    color: #fff;
    padding: 12px 35px;
    border-radius: 50px;
    font-weight: 600;
    box-shadow: 0 6px 22px rgba(41, 177, 74, 0.38);
    transition: .3s ease;
}

.welcome-btn:hover {
    background: linear-gradient(135deg, #29b14a 0%, #dbd300 100%) !important;
    color: #fff !important;
    transform: translateY(-3px);
    box-shadow: 0 14px 35px rgba(41, 177, 74, 0.45);
}

/* KEYFRAMES */
@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush