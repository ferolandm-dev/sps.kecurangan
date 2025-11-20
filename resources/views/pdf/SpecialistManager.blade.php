<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Specialist Manager</title>
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

    <h2>Laporan Data Specialist Manager</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align:center;">No</th>
                <th>Nama Specialist Manager</th>
                <th style="width: 20%; text-align:center;">Total User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($managers as $m)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $m->NAMA }}</td>
                <td class="text-center">{{ $m->total_user }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
