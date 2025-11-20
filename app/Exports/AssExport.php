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
     * Ambil data ASS (type_salesman = 7)
     */
    public function collection()
    {
        return DB::table('salesman')
            ->where('TYPE_SALESMAN', 7)
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.ID_SALESMAN',
                'salesman.NAMA_SALESMAN',
                'salesman.ID_DISTRIBUTOR',
                'distributor.NAMA_DISTRIBUTOR'
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
            'Nama Distributor'
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
        ];
    }
}
