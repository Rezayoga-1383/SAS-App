<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Pengerjaan Teknisi</title>
    <style>
        @page {
            size: 33cm 21.5cm;
            margin: 1.5cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table.td {
            border: none;
            vertical-align: top;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .periode {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="85%">
                <strong style="font-size:15px;">PT SARANA AGUNG SEJAHTERA</strong><br>
                General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>
                Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
                Telp : (031) 867 2677 | 0811 349 2009
            </td>
        </tr>
    </table>

    <hr style="border:1px solid #000; margin-top:10px;">

    <div class="title">REPORT PENGERJAAN TEKNISI <br></div>

    <div class="periode">
        Periode:
        {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d-m-Y') : 'Semua' }}
        -
        {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d-m-Y') : 'Semua' }}
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th style="border:1px solid#000; padding:4px;">No</th>
                <th style="border:1px solid #000; padding:6px;">Nama Teknisi</th>
                <th style="border:1px solid #000; padding:6px;">Cuci AC</th>
                <th style="border:1px solid #000; padding:6px">Perbaikan</th>
                <th style="border:1px solid #000; padding:6px;">Cek AC</th>
                <th style="border:1px solid #000; padding:6px;">Ganti Unit</th>
            </tr>
        </thead>

        <tbody>
            @foreach ( $data as $key => $row )
                <tr>
                    <td style="border:1px solid #000; padding:4px">{{ $key+1 }}</td>
                    <td style="border:1px solid #000; padding:6px">{{ $row->nama }}</td>
                    <td style="border:1px solid #000; padding:6px">{{ $row->cuci_ac }}</td>
                    <td style="border:1px solid #000; padding:6px">{{ $row->perbaikan }}</td>
                    <td style="border:1px solid #000; padding:6px">{{ $row->cek_ac }}</td>
                    <td style="border:1px solid #000; padding:6px">{{ $row->ganti_unit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>