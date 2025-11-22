<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssExport;
use PDF;

class AssController extends Controller
{
    // =========================================================================
    // DATA LISTING
    // =========================================================================
    public function data(Request $request)
    {
        $query = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7) // 🔥 HANYA TYPE 7
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.*',
                'distributor.NAMA_DISTRIBUTOR',
                DB::raw('(SELECT COUNT(*) FROM kecurangan 
                        WHERE kecurangan.ID_SALES = salesman.ID_SALESMAN
                        AND kecurangan.VALIDASI = 1) AS total_kecurangan') // 🔥 ASS → ambil dari kolom ID_ASS
            );

        // SEARCH
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('salesman.ID_SALESMAN', 'like', "%{$s}%")
                  ->orWhere('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
                  ->orWhere('salesman.ID_DISTRIBUTOR', 'like', "%{$s}%")
                  ->orWhere('distributor.NAMA_DISTRIBUTOR', 'like', "%{$s}%");
            });
        }

        // SORT
        $sortBy = $request->get('sort_by', 'ID_SALESMAN');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowed = [
            'ID_SALESMAN',
            'NAMA_SALESMAN',
            'ID_DISTRIBUTOR',
            'NAMA_DISTRIBUTOR',
            'total_kecurangan',
        ];

        if (!in_array($sortBy, $allowed)) $sortBy = 'ID_SALESMAN';

        if ($sortBy === 'total_kecurangan') {
            $query->orderBy(DB::raw('total_kecurangan'), $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        if ($request->has('all')) {
    $ass = $query->paginate(999999)->appends($request->query());
        } else {
            $ass = $query->paginate(10)->appends($request->query());
        }

        return view('ass.data', compact('ass', 'sortBy', 'sortOrder'));
    }


    // =========================================================================
    // GET DATA KECURANGAN ASS
    // =========================================================================
    public function getKecurangan(Request $request, $id)
    {
        $data = DB::table('kecurangan')
            ->where('ID_SALES', $id)
            ->where('VALIDASI', 1)
            ->orderBy('TANGGAL', 'desc')
            ->paginate(7);   

        $totalNilai = DB::table('kecurangan')
            ->where('ID_SALES', $id)
            ->where('VALIDASI', 1)
            ->sum('NILAI_SANKSI');

        return response()->json([
            'data'        => $data,   
            'pagination'  => $data->links('pagination::modal')->render(),
            'total_nilai' => $totalNilai
        ]);
    }


    // =========================================================================
    // EXPORT EXCEL
    // =========================================================================
    public function exportExcel()
    {
        return Excel::download(new AssExport, 'data_ass.xlsx');
    }


    // =========================================================================
    // EXPORT PDF
    // =========================================================================
    public function exportPdf()
    {
        $data = DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.*',
                'distributor.NAMA_DISTRIBUTOR',
                DB::raw('(SELECT COUNT(*) FROM kecurangan 
                    WHERE kecurangan.ID_SALES = salesman.ID_SALESMAN 
                    AND kecurangan.VALIDASI = 1) AS total_kecurangan')
            )
            ->get();

        $pdf = PDF::loadView('pdf.ass', ['ass' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_ass.pdf');
    }
}