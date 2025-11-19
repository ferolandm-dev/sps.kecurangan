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
        // Sales list
        $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();


        // ASS = Salesman TYPE_SALESMAN = 7
        $asistenManagers = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');
        $keteranganSanksi = DB::table('sanksi')->get();

        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.id_sales', '=', 'salesman.ID_SALESMAN')
            ->select('kecurangan.*', 'salesman.NAMA_SALESMAN as nama_sales');

        // SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('salesman.NAMA_SALESMAN', 'like', "%" . $request->search . "%")
                  ->orWhere('kecurangan.toko', 'like', "%" . $request->search . "%")
                  ->orWhere('kecurangan.kunjungan', 'like', "%" . $request->search . "%");
            });
        }

        // SORT
        $allowedSorts = ['id_sales', 'nama_sales', 'toko', 'kunjungan', 'tanggal', 'jenis_sanksi'];

        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, $allowedSorts)) {
            $column = $sortBy === 'nama_sales'
                ? 'salesman.NAMA_SALESMAN'
                : "kecurangan.$sortBy";

            $query->orderBy($column, $sortOrder);
        }

        $kecurangan = $request->boolean('all')
            ? $query->get()
            : $query->paginate(10)->appends($request->query());

        return view('kecurangan.index', compact(
            'sales',
            'asistenManagers',
            'kecurangan',
            'jenisSanksi',
            'keteranganSanksi'
        ));
    }

    /* ==========================================================
     | CREATE
     ========================================================== */
    public function create()
    {
        $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();

        // ASS = Salesman type 7
        $asistenManagers = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

        return view('kecurangan.create', compact('sales', 'asistenManagers', 'jenisSanksi'));
    }

    /* ==========================================================
 | DATA PAGE (LIST KECURANGAN DENGAN FILTER + SEARCH)
========================================================== */
public function data(Request $request)
{
    // LIST SALES (UNTUK FILTER & MODAL)
    $sales = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->select('ID_SALESMAN as id_sales', 'NAMA_SALESMAN as nama_sales')
            ->get();

    $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');
    $keteranganSanksi = DB::table('sanksi')->get();

    // QUERY BASE
    $query = DB::table('kecurangan')
        ->leftJoin('salesman', 'kecurangan.id_sales', '=', 'salesman.ID_SALESMAN')
        ->select('kecurangan.*', 'salesman.NAMA_SALESMAN as nama_sales');

    /* ===================================================
       🔍 SEARCH
    =================================================== */
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('kecurangan.nama_sales', 'like', "%$search%")
              ->orWhere('kecurangan.toko', 'like', "%$search%")
              ->orWhere('kecurangan.keterangan_sanksi', 'like', "%$search%")
              ->orWhere('kecurangan.jenis_sanksi', 'like', "%$search%");
        });
    }

    /* ===================================================
       🎯 FILTER
    =================================================== */

    // Filter Sales
    if ($request->filled('sales')) {
        $query->where('kecurangan.id_sales', $request->sales);
    }

    // Filter Jenis Sanksi
    if ($request->filled('jenis_sanksi')) {
        $query->where('kecurangan.jenis_sanksi', $request->jenis_sanksi);
    }

    // Filter Keterangan Sanksi
    if ($request->filled('keterangan_sanksi')) {
        $query->where('kecurangan.keterangan_sanksi', $request->keterangan_sanksi);
    }

    // Filter tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('kecurangan.tanggal', [
            $request->start_date,
            $request->end_date
        ]);
    }

    /* ===================================================
       🔽 SORTING
    =================================================== */
    $allowedSorts = [
        'id_sales',
        'nama_sales',
        'distributor',
        'nama_asisten_manager',
        'jenis_sanksi',
        'keterangan_sanksi',
        'nilai_sanksi',
        'toko',
        'kunjungan',
        'tanggal',
        'kuartal'
    ];

    $sortBy = $request->get('sort_by', 'tanggal');
    $sortOrder = $request->get('sort_order', 'desc');

    if (in_array($sortBy, $allowedSorts)) {
        $column = $sortBy === 'nama_sales'
            ? 'salesman.NAMA_SALESMAN'
            : "kecurangan.$sortBy";

        $query->orderBy($column, $sortOrder);
    }

    /* ===================================================
       📄 PAGINATION / TAMPILKAN SEMUA
    =================================================== */
    $kecurangan = $request->has('all')
        ? $query->get()
        : $query->paginate(10)->appends($request->query());

    /* ===================================================
       RETURN VIEW
    =================================================== */
    return view('kecurangan.data', compact(
        'kecurangan',
        'sales',
        'jenisSanksi',
        'keteranganSanksi'
    ));
}

    /* ==========================================================
     | STORE
     ========================================================== */
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
            'jenis_sanksi' => 'required|string|max:100',
            'deskripsi_sanksi' => 'required|string',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // LIMIT FOTO 5
        if ($request->hasFile('bukti') && count($request->file('bukti')) > 5) {
            return back()->with('error', 'Maksimal 5 foto yang dapat diunggah.');
        }

        // FORMAT TANGGAL
        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
        $bulan = (int) date('m', strtotime($tanggal));
        $tahun = (int) date('Y', strtotime($tanggal));

        $kuartal = match (true) {
            $bulan <= 3 => "Q1 $tahun",
            $bulan <= 6 => "Q2 $tahun",
            $bulan <= 9 => "Q3 $tahun",
            default     => "Q4 $tahun",
        };

        // INSERT
        $id = DB::table('kecurangan')->insertGetId([
            'id_sales' => $request->id_sales,
            'nama_sales' => $request->nama_sales,
            'id_asisten_manager' => $request->id_asisten_manager,
            'nama_asisten_manager' => $request->nama_asisten_manager,
            'distributor' => $request->distributor,
            'toko' => $request->toko,
            'kunjungan' => $request->kunjungan,
            'tanggal' => $tanggal,
            'keterangan' => $request->keterangan,
            'kuartal' => $kuartal,
            'jenis_sanksi' => $request->jenis_sanksi,
            'keterangan_sanksi' => $request->deskripsi_sanksi,
            'nilai_sanksi' => $request->nilai_sanksi ? str_replace(['Rp', '.', ' '], '', $request->nilai_sanksi) : null,
            'validasi' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // UPLOAD FOTO
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs('kecurangan', $filename, 'public');

                DB::table('kecurangan_foto')->insert([
                    'id_kecurangan' => $id,
                    'path' => $path,
                    'created_at' => now()
                ]);
            }
        }

        return redirect()->route('kecurangan.index')->with('success', 'Data kecurangan berhasil ditambahkan!');
    }
    
    /* ==========================================================
    | VALIDASI
    ========================================================== */
    public function validasi($id)
    {
        // Cek apakah data kecurangan ada
        $cek = DB::table('kecurangan')->where('id', $id)->first();
        if (!$cek) {
            return back()->with('error', 'Data kecurangan tidak ditemukan.');
        }

        // Update validasi menjadi 1
        DB::table('kecurangan')->where('id', $id)->update([
            'validasi' => 1,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Data berhasil divalidasi!');
    }


    /* ==========================================================
    | DESTROY
    ========================================================== */
    public function destroy($id)
    {
        // cek data kecurangan
        $data = DB::table('kecurangan')->where('id', $id)->first();
        if (!$data) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // hapus foto dulu
        $fotos = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->get();
        foreach ($fotos as $foto) {

            // hapus file di storage/public
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }

            // hapus record
            DB::table('kecurangan_foto')->where('id', $foto->id)->delete();
        }

        // hapus record utama
        DB::table('kecurangan')->where('id', $id)->delete();

        return redirect()->route('kecurangan.index')->with('success', 'Data kecurangan berhasil dihapus!');
    }

    /* ==========================================================
    | EDIT
    ========================================================== */
    public function edit($id)
    {
        $kecurangan = DB::table('kecurangan')->where('id', $id)->first();
        if (!$kecurangan) {
            return redirect()->route('kecurangan.index')->with('error', 'Data tidak ditemukan.');
        }

        // daftar sales
        $sales = DB::table('salesman')
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();

        // daftar ASS: TYPE_SALESMAN = 7
        $asistenManagers = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN', 'ID_DISTRIBUTOR')
            ->get();

        // foto-foto lama
        $fotos = DB::table('kecurangan_foto')
            ->where('id_kecurangan', $id)
            ->get()
            ->map(function ($f) {
                $f->url = asset('storage/' . $f->path);
                return $f;
            });

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

        return view('kecurangan.edit', compact(
            'kecurangan',
            'sales',
            'asistenManagers',
            'fotos',
            'jenisSanksi'
        ));
    }

    /* ==========================================================
    | UPDATE
    ========================================================== */
    public function update(Request $request, $id)
{
    $request->validate([
        'id_sales' => 'required',
        'nama_sales' => 'required',
        'distributor' => 'required',
        'toko' => 'required',
        'kunjungan' => 'required',
        'tanggal' => 'required|date_format:d/m/Y',
        'jenis_sanksi' => 'required',
        'deskripsi_sanksi' => 'required',
        'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120'
    ]);

    // --- FORMAT TANGGAL ---
    $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

    /* ============================================================
       1. HAPUS FOTO LAMA (deleted_photos[])
    ============================================================ */
    if ($request->filled('deleted_photos')) {
        foreach ($request->deleted_photos as $fotoId) {

            $foto = DB::table('kecurangan_foto')->where('id', $fotoId)->first();
            if ($foto) {

                // hapus file di storage/public
                if (Storage::disk('public')->exists($foto->path)) {
                    Storage::disk('public')->delete($foto->path);
                }

                // hapus record
                DB::table('kecurangan_foto')->where('id', $fotoId)->delete();
            }
        }
    }

    /* ============================================================
       2. CEK TOTAL FOTO TIDAK LEBIH DARI 5
    ============================================================ */
    $fotoLama = DB::table('kecurangan_foto')
        ->where('id_kecurangan', $id)
        ->count();

    $fotoBaru = $request->hasFile('bukti') ? count($request->file('bukti')) : 0;

    if (($fotoLama + $fotoBaru) > 5) {
        return back()->with('error', 'Total foto tidak boleh lebih dari 5.');
    }

    /* ============================================================
       3. UPDATE DATA UTAMA
    ============================================================ */
    DB::table('kecurangan')->where('id', $id)->update([
        'id_sales' => $request->id_sales,
        'nama_sales' => $request->nama_sales,
        'id_asisten_manager' => $request->id_asisten_manager,
        'nama_asisten_manager' => $request->nama_asisten_manager,
        'distributor' => $request->distributor,
        'toko' => $request->toko,
        'kunjungan' => $request->kunjungan,
        'tanggal' => $tanggal,
        'keterangan' => $request->keterangan,
        'jenis_sanksi' => $request->jenis_sanksi,
        'keterangan_sanksi' => $request->deskripsi_sanksi,
        'updated_at' => now(),
    ]);

    /* ============================================================
       4. UPLOAD FOTO BARU
    ============================================================ */
    if ($request->hasFile('bukti')) {
        foreach ($request->file('bukti') as $file) {
            $filename = time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
            $path = $file->storeAs('kecurangan', $filename, 'public');

            DB::table('kecurangan_foto')->insert([
                'id_kecurangan' => $id,
                'path' => $path,
                'created_at' => now()
            ]);
        }
    }

    return redirect()->route('kecurangan.index')->with('success', 'Data berhasil diperbarui!');
}


    /* ==========================================================
     | GET SALES (AJAX)
     ========================================================== */
    public function getSales($id)
    {
        $sales = DB::table('salesman')
                ->where('ID_SALESMAN', $id)
                ->where('TYPE_SALESMAN', 1)
                ->first();

        if (!$sales) return response()->json(['error' => 'Sales tidak ditemukan'], 404);

        $distributor = DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $sales->ID_DISTRIBUTOR)
            ->first();

        return response()->json([
            'nama_sales' => $sales->NAMA_SALESMAN,
            'distributor_id' => $sales->ID_DISTRIBUTOR,
            'distributor' => $distributor?->NAMA_DISTRIBUTOR ?? '-',
        ]);
    }

    /* ==========================================================
     | GET ASS (TYPE_SALESMAN = 7) (AJAX)
     ========================================================== */
    public function getAsistenManager($distributorId)
    {
        return DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->where('ID_DISTRIBUTOR', $distributorId)
            ->select('ID_SALESMAN as id', 'NAMA_SALESMAN as nama')
            ->get();
    }

    /* ==========================================================
     | GET BUKTI
     ========================================================== */
    public function getBukti($id)
    {
        return DB::table('kecurangan_foto')
            ->where('id_kecurangan', $id)
            ->get()
            ->map(function ($f) {
                $f->url = asset('storage/' . $f->path);
                return $f;
            });
    }

    /* ==========================================================
     | GET DESKRIPSI SAN KSI
     ========================================================== */
    public function getKeteranganByJenis(Request $request)
    {
        $data = DB::table('sanksi')
            ->where('jenis', $request->jenis_sanksi)
            ->pluck('keterangan')
            ->map(fn ($item) => ['keterangan' => $item]);

        return response()->json($data);
    }

    /* ==========================================================
 | EXPORT PDF
========================================================== */
public function exportPDF(Request $request)
{
    $query = DB::table('kecurangan')
        ->where('validasi', 1)
        ->leftJoin('salesman', 'kecurangan.id_sales', '=', 'salesman.ID_SALESMAN')
        ->select('kecurangan.*', 'salesman.NAMA_SALESMAN as nama_sales');

    // FILTER SALES
    $sales = null;
    if ($request->filled('sales')) {
        $query->where('kecurangan.id_sales', $request->sales);

        $sales = DB::table('salesman')
            ->select('ID_SALESMAN as id_sales', 'NAMA_SALESMAN as nama_sales')
            ->where('ID_SALESMAN', $request->sales)
            ->first();
    }

    // FILTER JENIS SANKSI
    if ($request->filled('jenis_sanksi')) {
        $query->where('kecurangan.jenis_sanksi', $request->jenis_sanksi);
    }

    // FILTER KETERANGAN SANKSI
    if ($request->filled('keterangan_sanksi')) {
        $query->where('kecurangan.keterangan_sanksi', $request->keterangan_sanksi);
    }

    // FILTER PERIODE
    $startDate = $request->start_date ?? null;
    $endDate   = $request->end_date ?? null;

    if ($request->mode_pdf === 'date' && $startDate && $endDate) {
        $query->whereBetween('kecurangan.tanggal', [$startDate, $endDate]);
    }

    $data = $query->orderBy('tanggal', 'desc')->get();

    return PDF::loadView('pdf.kecurangan', [
        'data' => $data,
        'sales' => $sales,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ])
        ->setPaper('A4', 'landscape')
        ->stream('data_kecurangan.pdf');
}

public function exportExcel(Request $request)
{
    // ============================
    // Ambil filter dari request
    // ============================
    $mode         = $request->mode_excel ?? 'all';
    $startDate    = $request->start_date ?? null;
    $endDate      = $request->end_date ?? null;
    $jenis        = $request->jenis_sanksi ?? null;
    $keterangan   = $request->keterangan_sanksi ?? null;
    $sales        = $request->sales ?? null;

    // ============================
    // PANGGIL EXPORT
    // ============================
    return Excel::download(
        new KecuranganExport(
            $mode,
            $startDate,
            $endDate,
            $jenis,
            $keterangan,
            $sales
        ),
        'data_kecurangan.xlsx'
    );
}

}