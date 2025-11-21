<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KecuranganExport;

class KecuranganController extends Controller
{
    /* ==========================================================
     | INDEX
     ========================================================== */
    public function index(Request $request)
    {
        $sales = DB::table('salesman')
            ->whereIn('TYPE_SALESMAN', [1, 7])
            ->select('ID_SALESMAN', 'NAMA_SALESMAN')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('JENIS');
        $keteranganSanksi = DB::table('sanksi')->select('ID','JENIS','KETERANGAN','NILAI')->get();


        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->leftJoin('salesman as ass', 'kecurangan.ID_ASS', '=', 'ass.ID_SALESMAN')
            ->select(
                'kecurangan.*',
                'salesman.NAMA_SALESMAN as nama_sales',
                'ass.NAMA_SALESMAN as nama_ass',
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

    /* ==========================================================
     | DATA PAGE
     ========================================================== */
    public function data(Request $request)
    {
        $sales = DB::table('salesman')
            ->whereIn('TYPE_SALESMAN', [1, 7])
            ->select('ID_SALESMAN as id_sales', 'NAMA_SALESMAN as nama_sales')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('JENIS');
        $keteranganSanksi = DB::table('sanksi')->select('ID','JENIS','KETERANGAN','NILAI')->get();


        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->leftJoin('salesman as ass', 'kecurangan.ID_ASS', '=', 'ass.ID_SALESMAN')
            ->select(
                'kecurangan.*',
                'salesman.NAMA_SALESMAN as nama_sales',
                'ass.NAMA_SALESMAN as nama_ass',
                'salesman.ID_DISTRIBUTOR as distributor'
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
                ->orWhere('kecurangan.TOKO', 'like', "%{$s}%")
                ->orWhere('kecurangan.KETERANGAN_SANKSI', 'like', "%{$s}%")
                ->orWhere('kecurangan.JENIS_SANKSI', 'like', "%{$s}%");
            });
        }

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

        $allowedSorts = [
            'ID_SALES', 'nama_sales', 'DISTRIBUTOR', 'nama_ass',
            'JENIS_SANKSI', 'KETERANGAN_SANKSI', 'NILAI_SANKSI',
            'TOKO', 'KUNJUNGAN', 'TANGGAL', 'KUARTAL'
        ];

        $sortBy = $request->get('sort_by', 'TANGGAL');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, $allowedSorts)) {
            $column = $sortBy === 'nama_sales'
                ? 'salesman.NAMA_SALESMAN'
                : ($sortBy === 'nama_ass' ? 'ass.NAMA_SALESMAN' : "kecurangan.$sortBy");

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
            ->whereIn('TYPE_SALESMAN', [1, 7])
            ->select('ID_SALESMAN','NAMA_SALESMAN')
            ->get();

        $jenisSanksi = DB::table('sanksi')->distinct()->pluck('JENIS');

        $keteranganSanksi = DB::table('sanksi')->select('ID','JENIS','KETERANGAN','NILAI')->get();

        return view('kecurangan.create', compact('sales', 'jenisSanksi', 'keteranganSanksi'));

    }

    /* ==========================================================
     | STORE
     ========================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'id_sales' => 'required',
            'id_ass' => 'required',
            'distributor' => 'required',
            'toko' => 'nullable|string',
            'kunjungan' => 'nullable|string',
            'tanggal' => 'required|date_format:d/m/Y',
            'jenis_sanksi' => 'required|string|max:100',
            'deskripsi_sanksi' => 'required|string',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('bukti') && count($request->file('bukti')) > 5) {
            return back()->with('error', 'Maksimal total 5 foto.');
        }

        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

        $bulan = (int)date('m', strtotime($tanggal));
        $tahun = (int)date('Y', strtotime($tanggal));

        $kuartal = $bulan <= 3 ? "Q1 $tahun" :
                   ($bulan <= 6 ? "Q2 $tahun" :
                   ($bulan <= 9 ? "Q3 $tahun" : "Q4 $tahun"));

        $nilaiSanksi = $request->nilai_sanksi 
            ? str_replace(['Rp', '.', ' ', ','], '', $request->nilai_sanksi)
            : 0;



        $id = DB::table('kecurangan')->insertGetId([
            'ID_SALES'          => $request->id_sales,
            'ID_ASS'            => $request->id_ass,
            'DISTRIBUTOR'       => $request->distributor,
            'TOKO'              => $request->toko ?: '-',
            'KUNJUNGAN'         => $request->kunjungan ?: '-',
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
    $kecurangan = DB::table('kecurangan')
        ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
        ->leftJoin('salesman as ass', 'kecurangan.ID_ASS', '=', 'ass.ID_SALESMAN')
        ->select(
            'kecurangan.*',
            'salesman.NAMA_SALESMAN as NAMA_SALES',
            'ass.NAMA_SALESMAN as NAMA_ASS'
        )
        ->where('kecurangan.ID', $id)
        ->first();

    if (!$kecurangan) {
        return redirect()->route('kecurangan.index')->with('error', 'Data tidak ditemukan.');
    }

    // Sales TYPE 1 & 7
    $sales = DB::table('salesman')
        ->whereIn('TYPE_SALESMAN', [1, 7])
        ->select('ID_SALESMAN', 'NAMA_SALESMAN')
        ->get();

    // ASS TYPE 7
    $assList = DB::table('salesman')
        ->where('TYPE_SALESMAN', 7)
        ->select('ID_SALESMAN', 'NAMA_SALESMAN')
        ->get();

    // Jenis Sanksi
    $jenisSanksi = DB::table('sanksi')->distinct()->pluck('JENIS');

    // List lengkap: Jenis, Deskripsi, Nilai (untuk non-AJAX JS)
    $keteranganSanksi = DB::table('sanksi')
        ->select('ID', 'JENIS', 'KETERANGAN', 'NILAI')
        ->get();

    // Foto
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
        'assList',
        'jenisSanksi',
        'keteranganSanksi',
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
            'id_ass' => 'required',
            'distributor' => 'required',
            'toko' => 'nullable|string',
            'kunjungan' => 'nullable|string',
            'tanggal' => 'required|date_format:d/m/Y',
            'jenis_sanksi' => 'required|string|max:100',
            'deskripsi_sanksi' => 'required|string',
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $tanggal = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');

        $bulan = (int)date('m', strtotime($tanggal));
        $tahun = (int)date('Y', strtotime($tanggal));

        $kuartal = $bulan <= 3 ? "Q1 $tahun" :
                   ($bulan <= 6 ? "Q2 $tahun" :
                   ($bulan <= 9 ? "Q3 $tahun" : "Q4 $tahun"));

        $nilaiSanksi = $request->nilai_sanksi 
            ? str_replace(['Rp', '.', ' ', ','], '', $request->nilai_sanksi)
            : 0;

        DB::table('kecurangan')
            ->where('ID', $id)
            ->update([
                'ID_SALES'          => $request->id_sales,
                'ID_ASS'            => $request->id_ass,
                'DISTRIBUTOR'       => $request->distributor,
                'TOKO'              => $request->toko ?: '-',
                'KUNJUNGAN'         => $request->kunjungan ?: '-',
                'TANGGAL'           => $tanggal,
                'KETERANGAN'        => $request->keterangan,
                'KUARTAL'           => $kuartal,
                'JENIS_SANKSI'      => $request->jenis_sanksi,
                'KETERANGAN_SANKSI' => $request->deskripsi_sanksi,
                'NILAI_SANKSI'      => $nilaiSanksi,
                'UPDATED_AT'        => now(),
            ]);

        if ($request->filled('deleted_photos')) {
            foreach ($request->deleted_photos as $fotoId) {
                $foto = DB::table('kecurangan_foto')->where('ID', $fotoId)->first();
                if ($foto) {
                    Storage::disk('public')->delete($foto->PATH);
                }
                DB::table('kecurangan_foto')->where('ID', $fotoId)->delete();
            }
        }

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

    /* ==========================================================
     | DESTROY
     ========================================================== */
    public function destroy($id)
    {
        $row = DB::table('kecurangan')->where('ID', $id)->first();

        if (!$row) {
            return redirect()->route('kecurangan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        $fotos = DB::table('kecurangan_foto')
            ->where('ID_KECURANGAN', $id)
            ->get();

        foreach ($fotos as $f) {
            Storage::disk('public')->delete($f->PATH);
        }

        DB::table('kecurangan_foto')->where('ID_KECURANGAN', $id)->delete();

        DB::table('kecurangan')->where('ID', $id)->delete();

        return redirect()->route('kecurangan.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    /* ==========================================================
     | VALIDASI
     ========================================================== */
    public function validasi($id)
    {
        $row = DB::table('kecurangan')->where('ID', $id)->first();

        if (!$row) {
            return redirect()->route('kecurangan.index')
                ->with('error', 'Data tidak ditemukan.');
        }

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
        $sales = DB::table('salesman')
            ->where('ID_SALESMAN', $id)
            ->whereIn('TYPE_SALESMAN', [1, 7])
            ->first();

        if (!$sales) {
            return response()->json(['error' => 'Sales tidak ditemukan'], 404);
        }

        $distributor = DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $sales->ID_DISTRIBUTOR)
            ->value('NAMA_DISTRIBUTOR');

        return response()->json([
            'nama_sales'     => $sales->NAMA_SALESMAN,
            'distributor'    => $distributor ?? '-',
            'type_salesman'  => $sales->TYPE_SALESMAN,  // 🔥 WAJIB
            'id_salesman'    => $sales->ID_SALESMAN,    // opsional tapi berguna
        ]);
    }


    public function getCustomer($idSales)
    {
        $customer = DB::table('customer')
            ->where('ID_SALESMAN', $idSales)       
            ->where('STATUS', 1)                 
            ->select('ID_CUST', 'NAMA_CUST')
            ->orderBy('NAMA_CUST', 'asc')
            ->get();

        return response()->json($customer);
    }

    public function getKeteranganByJenis($jenis)
    {
        $list = DB::table('sanksi')
            ->where('JENIS', $jenis)
            ->select('KETERANGAN')
            ->get();

        return response()->json($list);
    }

    public function getNilai($jenis, $deskripsi)
    {
        $deskripsi = urldecode($deskripsi);

        $row = DB::table('sanksi')
            ->where('JENIS', $jenis)
            ->where('KETERANGAN', $deskripsi)
            ->select('NILAI')
            ->first();

        if (!$row) {
            return response()->json(['NILAI' => 0]);
        }

        return response()->json(['NILAI' => $row->NILAI]);
    }

    /* ==========================================================
     | AJAX: GET ASS LIST
     ========================================================== */
    public function getAss($idSales)
    {
        $sales = DB::table('salesman')
            ->where('ID_SALESMAN', $idSales)
            ->first();

        if (!$sales) {
            return response()->json([], 200);
        }

        $ass = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->where('ID_DISTRIBUTOR', $sales->ID_DISTRIBUTOR)
            ->select('ID_SALESMAN', 'NAMA_SALESMAN')
            ->get();

        return response()->json($ass);
    }

    /* ==========================================================
     | GET FOTO
     ========================================================== */
    public function getBukti($id)
    {
        $foto = DB::table('kecurangan_foto')
            ->where('ID_KECURANGAN', $id)
            ->get()
            ->map(function($f) {
                return [
                    'id'  => $f->ID,
                    'url' => asset('storage/' . $f->PATH)
                ];
            });

        return response()->json($foto);
    }

    /* ==========================================================
     | EXPORT PDF
     ========================================================== */
    public function exportPDF(Request $request)
{
    $query = DB::table('kecurangan')
        ->where('VALIDASI', 1)
        ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
        ->leftJoin('salesman as ass', 'kecurangan.ID_ASS', '=', 'ass.ID_SALESMAN')
        ->select(
            'kecurangan.*',
            'salesman.NAMA_SALESMAN as nama_sales',
            'ass.NAMA_SALESMAN as nama_ass',
            'salesman.ID_DISTRIBUTOR as distributor'
        );

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
              ->orWhere('kecurangan.TOKO', 'like', "%{$s}%")
              ->orWhere('kecurangan.KETERANGAN_SANKSI', 'like', "%{$s}%")
              ->orWhere('kecurangan.JENIS_SANKSI', 'like', "%{$s}%");
        });
    }

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

    $data = $query->orderBy('kecurangan.TANGGAL', 'desc')->get();

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


    /* ==========================================================
     | EXPORT EXCEL
     ========================================================== */
    public function exportExcel(Request $request)
    {
        return Excel::download(new KecuranganExport($request), 'laporan_kecurangan.xlsx');    
    }
}