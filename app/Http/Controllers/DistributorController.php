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
    // 📊 DATA DISTRIBUTOR PAGE
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

        // Tambahkan subquery total salesman
        $query = DB::table('distributor')->select('distributor.*', DB::raw('(SELECT COUNT(*) FROM salesman s WHERE s.ID_DISTRIBUTOR = distributor.ID_DISTRIBUTOR AND s.TYPE_SALESMAN = 1 AND s.ID_SPC_MANAGER IS NOT NULL ) AS total_salesman'));

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

        // Pagination / show all
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

    // =====================================================
    // 📌 AJAX — GET SALESMAN PER DISTRIBUTOR
    // =====================================================
    public function getSalesman($id)
    {
        $data = DB::table('salesman')
            ->where('ID_DISTRIBUTOR', $id)
            ->where('TYPE_SALESMAN', 1)
            ->whereNotNull('ID_SPC_MANAGER')
            ->orderBy('ID_SALESMAN', 'asc')
            ->paginate(7);

        return response()->json([
            'data'           => $data->items(),                                // list datanya
            'first'          => $data->firstItem(),                            // nomor pertama di halaman itu
            'total_salesman' => $data->total(),                                // total salesman
            'pagination'     => $data->links('pagination::modal')->render(),   // pagination
        ]);
    }


}