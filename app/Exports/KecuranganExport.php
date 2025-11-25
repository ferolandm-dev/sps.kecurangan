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
    protected $mode;
    protected $startDate;
    protected $endDate;
    protected $jenis;
    protected $keterangan;
    protected $sales;
    protected $ass;

    /**
     * Controller mengirim: new KecuranganExport($request)
     */
    public function __construct($request)
    {
        // Ambil semua filter dari request
        $this->mode        = $request->mode ?? 'all';
        $this->startDate   = $request->start_date;
        $this->endDate     = $request->end_date;
        $this->jenis       = $request->jenis_sanksi;
        $this->keterangan  = $request->keterangan_sanksi;
        $this->sales       = $request->sales;
        $this->ass         = $request->ass; // 🔥 tambahan filter ASS
    }

    public function collection()
    {
        $query = DB::table('kecurangan')
            ->where('kecurangan.VALIDASI', 1)
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
                'kecurangan.KUARTAL'
            );

        // === FILTER ===
        if (!empty($this->sales)) {
            $query->where('kecurangan.ID_SALES', $this->sales);
        }

        if (!empty($this->ass)) {
            $query->where('kecurangan.ID_ASS', $this->ass); // 🔥 Filter ASS
        }

        if (!empty($this->jenis)) {
            $query->where('kecurangan.JENIS_SANKSI', $this->jenis);
        }

        if (!empty($this->keterangan)) {
            $query->where('kecurangan.KETERANGAN_SANKSI', $this->keterangan);
        }

        if ($this->mode === 'date' && $this->startDate && $this->endDate) {
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
            'Tanggal',
            'Keterangan',
            'Kuartal',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_TEXT, // Kolom Kunjungan
        ];
    }
}