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
        // 📊 Total distributor
        // ======================================
        $totalDistributorAktif = DB::table('distributor')->count();

        // ======================================
        // 👨‍💼 Total salesman aktif
        // ======================================
        $totalSalesAktif = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->count();

        // ======================================
        // ⚠️ Fraud per bulan (kolom TANGGAL)
        // ======================================
        $fraudPerMonth = DB::table('kecurangan')
            ->select(
                DB::raw('MONTH(TANGGAL) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('VALIDASI', 1)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $fraudData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fraudData[] = $fraudPerMonth[$i] ?? 0;
        }

        // ======================================
        // 🏢 Top distributor berdasarkan jumlah salesman aktif
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
            ->limit(5)
            ->get();

        // ======================================
        // 🚨 Top 5 salesman paling sering curang
        // ======================================
        $topFraudSales = DB::table('kecurangan')
            ->join('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->select(
                'salesman.ID_SALESMAN as id_sales',
                'salesman.NAMA_SALESMAN as nama_sales',
                DB::raw('COUNT(kecurangan.ID) as total_kecurangan'),
                DB::raw('MAX(kecurangan.UPDATED_AT) as terakhir_validasi'),
                'kecurangan.DISTRIBUTOR as distributor'
            )
            ->where('kecurangan.VALIDASI', 1)
            ->groupBy('salesman.ID_SALESMAN', 'salesman.NAMA_SALESMAN', 'kecurangan.DISTRIBUTOR')
            ->orderByDesc('total_kecurangan')
            ->limit(5)
            ->get();


        // ======================================
        // 📆 Total kecurangan bulan ini
        // ======================================
        $totalKecuranganBulanIni = DB::table('kecurangan')
            ->where('VALIDASI', 1)
            ->whereMonth('TANGGAL', date('m'))
            ->whereYear('TANGGAL', date('Y'))
            ->count();

        // ======================================
        // 💰 Total nilai sanksi bulan ini
        // ======================================
        $totalNilaiSanksiBulanIni = DB::table('kecurangan')
            ->where('VALIDASI', 1)
            ->whereMonth('TANGGAL', date('m'))
            ->whereYear('TANGGAL', date('Y'))
            ->sum('NILAI_SANKSI');

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

        // Hitung hari kerja kuartal (exclude Minggu)
        $totalHariKuartal = 0;
        $temp = $startQuarter->copy();
        while ($temp->lte($endQuarter)) {
            if ($temp->dayOfWeek !== 0) $totalHariKuartal++;
            $temp->addDay();
        }

        // Hitung progress hari kerja
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
        // 📊 Kasus & Nilai sanksi per kuartal
        // ======================================
        $quarterSummary = DB::table('kecurangan')
            ->select(
                DB::raw('QUARTER(TANGGAL) as quarter'),
                DB::raw('COUNT(*) as total_kasus'),
                DB::raw('SUM(NILAI_SANKSI) as total_sanksi')
            )
            ->where('VALIDASI', 1)
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
            ->where('VALIDASI', 1)
            ->whereMonth('TANGGAL', $bulanIni)
            ->whereYear('TANGGAL', $tahunIni)
            ->count();

        $fraudBulanLalu = DB::table('kecurangan')
            ->where('VALIDASI', 1)
            ->whereMonth('TANGGAL', $bulanLalu)
            ->whereYear('TANGGAL', $tahunLalu)
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
            ->where('VALIDASI', 1)
            ->whereMonth('TANGGAL', date('m'))
            ->whereYear('TANGGAL', date('Y'))
            ->avg('NILAI_SANKSI') ?? 0;

        $avgSanksiBulanIni = round($avgSanksiBulanIni);

        // ======================================
        // 🔥 Heatmap per tanggal
        // ======================================
        $latestYear = DB::table('kecurangan')->max(DB::raw('YEAR(TANGGAL)')) ?? date('Y');

        $fraudCalendar = DB::table('kecurangan')
            ->whereYear('TANGGAL', $latestYear)
            ->groupBy('TANGGAL')
            ->pluck(DB::raw('COUNT(*) as total'), 'TANGGAL');

        // ======================================
        // 🔥 Leaderboard Distributor
        // ======================================
        $leaderboardDistributor = DB::table('kecurangan')
            ->leftJoin('distributor', 'distributor.ID_DISTRIBUTOR', '=', 'kecurangan.DISTRIBUTOR')
            ->select(
                'kecurangan.DISTRIBUTOR as distributor',
                DB::raw('COUNT(kecurangan.ID) as total_kecurangan'),
                DB::raw('SUM(kecurangan.NILAI_SANKSI) as total_sanksi')
            )
            ->groupBy('kecurangan.DISTRIBUTOR')
            ->orderByDesc('total_kecurangan')
            ->limit(5)
            ->get();


        // ======================================
        // 🔥 Kasus terbaru (sinkron dengan tabel kecurangan)
        // ======================================
        $recentFraudCases = DB::table('kecurangan')
            ->leftJoin('salesman', 'salesman.ID_SALESMAN', '=', 'kecurangan.ID_SALES')
            ->select(
                'kecurangan.ID_SALES as id_sales',
                'salesman.NAMA_SALESMAN as nama_sales',
                'kecurangan.DISTRIBUTOR as distributor',
                'kecurangan.JENIS_SANKSI as jenis_sanksi',
                'kecurangan.NILAI_SANKSI as nilai_sanksi',
                'kecurangan.TANGGAL as tanggal'
            )
            ->where('kecurangan.VALIDASI', 1)
            ->orderBy('kecurangan.CREATED_AT', 'DESC')
            ->limit(5)
            ->get();


        // ======================================
        // TOTAL ASS
        // ======================================
        $totalAssAktif = DB::table('specialist_manager')
            ->distinct('ID_SPC_MANAGER')
            ->count('ID_SPC_MANAGER');

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
