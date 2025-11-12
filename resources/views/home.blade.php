@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini ',
'activePage' => 'home',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')

{{-- ===== STYLE: GLASS EFFECT & BACKGROUND ===== --}}
<style>
.content {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 20px;
}

/* PANEL ATAS (CHART) */
.glass-panel {
    background: linear-gradient(135deg, rgba(41, 177, 74, 0.08), rgba(255, 255, 255, 0.1));
    backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid rgba(41, 177, 74, 0.25);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    /* margin-top: 50px; */
    /* 🟢 tambahkan baris ini */
    margin-bottom: 30px;
    padding: 20px;
}

/* KARTU */
.glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(41, 177, 74, 0.25);
    border-radius: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    color: #222;
}

/* HOVER – tanpa kuning */
.glass-card:hover {
    transform: translateY(-4px);
    background: linear-gradient(135deg, rgba(41, 177, 74, 0.1), rgba(255, 255, 255, 0.3));
    border-color: rgba(41, 177, 74, 0.5);
    box-shadow: 0 6px 25px rgba(41, 177, 74, 0.25);
}

.glass-card .card-header h5 {
    color: #29b14a;
    font-weight: 600;
}

.glass-card .card-header h4 {
    color: #111;
    font-weight: 700;
}

/* TABEL */
.table thead th {
    white-space: nowrap;
    /* Biar teks tidak turun ke bawah */
    text-align: left;
    vertical-align: middle;
    font-weight: 600;
    color: #29b14a;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.table tbody td {
    white-space: nowrap;
    /* Biar isi kolom juga tidak turun */
    vertical-align: middle;
}


/* Hover baris tabel – ganti dari kuning ke hijau lembut */
.table-hover tbody tr:hover {
    background-color: rgba(41, 177, 74, 0.08);
}

/* Icon & footer info */
.stats i {
    color: #29b14a;
}

.stats {
    color: #555;
}

.panel-header {
    background: #eee733
}
</style>

<div class="panel-header panel-header-sm"></div>

{{-- ===== CONTENT START ===== --}}
<div class="content">
    {{-- ===== CHART PANEL (dipindahkan ke sini) ===== --}}
    <div class="glass-panel">
        <canvas id="bigDashboardChart" style="width: 100%; height: 300px;"></canvas>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card glass-card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Distributor Aktif</h5>
                    <h4 class="card-title">{{ $totalDistributorAktif }} Distributor</h4>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card glass-card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Sales Aktif</h5>
                    <h4 class="card-title">{{ $totalSalesAktif }} Sales</h4>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card glass-card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Kecurangan Bulan Ini</h5>
                    <h4 class="card-title">{{ $totalKecuranganBulanIni }} Kasus</h4>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons ui-2_time-alarm"></i>
                        Diperbarui bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Kuartal & User --}}
        <div class="col-lg-4 col-md-6">
            <div class="card glass-card card-chart">
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

        <div class="col-lg-4 col-md-6">
            <div class="card glass-card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total User</h5>
                    <h4 class="card-title">{{ $totalUser }} Pengguna</h4>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABLES ===== --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card glass-card h-100">
                <div class="card-header">
                    <h5 class="card-category">Top 5 Distributor</h5>
                    <h4 class="card-title">Berdasarkan Jumlah Sales Aktif</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Distributor</th>
                                    <th>Nama Distributor</th>
                                    <th class="text-right">Jumlah Sales</th>
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

        <div class="col-md-6">
            <div class="card glass-card h-100">
                <div class="card-header">
                    <h5 class="card-category">Top 5 Sales Curang</h5>
                    <h4 class="card-title">Berdasarkan Jumlah Kecurangan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Sales</th>
                                    <th>Nama Sales</th>
                                    <th class="text-right">Total Kecurangan</th>
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
                borderColor: '#29b14a',
                backgroundColor: 'rgba(41, 177, 74, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#eee733',
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#29b14a',
                pointHoverBorderColor: '#fff',
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
                        color: '#333'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#333',
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    });
});
</script>
@endpush