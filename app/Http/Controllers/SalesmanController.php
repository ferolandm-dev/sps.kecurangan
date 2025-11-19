<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesmanExport;
use PDF;

class SalesmanController extends Controller
{
    // =========================================================================================
    // DATA (LISTING) — PENGGANTI index()
    // =========================================================================================
    public function data(Request $request)
    {
        $query = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.*',
                'distributor.NAMA_DISTRIBUTOR'
            );

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('salesman.ID_SALESMAN', 'like', "%{$search}%")
                  ->orWhere('salesman.NAMA_SALESMAN', 'like', "%{$search}%")
                  ->orWhere('salesman.ID_DISTRIBUTOR', 'like', "%{$search}%")
                  ->orWhere('distributor.NAMA_DISTRIBUTOR', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'ID_SALESMAN');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowed = [
            'ID_SALESMAN',
            'NAMA_SALESMAN',
            'ID_DISTRIBUTOR',
            'NAMA_DISTRIBUTOR',
            'TYPE_SALESMAN'
        ];

        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'ID_SALESMAN';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Paginate / Show All
        if ($request->has('all')) {
            $salesman = $query->get();
        } else {
            $salesman = $query->paginate(10)->appends($request->query());
        }

        return view('salesman.data', compact('salesman', 'sortBy', 'sortOrder'));
    }

    // =========================================================================================
    // EXPORT EXCEL
    // =========================================================================================
    public function exportExcel()
    {
        return Excel::download(new SalesmanExport, 'data_salesman.xlsx');
    }

    // =========================================================================================
    // EXPORT PDF
    // =========================================================================================
    public function exportPdf()
    {
        $data = DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->get();

        $pdf = PDF::loadView('pdf.salesman', ['salesman' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_salesman.pdf');
    }
}