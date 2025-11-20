<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SpecialistManagerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Ambil data Specialist Manager (unique per NAMA + hitung total user)
     */
    public function collection()
    {
        return DB::table('specialist_manager')
            ->select(
                'NAMA',
                DB::raw('COUNT(DISTINCT ID_USER) as total_user')
            )
            ->groupBy('NAMA')
            ->orderBy('NAMA', 'asc')
            ->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'Nama Specialist Manager',
            'Total User'
        ];
    }

    /**
     * Mapping tiap baris data
     */
    public function map($row): array
    {
        return [
            $row->NAMA,
            $row->total_user
        ];
    }
}