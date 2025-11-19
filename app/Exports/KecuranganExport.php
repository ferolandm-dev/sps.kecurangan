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
    protected $sales; // ⬅️ TAMBAHAN

    public function __construct(
        $mode = 'all',
        $startDate = null,
        $endDate = null,
        $jenis = null,
        $keterangan = null,
        $sales = null // ⬅️ TERIMA SALES DARI CONTROLLER
    ) {
        $this->mode        = $mode;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
        $this->jenis       = $jenis;
        $this->keterangan  = $keterangan;
        $this->sales       = $sales; // ⬅️ SET SALES
    }

    public function collection()
{
    $query = DB::table('kecurangan')
        ->where('validasi', 1)
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

    // FILTER SALES
    if (!empty($this->sales)) {
        $query->where('id_sales', $this->sales);
    }

    // FILTER JENIS
    if (!empty($this->jenis)) {
        $query->where('jenis_sanksi', $this->jenis);
    }

    // FILTER KETERANGAN
    if (!empty($this->keterangan)) {
        $query->where('keterangan_sanksi', $this->keterangan);
    }

    // FILTER TANGGAL
    if ($this->mode === 'date' && $this->startDate && $this->endDate) {
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