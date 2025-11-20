<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KecuranganExport;
use PDF;

class KecuranganController extends Controller
{
    /* ==========================================================
     | INDEX
     ========================================================== */
    public function index(Request $request)
    {
        $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');
        $keteranganSanksi = DB::table('sanksi')->get();

        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->leftJoin('specialist_manager', 'kecurangan.ID_SPC_MANAGER', '=', 'specialist_manager.ID_SPC_MANAGER')
            ->select(
                'kecurangan.*',
                'salesman.NAMA_SALESMAN as nama_sales',
                'specialist_manager.NAMA as nama_specialist_manager',
                'salesman.ID_DISTRIBUTOR as distributor'
            );


        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('salesman.NAMA_SALESMAN', 'like', "%{$request->search}%")
                  ->orWhere('kecurangan.TOKO', 'like', "%{$request->search}%")
                  ->orWhere('kecurangan.KUNJUNGAN', 'like', "%{$request->search}%");
            });
        }

        $allowedSorts = ['ID_SALES','NAMA_SALESMAN','TOKO','KUNJUNGAN','TANGGAL','JENIS_SANKSI'];
        $sortBy = $request->get('sort_by', 'TANGGAL');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, $allowedSorts)) {
            $column = $sortBy === 'NAMA_SALESMAN'
                ? 'salesman.NAMA_SALESMAN'
                : "kecurangan.$sortBy";

            $query->orderBy($column, $sortOrder);
        }

        $kecurangan = $request->boolean('all')
            ? $query->get()
            : $query->paginate(10)->appends($request->query());

        return view('kecurangan.index', compact(
            'sales',
            'kecurangan',
            'jenisSanksi',
            'keteranganSanksi'
        ));
    }

    public function data(Request $request)
    {
        $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', '1')
            ->select('ID_SALESMAN as id_sales', 'NAMA_SALESMAN as nama_sales')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');
        $keteranganSanksi = DB::table('sanksi')->get();

        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->leftJoin('specialist_manager', 'kecurangan.ID_SPC_MANAGER', '=', 'specialist_manager.ID_SPC_MANAGER')
            ->select(
                'kecurangan.*',
                'salesman.NAMA_SALESMAN as nama_sales',
                'specialist_manager.NAMA as nama_specialist_manager',
                'salesman.ID_DISTRIBUTOR as distributor'
            );


        // SEARCH
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
                ->orWhere('kecurangan.TOKO', 'like', "%{$s}%")
                ->orWhere('kecurangan.KETERANGAN_SANKSI', 'like', "%{$s}%")
                ->orWhere('kecurangan.JENIS_SANKSI', 'like', "%{$s}%");
            });
        }

        // FILTER
        if ($request->filled('sales')) {
            $query->where('kecurangan.ID_SALES', $request->sales);
        }

        if ($request->filled('jenis_sanksi')) {
            $query->where('kecurangan.JENIS_SANKSI', $request->jenis_sanksi);
        }

        if ($request->filled('keterangan_sanksi')) {
            $query->where('kecurangan.KETERANGAN_SANKSI', $request->keterangan_sanksi);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('kecurangan.TANGGAL', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // SORT
        $allowedSorts = [
            'ID_SALES', 'nama_sales', 'DISTRIBUTOR', 'nama_spc_manager',
            'JENIS_SANKSI', 'KETERANGAN_SANKSI', 'NILAI_SANKSI',
            'TOKO', 'KUNJUNGAN', 'TANGGAL', 'KUARTAL'
        ];

        $sortBy = $request->get('sort_by', 'TANGGAL');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, $allowedSorts)) {
            $column = $sortBy === 'nama_sales' ? 'salesman.NAMA_SALESMAN' : "kecurangan.$sortBy";
            $query->orderBy($column, $sortOrder);
        }

        $kecurangan = $request->has('all')
            ? $query->get()
            : $query->paginate(10)->appends($request->query());

        return view('kecurangan.data', compact(
            'kecurangan',
            'sales',
            'jenisSanksi',
            'keteranganSanksi'
        ));
    }

    /* ==========================================================
     | CREATE PAGE
     ========================================================== */
    public function create()
    {
        $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->select('ID_SALESMAN','NAMA_SALESMAN')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

        return view('kecurangan.create', compact('sales', 'jenisSanksi'));
    }

    /* ==========================================================
     | STORE
     ========================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'id_sales' => 'required',
            'id_specialist_manager' => 'required',
            'distributor' => 'required',
            'toko' => 'required',
            'kunjungan' => 'required',
            'tanggal' => 'required|date_format:d/m/Y',
            'jenis_sanksi' => 'required|string|max:100',
            'deskripsi_sanksi' => 'required|string',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Maksimal 5 foto
        if ($request->hasFile('bukti') && count($request->file('bukti')) > 5) {
            return back()->with('error', 'Maksimal total 5 foto.');
        }

        // Format tanggal
        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

        $bulan = (int)date('m', strtotime($tanggal));
        $tahun = (int)date('Y', strtotime($tanggal));

        $kuartal = $bulan <= 3 ? "Q1 $tahun" :
                ($bulan <= 6 ? "Q2 $tahun" :
                ($bulan <= 9 ? "Q3 $tahun" : "Q4 $tahun"));

        // Bersihkan nilai sanksi
        $nilaiSanksi = $request->nilai_sanksi 
            ? str_replace(['Rp', '.', ' ', ','], '', $request->nilai_sanksi)
            : 0;

        // Insert ke tabel kecurangan sesuai struktur baru
        $id = DB::table('kecurangan')->insertGetId([
            'ID_SALES'          => $request->id_sales,
            'ID_SPC_MANAGER'    => $request->id_specialist_manager,
            'DISTRIBUTOR'       => $request->distributor,
            'TOKO'              => $request->toko,
            'KUNJUNGAN'         => $request->kunjungan,
            'TANGGAL'           => $tanggal,
            'KETERANGAN'        => $request->keterangan,
            'KUARTAL'           => $kuartal,
            'JENIS_SANKSI'      => $request->jenis_sanksi,
            'KETERANGAN_SANKSI' => $request->deskripsi_sanksi,
            'NILAI_SANKSI'      => $nilaiSanksi,
            'VALIDASI'          => 0,
            'CREATED_AT'        => now(),
            'UPDATED_AT'        => now(),
        ]);

        // Upload foto
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('kecurangan', $filename, 'public');

                DB::table('kecurangan_foto')->insert([
                    'ID_KECURANGAN' => $id,
                    'PATH'          => $path,
                    'CREATED_AT'    => now(),
                ]);
            }
        }

        return redirect()->route('kecurangan.index')->with('success', 'Data berhasil disimpan!');
    }


    /* ==========================================================
 | EDIT PAGE
========================================================== */
public function edit($id)
{
    // Ambil data kecurangan
    $kecurangan = DB::table('kecurangan')
        ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
        ->leftJoin('specialist_manager', 'kecurangan.ID_SPC_MANAGER', '=', 'specialist_manager.ID_SPC_MANAGER')
        ->select(
            'kecurangan.*',
            'salesman.NAMA_SALESMAN as NAMA_SALES',
            'specialist_manager.NAMA as NAMA_SPECIALIST_MANAGER'
        )
        ->where('kecurangan.ID', $id)
        ->first();

    if (!$kecurangan) {
        return redirect()->route('kecurangan.index')->with('error', 'Data tidak ditemukan.');
    }

    // Sales dropdown
    $sales = DB::table('salesman')
        ->where('TYPE_SALESMAN', 1)
        ->select('ID_SALESMAN', 'NAMA_SALESMAN')
        ->get();

    // Jenis sanksi
    $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

    // Foto lama
    $fotos = DB::table('kecurangan_foto')
        ->where('ID_KECURANGAN', $id)
        ->get()
        ->map(function ($f) {
            $f->url = asset('storage/' . $f->PATH);
            return $f;
        });

    return view('kecurangan.edit', compact(
        'kecurangan',
        'sales',
        'jenisSanksi',
        'fotos'
    ));
}


