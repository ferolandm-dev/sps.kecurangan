<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistributorsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('distributors')
            ->select('id', 'distributor', 'status', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Distributor',
            'Nama Distributor',
            'Status',
            'Dibuat Pada',
        ];
    }
}
