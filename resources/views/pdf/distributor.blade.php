<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Distributor</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        margin: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 10px;
        font-size: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        page-break-inside: auto;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
    }

    th,
    td {
        border: 1px solid #333;
        padding: 6px;
        word-wrap: break-word;
        white-space: normal;
    }

    th {
        background: #e9e9e9;
        font-weight: bold;
        text-align: center;
        font-size: 11px;
    }

    /* Lebar kolom */
    th:nth-child(1) { width: 5%; }   /* No */
    th:nth-child(2) { width: 9%; }
    th:nth-child(3) { width: 18%; }
    th:nth-child(4) { width: 10%; }
    th:nth-child(5) { width: 8%; }
    th:nth-child(6) { width: 8%; }
    th:nth-child(7) { width: 8%; }
    th:nth-child(8) { width: 10%; }
    th:nth-child(9) { width: 10%; }
    th:nth-child(10) { width: 7%; }
    th:nth-child(11) { width: 7%; }

    td {
        font-size: 10.5px;
    }

    .coords {
        white-space: nowrap;
        font-size: 10px;
    }
    </style>
</head>

<body>

    <h2>Data Distributor - {{ now()->format('F Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Distributor</th>
                <th>Nama Distributor</th>
                <!-- <th>Kota</th>
                <th>Region</th>
                <th>SPV</th>
                <th>Logistic</th>
                <th>Provinsi</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Accuracy</th> -->
            </tr>
        </thead>

        <tbody>
            @foreach ($distributor as $index => $d)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{{ $d->ID_DISTRIBUTOR }}</td>
                <td>{{ $d->NAMA_DISTRIBUTOR }}</td>
                <!-- <td>{{ $d->ID_KOTA ?? '-' }}</td>
                <td>{{ $d->ID_REGION ?? '-' }}</td>
                <td>{{ $d->ID_SPV ?? '-' }}</td>
                <td>{{ $d->ID_LOGISTIC ?? '-' }}</td>
                <td>{{ $d->ID_PROV ?? '-' }}</td>

                <td class="coords">{{ $d->LATITUDE_DIST ?? '-' }}</td>
                <td class="coords">{{ $d->LONGITUDE_DIST ?? '-' }}</td>
                <td>{{ $d->ACCURACY_DIST ?? '-' }}</td> -->
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
