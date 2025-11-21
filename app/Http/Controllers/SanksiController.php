<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SanksiController extends Controller
{
    // ============================================================
    //                     TAMPILKAN LIST SANKSI
    // ============================================================
    public function index(Request $request)
    {
        $query = DB::table('sanksi');

        // ------------------------------------------------------------
        // 🔍 Pencarian
        // ------------------------------------------------------------
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('JENIS', 'like', "%{$search}%")
                  ->orWhere('KETERANGAN', 'like', "%{$search}%")
                  ->orWhere('NILAI', 'like', "%{$search}%");
            });
        }

        // ------------------------------------------------------------
        // 🔁 Sorting
        // ------------------------------------------------------------
        $allowedSorts = ['ID', 'JENIS', 'KETERANGAN', 'NILAI'];
        $sortBy = strtoupper($request->get('sort_by', 'ID'));
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'ID';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // ------------------------------------------------------------
        // 🔁 Pagination atau Tampilkan Semua
        // ------------------------------------------------------------
        if ($request->has('all')) {
            $sanksi = $query->orderBy($sortBy, $sortOrder)->get();
        } else {
            $sanksi = $query->orderBy($sortBy, $sortOrder)
                            ->paginate(10)
                            ->appends($request->query());
        }

        return view('sanksi.index', compact('sanksi'));
    }


    // ============================================================
    //                      FORM TAMBAH SANKSI
    // ============================================================
    public function create()
    {
        return view('sanksi.create');
    }


    // ============================================================
    //                      SIMPAN DATA SANKSI
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'jenis'      => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nilai'      => 'required|numeric|min:0',
        ]);

        DB::table('sanksi')->insert([
            'JENIS'        => $request->jenis,
            'KETERANGAN'   => $request->keterangan,
            'NILAI'        => $request->nilai,
            'CREATED_AT'   => now(),
            'UPDATED_AT'   => now(),
        ]);

        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil ditambahkan!');
    }


    // ============================================================
    //                      FORM EDIT SANKSI
    // ============================================================
    public function edit($id)
    {
        $sanksi = DB::table('sanksi')->where('ID', $id)->first();

        if (!$sanksi) {
            return redirect()->route('sanksi.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('sanksi.edit', compact('sanksi'));
    }


    // ============================================================
    //                      UPDATE DATA SANKSI
    // ============================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis'      => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nilai'      => 'required|numeric|min:0',
        ]);

        DB::table('sanksi')->where('ID', $id)->update([
            'JENIS'        => $request->jenis,
            'KETERANGAN'   => $request->keterangan,
            'NILAI'        => $request->nilai,
            'UPDATED_AT'   => now(),
        ]);

        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil diperbarui!');
    }


    // ============================================================
    //                        HAPUS DATA SANKSI
    // ============================================================
    public function destroy($id)
    {
        DB::table('sanksi')->where('ID', $id)->delete();

        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil dihapus!');
    }


    // ============================================================
    //              AMBIL DESKRIPSI BERDASARKAN JENIS
    // ============================================================
    public function getDeskripsiByJenis($jenis)
    {
        $data = DB::table('sanksi')
            ->where('JENIS', $jenis)
            ->select('KETERANGAN')
            ->distinct()
            ->get();

        return response()->json($data);
    }


    // ============================================================
    //      AMBIL NILAI BERDASARKAN JENIS & KETERANGAN
    // ============================================================
    public function getNilaiByDeskripsi($jenis, $deskripsi)
    {
        $decodedDeskripsi = urldecode($deskripsi);

        $sanksi = DB::table('sanksi')
            ->where('JENIS', $jenis)
            ->where('KETERANGAN', $decodedDeskripsi)
            ->select('NILAI')
            ->first();

        return response()->json([
            'nilai' => $sanksi ? (float) $sanksi->NILAI : 0
        ]);
    }
}
