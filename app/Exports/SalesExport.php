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
     * Ambil data dari tabel sales dan join ke distributors.
     */
    public function collection()
    {
        return DB::table('sales')
            ->join('distributors', 'sales.id_distributor', '=', 'distributors.id')
            ->select(
                'sales.id',
                'sales.nama',
                'distributors.distributor as nama_distributor',
                'sales.status',
                'sales.created_at'
            )
            ->orderBy('sales.created_at', 'desc')
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
            'Status',
            'Tanggal Dibuat',
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
            ucfirst($row->status),
            \Carbon\Carbon::parse($row->created_at)->format('d-m-Y'),
        ];
    }
}