<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data ASS</title>
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

    <h2>Laporan Data ASS</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align:center;">No</th>
                <th>Nama ASS</th>
                <th style="width: 20%; text-align:center;">Total Distributor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ass as $a)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $a->NAMA_SALESMAN }}</td>
                <td class="text-center">{{ $a->total_distributor }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>