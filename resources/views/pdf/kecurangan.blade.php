<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kasus</title>

    <style>
    body {
        font-family: "DejaVu Sans", sans-serif;
        margin: 10px 20px;
        /* FIX: lebih kecil agar tabel naik */
        padding-bottom: 20px;
        /* FIX: kecilkan footer space */
        color: #000;
    }

    h2 {
        text-align: center;
        margin: 0 0 3px 0;
        /* FIX: dipadatkan */
        font-weight: bold;
        text-transform: uppercase;
        line-height: 18px;
        /* FIX */
    }

    p {
        text-align: center;
        font-size: 12px;
        margin: 0;
        /* FIX: hilangkan gap antar filter */
        line-height: 14px;
        /* FIX: rapat */
        padding: 1px 0;
        /* FIX */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        page-break-inside: auto;
        /* FIX */
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px 3px;
        /* FIX: rapat */
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .footer {
        margin-top: 10px;
        text-align: right;
        font-size: 10px;
        color: #666;
    }
    </style>
</head>

<body>

    <h2>LAPORAN DATA KASUS</h2>

    {{-- FILTER SALES --}}
    @if($sales)
    <p>Sales:
        <strong>{{ $sales->NAMA_SALESMAN }} ({{ $sales->ID_SALESMAN }})</strong>
    </p>
    @endif

    {{-- FILTER ASS --}}
    @if($ass)
    <p>ASS:
        <strong>{{ $ass->NAMA_SALESMAN }} ({{ $ass->ID_SALESMAN }})</strong>
    </p>
    @endif

    {{-- FILTER JENIS SANKSI --}}
    @if(!empty($jenis_sanksi))
    <p>Jenis Sanksi:
        <strong>{{ $jenis_sanksi }}</strong>
    </p>
    @endif

    {{-- FILTER KETERANGAN SANKSI --}}
    @if(!empty($keterangan_sanksi))
    <p>Keterangan Sanksi:
        <strong>{{ $keterangan_sanksi }}</strong>
    </p>
    @endif

    {{-- FILTER CREATED_AT --}}
    @if (!empty($createdStart) && !empty($createdEnd))
    <p>Tanggal Buat:
        <strong>{{ \Carbon\Carbon::parse($createdStart)->format('d/m/Y') }}</strong>
        s/d
        <strong>{{ \Carbon\Carbon::parse($createdEnd)->format('d/m/Y') }}</strong>
    </p>
    @endif

    {{-- FILTER TANGGAL KEJADIAN --}}
    @if ($startDate && $endDate)
    <p>Tanggal Kejadian:
        <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong>
        s/d
        <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
    </p>
    @else

    {{-- FILTER VALIDASI --}}
    @if($validasi !== null && $validasi !== '')

    @php
    $validasiLabel = $validasi == 1 ? 'Sudah Validasi' : 'Belum Validasi';
    @endphp

    <p>Status Validasi:
        <strong>{{ $validasiLabel }}</strong>
    </p>
    @endif

    <p><strong>Semua Tanggal Kasus</strong></p>
    @endif

    <br>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Sales</th>
                    <th>Nama Sales</th>
                    <th>Distributor</th>
                    <th>Nama ASS</th>
                    <th>Jenis Sanksi</th>
                    <th>Keterangan Sanksi</th>
                    <th>Nilai Sanksi</th>
                    <th>Toko</th>
                    <th>Kunjungan</th>
                    <th>Tanggal Kasus</th>
                    <th>Tanggal Buat</th>
                    <th>Keterangan</th>
                    <th>Kuartal</th>
                </tr>
            </thead>

            <tbody>
                @php $totalNilaiSanksi = 0; @endphp

                @forelse ($data as $index => $item)
                @php
                $nilai = $item->NILAI_SANKSI ?? 0;
                $totalNilaiSanksi += $nilai;
                @endphp

                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $item->ID_SALES }}</td>
                    <td>{{ $item->nama_sales }}</td>
                    <td>{{ $item->DISTRIBUTOR }}</td>
                    <td>{{ $item->nama_ass ?? '-' }}</td>
                    <td>{{ $item->JENIS_SANKSI ?? '-' }}</td>
                    <td>{{ $item->KETERANGAN_SANKSI ?? '-' }}</td>
                    <td>Rp {{ number_format($nilai, 0, ',', '.') }}</td>
                    <td>{{ $item->TOKO }}</td>
                    <td style="text-align:center;">{{ $item->KUNJUNGAN }}</td>
                    <td style="text-align:center;">
                        {{ \Carbon\Carbon::parse($item->TANGGAL)->format('d/m/Y') }}
                    </td>
                    <td style="text-align:center;">
                        {{ \Carbon\Carbon::parse($item->CREATED_AT)->format('d/m/Y') }}
                    </td>
                    <td>{{ $item->KETERANGAN ?: '-' }}</td>
                    <td style="text-align:center;">{{ $item->KUARTAL }}</td>
                </tr>

                @empty
                <tr>
                    <td colspan="14" style="text-align:center; color:#888;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:center;">TOTAL SANKSI</th>
                    <th colspan="7" style="font-weight:bold;">
                        Rp {{ number_format($totalNilaiSanksi, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>