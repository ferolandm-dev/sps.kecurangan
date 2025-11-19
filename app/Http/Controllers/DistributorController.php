<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DistributorExport;
use PDF;

class DistributorController extends Controller
{
    // =====================================================
    // 📌 MASTER DISTRIBUTOR LIST
    // =====================================================
    public function index(Request $request)
    {
        $query = DB::table('distributor');

        // Searching
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ID_DISTRIBUTOR', 'like', "%{$search}%")
                  ->orWhere('NAMA_DISTRIBUTOR', 'like', "%{$search}%")
                  ->orWhere('ID_REGION', 'like', "%{$search}%")
                  ->orWhere('ID_KOTA', 'like', "%{$search}%")
                  ->orWhere('ID_SPV', 'like', "%{$search}%")
                  ->orWhere('ID_LOGISTIC', 'like', "%{$search}%");
            });
        }

        // Sorting
        $allowedColumns = [
            'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR', 'ID_REGION', 'ID_KOTA',
            'ID_SPV', 'ID_LOGISTIC', 'ID_PROV',
            'LATITUDE_DIST', 'LONGITUDE_DIST', 'ACCURACY_DIST'
        ];

        $sortBy = $request->get('sort_by', 'NAMA_DISTRIBUTOR');
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'NAMA_DISTRIBUTOR';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        if ($request->has('all')) {
            $distributor = $query->get();
        } else {
            $distributor = $query->paginate(10)->appends($request->query());
        }

        return view('distributor.index', compact('distributor'));
    }

    // =====================================================
    // 📌 MASTER CREATE
    // =====================================================
    public function create()
    {
        return view('distributor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ID_DISTRIBUTOR' => 'required|max:5',
            'NAMA_DISTRIBUTOR' => 'required|max:50',
        ]);

        // Check duplicate ID
        if (DB::table('distributor')->where('ID_DISTRIBUTOR', $request->ID_DISTRIBUTOR)->exists()) {
            return back()->withInput()->with('error', 'ID Distributor sudah ada!');
        }

        DB::table('distributor')->insert([
            'ID_DISTRIBUTOR' => $request->ID_DISTRIBUTOR,
            'ID_KOTA' => $request->ID_KOTA,
            'ID_REGION' => $request->ID_REGION,
            'ID_SPV' => $request->ID_SPV,
            'ID_LOGISTIC' => $request->ID_LOGISTIC,
            'ID_PROV' => $request->ID_PROV,
            'NAMA_DISTRIBUTOR' => $request->NAMA_DISTRIBUTOR,
            'LATITUDE_DIST' => $request->LATITUDE_DIST,
            'LONGITUDE_DIST' => $request->LONGITUDE_DIST,
            'ACCURACY_DIST' => $request->ACCURACY_DIST,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil ditambahkan!');
    }

    // =====================================================
    // 📌 MASTER EDIT
    // =====================================================
    public function edit($id)
    {
        $distributor = DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $id)
            ->first();

        return view('distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NAMA_DISTRIBUTOR' => 'required|max:50',
        ]);

        DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $id)
            ->update([
                'ID_KOTA' => $request->ID_KOTA,
                'ID_REGION' => $request->ID_REGION,
                'ID_SPV' => $request->ID_SPV,
                'ID_LOGISTIC' => $request->ID_LOGISTIC,
                'ID_PROV' => $request->ID_PROV,
                'NAMA_DISTRIBUTOR' => $request->NAMA_DISTRIBUTOR,
                'LATITUDE_DIST' => $request->LATITUDE_DIST,
                'LONGITUDE_DIST' => $request->LONGITUDE_DIST,
                'ACCURACY_DIST' => $request->ACCURACY_DIST,
                'updated_at' => now(),
            ]);

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil diperbarui!');
    }

    // =====================================================
    // 📌 MASTER DELETE
    // =====================================================
    public function destroy($id)
    {
        DB::table('distributor')
            ->where('ID_DISTRIBUTOR', $id)
            ->delete();

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil dihapus!');
    }

    // =====================================================
    // 📊 DATA DISTRIBUTOR PAGE (yang error tadi)
    // =====================================================
    public function data(Request $request)
    {
        $allowedColumns = [
            'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR',
            'ID_KOTA', 'ID_REGION', 'ID_SPV',
            'ID_LOGISTIC', 'ID_PROV',
            'LATITUDE_DIST', 'LONGITUDE_DIST', 'ACCURACY_DIST'
        ];

        $sortBy = $request->get('sort_by', 'NAMA_DISTRIBUTOR');
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortBy, $allowedColumns)) $sortBy = 'NAMA_DISTRIBUTOR';
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'asc';

        $query = DB::table('distributor');

        // Searching
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ID_DISTRIBUTOR', 'like', "%$search%")
                  ->orWhere('NAMA_DISTRIBUTOR', 'like', "%$search%")
                  ->orWhere('ID_KOTA', 'like', "%$search%")
                  ->orWhere('ID_REGION', 'like', "%$search%")
                  ->orWhere('ID_SPV', 'like', "%$search%")
                  ->orWhere('ID_LOGISTIC', 'like', "%$search%");
            });
        }

        // Sorting
        $query->orderBy($sortBy, $sortOrder);

        // Pagination/show all
        if ($request->has('all')) {
            $distributor = $query->get();
        } else {
            $distributor = $query->paginate(10)->appends($request->query());
        }

        return view('distributor.data', compact('distributor', 'sortBy', 'sortOrder'));
    }

    // =====================================================
    // 📤 EXPORT EXCEL
    // =====================================================
    public function exportExcel()
    {
        return Excel::download(new DistributorExport, 'data_distributor.xlsx');
    }

    // =====================================================
    // 📄 EXPORT PDF
    // =====================================================
    public function exportPdf()
    {
        $data = DB::table('distributor')->get();

        $pdf = PDF::loadView('pdf.distributor', ['distributor' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_distributor.pdf');
    }
}