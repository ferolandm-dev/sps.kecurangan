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
     * Ambil data dari tabel sales dan join ke distributors + total kecurangan.
     */
    public function collection()
    {
        return DB::table('sales')
            ->where('status', 'Aktif')
            ->join('distributors', 'sales.id_distributor', '=', 'distributors.id')
            ->select(
                'sales.id',
                'sales.nama',
                'distributors.distributor as nama_distributor',
                DB::raw('(SELECT COUNT(*) FROM kecurangan WHERE kecurangan.id_sales = sales.id) as total_kecurangan'),
                'sales.status'
            )
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
     * Mapping isi data per baris.
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->nama,
            $row->nama_distributor,
            $row->total_kecurangan,
            ucfirst($row->status),
        ];
    }
}
