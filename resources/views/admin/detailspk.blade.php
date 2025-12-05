@extends('admin.template.main')

@section('title', 'Detail SPK - SAS')

@section('content')
<main class="content">
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-12 col-md-10 col-lg-9 mx-auto">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h4 class="card-title">Detail SPK - {{ $spk->no_spk ?? '-' }}</h4>
          <div>
            <a href="{{ route('admin.spk') }}" class="btn btn-outline-secondary">Kembali</a>
           {{-- @php
              // gunakan field spk bukan file_spk
              $file = $spk->spk ?? $spk->file_spk ?? $spk->file ?? null;
              $fileUrl = $file ? asset('storage/' . ltrim($file, '/')) : null;
              $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
            @endphp

            @if($file)
              <a href="{{ $fileUrl }}" target="_blank" class="btn btn-primary">Buka File</a>
              <a href="{{ $fileUrl }}" download class="btn btn-outline-primary">Unduh</a>
            @endif --}}
            <a href="{{ route('spk.generatePdf', $spk->id) }}" target="_blank" class="btn btn-primary">Buka SPK</a>
            <a href="{{ route('spk.generatePdf', $spk->id) }}?download=1" class="btn btn-outline-primary">Unduh SPK</a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <table class="table table-borderless mb-4">
              <tbody>
                <tr><th style="width:30%;">No. SPK</th><td>: {{ $spk->no_spk }}</td></tr>
                <tr><th>Tanggal</th><td>: {{ optional($spk->tanggal) ? \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') : '-' }}</td></tr>
                <tr><th>No AC</th><td>: {{ $spk->acdetail->no_ac ?? '-' }}</td></tr>
                <tr><th>Keluhan</th><td>: {{ $spk->keluhan }}</td></tr>
                <tr><th>Jenis Pekerjaan</th><td>: {{ $spk->jenis_pekerjaan }}</td></tr>
                <tr><th>Jumlah Teknisi</th><td>: {{ $spk->jumlah_orang }}</td></tr>
                <tr><th>Nama Teknisi</th><td>: {{ $spk->teknisi->pluck('nama')->join(', ') ?? '-' }}</td></tr>
                <tr><th>Waktu Mulai Pengerjaan</th><td>: {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_mulai)->format('H.i')  }}</td></tr>
                <tr><th>Waktu Selesai Pengerjaan</th><td>: {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_selesai)->format('H.i')  }}</td></tr>
              </tbody>
            </table>

            {{-- View SPK --}}
            <div class="sheet">
                <header class="hdr">
                <div class="logo">
                    <img src="{{ asset('assets/image/logo_sas_ori.png') }}" alt="SAS logo" class="logo-img">
                    <!-- <div class="logo-box">SAS</div> -->
                    <div class="company">
                    <strong>PT. SARANA AGUNG SEJAHTERA</strong><br>
                    General Contractor, Technical, Mechanical, Electrical, Computer & Stationery<br>
                    Ruko Palm Square Blok TF 31 Pondok Candra - Sidoarjo<br>
                    Telp : (031) 867 2677, 0811 349 2009 | Fax : (031) 867 2677
                    </div>
                </div>
                <div class="addr">
                    <div class="small">Sidoarjo, {{ optional($spk->tanggal) ? \Carbon\Carbon::parse($spk->tanggal)->format('d M Y') : '-' }}</div>
                    <div class="small">Kepada Yth.</div>
                    <div class="small">{{ $spk->kepada }}</div>
                </div>
                </header>

                <h1 class="title">SURAT PERINTAH KERJA</h1>

                <div class="meta">
                <div>Datang jam: {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_mulai)->format('H.i')  }} s/d jam {{ \Carbon\Carbon::createFromFormat('H:i:s', $spk->waktu_selesai)->format('H.i')  }}</div>
                <div class="right">No: <span class="uline short">{{ $spk->no_spk}}</span></div>
                </div>

                <p class="intro">Dengan hormat, bersama ini kami kirimkan satu team kerja berjumlah {{ $spk->jumlah_orang }} orang untuk melakukan pekerjaan sesuai permintaan dari:</p>

                <table class="grid">
                <thead>
                    <tr>
                    <th style="width:6%;">No.</th>
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

                <p class="note">Dengan ini kami menyatakan pekerjaan tsb. di atas belum / sudah selesai.</p>

                <footer class="sign">
                <div class="sig-block">
                    <div class="sig-title">Pelaksana,</div>
                    <br>
                    <div class="sig-line">( {{ $spk->pengguna->nama ?? '-' }} )</div>
                </div>
                <div class="sig-block">
                    <div class="sig-title">Mengetahui pemilik AC,</div>
                    <br>
                    <div class="sig-line">( {{ $spk->mengetahui }} )</div>
                </div>
                <div class="sig-block">
                    <div class="sig-title">Hormat kami,</div>
                    <br>
                    <div class="sig-line">( {{ $spk->hormatKamiUser->nama }} )</div>
                </div>
                </footer>
                <div class="stamp">SPK ini sah jika ada stempel perusahaan</div>
            </div>

            @if($spk->file_spk)
              @php
                $fileUrl = asset('storage/' . $spk->file_spk);
                $ext = strtolower(pathinfo($spk->file_spk, PATHINFO_EXTENSION));
              @endphp

              <div class="spk-preview text-center">
                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                  <img src="{{ $fileUrl }}" alt="SPK {{ $spk->no_spk }}" class="img-fluid border" style="max-width:100%; box-shadow:0 6px 18px rgba(0,0,0,0.08);">
                @elseif($ext === 'pdf')
                  <div class="ratio ratio-16x9">
                    <iframe src="{{ $fileUrl }}" frameborder="0"></iframe>
                  </div>
                @else
                  <p class="text-muted">Tipe file tidak didukung untuk preview. <a href="{{ $fileUrl }}" target="_blank">Buka file</a></p>
                @endif
              </div>
            @else
              <div class="alert alert-secondary mb-0">Belum ada file SPK yang diupload.</div>
            @endif

          </div>
        </div>

      </div>
    </div>
  </div>
