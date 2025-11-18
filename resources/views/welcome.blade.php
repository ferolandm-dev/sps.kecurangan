@extends('layouts.app', [
'namePage' => '',
'class' => 'sidebar-mini background-none',
'activePage' => 'welcome',
])


@section('content')

<div class="welcome-content-bg">

    {{-- LOGO SPS --}}
    <div class="welcome-logo mt-4 fadeUp fade-delay-1">
        <img src="{{ asset('assets/img/SPS LOGO.png') }}" class="welcome-logo-img">
    </div>

    {{-- FANCY DATE --}}
    <div id="wibDate" class="welcome-date fancy-date fadeUp fade-delay-2"></div>

    {{-- JAM WAKTU INDONESIA BARAT --}}
    <div id="wibClock" class="welcome-clock fadeUp fade-delay-2"></div>


    <h1 class="welcome-title fadeUp fade-delay-4">
        <span id="greetingText"></span>, {{ strtoupper(auth()->user()->name) }}!
    </h1>

    <p id="greetingMood" class="welcome-mood fadeUp fade-delay-4"></p>

    {{-- QUICK MENU RANDOM 5 ITEM SESUAI AKSES + ROUTE OTOMATIS (FIXED) --}}
    <div class="quick-menu fadeUp fade-delay-3">
        @php
        use Illuminate\Support\Str;
        use Illuminate\Support\Facades\Route;

        // Ambil semua menu yang user punya akses
        $accessibleMenus = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->where('can_access', 1)
        ->whereNotNull('sub_menu')
        ->pluck('sub_menu')
        ->toArray();

        // Shuffle dan ambil 5 item (pastikan koleksi terdefinisi)
        $randomMenus = collect($accessibleMenus)->shuffle()->take(5)->values();

        // Ambil semua nama route Laravel (named routes)
        $allRoutes = collect(Route::getRoutes())
        ->map(fn($r) => $r->getName())
        ->filter()
        ->values();
        @endphp

        {{-- jika tidak ada menu yg bisa diakses, tampilkan pesan singkat atau kosong --}}
        @if($randomMenus->isEmpty())
        {{-- optional: tampilkan nothing atau placeholder --}}
        @else
        @foreach($randomMenus as $label)
        @php
        $label = trim((string) $label);
        $labelLower = strtolower($label);

        // === KHUSUS ASS (MASTER & DATA) ===
        if (Str::contains($labelLower, 'ass')) {

        // DATA ASS → /asisten_manager/data
        if (Str::startsWith($labelLower, 'data ')) {
        $url = url('/asisten_manager/data');
        }

        // MASTER ASS → /asisten_manager
        else {
        $url = url('/asisten_manager');
        }
        }

        // === KHUSUS MENU DATA LAINNYA ===
        elseif (Str::startsWith($labelLower, 'data ')) {
        $dataSlug = Str::slug(str_replace('data ', '', $labelLower));

        if ($dataSlug === 'distributor') {
        $url = url('/distributors/data'); // khusus distributor plural
        } else {
        $url = url('/' . $dataSlug . '/data');
        }
        }

        // === ROUTE OTOMATIS NORMAL ===
        else {
        $words = array_filter(explode(' ', $labelLower), fn($w) => $w !== '');
        $filtered = array_values(array_filter($words, fn($w) => $w !== 'master'));

        $main = empty($filtered) ? (end($words) ?: $labelLower) : end($filtered);

        $bestRoute = $allRoutes->first(fn($r) => Str::contains(strtolower($r), $main));

        if (!$bestRoute && !empty($filtered)) {
        $first = $filtered[0];
        $bestRoute = $allRoutes->first(fn($r) => Str::contains(strtolower($r), $first));
        }

        $slug = Str::slug($main);

        if ($bestRoute && Route::has($bestRoute)) {
        try {
        $url = route($bestRoute);
        } catch (\Throwable $e) {
        $url = url('/' . $slug);
        }
        } else {
        $url = url('/' . $slug);
        }
        }
        @endphp

        <a href="{{ $url }}" class="quick-item">
            {{ $label }}
        </a>

        @endforeach
        @endif
    </div>


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

/* ===============================
   WELCOME CARD (COMPACT + SOLID WHITE)
=============================== */

