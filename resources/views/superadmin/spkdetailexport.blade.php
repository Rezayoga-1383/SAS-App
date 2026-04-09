<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

@page{
    margin:40px 50px;
}

.page-break{
    page-break-before: always;
}

.ac-unit{
    page-break-inside: avoid;
}

img{
    page-break-inside: avoid;
}

table{
    page-break-inside: avoid;
}

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
}

/* container */
.sheet{
    width:100%;
}

/* ================= DETAIL ================= */

.detail-table{
    width:100%;
    margin-bottom:40px; /* jarak dengan SPK */
}

.detail-table td{
    padding:4px 6px;
}

/* ================= HEADER ================= */

.header-table{
    width:100%;
    margin-bottom:15px;
}

.header-table td{
    vertical-align:top;
}

.logo{
    width:80px;
}

/* ================= TITLE ================= */

.title{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin:20px 0;
}

/* ================= META ================= */

.meta{
    margin-bottom:20px;
}

/* ================= GRID ================= */

.grid{
    width:100%;
    border-collapse: collapse;
    font-size:12px;
    margin-top:10px;
}

.grid th{
    border:1px solid #000;
    background:#f2f2f2;
    padding:6px;
}

.grid td{
    border:1px solid #000;
    padding:6px;
}

/* ================= PARAGRAPH ================= */

p{
    margin:15px 0;
}

/* ================= SIGNATURE ================= */

.sign{
    margin-top:60px;
    width:100%;
}

.sign td{
    text-align:center;
    vertical-align:top;
}

.sig-line{
    margin-top:70px;
    border-top:1px solid #000;
    display:inline-block;
    padding-top:5px;
    width:160px;
}

.stamp{
    margin-top:15px;
    font-size:11px;
}

</style>

</head>

<body>

<div class="sheet">

<!-- ================= DETAIL SPK ================= -->
<table class="detail-table">

<tr>
<td width="180"><strong>No. SPK</strong></td>
<td width="10">:</td>
<td>{{ $spk->no_spk }}</td>
</tr>

