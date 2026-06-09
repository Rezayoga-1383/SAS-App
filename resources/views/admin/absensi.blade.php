@extends('admin.template.main')

@section('title', 'Absensi Karyawan - SAS')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-0"><strong>Absensi</strong> Admin</h1>
                <p class="text-muted mb-0 small">Rekam kehadiran dengan foto &amp; lokasi</p>
            </div>
            <div class="absensi-clock-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="clock-icon-wrap">
                        <i data-feather="clock" style="width:18px;height:18px;color:#3b7ddd;"></i>
                    </div>
                    <div>
                        <div id="global-time" class="clock-time">--:--:--</div>
                        <div id="global-date" class="clock-date">Memuat tanggal...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">

            {{-- Kolom Kiri: Kamera + Lokasi --}}
            <div class="col-12 col-lg-6">

                {{-- Card Kamera --}}
                <div class="card mb-3 shadow-sm overflow-hidden">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i data-feather="camera" class="me-2" style="width:18px;height:18px;"></i>
                            Kamera Selfie
                        </h5>
                        <span id="camera-status-badge" class="badge bg-secondary">Kamera Mati</span>
                    </div>

                    {{-- Camera full-bleed (no card-body) --}}
                    <div class="camera-container" id="camera-box">
                        <video id="camera-preview" autoplay playsinline muted class="camera-video"
                               style="display:none;"></video>
                        <canvas id="camera-canvas" style="display:none;"></canvas>
                        <div id="camera-placeholder" class="camera-placeholder">
                            <i data-feather="camera-off" style="width:48px;height:48px;opacity:.3;"></i>
                            <p class="mt-2 mb-0 small">Kamera belum aktif</p>
                        </div>
                        <img id="captured-photo" class="camera-video" alt="Foto Absensi"
                             style="display:none;">
                    </div>

                    {{-- Tombol di card-footer --}}
                    <div class="card-footer bg-white border-top py-2 px-3 d-flex gap-2">
                        <button id="btn-start-camera" class="btn btn-outline-primary btn-sm flex-fill"
                                onclick="startCamera()">
                            <i data-feather="video" class="me-1" style="width:14px;height:14px;"></i>
                            Aktifkan Kamera
                        </button>
                        <button id="btn-capture" class="btn btn-primary btn-sm flex-fill"
                                onclick="capturePhoto()" style="display:none;">
                            <i data-feather="aperture" class="me-1" style="width:14px;height:14px;"></i>
                            Ambil Foto
                        </button>
                        <button id="btn-retake" class="btn btn-warning btn-sm flex-fill"
                                onclick="retakePhoto()" style="display:none;">
                            <i data-feather="refresh-cw" class="me-1" style="width:14px;height:14px;"></i>
                            Ulangi
                        </button>
                    </div>
                </div>

                {{-- Card Lokasi --}}
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i data-feather="map-pin" class="me-2" style="width:18px;height:18px;"></i>
                            Lokasi GPS
                        </h5>
                        <span id="location-badge" class="badge bg-secondary">Belum Dideteksi</span>
                    </div>
                    <div class="card-body">
                        <div id="location-info" class="location-info-box d-none">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <div class="location-item">
                                        <span class="text-muted d-block">Latitude</span>
                                        <strong id="loc-lat">-</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="location-item">
                                        <span class="text-muted d-block">Longitude</span>
                                        <strong id="loc-lng">-</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="location-item">
                                        <span class="text-muted d-block">Akurasi</span>
                                        <strong id="loc-acc">-</strong>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="location-item">
                                        <span class="text-muted d-block">Alamat</span>
                                        <strong id="loc-addr">Memuat alamat...</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="location-placeholder" class="text-center py-3 text-muted small">
                            <i data-feather="navigation" style="width:36px;height:36px;opacity:.3;"></i>
                            <p class="mt-1 mb-0">Lokasi belum terdeteksi</p>
                        </div>
                        <div id="map-container" class="mt-2 d-none">
                            <iframe id="map-frame" class="absensi-map" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <button id="btn-get-location" class="btn btn-outline-success btn-sm w-100 mt-2" onclick="getLocation()">
                            <i data-feather="navigation" class="me-1" style="width:15px;height:15px;"></i>
                            Deteksi Lokasi
                        </button>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Form + Riwayat --}}
            <div class="col-12 col-lg-6">

                @php
                    $user   = auth()->user();
                    $role   = $user->role;
                    $jamMasuk   = config('absensi.jam_masuk')[$role] ?? '07:30';
                    $toleransi  = (int) config('absensi.toleransi_menit', 0);
                @endphp
                {{-- Info Jam Masuk --}}
                <div class="alert alert-info py-2 px-3 small mb-3 d-flex align-items-center gap-2">
                    <i data-feather="info" style="width: 15px;height:15px;flex-shrink:0;"></i>
                    <span>
                        Batas Check-In untuk
                        <strong>{{ ucfirst($role) }}</strong>
                        adalah pukul <strong>{{ $jamMasuk }} WIB</strong>
                        @if ($toleransi > 0)
                            <span class="text-muted">(toleransi {{ $toleransi }} menit)</span>
                        @endif
                        - lebih dari jam ini tercatat
                        <span class="text-danger fw-semibold">Terlambat</span>.
                    </span>
                </div>

                {{-- Card Form Absensi --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i data-feather="clipboard" class="me-2" style="width:18px;height:18px;"></i>
                            Form Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="form-absensi" onsubmit="submitAbsensi(event)">
                            @csrf
                            <input type="hidden" id="field-foto"  name="foto">
                            <input type="hidden" id="field-lat"   name="latitude">
                            <input type="hidden" id="field-lng"   name="longitude">
                            <input type="hidden" id="field-addr"  name="alamat">
                            <input type="hidden" id="field-waktu" name="waktu">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Karyawan</label>
                                <input type="text" class="form-control"
                                    value="{{ $user->nama }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Absensi</label>
                                <select name="jenis" id="field-jenis" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Pulang">Pulang</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Catatan <span class="text-muted fw-normal">(opsional)</span>
                                </label>
                                <textarea name="catatan" class="form-control" rows="2"
                                    placeholder="Tambahkan catatan jika ada..."></textarea>
                            </div>

                            {{-- Checklist Validasi --}}
                            <div class="absensi-checklist mb-3">
                                <div class="check-item" id="chk-foto">
                                    <span class="check-icon">⬜</span>
                                    <span>Foto belum diambil</span>
                                </div>
                                <div class="check-item" id="chk-lokasi">
                                    <span class="check-icon">⬜</span>
                                    <span>Lokasi belum terdeteksi</span>
                                </div>
                                <div class="check-item" id="chk-waktu">
                                    <span class="check-icon">⬜</span>
                                    <span>Waktu belum dicatat</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="btn-submit" disabled>
                                <i data-feather="check-circle" class="me-1" style="width:16px;height:16px;"></i>
                                Kirim Absensi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('style')
<style>
/* ── CLOCK CARD ── */
.absensi-clock-card {
    background: linear-gradient(135deg, #1a1f3c 0%, #2d3561 100%);
    border-radius: 12px;
    padding: 10px 16px;
    color: #fff;
    box-shadow: 0 4px 15px rgba(59,125,221,.25);
    flex-shrink: 0;
}
.clock-icon-wrap {
    background: rgba(255,255,255,.12);
    border-radius: 50%;
    width: 38px; height: 38px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.clock-time {
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: 2px;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
.clock-date { font-size: .7rem; opacity: .75; letter-spacing: .5px; white-space: nowrap; }

/* ── CAMERA ── */
.camera-container {
    position: relative;
    width: 100%;
    height: 300px;          /* tinggi tetap, fill card */
    background: #0d1117;
    overflow: hidden;
    /* full-bleed: tidak ada border-radius di sini,
       card parent sudah overflow:hidden */
}
.camera-video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1);  /* mirror selfie */
}
.camera-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #8a9bb0;
    background: #0d1117;
    pointer-events: none;
}

/* ── LOCATION ── */
.location-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 7px 10px;
}
.absensi-map {
    width: 100%;
    height: 200px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    display: block;
}

/* ── CHECKLIST ── */
.absensi-checklist {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.check-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .82rem;
}
.check-item.done { color: #198754; }

/* ── MODAL ── */
.success-anim { font-size: 3rem; animation: bounce .5s ease; }
@keyframes bounce {
    0%,100% { transform: scale(1); }
    50%      { transform: scale(1.3); }
}
.shutter-flash {
    position: absolute; inset: 0;
    background: #fff;
    border-radius: 8px;
    opacity: 0;
    animation: shutter .35s ease forwards;
    pointer-events: none;
    z-index: 10;
}
@keyframes shutter {
    0%   { opacity: 1; }
    100% { opacity: 0; }
}

/* ── RESPONSIVE ── */
@media (max-width: 575.98px) {
    .absensi-clock-card { padding: 8px 12px; }
    .clock-time { font-size: 1.15rem; letter-spacing: 1px; }
    .clock-date { font-size: .65rem; }
    .clock-icon-wrap { width: 32px; height: 32px; }
    .camera-container { height: 220px; }
    .absensi-map { height: 140px; }
}
@media (min-width: 576px) and (max-width: 991.98px) {
    .camera-container { height: 260px; }
    .absensi-map { height: 180px; }
}
@media (min-width: 992px) {
    .camera-container { height: 320px; }
    .absensi-map { height: 220px; }
}
</style>
@endpush

@push('script')
<script>
// ============================================================
// JAM GLOBAL
// ============================================================
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

// Radius Kantor
const OFFICE_LAT = -7.34316;
const OFFICE_LNG = 112.77119;
const MAX_RADIUS = 50;


function updateClock() {
    const now = new Date();
    const hh  = String(now.getHours()).padStart(2,'0');
    const mm  = String(now.getMinutes()).padStart(2,'0');
    const ss  = String(now.getSeconds()).padStart(2,'0');
    document.getElementById('global-time').textContent = `${hh}:${mm}:${ss}`;
    document.getElementById('global-date').textContent =
        `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
    const wkt = `${hh}:${mm}:${ss}`;
    document.getElementById('field-waktu').value = wkt;
    setCheck('chk-waktu', true, `Waktu: ${wkt}`);
    checkSubmitReady();
}
setInterval(updateClock, 1000);
updateClock();

// ============================================================
// KAMERA
// ============================================================
let stream = null;

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode:'user' }, audio: false });
        const video = document.getElementById('camera-preview');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('camera-placeholder').style.display  = 'none';
        document.getElementById('captured-photo').style.display       = 'none';
        document.getElementById('btn-start-camera').style.display     = 'none';
        document.getElementById('btn-capture').style.display          = 'flex';
        document.getElementById('btn-retake').style.display           = 'none';
        document.getElementById('camera-status-badge').className = 'badge bg-success';
        document.getElementById('camera-status-badge').textContent = 'Kamera Aktif';
        feather.replace();
    } catch(e) {
        const msg = e.name === 'NotAllowedError'
            ? 'Izin kamera ditolak. Silakan izinkan akses kamera di browser Anda.'
            : 'Kamera tidak dapat diakses: ' + e.message;
        alert(msg);
    }
}

function capturePhoto() {
    const video  = document.getElementById('camera-preview');
    const canvas = document.getElementById('camera-canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0);

    const flash = document.createElement('div');
    flash.className = 'shutter-flash';
    document.querySelector('.camera-container').appendChild(flash);
    setTimeout(() => flash.remove(), 400);

    const dataURL = canvas.toDataURL('image/jpeg', .85);
    const photo = document.getElementById('captured-photo');
    photo.src = dataURL;
    photo.style.display = 'block';
    video.style.display = 'none';
    document.getElementById('btn-capture').style.display      = 'none';
    document.getElementById('btn-retake').style.display       = 'flex';
    document.getElementById('field-foto').value = dataURL;

    if (stream) stream.getTracks().forEach(t => t.stop());
    stream = null;

    document.getElementById('camera-status-badge').className = 'badge bg-info';
    document.getElementById('camera-status-badge').textContent = 'Foto Diambil';
    setCheck('chk-foto', true, 'Foto sudah diambil ✓');
    checkSubmitReady();
}

function retakePhoto() {
    document.getElementById('captured-photo').style.display      = 'none';
    document.getElementById('camera-preview').style.display      = 'none';
    document.getElementById('btn-retake').style.display          = 'none';
    document.getElementById('btn-capture').style.display         = 'none';
    document.getElementById('btn-start-camera').style.display    = 'flex';
    document.getElementById('camera-placeholder').style.display  = 'flex';
    document.getElementById('camera-status-badge').className = 'badge bg-secondary';
    document.getElementById('camera-status-badge').textContent = 'Kamera Mati';
    document.getElementById('field-foto').value = '';
    setCheck('chk-foto', false, 'Foto belum diambil');
    checkSubmitReady();
    feather.replace();
}

// ============================================================
// LOKASI GPS
// ============================================================
function getLocation() {
    const btn = document.getElementById('btn-get-location');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';

    if (!navigator.geolocation) {
        alert('Browser tidak mendukung geolokasi.');
        btn.disabled = false;
        return;
    }

    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);

            const accValue = pos.coords.accuracy;
            const acc = accValue.toFixed(0) + ' m';

            const isMobile = /Android|iphone|ipad|ipod/i.test(navigator.userAgent);

            const MAX_ACCURACY = isMobile ? 100 : 150;

            if (accValue > MAX_ACCURACY) {
                Swal.fire({
                    icon: 'warning',
                    title: 'GPS kurang akurat',
                    text: `Akurasi ${acc}. Maksimal ${MAX_ACCURACY} meter untuk device ini.`
                });

                btn.disabled = false;
                btn.innerHTML = '<i data-feather="navigation" class="me-1"></i> Coba Lagi';

                if (window.feather) feather.replace();

                return;
            }

            const jarak = hitungJarak(
                parseFloat(lat),
                parseFloat(lng),
                OFFICE_LAT,
                OFFICE_LNG
            );

            console.log("jarak ke kantor:", jarak.toFixed(2), "meter");

            if (jarak > MAX_RADIUS) {
                Swal.fire({
                    icon: 'error',
                    title: 'Di luar area!',
                    text: `Anda berada ${Math.round(jarak)} meter dari kantor. Maksimal ${MAX_RADIUS} meter.`
                });

                document.getElementById('field-lat').value = '';
                document.getElementById('field-lng').value = '';
                document.getElementById('field-addr').value = '';

                setCheck('chk-lokasi', false, 'Lokasi di luar area!');
                checkSubmitReady();

                btn.disabled = false;
                btn.innerHTML = '<i data-feather="navigation" class="me-1"></i> Coba Lagi';
                feather.replace();

                return;
            }

            document.getElementById('loc-lat').textContent = lat;
            document.getElementById('loc-lng').textContent = lng;
            document.getElementById('loc-acc').textContent = acc;
            document.getElementById('field-lat').value = lat;
            document.getElementById('field-lng').value = lng;

            document.getElementById('location-placeholder').classList.add('d-none');
            document.getElementById('location-info').classList.remove('d-none');
            document.getElementById('map-container').classList.remove('d-none');
            document.getElementById('map-frame').src =
                `https://maps.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
            document.getElementById('location-badge').className = 'badge bg-success';
            document.getElementById('location-badge').textContent = 'Terdeteksi';

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(r => r.json())
                .then(d => {
                    const addr = d.display_name || `${lat}, ${lng}`;
                    document.getElementById('loc-addr').textContent = addr;
                    document.getElementById('field-addr').value = addr;
                }).catch(() => {
                    const fallback = `${lat}, ${lng}`;
                    document.getElementById('loc-addr').textContent = fallback;
                    document.getElementById('field-addr').value = fallback;
                });

            setCheck('chk-lokasi', true, `Jarak ke kantor: ${Math.round(jarak)} m`);
            checkSubmitReady();
            btn.disabled = false;
            btn.innerHTML = '<i data-feather="check" style="width:15px;height:15px;" class="me-1"></i> Lokasi Terdeteksi';
            feather.replace();
        },
        err => {
            const pesanError = {
                1: 'Izin lokasi ditolak, Silakan izinkan akses lokasi di browser Anda.',
                2: 'Posisi tidak dapat ditentukan. Pastikan GPS aktif.',
                3: 'Waktu deteksi habis. Coba lagi.',
            };
            
            Swal.fire({
                icon: 'error',
                title: 'Lokasi Gagal',
                text: pesanError[err.code] ?? err.message
            });

            btn.disabled = false;
            btn.innerHTML = '<i data-feather="navigation" style="width:15px;height:15px;" class="me-1"></i> Coba Lagi';
            feather.replace();
        },
        { enableHighAccuracy: true, timeout: 15000 }
    );
}

