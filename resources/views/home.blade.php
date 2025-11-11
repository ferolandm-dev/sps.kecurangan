@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini ',
'activePage' => 'home',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')
<div class="panel-header panel-header-lg">
    <canvas id="bigDashboardChart"></canvas>
</div>
<div class="content">
    <div class="row">
        {{-- Kartu Statistik Utama --}}
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Distributor Aktif</h5>
                    <h4 class="card-title">{{ $totalDistributorAktif }} Distributor</h4>
<<<<<<< HEAD
                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret"
                            data-toggle="dropdown">
                            <i class="now-ui-icons loader_gear"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Refresh</a>
                        </div>
                    </div>
=======
>>>>>>> recovery-branch
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Sales Aktif</h5>
                    <h4 class="card-title">{{ $totalSalesAktif }} Sales</h4>
<<<<<<< HEAD
                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret"
                            data-toggle="dropdown">
                            <i class="now-ui-icons loader_gear"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Refresh</a>
                        </div>
                    </div>
=======
>>>>>>> recovery-branch
                </div>

                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Kecurangan Bulan Ini</h5>
                    <h4 class="card-title">{{ $totalKecuranganBulanIni }} Kasus</h4>
<<<<<<< HEAD
                    <div class="dropdown">
                        <button type="button"
                            class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret"
                            data-toggle="dropdown">
                            <i class="now-ui-icons loader_gear"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Refresh</a>
                        </div>
                    </div>
=======
>>>>>>> recovery-branch
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons ui-2_time-alarm"></i> Diperbarui bulan
                        {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
=======

        <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Kecurangan Kuartal {{ $currentQuarter }}</h5>
                    <h4 class="card-title">{{ $totalKecuranganKuartalIni }} Kasus</h4>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons ui-2_time-alarm"></i>
                        Periode Kuartal {{ $currentQuarter }} ({{ date('Y') }})
                    </div>
                </div>
            </div>
        </div>
>>>>>>> recovery-branch
    </div>

    {{-- ROW UNTUK TOP DISTRIBUTOR & TOP SALES CURANG --}}
    <div class="row">
        {{-- Top 5 Distributor --}}
        <div class="col-md-6">
<<<<<<< HEAD
            <div class="card">
=======
            <div class="card h-100 d-flex flex-column">
>>>>>>> recovery-branch
                <div class="card-header">
                    <h5 class="card-category">Top 5 Distributor</h5>
                    <h4 class="card-title">Berdasarkan Jumlah Sales Aktif</h4>
                </div>
<<<<<<< HEAD
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
=======
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="table-responsive" style="overflow-x: auto; flex: 1;">
                        <table class="table table-hover align-middle mb-0" style="height: 100%;">
>>>>>>> recovery-branch
                            <thead class="text-primary">
                                <tr>
                                    <th style="white-space: nowrap;">ID Distributor</th>
                                    <th style="min-width: 200px;">Nama Distributor</th>
                                    <th class="text-right" style="white-space: nowrap;">Jumlah Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDistributors as $distributor)
                                <tr>
                                    <td>{{ $distributor->id }}</td>
                                    <td>{{ $distributor->distributor }}</td>
                                    <td class="text-right">{{ $distributor->total_sales }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data distributor.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 5 Sales Curang --}}
        <div class="col-md-6">
<<<<<<< HEAD
            <div class="card">
=======
            <div class="card h-100 d-flex flex-column">
>>>>>>> recovery-branch
                <div class="card-header">
                    <h5 class="card-category">Top 5 Sales Curang</h5>
                    <h4 class="card-title">Berdasarkan Jumlah Kecurangan</h4>
                </div>
<<<<<<< HEAD
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
=======
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="table-responsive" style="overflow-x: auto; flex: 1;">
                        <table class="table table-hover align-middle mb-0" style="height: 100%;">
>>>>>>> recovery-branch
                            <thead class="text-primary">
                                <tr>
                                    <th style="white-space: nowrap;">ID Sales</th>
                                    <th style="min-width: 200px;">Nama Sales</th>
                                    <th class="text-right" style="white-space: nowrap;">Total Kecurangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topFraudSales as $sales)
                                <tr>
                                    <td>{{ $sales->id_sales }}</td>
                                    <td>{{ $sales->nama_sales }}</td>
                                    <td class="text-right">{{ $sales->total_kecurangan }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data kecurangan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
=======

>>>>>>> recovery-branch
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('bigDashboardChart').getContext('2d');
    const fraudData = @json($fraudData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            datasets: [{
                label: 'Jumlah Kecurangan',
                data: fraudData,
                borderColor: '#f96332',
                backgroundColor: 'rgba(249, 99, 50, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#f96332',
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#f96332',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#fff'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#fff'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#fff',
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endpush