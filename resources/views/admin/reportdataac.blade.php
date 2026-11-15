
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Data AC - RSPAL Dr. Ramelan</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 17px;
        }

        .header p {
            margin: 0;
            font-size: 10px;
        }

        .info {
            margin-bottom: 10px;
            text-align: right;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #777;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        td.text-left {
            text-align: left;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>DATA AC - RSPAL Dr. Ramelan</h2>
        <p>PT SARANA AGUNG SEJAHTERA</p>
    </div>

    <div class="info">
        Tanggal Export: {{ $tanggal }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">No AC</th>
                <th width="9%">Merk</th>
                <th width="8%">Jenis</th>
                <th width="12%">Departement</th>
                <th width="12%">Ruangan</th>
                <th width="10%">No Seri Indoor</th>
                <th width="10%">No Seri Outdoor</th>
                <th width="5%">PK</th>
                <th width="5%">Jumlah</th>
                <th width="5%">Tahun</th>
                {{-- <th width="7%">Tgl Pemasangan</th> --}}
                {{-- <th width="8%">Tgl Habis Garansi</th> --}}
            </tr>
        </thead>

        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->no_ac ?? '-' }}</td>
                    <td class="text-left">
                        {{ $item->merkac->nama_merk ?? '-' }}
                    </td>
                    <td>{{ $item->jenisac->nama_jenis ?? '-' }}</td>
                    <td class="text-left">
                        {{ $item->ruangan->departement->nama_departement ?? '-' }}
                    </td>
                    <td class="text-left">
                        {{ $item->ruangan->nama_ruangan ?? '-' }}
                    </td>
                    <td>{{ $item->no_seri_indoor ?? '-' }}</td>
                    <td>{{ $item->no_seri_outdoor ?? '-' }}</td>
                    <td>{{ $item->pk_ac ?? '-' }}</td>
                    <td>{{ $item->jumlah_ac ?? 0 }}</td>
                    <td>{{ $item->tahun_ac ?? '-' }}</td>
                    {{-- <td>{{ $item->tanggal_pemasangan ?? '-' }}</td> --}}
                    {{-- <td>{{ $item->tanggal_habis_garansi ?? '-' }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="13">Belum ada data AC.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total Data AC: {{ $data->count() }} Data
    </div>

</body>
</html>