// ============================================================
// CHECKLIST & VALIDASI
// ============================================================
function setCheck(id, done, text) {
    const el = document.getElementById(id);
    if (!el) return;
    el.querySelector('.check-icon').textContent = done ? '✅' : '⬜';
    el.querySelector('span:last-child').textContent = text;
    el.classList.toggle('done', done);
}

function checkSubmitReady() {
    const foto  = document.getElementById('field-foto').value;
    const lat   = document.getElementById('field-lat').value;
    const waktu = document.getElementById('field-waktu').value;
    const jenis = document.getElementById('field-jenis').value;

    document.getElementById('btn-submit').disabled = !(foto && lat && waktu && jenis);
}

function tampilkanModal(isLate, menit, jamMasuk, jenis, waktu) {

    const menitBulat = Math.floor(menit);

    const icon  = isLate ? 'warning' : 'success';
    const title = isLate ? 'Absensi Terlambat!' : 'Absensi Berhasil';

    const text = isLate
        ? `Terlambat ${menitBulat} menit dari jam ${jamMasuk}`
        : `${jenis} - ${waktu}`;

    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        timer: 3000,
        showConfirmButton: false
    });
}

// ============================================================
// SUBMIT ABSENSI
// ============================================================
async function submitAbsensi(e) {
    e.preventDefault();
    const form  = document.getElementById('form-absensi');
    const jenis = form.querySelector('[name="jenis"]').value;

    if (!jenis) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Pilih jenis absensi terlebih dahulu!'
        });
        return
    }

    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

    try {
        const formData = new FormData(form);

        const res  = await fetch('{{ route("admin.absensi.store") }}', {
            method : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Accept'      : 'application/json',
            },
            body: formData,
        });

        const data = await res.json();

        // error handling
        if (!res.ok) {
            let pesan = '';

            if (data.errors) {
                pesan = Object.values(data.errors)
                    .map(e => e[0])
                    .join('<br>');
            }
            else if (data.message) {
                pesan = data.message;
            }
            else {
                pesan = 'Terjadi kesalahan server';
            }

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: pesan
            });

            return;
        }

        if (data.success) {
            // Tampilkan modal dengan benar (tanpa aria-hidden conflict)
            tampilkanModal(
                data.status === 'Terlambat',
                data.menit_terlambat,
                data.jam_masuk,
                jenis,
                document.getElementById('field-waktu').value
            );

            setTimeout(() => {
                location.reload();
            }, 1500);

            resetForm();

        } else {
           Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: data.message || 'Terjadi kesalahan'
           });
        }

    } catch (err) {
        console.error(err);

        Swal.fire({
            icon: 'error',
            title: 'Koneksi Error',
            text: 'Tidak dapat terhubung ke server'
        });

    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="check-circle" class="me-1" style="width:16px;height:16px;"></i> Kirim Absensi';
        feather.replace();
        checkSubmitReady();
    }
}

// ============================================================
// RESET FORM
// ============================================================
function resetForm() {
    const form = document.getElementById('form-absensi');
    form.querySelector('[name="jenis"]').value   = '';
    form.querySelector('[name="catatan"]').value = '';
    document.getElementById('field-lat').value  = '';
    document.getElementById('field-lng').value  = '';
    document.getElementById('field-addr').value = '';

    retakePhoto();

    document.getElementById('location-placeholder').classList.remove('d-none');
    document.getElementById('location-info').classList.add('d-none');
    document.getElementById('map-container').classList.add('d-none');
    document.getElementById('location-badge').className = 'badge bg-secondary';
    document.getElementById('location-badge').textContent = 'Belum Dideteksi';
    document.getElementById('btn-get-location').disabled = false;
    document.getElementById('btn-get-location').innerHTML =
        '<i data-feather="navigation" class="me-1" style="width:15px;height:15px;"></i> Deteksi Lokasi';

    setCheck('chk-lokasi', false, 'Lokasi belum terdeteksi');
    checkSubmitReady();
    feather.replace();
}

// Hitung Jarak Radius
function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000;

    const dLat = (lat2 - lat1) * Math.PI /180;
    const dLon = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c;
}
</script>
@endpush