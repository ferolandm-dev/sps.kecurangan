<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpecialistManagerExport;
use PDF;

class SpecialistManagerController extends Controller
{
    // ============================================================================
    // LIST DATA SPECIALIST MANAGER
    // ============================================================================
    public function data(Request $request)
    {
        $query = DB::table('specialist_manager')
            ->select(
                'NAMA',
                DB::raw('COUNT(DISTINCT ID_USER) as total_user')
            )
            ->groupBy('NAMA');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('NAMA', 'like', "%{$search}%");
        }

        // SORTING
        $sortBy = $request->get('sort_by', 'NAMA');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowed = ['NAMA', 'total_user'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'NAMA';
        }

        $query->orderBy($sortBy, $sortOrder);

        // PAGINATION / SHOW ALL
        if ($request->has('all')) {
            $data = $query->get();
        } else {
            $data = $query->paginate(10)->appends($request->query());
        }

        return view('specialist_manager.data', compact('data', 'sortBy', 'sortOrder'));
    }


    // ============================================================================
    // EXPORT EXCEL
    // ============================================================================
    public function exportExcel()
    {
        return Excel::download(new SpecialistManagerExport, 'specialist_manager.xlsx');
    }

    // ============================================================================
    // EXPORT PDF
    // ============================================================================
    public function exportPdf()
    {
        $managers = DB::table('specialist_manager')
            ->select(
                'NAMA',
                DB::raw('COUNT(DISTINCT ID_USER) as total_user')
            )
            ->groupBy('NAMA')
            ->orderBy('NAMA', 'asc')
            ->get();

        $pdf = PDF::loadView('pdf.SpecialistManager', compact('managers'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_specialist_manager.pdf');
    }

}