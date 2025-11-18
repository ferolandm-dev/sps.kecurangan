<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ======================================
        // 📊 Total distributor aktif
        // ======================================
        $totalDistributorAktif = DB::table('distributors')
            ->where('status', 'Aktif')
            ->count();

        // ======================================
        // 👨‍💼 Total sales aktif
        // ======================================
        $totalSalesAktif = DB::table('sales')
            ->where('status', 'Aktif')
            ->count();

        // ======================================
        // ⚠️ Jumlah kecurangan tiap bulan (validasi = 1)
        // ======================================
        $fraudPerMonth = DB::table('kecurangan')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('validasi', 1)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Pastikan 12 bulan terisi
        $fraudData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fraudData[] = $fraudPerMonth[$i] ?? 0;
        }

        // ======================================
        // 🏢 Top 5 distributor berdasarkan sales aktif
        // ======================================
        $topDistributors = DB::table('distributors')
            ->leftJoin('sales', function ($join) {
                $join->on('sales.id_distributor', '=', 'distributors.id')
                    ->where('sales.status', 'Aktif');
            })
            ->select(
                'distributors.id',
                'distributors.distributor',
                DB::raw('COUNT(sales.id) as total_sales')
            )
            ->where('distributors.status', 'Aktif')
            ->groupBy('distributors.id', 'distributors.distributor')
            ->orderByDesc('total_sales')
            ->orderBy('distributors.distributor', 'asc')
            ->limit(5)
            ->get();

        // ======================================
        // 🚨 Top 5 sales paling sering curang (validasi = 1)
        // ======================================
        $topFraudSales = DB::table('kecurangan')
            ->join('sales', 'kecurangan.id_sales', '=', 'sales.id')
            ->select(
                'sales.id as id_sales',
                'sales.nama as nama_sales',
                DB::raw('COUNT(kecurangan.id) as total_kecurangan'),
                DB::raw('MAX(kecurangan.updated_at) as terakhir_validasi')
            )
            ->where('kecurangan.validasi', 1)
            ->groupBy('sales.id', 'sales.nama')
            ->orderByDesc('total_kecurangan')
            ->orderBy('terakhir_validasi', 'asc')
            ->limit(5)
            ->get();

        // ======================================
        // 📆 Total kecurangan bulan ini (validasi = 1)
        // ======================================
        $totalKecuranganBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

        // ======================================
        // 💰 Total nilai sanksi bulan ini
        // ======================================
        $totalNilaiSanksiBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('nilai_sanksi');

        // ======================================
        // 📆 Progress kuartal (hari kerja)
        // ======================================
        $currentMonth = date('n');

        if ($currentMonth <= 3) $currentQuarter = 1;
        elseif ($currentMonth <= 6) $currentQuarter = 2;
        elseif ($currentMonth <= 9) $currentQuarter = 3;
        else $currentQuarter = 4;

        $year = date('Y');
        $today = now()->startOfDay();

        $quarterRanges = [
            1 => ['start' => "$year-01-01", 'end' => "$year-03-31"],
            2 => ['start' => "$year-04-01", 'end' => "$year-06-30"],
            3 => ['start' => "$year-07-01", 'end' => "$year-09-30"],
            4 => ['start' => "$year-10-01", 'end' => "$year-12-31"],
        ];

        $startQuarter = \Carbon\Carbon::parse($quarterRanges[$currentQuarter]['start']);
        $endQuarter   = \Carbon\Carbon::parse($quarterRanges[$currentQuarter]['end']);

        $totalHariKuartal = 0;
        $temp = $startQuarter->copy();

        while ($temp->lte($endQuarter)) {
            if ($temp->dayOfWeek !== 0) $totalHariKuartal++;
            $temp->addDay();
        }

        $hariKe = 0;
        $temp = $startQuarter->copy();

        while ($temp->lte($today)) {
            if ($temp->dayOfWeek !== 0) $hariKe++;
            $temp->addDay();
        }

        $progressQuarterDay = ($totalHariKuartal > 0)
            ? ($hariKe / $totalHariKuartal) * 100
            : 0;

        $progressQuarterDay = round(min(max($progressQuarterDay, 0), 100), 1);

        // ======================================
        // 📊 Kasus & Nilai Sanksi per Kuartal
        // ======================================
        $quarterSummary = DB::table('kecurangan')
            ->select(
                DB::raw('QUARTER(tanggal) as quarter'),
                DB::raw('COUNT(*) as total_kasus'),
                DB::raw('SUM(nilai_sanksi) as total_sanksi')
            )
            ->where('validasi', 1)
            ->groupBy('quarter')
            ->orderBy('quarter')
            ->get();

        $quarters = [1, 2, 3, 4];
        $kasusPerQuarter = [];
        $sanksiPerQuarter = [];

        foreach ($quarters as $q) {
            $row = $quarterSummary->firstWhere('quarter', $q);
            $kasusPerQuarter[] = $row->total_kasus ?? 0;
            $sanksiPerQuarter[] = $row->total_sanksi ?? 0;
        }

        // ======================================
        // 📈 Trend Kecurangan Bulan Ini
        // ======================================
        $bulanIni = date('m');
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunIni = date('Y');
        $tahunBulanLalu = date('Y', strtotime('-1 month'));

        $fraudBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        $fraudBulanLalu = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanLalu)
            ->whereYear('tanggal', $tahunBulanLalu)
            ->count();

        if ($fraudBulanLalu == 0) {
            $trendFraud = $fraudBulanIni > 0 ? 100 : 0;
        } else {
            $trendFraud = round((($fraudBulanIni - $fraudBulanLalu) / $fraudBulanLalu) * 100, 1);
        }

        // ======================================
        // 💵 Rata-rata nilai sanksi bulan ini
        // ======================================
        $avgSanksiBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->avg('nilai_sanksi') ?? 0;

        $avgSanksiBulanIni = round($avgSanksiBulanIni);

        // ======================================
        // 🔥 HEATMAP KECURANGAN PER TANGGAL
        // ======================================

        // Ambil tahun terbaru berdasarkan data kecurangan
        $latestYear = DB::table('kecurangan')
            ->max(DB::raw('YEAR(tanggal)'));

        // Jika tidak ada data → gunakan tahun sekarang
        if (!$latestYear) {
            $latestYear = date('Y');
        }

        // Query data heatmap per tanggal untuk tahun tersebut
        $fraudCalendar = DB::table('kecurangan')
            ->whereYear('tanggal', $latestYear)
            ->groupBy('tanggal')
            ->pluck(DB::raw('COUNT(*) as total'), 'tanggal');
        
        // ======================================
        // 🔥 LEADERBOARD DISTRIBUTOR
        // ======================================

        $leaderboardDistributor = DB::table('kecurangan')
            ->leftJoin('distributors', 'distributors.distributor', '=', 'kecurangan.distributor')
            ->select(
                'kecurangan.distributor',
                DB::raw('COUNT(kecurangan.id) as total_kecurangan'),
                DB::raw('SUM(kecurangan.nilai_sanksi) as total_sanksi'),
                'distributors.status'
            )
            ->groupBy('kecurangan.distributor', 'distributors.status')
            ->orderByDesc('total_kecurangan')
            ->limit(5)
            ->get();

        // ======================================
        // 🔥 RECENT FRAUD CASES
        // ======================================
        $recentFraudCases = DB::table('kecurangan')
            ->select(
                'id_sales',
                'nama_sales',
                'distributor',
                'jenis_sanksi',
                'nilai_sanksi',
                'tanggal',
                'validasi'
            )
            ->where('validasi', 1)
            ->orderBy('created_at', 'DESC') // paling terbaru
            ->limit(5)
            ->get();


        // ======================================
        // 🔁 Kirim semua data ke VIEW
        // ======================================
        return view('dashboard', compact(
            'totalDistributorAktif',
            'totalSalesAktif',
            'fraudData',
            'topDistributors',
            'topFraudSales',
            'totalKecuranganBulanIni',
            'currentQuarter',
            'totalNilaiSanksiBulanIni',
            'progressQuarterDay',
            'kasusPerQuarter',
            'sanksiPerQuarter',
            'trendFraud',
            'avgSanksiBulanIni',
            'fraudCalendar',
            'latestYear',
            'leaderboardDistributor',
            'recentFraudCases'
        ));

    }
}