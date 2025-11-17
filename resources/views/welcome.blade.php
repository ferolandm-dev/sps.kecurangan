@extends('layouts.app', [
'namePage' => '',
'class' => 'sidebar-mini',
'activePage' => 'welcome',
])

@section('content')

<div class="welcome-content-bg">

    {{-- LOGO SPS --}}
    <div class="welcome-logo mt-4 fadeUp fade-delay-1">
        <img src="{{ asset('assets/img/SPS LOGO.png') }}" class="welcome-logo-img">
    </div>

    {{-- TITLE --}}
    <h1 class="welcome-title fadeUp fade-delay-2">
        WELCOME, {{ strtoupper(auth()->user()->name) }}!
    </h1>

    {{-- CARD CONTAINER --}}
    <div class="welcome-card-container fadeUp fade-delay-3">
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
<style>
/* =========================================
   FULL BACKGROUND
========================================= */
.welcome-content-bg {
    width: 100%;
    min-height: 100vh;
    padding: clamp(20px, 5vw, 60px) 20px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;

    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%);
    background-size: cover !important;
    background-position: center !important;
}

/* =========================================
   LOGO
========================================= */
.welcome-logo-img {
    height: clamp(60px, 12vw, 95px);
    width: auto;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.25));
}

/* =========================================
   TITLE
========================================= */
.welcome-title {
    font-size: clamp(22px, 5vw, 42px);
    font-weight: 800;
    color: #ffffff;
    text-align: center;
    letter-spacing: clamp(1px, 0.6vw, 2px);

    margin-top: clamp(8px, 1vw, 18px);

    text-shadow:
        0 3px 6px rgba(0, 0, 0, 0.20),
        0 6px 12px rgba(0, 0, 0, 0.15);
}

/* =========================================
   CARD WRAPPER
========================================= */
.welcome-card-container {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: clamp(18px, 3vw, 30px);
}

/* =========================================
   CARD
========================================= */
.welcome-card {
    width: 100%;
    max-width: 460px;

    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(12px);

    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.25);

    padding: clamp(25px, 5vw, 45px) clamp(20px, 4vw, 38px);

    text-align: center;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.06),
        0 22px 45px rgba(41, 177, 74, 0.10);

    transition: 0.3s ease;
}

/* ICON */
.welcome-icon {
    font-size: clamp(40px, 7vw, 62px);
    color: #29b14a;
    margin-bottom: clamp(12px, 3vw, 17px);
}

/* DESCRIPTION */
.welcome-desc {
    color: #333;
    font-size: clamp(14px, 2.6vw, 17px);
    line-height: 1.55;
    margin-bottom: clamp(20px, 4vw, 32px);
}

/* BUTTON */
.welcome-btn {
    background: linear-gradient(135deg, #29b14a 0%, #dbd300 100%);
    padding: clamp(10px, 2.2vw, 12px) clamp(26px, 5vw, 35px);
    border-radius: 50px;
    color: #fff;
    font-weight: 600;

    font-size: clamp(14px, 2.4vw, 17px);

    box-shadow: 0 6px 22px rgba(41, 177, 74, 0.38);
    transition: .25s ease;
}

.welcome-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 35px rgba(41, 177, 74, 0.45);
}

/* =========================================
   ANIMATION
========================================= */
.fadeUp {
    opacity: 0;
    transform: translateY(18px);
    animation: fadeUp .65s ease forwards;
    animation-delay: var(--delay, 0s);
}

.fade-delay-1 {
    --delay: 0.1s;
}

.fade-delay-2 {
    --delay: 0.25s;
}

.fade-delay-3 {
    --delay: 0.4s;
}

.fade-delay-4 {
    --delay: 0.55s;
}

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* =========================================
   SUPER SMOOTH RESPONSIVE BREAKPOINTS
========================================= */
@media (max-width: 400px) {
    .welcome-card {
        border-radius: 18px;
    }
}
</style>
@endpush