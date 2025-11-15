<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KecuranganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $mode;
    protected $startDate;
    protected $endDate;
    protected $jenis;
    protected $keterangan;

    public function __construct(
        $mode = 'all',
        $startDate = null,
        $endDate = null,
        $jenis = null,
        $keterangan = null
    ) {
        $this->mode        = $mode;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
        $this->jenis       = $jenis;
        $this->keterangan  = $keterangan;
    }

    public function collection()
    {
        $query = DB::table('kecurangan')
            ->where('kecurangan.validasi', 1)
            ->select(
                'kecurangan.id_sales',
                'sales.nama AS nama_sales',
                'distributors.distributor AS distributor',
                'asisten_managers.nama AS nama_asisten_manager',
                'kecurangan.jenis_sanksi',
                'kecurangan.keterangan_sanksi',
                'kecurangan.nilai_sanksi',
                'kecurangan.toko',
                'kecurangan.kunjungan',
                'kecurangan.tanggal',
                'kecurangan.keterangan',
                'kecurangan.kuartal'
            )
            ->leftJoin('sales', 'kecurangan.id_sales', '=', 'sales.id')
            ->leftJoin('distributors', 'sales.id_distributor', '=', 'distributors.id')
            ->leftJoin('asisten_managers', 'kecurangan.id_asisten_manager', '=', 'asisten_managers.id');


        // MODE CETAK SEMUA
        if ($this->mode === 'all') {

        }

        // MODE CETAK BERDASARKAN TANGGAL
        if ($this->mode === 'date') {
            if ($this->startDate && $this->endDate) {
                $query->whereBetween('kecurangan.tanggal', [
                    $this->startDate,
                    $this->endDate
                ]);
            }
        }

        // FILTER TAMBAHAN (BERFUNGSI DI KEDUA MODE)
        if (!empty($this->jenis)) {
            $query->where('kecurangan.jenis_sanksi', $this->jenis);
        }

        if (!empty($this->keterangan)) {
            $query->where('kecurangan.keterangan_sanksi', $this->keterangan);
        }

        return $query->orderBy('kecurangan.tanggal', 'desc')->get();
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