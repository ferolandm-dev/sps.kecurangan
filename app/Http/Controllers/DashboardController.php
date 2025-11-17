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

        // Pastikan 12 bulan terisi (kosong = 0)
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
        // 📅 Hitung total kecurangan per kuartal
        // ======================================
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

        $totalKecuranganKuartalIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', '>=', $startMonth)
            ->whereMonth('tanggal', '<=', $endMonth)
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
        // 👥 Total User
        // ======================================
        $totalUser = DB::table('users')->count();

        // ======================================
        // 🔁 Kirim semua data ke view
        // ======================================
        return view('dashboard', compact(
            'totalDistributorAktif',
            'totalSalesAktif',
            'fraudData',
            'topDistributors',
            'topFraudSales',
            'totalKecuranganBulanIni',
            'totalKecuranganKuartalIni',
            'currentQuarter',
            'totalUser',
            'totalNilaiSanksiBulanIni'
        ));
    }
}