/* ==========================================================
 | UPDATE
========================================================== */
public function update(Request $request, $id)
{
    $request->validate([
        'id_sales' => 'required',
        'id_specialist_manager' => 'required',
        'distributor' => 'required',
        'toko' => 'required',
        'kunjungan' => 'required',
        'tanggal' => 'required|date_format:d/m/Y',
        'jenis_sanksi' => 'required|string|max:100',
        'deskripsi_sanksi' => 'required|string',
        'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
    ]);

    // Format tanggal
    $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

    // Hitung kuartal
    $bulan = (int)date('m', strtotime($tanggal));
    $tahun = (int)date('Y', strtotime($tanggal));

    $kuartal = $bulan <= 3 ? "Q1 $tahun" :
              ($bulan <= 6 ? "Q2 $tahun" :
              ($bulan <= 9 ? "Q3 $tahun" : "Q4 $tahun"));

    // Bersihkan nilai sanksi
    $nilaiSanksi = $request->nilai_sanksi 
        ? str_replace(['Rp', '.', ' ', ','], '', $request->nilai_sanksi)
        : 0;

    // Update data inti
    DB::table('kecurangan')
        ->where('ID', $id)
        ->update([
            'ID_SALES'          => $request->id_sales,
            'ID_SPC_MANAGER'    => $request->id_specialist_manager,
            'DISTRIBUTOR'       => $request->distributor,
            'TOKO'              => $request->toko,
            'KUNJUNGAN'         => $request->kunjungan,
            'TANGGAL'           => $tanggal,
            'KETERANGAN'        => $request->keterangan,
            'KUARTAL'           => $kuartal,
            'JENIS_SANKSI'      => $request->jenis_sanksi,
            'KETERANGAN_SANKSI' => $request->deskripsi_sanksi,
            'NILAI_SANKSI'      => $nilaiSanksi,
            'UPDATED_AT'        => now(),
        ]);

    // Hapus foto lama
    if ($request->filled('deleted_photos')) {
        foreach ($request->deleted_photos as $fotoId) {

            $foto = DB::table('kecurangan_foto')->where('ID', $fotoId)->first();
            if ($foto) {
                Storage::disk('public')->delete($foto->PATH);
            }

            DB::table('kecurangan_foto')->where('ID', $fotoId)->delete();
        }
    }

    // Upload foto baru
    if ($request->hasFile('bukti')) {
        foreach ($request->file('bukti') as $file) {
            $filename = time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
            $path = $file->storeAs('kecurangan', $filename, 'public');

            DB::table('kecurangan_foto')->insert([
                'ID_KECURANGAN' => $id,
                'PATH'          => $path,
                'CREATED_AT'    => now(),
            ]);
        }
    }

    return redirect()->route('kecurangan.index')->with('success', 'Data berhasil diperbarui!');
}

    public function destroy($id)
{
    // Cek data kecurangan
    $row = DB::table('kecurangan')->where('ID', $id)->first();

    if (!$row) {
        return redirect()->route('kecurangan.index')
            ->with('error', 'Data tidak ditemukan.');
    }

    // Hapus semua foto terkait
    $fotos = DB::table('kecurangan_foto')
        ->where('ID_KECURANGAN', $id)
        ->get();

    foreach ($fotos as $f) {
        Storage::disk('public')->delete($f->PATH);
    }

    DB::table('kecurangan_foto')->where('ID_KECURANGAN', $id)->delete();

    // Hapus data kecurangan
    DB::table('kecurangan')->where('ID', $id)->delete();

    return redirect()->route('kecurangan.index')
        ->with('success', 'Data berhasil dihapus!');
}

