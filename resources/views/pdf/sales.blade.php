<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Sales</title>
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
    <h2>Laporan Data Sales</h2>
    <table>
        <thead>
            <tr>
                <th>ID Sales</th>
                <th>Nama Sales</th>
                <th>ID Distributor</th>
                <th>Total Kecurangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->id_distributor }}</td>
                <td class="text-center">{{ $s->total_kecurangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>