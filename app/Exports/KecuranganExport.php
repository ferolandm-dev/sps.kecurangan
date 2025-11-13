<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KecuranganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $query = DB::table('kecurangan')
            ->select(
                'id_sales',
                'nama_sales',
                'distributor',
                'nama_asisten_manager',
                'jenis_sanksi',
                'keterangan_sanksi',
                'nilai_sanksi',
                'toko',
                'kunjungan',
                'tanggal',
                'keterangan',
                'kuartal'
            );

        // Filter berdasarkan tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function map($row): array
    {
        return [
            $row->id_sales,
            $row->nama_sales,
            $row->distributor,
            $row->nama_asisten_manager,
            $row->jenis_sanksi ?? '-',
            $row->keterangan_sanksi ?? '-',
            $row->nilai_sanksi ? 'Rp ' . number_format($row->nilai_sanksi, 0, ',', '.') : '-',
            $row->toko,
            $row->kunjungan,
            \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y'),
            $row->keterangan ?? '-',
            $row->kuartal ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'ID Sales',
            'Nama Sales',
            'Distributor',
            'Asisten Manager',
            'Jenis Sanksi',
            'Keterangan Sanksi',
            'Nilai Sanksi',
            'Toko',
            'Kunjungan',
            'Tanggal',
            'Keterangan',
            'Kuartal',
        ];
    }
}