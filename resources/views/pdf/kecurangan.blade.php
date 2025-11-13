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
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    p {
        text-align: center;
        font-size: 13px;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px 5px;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .footer {
        position: fixed;
        bottom: 0;
        text-align: right;
        font-size: 10px;
        color: #666;
    }
    </style>
</head>

<body>

    <h2>LAPORAN DATA KECURANGAN</h2>

    @if (!empty($startDate) && !empty($endDate))
    <p>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong>
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
                <th>Asisten Manager</th>
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
            @forelse ($data as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->id_sales }}</td>
                <td>{{ $item->nama_sales }}</td>
                <td>{{ $item->distributor }}</td>
                <td>{{ $item->nama_asisten_manager }}</td>
                <td>{{ $item->jenis_sanksi ?? '-' }}</td>
                <td>{{ $item->keterangan_sanksi ?? '-' }}</td>
                <td>
                    @if (!empty($item->nilai_sanksi))
                    Rp {{ number_format($item->nilai_sanksi, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $item->toko }}</td>
                <td style="text-align: center;">{{ $item->kunjungan }}</td>
                <td style="text-align: center;">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                </td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->kuartal }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" style="text-align:center; color:#888;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>