<tr>
<td><strong>Tanggal</strong></td>
<td>:</td>
<td>{{ \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') }}</td>
</tr>

<tr>
<td><strong>Kategori Pengerjaan</strong></td>
<td>:</td>
<td>
@if($spk->details->count())
{{ $spk->details->pluck('kategori_pekerjaan')->unique()->join(', ') }}
@else
-
@endif
</td>
</tr>

<tr>
<td><strong>No AC</strong></td>
<td>:</td>
<td>
{{ $spk->units->count() 
? $spk->units->pluck('acdetail.no_ac')->join(', ') 
: '-' }}
</td>
</tr>

<tr>
<td><strong>Departement / Ruangan</strong></td>
<td>:</td>
<td>

@foreach($spk->units as $unit)

{{ $unit->acdetail->no_ac ?? '-' }} -
{{ optional($unit->acdetail->ruangan->departement)->nama_departement ?? '-' }}
({{ optional($unit->acdetail->ruangan)->nama_ruangan ?? '-' }})
<br>

@endforeach

</td>
</tr>

<tr>
<td><strong>Jumlah Teknisi</strong></td>
<td>:</td>
<td>{{ $spk->jumlah_orang }}</td>
</tr>

<tr>
<td><strong>Nama Teknisi</strong></td>
<td>:</td>
<td>
{{ $spk->teknisi && $spk->teknisi->count() > 0 
? $spk->teknisi->pluck('nama')->join(', ') 
: '-' }}
</td>
</tr>

<tr>
<td><strong>Waktu Pengerjaan</strong></td>
<td>:</td>
<td>
{{ \Carbon\Carbon::createFromFormat('H:i:s',$spk->waktu_mulai)->format('H.i') }}
-
{{ \Carbon\Carbon::createFromFormat('H:i:s',$spk->waktu_selesai)->format('H.i') }}
</td>
</tr>

</table>

<!-- ================= HEADER SPK ================= -->
<table class="header-table">
<tr>

<td>

<strong>PT. SARANA AGUNG SEJAHTERA</strong><br>

General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>

Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>

Telp : (031) 867 2677

</td>

<td align="right">

Sidoarjo, {{ \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') }}<br>

Kepada Yth.<br>

{{ $spk->kepada }}

</td>

</tr>
</table>

<div class="title">
SURAT PERINTAH KERJA
</div>

<div class="meta">

Datang jam 
{{ \Carbon\Carbon::createFromFormat('H:i:s',$spk->waktu_mulai)->format('H.i') }}

s/d

{{ \Carbon\Carbon::createFromFormat('H:i:s',$spk->waktu_selesai)->format('H.i') }}

<span style="float:right">

No : {{ $spk->no_spk }}

</span>

</div>

<p>

Dengan hormat, bersama ini kami kirimkan satu team kerja berjumlah 

{{ $spk->jumlah_orang }} orang untuk melakukan pekerjaan sesuai permintaan.

</p>

<!-- ================= TABEL PEKERJAAN ================= -->
<table class="grid">

<thead>

<tr>

<th width="5%">No</th>

<th>No AC</th>

<th>Keluhan</th>

<th>Jenis Pekerjaan</th>

</tr>

</thead>

<tbody>

@foreach($spk->units as $index => $unit)

@php
$detail = $spk->details->firstWhere('acdetail_id', $unit->acdetail_id);
@endphp

<tr>

<td>{{ $index+1 }}</td>

<td>{{ $unit->acdetail->no_ac ?? '-' }}</td>

<td>{{ $detail->keluhan ?? '-' }}</td>

<td>{{ $detail->jenis_pekerjaan ?? '-' }}</td>

</tr>

@endforeach

</tbody>

</table>

<p>

Dengan ini kami menyatakan pekerjaan tsb. di atas belum / sudah selesai.

</p>

<!-- ================= SIGNATURE ================= -->

<table class="sign">

<tr>

<td>

Pelaksana<br>

<div class="sig-line">

{{ $spk->pelaksana->nama ?? '-' }}

</div>

</td>

<td>

Mengetahui pemilik AC<br>

<div class="sig-line">

{{ $spk->mengetahui }}

</div>

</td>

<td>

Hormat kami<br>

<div class="sig-line">

{{ $spk->hormatKamiUser->nama ?? '-' }}

</div>

</td>

</tr>

</table>

<div class="stamp">

SPK ini sah jika ada stempel perusahaan

</div>

</div>
<div class="page-break"></div>
<hr style="margin-bottom:20px;">

<h3>Dokumentasi SPK</h3>

<!-- ================= FILE SPK ================= -->

@if($spk->file_spk)

@php
$fileUrl = public_path('storage/' . $spk->file_spk);
$ext = strtolower(pathinfo($spk->file_spk, PATHINFO_EXTENSION));
@endphp

<h4>File SPK</h4>

@if(in_array($ext, ['jpg','jpeg','png','gif','webp']))

<img src="{{ $fileUrl }}" style="width:100%; max-height:600px; object-fit:contain;">

@elseif($ext === 'pdf')

<p>File SPK berupa PDF. Silahkan buka file asli pada sistem.</p>

@endif

@else

<p>Tidak ada file SPK yang diupload.</p>

@endif


<!-- ================= UNIT AC ================= -->

@foreach($spk->units->chunk(2) as $chunkIndex => $unitChunk)

@if($chunkIndex > 0)
<div class="page-break"></div>
@endif

<table width="100%" style="margin-top:20px;">

@foreach($unitChunk as $unit)

<tr>
<td colspan="2">

<h4 style="margin-top:20px;">
Nomor AC : {{ $unit->acdetail->no_ac }} 
- {{ $unit->acdetail->ruangan->nama_ruangan ?? '' }}
</h4>

</td>
</tr>

@php
$historyImages = $unit->historyImages;
$fotoKolase = $unit->images;
@endphp

<tr>

<!-- HISTORY -->

<td width="50%" style="vertical-align:top; padding-right:10px;">

<strong>Kartu History AC</strong>

<br><br>

@if($historyImages->count())

@foreach($historyImages as $img)

<img 
src="{{ public_path('storage/'.$img->image_path) }}" 
style="width:100%; margin-bottom:10px;">

@endforeach

@else

<p>Tidak ada kartu history.</p>

@endif

</td>


<!-- FOTO KOLOSA -->

<td width="50%" style="vertical-align:top; padding-left:10px;">

<strong>Foto Kolase</strong>

<br><br>

@if($fotoKolase->count())

@foreach($fotoKolase as $img)

<img 
src="{{ public_path('storage/'.$img->image_path) }}" 
style="width:100%; margin-bottom:10px;">

@endforeach

@else

<p>Tidak ada foto kolase.</p>

@endif

</td>

</tr>

@endforeach

</table>

@endforeach

</body>
</html>