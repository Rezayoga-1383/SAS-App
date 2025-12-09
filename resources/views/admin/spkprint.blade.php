<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SPK - {{ $spk->no_spk }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            background: #f5f5f5;
        }

        .container {
            width: 210mm;
            height: 297mm;
            background: #fff;
            margin: 10mm auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 2px solid #0b4b9b;
            padding-bottom: 10px;
        }

        .header-left {
            display: flex;
            gap: 12px;
        }

        .logo-img {
            width: 70px;
            height: 60px;
            object-fit: contain;
            background: #f0f0f0;
            padding: 4px;
            border-radius: 4px;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }

        .company-info strong {
            display: block;
            font-size: 12px;
            color: #0b4b9b;
            margin-bottom: 3px;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.4;
            color: #555;
        }

        /* TITLE */
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #0b4b9b;
            margin: 15px 0 10px 0;
            letter-spacing: 1px;
        }

        /* META */
        .meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 10px;
            color: #555;
        }

        .uline {
            border-bottom: 1px dashed #000;
            display: inline-block;
            min-width: 100px;
            margin-left: 5px;
        }

        /* INTRO TEXT */
        .intro {
            font-size: 12px;
            margin: 10px 0;
            color: #555;
            line-height: 1.5;
        }

        /* TABLE */
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }

        table.grid thead {
            background: #e5eefc;
            border: 1px solid #0b4b9b;
        }

        table.grid th {
            border: 1px solid #0b4b9b;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #0b4b9b;
        }

        table.grid tbody td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
            min-height: 30px;
        }

        table.grid tbody tr:nth-child(odd) {
            background: #fafafa;
        }

        /* NOTE */
        .note {
            font-size: 12px;
            margin: 10px 0;
            color: #555;
        }

        /* SIGNATURE SECTION */
        .signature-section {
            margin-top: 30px;
        }

        .signature-table {
            width: 100%;
            margin-top: 20px;
        }

        .signature-table td {
            text-align: center;
            width: 33.33%;
            padding: 40px 10px 10px 10px;
            font-size: 11px;
            vertical-align: bottom;
        }

        .signature-line {
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-top: 30px;
            min-height: 20px;
        }

        .sig-title {
            font-weight: bold;
            margin-bottom: 35px;
            color: #333;
        }

        /* STAMP */
        .stamp {
            text-align: center;
            margin-top: 15px;
            padding: 8px 15px;
            display: inline-block;
            border: 2px solid #0b4b9b;
            font-weight: bold;
            font-size: 11px;
            color: #0b4b9b;
            border-radius: 3px;
        }

        /* PAGE BREAK */
        @media print {
            body {
                background: #fff;
            }
            .container {
                width: 100%;
                height: auto;
                margin: 0;
                padding: 20mm;
                box-shadow: none;
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('assets/image/logo_sas_ori.png') }}" class="logo-img" alt="SAS Logo">
            <div class="company-info">
                <strong>PT. SARANA AGUNG SEJAHTERA</strong>
                General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>
                Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
                Telp : (031) 867 2677, 0811 349 2009 | Fax : (031) 867 2677
            </div>
        </div>
        <div class="header-right">
            <div>Sidoarjo, {{ \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') }}</div>
            <div style="margin-top: 5px;">Kepada Yth.</div>
            <div style="margin-top: 3px;"><strong>{{ $spk->kepada }}</strong></div>
        </div>
    </div>

    <!-- TITLE -->
    <div class="title">SURAT PERINTAH KERJA</div>

    <!-- META INFO -->
    <div class="meta">
        <div>
            Datang jam: 
            <strong>
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_mulai)->format('H.i') }} 
                s/d 
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_selesai)->format('H.i') }}
            </strong>
        </div>
        <div>
            No: <span class="uline">{{ $spk->no_spk }}</span>
        </div>
    </div>

    <!-- INTRO TEXT -->
    <p class="intro">
        Dengan hormat, bersama ini kami kirimkan satu team kerja berjumlah 
        <strong>{{ $spk->jumlah_orang }} orang</strong> 
        untuk melakukan pekerjaan sesuai permintaan dari:
    </p>

    <!-- DETAIL AC TABLE -->
    <table class="grid">
        <thead>
            <tr>
                <th style="width:5%;">No.</th>
                <th style="width:20%;">No AC</th>
                <th style="width:35%;">Keluhan</th>
                <th style="width:40%;">Jenis Pekerjaan</th>
            </tr>
        </thead>
        <tbody>
            @if($spk->acdetails && $spk->acdetails->count() > 0)
                @foreach($spk->acdetails as $index => $ac)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $ac->no_ac }}</td>
                    <td>{{ $ac->pivot->keluhan ?? '-' }}</td>
                    <td>{{ $ac->pivot->jenis_pekerjaan ?? '-' }}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="4" style="text-align: center; color: #999;">Tidak ada data AC</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- NOTE -->
    <p class="note">
        Dengan ini kami menyatakan pekerjaan tsb. di atas belum / sudah selesai.
    </p>

    <!-- SIGNATURE SECTION -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="sig-title">Pelaksana,</div>
                    <div class="signature-line">
                        <div style="font-size: 10px; margin-top: 5px;">
                            ( {{ $spk->pelaksana->nama ?? '-' }} )
                        </div>
                    </div>
                </td>
                <td>
                    <div class="sig-title">Mengetahui Pemilik AC,</div>
                    <div class="signature-line">
                        <div style="font-size: 10px; margin-top: 5px;">
                            ( {{ $spk->mengetahui }} )
                        </div>
                    </div>
                </td>
                <td>
                    <div class="sig-title">Hormat Kami,</div>
                    <div class="signature-line">
                        <div style="font-size: 10px; margin-top: 5px;">
                            ( {{ $spk->hormatKamiUser->nama ?? '-' }} )
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- STAMP -->
    <div style="text-align: center; margin-top: 20px;">
        <div class="stamp">SPK ini sah jika ada stempel perusahaan</div>
    </div>

</div>

</body>
</html>
