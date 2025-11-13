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
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 🔥 ubah jadi 5MB
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
            'validasi' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ Simpan ke storage/app/public/kecurangan
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

        return redirect()->route('kecurangan.data')->with('success', 'Data kecurangan berhasil ditambahkan beserta bukti foto!');
    }

    public function uploadBukti(Request $request, $id)
    {
        $data = DB::table('kecurangan')->where('id', $id)->first();
        if (!$data) {
            return back()->with('error', 'Data kecurangan tidak ditemukan.');
        }

        if ($data->validasi == 1) {
            return back()->with('error', 'Data sudah divalidasi dan tidak bisa diubah.');
        }

        $request->validate([
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 🔥 ubah jadi 5MB
        ]);

        $existingCount = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->count();
        $newCount = count($request->file('bukti') ?? []);
        if ($existingCount + $newCount > 5) {
            return back()->with('error', 'Total foto tidak boleh lebih dari 5.');
        }

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
        if (!$foto) {
            return back()->with('error', 'Foto tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($foto->path)) {
            Storage::disk('public')->delete($foto->path);
        }

        DB::table('kecurangan_foto')->where('id', $id)->delete();

        return back()->with('success', 'Foto bukti berhasil dihapus.');
    }

    public function edit($id)
    {
        $kecurangan = DB::table('kecurangan')->where('id', $id)->first();

        if (!$kecurangan) {
            return redirect()->route('kecurangan.data')->with('error', 'Data tidak ditemukan!');
        }

        if ($kecurangan->validasi == 1) {
            return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa diedit!');
        }

        $sales = DB::table('sales')->select('id', 'nama', 'id_distributor')->get();
        $asistenManagers = DB::table('asisten_managers')->select('id', 'nama', 'id_distributor')->get();

        $fotos = DB::table('kecurangan_foto')
            ->where('id_kecurangan', $id)
            ->select('id', 'path', 'created_at')
            ->get()
            ->map(function ($foto) {
                $foto->url = asset('storage/' . $foto->path);
                return $foto;
            });

        return view('kecurangan.edit', compact('kecurangan', 'sales', 'asistenManagers', 'fotos'));
    }

    public function update(Request $request, $id)
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
            'bukti.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = DB::table('kecurangan')->where('id', $id)->first();
        if ($data && $data->validasi == 1) {
            return redirect()->route('kecurangan.data')->with('error', 'Data sudah divalidasi dan tidak bisa diedit!');
        }

        // ✅ HAPUS FOTO YANG DITANDAI UNTUK DIHAPUS
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

        if ($bulan >= 1 && $bulan <= 3) $kuartal = 'Q1 ' . $tahun;
        elseif ($bulan >= 4 && $bulan <= 6) $kuartal = 'Q2 ' . $tahun;
        elseif ($bulan >= 7 && $bulan <= 9) $kuartal = 'Q3 ' . $tahun;
        else $kuartal = 'Q4 ' . $tahun;

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
            'kuartal' => $kuartal,
            'updated_at' => now(),
        ]);

        // ✅ UPLOAD FOTO BARU JIKA ADA
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

        return redirect()->route('kecurangan.data')->with('success', 'Data dan foto berhasil diperbarui!');
    }

    // ===================== API =====================

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
        return DB::table('asisten_managers')
            ->where('id_distributor', $distributorId)
            ->select('id', 'nama')
            ->get();
    }

    // ===================== DATA & EXPORT =====================

    public function data(Request $request)
    {
        $allowedSorts = [
            'id', 'id_sales', 'nama_sales', 'id_asisten_manager', 'nama_asisten_manager',
            'distributor', 'toko', 'kunjungan', 'tanggal', 'keterangan', 'kuartal'
        ];

        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');

        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'tanggal';
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

        $fotos = DB::table('kecurangan_foto')->where('id_kecurangan', $id)->get();
        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
        }

        DB::table('kecurangan_foto')->where('id_kecurangan', $id)->delete();
        DB::table('kecurangan')->where('id', $id)->delete();

        return redirect()->route('kecurangan.data')->with('success', 'Data dan foto bukti berhasil dihapus!');
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
            ->select('id_sales', 'nama_sales', 'id_asisten_manager', 'nama_asisten_manager', 'distributor', 'toko', 'kunjungan', 'tanggal', 'keterangan', 'kuartal');

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

    // ===================== AJAX GET BUKTI FOTO =====================
public function getBukti($id)
{
    $fotos = DB::table('kecurangan_foto')
        ->where('id_kecurangan', $id)
        ->select('id', 'path', 'created_at')
        ->get()
        ->map(function ($foto) {
            $foto->url = asset('storage/' . $foto->path);
            return $foto;
        });

    return response()->json($fotos);
}

}