public function validasi($id)
{
    // cek data
    $row = DB::table('kecurangan')->where('ID', $id)->first();

    if (!$row) {
        return redirect()->route('kecurangan.index')
            ->with('error', 'Data tidak ditemukan.');
    }

    // update validasi
    DB::table('kecurangan')
        ->where('ID', $id)
        ->update([
            'VALIDASI'   => 1,
            'UPDATED_AT' => now(),
        ]);

    return redirect()->route('kecurangan.index')
        ->with('success', 'Data berhasil divalidasi.');
}


    /* ==========================================================
     | AJAX: GET SALES
     ========================================================== */
    public function getSales($id)
    {
        // Ambil data sales
        $sales = DB::table('salesman')
            ->where('ID_SALESMAN', $id)
            ->where('TYPE_SALESMAN', 1)
            ->first();

        if (!$sales) {
            return response()->json(['error' => 'Sales tidak ditemukan'], 404);
        }

        // Ambil distributor
        $distributor = DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $sales->ID_DISTRIBUTOR)
            ->first();

        // Ambil nama specialist manager
        $specialistManager = null;

        if ($sales->ID_SPC_MANAGER) {
            $specialistManager = DB::table('specialist_manager')
                ->where('ID_SPC_MANAGER', $sales->ID_SPC_MANAGER)
                ->value('NAMA');
        }

        return response()->json([
            'nama_sales' => $sales->NAMA_SALESMAN,
            'distributor' => $distributor?->NAMA_DISTRIBUTOR ?? '-',
            'id_specialist_manager' => $sales->ID_SPC_MANAGER,
            'nama_specialist_manager' => $specialistManager ?? '-',
        ]);
    }


    /* ==========================================================
     | AJAX: GET SPECIALIST MANAGER
     ========================================================== */
    public function getSpecialistManager($id)
    {
        $sm = DB::table('specialist_manager')
            ->where('ID_SPC_MANAGER', $id)
            ->first();

        if (!$sm) {
            return response()->json(['error' => 'Tidak ditemukan'], 404);
        }

        return response()->json([
            'id_specialist_manager' => $sm->ID_SPC_MANAGER,
            'nama_specialist_manager' => $sm->NAMA,
        ]);
    }

    public function getBukti($id)
{
    $foto = DB::table('kecurangan_foto')
        ->where('ID_KECURANGAN', $id)
        ->get();

    // Kembalikan dalam bentuk url
    $foto = $foto->map(function($f) {
        return [
            'id'  => $f->ID,
            'url' => asset('storage/' . $f->PATH)
        ];
    });

    return response()->json($foto);
}

