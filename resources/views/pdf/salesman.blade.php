<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Salesman</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    h2 {
        text-align: center;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px 8px;
        text-align: left;
    }

    th {
        background-color: #f0f0f0;
    }

    .text-center {
        text-align: center;
    }
    </style>
</head>

<body>

    <h2>Laporan Data Salesman</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align:center;">No</th>
                <th>ID Sales</th>
                <th>Nama Sales</th>
                <th>ID Distributor</th>
                <th style="text-align:center;">Total Kecurangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($salesman as $s)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $s->ID_SALESMAN }}</td>
                <td>{{ $s->NAMA_SALESMAN }}</td>
                <td>{{ $s->ID_DISTRIBUTOR }}</td>

                {{-- Kolom baru --}}
                <td class="text-center">
                    {{ $s->total_kecurangan ?? 0 }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>