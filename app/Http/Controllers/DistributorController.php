<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // ← ini penting
use App\Exports\DistributorsExport;  // pastikan kamu sudah buat file export ini
use PDF; // kalau nanti export PDF juga

class DistributorController extends Controller
{
    public function index(Request $request)
{
    // Mulai query
    $query = DB::table('distributors');

    // Jika ada input pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('distributor', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

    // Jika user memilih "Tampilkan Semua"
    if ($request->has('all')) {
        $distributors = $query->get();
    } else {
        $distributors = $query->paginate(10)->appends($request->query());
    }

    return view('distributors.index', compact('distributors'));
}


    public function create()
    {
        return view('distributors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:6',
            'distributor' => 'required|string|max:100',
        ]);

        // Cek apakah ID sudah ada
        $exists = DB::table('distributors')->where('id', $request->id)->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ID Distributor <strong>' . e($request->id) . '</strong> sudah terdaftar!');
        }

        DB::table('distributors')->insert([
            'id' => $request->id,
            'distributor' => $request->distributor,
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('distributors.index')
            ->with('success', 'Data distributor berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $distributor = DB::table('distributors')->where('id', $id)->first();
        return view('distributors.edit', compact('distributor'));
    }
    
    public function destroy($id)
    {
        DB::table('distributors')->where('id', $id)->delete();

        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'distributor' => 'required',
            'status' => 'required',
        ]);

        DB::table('distributors')
            ->where('id', $id)
            ->update([
                'distributor' => $request->distributor,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil diperbarui!');
    }

    public function data(Request $request)
{
    $sort_by = $request->get('sort_by', 'distributor');
    $sort_order = $request->get('sort_order', 'asc');

    $allowed_columns = ['id', 'distributor', 'status', 'jumlah_sales'];
    $allowed_order = ['asc', 'desc'];

    if (!in_array($sort_by, $allowed_columns)) $sort_by = 'distributor';
    if (!in_array($sort_order, $allowed_order)) $sort_order = 'asc';

    // Base query
    $query = DB::table('distributors')
        ->leftJoin('sales', function ($join) {
            $join->on('distributors.id', '=', 'sales.id_distributor')
                 ->where('sales.status', '=', 'Aktif');
        })
        ->select(
            'distributors.id',
            'distributors.distributor',
            'distributors.status',
            DB::raw('COUNT(sales.id) as jumlah_sales')
        )
        ->groupBy('distributors.id', 'distributors.distributor', 'distributors.status');

    // Pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('distributors.id', 'like', "%{$search}%")
              ->orWhere('distributors.distributor', 'like', "%{$search}%")
              ->orWhere('distributors.status', 'like', "%{$search}%");
        });
    }

    // Sorting
    $query->orderBy($sort_by, $sort_order);

    // Paginate / tampilkan semua
    if ($request->has('all')) {
        $distributors = $query->get();
    } else {
        $distributors = $query->paginate(10)->appends($request->query());
    }

    return view('distributors.data', compact('distributors', 'sort_by', 'sort_order'));
}

public function exportExcel()
    {
        return Excel::download(new DistributorsExport, 'data_distributor.xlsx');
    }

public function exportPdf()
    {
        $data = DB::table('distributors')
            ->select('id', 'distributor', 'status', 'created_at')
            ->get();

        $pdf = Pdf::loadView('pdf.distributors', ['distributors' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_distributor.pdf');
    }

}