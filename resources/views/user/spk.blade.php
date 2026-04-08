<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Data SPK RSPAL - PT Sarana Agung Sejahtera</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
  rel="stylesheet"
  media="print"
  onload="this.media='all'">

  {{-- datatables css --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

  <!-- SweetAlert2 JS -->
  <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="https://mail.google.com/mail/?view=cm&fs=1&to=saranaagungsejahtera@gmail.com" target="_blank">saranaagungsejahtera@gmail.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span><a href="https://wa.me/628113492009">+62 811-3492-009</span></a></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <!-- <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a> -->
          <a href="https://wa.me/628113492009" class="whatsapp"><i class="bi bi-whatsapp"></i></a>
          <a href="https://www.instagram.com/saranaagungsejahtera/" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="https://www.facebook.com/saranaagungsejahtera" class="facebook"><i class="bi bi-facebook"></i></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="{{ asset('assets/image/logo-sas.png') }}" alt="">
          <!-- <h1 class="sitename">SAS</h1> -->
        </a>

        <nav id="navmenu" class="navmenu d-flex align-items-center">
          <ul>
            <li><a href="/data-ac-rsal">Data AC</a></li>
            @if (auth()->id() === 8)
                <li><a href="/data-spk" class="active">Data SPK</a></li>
            @endif
            <li><a href="/input-data-spk">Form Input SPK</a></li>
            <li></li>
          </ul>
          <form action="{{ route('logout') }}" method="post" class="me-3">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">Keluar</button>
          </form>

          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>

    </div>

  </header>

  <main class="main">
      @if(session('success'))
          <script>
            document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#f0fff4',
                color: '#155724',
                customClass: {
                  popup: 'swal2-border-radius'
                }
              });
            });
          </script>
      @endif
  <!-- Contact Section -->
  <section id="forminput" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Data SPK</h2>
      <p>Data Monitoring Status SPK RSPAL DR. Ramelan</p>
    </div>
    <!-- End Section Title -->

    <div class="container">
        <!-- Filter Tanggal -->
        <div class="row justify-content-center mb-3">
            <div class="col-12 col-md-10 col-lg-9">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-sm-auto">
                                <label for="startDate" class="col-form-label fw-bold mb-0">Tanggal Awal:</label>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <input type="date" id="startDate" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-sm-auto ms-sm-2">
                                <label for="endDate" class="col-form-label fw-bold mb-0">Tanggal Akhir:</label>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <input type="date" id="endDate" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-sm-auto mt-3 mt-sm-0 ms-sm-auto">
                                <button id="btnFilter" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Filter</button>
                            </div>
                            <div class="col-12 col-sm-auto mt-2 mt-sm-0">
                                <button id="btnReset" class="btn btn-secondary btn-sm w-100"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-9">
                <!-- <div class="table-responsive"> -->
                    <table id="TabelDetailSPK" class="table hover stripe dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SPK</th>
                                <th>Tanggal</th>
                                <th>Teknisi</th>
                                <th>Departement</th>
                                <th>Ruangan</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                <!-- </div> -->
            </div>
        </div>
    </div>

  </section>
  <!-- /Contact Section -->

</main>

  <footer id="footer" class="footer light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/image/logo-sas.png') }}" alt="">
          </a>
          <div class="footer-contact pt-3">
            <p><strong>Alamat:</strong>
            <br> Jl Raya Tmn Asri Jl. Palm Square City <br> No.Ruko Kav TF/31, Palem, Wadungasri</p>
            <p>Sidoarjo, Jawa Timur, 61256</p>
            <p class="mt-3"><strong>Phone:</strong> <br> <span>+62 811-3492-009</span></p>
            <p><strong>Email:</strong> <br> <span>saranaagungsejahtera@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="https://wa.me/628113492009" class="whatsapp"><i class="bi bi-whatsapp"></i></a>
            <a href="https://www.instagram.com/saranaagungsejahtera/" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.facebook.com/saranaagungsejahtera" class="facebook"><i class="bi bi-facebook"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Informasi Umum</h4>
          <ul>
            <li><a href="/#beranda">Beranda</a></li>
            <li><a href="/#tentangkami">Tentang Kami</a></li>
            <li><a href="/#layanan">Layanan</a></li>
            <li><a href="/#galeri">Galeri</a></li>
            <li><a href="/#contact">Contact</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Layanan Kami</h4>
          <ul>
            <li><a href="#">Service AC</a></li>
            <li><a href="#">Instalasi AC</a></li>
            <!-- <li><a href="#">Layanan Bergaransi</a></li> -->
            <li><a href="#">Perawatan Berkala</a></li>
            <!-- <li><a href="#">Konsultasi Teknis</a></li> -->
            <!-- <li><a href="#">Produk Berkualitas</a></li> -->
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Lainnya</h4>
          <ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Konsultasi Teknis</a></li>
            <li><a href="#">Produk Berkualitas</a></li>
            <li><a href="#">Layanan Bergaransi</a></li>
            <!-- <li><a href="#">Excepturi dignissimos</a></li>
            <li><a href="#">Suscipit distinctio</a></li>
            <li><a href="#">Dilecta</a></li>
            <li><a href="#">Sit quas consectetur</a></li> -->
          </ul>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2025</strong> <span>PT Sarana Agung Sejahtera</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

