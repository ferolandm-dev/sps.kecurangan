<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesmanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Ambil data salesman + distributor + total kecurangan + total customer
     */
    public function collection()
    {
        return DB::table('salesman')
            ->where('TYPE_SALESMAN', 1)
            ->leftJoin('distributor', 'salesman.ID_DISTRIBUTOR', '=', 'distributor.ID_DISTRIBUTOR')
            ->select(
                'salesman.ID_SALESMAN',
                'salesman.NAMA_SALESMAN',
                'salesman.ID_DISTRIBUTOR',
                'distributor.NAMA_DISTRIBUTOR',

                // TOTAL CUSTOMER
                DB::raw('(SELECT COUNT(*) 
                          FROM customer 
                          WHERE customer.ID_SALESMAN = salesman.ID_SALESMAN
                         ) AS total_customer'),

                // TOTAL KECURANGAN
                DB::raw('(SELECT COUNT(*) 
                          FROM kecurangan 
                          WHERE kecurangan.ID_SALES = salesman.ID_SALESMAN 
                          AND kecurangan.VALIDASI = 1
                         ) AS total_kecurangan')
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
            'ID Salesman',
            'Nama Salesman',
            'ID Distributor',
            'Nama Distributor',
            'Total Customer',
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
            $row->total_customer ?? 0,
            $row->total_kecurangan ?? 0
        ];
    }
}