@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini',
'activePage' => 'dashboard',
])

@section('content')

<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">

    {{-- ===========================
        DASHBOARD VIEW NAVBAR-STYLE SELECTOR (A4)
    =========================== --}}
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



    <!-- ===============================
           SECTION: OVERVIEW
    =============================== -->
    <div id="section-overview">

        {{-- Sticky chart --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="sticky-container">
                    <div class="chart-sticky-wrapper fade-up" data-animate>
                        <div class="glass-panel">
                            <canvas id="bigDashboardChart" style="width:100%;height:320px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART: Sanksi Per Kuartal --}}
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

        {{-- Card: Progress Harian Kuartal --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card glass-card fade-up" data-animate>
                    <div class="p-4">
                        <h5 class="card-category">Progress Harian Kuartal {{ $currentQuarter }}</h5>
                        <h4 class="card-title">{{ $progressQuarterDay }}%</h4>

                        <div class="progress mt-3"
                            style="height: 18px; border-radius: 12px; background: rgba(41,177,74,0.12);">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $progressQuarterDay }}%; background: linear-gradient(90deg,#29b14a,#c7c500); border-radius:12px;"
                                aria-valuenow="{{ $progressQuarterDay }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <p class="mt-3 text-muted" style="font-size: 13px;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Updated
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===============================
           SECTION: SUMMARY CARDS
    =============================== -->
    <div id="section-summary" style="display:none;">

        <div class="row">

            {{-- Card: Distributor Aktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Distributor Aktif</h5>
                            <h4 class="card-title">{{ $totalDistributorAktif }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="stats"><i class="now-ui-icons arrows-1_refresh-69"></i> Updated</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Sales Aktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Total Sales Aktif</h5>
                            <h4 class="card-title">{{ $totalSalesAktif }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="stats"><i class="now-ui-icons arrows-1_refresh-69"></i> Updated</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Kecurangan Bulan Ini --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Kecurangan Bulan Ini</h5>
                            <h4 class="card-title">{{ $totalKecuranganBulanIni }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="stats"><i class="now-ui-icons ui-2_time-alarm"></i>
                                    {{ now()->translatedFormat('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Nilai Sanksi Bulan Ini --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Nilai Sanksi Bulan Ini</h5>
                            <h4 class="card-title">Rp {{ number_format($totalNilaiSanksiBulanIni,0,',','.') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="stats"><i class="now-ui-icons ui-1_calendar-60"></i>
                                    {{ now()->translatedFormat('F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Rata-Rata Sanksi Bulan Ini --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Rata-Rata Sanksi Bulan Ini</h5>
                            <h4 class="card-title">Rp {{ number_format($avgSanksiBulanIni,0,',','.') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="stats">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                    {{ now()->translatedFormat('F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Tren --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Tren Kecurangan Bulan Ini</h5>

                            @php
                            $isUp = $trendFraud > 0;
                            $isDown = $trendFraud < 0; @endphp <h4 class="card-title" style="font-weight:700;">
                                @if($isUp)
                                <span style="color:#e63946;">↑ {{ $trendFraud }}%</span>
                                @elseif($isDown)
                                <span style="color:#29b14a;">↓ {{ abs($trendFraud) }}%</span>
                                @else
                                <span style="color:#555;">0% (Stabil)</span>
                                @endif
                                </h4>

                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mt-2">
                                <div class="stats"><i class="now-ui-icons arrows-1_refresh-69"></i> Dibanding Bulan
                                    Lalu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ===============================
           SECTION: TABLES
    =============================== -->
    <div id="section-tables" style="display:none;">

        <div class="row">

            {{-- Distributor --}}
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
                                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fraud Sales --}}
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
                                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

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
                                    @foreach($leaderboardDistributor as $index => $d)
                                    <tr>
                                        <td class="text-center">
                                            <span class="rank-badge rank-{{ $index+1 }} t">
                                                {{ $index+1 }}
                                            </span>
                                        </td>
                                        <td>{{ $d->distributor }}</td>
                                        <td class="text-right">
                                            <span class="badge-soft">{{ $d->total_kecurangan }}</span>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if($leaderboardDistributor->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            Belum ada data kecurangan
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card glass-card fade-up h-100" data-animate>
                    <div class="card-header pb-0">
                        <h5 class="card-category">Top 5 Kasus Terbaru</h5>
                        <h4 class="card-title">Kasus Terbaru</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table table-hover align-middle" style="min-width: 1050px;">
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

                                        <!-- Tanggal -->
                                        <td>{{ \Carbon\Carbon::parse($f->tanggal)->translatedFormat('d M Y') }}</td>

                                        <!-- ID Sales -->
                                        <td>{{ $f->id_sales }}</td>

                                        <!-- Nama Sales -->
                                        <td>{{ $f->nama_sales }}</td>

                                        <!-- Distributor -->
                                        <td>{{ $f->distributor }}</td>

                                        <!-- Pelanggaran (1 baris) -->
                                        <td>
                                            {{ $f->jenis_sanksi ?? 'Non-Sanksi' }}
                                        </td>

                                        <!-- Nilai -->
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

    <!-- ===============================
           SECTION: CALENDAR HEATMAP
================================ -->
    <div id="section-calendar" style="display:none;">
        <div class="row">
            <div class="col-lg-12 mb-4">

                <div class="card glass-card fade-up" data-animate>
                    <div class="card-header pb-0">
                        <h5 class="card-category">Heatmap Kecurangan</h5>
                        <h4 class="card-title">Calendar View</h4>
                    </div>

                    <div class="card-body">

                        <!-- Heatmap -->
                        <div id="fraudCalendar" class="calendar-heatmap"></div>

                        <!-- Tooltip floating -->
                        <div id="heatmapTooltip" class="heatmap-tooltip"></div>
                        <!-- Legend Heatmap -->
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


{{-- ============================
     CUSTOM CSS
=============================== --}}
@push('styles')
<style>
/* PAGE BACKGROUND */
body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
}

.panel-header-sps {
    background: transparent !important;
    box-shadow: none !important;
}

.content {
    background: transparent !important;
}

/* Animations */
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

/* GLASS CARDS */
.glass-panel,
.glass-card {
    background: rgba(255, 255, 255, 0.92);
    border-radius: 14px;
    border: 1px solid rgba(41, 177, 74, 0.12);
}

/* TILT */
.card-tilt {
    perspective: 1000px;
}

.tilt-inner {
    transform-style: preserve-3d;
    transition: transform .2s;
}

/* TABLE HEAD */
.table thead th {
    color: #29b14a;
    font-weight: 700;
    white-space: nowrap;
}

/* BADGE SOFT */
.badge-soft {
    background: rgba(41, 177, 74, 0.14);
    color: #29b14a;
    padding: 6px 10px;
    border-radius: 8px;
}

/* =========================
   VIEW SELECTOR BAR (A4)
   ========================= */
.view-selector-bar-wrapper {
    margin-bottom: 18px;
}

.view-selector-bar {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    border: 1px solid rgba(41, 177, 74, 0.12);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    cursor: pointer;
    font-weight: 700;
    color: #2f2f2f;
    transition: all .18s ease;
    min-width: 140px;
    justify-content: center;
}

.view-btn i {
    font-size: 18px;
    color: rgba(41, 177, 74, 0.9);
}

/* active state */
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

/* hover */
.view-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
}

/* small screens: make buttons shrink / full-width stack */
@media (max-width: 576px) {
    .view-btn {
        flex: 1 1 100%;
        min-width: auto;
        justify-content: flex-start;
        padding-left: 12px;
    }
}

/* Remaining styles (heatmap, tooltip, legend) kept same as original */

/* ====== CALENDAR HEATMAP (Responsive) ====== */
.calendar-heatmap {
    display: grid;
    grid-template-columns: repeat(53, 14px);
    grid-gap: 3px;
    margin-top: 20px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.92);
    border-radius: 14px;
    width: max-content;
    overflow-x: auto;
    max-width: 100%;
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

/* TOOLTIP */
.heatmap-tooltip {
    position: fixed;
    background: rgba(0, 0, 0, 0.80);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    pointer-events: none;
    z-index: 99999;
    opacity: 0;
    transform: translateY(-6px);
    transition: opacity .15s ease, transform .15s ease;
}

.heatmap-legend {
    display: flex;
    gap: 18px;
    align-items: center;
    padding: 10px 15px;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 12px;
    width: fit-content;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
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

/* Hilangkan border biru ketika button di-klik */
.view-btn:focus,
.view-btn:active {
    outline: none !important;
    box-shadow: none !important;
}
</style>
@endpush


{{-- ============================
     CUSTOM JS
=============================== --}}
@push('js')
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ===== VIEW SELECTOR (A4) + SECTION SWITCH =====
document.addEventListener("DOMContentLoaded", function() {
    const viewButtons = document.querySelectorAll('.view-btn');

    const sections = {
        overview: document.getElementById('section-overview'),
        summary: document.getElementById('section-summary'),
        tables: document.getElementById('section-tables'),
        calendar: document.getElementById('section-calendar')
    };

    function showSection(key) {
        Object.keys(sections).forEach(k => {
            sections[k].style.display = (k === key) ? 'block' : 'none';
        });
    }

    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // toggle active classes and aria
            viewButtons.forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');

            const view = btn.dataset.view;
            showSection(view);

            // if calendar selected, render heatmap slightly delayed for layout
            if (view === 'calendar') {
                setTimeout(renderHeatmap, 150);
            }
        });
    });

    // ===== RANDOM DEFAULT VIEW =====
    const views = ['overview', 'summary', 'tables', 'calendar'];
    const randomView = views[Math.floor(Math.random() * views.length)];

    // Tampilkan section random
    showSection(randomView);

    // Set tombol aktif
    viewButtons.forEach(b => {
        const isActive = (b.dataset.view === randomView);
        b.classList.toggle('active', isActive);
        b.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });

    // 🔥 FIX: Kalau default view = calendar → render heatmap otomatis
    if (randomView === 'calendar') {
        setTimeout(() => {
            if (typeof renderHeatmap === "function") {
                renderHeatmap();
            }
        }, 200);
    }

});

// ===== ANIMASI =====
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('in-view');
            obs.unobserve(e.target);
        }
    });
}, {
    threshold: 0.15
});

document.querySelectorAll('[data-animate]').forEach(el => obs.observe(el));


// ===== 3D Card Tilt =====
document.querySelectorAll('.card-tilt').forEach(card => {
    const inner = card.querySelector('.tilt-inner');
    card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - .5;
        const y = (e.clientY - r.top) / r.height - .5;
        inner.style.transform = `rotateX(${(y * -8).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
    });
    card.addEventListener('mouseleave', () => inner.style.transform = 'none');
});


// =========================
//   BIG LINE CHART
// =========================
const fraudData = @json($fraudData ?? array_fill(0, 12, 0));
new Chart(document.getElementById('bigDashboardChart'), {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Jumlah Kasus',
            data: fraudData,
            borderColor: '#29b14a',
            backgroundColor: 'rgba(41,177,74,0.12)',
            pointBackgroundColor: '#eee733',
            pointRadius: 5,
            fill: true,
            tension: .35,
            borderWidth: 3
        }]
    },
    options: {
        maintainAspectRatio: false
    }
});


// =========================
//   QUARTER CHART
// =========================
new Chart(document.getElementById('quarterSanksiChart'), {
    type: 'bar',
    data: {
        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
        datasets: [{
                label: 'Jumlah Kasus',
                data: @json($kasusPerQuarter),
                backgroundColor: 'rgba(41,177,74,0.55)',
                borderColor: '#29b14a',
                borderWidth: 2,
                yAxisID: 'y'
            },
            {
                label: 'Total Nilai Sanksi',
                data: @json($sanksiPerQuarter),
                backgroundColor: 'rgba(219,211,0,0.55)',
                borderColor: '#c7c500',
                borderWidth: 2,
                type: 'line',
                tension: 0.3,
                yAxisID: 'y1'
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

// ===== CALENDAR HEATMAP =====

// Data dari server (jumlah kecurangan per tanggal)
const fraudCalendarData = @json($fraudCalendar ?? []);

// Render Calendar Heatmap
function renderHeatmap() {
    const container = document.getElementById("fraudCalendar");
    const tooltip = document.getElementById("heatmapTooltip");
    container.innerHTML = "";

    const now = new Date();
    const year = @json($latestYear);
    const start = new Date(year, 0, 1);

    for (let i = 0; i < 365; i++) {
        const current = new Date(start);
        current.setDate(start.getDate() + i);

        // gunakan format stabil (YYYY-MM-DD)
        const dateKey = current.toLocaleDateString("en-CA");

        const count = fraudCalendarData[dateKey] ?? 0;

        let level = 0;
        if (count > 10) level = 4;
        else if (count > 5) level = 3;
        else if (count > 2) level = 2;
        else if (count > 0) level = 1;

        const cell = document.createElement("div");
        cell.className = `calendar-day level-${level}`;

        // tooltip event
        cell.addEventListener("mousemove", e => {
            const tooltipWidth = tooltip.offsetWidth;
            const tooltipHeight = tooltip.offsetHeight;

            let x = e.clientX - tooltipWidth / 2 - 290;
            let y = e.clientY - tooltipHeight - 180;

            const pageWidth = window.innerWidth;
            if (x < 5) x = 5;
            if (x + tooltipWidth > pageWidth) x = pageWidth - tooltipWidth - 5;

            if (y < 5) y = e.clientY + 20;

            tooltip.style.left = x + "px";
            tooltip.style.top = y + "px";
            tooltip.style.opacity = 1;

            const hari = current.toLocaleDateString("id-ID", {
                weekday: "long",
                day: "numeric",
                month: "long",
                year: "numeric"
            });

            tooltip.innerHTML = `
                <b>${hari}</b><br>
                ${count} Kasus Kecurangan
            `;
        });

        cell.addEventListener("mouseleave", () => {
            tooltip.style.opacity = 0;
        });

        container.appendChild(cell);
    }
}
</script>
@endpush