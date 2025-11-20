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
     * Ambil data ASS (unique per NAMA_SALESMAN + hitung total distributor)
     */
    public function collection()
    {
        return DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->select(
                'NAMA_SALESMAN',
                DB::raw('COUNT(DISTINCT ID_DISTRIBUTOR) as total_distributor')
            )
            ->groupBy('NAMA_SALESMAN')
            ->orderBy('NAMA_SALESMAN', 'asc')
            ->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'Nama ASS',
            'Total Distributor'
        ];
    }

    /**
     * Mapping tiap baris data
     */
    public function map($row): array
    {
        return [
            $row->NAMA_SALESMAN,
            $row->total_distributor
        ];
    }
}