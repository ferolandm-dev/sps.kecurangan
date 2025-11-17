<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Ambil data sales + distributor + total kecurangan.
     */
    public function collection()
    {
        return DB::table('sales')
            ->join('distributors', 'sales.id_distributor', '=', 'distributors.id')
            ->select(
                'sales.id',
                'sales.nama',
                'distributors.distributor as nama_distributor',
                DB::raw('(SELECT COUNT(*) FROM kecurangan WHERE kecurangan.id_sales = sales.id) as total_kecurangan'),
                'sales.status'
            )
            ->where('sales.status', 'Aktif')   // FIX AMBIGUOUS COLUMN
            ->orderBy('sales.id', 'asc')
            ->get();
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Sales',
            'Nama Sales',
            'Nama Distributor',
            'Total Kecurangan',
            'Status',
        ];
    }

    /**
     * Mapping tiap baris data.
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->nama,
            $row->nama_distributor,
            $row->total_kecurangan,
            ucfirst(strtolower($row->status)),
        ];
    }
}
