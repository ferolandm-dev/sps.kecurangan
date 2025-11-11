<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenManagerExport;
use PDF;

class AsistenManagerController extends Controller
{
    // 🔹 Tampilkan semua data Asisten Manager
    public function index(Request $request)
    {
        $query = DB::table('asisten_managers');

        // Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('id_distributor', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Jika user klik "Tampilkan Semua"
        if ($request->has('all')) {
            $asistenManagers = $query->get();
        } else {
            $asistenManagers = $query->paginate(10)->appends($request->query());
        }

        return view('asisten_manager.index', compact('asistenManagers'));
    }

    // 🔹 Form tambah data
    public function create()
    {
        $distributors = DB::table('distributors')->get();
        return view('asisten_manager.create', compact('distributors'));
    }

    // 🔹 Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|max:6',
            'nama' => 'required',
            'id_distributor' => 'required',
        ]);

        // Ambil prefix dari ID distributor
        $prefix = substr($request->id_distributor, 0, 3);

        // Gabungkan prefix + input ID
        $finalId = strtoupper($prefix . $request->id);

        // Cek apakah ID sudah ada
        $exists = DB::table('asisten_managers')->where('id', $finalId)->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "ID Asisten Manager <strong>{$finalId}</strong> sudah terdaftar. Silakan gunakan ID lain.");
        }

        DB::table('asisten_managers')->insert([
            'id' => $finalId,
            'nama' => $request->nama,
            'id_distributor' => $request->id_distributor,
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('asisten_manager.index')->with('success', 'Data Asisten Manager berhasil ditambahkan!');
    }

    // 🔹 Form edit data
    public function edit($id)
    {
        $asistenManager = DB::table('asisten_managers')->where('id', $id)->first();

        if (!$asistenManager) {
            return redirect()->route('asisten_manager.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('asisten_manager.edit', compact('asistenManager'));
    }

    // 🔹 Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
        ]);

        DB::table('asisten_managers')->where('id', $id)->update([
            'nama' => $request->nama,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('asisten_manager.index')->with('success', 'Data Asisten Manager berhasil diperbarui!');
    }

    // 🔹 Hapus data
    public function destroy($id)
    {
        DB::table('asisten_managers')->where('id', $id)->delete();
        return redirect()->route('asisten_manager.index')->with('success', 'Data Asisten Manager berhasil dihapus!');
    }

    // 🔹 Data + total kecurangan (jika nanti dibutuhkan)
    public function data(Request $request)
    {
        $query = DB::table('asisten_managers')
            ->select(
                'asisten_managers.*',
                DB::raw('(SELECT COUNT(*) FROM kecurangan WHERE kecurangan.id_asisten_manager = asisten_managers.id) as total_kecurangan')
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asisten_managers.id', 'like', "%{$search}%")
                    ->orWhere('asisten_managers.nama', 'like', "%{$search}%")
                    ->orWhere('asisten_managers.id_distributor', 'like', "%{$search}%")
                    ->orWhere('asisten_managers.status', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['id', 'nama', 'id_distributor', 'total_kecurangan', 'status'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'id';
        if (!in_array($sortOrder, $allowedOrders)) $sortOrder = 'asc';

        if ($sortBy === 'total_kecurangan') {
            $query->orderByRaw("total_kecurangan $sortOrder");
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        if ($request->has('all')) {
            $asistenManagers = $query->get();
        } else {
            $asistenManagers = $query->paginate(10)->appends($request->query());
        }

        return view('asisten_manager.data', compact('asistenManagers', 'sortBy', 'sortOrder'));
    }

    // 🔹 Export Excel
    public function exportExcel()
    {
        return Excel::download(new AsistenManagerExport, 'data_asisten_manager.xlsx');
    }

    // 🔹 Export PDF
    public function exportPdf()
    {
        $data = DB::table('asisten_managers')
            ->select('id', 'nama', 'id_distributor', 'status', 'created_at')
            ->get();

        $pdf = Pdf::loadView('pdf.asisten_managers', ['asistenManagers' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_asisten_manager.pdf');
    }
}