.welcome-card {
    max-width: 380px;
    width: 100%;

    background: #ffffff !important;
    /* 🔥 FULL WHITE, NO TRANSPARENCY */
    border-radius: 18px;

    padding: clamp(14px, 3vw, 22px) clamp(12px, 3vw, 22px);

    border: 1px solid rgba(0, 0, 0, 0.05);
    /* border lembut */

    box-shadow:
        0 8px 22px rgba(0, 0, 0, 0.06),
        0 16px 32px rgba(41, 177, 74, 0.10);

    backdrop-filter: none !important;

    text-align: center;
    /* 🔥 matikan blur */
}

/* Card container lebih kecil */
.welcome-card-container {
    margin-top: clamp(12px, 2.5vw, 20px);
}

/* Icon lebih kecil */
.welcome-icon {
    font-size: clamp(30px, 6vw, 48px);
    margin-bottom: clamp(8px, 2vw, 14px);
}

/* Deskripsi compact */
.welcome-desc {
    font-size: clamp(13px, 2.3vw, 15px);
    margin-bottom: clamp(14px, 3vw, 22px);
    line-height: 1.45;
}

.welcome-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: clamp(7px, 1.8vw, 10px) clamp(14px, 3.5vw, 20px);

    background: #ffffff !important;
    /* 🔥 background putih */
    color: #29b14a !important;
    /* icon hijau */
    border: 2px solid #29b14a;
    /* 🔥 border hijau */
    border-radius: 40px;

    font-size: clamp(12px, 2vw, 15px);
    font-weight: 600;

    box-shadow: 0 4px 12px rgba(41, 177, 74, 0.15);
    /* soft shadow */
    transition: 0.25s ease;

    text-decoration: none !important;
}

/* Hover effect */
.welcome-btn:hover {
    background: #ff1e00ff !important;
    /* hijau */
    color: #ffffff !important;
    /* icon putih */
    border-color: #ff1e00ff;

    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(41, 177, 74, 0.35);
}

/* Icon power lebih besar & rapi */
.welcome-btn i {
    font-size: 20px;
}

.welcome-icon {
    color: #29b14a !important;
    -webkit-text-stroke: 0px transparent;
    text-shadow: none !important;
    /* jika mau hilangkan shadow */
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

/* CLOCK */
.welcome-clock {
    font-size: clamp(16px, 3.2vw, 22px);
    font-weight: 600;
    color: #ffffff;
    margin-top: 5px;
    letter-spacing: 1px;
    text-shadow:
        0 2px 4px rgba(0, 0, 0, 0.20),
        0 4px 8px rgba(0, 0, 0, 0.12);
}

.welcome-greet {
    font-size: clamp(18px, 4vw, 28px);
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 5px;
    text-shadow:
        0 2px 4px rgba(0, 0, 0, 0.20),
        0 4px 8px rgba(0, 0, 0, 0.18);
}

/* ===============================
   FANCY DATE STYLE
=============================== */
.fancy-date {
    font-size: clamp(15px, 3vw, 20px);
    font-weight: 600;
    color: #ffffff;
    margin-top: 6px;
    text-align: center;

    text-shadow:
        0 2px 4px rgba(0, 0, 0, 0.20),
        0 4px 8px rgba(0, 0, 0, 0.15);
}

/* ===============================
   QUICK MENU / SHORTCUT
=============================== */
.quick-menu {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-top: clamp(18px, 4vw, 28px);
}

.quick-item {
    display: flex;
    align-items: center;
    gap: 6px;

    padding: 9px 15px;
    background: rgba(255, 255, 255, 0.92);
    border-radius: 14px;
    color: #29b14a;
    font-weight: 600;
    text-decoration: none !important;
    /* 🔥 Hilangkan underline */

    font-size: clamp(13px, 2.4vw, 15px);

    box-shadow:
        0 4px 12px rgba(0, 0, 0, 0.10),
        0 6px 18px rgba(41, 177, 74, 0.15);

    transition: 0.25s ease;
}

.quick-item:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 1);
    color: #29b14a !important;
    /* 🔥 Matikan hover merah */
    text-decoration: none !important;
    /* 🔥 Tetap tanpa underline */

    box-shadow:
        0 10px 28px rgba(41, 177, 74, 0.35),
        0 6px 14px rgba(0, 0, 0, 0.12);
}

.quick-item:focus,
.quick-item:active {
    color: #29b14a !important;
    /* 🔥 Tidak berubah warna saat ditekan */
    text-decoration: none !important;
}

.quick-item i {
    font-size: 18px;
    color: #29b14a;
}

