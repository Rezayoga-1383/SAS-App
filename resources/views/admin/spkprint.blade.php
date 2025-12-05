<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>SPK - {{ $spk->no_spk }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px 25px;
        }

        .container {
            width: 100%;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }

        .logo-img {
            width: 80px;
            height: auto;
        }

        .company {
            font-size: 11px;
            line-height: 1.2;
        }

        .addr {
            text-align: right;
            font-size: 11px;
            line-height: 1.2;
        }

        /* TITLE */
        .title {
            text-align: center;
            font-size: 18px;
            margin: 15px 0 10px 0;
            font-weight: bold;
        }

        /* META */
        .meta-table {
            width: 100%;
            margin: 10px 0;
            font-size: 12px;
        }

        .uline {
            border-bottom: 1px dashed #000;
            display: inline-block;
            min-width: 100px;
        }

        /* TABLE DATA */
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        table.grid th, table.grid td {
            border: 1px solid #000;
            padding: 6px;
        }
        table.grid th {
            background: #e5eefc;
            font-weight: bold;
        }

        /* SIGNATURE */
        .sign-table {
            width: 100%;
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
        }

        .sign-table td {
            padding: 35px 10px 5px 10px;
        }

        .stamp {
            margin-top: 20px;
            text-align: center;
            border: 2px solid #000;
            padding: 5px 10px;
            display: inline-block;
            font-weight: bold;
            font-size: 11px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="20%">
                <img src="{{ public_path('assets/image/logo_sas_ori.png') }}" class="logo-img">
            </td>
            <td width="55%" class="company">
                <strong>PT. SARANA AGUNG SEJAHTERA</strong><br>
                General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>
                Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
                Telp : (031) 867 2677, 0811 349 2009 | Fax : (031) 867 2677
            </td>
            <td width="25%" class="addr">
                Sidoarjo, {{ \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') }}<br>
                Kepada Yth.<br>
                {{ $spk->kepada }}
            </td>
        </tr>
    </table>

    <!-- TITLE -->
    <div class="title">SURAT PERINTAH KERJA (SPK)</div>

    <!-- META -->
    <table class="meta-table">
        <tr>
            <td>Datang : 
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_mulai)->format('H.i') }}
                s/d 
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_selesai)->format('H.i') }}
            </td>
            <td style="text-align:right;">
                No: <span class="uline">{{ $spk->no_spk }}</span>
            </td>
        </tr>
    </table>

    <p>
        Dengan hormat, bersama ini kami kirimkan satu team kerja berjumlah 
        {{ $spk->jumlah_orang }} orang untuk melakukan pekerjaan sesuai permintaan dari:
    </p>

    <!-- TABLE KELUHAN -->
    <table class="grid">
        <thead>
            <tr>
                <th style="width:5%;">No.</th>
                <th>Keluhan</th>
                <th style="width:40%;">Jenis Pekerjaan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $spk->keluhan }}</td>
                <td>{{ $spk->jenis_pekerjaan }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:10px;">
        Dengan ini kami menyatakan pekerjaan tersebut di atas belum / sudah selesai.
    </p>

    <!-- SIGNATURE -->
    <table class="sign-table">
        <tr>
            <td>
                Pelaksana<br><br><br>
                ( {{ $spk->pengguna->nama ?? '-' }} )
            </td>
            <td>
                Mengetahui Pemilik AC<br><br><br>
                ( {{ $spk->mengetahui }} )
            </td>
            <td>
                Hormat Kami<br><br><br>
                ( {{ $spk->hormatKamiUser->nama }} )
            </td>
        </tr>
    </table>

    <div class="stamp">SPK ini sah jika ada stempel perusahaan</div>

</div>

</body>
</html>
