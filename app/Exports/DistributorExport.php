<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistributorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::table('distributor')
            ->select(
                'ID_DISTRIBUTOR',
                'NAMA_DISTRIBUTOR',
                // 'ID_KOTA',
                // 'ID_REGION',
                // 'ID_SPV',
                // 'ID_LOGISTIC',
                // 'ID_PROV',
                // 'LATITUDE_DIST',
                // 'LONGITUDE_DIST',
                // 'ACCURACY_DIST'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Distributor',
            'Nama Distributor',
            // 'Kota',
            // 'Region',
            // 'SPV',
            // 'Logistic',
            // 'Provinsi',
            // 'Latitude',
            // 'Longitude',
            // 'Accuracy'
        ];
    }
} 