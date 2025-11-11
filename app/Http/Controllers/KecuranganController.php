<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KecuranganExport;
use PDF;

class KecuranganController extends Controller
{
    public function index()
    {
        $sales = DB::table('sales')->select('id', 'nama', 'id_distributor')->get();
        $asistenManagers = DB::table('asisten_managers')->select('id', 'nama', 'id_distributor')->get();
        $kecurangan = DB::table('kecurangan')->orderBy('tanggal', 'desc')->get();

        return view('kecurangan.index', compact('sales', 'asistenManagers', 'kecurangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sales' => 'required',
            'nama_sales' => 'required',
            'id_asisten_manager' => 'nullable',
            'nama_asisten_manager' => 'nullable',
            'distributor' => 'required',
            'toko' => 'required',
            'kunjungan' => 'required',
            'tanggal' => 'required|date_format:d/m/Y',
            'keterangan' => 'nullable',
        ]);

        // ✅ Ubah format tanggal ke Y-m-d
        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

        // ✅ Tentukan kuartal berdasarkan bulan
        $bulan = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('m');
        $tahun = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y');

        if ($bulan >= 1 && $bulan <= 3) {
            $kuartal = 'Q1 ' . $tahun;
        } elseif ($bulan >= 4 && $bulan <= 6) {
            $kuartal = 'Q2 ' . $tahun;
        } elseif ($bulan >= 7 && $bulan <= 9) {
            $kuartal = 'Q3 ' . $tahun;
        } else {
            $kuartal = 'Q4 ' . $tahun;
        }

        DB::table('kecurangan')->insert([
            'id_sales' => $request->id_sales,
            'nama_sales' => $request->nama_sales,
            'id_asisten_manager' => $request->id_asisten_manager,
            'nama_asisten_manager' => $request->nama_asisten_manager,
            'distributor' => $request->distributor,
            'toko' => $request->toko,
            'kunjungan' => $request->kunjungan,
            'tanggal' => $tanggal,
            'keterangan' => $request->keterangan,
            'kuartal' => $kuartal, // ✅ kolom baru
            'validasi' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('kecurangan.data')
            ->with('success', 'Data kecurangan berhasil ditambahkan!');
    }

    public function getSales($id)
    {
        $sales = DB::table('sales')->where('id', $id)->first();

        if (!$sales) {
            return response()->json(['error' => 'Sales tidak ditemukan'], 404);
        }

        $distributor = DB::table('distributors')->where('id', $sales->id_distributor)->first();

        return response()->json([
            'nama_sales' => $sales->nama,
            'distributor_id' => $sales->id_distributor,
            'distributor' => $distributor ? $distributor->distributor : '-',
        ]);
    }

    public function getAsistenManager($distributorId)
    {
        $asistenManagers = DB::table('asisten_managers')
            ->where('id_distributor', $distributorId)
            ->select('id', 'nama')
            ->get();

        return response()->json($asistenManagers);
    }

    public function data(Request $request)
    {
        $allowedSorts = [
            'id', 'id_sales', 'nama_sales', 'id_asisten_manager', 'nama_asisten_manager',
            'distributor', 'toko', 'kunjungan', 'tanggal', 'keterangan', 'kuartal'
        ];

        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal';
        }
        $sortOrder = ($sortOrder === 'asc') ? 'asc' : 'desc';

        $query = DB::table('kecurangan')
            ->select(
                'kecurangan.*',
                'sales.nama as nama_sales',
                'distributors.distributor',
                'asisten_managers.nama as nama_asisten_manager'
            )
            ->leftJoin('sales', 'kecurangan.id_sales', '=', 'sales.id')
            ->leftJoin('distributors', 'sales.id_distributor', '=', 'distributors.id')
            ->leftJoin('asisten_managers', 'kecurangan.id_asisten_manager', '=', 'asisten_managers.id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sales.nama', 'like', "%{$search}%")
                    ->orWhere('asisten_managers.nama', 'like', "%{$search}%")
                    ->orWhere('distributors.distributor', 'like', "%{$search}%")
                    ->orWhere('kecurangan.toko', 'like', "%{$search}%")
                    ->orWhere('kecurangan.keterangan', 'like', "%{$search}%")
                    ->orWhere('kecurangan.kuartal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('kecurangan.tanggal', [$request->start_date, $request->end_date]);
        }

        switch ($sortBy) {
            case 'nama_sales':
                $query->orderBy('sales.nama', $sortOrder);
                break;
            case 'distributor':
                $query->orderBy('distributors.distributor', $sortOrder);
                break;
            case 'nama_asisten_manager':
                $query->orderBy('asisten_managers.nama', $sortOrder);
                break;
            default:
                $query->orderBy('kecurangan.' . $sortBy, $sortOrder);
        }

        $kecurangan = $request->has('all')
            ? $query->get()
            : $query->paginate(10)->appends($request->query());

        return view('kecurangan.data', compact('kecurangan'));
    }

    public function destroy($id)
    {
        $data = DB::table('kecurangan')->where('id', $id)->first();

        if ($data && $data->validasi == 1) {
            return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa dihapus!');
        }

        DB::table('kecurangan')->where('id', $id)->delete();
        return redirect()->route('kecurangan.data')->with('success', 'Data berhasil dihapus!');
    }

    public function validasi($id)
    {
        DB::table('kecurangan')
            ->where('id', $id)
            ->update(['validasi' => 1, 'updated_at' => now()]);

        return redirect()->route('kecurangan.data')->with('success', 'Data berhasil divalidasi!');
    }

    public function exportExcel()
    {
        return Excel::download(new KecuranganExport, 'data_kecurangan.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = DB::table('kecurangan')
            ->select(
                'id_sales',
                'nama_sales',
                'id_asisten_manager',
                'nama_asisten_manager',
                'distributor',
                'toko',
                'kunjungan',
                'tanggal',
                'keterangan',
                'kuartal' // ✅ ditambahkan agar ikut di PDF
            );

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $pdf = PDF::loadView('pdf.kecurangan', [
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Kecurangan.pdf');
    }
}
