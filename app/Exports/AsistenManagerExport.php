<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AsistenManagerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Ambil data dari tabel asisten_managers dan join ke distributors.
     */
    public function collection()
    {
        return DB::table('asisten_managers')
            ->join('distributors', 'asisten_managers.id_distributor', '=', 'distributors.id')
            ->select(
                'asisten_managers.id',
                'asisten_managers.nama',
                'distributors.distributor as nama_distributor',
                'asisten_managers.status',
                'asisten_managers.created_at'
            )
            ->orderBy('asisten_managers.created_at', 'desc')
            ->get();
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Asisten Manager',
            'Nama Asisten Manager',
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