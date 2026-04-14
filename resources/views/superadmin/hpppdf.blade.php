<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan HPP</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .company {
            font-size: 14px;
            font-weight: bold;
        }

        .divider {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th, table.data td {
            border: 1px solid #000;
            padding: 5px;
        }

        table.data th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 10px;
            width: 100%;
        }

        .summary td {
            padding: 5px;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td width="70%">
                <div class="company">PT SARANA AGUNG SEJAHTERA</div>
                General Contractor, Mechanical, Electrical, Computer & Stationery<br>
                Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
                Telp : (031) 867 2677 | 0811 349 2009
            </td>
            <td width="30%" class="text-right">
                {{-- Optional logo --}}
                {{-- <img src="{{ public_path('assets/image/logo.png') }}" width="80"> --}}
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- TITLE --}}
    <div class="title">LAPORAN HPP</div>

    {{-- INFO --}}
    <div class="info">
        <table>
            <tr>
                <td width="50%">
                    <strong>Periode :</strong>
                    {{ $start ? \Carbon\Carbon::parse($start)->format('d-m-Y') : 'Semua' }}
                    s/d
                    {{ $end ? \Carbon\Carbon::parse($end)->format('d-m-Y') : 'Semua' }}
                </td>
                <td width="50%" class="text-right">
                    <strong>Total SPK :</strong> {{ $totalSpk }}
                </td>
            </tr>
        </table>
    </div>

    {{-- TABLE --}}
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">No SPK</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Teknisi</th>
                <th width="15%">Departement</th>
                <th width="15%">Ruangan</th>
                <th width="20%">Pekerjaan</th>
                <th width="10%">Total HPP</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item['no_spk'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ $item['teknisi'] }}</td>
                    <td>{{ $item['departement'] }}</td>
                    <td>{{ $item['ruangan'] }}</td>
                    <td>{{ $item['pekerjaan'] }}</td>
                    <td class="text-right">
                        Rp {{ number_format($item['total_hpp'], 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- SUMMARY --}}
    <table class="summary">
        <tr>
            <td width="80%" class="text-right">
                <strong>Grand Total</strong>
            </td>
            <td width="20%" class="text-right">
                <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    {{-- <div class="footer">
        Sidoarjo, {{ \Carbon\Carbon::now()->format('d-m-Y') }}<br><br><br>
        ___________________________<br>
        ( Penanggung Jawab )
    </div> --}}

</body>
</html>