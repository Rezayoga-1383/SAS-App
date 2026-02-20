<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Data AC RSPAL - PT Sarana Agung Sejahtera</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
            <li><a href="/data-ac" class="active">Data AC</a></li>
            <li><a href="/input-data-ac">Form Input Data</a></li>
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
      <h2>Data AC</h2>
      <p>Data AC RSPAL DR Ramelan</p>
    </div>
    <!-- End Section Title -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="table-responsive">
                    <table id="TabelDetailAC" class="table hover stripe" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No AC</th>
                                <th>Merk</th>
                                <th>Jenis</th>
                                <th>Departement</th>
                                <th>Ruangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
        const table = $('#TabelDetailAC').DataTable({
            processing : true,
            serverside : true,
            ajax : "{{ route('ac.data') }}",
            order : [],
            columns : [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_ac', name: 'no_ac' },
                { data: 'nama_merkac', name: 'merkac.nama_merk' },
                { data: 'nama_jenisac', name: 'jenisac.nama_jenis' },
                { data: 'nama_departement', name: 'departement.nama_departement' },
                { data: 'nama_ruangan', name: 'ruangan.nama_ruangan' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ],
            drawCallback: function() {
                // ✅ Agar feather icon muncul kembali setelah DataTables refresh
                feather.replace();
            }
        });

        // ✅ Tombol detail
        $(document).on('click', '.btn-detail', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/data-ac/detail/${id}`,
                type: 'GET',
                success: function(response) {
                    let htmlDetail = `
                        <table class="table table-bordered text-start">
                            <tr><th>Merk</th><td>${response.nama_merk}</td></tr>
                            <tr><th>Jenis</th><td>${response.nama_jenis}</td></tr>
                            <tr><th>Departement</th><td>${response.nama_departement}</td></tr>
                            <tr><th>Ruangan</th><td>${response.nama_ruangan}</td></tr>
                            <tr><th>No AC</th><td>${response.no_ac}</td></tr>
                            <tr><th>No Seri Indoor</th><td>${response.no_seri_indoor}</td></tr>
                            <tr><th>No Seri Outdoor</th><td>${response.no_seri_outdoor}</td></tr>
                            <tr><th>PK AC</th><td>${response.pk_ac}</td></tr>
                            <tr><th>Jumlah AC</th><td>${response.jumlah_ac}</td></tr>
                            <tr><th>Tahun</th><td>${response.tahun_ac}</td></tr>
                            <tr><th>Tanggal Pemasangan</th><td>${response.tanggal_pemasangan}</td></tr>
                            <tr><th>Tanggal Habis Garansi</th><td>${response.tanggal_habis_garansi}</td></tr>
                        </table>`;

                    Swal.fire({
                        title: '<strong>Detail Data AC</strong>',
                        html: htmlDetail,
                        width: 500,
                        confirmButtonText: 'Tutup'
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat memuat data detail AC.'
                    });
                }
            });
        });
    });

</script>
</body>
</html>