<!-- jQuery dulu -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

  <!-- DataTables JS -->
<script src="{{ asset('js/dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

{{-- feather-icons --}}
<script src="https://unpkg.com/feather-icons"></script>

  <!-- Select2 CSS -->
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
  <!-- Select2 JS -->
  <script src="{{ asset('js/select2.min.js') }}"></script>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>


<script>
    $(document).ready(function() {
        const table = $('#TabelDetailSPK').DataTable({
            processing : true,
            serverSide : true,
            responsive: true,
            ajax: {
                url: "{{ route('spk.data.teknisi') }}",
                data: function(d) {
                    d.start_date = $('#startDate').val();
                    d.end_date = $('#endDate').val();
                }
            },
            columns : [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_spk'},
                { data: 'tanggal'},
                { data: 'teknisi'},
                { data: 'departement'},
                { data: 'ruangan'},
                { data: 'status'},
                { data: 'keterangan_spk'},
                { data: 'aksi', orderable: false, searchable: false},
            ],
            drawCallback: function() {
                // ✅ Agar feather icon muncul kembali setelah DataTables refresh
                feather.replace();
            }
        });

        // ✅ Filter button actions
        $('#btnFilter').click(function() {
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan isi Tanggal Awal dan Tanggal Akhir terlebih dahulu!'
                });
                return;
            }

            table.ajax.reload();
        });

        $('#btnReset').click(function() {
            $('#startDate').val('');
            $('#endDate').val('');
            table.ajax.reload();
        });

       // ✅ Tombol detail
        $(document).on('click', '.btn-detail', function() {
            let id = $(this).data('id');

            $.ajax({
                url: `/spk/detail/${id}`,
                type: 'GET',
                success: function(response) {
                  // console.log(response);

                    // ✅ Teknisi
                    let teknisi = Array.isArray(response.teknisi) && response.teknisi.length > 0
                        ? response.teknisi.map(t => t.nama).join(', ')
                        : '-';

                    // ✅ Status
                    let statusBadge = '';
                    if (response.status === 'menunggu') {
                        statusBadge = '<span class="badge bg-warning text-dark">Menunggu</span>';
                    } else if (response.status === 'disetujui') {
                        statusBadge = '<span class="badge bg-primary">Disetujui</span>'; 
                    } else if (response.status === 'belum selesai') { 
                        statusBadge = '<span class="badge bg-secondary">Belum Selesai</span>';
                    }else {
                        statusBadge = '<span class="badge bg-success">Selesai</span>';
                    }

                    let keteranganBadge = '<span class="text-muted">-</span>';
                    if (response.keterangan_spk === 'cocok') {
                        keteranganBadge = '<span class="badge bg-success">Cocok</span>';
                    } else if (response.keterangan_spk === 'tidak cocok') {
                        keteranganBadge = '<span class="badge bg-danger">Tidak Cocok</span>';
                    }

                    let catatanHtml = '';
                    if (response.keterangan_spk === 'tidak cocok') {
                        catatanHtml = `
                            <tr>
                                <th>Catatan</th>
                                <td>
                                    ${response.catatan_spk
                                        ? `<div class="text-danger">
                                            ${response.catatan_spk}
                                        </div>`
                                        : '<span class="text-muted">-</span>'}    
                                </td>
                            </tr>
                        `;
                    }

                    // ✅ Gambar SPK (file utama)
                    let fileSpk = response.file_spk 
                        ? `<a href="/storage/${response.file_spk}" class="glightbox" data-gallery="gallery-spk">
                               <img src="/storage/${response.file_spk}" class="img-fluid mb-3 rounded shadow-sm" style="width: 120px; height: 120px; object-fit: cover; cursor: zoom-in;">
                           </a>`
                        : '<span>-</span>';

                    let images = [];
                    let historyImages = [];

                    if (Array.isArray(response.units)) {
                        response.units.forEach(unit => {

                            let noAc = unit.acdetail?.no_ac ?? 'No AC -';

                            // 🔵 Kolase (log_service_image)
                            if (Array.isArray(unit.images)) {
                                unit.images.forEach(img => {
                                    if (img.image_path) {
                                        images.push(`
                                            <div class="text-center">
                                                <span class="badge bg-info mb-1">${noAc}</span>
                                                <div class="mt-1">
                                                    <a href="/storage/${img.image_path}" class="glightbox" data-gallery="gallery-kolase">
                                                        <img src="/storage/${img.image_path}"
                                                            class="img-thumbnail img-preview d-block mx-auto shadow-sm"
                                                            style="width: 100px; height: 100px; object-fit: cover; cursor: zoom-in;">
                                                    </a>
                                                </div>
                                            </div>
                                        `);
                                    }
                                });
                            }

                            // 🟢 History (ac_history_images)
                            if (Array.isArray(unit.history_images)) {
                                unit.history_images.forEach(img => {
                                    if (img.image_path) {
                                        historyImages.push(`
                                            <div class="text-center">
                                                <span class="badge bg-secondary mb-1">${noAc}</span>
                                                <div class="mt-1">
                                                    <a href="/storage/${img.image_path}" class="glightbox" data-gallery="gallery-history">
                                                        <img src="/storage/${img.image_path}"
                                                            class="img-thumbnail img-preview d-block mx-auto shadow-sm"
                                                            style="width: 100px; height: 100px; object-fit: cover; cursor: zoom-in;">
                                                    </a>
                                                </div>
                                            </div>
                                        `);
                                    }
                                });
                            }

                        });
                    }

                    let imageHtml = images.length > 0 ? images.join('') : '<span>-</span>';
                    let historyHtml = historyImages.length > 0 ? historyImages.join('') : '<span>-</span>';

                    // ✅ HTML FINAL
                    let htmlDetail = `
                        <div class="text-start">

                            <table class="table table-bordered">
                                <tr>
                                    <th>No SPK</th>
                                    <td>${response.no_spk ?? '-'}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>${response.tanggal ?? '-'}</td>
                                </tr>
                                <tr>
                                    <th>Teknisi</th>
                                    <td>${teknisi}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${statusBadge}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>${keteranganBadge}</td>
                                </tr>
                                ${catatanHtml}
                            </table>

                            <div class="text-center mt-4">
                                <h6 class="fw-bold">📄 File SPK</h6>
                                <div class="d-flex justify-content-center mb-4">
                                    ${fileSpk}
                                </div>

                                <h6 class="fw-bold">📸 Gambar Kolase</h6>
                                <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                                    ${imageHtml}
                                </div>

                                <h6 class="fw-bold">🧾 Gambar History</h6>
                                <div class="d-flex flex-wrap justify-content-center gap-3 mb-2">
                                    ${historyHtml}
                                </div>
                            </div>

                        </div>
                    `;

                    Swal.fire({   
                        title: 'Detail SPK',
                        html: htmlDetail,
                        width: 500,
                        confirmButtonText: 'Tutup',
                        didOpen: () => {
                            if (typeof GLightbox !== 'undefined') {
                                GLightbox({ selector: '.glightbox' });
                            }
                        }
                    });
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak bisa mengambil data'
                    });
                }
            });
        });

        $(document).on('click', '.btn-approve', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin Ingin Approve?',
                text: "Status akan berubah menjadi Disetujui",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Approve!',
                cancelButtontext: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.post(`/spk/approve/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'berhasil',
                            text: 'Status berhasil diubah menjadi Disetujui',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#TabelDetailSPK').DataTable().ajax.reload();
                    });
                }
            });
        });

        $(document).on('click', '.btn-selesai', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Selesaikan Pekerjaan?',
                text: "Status akan berubah menjadi Selesai",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtontext: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.post(`/spk/selesai/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Status berhasil diubah menjadi Selesai',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#TabelDetailSPK').DataTable().ajax.reload();
                    });
                }
            });
        });

        $(document).on('click', '.btn-keterangan', function() {
          let id = $(this).data('id');

          Swal.fire({
              title: 'Pilih Keterangan',
              text: 'Silakan pilih keterangan SPK',
              icon: 'question',
              showCancelButton: true,
              showDenyButton: true,

              confirmButtonText: 'Cocok',
              confirmButtonColor: '#198754', // hijau

              denyButtonText: 'Tidak Cocok',
              denyButtonColor: '#dc3545', // merah

              cancelButtonText: 'Batal',
              cancelButtonColor: '#6c757d'
          }).then((result) => {

              if (result.isConfirmed) {
                kirimKeterangan(id, 'cocok', null);
              }

              else if (result.isDenied) {
                Swal.fire({
                    title: 'Masukkan Keterangan',
                    input: 'textarea',
                    inputPlaceholder: 'Jelaskan mengapa tidak cocok..',
                    inputAttributes: {
                        'aria-label': 'Masukkan Keterangan'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'batal',
                    confirmButtonColor: '#dc3545',

                    inputValidator: (value) => {
                        if (!value) {
                            return 'Keterangan tidak boleh kosong!';
                        }
                    }
                }).then((res2) => {
                    if (res2.isConfirmed) {
                        kirimKeterangan(id, 'tidak cocok', res2.value);
                    }
                });
              }
        });

        function kirimKeterangan(id, keterangan, catatan) {
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: '/update-keterangan/' + id,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    keterangan_spk: keterangan,
                    catatan_spk: catatan
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#TabelDetailSPK').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.resposeJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        }
    });
});
</script>
<div class="modal fade" id="modalDetailSPK" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detail SPK</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="modalDetailContent">
          <p class="text-center">Loading...</p>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>

