<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kecurangan</title>

    <style>
    body {
        font-family: "DejaVu Sans", sans-serif;
        margin: 20px;
        color: #000;
    }

    h2 {
        text-align: center;
        margin-bottom: 5px;
        font-weight: bold;
        text-transform: uppercase;
    }

    p {
        text-align: center;
        font-size: 13px;
        margin: 0 0 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px 4px;
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .footer {
        position: fixed;
        bottom: 0;
        text-align: right;
        font-size: 10px;
        width: 100%;
        color: #666;
    }
    </style>
</head>

<body>

    <h2>LAPORAN DATA KECURANGAN</h2>

    {{-- FILTER SALES --}}
    @if($sales)
    <p>
        Sales:
        <strong>{{ $sales->NAMA_SALESMAN }} ({{ $sales->ID_SALESMAN }})</strong>
    </p>
    @endif

    {{-- FILTER PERIODE --}}
    @if ($startDate && $endDate)
    <p>
        Periode:
        <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong>
        s/d
        <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
    </p>
    @else
    <p><strong>Semua Periode</strong></p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Sales</th>
                <th>Nama Sales</th>
                <th>Distributor</th>
                <th>Specialist Manager</th>
                <th>Jenis Sanksi</th>
                <th>Keterangan Sanksi</th>
                <th>Nilai Sanksi</th>
                <th>Toko</th>
                <th>Kunjungan</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kuartal</th>
            </tr>
        </thead>

        <tbody>
            @php $totalNilaiSanksi = 0; @endphp

            @forelse ($data as $index => $item)
            @php
                $nilai = !empty($item->NILAI_SANKSI) ? $item->NILAI_SANKSI : 0;
                $totalNilaiSanksi += $nilai;
            @endphp

            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>

                <td>{{ $item->ID_SALES }}</td>

                <td>{{ $item->nama_sales }}</td>

                <td>{{ $item->DISTRIBUTOR }}</td>

                <td>{{ $item->nama_specialist_manager ?? '-' }}</td>

                <td>{{ $item->JENIS_SANKSI ?? '-' }}</td>

                <td>{{ $item->KETERANGAN_SANKSI ?? '-' }}</td>

                <td>
                    @if ($nilai > 0)
                        Rp {{ number_format($nilai, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                <td>{{ $item->TOKO }}</td>

                <td style="text-align:center;">{{ $item->KUNJUNGAN }}</td>

                <td style="text-align:center;">
                    {{ \Carbon\Carbon::parse($item->TANGGAL)->format('d/m/Y') }}
                </td>

                <td>{{ $item->KETERANGAN ?: '-' }}</td>

                <td style="text-align:center;">{{ $item->KUARTAL }}</td>
            </tr>

            @empty
            <tr>
                <td colspan="13" style="text-align:center; color:#888;">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="7" style="text-align:center;">TOTAL SANKSI</th>
                <th colspan="6" style="font-weight:bold;">
                    Rp {{ number_format($totalNilaiSanksi, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
