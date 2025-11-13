<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SanksiController extends Controller
{
    /**
     * Tampilkan semua data sanksi.
     */
    public function index(Request $request)
    {
        $query = DB::table('sanksi');

        // 🔍 Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('nilai', 'like', "%{$search}%");
            });
        }

        // 🔁 Pagination atau tampil semua
        if ($request->has('all')) {
            $sanksi = $query->orderBy('id', 'asc')->get();
        } else {
            $sanksi = $query->orderBy('id', 'asc')->paginate(10)->appends($request->query());
        }

        return view('sanksi.index', compact('sanksi'));
    }

    /**
     * Form tambah sanksi baru.
     */
    public function create()
    {
        return view('sanksi.create');
    }

    /**
     * Simpan data sanksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric|min:0',
        ]);

        DB::table('sanksi')->insert([
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'nilai' => $request->nilai,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil ditambahkan!');
    }

    /**
     * Form edit data sanksi.
     */
    public function edit($id)
    {
        $sanksi = DB::table('sanksi')->where('id', $id)->first();

        if (!$sanksi) {
            return redirect()->route('sanksi.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('sanksi.edit', compact('sanksi'));
    }

    /**
     * Update data sanksi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nilai' => 'required|numeric|min:0',
        ]);

        DB::table('sanksi')->where('id', $id)->update([
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'nilai' => $request->nilai,
            'updated_at' => now(),
        ]);

        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil diperbarui!');
    }

    /**
     * Hapus data sanksi.
     */
    public function destroy($id)
    {
        DB::table('sanksi')->where('id', $id)->delete();
        return redirect()->route('sanksi.index')->with('success', 'Data sanksi berhasil dihapus!');
    }
}