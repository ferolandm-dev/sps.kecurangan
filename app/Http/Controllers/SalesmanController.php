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
            ->whereIn('TYPE_SALESMAN', [1])
            ->whereNotNull('ID_SPC_MANAGER')
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.*',
                'distributor.NAMA_DISTRIBUTOR',

                // TOTAL KECURANGAN
                DB::raw('(SELECT COUNT(*) 
                        FROM kecurangan 
                        WHERE kecurangan.ID_SALES = salesman.ID_SALESMAN 
                        AND kecurangan.VALIDASI = 1
                ) AS total_kecurangan'),

                // TOTAL CUSTOMER
                DB::raw('(SELECT COUNT(*) 
                        FROM customer 
                        WHERE customer.ID_SALESMAN = salesman.ID_SALESMAN
                ) AS total_customer')
            );

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('salesman.ID_SALESMAN', 'like', "%{$search}%")
                ->orWhere('salesman.NAMA_SALESMAN', 'like', "%{$search}%")
                ->orWhere('salesman.ID_DISTRIBUTOR', 'like', "%{$search}%")
                ->orWhere('distributor.NAMA_DISTRIBUTOR', 'like', "%{$search}%");
            });
        }

        // 🔀 SORTING
        $sortBy    = $request->get('sort_by', 'ID_SALESMAN');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowed = [
            'ID_SALESMAN',
            'NAMA_SALESMAN',
            'ID_DISTRIBUTOR',
            'NAMA_DISTRIBUTOR',
            'TYPE_SALESMAN',
            'total_kecurangan',
            'total_customer' 
        ];

        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'ID_SALESMAN';
        }

        // kolom hasil subquery wajib pakai RAW
        if (in_array($sortBy, ['total_kecurangan', 'total_customer'])) {
            $query->orderBy(DB::raw($sortBy), $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $salesman = $request->has('all')
            ? $query->paginate(999999)->appends($request->query())
            : $query->paginate(10)->appends($request->query());

        return view('salesman.data', compact('salesman', 'sortBy', 'sortOrder'));
    }


    public function getKecurangan(Request $request, $id)
    {
        $data = DB::table('kecurangan')
            ->where('ID_SALES', $id)
            ->where('VALIDASI', 1)
            ->orderBy('TANGGAL', 'desc')
            ->paginate(7);

        // ➕ Hitung total nilai sanksi
        $totalNilai = DB::table('kecurangan')
            ->where('ID_SALES', $id)
            ->where('VALIDASI', 1)
            ->sum('NILAI_SANKSI');

        return response()->json([
            'data'       => $data->items(),
            'first'      => $data->firstItem(),
            'pagination' => $data->links('pagination::modal')->render(),
            'total_nilai' => $totalNilai
        ]);
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
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.*',
                'distributor.NAMA_DISTRIBUTOR',

                DB::raw('(SELECT COUNT(*) 
                        FROM kecurangan 
                        WHERE kecurangan.ID_SALES = salesman.ID_SALESMAN 
                        AND kecurangan.VALIDASI = 1
                ) AS total_kecurangan'),

                DB::raw('(SELECT COUNT(*) 
                        FROM customer 
                        WHERE customer.ID_SALESMAN = salesman.ID_SALESMAN
                ) AS total_customer')
            )
            ->get();

        $pdf = PDF::loadView('pdf.salesman', ['salesman' => $data])
            ->setPaper('a4', 'portrait');

        return $pdf->download('data_salesman.pdf');
    }
}