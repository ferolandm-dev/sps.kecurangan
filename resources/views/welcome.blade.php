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
<style>
/* =========================================
   FULL BACKGROUND AREA
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
   LOGO (HEADER AREA)
========================================= */
.welcome-logo-img {
    height: clamp(60px, 12vw, 95px);
    width: auto;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.25));
}


/* =========================================
   GREETING TITLE (PAGI/SIANG/SORE/MALAM)
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
   CARD WRAPPER (CONTAINER)
========================================= */
.welcome-card-container {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: clamp(18px, 3vw, 30px);
}


/* =========================================
   WELCOME CARD (COMPACT WHITE CARD)
========================================= */
.welcome-card {
    max-width: 380px;
    width: 100%;

    background: #ffffff !important;
    border-radius: 18px;

    padding: clamp(14px, 3vw, 22px) clamp(12px, 3vw, 22px);

    border: 1px solid rgba(0, 0, 0, 0.05);

    box-shadow:
        0 8px 22px rgba(0, 0, 0, 0.06),
        0 16px 32px rgba(41, 177, 74, 0.10);

    text-align: center;
    backdrop-filter: none !important;
}


/* =========================================
   CARD ELEMENTS (ICON, TEXT)
========================================= */
.welcome-card-container {
    margin-top: clamp(12px, 2.5vw, 20px);
}

.welcome-icon {
    font-size: clamp(30px, 6vw, 48px);
    margin-bottom: clamp(8px, 2vw, 14px);
    color: #29b14a !important;
    text-shadow: none !important;
}

.welcome-desc {
    font-size: clamp(13px, 2.3vw, 15px);
    margin-bottom: clamp(14px, 3vw, 22px);
    line-height: 1.45;
}


/* =========================================
   LOGOUT BUTTON (POWER BUTTON)
========================================= */
.welcome-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: clamp(7px, 1.8vw, 10px) clamp(14px, 3.5vw, 20px);

    background: #ffffff !important;
    color: #29b14a !important;
    border: 2px solid #29b14a;

    border-radius: 40px;
    font-size: clamp(12px, 2vw, 15px);
    font-weight: 600;

    box-shadow: 0 4px 12px rgba(41, 177, 74, 0.15);

    text-decoration: none !important;
    transition: 0.25s ease;
}

.welcome-btn:hover {
    background: #ff1e00ff !important;
    color: #ffffff !important;
    border-color: #ff1e00ff;

    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(41, 177, 74, 0.35);
}

.welcome-btn i {
    font-size: 20px;
}


/* =========================================
   ENTRANCE ANIMATIONS
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
   EXTRA MOBILE TWEAKS
========================================= */
@media (max-width: 400px) {
    .welcome-card {
        border-radius: 18px;
    }
}


/* =========================================
   CLOCK (JAM WIB)
========================================= */
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


/* =========================================
   GREETING MOOD TEXT
========================================= */
.welcome-mood {
    font-size: clamp(12px, 2.5vw, 16px);
    font-weight: 500;
    color: #ffffff;

    margin-top: -25px !important;

    opacity: 0;
    transform: translateY(8px);
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


/* =========================================
   DATE STYLE
========================================= */
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


/* =========================================
   QUICK MENU ITEMS
========================================= */
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
    text-decoration: none !important;

    box-shadow:
        0 10px 28px rgba(41, 177, 74, 0.35),
        0 6px 14px rgba(0, 0, 0, 0.12);
}

.quick-item:focus,
.quick-item:active {
    color: #29b14a !important;
    text-decoration: none !important;
}

.quick-item i {
    font-size: 18px;
    color: #29b14a;
}


/* =========================================
   REMOVE BODY SCROLL (LANDING PAGE)
========================================= */
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
    padding-bottom: 6px !important;
}


/* =========================================
   MICRO SPACING OPTIMIZATION
========================================= */
.welcome-logo {
    margin-top: 4px !important;
}

.fancy-date {
    margin-top: 20px !important;
    font-size: clamp(12px, 2.4vw, 16px) !important;
}

.welcome-clock {
    margin-top: 0 !important;
    font-size: clamp(12px, 2.6vw, 17px) !important;
}

.welcome-title {
    margin-top: 15px !important;
}

.quick-menu {
    margin-top: 20px !important;
    gap: 7px !important;
}

.welcome-card {
    padding: 14px 16px !important;
    margin-top: 50px !important;
}
</style>
@endpush


@push('js')

<!-- =========================================
     REALTIME CLOCK WIB (JAM + DETIK)
========================================= -->
<script>
function updateWIBClock() {
    const now = new Date();

    // Konversi zona waktu ke WIB
    const wibTime = new Date(
        now.toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        })
    );

    const h = String(wibTime.getHours()).padStart(2, '0');
    const m = String(wibTime.getMinutes()).padStart(2, '0');
    const s = String(wibTime.getSeconds()).padStart(2, '0');

    document.getElementById("wibClock").textContent = `${h}:${m}:${s} WIB`;
}

// Render awal + interval tiap detik
updateWIBClock();
setInterval(updateWIBClock, 1000);
</script>



<!-- =========================================
     GREETING PAGI / SIANG / SORE / MALAM
========================================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {

    function setGreeting() {
        const now = new Date();

        // Ambil waktu WIB
        const wib = new Date(
            now.toLocaleString("en-US", {
                timeZone: "Asia/Jakarta"
            })
        );
        const hour = wib.getHours();

        let greeting = "";
        let mood = "";

        // Penentuan waktu salam
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

        // Update teks greeting
        const targetGreeting = document.getElementById("greetingText");
        if (targetGreeting) targetGreeting.textContent = greeting;

        // Update mood/kesan
        const targetMood = document.getElementById("greetingMood");
        if (targetMood) targetMood.textContent = mood;
    }

    // Render pertama
    setGreeting();

    // Update setiap 30 menit
    setInterval(setGreeting, 30 * 60 * 1000);
});
</script>



<!-- =========================================
     FANCY DATE WIB (HARI, TANGGAL, BULAN, TAHUN)
========================================= -->
<script>
function updateWIBDate() {
    const now = new Date();

    // Mengubah waktu JS ke WIB
    const wib = new Date(
        now.toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        })
    );

    const hari = [
        "Minggu", "Senin", "Selasa", "Rabu",
        "Kamis", "Jumat", "Sabtu"
    ];

    const bulan = [
        "Januari", "Februari", "Maret", "April",
        "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"
    ];

    const text =
        `${hari[wib.getDay()]}, ${wib.getDate()} ${bulan[wib.getMonth()]} ${wib.getFullYear()}`;

    document.getElementById("wibDate").textContent = text;
}

// Render pertama + update tiap jam
updateWIBDate();
setInterval(updateWIBDate, 3600000);
</script>

@endpush