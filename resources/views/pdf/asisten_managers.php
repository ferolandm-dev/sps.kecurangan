<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Asisten Manager</title>
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
    <h2>Laporan Data Asisten Manager</h2>
    <table>
        <thead>
            <tr>
                <th>ID Asisten Manager</th>
                <th>Nama Asisten Manager</th>
                <th>ID Distributor</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistenManagers as $am)
            <tr>
                <td>{{ $am->id }}</td>
                <td>{{ $am->nama }}</td>
                <td>{{ $am->id_distributor }}</td>
                <td>{{ $am->status }}</td>
                <td>{{ $am->created_at ? date('d-m-Y', strtotime($am->created_at)) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>