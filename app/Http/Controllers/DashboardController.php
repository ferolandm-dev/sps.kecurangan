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
        // 💰 Total nilai sanksi bulan ini
        // ======================================
        $totalNilaiSanksiBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('nilai_sanksi');

        // ======================================
        // 📆 Progress waktu per hari dalam kuartal (TANPA Hari Minggu)
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

        $year = date('Y');
        $today = now()->startOfDay();

        // Tentukan awal & akhir kuartal
        $quarterRanges = [
            1 => ['start' => "$year-01-01", 'end' => "$year-03-31"],
            2 => ['start' => "$year-04-01", 'end' => "$year-06-30"],
            3 => ['start' => "$year-07-01", 'end' => "$year-09-30"],
            4 => ['start' => "$year-10-01", 'end' => "$year-12-31"],
        ];

        $startQuarter = \Carbon\Carbon::parse($quarterRanges[$currentQuarter]['start']);
        $endQuarter   = \Carbon\Carbon::parse($quarterRanges[$currentQuarter]['end']);

        // Hitung total hari *kerja* (Senin–Sabtu) dalam kuartal
        $totalHariKuartal = 0;
        $temp = $startQuarter->copy();

        while ($temp->lte($endQuarter)) {
            if ($temp->dayOfWeek !== 0) { // 0 = Minggu
                $totalHariKuartal++;
            }
            $temp->addDay();
        }

        // Hitung hari ke berapa (exclude Sunday)
        $hariKe = 0;
        $temp = $startQuarter->copy();

        while ($temp->lte($today)) {
            if ($temp->dayOfWeek !== 0) { // bukan Minggu
                $hariKe++;
            }
            $temp->addDay();
        }

        // Progress dalam persen
        $progressQuarterDay = ($totalHariKuartal > 0)
            ? ($hariKe / $totalHariKuartal) * 100
            : 0;

        $progressQuarterDay = round(min(max($progressQuarterDay, 0), 100), 1);

        // ============================
        // 📊 Total Kasus & Nilai Sanksi per Kuartal
        // ============================
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

        // buat array untuk chart
        $quarters = [1, 2, 3, 4];
        $kasusPerQuarter = [];
        $sanksiPerQuarter = [];

        foreach ($quarters as $q) {
            $row = $quarterSummary->firstWhere('quarter', $q);
            $kasusPerQuarter[] = $row->total_kasus ?? 0;
            $sanksiPerQuarter[] = $row->total_sanksi ?? 0;
        }

        // ================================
        // 📊 Kenaikan / Penurunan Kecurangan Bulan Ini
        // ================================
        $bulanIni = date('m');
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunIni = date('Y');
        $tahunBulanLalu = date('Y', strtotime('-1 month'));

        // Kecurangan bulan ini
        $fraudBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();

        // Kecurangan bulan lalu
        $fraudBulanLalu = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', $bulanLalu)
            ->whereYear('tanggal', $tahunBulanLalu)
            ->count();

        if ($fraudBulanLalu == 0) {
            // Jika tidak ada data bulan lalu → anggap kenaikan penuh (100%)
            $persentasePerubahan = $fraudBulanIni > 0 ? 100 : 0;
        } else {
            $persentasePerubahan = (($fraudBulanIni - $fraudBulanLalu) / $fraudBulanLalu) * 100;
        }

        $trendFraud = round($persentasePerubahan, 1);


        // ======================================
        // 💵 Rata-rata nilai sanksi per kasus (bulan ini)
        // ======================================
        $avgSanksiBulanIni = DB::table('kecurangan')
            ->where('validasi', 1)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->avg('nilai_sanksi');

        // Jika null jadikan 0
        $avgSanksiBulanIni = round($avgSanksiBulanIni ?? 0);



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
            'currentQuarter',
            'totalNilaiSanksiBulanIni',
            'progressQuarterDay',
            'kasusPerQuarter',
            'sanksiPerQuarter',
            'trendFraud',
            'avgSanksiBulanIni'
        ));
    }
}