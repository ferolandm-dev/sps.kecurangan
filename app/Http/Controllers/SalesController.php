<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use PDF;

class SalesController extends Controller
{
    // Tampilkan semua data sales
    public function index(Request $request)
{
    // Mulai query dari tabel sales
    $query = DB::table('sales');

    // Jika ada input pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('nama', 'like', "%{$search}%")
              ->orWhere('id_distributor', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

    $sortBy = $request->get('sort_by', 'id');
    $sortOrder = $request->get('sort_order', 'asc');

    $allowedSorts = ['id', 'nama', 'id_distributor', 'total_kecurangan', 'status'];
    $allowedOrders = ['asc', 'desc'];

    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'id';
    }
    if (!in_array($sortOrder, $allowedOrders)) {
        $sortOrder = 'asc';
    }

    if ($sortBy === 'total_kecurangan') {
        $query->orderByRaw("total_kecurangan $sortOrder");
    } else {
        $query->orderBy($sortBy, $sortOrder);
    }

    if ($request->has('all')) {
        $sales = $query->get();
    } else {
        $sales = $query->paginate(10)->appends($request->query());
    }

    return view('sales.index', compact('sales'));
}


    // Form tambah sales baru
    public function create()
    {
        $distributors = DB::table('distributors')->get(); // untuk dropdown pilih distributor
        return view('sales.create', compact('distributors'));
    }

    // Simpan data sales baru
    public function store(Request $request)
{
    $request->validate([
        'id' => 'required|max:6',
        'nama' => 'required',
        'id_distributor' => 'required',
    ]);

    // Ambil 3 huruf pertama dari ID distributor
    $prefix = substr($request->id_distributor, 0, 3);

    // Gabungkan prefix + ID sales input
    $finalId = strtoupper($prefix . $request->id);

    // 🔍 Cek apakah finalId sudah ada di database
    $exists = DB::table('sales')->where('id', $finalId)->exists();

    if ($exists) {
        // Kirim pesan error ke user
        return redirect()
            ->back()
            ->withInput()
            ->with('error', "ID Sales <strong>{$finalId}</strong> sudah terdaftar. Silakan gunakan ID lain.");
    }

    // Simpan ke database
    DB::table('sales')->insert([
        'id' => $finalId,
        'nama' => $request->nama,
        'id_distributor' => $request->id_distributor,
        'status' => 'Aktif',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('sales.index')->with('success', 'Data sales berhasil ditambahkan!');
}

    // Form edit data sales
    public function edit($id)
    {
        $sales = DB::table('sales')->where('id', $id)->first();

        if (!$sales) {
            return redirect()->route('sales.index')->with('error', 'Data tidak ditemukan.');
        }

        return view('sales.edit', compact('sales'));
    }

    // Update data sales
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
        ]);

        DB::table('sales')->where('id', $id)->update([
            'nama' => $request->nama,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('sales.index')->with('success', 'Data sales berhasil diperbarui!');
    }

    // Hapus data sales
    public function destroy($id)
    {
        DB::table('sales')->where('id', $id)->delete();
        return redirect()->route('sales.index')->with('success', 'Data sales berhasil dihapus!');
    }

    public function data(Request $request)
{
    $query = DB::table('sales')
        ->select(
            'sales.*',
            DB::raw('(SELECT COUNT(*) FROM kecurangan WHERE kecurangan.id_sales = sales.id) as total_kecurangan')
        );

    // 🔍 Jika ada input pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('sales.id', 'like', "%{$search}%")
              ->orWhere('sales.nama', 'like', "%{$search}%")
              ->orWhere('sales.id_distributor', 'like', "%{$search}%")
              ->orWhere('sales.status', 'like', "%{$search}%");
        });
    }

    $sortBy = $request->get('sort_by', 'id');
    $sortOrder = $request->get('sort_order', 'asc');

    $allowedSorts = ['id', 'nama', 'id_distributor', 'total_kecurangan', 'status'];
    $allowedOrders = ['asc', 'desc'];

    if (!in_array($sortBy, $allowedSorts)) {
        $sortBy = 'id';
    }
    if (!in_array($sortOrder, $allowedOrders)) {
        $sortOrder = 'asc';
    }

    // Karena total_kecurangan adalah alias dari subquery, sorting pakai orderByRaw
    if ($sortBy === 'total_kecurangan') {
        $query->orderByRaw("total_kecurangan $sortOrder");
    } else {
        $query->orderBy($sortBy, $sortOrder);
    }

    if ($request->has('all')) {
        $sales = $query->get();
    } else {
        $sales = $query->paginate(10)->appends($request->query());
    }

    return view('sales.data', compact('sales', 'sortBy', 'sortOrder'));
}

public function exportExcel()
{
    return Excel::download(new SalesExport, 'data_sales.xlsx');
}

public function exportPdf()
{
    $data = DB::table('sales')
        ->select('id', 'nama', 'id_distributor', 'status', 'created_at')
        ->get();

    $pdf = Pdf::loadView('pdf.sales', ['sales' => $data])
        ->setPaper('a4', 'portrait');

    return $pdf->download('data_sales.pdf');

}
}