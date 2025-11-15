@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini',
'activePage' => 'home',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')

<div class="panel-header panel-header-sm" style="background:#dbd300ff"></div>

<div class="content">

    {{-- Sticky chart --}}
    <div class="sticky-container">
        <div class="chart-sticky-wrapper fade-up" data-animate>
            <div class="glass-panel">
                <canvas id="bigDashboardChart" style="width:100%;height:320px;"></canvas>
            </div>
        </div>
    </div>

    {{-- CARDS --}}
    <div class="row">
        {{-- Card 1 --}}
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
                            <div class="badge-soft">{{ $topDistributors[0]->total_sales ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
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
                            <div class="badge-soft">{{ $totalSalesAktif }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
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
                            <div class="badge-soft">{{ $totalKecuranganBulanIni }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card glass-card card-tilt fade-up" data-animate>
                <div class="tilt-inner p-3">
                    <div class="card-header">
                        <h5 class="card-category">Kecurangan Kuartal {{ $currentQuarter }}</h5>
                        <h4 class="card-title">{{ $totalKecuranganKuartalIni }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mt-2">
                            <div class="stats"><i class="now-ui-icons ui-2_time-alarm"></i> Kuartal
                                {{ $currentQuarter }}</div>
                            <div class="badge-soft">{{ $totalKecuranganKuartalIni }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 5 --}}
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
                            <div class="badge-soft">Rp {{ number_format($totalNilaiSanksiBulanIni,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 6 --}}
        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card glass-card card-tilt fade-up" data-animate>
                <div class="tilt-inner p-3">
                    <div class="card-header">
                        <h5 class="card-category">Total User</h5>
                        <h4 class="card-title">{{ $totalUser }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mt-2">
                            <div class="stats"><i class="now-ui-icons arrows-1_refresh-69"></i> Updated</div>
                            <div class="badge-soft">{{ $totalUser }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- TABLES — kedua card dibuat sama tinggi (h-100) --}}
    <div class="row mt-4">
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
                                    <th>ID Distributor</th>
                                    <th style="min-width: 300px;">Nama Distributor</th>
                                    <th class="text-right">Jumlah Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDistributors as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->distributor }}</td>
                                    <td class="text-right"><span class="badge-soft">{{ $d->total_sales }}</span></td>
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
                                    <th>ID Sales</th>
                                    <th>Nama Sales</th>
                                    <th class="text-right">Kecurangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topFraudSales as $s)
                                <tr>
                                    <td>{{ $s->id_sales }}</td>
                                    <td>{{ $s->nama_sales }}</td>
                                    <td class="text-right"><span class="badge-soft">{{ $s->total_kecurangan }}</span>
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

    </div>

</div>

@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* CONTENT */
.content {
    backdrop-filter: blur(12px);
    margin-top: -70px;
    padding: 30px;
    color: #333;
    transition: backdrop-filter .3s ease;
}

/* Sticky chart */
.sticky-container {
    position: relative;
    isolation: isolate;
}

.chart-sticky-wrapper {
    position: sticky;
    top: 75px;
    z-index: 10;
    margin-bottom: 24px;
}

/* Chart glass panel */
.glass-panel {
    background: rgba(255, 255, 255, 0.92);
    border-radius: 16px;
    border: 1px solid rgba(41, 177, 74, 0.18);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    padding: 18px;
}

/* CARDS */
.glass-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(41, 177, 74, 0.12);
    border-radius: 14px;
    transition: transform .28s cubic-bezier(.2, .9, .2, 1), box-shadow .28s ease;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
}

.glass-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(41, 177, 74, 0.12);
    border-color: rgba(41, 177, 74, 0.25);
}

.glass-card .card-header h5 {
    color: #29b14a;
    font-weight: 700;
    margin: 0;
}

.glass-card .card-header h4 {
    color: #111;
    margin-top: 6px;
}

/* Table */
.table thead th {
    white-space: nowrap;
    font-weight: 700;
    color: #29b14a;
    border-bottom: 1px solid rgba(41, 177, 74, 0.12);
}

.table-hover tbody tr:hover {
    background: rgba(41, 177, 74, 0.06);
}

/* Badge */
.badge-soft {
    background: rgba(41, 177, 74, 0.14);
    color: #29b14a;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 700;
}

/* Fade-up */
.fade-up {
    opacity: 0;
    transform: translateY(10px);
}

.fade-up.in-view {
    animation: fadeUp .62s cubic-bezier(.2, .9, .2, 1) forwards;
}

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tilt */
.card-tilt {
    perspective: 1000px;
}

.card-tilt .tilt-inner {
    transform-style: preserve-3d;
    transition: transform .18s ease;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {

    /* Data utama chart */
    const fraudData = @json($fraudData ?? array_fill(0, 12, 0));

    /* Big Chart */
    const ctx = document.getElementById('bigDashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                'Des'
            ],
            datasets: [{
                label: 'Jumlah Kecurangan',
                data: fraudData,
                borderColor: '#29b14a',
                backgroundColor: 'rgba(41,177,74,0.12)',
                pointBackgroundColor: '#eee733',
                pointRadius: 5,
                fill: true,
                tension: 0.36,
                borderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.03)'
                    }
                },
                y: {
                    ticks: {
                        color: '#333'
                    },
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.03)'
                    }
                }
            }
        }
    });

    /* Fade-up animation */
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

    /* Tilt */
    document.querySelectorAll('.card-tilt').forEach(card => {
        const inner = card.querySelector('.tilt-inner');
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - .5;
            const y = (e.clientY - r.top) / r.height - .5;
            inner.style.transform =
                `rotateX(${(y*-8).toFixed(2)}deg) rotateY(${(x*8).toFixed(2)}deg)`;
        });
        card.addEventListener('mouseleave', () => inner.style.transform = 'none');
    });

});
</script>
@endpush