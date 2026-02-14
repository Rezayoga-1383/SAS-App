<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF | Data SPK</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #f2f2f2; }

        /* Kop Sarana */
        .sheet {
            width: 100%;
            max-width: var(--paper-w);
            background: #fff;
            padding: 26px;
            border: 1 px solid #cfcfcf;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            margin-bottom: 20px;
        }
        .hdr {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .logo {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .logo-img {
            width: 80px;
            height: 60px;
            object-fit: contain;
            border-radius: 6px;
            background: transparent;
        }
        .company {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.15;
        }
        .addr {
            font-size: 12px;
            text-align: right;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <div class="sheet">
        <table width="100%" style="border: none; border-collapse: collapse;">
            <tr>
                <td width="15%" style="border: none;">
                    <img src="{{ public_path('assets/image/logo_sas_ori.png') }}" 
                        style="width:90px;">
                </td>
                <td width="85%" style="border: none; text-align: left;">
                    <div style="font-size:16px; font-weight:bold;">
                        PT SARANA AGUNG SEJAHTERA
                    </div>
                    <div style="font-size:12px;">
                        General Contractor, Technical, Mechanical, Electrical, Computer & Stationery
                    </div>
                    <div style="font-size:12px;">
                        Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo
                    </div>
                    <div style="font-size:12px;">
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
            <th>No</th>
            <th>Tanggal</th>
            <th>No SPK</th>
            <th>No AC</th>
            <th>Keluhan</th>
            <th>Jenis Pekerjaan</th>
            <th>Teknisi</th>
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