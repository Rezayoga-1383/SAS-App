<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Absensi Karyawan - PT Sarana Agung Sejahtera</title>

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

  <!-- Bootstrap -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <style>
    * { box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f6f9;
      color: #333;
    }

    /* ── Page Header ── */
    .absensi-header {
      background: #fff;
      border-bottom: 1px solid #e8eaf0;
      padding: 18px 0;
      margin-bottom: 28px;
    }
    .absensi-header h1 {
      font-size: 22px;
      font-weight: 700;
      color: #1a1a2e;
      margin: 0 0 2px;
    }
    .absensi-header h1 span { font-weight: 300; color: #6c757d; }
    .absensi-header p {
      font-size: 13px;
      color: #9099a8;
      margin: 0;
    }

    /* Clock top-right */
    .clock-box {
      text-align: right;
    }
    .clock-box .time {
      font-size: 22px;
      font-weight: 600;
      color: #1a1a2e;
      line-height: 1.2;
    }
    .clock-box .date {
      font-size: 13px;
      color: #9099a8;
    }

    /* ── Late Notice ── */
    .late-notice {
      background: #fff8f0;
      border: 1px solid #fddcb5;
      border-radius: 10px;
      padding: 11px 16px;
      font-size: 13px;
      color: #7a4f1e;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .late-notice strong { color: #e07c28; }
    .late-badge {
      background: #e07c28;
      color: #fff;
      font-size: 11px;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 20px;
      margin-left: 4px;
    }

    /* ── Cards ── */
    .card-section {
      background: #fff;
      border-radius: 14px;
      border: 1px solid #e8eaf0;
      overflow: hidden;
      margin-bottom: 16px;
    }
    .card-section-header {
      padding: 14px 18px;
      border-bottom: 1px solid #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-section-header .title {
      font-size: 14px;
      font-weight: 600;
      color: #444;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .card-section-header .title i {
      color: #9099a8;
      font-size: 16px;
    }

    /* Status badges */
    .badge-status {
      font-size: 11px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
    }
    .badge-ok   { background: #1aab6d; color: #fff; }
    .badge-warn { background: #e07c28; color: #fff; }
    .badge-idle { background: #e8eaf0; color: #888; }

    /* ── Camera Section ── */
    .camera-wrapper {
      position: relative;
      background: #111;
      overflow: hidden;
    }
    #video, #preview-img {
      width: 100%;
      display: block;
      max-height: 340px;
      object-fit: cover;
    }
    #preview-img { display: none; }

    .btn-ulangi {
      width: 100%;
      background: #f5a623;
      color: #fff;
      border: none;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .2s;
    }
    .btn-ulangi:hover { background: #e09510; }
    .btn-ulangi:disabled { opacity: .5; cursor: default; }

    /* Overlay foto diambil */
    .foto-taken-overlay {
      position: absolute;
      top: 10px; right: 10px;
      background: #1aab6d;
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
      display: none;
    }

    /* ── Lokasi Section ── */
    .lokasi-body { padding: 16px 18px; }
    .lokasi-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 14px;
    }
    .lokasi-item label {
      display: block;
      font-size: 11px;
      color: #9099a8;
      font-weight: 500;
      margin-bottom: 2px;
    }
    .lokasi-item span {
      font-size: 13px;
      color: #333;
      font-weight: 500;
    }
    .lokasi-item.full { grid-column: 1 / -1; }
    .alamat-val {
      font-size: 13px;
      color: #333;
      line-height: 1.5;
    }

    /* Map container */
    #map-container {
      height: 160px;
      background: #e8eaf0;
      overflow: hidden;
      position: relative;
      margin-bottom: 0;
    }
    #map-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
    #map-placeholder {
      position: absolute; inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #b0b8c4;
      font-size: 13px;
      gap: 8px;
    }

    /* Tombol lokasi terdeteksi */
    .btn-lokasi-detected {
      width: 100%;
      background: #fff;
      border: none;
      border-top: 1px solid #f0f2f5;
      padding: 13px;
      font-size: 13px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      color: #1aab6d;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: default;
    }
    .btn-ambil-lokasi {
      width: 100%;
      background: #fff;
      border: none;
      border-top: 1px solid #f0f2f5;
      padding: 13px;
      font-size: 13px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      color: #3b7ddd;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      transition: background .2s;
    }
    .btn-ambil-lokasi:hover { background: #f4f6f9; }
    .btn-ambil-lokasi:disabled { opacity: .5; cursor: default; }

    /* ── Form Absensi (right column) ── */
    .form-body { padding: 20px 20px 16px; }

    .form-group { margin-bottom: 18px; }
    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #555;
      margin-bottom: 6px;
    }
    .form-group label span {
      font-weight: 400;
      color: #aaa;
      font-size: 12px;
    }
    .form-control-custom {
      width: 100%;
      border: 1.5px solid #e0e3ea;
      border-radius: 9px;
      padding: 10px 14px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      color: #333;
      background: #fff;
      outline: none;
      transition: border-color .2s;
      appearance: none;
    }
    .form-control-custom:focus { border-color: #3b7ddd; }

    .select-wrapper {
      position: relative;
    }
    .select-wrapper::after {
      content: '\F282';
      font-family: 'Bootstrap-icons';
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
      pointer-events: none;
    }

    textarea.form-control-custom {
      resize: vertical;
      min-height: 90px;
    }

    /* Status checklist */
    .status-list {
      list-style: none;
      padding: 0;
      margin: 0 0 20px;
    }
    .status-list li {
      display: flex;
      align-items: center;
      gap: 9px;
      font-size: 13px;
      color: #444;
      margin-bottom: 6px;
      font-weight: 500;
    }
    .status-list li .ico {
      width: 20px; height: 20px;
      border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px;
      flex-shrink: 0;
    }
    .ico-ok  { background: #1aab6d; color: #fff; }
    .ico-no  { background: #e0e3ea; color: #aaa; }
    .ico-warn{ background: #e07c28; color: #fff; }

    /* Submit button */
    .btn-kirim {
      width: 100%;
      background: #3b7ddd;
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 14px;
      font-size: 15px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .2s, transform .1s;
    }
    .btn-kirim:hover  { background: #2c6bc4; }
    .btn-kirim:active { transform: scale(.98); }

    /* button kembali */
    .btn-kembali {
      width: 100%;
      background: #f4f6f9;
      color: #555;
      border: 1.5px solid #e0e3ea;
      border-radius: 10px;
      padding: 13px;
      font-size: 15px;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      margin-bottom: 10px;
      transition: background .2s, border-color .2s;
    }

    .btn-kembali:hover {
      background: #e8eaf0;
      border-color: #c8ccd4;
      color: #333;
    }

    /* Footer */
    .page-footer {
      text-align: center;
      font-size: 13px;
      color: #b0b8c4;
      padding: 24px 0 32px;
    }

    /* Canvas hidden */
    #canvas { display: none; }
  </style>
</head>
<body>

{{-- ── Header ── --}}
<div class="absensi-header">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1>Absensi <span>Teknisi</span></h1>
        <p>Rekam kehadiran dengan foto &amp; lokasi</p>
      </div>
      <div class="clock-box">
        <div class="time" id="jam">--:--:--</div>
        <div class="date" id="tanggal">--, -- --- ----</div>
      </div>
    </div>
  </div>
</div>

<div class="container pb-5">

  {{-- Late notice --}}
  <div class="late-notice" id="late-notice" style="display:none">
    <i class="bi bi-info-circle-fill" style="color:#e07c28"></i>
    Batas Check-In untuk <strong>{{ auth()->user()->name ?? 'Karyawan' }}</strong>
    adalah pukul <strong>07:30 WIB</strong> - lebih dari jam ini tercatat
    <span class="late-badge">Terlambat</span>
  </div>

  <div class="row g-4">

    {{-- ── KOLOM KIRI ── --}}
    <div class="col-lg-6">

      {{-- Kamera --}}
      <div class="card-section mb-4">
        <div class="card-section-header">
          <div class="title"><i class="bi bi-camera"></i> Kamera Selfie</div>
          <span class="badge-status badge-ok" id="badge-foto" style="display:none">Foto Diambil</span>
        </div>
        <div class="camera-wrapper">
          <video id="video" autoplay playsinline></video>
          <img id="preview-img" alt="Preview Foto">
          <div class="foto-taken-overlay" id="foto-overlay">
            <i class="bi bi-check"></i> Foto Diambil
          </div>
        </div>
        <canvas id="canvas"></canvas>
        <button class="btn-ulangi" id="btn-ulangi" onclick="ulangiKamera()" style="display:none">
          <i class="bi bi-arrow-clockwise"></i> Ulangi
        </button>
        <button class="btn-ulangi" id="btn-ambil-foto" onclick="ambilFoto()">
          <i class="bi bi-camera-fill"></i> Ambil Foto
        </button>
      </div>

      {{-- Lokasi GPS --}}
      <div class="card-section">
        <div class="card-section-header">
          <div class="title"><i class="bi bi-geo-alt"></i> Lokasi GPS</div>
          <span class="badge-status badge-idle" id="badge-lokasi">Belum Dideteksi</span>
        </div>

        {{-- Info lokasi (muncul setelah detect) --}}
        <div class="lokasi-body" id="lokasi-info" style="display:none">
          <div class="lokasi-grid">
            <div class="lokasi-item">
              <label>Latitude</label>
              <span id="lat-val">-</span>
            </div>
            <div class="lokasi-item">
              <label>Longitude</label>
              <span id="lon-val">-</span>
            </div>
            <div class="lokasi-item">
              <label>Akurasi</label>
              <span id="akurasi-val">-</span>
            </div>
            <div class="lokasi-item full">
              <label>Alamat</label>
              <span class="alamat-val" id="alamat-val">Mengambil alamat...</span>
            </div>
          </div>
        </div>

        {{-- Peta --}}
        <div id="map-container">
          <div id="map-placeholder">
            <i class="bi bi-map" style="font-size:20px"></i>
            Peta akan muncul setelah lokasi dideteksi
          </div>
          <iframe id="map-iframe" src="" style="display:none"></iframe>
        </div>

        {{-- Tombol --}}
        <button class="btn-ambil-lokasi" id="btn-lokasi" onclick="ambilLokasi()">
          <i class="bi bi-geo-alt-fill"></i> Deteksi Lokasi
        </button>
        <div class="btn-lokasi-detected" id="lokasi-detected-bar" style="display:none">
          <i class="bi bi-check-circle-fill"></i> Lokasi Terdeteksi
        </div>
      </div>

    </div>{{-- /KIRI --}}

    {{-- ── KOLOM KANAN ── --}}
    <div class="col-lg-6">
      <div class="card-section">
        <div class="card-section-header">
          <div class="title"><i class="bi bi-clipboard-check"></i> Form Absensi</div>
        </div>
        <div class="form-body">

          <form method="POST" action="{{ route('absensi.store') }}" id="form-absensi">
            @csrf
            <input type="hidden" name="latitude"  id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="alamat"    id="alamat_input">
            <input type="hidden" name="foto"      id="foto">

            {{-- Nama --}}
            <div class="form-group">
              <label>Nama Karyawan</label>
              <input type="text" class="form-control-custom"
                     value="{{ auth()->user()->nama ?? '' }}" readonly>
            </div>

            {{-- Jenis --}}
            <div class="form-group">
              <label>Jenis Absensi</label>
              <div class="select-wrapper">
                <select name="jenis" class="form-control-custom" id="jenis-absensi" required>
                  <option value="" disabled selected>-- Pilih Jenis --</option>
                  <option value="Hadir">Hadir</option>
                  <option value="Pulang">Pulang</option>
                  <option value="Izin">Izin</option>
                  <option value="Sakit">Sakit</option>
                </select>
              </div>
            </div>

            {{-- Catatan --}}
            <div class="form-group">
              <label>Catatan <span>(opsional)</span></label>
              <textarea name="catatan" class="form-control-custom"
                        placeholder="Tambahkan catatan jika ada..."></textarea>
            </div>

            {{-- Status checklist --}}
            <ul class="status-list">
              <li>
                <span class="ico ico-no" id="ico-foto">✓</span>
                <span id="txt-foto">Foto belum diambil</span>
              </li>
              <li>
                <span class="ico ico-no" id="ico-jarak">✓</span>
                <span id="txt-jarak">Lokasi belum dideteksi</span>
              </li>
              <li>
                <span class="ico ico-ok" id="ico-waktu">✓</span>
                <span id="txt-waktu">Waktu: --:--:--</span>
              </li>
            </ul>

            <a href="/data-ac-rsal" class="btn-kembali">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn-kirim">
              <i class="bi bi-send-check-fill"></i> Kirim Absensi
            </button>
          </form>

        </div>
      </div>
    </div>{{-- /KANAN --}}

  </div>{{-- /row --}}
</div>

<div class="page-footer">
  P.T Sarana Agung Sejahtera - 2025 ©
</div>

{{-- Scripts --}}

<script>
  // ── Globals ─────────────────────────────────────────
  let video  = document.getElementById('video');
  let stream = null;
  let fotoOk = false;
  let lokasiOk = false;

  // Koordinat kantor (sesuaikan dengan lokasi kantor)
  const KANTOR_LIST = [
    { nama: 'Kantor 1', lat: -7.34316, lon: 112.77119, radius: 50 },
    { nama: 'Kantor 2', lat: -7.311139, lon: 112.737250, radius: 50 },
  ];

  document.addEventListener('DOMContentLoaded', function () {
    initJam();
    startCamera();
    initFormValidation();
    cekLate();
  });

  // ── Jam Real Time ────────────────────────────────────
  function initJam() {
    const hariList  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const bulanList = ['Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

    function update() {
      const now  = new Date();
      document.getElementById('jam').innerText = now.toLocaleTimeString('id-ID');
      const h = hariList[now.getDay()], b = bulanList[now.getMonth()];
      document.getElementById('tanggal').innerText =
        `${h}, ${now.getDate()} ${b} ${now.getFullYear()}`;
      document.getElementById('txt-waktu').innerText =
        'Waktu: ' + now.toLocaleTimeString('id-ID');
    }
    update();
    setInterval(update, 1000);
  }

  // ── Cek Terlambat ────────────────────────────────────
  function cekLate() {
    const now = new Date();
    const batas = new Date();
    batas.setHours(7, 30, 0, 0);
    if (now > batas) {
      document.getElementById('late-notice').style.display = 'flex';
    }
  }

  // ── Kamera ───────────────────────────────────────────
  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user' }, audio: false
      });
      video.srcObject = stream;
    } catch (err) {
      alert('Kamera tidak bisa diakses: ' + err.message);
    }
  }

  function ambilFoto() {
    const canvas = document.getElementById('canvas');
    const prev   = document.getElementById('preview-img');

    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    prev.src = dataUrl;

    // Tampilkan preview, sembunyikan live video
    video.style.display = 'none';
    prev.style.display  = 'block';
    document.getElementById('foto-overlay').style.display = 'block';
    document.getElementById('badge-foto').style.display   = 'inline-block';
    document.getElementById('btn-ambil-foto').style.display = 'none';
    document.getElementById('btn-ulangi').style.display     = 'flex';

    // Simpan ke form
    document.getElementById('foto').value = dataUrl;
    fotoOk = true;

    // Update checklist
    setStatus('ico-foto', 'ok');
    document.getElementById('txt-foto').innerText = 'Foto sudah diambil ✓';
  }

  function ulangiKamera() {
    const prev = document.getElementById('preview-img');
    video.style.display = 'block';
    prev.style.display  = 'none';
    document.getElementById('foto-overlay').style.display = 'none';
    document.getElementById('badge-foto').style.display   = 'none';
    document.getElementById('btn-ambil-foto').style.display = 'flex';
    document.getElementById('btn-ulangi').style.display     = 'none';

    document.getElementById('foto').value = '';
    fotoOk = false;

    setStatus('ico-foto', 'no');
    document.getElementById('txt-foto').innerText = 'Foto belum diambil';
  }

  // ── Lokasi ───────────────────────────────────────────
  function ambilLokasi() {
    if (!navigator.geolocation) {
      alert('Browser tidak mendukung Geolocation.');
      return;
    }

    const btnLokasi = document.getElementById('btn-lokasi');
    btnLokasi.disabled = true;
    btnLokasi.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi...`;

    navigator.geolocation.getCurrentPosition(
      async function (pos) {
        const lat  = pos.coords.latitude;
        const lon  = pos.coords.longitude;
        const akurasi = pos.coords.accuracy;

        // Isi hidden inputs
        document.getElementById('latitude').value  = lat;
        document.getElementById('longitude').value = lon;

        // Tampilkan info
        document.getElementById('lokasi-info').style.display = 'block';
        document.getElementById('lat-val').innerText  = lat.toFixed(6);
        document.getElementById('lon-val').innerText  = lon.toFixed(6);
        document.getElementById('akurasi-val').innerText = Math.round(akurasi) + ' m';

        // Badge
        document.getElementById('badge-lokasi').className = 'badge-status badge-ok';
        document.getElementById('badge-lokasi').innerText = 'Terdeteksi';

        // Peta (Google Maps Embed)
        document.getElementById('map-placeholder').style.display = 'none';
        const iframe = document.getElementById('map-iframe');
        iframe.src = `https://maps.google.com/maps?q=${lat},${lon}&z=17&output=embed`;
        iframe.style.display = 'block';

        // Tombol
        btnLokasi.style.display = 'none';
        document.getElementById('lokasi-detected-bar').style.display = 'flex';

        // Hitung jarak ke kantor
        let jarakTerdekat   = Infinity;
        let kantorTerdekat  = null;
        
        KANTOR_LIST.forEach(kantor => {
          const jarak = hitungJarak(lat, lon, kantor.lat, kantor.lon);
          if (jarak < jarakTerdekat) {
            jarakTerdekat   = jarak;
            kantorTerdekat  = kantor; 
          }
        });

        const jarakTeks = jarakTerdekat < 1000
          ? Math.round(jarakTerdekat) + ' m'
          : (jarakTerdekat / 1000).toFixed(1) + ' km';
        
        const dalamRadius = jarakTerdekat <= kantorTerdekat.radius;
        setStatus('ico-jarak', dalamRadius ? 'ok' : 'warn');
        document.getElementById('txt-jarak').innerText = 
          `Jarak ke ${kantorTerdekat.nama}: ${jarakTeks}` + 
          (dalamRadius ? ' ✓' : ' ✗ (di luar radius)');
        
        lokasiOk = dalamRadius;

        // Reverse geocoding
        try {
          const res  = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`
          );
          const data = await res.json();
          const alamat = data.display_name || `${lat}, ${lon}`;
          document.getElementById('alamat-val').innerText  = alamat;
          document.getElementById('alamat_input').value    = alamat;
        } catch {
          const fb = `${lat}, ${lon}`;
          document.getElementById('alamat-val').innerText = fb;
          document.getElementById('alamat_input').value   = fb;
        }
      },
      function (err) {
        btnLokasi.disabled = false;
        btnLokasi.innerHTML = `<i class="bi bi-geo-alt-fill"></i> Deteksi Lokasi`;
        alert('Gagal mendapatkan lokasi: ' + err.message);
      },
      { enableHighAccuracy: true }
    );
  }

  // Haversine distance (meter)
  function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 +
              Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) *
              Math.sin(dLon/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  }

  // ── Checklist helper ─────────────────────────────────
  function setStatus(id, state) {
    const el = document.getElementById(id);
    el.className = 'ico';
    if (state === 'ok')   el.classList.add('ico-ok');
    if (state === 'no')   el.classList.add('ico-no');
    if (state === 'warn') el.classList.add('ico-warn');
  }

  // fungsi ambil waktu
  function getWaktuSekarang() {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2, '0');
    const mm = String(now.getMinutes()).padStart(2, '0');
    const ss = String(now.getSeconds()).padStart(2, '0');
    return `${hh}:${mm}:${ss}`;
  }

  // validasi & submit ajax
  function initFormValidation() {
    document.getElementById('form-absensi').addEventListener('submit', async function (e) {
      e.preventDefault();

      if (!fotoOk) {
        Swal.fire({ icon: 'warning', title: 'Foto belum diambil',
          text: 'Silahkan ambil foto selfie terlebih dahulu.' });
        return;
      }
      if (!lokasiOk) {
        Swal.fire({ icon: 'warning', title: 'Lokasi belum dideteksi',
          text: 'Silahkan deteksi lokasi GPS terlebih dahulu.'});
        return;
      }
      const jenis = document.getElementById('jenis-absensi').value;
      if (!jenis) {
        Swal.fire({ icon: 'warning', title: 'Pilih Jenis Absensi',
          text: 'Silahkan pilih jenis absensi terlebih dahulu.'});
        return;
      }

      // tombol loading
      const btnKirim = document.querySelector('.btn-kirim');
      btnKirim.disabled = true;
      btnKirim.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...`;

      const form = document.getElementById('form-absensi');
      const payload = new FormData(form);
      payload.set('waktu', getWaktuSekarang());

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value },
          body: payload,
        });

        const data = await res.json();

        if (data.success) {
          let pesan = 'Absensi berhasil dicatat';
          if (data.status === 'Terlambat') {
            pesan = 'Absensi tercatat - anda terlambat ${data.menit_terlambat} menit.';
          }

          await Swal.fire({ icon:'success', title: 'Berhasil', text: pesan });

          fotoOk = false;
          lokasiOk = false;

          document.getElementById('foto').value         = '';
          document.getElementById('preview-img').src    = '';
          document.getElementById('preview-img').style.display = 'none';
          document.getElementById('video').style.display = 'block';
          document.getElementById('foto-overlay').style.display = 'none';
          document.getElementById('badge-foto').style.display = 'none';
          document.getElementById('btn-ambil-foto').style.display = 'flex';
          document.getElementById('btn-ulangi').style.display = 'none';
          setStatus('ico-foto', 'no');
          document.getElementById('txt-foto').innerText = 'Foto belum diambil';
        } else {
          Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
          btnKirim.disabled = false;
          btnKirim.innerHTML = `<i class="bi bi-send-check-fill"></i> Kirim Absensi`;
        }

      } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'terjadi kesalahan jaringan.'});
        btnKirim.disabled = false;
        btnKirim.innerHTML = `<i class="bi bi-send-check-fill"></i> Kirim Absensi`;
      }
    });
  }
</script>
</body>
</html>