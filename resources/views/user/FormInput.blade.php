<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Input Data - PT Sarana Agung Sejahtera</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
            {{-- <li>
              <a href="/">Beranda</a>
            </li> --}}
            <!-- <li><a href="#about">About</a></li> -->
            <!-- <li><a href="#services">Services</a></li> -->
            <!-- <li><a href="#departments">Departments</a></li> -->
            <li><a href="/input-data-ac" class="active">Form Input Data</a></li>
            <li></li>
            <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="#">Dropdown 1</a></li>
                <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                  <ul>
                    <li><a href="#">Deep Dropdown 1</a></li>
                    <li><a href="#">Deep Dropdown 2</a></li>
                    <li><a href="#">Deep Dropdown 3</a></li>
                    <li><a href="#">Deep Dropdown 4</a></li>
                    <li><a href="#">Deep Dropdown 5</a></li>
                  </ul>
                </li>
                <li><a href="#">Dropdown 2</a></li>
                <li><a href="#">Dropdown 3</a></li>
                <li><a href="#">Dropdown 4</a></li>
              </ul>
            </li> -->
            <!-- <li><a href="#contact">Contact</a></li> -->
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
  <!-- Contact Section -->
  <section id="forminput" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Form Input Data AC</h2>
      <p>Silahkan Masukkan Data AC Dengan Lengkap dan Benar</p>
    </div>
    <!-- End Section Title -->

    <div class="container">
      <div class="row justify-content-center">

        <div class="col-md-9">
          <form action="{{ route('ac.store') }}" method="POST" novalidate>
            @csrf
            <div class="row gy-4">
              <div class="col-sm-6">
                <label for="id_merkac" class="form-label">Merk AC</label>
                <select name="id_merkac" id="id_merkac" class="form-select form-select-md @error('id_merkac') is-invalid @enderror" required>
                  <option value="">-- Pilih Merk AC --</option>
                  @foreach ($merkac as $item)
                    <option value="{{ $item->id }}" {{ old('id_merkac') == $item->id ? 'selected' : '' }}> {{ $item->nama_merk }}</option>
                  @endforeach 
                </select>
                @error('id_merkac')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_jenisac">
                <label for="id_jenisac" class="form-label">Jenis AC</label>
                <select name="id_jenisac" id="id_jenisac" class="form-select form-select-md @error('id_jenisac') is-invalid @enderror" required>
                  <option value="">-- Pilih Jenis AC --</option>
                  @foreach ($jenisac as $item)
                    <option value="{{ $item->id }}" {{ old('id_jenisac') == $item->id ? 'selected' : '' }}> {{ $item->nama_jenis }}</option>
                  @endforeach 
                </select>
                @error('id_jenisac')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_departement">
                <label for="id_departement" class="form-label">Departement</label>
                <select name="id_departement" id="id_departement" class="form-select form-select-md @error('id_departement') is-invalid @enderror" required>
                  <option value="">-- Pilih Departement --</option>
                  @foreach ($departement as $item)
                      <option value="{{ $item->id }}" {{ old('id_departement') == $item->id ? 'selected' : '' }}>
                          {{ $item->nama_departement }}
                      </option>
                  @endforeach 
                </select>
                @error('id_departement')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_ruangan">
                <label for="id_ruangan" class="form-label">Ruangan</label>
                <select name="id_ruangan" id="id_ruangan" class="form-select form-select-md @error('id_ruangan') is-invalid @enderror" required data-selected="{{ old('id_ruangan') }}">
                  <option value="">-- Pilih Ruangan --</option> 
                </select>
                @error('id_ruangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_nomorac">
                <label for="no_ac" class="form-label">Nomor AC</label>
                <div class="input-group">
                    <span class="input-group-text">I-</span>
                    <input
                        type="text"
                        id="no_ac"
                        name="no_ac"
                        required
                        class="form-control form-control-md @error('no_ac') is-invalid @enderror"
                        placeholder="Masukkan Nomor AC (contoh: 001)"
                        value="{{ old('no_ac') }}">
                    @error('no_ac')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
              </div>

              <div class="col-sm-6" id="field_no_seri_indoor">
                <label for="no_seri_indoor" class="form-label">Nomor Seri Indoor</label>
                <input
                    type="text"
                    id="no_seri_indoor"
                    name="no_seri_indoor"
                    required
                    class="form-control form-control-md @error('no_seri_indoor') is-invalid @enderror"
                    placeholder="Masukkan Nomor Seri AC Indoor"
                    value="{{ old('no_seri_indoor') }}">
                @error('no_seri_indoor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_no_seri_outdoor">
                <label for="no_seri_outdoor" class="form-label">Nomor Seri Outdoor</label>
                <input
                    type="text"
                    id="no_seri_outdoor"
                    name="no_seri_outdoor"
                    required
                    class="form-control form-control-md @error('no_seri_outdoor') is-invalid @enderror"
                    placeholder="Masukkan Nomor Seri AC Outdoor"
                    value="{{ old('no_seri_outdoor') }}">
                @error('no_seri_outdoor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_pk_ac">
                <label for="pk_ac" class="form-label">PK AC</label>
                <input
                    type="number"
                    id="pk_ac"
                    name="pk_ac"
                    required
                    class="form-control form-control-md @error('pk_ac') is-invalid @enderror"
                    placeholder="Masukkan PK AC"
                    value="{{ old('pk_ac') }}">
                @error('pk_ac')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_tahun_ac">
                <label for="tahun_ac" class="form-label">Tahun AC</label>
                <input
                    type="number"
                    id="tahun_ac"
                    name="tahun_ac"
                    required
                    class="form-control form-control-md @error('tahun_ac') is-invalid @enderror"
                    placeholder="Masukkan Tahun AC"
                    value="{{ old('tahun_ac') }}">
                @error('tahun_ac')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_tgl_pemasangan">
                <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan</label>
                <input
                    type="date"
                    id="tanggal_pemasangan"
                    name="tanggal_pemasangan"
                    required
                    class="form-control form-control-md @error('tanggal_pemasangan') is-invalid @enderror"
                    value="{{ old('tanggal_pemasangan') }}">
                @error('tanggal_pemasangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6" id="field_tanggal_habis_garansi">
                <label for="tanggal_habis_garansi" class="form-label">Tanggal Habis Garansi</label>
                <input
                    type="date"
                    id="tanggal_habis_garansi"
                    name="tanggal_habis_garansi"
                    required
                    class="form-control form-control-md @error('tanggal_habis_garansi') is-invalid @enderror"
                    value="{{ old('tanggal_habis_garansi') }}">
                @error('tanggal_habis_garansi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-12 text-center" id="submit_btn">
                <button type="submit" id="submit_btn" class="btn btn-primary">Kirim Data</button>
              </div>
            </div>
          </form>
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

  <!-- Tambahkan jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
$(document).ready(function () {

    function loadRuangan(departementID, selectedRuangan = null) {
        if (!departementID) return;

        $('#id_ruangan').prop('disabled', true); // disable sementara
        $('#id_ruangan').html('<option value="">Memuat...</option>');

        $.ajax({
            url: "{{ url('sas/get-ruangan') }}/" + departementID,
            type: 'GET',
            success: function(data) {
                $('#id_ruangan').empty();
                $('#id_ruangan').append('<option value="">-- Pilih Ruangan --</option>');

                $.each(data, function(key, value){
                    let selected = (value.id == selectedRuangan) ? 'selected' : '';
                    $('#id_ruangan').append('<option value="'+value.id+'" '+selected+'>'+value.nama_ruangan+'</option>');
                });

                $('#id_ruangan').prop('disabled', false); // enable setelah load
            },
            error: function() {
                $('#id_ruangan').html('<option value="">Gagal memuat data</option>');
                $('#id_ruangan').prop('disabled', true);
            }
        });
    }

    // Event change departement
    $('#id_departement').on('change', function() {
        const departementID = $(this).val();
        loadRuangan(departementID);
    });

    // Load ruangan otomatis saat page load jika ada old value
    const oldDepartement = $('#id_departement').val();
    const oldRuangan = $('#id_ruangan').data('selected');
    if (oldDepartement) {
        loadRuangan(oldDepartement, oldRuangan);
    }

});
</script>

</body>

</html>

