<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KecuranganExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $sales;
    protected $ass;
    protected $jenis;
    protected $keterangan;
    protected $startDate;
    protected $endDate;
    protected $createdStart;
    protected $createdEnd;
    protected $search;
    protected $validasi;

    public function __construct($request)
    {
        $this->sales        = $request['sales'] ?? null;
        $this->ass          = $request['ass'] ?? null;
        $this->jenis        = $request['jenis_sanksi'] ?? null;
        $this->keterangan   = $request['keterangan_sanksi'] ?? null;

        // Filter tanggal kejadian
        $this->startDate    = $request['start_date'] ?? null;
        $this->endDate      = $request['end_date'] ?? null;

        // Filter CREATED_AT
        $this->createdStart = $request['created_start_date'] ?? null;
        $this->createdEnd   = $request['created_end_date'] ?? null;

        // Search
        $this->search       = $request['search'] ?? null;

        // 🔥 Filter VALIDASI (bisa 1, 0, atau null = semua)
        $this->validasi     = $request['validasi'] ?? null;
    }

    public function collection()
    {
        $query = DB::table('kecurangan')
            ->leftJoin('salesman', 'kecurangan.ID_SALES', '=', 'salesman.ID_SALESMAN')
            ->leftJoin('salesman as ass', 'kecurangan.ID_ASS', '=', 'ass.ID_SALESMAN')
            ->select(
                'kecurangan.ID_SALES',
                'salesman.NAMA_SALESMAN',
                'kecurangan.DISTRIBUTOR',
                'ass.NAMA_SALESMAN as NAMA_ASS',
                'kecurangan.JENIS_SANKSI',
                'kecurangan.KETERANGAN_SANKSI',
                'kecurangan.NILAI_SANKSI',
                'kecurangan.TOKO',
                'kecurangan.KUNJUNGAN',
                'kecurangan.TANGGAL',
                'kecurangan.KETERANGAN',
                'kecurangan.KUARTAL',
                'kecurangan.CREATED_AT',
                'kecurangan.VALIDASI'
            );

        /* ==========================================================
           FILTER VALIDASI
        ========================================================== */
        if ($this->validasi !== null && $this->validasi !== '') {
            $query->where('kecurangan.VALIDASI', $this->validasi);
        }

        /* ==========================================================
           SEARCH
        ========================================================== */
        if (!empty($this->search)) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('salesman.NAMA_SALESMAN', 'like', "%{$s}%")
                  ->orWhere('kecurangan.TOKO', 'like', "%{$s}%")
                  ->orWhere('kecurangan.KETERANGAN_SANKSI', 'like', "%{$s}%")
                  ->orWhere('kecurangan.JENIS_SANKSI', 'like', "%{$s}%");
            });
        }

        /* ==========================================================
           FILTER SALES / ASS / SANCTION
        ========================================================== */
        if (!empty($this->sales)) {
            $query->where('kecurangan.ID_SALES', $this->sales);
        }

        if (!empty($this->ass)) {
            $query->where('kecurangan.ID_ASS', $this->ass);
        }

        if (!empty($this->jenis)) {
            $query->where('kecurangan.JENIS_SANKSI', $this->jenis);
        }

        if (!empty($this->keterangan)) {
            $query->where('kecurangan.KETERANGAN_SANKSI', $this->keterangan);
        }

        /* ==========================================================
           FILTER CREATED_AT RANGE
        ========================================================== */
        if (!empty($this->createdStart) && !empty($this->createdEnd)) {
            $query->whereBetween('kecurangan.CREATED_AT', [
                $this->createdStart . " 00:00:00",
                $this->createdEnd   . " 23:59:59"
            ]);
        }

        /* ==========================================================
           FILTER TANGGAL KEJADIAN
        ========================================================== */
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('kecurangan.TANGGAL', [
                $this->startDate,
                $this->endDate
            ]);
        }

        return $query->orderBy('kecurangan.TANGGAL', 'desc')->get();
    }

    public function map($row): array
    {
        return [
            $row->ID_SALES,
            $row->NAMA_SALESMAN,
            $row->DISTRIBUTOR,
            $row->NAMA_ASS ?? '-',
            $row->JENIS_SANKSI ?? '-',
            $row->KETERANGAN_SANKSI ?? '-',
            $row->NILAI_SANKSI 
                ? 'Rp ' . number_format($row->NILAI_SANKSI, 0, ',', '.')
                : 'Rp 0',
            $row->TOKO,
            (string) $row->KUNJUNGAN,
            \Carbon\Carbon::parse($row->TANGGAL)->format('d/m/Y'),
            \Carbon\Carbon::parse($row->CREATED_AT)->format('d/m/Y H:i'),
            $row->KETERANGAN ?: '-',
            $row->KUARTAL ?: '-',
        ];
    }

    public function headings(): array
    {
        return [
            'ID Sales',
            'Nama Sales',
            'Distributor',
            'Nama ASS',
            'Jenis Sanksi',
            'Keterangan Sanksi',
            'Nilai Sanksi',
            'Toko',
            'Kunjungan',
            'Tanggal Kejadian',
            'Tanggal Buat',
            'Keterangan',
            'Kuartal'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
