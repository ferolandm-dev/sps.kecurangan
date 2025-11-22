<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Ambil data ASS + distributor + total kecurangan valid
     */
    public function collection()
    {
        return DB::table('salesman')
            ->where('TYPE_SALESMAN', 7) // TIPE ASS
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.ID_SALESMAN',      // ID ASS
                'salesman.NAMA_SALESMAN',    // Nama ASS
                'salesman.ID_DISTRIBUTOR',
                'distributor.NAMA_DISTRIBUTOR',
                DB::raw('(SELECT COUNT(*) FROM kecurangan 
                          WHERE kecurangan.id_ass = salesman.ID_SALESMAN 
                          AND kecurangan.validasi = 1) AS total_kecurangan')
            )
            ->orderBy('salesman.ID_SALESMAN', 'asc')
            ->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID ASS',
            'Nama ASS',
            'ID Distributor',
            'Nama Distributor',
            'Total Kecurangan'
        ];
    }

    /**
     * Mapping tiap baris data
     */
    public function map($row): array
    {
        return [
            $row->ID_SALESMAN,
            $row->NAMA_SALESMAN,
            $row->ID_DISTRIBUTOR,
            $row->NAMA_DISTRIBUTOR ?? '-',
            $row->total_kecurangan ?? 0
        ];
    }
}