</main>
@endsection

@push('styles')
<style>
/* Reset box-sizing */
* {
    box-sizing: border-box;
}

/* ========================
   SPK Preview Styling
   ======================== */
.spk-preview {
    width: 100%;           /* 100% dari container, termasuk saat sidebar collapse */
    max-width: 100%;       /* jangan dibatasi 800px */
    margin: 20px auto 0;
    padding: 0;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    overflow: hidden;
}

/* Gambar SPK */
.spk-preview img {
    display: block;
    width: 100%;           /* otomatis menyesuaikan container */
    height: auto;
    object-fit: contain;
    border-radius: 0;
}

/* PDF iframe dengan aspect-ratio A4 */
.spk-preview .ratio {
    width: 100%;
    aspect-ratio: 210 / 297;   /* Rasio A4 */
    position: relative;
}

.spk-preview .ratio iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* ========================
   SPK Layout (.sheet)
   ======================== */
:root {
    --paper-w: 800px;
    --accent: #0b4b9b;
    --muted: #555;
}

.sheet {
    width: 100%;
    max-width: var(--paper-w);
    background: #fff;
    padding: 26px;
    border: 1px solid #cfcfcf;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    margin-bottom: 30px;
}

/* Header & Logo */
.hdr { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; }
.logo { display: flex; gap: 12px; align-items: flex-start; }
.logo-img { width: 80px; height: 60px; object-fit: contain; border-radius: 6px; background: transparent; }
.company { font-size: 12px; color: var(--muted); line-height: 1.15; }
.addr { font-size: 12px; text-align: right; color: var(--muted); }

/* Title & meta */
.title { text-align: center; color: var(--accent); margin: 12px 0; font-weight: 700; letter-spacing: 1px; }
.meta { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; color: var(--muted); flex-wrap: wrap; }
.uline { display: inline-block; border-bottom: 2px dashed #666; padding: 0 6px; margin-left: 6px; }
.uline.short { min-width: 90px; }
.intro { font-size: 13px; color: var(--muted); margin: 8px 0 12px; }

/* Table grid */
.grid { width: 100%; border-collapse: collapse; font-size: 13px; }
.grid thead th { border: 1px solid #0b4b9b; background: #f3f8ff; color: var(--accent); padding: 8px; text-align: left; }
.grid tbody td { border: 1px solid #cfcfcf; padding: 10px; vertical-align: top; height: auto; }

/* Notes & Signatures */
.note { font-size: 13px; color: var(--muted); margin: 12px 4px; }
.sign { display: flex; justify-content: space-between; margin-top: 18px; flex-wrap: wrap; }
.sig-block { text-align: center; flex: 1; min-width: 150px; margin-bottom: 10px; }
.sig-block .sig-title { font-size: 13px; margin-bottom: 36px; color: var(--muted); }
.sig-line { border-top: 1px solid #333; padding-top: 6px; margin: 0 12px; }
.stamp { display: inline-block; margin-top: 12px; padding: 6px 10px; border: 2px solid var(--accent); color: var(--accent); font-weight: 700; border-radius: 4px; font-size: 12px; }

/* Responsif untuk mobile */
@media (max-width: 768px) {
    .sheet, .spk-preview {
        width: 100%;
        padding: 15px;
        margin: 15px auto;
    }
    .hdr, .meta, .sign {
        flex-direction: column;
        align-items: flex-start;
    }
    .sig-block {
        min-width: 100%;
        margin-bottom: 15px;
    }
}
</style>
@endpush