public function exportPDF(Request $request)
{
    // Ambil data sesuai filter yg sama seperti index()
    $query = DB::table('kecurangan')
        ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
        ->leftJoin('specialist_manager', 'kecurangan.ID_SPC_MANAGER', '=', 'specialist_manager.ID_SPC_MANAGER')
        ->select(
            'kecurangan.*',
            'salesman.NAMA_SALESMAN as nama_sales',
            'specialist_manager.NAMA as nama_specialist_manager',
            'salesman.ID_DISTRIBUTOR as distributor'
        );

    // FILTER SEARCH
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
              ->orWhere('kecurangan.TOKO', 'like', "%{$s}%")
              ->orWhere('kecurangan.KETERANGAN_SANKSI', 'like', "%{$s}%")
              ->orWhere('kecurangan.JENIS_SANKSI', 'like', "%{$s}%");
        });
    }

    // FILTER SALES
    if ($request->filled('sales')) {
        $query->where('kecurangan.ID_SALES', $request->sales);
    }

    // FILTER JENIS SANKSI
    if ($request->filled('jenis_sanksi')) {
        $query->where('kecurangan.JENIS_SANKSI', $request->jenis_sanksi);
    }

    // FILTER DESKRIPSI
    if ($request->filled('keterangan_sanksi')) {
        $query->where('kecurangan.KETERANGAN_SANKSI', $request->keterangan_sanksi);
    }

    // FILTER TANGGAL
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('kecurangan.TANGGAL', [
            $request->start_date,
            $request->end_date
        ]);
    }

    $data = $query->orderBy('kecurangan.TANGGAL', 'desc')->get();


    // **LOAD PDF MENGGUNAKAN NAMA FILE YANG BENAR**
    $pdf = \PDF::loadView('pdf.kecurangan', [
        'data' => $data,
        'startDate' => $request->start_date,
        'endDate' => $request->end_date,
        'sales' => $request->filled('sales')
            ? DB::table('salesman')->where('ID_SALESMAN', $request->sales)->first()
            : null
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan_kecurangan.pdf');
}


public function exportExcel(Request $request)
{
    return Excel::download(new KecuranganExport($request), 'laporan_kecurangan.xlsx');    
}
}