/* ===============================
   GREETING MOOD TEXT
=============================== */
.welcome-mood {
    font-size: clamp(12px, 2.5vw, 16px);
    /* lebih kecil */
    font-weight: 500;
    color: #ffffff;
    margin-top: -25px !important;
    /* lebih rapat ke title */
    opacity: 0;
    transform: translateY(8px);
    /* lebih kecil biar smooth */
    transition: opacity 0.6s ease, transform 0.6s ease;
    text-align: center;

    text-shadow:
        0 2px 4px rgba(0, 0, 0, 0.20),
        0 4px 8px rgba(0, 0, 0, 0.15);
}

.welcome-mood.show {
    opacity: 1;
    transform: translateY(0);
}

/* ===============================
   FIX: HILANGKAN SCROLL DI HALAMAN
=============================== */

html,
body {
    height: 100%;
    overflow: hidden;
}

.welcome-content-bg {
    min-height: 100vh;
    height: 100vh;
    overflow: hidden;
    padding-top: 14px !important;
    /* lebih kecil lagi */
    padding-bottom: 6px !important;
}

/* Kurangi jarak antar elemen */
.welcome-logo {
    margin-top: 4px !important;
}

/* Fancy date lebih hemat tempat */
.fancy-date {
    margin-top: 20px !important;
    font-size: clamp(12px, 2.4vw, 16px) !important;
}

/* Jam lebih kecil */
.welcome-clock {
    margin-top: 0 !important;
    font-size: clamp(12px, 2.6vw, 17px) !important;
}

.welcome-title {
    margin-top: 15px !important;
    /* rapat */
}

.quick-menu {
    margin-top: 20px !important;
    gap: 7px !important;
    /* lebih rapat */
}

/* Card lebih compact */
.welcome-card {
    padding: 14px 16px !important;
    margin-top: 50px !important;
}
</style>
@endpush

@push('js')
<script>
function updateWIBClock() {
    const now = new Date();

    // Konversi ke WIB (GMT+7)
    const wibTime = new Date(now.toLocaleString("en-US", {
        timeZone: "Asia/Jakarta"
    }));

    let h = String(wibTime.getHours()).padStart(2, '0');
    let m = String(wibTime.getMinutes()).padStart(2, '0');
    let s = String(wibTime.getSeconds()).padStart(2, '0');

    document.getElementById("wibClock").textContent = `${h}:${m}:${s} WIB`;
}

// Update awal
updateWIBClock();

// Update setiap 1 detik untuk menampilkan detik
setInterval(updateWIBClock, 1000);
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {

    function setGreeting() {
        const now = new Date();

        // Waktu WIB
        const wib = new Date(
            now.toLocaleString("en-US", {
                timeZone: "Asia/Jakarta"
            })
        );
        const hour = wib.getHours();

        let greeting = "";
        let mood = "";

        if (hour >= 5 && hour < 11) {
            greeting = "PAGI";
            mood = "Semangat memulai hari!";
        } else if (hour >= 11 && hour < 15) {
            greeting = "SIANG";
            mood = "Jaga fokus, tetap produktif!";
        } else if (hour >= 15 && hour < 18) {
            greeting = "SORE";
            mood = "Sedikit lagi menuju selesai.";
        } else {
            greeting = "MALAM";
            mood = "Terima kasih atas kerja keras hari ini.";
        }

        // Update Greeting Utama
        const targetGreeting = document.getElementById("greetingText");
        if (targetGreeting) {
            targetGreeting.textContent = greeting;
        }

        // Update Mood Text
        const targetMood = document.getElementById("greetingMood");
        if (targetMood) {
            targetMood.textContent = mood;
        }
    }

    // Jalankan saat halaman dimuat
    setGreeting();

    // Update setiap 30 menit
    setInterval(setGreeting, 30 * 60 * 1000);

});
</script>

<script>
// ======== FANCY DATE WIB =========
function updateWIBDate() {
    const now = new Date();

    const wib = new Date(now.toLocaleString("en-US", {
        timeZone: "Asia/Jakarta"
    }));

    const hari = [
        "Minggu", "Senin", "Selasa", "Rabu",
        "Kamis", "Jumat", "Sabtu"
    ];

    const bulan = [
        "Januari", "Februari", "Maret", "April",
        "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"
    ];

    const dayName = hari[wib.getDay()];
    const day = wib.getDate();
    const month = bulan[wib.getMonth()];
    const year = wib.getFullYear();

    document.getElementById("wibDate").textContent =
        `${dayName}, ${day} ${month} ${year}`;
}

// initial render
updateWIBDate();

// update setiap 1 jam
setInterval(updateWIBDate, 3600000);
</script>


@endpush