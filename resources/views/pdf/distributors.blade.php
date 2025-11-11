<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Distributor</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    h2 {
        text-align: center;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <h2>Data Distributor - {{ now()->format('F Y') }}</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Distributor</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($distributors as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->distributor }}</td>
                <td>{{ ucfirst($d->status) }}</td>
                <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>