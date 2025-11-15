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
    public function index(Request $request)
{
    $sales = DB::table('sales')
        ->select('id', 'nama', 'id_distributor')
        ->get();

    $asistenManagers = DB::table('asisten_managers')
        ->select('id', 'nama', 'id_distributor')
        ->get();

    $jenisSanksi = DB::table('sanksi')
        ->distinct()
        ->pluck('jenis');

    $keteranganSanksi = DB::table('sanksi')->get();

    // =============================
    // QUERY DASAR
    // =============================
    $query = DB::table('kecurangan')
        ->join('sales', 'kecurangan.id_sales', '=', 'sales.id')
        ->select('kecurangan.*', 'sales.nama as nama_sales');

    // =============================
    // SEARCH (opsional)
    // =============================
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('sales.nama', 'like', '%' . $request->search . '%')
              ->orWhere('kecurangan.toko', 'like', '%' . $request->search . '%')
              ->orWhere('kecurangan.kunjungan', 'like', '%' . $request->search . '%');
        });
    }

    // =============================
    // SORTING
    // =============================
    $allowedSorts = [
        'id_sales',
        'nama_sales',
        'toko',
        'kunjungan',
        'tanggal',
        'jenis_sanksi',
    ];

    $sortBy = $request->get('sort_by', 'tanggal');
    $sortOrder = $request->get('sort_order', 'desc');

    if (in_array($sortBy, $allowedSorts)) {
        $column = $sortBy === 'nama_sales'
            ? 'sales.nama'
            : 'kecurangan.' . $sortBy;

        $query->orderBy($column, $sortOrder);
    }

    // =============================
    // TAMPILKAN SEMUA
    // =============================
    if ($request->boolean('all')) {
        $kecurangan = $query->get();
    } else {
        $kecurangan = $query->paginate(10)->appends($request->query());
    }

    return view('kecurangan.index', compact(
        'sales',
        'asistenManagers',
        'kecurangan',
        'jenisSanksi',
        'keteranganSanksi'
    ));
}
    public function create()
    {
        $sales = DB::table('sales')
            ->select('id', 'nama', 'id_distributor')
            ->get();

        $asistenManagers = DB::table('asisten_managers')
            ->select('id', 'nama', 'id_distributor')
            ->get();

        $jenisSanksi = DB::table('sanksi')
            ->distinct()
            ->pluck('jenis');

        return view('kecurangan.create', compact(
            'sales',
            'asistenManagers',
            'jenisSanksi'
        ));
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
            'jenis_sanksi' => 'required|string|max:100',
            'deskripsi_sanksi' => 'required|string',
            'nilai_sanksi' => 'nullable|string',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('bukti') && count($request->file('bukti')) > 5) {
            return back()->with('error', 'Maksimal 5 foto yang dapat diunggah.')->withInput();
        }

        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
        $bulan = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('m');
        $tahun = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y');

        if ($bulan >= 1 && $bulan <= 3) $kuartal = 'Q1 ' . $tahun;
        elseif ($bulan >= 4 && $bulan <= 6) $kuartal = 'Q2 ' . $tahun;
        elseif ($bulan >= 7 && $bulan <= 9) $kuartal = 'Q3 ' . $tahun;
        else $kuartal = 'Q4 ' . $tahun;

        $nilaiSanksi = $request->nilai_sanksi ? str_replace(['Rp', '.', ' '], '', $request->nilai_sanksi) : null;

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
            'nilai_sanksi' => $nilaiSanksi,
            'validasi' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kecurangan', $filename, 'public');
                DB::table('kecurangan_foto')->insert([
                    'id_kecurangan' => $id,
                    'path' => $path,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('kecurangan.index')->with('success', 'Data kecurangan berhasil ditambahkan!');
    }


    public function uploadBukti(Request $request, $id)
    {
        $data = DB::table('kecurangan')->where('id', $id)->first();
        if (!$data) return back()->with('error', 'Data kecurangan tidak ditemukan.');
        if ($data->validasi == 1) return back()->with('error', 'Data sudah divalidasi dan tidak bisa diubah.');

        $request->validate([
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $existingCount = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->count();
        $newCount = count($request->file('bukti') ?? []);
        if ($existingCount + $newCount > 5) return back()->with('error', 'Total foto tidak boleh lebih dari 5.');

        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kecurangan', $filename, 'public');
                DB::table('kecurangan_foto')->insert([
                    'id_kecurangan' => $id,
                    'path' => $path,
                    'created_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Foto bukti berhasil ditambahkan.');
    }


    public function hapusBukti($id)
    {
        $foto = DB::table('kecurangan_foto')->where('id', $id)->first();
        if (!$foto) return back()->with('error', 'Foto tidak ditemukan.');

        if (Storage::disk('public')->exists($foto->path)) Storage::disk('public')->delete($foto->path);
        DB::table('kecurangan_foto')->where('id', $id)->delete();

        return back()->with('success', 'Foto bukti berhasil dihapus.');
    }


    public function edit($id)
    {
        $kecurangan = DB::table('kecurangan')->where('id', $id)->first();
        if (!$kecurangan) return redirect()->route('kecurangan.data')->with('error', 'Data tidak ditemukan!');
        if ($kecurangan->validasi == 1) return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa diedit!');

        $sales = DB::table('sales')->select('id', 'nama', 'id_distributor')->get();
        $asistenManagers = DB::table('asisten_managers')->select('id', 'nama', 'id_distributor')->get();
        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

        $fotos = DB::table('kecurangan_foto')
            ->where('id_kecurangan', $id)
            ->select('id', 'path', 'created_at')
            ->get()
            ->map(fn($foto) => tap($foto, fn($f) => $f->url = asset('storage/' . $f->path)));

        return view('kecurangan.edit', compact('kecurangan', 'sales', 'asistenManagers', 'jenisSanksi', 'fotos'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_sales' => 'required',
            'nama_sales' => 'required',
            'distributor' => 'required',
            'jenis_sanksi' => 'nullable|string|max:100',
            'deskripsi_sanksi' => 'nullable|string',
            'nilai_sanksi' => 'nullable|string',
            'toko' => 'required',
            'kunjungan' => 'required',
            'tanggal' => 'required|date_format:d/m/Y',
            'keterangan' => 'nullable',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = DB::table('kecurangan')->where('id', $id)->first();
        if (!$data) return redirect()->route('kecurangan.data')->with('error', 'Data tidak ditemukan!');
        if ($data->validasi == 1) return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa diedit!');

        if ($request->filled('deleted_photos')) {
            foreach ($request->deleted_photos as $fotoId) {
                $foto = DB::table('kecurangan_foto')->where('id', $fotoId)->first();
                if ($foto) {
                    if (Storage::disk('public')->exists($foto->path)) {
                        Storage::disk('public')->delete($foto->path);
                    }
                    DB::table('kecurangan_foto')->where('id', $fotoId)->delete();
                }
            }
        }

        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
        $bulan = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('m');
        $tahun = (int) \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y');
        $kuartal = match (true) {
            $bulan >= 1 && $bulan <= 3 => 'Q1 ' . $tahun,
            $bulan >= 4 && $bulan <= 6 => 'Q2 ' . $tahun,
            $bulan >= 7 && $bulan <= 9 => 'Q3 ' . $tahun,
            default => 'Q4 ' . $tahun,
        };

        $nilaiSanksi = null;
        if ($request->filled('nilai_sanksi')) {
            $nilaiSanksi = (int) str_replace(['Rp', '.', ',', ' '], '', $request->nilai_sanksi);
        }

        $keteranganSanksiBaru = $request->filled('deskripsi_sanksi')
            ? $request->deskripsi_sanksi
            : $data->keterangan_sanksi;

        DB::table('kecurangan')->where('id', $id)->update([
            'id_sales' => $request->id_sales,
            'nama_sales' => $request->nama_sales,
            'id_asisten_manager' => $request->id_asisten_manager,
            'nama_asisten_manager' => $request->nama_asisten_manager,
            'distributor' => $request->distributor,
            'jenis_sanksi' => $request->jenis_sanksi,
            'keterangan_sanksi' => $keteranganSanksiBaru,
            'nilai_sanksi' => $nilaiSanksi ?? $data->nilai_sanksi,
            'toko' => $request->toko,
            'kunjungan' => $request->kunjungan,
            'tanggal' => $tanggal,
            'keterangan' => $request->keterangan,
            'kuartal' => $kuartal,
            'updated_at' => now(),
        ]);

        if ($request->hasFile('bukti')) {
            $existingCount = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->count();
            $newCount = count($request->file('bukti'));
            if ($existingCount + $newCount > 5) {
                return back()->with('error', 'Total foto tidak boleh lebih dari 5.');
            }

            foreach ($request->file('bukti') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kecurangan', $filename, 'public');
                DB::table('kecurangan_foto')->insert([
                    'id_kecurangan' => $id,
                    'path' => $path,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('kecurangan.index')->with('success', 'Data berhasil diperbarui!');
    }


    public function getSales($id)
    {
        $sales = DB::table('sales')->where('id', $id)->first();
        if (!$sales) return response()->json(['error' => 'Sales tidak ditemukan'], 404);

        $distributor = DB::table('distributors')->where('id', $sales->id_distributor)->first();

        return response()->json([
            'nama_sales' => $sales->nama,
            'distributor_id' => $sales->id_distributor,
            'distributor' => $distributor?->distributor ?? '-',
        ]);
    }


    public function getAsistenManager($distributorId)
    {
        return DB::table('asisten_managers')
            ->where('id_distributor', $distributorId)
            ->select('id', 'nama')
            ->get();
    }


    public function data(Request $request)
{
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


    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('sales.nama', 'like', "%$search%")
                ->orWhere('asisten_managers.nama', 'like', "%$search%")
                ->orWhere('distributors.distributor', 'like', "%$search%")
                ->orWhere('kecurangan.toko', 'like', "%$search%");
        });
    }

    // FILTER TANGGAL
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('kecurangan.tanggal', [$request->start_date, $request->end_date]);
    }

    // FILTER JENIS SANKSI
    if ($request->filled('jenis_sanksi')) {
        $query->whereRaw("LOWER(TRIM(kecurangan.jenis_sanksi)) = LOWER(TRIM(?))", [$request->jenis_sanksi]);
    }

    // FILTER KETERANGAN SANKSI
    if ($request->filled('keterangan_sanksi')) {
        $query->whereRaw("LOWER(TRIM(kecurangan.keterangan_sanksi)) = LOWER(TRIM(?))", [$request->keterangan_sanksi]);
    }

    // SORTING
    if (in_array($sortBy, $allowedSorts)) {
        $column = match ($sortBy) {
            'nama_sales' => 'sales.nama',
            'distributor' => 'distributors.distributor',
            'nama_asisten_manager' => 'asisten_managers.nama',
            default => 'kecurangan.' . $sortBy,
        };

        $query->orderBy($column, $sortOrder);
    }

    // DROPDOWN
    $jenisSanksi = DB::table('sanksi')->distinct()->pluck('jenis');

    if ($request->boolean('all')) {
        $kecurangan = $query->get();
    } else {
        $kecurangan = $query->paginate(10)->appends($request->query());
    }

    return view('kecurangan.data', compact('kecurangan', 'jenisSanksi'));
}

    public function destroy($id)
    {
        $data = DB::table('kecurangan')->where('id', $id)->first();
        if ($data && $data->validasi == 1)
            return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa dihapus!');

        $fotos = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->get();
        foreach ($fotos as $foto)
            if (Storage::disk('public')->exists($foto->path)) Storage::disk('public')->delete($foto->path);

        DB::table('kecurangan_foto')->where('id_kecurangan', $id)->delete();
        DB::table('kecurangan')->where('id', $id)->delete();

        return redirect()->route('kecurangan.index')->with('success', 'Data berhasil dihapus!');
    }


    public function validasi($id)
    {
        DB::table('kecurangan')
            ->where('id', $id)
            ->update(['validasi' => 1, 'updated_at' => now()]);

        return redirect()->route('kecurangan.index')->with('success', 'Data berhasil divalidasi!');
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(
            new KecuranganExport(
                $request->mode_excel,
                $request->start_date,
                $request->end_date,
                $request->jenis_sanksi,
                $request->keterangan_sanksi
            ),
            'Data_Kecurangan.xlsx'
        );
    }


    public function exportPDF(Request $request)
{
    $mode = $request->mode_pdf; // all / date

    $startDate = $request->filled('start_date') ? $request->start_date : null;
    $endDate   = $request->filled('end_date') ? $request->end_date : null;

    $query = DB::table('kecurangan')
        ->where('kecurangan.validasi', 1)
        ->select(
            'kecurangan.*',
            'sales.nama as nama_sales',
            'distributors.distributor',
            'asisten_managers.nama as nama_asisten_manager'
        )
        ->leftJoin('sales', 'kecurangan.id_sales', '=', 'sales.id')
        ->leftJoin('distributors', 'sales.id_distributor', '=', 'distributors.id')
        ->leftJoin('asisten_managers', 'kecurangan.id_asisten_manager', '=', 'asisten_managers.id');

    if ($mode === 'all') {

    }

    if ($mode === 'date') {
        if ($startDate && $endDate) {
            $query->whereBetween('kecurangan.tanggal', [$startDate, $endDate]);
        }
    }

    // FILTER TAMBAHAN (tetap berfungsi di kedua mode)
    if ($request->filled('jenis_sanksi')) {
        $query->where('kecurangan.jenis_sanksi', $request->jenis_sanksi);
    }

    if ($request->filled('keterangan_sanksi')) {
        $query->where('kecurangan.keterangan_sanksi', $request->keterangan_sanksi);
    }

    // GET DATA
    $data = $query->orderBy('kecurangan.tanggal', 'desc')->get();

    // GENERATE PDF
    $pdf = PDF::loadView('pdf.kecurangan', [
        'data' => $data,
        'startDate' => $mode === 'date' ? $startDate : null,
        'endDate'   => $mode === 'date' ? $endDate : null,
    ])->setPaper('a4', 'landscape');

    return $pdf->download('Laporan_Kecurangan.pdf');
}



    public function getBukti($id)
    {
        $fotos = DB::table('kecurangan_foto')
            ->where('id_kecurangan', $id)
            ->select('id', 'path', 'created_at')
            ->get()
            ->map(fn($foto) => tap($foto, fn($f) => $f->url = asset('storage/' . $f->path)));

        return response()->json($fotos);
    }


    // API untuk dropdown dinamis (jenis -> keterangan)
    public function getKeteranganByJenis(Request $request)
{
    $data = DB::table('sanksi')
        ->where('jenis', $request->jenis_sanksi)
        ->pluck('keterangan')     // <-- ambil kolom langsung
        ->map(function ($item) {
            return ['keterangan' => $item];
        });

    return response()->json($data);
}



}