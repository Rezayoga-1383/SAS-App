<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF | Data SPK</title>
    <style>
        @page {
        size: A4 landscape;
        margin: 20px;
        }

        body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        }

        /* HEADER */
        .header-table td{
        border:none;
        padding:0;
        }

        .title{
        font-size:16px;
        font-weight:bold;
        margin-top:10px;
        }

        .sub{
        font-size:12px;
        }

        /* TABEL */
        table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed; /* penting */
        margin-top:8px;
        }

        th,td{
        border:1px solid #000;
        padding:4px;
        word-wrap:break-word;
        vertical-align:top;
        }

        th{
        background:#f2f2f2;
        text-align:center;
        }

        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        /* ukuran kolom */
        .col-no{width:3%}
        .col-tgl{width:8%}
        .col-spk{width:7%}
        .col-ac{width:8%}
        .col-dept{width:12%}
        .col-ruang{width:10%}
        .col-keluhan{width:16%}
        .col-pekerjaan{width:20%}
        .col-teknisi{width:10%}
    </style>
</head>
<body>
    <div class="sheet">
        <table width="100%" class="header-table">
            <tr>
                {{-- <td width="15%" style="border: none;">
                    <img src="{{ public_path('assets/image/logo_sas_ori.png') }}" 
                        style="width:90px;">
                </td> --}}
                <td width="85%" style="border: none; text-align: left;">
                    <div style="font-size:16px; font-weight:bold;">
                        PT SARANA AGUNG SEJAHTERA
                    </div>
                    <div class="sub">
                        General Contractor, Technical, Mechanical, Electrical, Computer & Stationery
                    </div>
                    <div class="sub">
                        Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo
                    </div>
                    <div class="sub">
                        Telp : (031) 867 2677, 0811 349 2009 | Fax : (031) 867 2677
                    </div>
                </td>
            </tr>
        </table>

        <hr style="border: 2px solid black; margin-top:5px; margin-bottom:5px;">
    </div>

<div>
<h2 class="fw-bolder">Data Pengerjaan RSPAL Dr. Ramelan</h2>
    @if(!empty($jenis_service))
        <span style="font-size:14px; font-weight:bold;">
            JENIS SERVICE: {{ strtoupper($jenis_service) }}
        </span>
    @endif
</div>
@php
use Carbon\Carbon;
@endphp
<p>
Periode:
{{ $start_date ? Carbon::parse($start_date)->format('d-m-Y') : 'Semua' }}
-
{{ $end_date ? Carbon::parse($end_date)->format('d-m-Y') : 'Semua' }}
</p>

<table>
    <thead>
        <tr>
            <th class="col-no" >No</th>
            <th class="col-tgl">Tanggal</th>
            <th class="col-spk">No SPK</th>
            <th class="col-ac">No AC</th>
            <th class="col-dept">Departement</th>
            <th class="col-ruang">Ruangan</th>
            <th class="col-keluhan">Keluhan</th>
            <th class="col-pekerjaan">Jenis Pekerjaan</th>
            <th class="col-teknisi">Teknisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $spk)
        <tr>
            <td>
                {{ $index + 1 }}
            </td>
            <td>
                {{ $spk->tanggal }}
            </td>
            <td>
                {{ $spk->no_spk }}
            </td>

            {{-- Gabungkan semua No AC --}}
            <td>
                {{ $spk->details->map(fn($d) => $d->acdetail->no_ac ?? '-')->join(', ') }}
            </td>

            {{-- Gabungkan semua departement --}}
            <td>
                {{ $spk->details
                    ->map(fn($d) => $d->acdetail->ruangan->departement->nama_departement ?? '-')
                    ->filter()
                    ->unique()
                    ->join(', ') }}
            </td>

            {{-- Gabungkan semua ruangan --}}
            <td>
                {{ $spk->details
                    ->map(fn($d) => $d->acdetail->ruangan->nama_ruangan ?? '-')
                    ->filter()
                    ->unique()
                    ->join(', ') }}
            </td>        

            {{-- Gabungkan semua keluhan --}}
            <td>
                {{ $spk->details->pluck('keluhan')->filter()->join(', ') }}
            </td>

            {{-- Gabungkan semua jenis pekerjaan --}}
            <td>
                {{ $spk->details->pluck('jenis_pekerjaan')->filter()->join(', ') }}
            </td>

            {{-- Gabungkan semua teknisi --}}
            <td>
                {{ $spk->teknisi->pluck('nama')->filter()->join(', ') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>