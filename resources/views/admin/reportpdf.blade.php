<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dokumentasi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f2f2f2;
        }

        .header-table td {
            border: none;
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
            margin-bottom: 15px;
        }

        img.logo {
            width: 90px;
        }

        img.foto {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<table class="header-table">
    <tr>
        <td width="15%">
            <img src="{{ public_path('assets/image/logo_sas_ori.png') }}" class="logo">
        </td>
        <td width="85%">
            <strong style="font-size:15px;">PT SARANA AGUNG SEJAHTERA</strong><br>
            General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>
            Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
            Telp : (031) 867 2677 | 0811 349 2009
        </td>
    </tr>
</table>

<hr style="border:1px solid #000; margin-top:10px;">

<div class="title">LAPORAN DOKUMENTASI PENGERJAAN <br>
    @if(!empty($jenis_service))
        <span style="font-size:14px;">
            JENIS SERVICE: {{ strtoupper($jenis_service) }}
        </span>
    @endif
</div>

<div class="periode">
    Periode:
    {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d-m-Y') : 'Semua' }}
    -
    {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d-m-Y') : 'Semua' }}
</div>

{{-- ================= TABLE ================= --}}
<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">Tanggal</th>
            <th width="10%">No AC</th>
            <th width="15%">Ruangan</th>
            <th width="15%">Departemen</th>
            <th width="20%">Foto History</th>
            <th width="20%">Foto Kolase</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>

            <td>
                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
            </td>

            <td>{{ $item['no_ac'] }}</td>

            <td>{{ $item['ruangan'] }}</td>

            <td>{{ $item['departemen'] }}</td>

            <td>
                @if($item['foto_history'])
                    <img src="{{ public_path('storage/'.$item['foto_history']) }}" class="foto">
                @else
                    -
                @endif
            </td>

            <td>
                @if($item['foto_kolase'])
                    <img src="{{ public_path('storage/'.$item['foto_kolase']) }}" class="foto">
                @else
                    -
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada data.</td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>