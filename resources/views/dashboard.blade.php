@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini',
'activePage' => 'dashboard',
])

@section('content')

<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">

    {{-- =========================================================
        DASHBOARD VIEW SELECTOR (NAVBAR STYLE)
    ========================================================== --}}
    <div class="row fade-up" data-animate>
        <div class="col-12">
            <div class="view-selector-bar-wrapper">
                <div class="view-selector-bar" role="tablist" aria-label="Dashboard views">

                    <button class="view-btn active" data-view="overview" role="tab" aria-selected="true">
                        <i class="now-ui-icons business_chart-bar-32"></i>
                        <span class="label">Overview</span>
                    </button>

                    <button class="view-btn" data-view="summary" role="tab" aria-selected="false">
                        <i class="now-ui-icons business_bulb-63"></i>
                        <span class="label">Ringkasan</span>
                    </button>

                    <button class="view-btn" data-view="tables" role="tab" aria-selected="false">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                        <span class="label">Tabel Data</span>
                    </button>

                    <button class="view-btn" data-view="calendar" role="tab" aria-selected="false">
                        <i class="now-ui-icons ui-1_calendar-60"></i>
                        <span class="label">Heatmap Kecurangan</span>
                    </button>

                </div>
            </div>
        </div>
    </div>


    {{-- =========================================================
        SECTION: OVERVIEW
    ========================================================== --}}
    <div id="section-overview">

        {{-- Sticky Chart --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="sticky-container">
                    <div class="chart-sticky-wrapper fade-up" data-animate>
                        <div class="glass-panel">
                            <canvas id="bigDashboardChart" style="width:100%; height:320px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Sanksi Per Kuartal --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card glass-card fade-up" data-animate>
                    <div class="card-header pb-0">
                        <h5 class="card-category">Analisis Kuartal</h5>
                        <h4 class="card-title">Sanksi Per Kuartal</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="quarterSanksiChart" style="width:100%; height:280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Harian --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card glass-card fade-up" data-animate>
                    <div class="p-4">
                        <h5 class="card-category">Progress Harian Kuartal {{ $currentQuarter }}</h5>
                        <h4 class="card-title">{{ $progressQuarterDay }}%</h4>

                        <div class="progress mt-3"
                            style="height:18px; border-radius:12px; background:rgba(41,177,74,0.12);">
                            <div class="progress-bar"
                                style="width:{{ $progressQuarterDay }}%; background:linear-gradient(90deg,#29b14a,#c7c500); border-radius:12px;">
                            </div>
                        </div>

                        <p class="mt-3 text-muted" style="font-size:13px;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Updated
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        SECTION: SUMMARY CARDS
    ========================================================== --}}
    <div id="section-summary" style="display:none;">

        <div class="row">

            {{-- Distributor Aktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Distributor Aktif</h5>
                            <h4 class="card-title">{{ $totalDistributorAktif }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons arrows-1_refresh-69"></i> Updated
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ASS Aktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Total ASS Aktif</h5>
                            <h4 class="card-title">{{ $totalAssAktif }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons arrows-1_refresh-69"></i> Updated
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales Aktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Total Sales Aktif</h5>
                            <h4 class="card-title">{{ $totalSalesAktif }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons arrows-1_refresh-69"></i> Updated
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kecurangan Bulan Ini --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Kecurangan Bulan Ini</h5>
                            <h4 class="card-title">{{ $totalKecuranganBulanIni }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons ui-2_time-alarm"></i> {{ now()->translatedFormat('F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Nilai --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Nilai Sanksi Bulan Ini</h5>
                            <h4 class="card-title">Rp {{ number_format($totalNilaiSanksiBulanIni,0,',','.') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons ui-1_calendar-60"></i> {{ now()->translatedFormat('F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rata-rata --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Rata-Rata Sanksi Bulan Ini</h5>
                            <h4 class="card-title">Rp {{ number_format($avgSanksiBulanIni,0,',','.') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons ui-1_calendar-60"></i> {{ now()->translatedFormat('F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tren Kecurangan --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">

                            <h5 class="card-category">Tren Kecurangan Bulan Ini</h5>

                            <h4 class="card-title" style="font-weight:700;">
                                @if($trendFraud > 0)
                                <span style="color:#e63946;">↑ {{ $trendFraud }}%</span>
                                @elseif($trendFraud < 0) <span style="color:#29b14a;">↓ {{ abs($trendFraud) }}%</span>
                                    @else
                                    <span style="color:#555;">0% (Stabil)</span>
                                    @endif
                            </h4>

                        </div>
                        <div class="card-body">
                            <div class="stats mt-2">
                                <i class="now-ui-icons arrows-1_refresh-69"></i> Dibanding Bulan Lalu
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


    {{-- =========================================================
        SECTION: TABLES (LEADERBOARD + RECENT)
    ========================================================== --}}
    <div id="section-tables" style="display:none;">
        <div class="row">

            {{-- TOP DISTRIBUTOR --}}
            <div class="col-md-6 mb-4">
                <div class="card glass-card fade-up h-100" data-animate>

                    <div class="card-header">
                        <h5 class="card-category">Top 5 Distributor</h5>
                        <h4 class="card-title">Jumlah Sales Aktif</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th>ID Distributor</th>
                                        <th style="min-width:300px;">Nama Distributor</th>
                                        <th class="text-right">Jumlah Sales</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($topDistributors as $d)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->distributor }}</td>
                                        <td class="text-right">
                                            <span class="badge-soft">{{ $d->total_sales }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

            {{-- TOP SALES CURANG --}}
            <div class="col-md-6 mb-4">
                <div class="card glass-card fade-up h-100" data-animate>

                    <div class="card-header">
                        <h5 class="card-category">Top 5 Sales Curang</h5>
                        <h4 class="card-title">Jumlah Kecurangan</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th>ID Sales</th>
                                        <th>Nama Sales</th>
                                        <th class="text-right">Kecurangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($topFraudSales as $s)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $s->id_sales }}</td>
                                        <td>{{ $s->nama_sales }}</td>
                                        <td class="text-right">
                                            <span class="badge-soft">{{ $s->total_kecurangan }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

            {{-- LEADERBOARD DISTRIBUTOR --}}
            <div class="col-lg-6 mb-4">
                <div class="card glass-card fade-up h-100" data-animate>

                    <div class="card-header pb-0">
                        <h5 class="card-category">Top 5 Distributor Bermasalah</h5>
                        <h4 class="card-title">Leaderboard Distributor</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th style="min-width:400px;">Distributor</th>
                                        <th class="text-right">Jumlah Kasus</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($leaderboardDistributor as $index => $d)
                                    <tr>
                                        <td class="text-center">
                                            <span class="rank-badge rank-{{ $index+1 }}">
                                                {{ $index+1 }}
                                            </span>
                                        </td>
                                        <td>{{ $d->distributor }}</td>
                                        <td class="text-right">
                                            <span class="badge-soft">{{ $d->total_kecurangan }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>

            {{-- KASUS TERBARU --}}
            <div class="col-md-6 mb-4">
                <div class="card glass-card fade-up h-100" data-animate>

                    <div class="card-header pb-0">
                        <h5 class="card-category">Top 5 Kasus Terbaru</h5>
                        <h4 class="card-title">Kasus Terbaru</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x:auto;">

                            <table class="table table-hover align-middle" style="min-width:1050px;">
                                <thead>
                                    <tr>
                                        <th style="min-width:120px;">Tanggal</th>
                                        <th style="min-width:150px;">ID Sales</th>
                                        <th style="min-width:230px;">Nama Sales</th>
                                        <th style="min-width:350px;">Distributor</th>
                                        <th>Pelanggaran</th>
                                        <th class="text-right" style="min-width:150px;">Nilai</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($recentFraudCases as $f)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($f->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $f->id_sales }}</td>
                                        <td>{{ $f->nama_sales }}</td>
                                        <td>{{ $f->distributor }}</td>
                                        <td>{{ $f->jenis_sanksi ?? 'Non-Sanksi' }}</td>
                                        <td class="text-right">
                                            <span class="badge-soft">
                                                Rp {{ number_format($f->nilai_sanksi,0,',','.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


    {{-- =========================================================
        SECTION: CALENDAR HEATMAP
    ========================================================== --}}
    <div id="section-calendar" style="display:none;">
        <div class="row">
            <div class="col-lg-12 mb-4">

                <div class="card glass-card fade-up" data-animate>

                    <div class="card-header pb-0">
                        <h5 class="card-category">Heatmap Kecurangan</h5>
                        <h4 class="card-title">Calendar View</h4>
                    </div>

                    <div class="card-body">

                        <div id="fraudCalendar" class="calendar-heatmap"></div>
                        <div id="heatmapTooltip" class="heatmap-tooltip"></div>

                        <div class="heatmap-legend mt-4 d-flex align-items-center flex-wrap gap-3">

                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-box" style="background:#e9ecef;"></div>
                                <span class="legend-label">0</span>
                            </div>

                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-box" style="background:rgba(41,177,74,0.25);"></div>
                                <span class="legend-label">1 - 2</span>
                            </div>

                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-box" style="background:rgba(41,177,74,0.55);"></div>
                                <span class="legend-label">3 - 5</span>
                            </div>

                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-box" style="background:rgba(41,177,74,0.75);"></div>
                                <span class="legend-label">6 - 10</span>
                            </div>

                            <div class="legend-item d-flex align-items-center">
                                <div class="legend-box" style="background:rgba(41,177,74,1);"></div>
                                <span class="legend-label">> 10</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ============================================================
   GLOBAL PAGE BACKGROUND
============================================================ */
body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
}

.panel-header-sps,
.content {
    background: transparent !important;
    box-shadow: none !important;
}

/* ============================================================
   FADE-UP ANIMATION
============================================================ */
.fade-up {
    opacity: 0;
    transform: translateY(10px);
}

.fade-up.in-view {
    animation: fadeUp .6s forwards;
}

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================================
   GLASS CARDS / GLASS PANEL STYLE
============================================================ */
.glass-panel,
.glass-card {
    background: rgba(255, 255, 255, 0.92);
    border-radius: 14px;
    border: 1px solid rgba(41, 177, 74, 0.12);
}

/* ============================================================
   CARD TILT EFFECT
============================================================ */
.card-tilt {
    perspective: 1000px;
}

.tilt-inner {
    transform-style: preserve-3d;
    transition: transform .2s;
}

/* ============================================================
   TABLE HEADER STYLE
============================================================ */
.table thead th {
    color: #29b14a;
    font-weight: 700;
    white-space: nowrap;
}

/* ============================================================
   BADGE SOFT STYLE
============================================================ */
.badge-soft {
    background: rgba(41, 177, 74, 0.14);
    color: #29b14a;
    padding: 6px 10px;
    border-radius: 8px;
}

/* ============================================================
   VIEW SELECTOR (NAVBAR-STYLE)
============================================================ */
.view-selector-bar-wrapper {
    margin-bottom: 18px;
}

.view-selector-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 140px;
    gap: 8px;

    padding: 8px 14px;
    border-radius: 12px;
    cursor: pointer;

    font-weight: 700;
    color: #2f2f2f;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(41, 177, 74, 0.12);

    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    transition: all .18s ease;
}

.view-btn i {
    font-size: 18px;
    color: rgba(41, 177, 74, 0.9);
}

/* Active button */
.view-btn.active {
    background: linear-gradient(135deg, #29b14a, #34d058);
    color: #fff;
    border: none;
    transform: translateY(-3px);
    box-shadow: 0 10px 26px rgba(41, 177, 74, 0.22);
}

.view-btn.active i {
    color: #fff;
}

/* Hover effect */
.view-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
}

/* Mobile layout */
@media (max-width: 576px) {
    .view-btn {
        flex: 1 1 100%;
        padding-left: 12px;
        min-width: auto;
        justify-content: flex-start;
    }
}

/* Remove focus outline */
.view-btn:focus,
.view-btn:active {
    outline: none !important;
    box-shadow: none !important;
}

/* ============================================================
   CALENDAR HEATMAP
============================================================ */
.calendar-heatmap {
    display: grid;
    grid-template-columns: repeat(53, 14px);
    grid-gap: 3px;

    margin-top: 20px;
    padding: 20px;

    width: max-content;
    max-width: 100%;
    overflow-x: auto;

    background: rgba(255, 255, 255, 0.92);
    border-radius: 14px;
}

.calendar-day {
    width: 14px;
    height: 14px;
    border-radius: 3px;
    background: #e9ecef;
    transition: .2s ease;
}

.calendar-day.level-1 {
    background: rgba(41, 177, 74, 0.25);
}

.calendar-day.level-2 {
    background: rgba(41, 177, 74, 0.55);
}

.calendar-day.level-3 {
    background: rgba(41, 177, 74, 0.75);
}

.calendar-day.level-4 {
    background: rgba(41, 177, 74, 1);
}

.calendar-day:hover {
    transform: scale(1.25);
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);
}

/* ============================================================
   HEATMAP TOOLTIP
============================================================ */
.heatmap-tooltip {
    position: fixed;
    z-index: 99999;
    pointer-events: none;

    padding: 6px 10px;
    font-size: 12px;

    background: rgba(0, 0, 0, 0.80);
    color: #fff;
    border-radius: 6px;

    opacity: 0;
    transform: translateY(-6px);
    transition: opacity .15s ease, transform .15s ease;
}

/* ============================================================
   HEATMAP LEGEND
============================================================ */
.heatmap-legend {
    margin-top: 14px;
    padding: 10px 15px;
    border-radius: 12px;

    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    align-items: center;

    background: rgba(255, 255, 255, 0.85);
}

.legend-item {
    display: flex;
    gap: 6px;
    align-items: center;
}

.legend-box {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.legend-label {
    font-size: 13px;
    font-weight: 600;
    color: #333;
}
</style>
@endpush

@push('js')
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>

<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ============================================================
   VIEW SELECTOR (NAVBAR STYLE)
   ============================================================ */
document.addEventListener("DOMContentLoaded", function() {

    const viewButtons = document.querySelectorAll(".view-btn");

    const sections = {
        overview: document.getElementById("section-overview"),
        summary: document.getElementById("section-summary"),
        tables: document.getElementById("section-tables"),
        calendar: document.getElementById("section-calendar"),
    };

    function showSection(key) {
        Object.keys(sections).forEach(name => {
            sections[name].style.display = (name === key ? "block" : "none");
        });
    }

    // Toggle view button active state
    viewButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            viewButtons.forEach(b => {
                b.classList.remove("active");
                b.setAttribute("aria-selected", "false");
            });

            btn.classList.add("active");
            btn.setAttribute("aria-selected", "true");

            const view = btn.dataset.view;
            showSection(view);

            // Render heatmap only when calendar activated
            if (view === "calendar") {
                setTimeout(renderHeatmap, 150);
            }
        });
    });

    /* ============================================================
       RANDOM DEFAULT VIEW
       ============================================================ */
    const randomView = ["overview", "summary", "tables", "calendar"]
        [Math.floor(Math.random() * 4)];

    showSection(randomView);

    viewButtons.forEach(b => {
        const active = b.dataset.view === randomView;
        b.classList.toggle("active", active);
        b.setAttribute("aria-selected", active ? "true" : "false");
    });

    if (randomView === "calendar") {
        setTimeout(() => {
            if (typeof renderHeatmap === "function") renderHeatmap();
        }, 200);
    }

});
</script>

<script>
/* ============================================================
   ANIMASI SCROLL (FADE-UP)
   ============================================================ */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add("in-view");
            obs.unobserve(e.target);
        }
    });
}, {
    threshold: 0.15
});

