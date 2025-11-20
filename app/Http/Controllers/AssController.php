<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssExport;
use PDF;

class AssController extends Controller
{
    public function data(Request $request)
    {
        $query = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select(
                'NAMA_SALESMAN',
                DB::raw('COUNT(DISTINCT ID_DISTRIBUTOR) as total_distributor')
            )
            ->groupBy('NAMA_SALESMAN');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('NAMA_SALESMAN', 'like', "%{$search}%");
        }

        // SORTING
        $sortBy = $request->get('sort_by', 'NAMA_SALESMAN');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowed = ['NAMA_SALESMAN', 'total_distributor'];
        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'NAMA_SALESMAN';
        }

        $query->orderBy($sortBy, $sortOrder);

        // PAGINATION / SHOW ALL
        if ($request->has('all')) {
            $salesman = $query->get();
        } else {
            $salesman = $query->paginate(10)->appends($request->query());
        }

        return view('ass.data', compact('salesman', 'sortBy', 'sortOrder'));
    }
    public function getDistributorByAss(Request $request, $namaAss)
{
    $data = DB::table('salesman')
        ->where('TYPE_SALESMAN', 7)
        ->whereRaw("TRIM(LOWER(NAMA_SALESMAN)) = TRIM(LOWER(?))", [$namaAss])
        ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
        ->select(
            'salesman.ID_DISTRIBUTOR',
            'distributor.NAMA_DISTRIBUTOR'
        )
        ->groupBy('salesman.ID_DISTRIBUTOR', 'distributor.NAMA_DISTRIBUTOR')
        ->orderBy('distributor.NAMA_DISTRIBUTOR')
        ->paginate(7);

        return response()->json([
        'data'       => $data->items(),
        'pagination' => $data->links('pagination::modal')->render(),
        'currentPage' => $data->currentPage(),
        'perPage'     => $data->perPage(),
        'firstItem'   => $data->firstItem(),
    ]);

}



    // ============================================================================
    // EXPORT EXCEL
    // ============================================================================
    public function exportExcel()
    {
        return Excel::download(new AssExport, 'data_ass.xlsx');
    }

    // ============================================================================
    // EXPORT PDF
    // ============================================================================
    public function exportPdf()
    {
        $data = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select(
                'NAMA_SALESMAN',
                DB::raw('COUNT(DISTINCT ID_DISTRIBUTOR) as total_distributor')
            )
            ->groupBy('NAMA_SALESMAN')
            ->orderBy('NAMA_SALESMAN', 'asc')
            ->get();

        $pdf = PDF::loadView('pdf.ass', ['ass' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_ass.pdf');
    }
}