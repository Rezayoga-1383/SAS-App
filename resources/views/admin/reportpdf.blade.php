<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dokumentasi</title>
    <style>
        @page {
            size: 21.5cm 33cm;
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

        .header-table td {
            border: none;
            vertical-align: top;
        }

        img.logo {
            width: 90px;
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

        /* ===== 1 DATA = 1 HALAMAN ===== */
        .section {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .judul-lokasi {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
        }

        /* SLOT FOTO TETAP */
        .foto-wrapper {
            height: 10.5cm;
            border: 1px solid #ccc;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        img.foto {
            max-height: 10.5cm;
            max-width: 100%;
        }

        .foto-kosong {
            font-size: 14px;
            color: #777;
            font-style: italic;
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

{{-- ================= DATA LIST ================= --}}
@forelse($data as $index => $item)

    <div class="section"
        @if($index > 0)
            style="page-break-before: always;"
        @endif
    >

        <div class="judul-lokasi">
            {{ $item['ruangan'] }} <br>
            ( No AC {{ $item['no_ac'] }})
        </div>

        <div class="info">
            Tanggal: {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }} <br>
            Departemen: {{ $item['departemen'] }}
        </div>

        {{-- FOTO HISTORY --}}
        <div class="foto-wrapper">
            @if($item['foto_history'])
                <img src="{{ public_path('storage/'.$item['foto_history']) }}" class="foto">
            @else
                <div class="foto-kosong">Tidak Ada Gambar</div>
            @endif
        </div>

        {{-- FOTO KOLOASE --}}
        <div class="foto-wrapper">
            @if($item['foto_kolase'])
                <img src="{{ public_path('storage/'.$item['foto_kolase']) }}" class="foto">
            @else
                <div class="foto-kosong">Tidak Ada Gambar</div>
            @endif
        </div>

    </div>

@empty
    <div style="text-align:center; margin-top:20px;">
        Tidak ada data.
    </div>
@endforelse

</body>
</html>