document.querySelectorAll("[data-animate]").forEach(el => obs.observe(el));
</script>

<script>
/* ============================================================
   CARD TILT EFFECT
   ============================================================ */
document.querySelectorAll(".card-tilt").forEach(card => {
    const inner = card.querySelector(".tilt-inner");

    card.addEventListener("mousemove", e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        inner.style.transform =
            `rotateX(${(y * -8).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
    });

    card.addEventListener("mouseleave", () => {
        inner.style.transform = "none";
    });
});
</script>

<script>
/* ============================================================
   LINE CHART — KASUS PER BULAN
   ============================================================ */
const fraudData = @json($fraudData ?? array_fill(0, 12, 0));

new Chart(document.getElementById("bigDashboardChart"), {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
            label: "Jumlah Kasus",
            data: fraudData,
            borderColor: "#29b14a",
            backgroundColor: "rgba(41,177,74,0.12)",
            pointBackgroundColor: "#eee733",
            pointRadius: 5,
            fill: true,
            tension: 0.35,
            borderWidth: 3
        }]
    },
    options: {
        maintainAspectRatio: false
    }
});
</script>

<script>
/* ============================================================
   QUARTER BAR + LINE CHART
   ============================================================ */
new Chart(document.getElementById("quarterSanksiChart"), {
    type: "bar",
    data: {
        labels: ["Q1", "Q2", "Q3", "Q4"],
        datasets: [{
                label: "Jumlah Kasus",
                data: @json($kasusPerQuarter),
                backgroundColor: "rgba(41,177,74,0.55)",
                borderColor: "#29b14a",
                borderWidth: 2,
                yAxisID: "y",
            },
            {
                label: "Total Nilai Sanksi",
                data: @json($sanksiPerQuarter),
                backgroundColor: "rgba(219,211,0,0.55)",
                borderColor: "#c7c500",
                borderWidth: 2,
                type: "line",
                tension: 0.3,
                yAxisID: "y1",
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            },
            y1: {
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    }
});
</script>

<script>
/* ============================================================
   CALENDAR HEATMAP
   ============================================================ */

// Data tanggal → jumlah kasus
const fraudCalendarData = @json($fraudCalendar ?? []);

/* Render Heatmap */
function renderHeatmap() {

    const container = document.getElementById("fraudCalendar");
    const tooltip = document.getElementById("heatmapTooltip");
    container.innerHTML = "";

    const year = @json($latestYear);
    const start = new Date(year, 0, 1);

    for (let i = 0; i < 365; i++) {

        const current = new Date(start);
        current.setDate(start.getDate() + i);

        const dateKey = current.toLocaleDateString("en-CA");
        const count = fraudCalendarData[dateKey] ?? 0;

        let level = 0;
        if (count > 10) level = 4;
        else if (count > 5) level = 3;
        else if (count > 2) level = 2;
        else if (count > 0) level = 1;

        const cell = document.createElement("div");
        cell.className = `calendar-day level-${level}`;

        /* Tooltip on hover */
        cell.addEventListener("mousemove", e => {

            const tWidth = tooltip.offsetWidth;
            const tHeight = tooltip.offsetHeight;

            let x = e.clientX - tWidth / 2 - 290;
            let y = e.clientY - tHeight - 180;

            const maxW = window.innerWidth;
            if (x < 5) x = 5;
            if (x + tWidth > maxW) x = maxW - tWidth - 5;
            if (y < 5) y = e.clientY + 20;

            tooltip.style.left = x + "px";
            tooltip.style.top = y + "px";
            tooltip.style.opacity = 1;

            tooltip.innerHTML =
                `<b>${current.toLocaleDateString("id-ID", { weekday: "long", day: "numeric", month: "long", year: "numeric" })}</b><br>
                 ${count} Kasus Kecurangan`;
        });

        cell.addEventListener("mouseleave", () => {
            tooltip.style.opacity = 0;
        });

        container.appendChild(cell);
    }
}
</script>
@endpush