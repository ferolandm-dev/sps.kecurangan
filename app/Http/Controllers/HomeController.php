<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Total distributor aktif
        $totalDistributorAktif = DB::table('distributors')
            ->where('status', 'Aktif')
            ->count();

        // Total sales aktif
        $totalSalesAktif = DB::table('sales')
            ->where('status', 'Aktif')
            ->count();

        // Jumlah kecurangan tiap bulan (yang sudah tervalidasi)
        $fraudPerMonth = DB::table('kecurangan')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('validasi', 1)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Isi semua 12 bulan, jika kosong isi 0
        $fraudData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fraudData[] = $fraudPerMonth[$i] ?? 0;
        }

        // Top 5 distributor berdasarkan jumlah sales aktif
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

        // Top 5 sales curang (yang sudah tervalidasi)
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
            ->orderByDesc('total_kecurangan') // urut dari yang paling banyak curang
            ->orderBy('terakhir_validasi', 'asc') // kalau jumlah sama, tampilkan yang lebih dulu divalidasi
            ->limit(5)
            ->get();

        // Total kecurangan bulan ini (yang tervalidasi)
        $totalKecuranganBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

<<<<<<< HEAD
=======
            // Hitung kuartal sekarang
$currentMonth = date('n');
if ($currentMonth >= 1 && $currentMonth <= 3) {
    $currentQuarter = 1;
    $startMonth = 1;
    $endMonth = 3;
} elseif ($currentMonth >= 4 && $currentMonth <= 6) {
    $currentQuarter = 2;
    $startMonth = 4;
    $endMonth = 6;
} elseif ($currentMonth >= 7 && $currentMonth <= 9) {
    $currentQuarter = 3;
    $startMonth = 7;
    $endMonth = 9;
} else {
    $currentQuarter = 4;
    $startMonth = 10;
    $endMonth = 12;
}

// Total kecurangan pada kuartal saat ini (yang tervalidasi)
$totalKecuranganKuartalIni = DB::table('kecurangan')
    ->where('validasi', 1)
    ->whereMonth('tanggal', '>=', $startMonth)
    ->whereMonth('tanggal', '<=', $endMonth)
    ->whereYear('tanggal', date('Y'))
    ->count();


>>>>>>> recovery-branch
        return view('home', compact(
            'totalDistributorAktif',
            'totalSalesAktif',
            'fraudData',
            'topDistributors',
            'topFraudSales',
<<<<<<< HEAD
            'totalKecuranganBulanIni'
=======
            'totalKecuranganBulanIni',
            'totalKecuranganKuartalIni', // 👈 tambahkan ini
            'currentQuarter' // 👈 dan ini
>>>>>>> recovery-branch
        ));
    }
}