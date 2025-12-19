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
                        <span class="label">Heatmap Kasus</span>
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

            {{-- Kasus Bulan Ini --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">
                            <h5 class="card-category">Kasus Bulan Ini</h5>
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

            {{-- Tren Kasus --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card glass-card card-tilt fade-up" data-animate>
                    <div class="tilt-inner p-3">
                        <div class="card-header">

                            <h5 class="card-category">Tren Kasus Bulan Ini</h5>

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
                                        <th class="text-center">Jumlah Sales</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($topDistributors as $d)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->distributor }}</td>
                                        <td class="text-center">
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
                        <h4 class="card-title">Jumlah Kasus</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th>ID Sales</th>
                                        <th>Nama Sales</th>
                                        <th class="text-center">Kecurangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($topFraudSales as $s)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $s->id_sales }}</td>
                                        <td>{{ $s->nama_sales }}</td>
                                        <td class="text-center">
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
                                        <th class="text-center">Jumlah Kasus</th>
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
                                        <td class="text-center">
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
                        <h5 class="card-category">Top 5 Customer</h5>
                        <h4 class="card-title">Top Customer Distributor</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x:auto;">

                            <table class="table table-hover align-middle" style="min-width:800px;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:80px;">Rank</th>
                                        <th style="min-width:150px;">ID Distributor</th>
                                        <th style="min-width:300px;">Nama Distributor</th>
                                        <th class="text-center" style="min-width:180px;">Total Customer</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($totalCustomerTopDistributor as $index => $d)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $d->ID_DISTRIBUTOR }}</td>
                                        <td>{{ $d->NAMA_DISTRIBUTOR }}</td>
                                        <td class="text-center">
                                            <span class="badge-soft">
                                                {{ number_format($d->total_customer, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Belum ada data
                                        </td>
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
                        <h5 class="card-category">Heatmap Kasus</h5>
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
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

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