@extends('superadmin.template.main')

@section('title', 'Absensi - SAS')

@push('style')
    <style>
        #last-updated {
            font-size: 0.8rem;
            color: #6c757d;
            white-space: nowrap;
        }

        #refresh-indicator {
            display: none;
        }

        #refresh-indicator.show {
            display: inline-block;
        }

        .attendance-photo {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .photo-placeholder {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .countdown-bar {
            height: 3px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            width: 180px;
            max-width: 180px;
        }

        .countdown-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #0d6efd, #0dcaf0);
            transition: width 1s linear;
        }

        /* Table */
        #tabelAbsensi thead th {
            white-space: nowrap;
            vertical-align: middle;
            font-size: 0.82rem;
        }

        #tabelAbsensi tbody td {
            vertical-align: middle;
            font-size: 0.82rem;
        }


        /* Modal */
        #modalDetail .modal-body {
            overflow-y: auto;
            max-height: 75vh;
        }

        /* header wrapper */
        .absensi-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .absensi-header .card-title {
            flex: 1 1 160px;
            margin: 0;
            font-size: 1rem;
        }

        .absensi-header-right {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        /* pastikan input tanggal tidak overflow */
        #filterTanggal {
            min-width: 130px;
            max-width: 160px;
            width: auto;
            flex-shrink: 0;
        }

        /* Datatables: pagination fix */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.5rem;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control,
        table.dataTable.dtr-inline.collapsed > tbody > tr >th.dtr-control {
            cursor: pointer;
        }
        
        #tabelAbsensi th:last-child,
        #tabelAbsensi td:last-child {
            white-space: nowrap;
        }

        /* Modal POP UP  */
        #modalDetail.modal .modal-backdrop,
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }

        #modalDetail .modal-dialog {
            max-width: 580px;
        }

        #modalDetail .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 16px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Header Modal */
        #modalDetail .modal-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            border-bottom: none;
            padding: 1.1rem 1.4rem;
        }

        #modalDetail .modal-header .modal-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #modalDetail .modal-header .modal-title svg,
        #modalDetail .modal-header .modal-title i {
            opacity: 0.9;
        }

        #modalDetail .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        #modalDetail .modal-header .btn-close:hover {
            opacity: 1;
        }

        /* Body Modal */
        #modalDetail .modal-body {
            padding: 1.4rem;
            overflow-y: auto;
            max-height: 70vh;
            background: #fff;
        }

        /* Info Card karyawan */
        .modal-info-card {
            background: #f8faff;
            border: 1px solid #e3eaff;
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.1rem;
        }

        .modal-info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .modal-info-item .label {
            font-size: 0.72rem;
            color: #8a97b0;
            text-transform: uppercase;
            letter-spacing: 0.04cm;
            font-weight: 600;
        }

        .modal-info-item .value {
            font-size: 0.9rem;
            color: #1a2540;
            font-weight: 500;
        }

        .modal-info-divider {
            border: none;
            border-top: 1px solid #e8eef8;
            margin: 0.75rem 0;
        }

        /* section foto */
        .modal-section-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #5a6a85;
            text-transform: uppercase;
            letter-spacing: 0.05cm;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 0.6rem;
        }

        /* footer modal */
        #modalDetail .modal-footer {
            border-top: 1px solid #eef1f7;
            padding: 0.85rem 1.4rem;
            background: #fafbff;
        }

        #modalDetail .modal-footer .btn-secondary {
            background: #e9edf5;
            border: none;
            color: #4a5568;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 0.45rem 1.1rem;
            transition: background 0.15s;;
        }

        #modalDetail .modal-footer .btn-secondary:hover {
            background: #d8dde8;
        }

        /* Tombol google maps */
        #link-maps {
            border-radius: 8px;
            font-size: 0.83rem;
            padding: 0.4rem 1rem;
            border-color: #c5cfe0;
            color: #4a5568;
            transition: all 0.15s;
        }

        #link-maps:hover {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        /* animasi masuk modal */
        #modalDetail .modal-dialog {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
            transform: scale(0.92) translateY(-12px);
            opacity: 0;
        }

        #modalDetail.show .modal-dialog {
            transform; scale(1) translateY(0);
            opacity: 1;
        }
 
        /* Mobile < 575px */
        @media (max-width: 575.98px) {
            .absensi-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .absensi-header .card-title {
                font-size: 0.92rem;
            }
            .absensi-header-right {
                width: 100%;
                flex-wrap: wrap;
            }

            /* Status update + bar kiri spinner + input kanan */
            .absensi-header-right .status-block {
                flex: 1 1 auto;
            }

            .countdown-bar {
                width: 100px;
                max-width: 100px;
            }

            #filterTanggal {
                flex: 1 1 120px;
                max-width: 100%;
            }

            /* Summary badge font */
            .fs-4 {
                font-size: 1.1rem !important;
            }

            /* Foto lebih pendek */
            .photo-placeholder {
                height: 130px;
            }

            /* Datatable toolbar: stack */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: left !important;
                display: block !important;
                width: 100% !important;
                margin-bottom: 0.35rem;
            }

            .dataTables_wrapper .dataTables_filter label {
                display: flex !important;
                align-items: center;
                gap: 6px;
                width: 100%;
            }

            .dataTables_wrapper .dataTables_filter input[type="search"] {
                flex: 1 !important;
                width: auto !important;
                margin-left: 0 !important;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: left !important;
                display: block !important;
            }

            #modalDetail .row.text-start {
                flex-direction: column !important;
            }

            #modalDetail .row.text-start > [class*="col-"] {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }
        }

        /* Tablet Kecil 576 - 767 px */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .absensi-header-right {
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .countdown-bar {
                max-width: 130px;
            }

            #filterTanggal {
                min-width: 130px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            {{-- header --}}
                            <div class="absensi-header">
                                <h5 class="card-title">
                                    <i data-feather="user-check" style="width:18px;height:18px;vertical-align:middle;margin-right:6px"></i>
                                    Monitoring Absensi Karyawan
                                </h5>

                                <div class="absensi-header-right ms-auto">
                                    {{-- status real time --}}
                                    <div class="status-block text-end">
                                        <div id="last-updated">
                                            <i data-feather="clock" style="width:12px;height:12px;vertical-align:middle;margin-right:3px"></i>
                                            Belum dimuat
                                        </div>
                                        <div class="countdown-bar mt-1">
                                            <div class="countdown-bar-fill" id="countdown-bar" style="width:100%"></div>
                                        </div>
                                    </div>

                                    {{-- Spinner Loading --}}
                                    <span id="refresh-indicator" class="spinner-border spinner-border-sm text-primary" role="status"></span>

                                    {{-- Filter Tanggal --}}
                                    <div class="input-group" style="width:auto">
                                        <span class="input-group-text px-2">
                                            <i data-feather="calendar" style="width:14px;height:14px"></i>
                                        </span>
                                        <input type="date" id="filterTanggal" class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- summary badges --}}
                            <div class="row g-2 mb-3" id="summary-row" style="display: none !important">
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center">
                                        <div class="fw-bold fs-4 text-success" id="count-tepat">0</div>
                                        <small class="text-muted">Tepat Waktu</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center">
                                        <div class="fw-bold fs-4 text-danger" id="count-terlambat">0</div>
                                        <small class="text-muted">Terlambat</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center">
                                        <div class="fw-bold fs-4 text-warning" id="count-izin">0</div>
                                        <small class="text-muted">Izin / Sakit</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center">
                                        <div class="fw-bold fs-4 text-primary" id="count-total">0</div>
                                        <small class="text-muted">Total Record</small>
                                    </div>
                                </div>
                            </div>

                            {{-- table --}}
                            <div class="table-responsive">
                                <table id="tabelAbsensi" class="table table-bordered table-striped text-center w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Waktu</th>
                                            <th>Status</th>
                                            <th>Terlambat</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script>
    @verbatim
        document.addEventListener('DOMContentLoaded', function () {

            // config
            const REFRESH_INTERVAL = 30;

            // State
            let dataTable = null;
            let refreshTimer = null;
            let countdownTimer = null;
            let secondsLeft = REFRESH_INTERVAL;

            // Init DataTable
            dataTable = $('#tabelAbsensi').DataTable({
                columns: [
                    { data: 'no', orderable: false, width: '40px' },
                    { data: 'nama' },
                    { data: 'role' },
                    { data: 'tanggal_format' },
                    { data: 'jenis_badge', orderable: false },
                    { data: 'waktu_format' },
                    { data: 'status_badge', orderable: false },
                    { data: 'telat_format', orderable: false },
                    { data: 'catatan', orderable: false },
                    { data: 'aksi', orderable: false, width: '60px', responsivePriority: 1 },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[5, 'asc']],
                // nonaktifkan build-in processing indicator
                processing: false,
                // kosongkan data awal
                data: [],

                drawCallback: function () {
                    if (typeof feather !== 'undefined') feather.replace();
                },
                rowCallback: function (row) {
                    if (typeof feather !== 'undefined') feather.replace();
                }
            });

            $('#tabelAbsensi tbody').on('responsive-display.dt', function () {
                if (typeof feather !== 'undefined') feather.replace();
            });

            // helper: escape html
            function escAttr(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }

            // helper: spinner
            function showSpinner(show) {
                const el = document.getElementById('refresh-indicator');
                if (el) el.classList.toggle('show', show);
            }

            // helper: update timestamp
            function updateLastUpdated() {
                const now = new Date().toLocaleTimeString('id-ID');
                document.getElementById('last-updated').textContent = `Update: ${now}`;
            }

            // helper: countdown bar
            function startCountdown() {
                clearInterval(countdownTimer);
                secondsLeft = REFRESH_INTERVAL;

                countdownTimer = setInterval(function () {
                    secondsLeft--;
                    const pct = (secondsLeft / REFRESH_INTERVAL) * 100;
                    document.getElementById('countdown-bar').style.width = pct + '%';
                    if (secondsLeft <= 0) clearInterval(countdownTimer);
                }, 1000);
            }

            // helper: update summary cards
            function updateSummary(rows) {

                const elTepat       = document.getElementById('count-tepat');
                const elTerlambat   = document.getElementById('count-terlambat');
                const elIzin        = document.getElementById('count-izin');
                const elTotal       = document.getElementById('count-total');
                const summaryRow    = document.getElementById('summary-row');

                // 🔴 STOP kalau elemen tidak ada
                if (!elTepat || !elTerlambat || !elIzin || !elTotal || !summaryRow) {
                    console.warn('Elemen summary tidak ditemukan');
                    return;
                }

                const tepat     = rows.filter(r => r.raw_status === 'Tepat Waktu').length;
                const terlambat = rows.filter(r => r.raw_status === 'Terlambat').length;
                const izin      = rows.filter(r => r.raw_jenis === 'Izin' || r.raw_jenis === 'Sakit').length;

                elTepat.textContent      = tepat;
                elTerlambat.textContent  = terlambat;
                elIzin.textContent       = izin;
                elTotal.textContent      = rows.length;

                summaryRow.style.removeProperty('display');
            }

            // load data
            function loadData() {
                const tanggal = document.getElementById('filterTanggal').value;

                if (!tanggal) {
                    dataTable.clear().draw();
                    document.getElementById('last-updated').textContent = 'Pilih tanggal terlebih dahulu';
                    document.getElementById('summary-row').style.setProperty('display', 'none','important');

                    return;
                }

                showSpinner(true);

                fetch(`/superadmin/absensi/karyawan/data?tanggal=${tanggal}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        const rows = data.map((row, index) => {
                            const aksiBtn = `
                                <button class="btn btn-sm btn-outline-primary btn-detail"
                                    title="Lihat detail"
                                    data-nama="${escAttr(row.pengguna?.nama)}"
                                    data-jenis-badge="${escAttr(row.jenis_badge ?? row.jenis)}"
                                    data-waktu="${escAttr(row.waktu_format ?? row.waktu)}"
                                    data-status-badge="${escAttr(row.status_badge ?? row.status)}"
                                    
                                    data-telat="${escAttr(row.telat_format)}"
                                    data-alamat="${escAttr(row.alamat)}"
                                    data-catatan="${escAttr(row.catatan)}"
                                    data-foto="${escAttr(row.foto_url)}"
                                    data-lat="${row.latitude ?? ''}"
                                    data-lng="${row.longitude ?? ''}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>`;

                            return {
                                no: index + 1,
                                nama: row.pengguna?.nama ?? '-',
                                role: row.pengguna?.role ?? '-',
                                tanggal_format: row.tanggal_format ?? row.tanggal ?? '-',
                                jenis_badge: row.jenis_badge ?? row.jenis ?? '-',
                                waktu_format: row.waktu_format ?? row.waktu?? '-',
                                status_badge: row.status_badge ?? row.status ?? '-',
                                telat_format: row.menit_terlambat > 0 
                                                ? `<span class="text-danger fw-semibold">${row.telat_format}</span>`
                                                : `<span class="text-muted">-</span>`,
                                catatan: row.catatan
                                            ? `<span class="small text-muted">${escAttr(row.catatan)}</span>`
                                            : `<span class="text-muted">-</span>`,
                                aksi: aksiBtn,

                                raw_status: row.status,
                                raw_jenis: row.jenis,
                            };
                        });

                        dataTable.clear().rows.add(rows).draw(false);

                        dataTable.rows().every(function () {
                            const d     = this.data();
                            const node  = this.node();

                            if (d.raw_status === 'Terlambat') {
                                $(node).addClass('table-danger');
                            } else if (d.raw_jenis === 'Izin') {
                                $(node).addClass('table-warning');
                            } else if (d.raw_jenis === 'Sakit') {
                                $(node).addClass('table-info');
                            } else if (d.raw_jenis === 'Pulang') {
                                $(node).addClass('table-secondary');
                            }
                        });

                        updateSummary(rows);
                        updateLastUpdated();
                        startCountdown();

                        if (typeof feather !== 'undefined') feather.replace();
                })
                .catch(err => {
                    console.error('Gagal memuat data:', err);
                    document.getElementById('last-updated').textContent = 'Gagal memuat - coba lagi...';
                })
                .finally(() => showSpinner(false));
            }

            // auto refresh
            function scheduleRefresh() {
                clearInterval(refreshTimer);
                refreshTimer = setInterval(loadData, REFRESH_INTERVAL * 1000);
            }

            // Event: filter tanggal berubah
            document.getElementById('filterTanggal').addEventListener('change', function () {
                loadData();
                scheduleRefresh();
            });

            // Event: klik tombol detail (event delegation)
            $('#tabelAbsensi tbody').on('click', '.btn-detail', function () {
                const $btn = $(this);

                const nama          = $btn.data('nama') || '-';
                const waktu         = $btn.data('waktu') || '-';
                const telat         = $btn.data('telat') || '-';
                const alamat        = $btn.data('alamat') || '-';
                const catatan       = $btn.data('catatan') || '-';
                const jenis         = $btn.data('jenis-badge') || '-';
                const status        = $btn.data('status-badge') || '-';
                const foto          = $btn.data('foto');
                const lat           = $btn.data('lat');
                const lng           = $btn.data('lng');

                const fotoHtml = foto
                    ? `<img src="${foto}" style="width:100%;max-height:220px;object-fit:cover;border-radius:10px;margin-top:10px">`
                    : `<div style="padding:20px;border:2px dashed #ddd;border-radius:10px;margin-top:10px;color:#888">
                        Tidak ada Foto
                        </div>`;         
                
                const mapsBtn = (lat && lng)
                ? `<a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank"
                    style="display:inline-block;margin-top:10px;padding:6px 12px;
                    border-radius:6px;background:#0d6efd;color:#fff;text-decoration:none;font-size:12px">
                    Lihat Lokasi
                    </a>`
                : '';

                Swal.fire({
                    title: `<strong>${nama}</strong>`,
                    html: `
                        <div style="text-align:left;font-size:13px">
                            <div style="
                                display:grid;
                                grid-template-columns: auto 1fr;
                                row-gap:6px;
                                column-gap:10px;
                                align-items:start;
                            ">
                                <div style="color:#6c757d">Jenis</div>
                                <div>${jenis}</div>

                                <div style="color:#6c757d">Status</div>
                                <div>${status}</div>

                                <div style="color:#6c757d">Waktu</div>
                                <div>${waktu}</div>

                                <div style="color:#6c757d">Terlambat</div>
                                <div>${telat}</div>

                                <div style="color:#6c757d">Alamat</div>
                                <div style="line-height:1.4">${alamat}</div>

                                <div style="color:#6c757d">Catatan</div>
                                <div>${catatan || '-'}</div>
                            </div>

                            <div style="margin-top:12px">
                                ${foto
                                    ? `<img src="${foto}" style="
                                        width:100%;
                                        max-height:220px;
                                        object-fit:cover;
                                        border-radius:10px;
                                        box-shadow:0 2px 8px rgba(0,0,0,0.1);
                                    ">`
                                    : `<div style="
                                        padding:20px;
                                        border:2px dashed #ddd;
                                        border-radius:10px;
                                        text-align:center;
                                        color:#999;
                                    ">Tidak ada foto</div>`
                                }
                            </div>

                            ${lat && lng ? `
                            <div style="text-align:left;margin-top:10px">
                                <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank"
                                    style="
                                        display:inline-block;
                                        padding:6px 12px;
                                        border-radius:6px;
                                        background:#0d6efd;
                                        color:#fff;
                                        text-decoration:none;
                                        font-size:12px;
                                    ">
                                    Lihat Lokasi
                                </a>
                            </div>
                            ` : ''}
                        </div>
                    `,
                    width: 420,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#0d6efd',
                    showCloseButton: true,
                    padding: '1.5rem',
                    customClass: {
                        popup: 'rounded-4'
                    }
                });
            });
            // default: set tanggal = hari ini, langsung load
            document.getElementById('filterTanggal').value = new Date().toISOString().split('T')[0];
            loadData();
            scheduleRefresh();
        });
    @endverbatim
    </script>
@endpush