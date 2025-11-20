<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ======================================
        // 📊 Total distributor (tidak ada kolom status)
        // ======================================
        $totalDistributorAktif = DB::table('distributor')->count();

        // ======================================
        // 👨‍💼 Total salesman (tidak ada kolom status)
        // ======================================
        $totalSalesAktif = DB::table('salesman')
            ->where('TYPE_SALESMAN', '1')
            ->count();

        // ======================================
        // ⚠️ Jumlah kecurangan tiap bulan
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

        // Isi 12 bulan
        $fraudData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fraudData[] = $fraudPerMonth[$i] ?? 0;
        }

        // ======================================
        // 🏢 Top 5 distributor berdasarkan jumlah salesman
        // ======================================
        $topDistributors = DB::table('distributor')
            ->leftJoin('salesman', function ($join) {
                $join->on('salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
                    ->where('salesman.TYPE_SALESMAN', 1); 
            })
            ->select(
                'distributor.ID_DISTRIBUTOR as id',
                'distributor.NAMA_DISTRIBUTOR as distributor',
                DB::raw('COUNT(salesman.ID_SALESMAN) as total_sales')
            )
            ->groupBy('distributor.ID_DISTRIBUTOR', 'distributor.NAMA_DISTRIBUTOR')
            ->orderByDesc('total_sales')
            ->orderBy('distributor.NAMA_DISTRIBUTOR', 'asc')
            ->limit(5)
            ->get();


        // ======================================
        // 🚨 Top 5 salesman paling sering curang
        // ======================================
        $topFraudSales = DB::table('kecurangan')
            ->join('salesman', 'kecurangan.id_sales', '=', 'salesman.ID_SALESMAN')
            ->select(
                'salesman.ID_SALESMAN as id_sales',
                'salesman.NAMA_SALESMAN as nama_sales',
                DB::raw('COUNT(kecurangan.id) as total_kecurangan'),
                DB::raw('MAX(kecurangan.updated_at) as terakhir_validasi')
            )
            ->where('kecurangan.validasi', 1)
            ->groupBy('salesman.ID_SALESMAN', 'salesman.NAMA_SALESMAN')
            ->orderByDesc('total_kecurangan')
            ->limit(5)
            ->get();

        // ======================================
        // 📆 Total kecurangan bulan ini
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
        // 📆 HITUNG KUARTAL & PROGRESS
        // ======================================
        $currentMonth = (int) Carbon::now()->format('n');
        if ($currentMonth <= 3) $currentQuarter = 1;
        elseif ($currentMonth <= 6) $currentQuarter = 2;
        elseif ($currentMonth <= 9) $currentQuarter = 3;
        else $currentQuarter = 4;

        $year = Carbon::now()->year;
        $quarterRanges = [
            1 => ['start' => "$year-01-01", 'end' => "$year-03-31"],
            2 => ['start' => "$year-04-01", 'end' => "$year-06-30"],
            3 => ['start' => "$year-07-01", 'end' => "$year-09-30"],
            4 => ['start' => "$year-10-01", 'end' => "$year-12-31"],
        ];

        $startQuarter = Carbon::parse($quarterRanges[$currentQuarter]['start']);
        $endQuarter   = Carbon::parse($quarterRanges[$currentQuarter]['end']);

        // Hitung total hari kerja (exclude Minggu)
        $totalHariKuartal = 0;
        $temp = $startQuarter->copy();
        while ($temp->lte($endQuarter)) {
            if ($temp->dayOfWeek !== 0) $totalHariKuartal++;
            $temp->addDay();
        }

        // Hitung progress hari kerja berjalan
        $hariKe = 0;
        $today = Carbon::now()->startOfDay();
        $temp = $startQuarter->copy();
        while ($temp->lte($today) && $temp->lte($endQuarter)) {
            if ($temp->dayOfWeek !== 0) $hariKe++;
            $temp->addDay();
        }

        $progressQuarterDay = $totalHariKuartal > 0
            ? round(min(max(($hariKe / $totalHariKuartal) * 100, 0), 100), 1)
            : 0;

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

        $kasusPerQuarter = [];
        $sanksiPerQuarter = [];
        foreach ([1,2,3,4] as $q) {
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
        $tahunLalu = date('Y', strtotime('-1 month'));

        $fraudBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        $fraudBulanLalu = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanLalu)
            ->whereYear('tanggal', $tahunLalu)
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
        // 🔥 Heatmap
        // ======================================
        $latestYear = DB::table('kecurangan')->max(DB::raw('YEAR(tanggal)')) ?? date('Y');

        $fraudCalendar = DB::table('kecurangan')
            ->whereYear('tanggal', $latestYear)
            ->groupBy('tanggal')
            ->pluck(DB::raw('COUNT(*) as total'), 'tanggal');

        // ======================================
        // 🔥 Leaderboard Distributor
        // ======================================
        $leaderboardDistributor = DB::table('kecurangan')
            ->leftJoin('distributor', 'distributor.ID_DISTRIBUTOR', '=', 'kecurangan.distributor')
            ->select(
                'kecurangan.distributor',
                DB::raw('COUNT(kecurangan.id) as total_kecurangan'),
                DB::raw('SUM(kecurangan.nilai_sanksi) as total_sanksi')
            )
            ->groupBy('kecurangan.distributor')
            ->orderByDesc('total_kecurangan')
            ->limit(5)
            ->get();

        // ======================================
        // 🔥 Kasus terbaru
        // ======================================
        $recentFraudCases = DB::table('kecurangan')
            ->select(
                'id_sales',
                'nama_sales',
                'distributor',
                'jenis_sanksi',
                'nilai_sanksi',
                'tanggal'
            )
            ->where('validasi', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        $totalAssAktif = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->distinct('NAMA_SALESMAN')
            ->count('NAMA_SALESMAN');

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
            'recentFraudCases',
            'totalAssAktif'
        ));
    }
}