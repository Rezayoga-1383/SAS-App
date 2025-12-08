<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Input Data SPK - PT Sarana Agung Sejahtera</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
            {{-- <li>
              <a href="/">Beranda</a>
            </li> --}}
            <!-- <li><a href="#about">About</a></li> -->
            <!-- <li><a href="#services">Services</a></li> -->
            <!-- <li><a href="#departments">Departments</a></li> -->
            <li><a href="/input-data-ac">Form Input Data</a></li>
            <li><a href="/input-data-spk" class="active">Form Input SPK</a></li>
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
      <h2>Form Input Data SPK</h2>
      <p>Silahkan Masukkan Data Dengan Lengkap dan Benar!</p>
    </div>
    <!-- End Section Title -->

    <div class="container">
      <div class="row justify-content-center">

        <div class="col-md-9">
          <form action="{{ route('spk.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row gy-4">
              <div class="col-sm-6">
                <label for="id_acdetail" class="form-label">Nomor AC</label>
                <select name="id_acdetail" id="id_acdetail" class="form-select form-select-md @error('id_acdetail') is-invalid @enderror" required>
                  <option value="">-- Pilih No AC --</option>
                  @foreach ($acdetail as $item)
                    <option value="{{ $item->id }}" {{ old('id_acdetail') == $item->id ? 'selected' : '' }}> {{ $item->no_ac }}</option>
                  @endforeach 
                </select>
                @error('id_acdetail')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Nomor SPK</label>
                <input
                    type="text"
                    id="no_spk"
                    name="no_spk"
                    required
                    class="form-control form-control-md @error('no_spk') is-invalid @enderror"
                    placeholder="Masukkan Nomor SPK"
                    value="{{ old('no_spk') }}">
                @error('no_spk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Tanggal</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    required
                    class="form-control form-control-md @error('tanggal') is-invalid @enderror"
                    placeholder="Masukkan tanggal yang sesuai"
                    value="{{ old('tanggal') }}">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Waktu Mulai Pengerjaan</label>
                <input
                    type="time"
                    id="waktu_mulai"
                    name="waktu_mulai"
                    required
                    class="form-control form-control-md @error('waktu_mulai') is-invalid @enderror"
                    placeholder="Waktu mulai pengerjaan"
                    value="{{ old('waktu_mulai') }}">
                @error('waktu_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Waktu Selesai Pengerjaan</label>
                <input
                    type="time"
                    id="waktu_selesai"
                    name="waktu_selesai"
                    required
                    class="form-control form-control-md @error('waktu_selesai') is-invalid @enderror"
                    placeholder="waktu selesai pengerjaan"
                    value="{{ old('waktu_selesai') }}">
                @error('waktu_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="jumlah_orang" class="form-label">Jumlah Teknisi</label>
                <input
                    type="number"
                    id="jumlah_orang"
                    name="jumlah_orang"
                    required
                    class="form-control form-control-md @error('jumlah_orang') is-invalid @enderror"
                    placeholder="Jumlah teknisi yang mengerjakan"
                    value="{{ old('jumlah_orang') }}">
                @error('jumlah_orang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label class="form-label">Teknisi</label>
                <div class="border rounded p-2 @error('teknisi') is-invalid @enderror" style="max-height: 150px; overflow-y: auto;">
                    @foreach($teknisi as $user)
                        <div class="form-check">
                            <input 
                                class="form-check-input teknisi-checkbox" 
                                type="checkbox" 
                                name="teknisi[]" 
                                id="teknisi{{ $user->id }}" 
                                value="{{ $user->id }}"
                                {{ in_array($user->id, old('teknisi', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="teknisi{{ $user->id }}">
                                {{ $user->nama }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('teknisi')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="col-sm-6">
                <label for="keluhan" class="form-label">Keluhan</label>
                <textarea
                    id="keluhan"
                    name="keluhan"
                    required
                    class="form-control form-control-md @error('keluhan') is-invalid @enderror"
                    placeholder="Masukkan keluhan">{{ old('keluhan') }}</textarea>
                @error('keluhan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="col-sm-6">
                <label for="keluhan" class="form-label">Jenis Pekerjaan</label>
                <textarea
                    id="jenis_pekerjaan"
                    name="jenis_pekerjaan"
                    required
                    class="form-control form-control-md @error('jenis_pekerjaan') is-invalid @enderror"
                    placeholder="Masukkan jenis pekerjaan">{{ old('jenis_pekerjaan') }}</textarea>
                @error('jenis_pekerjaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Kepada</label>
                <input
                    type="text"
                    id="kepada"
                    name="kepada"
                    required
                    class="form-control form-control-md @error('kepada') is-invalid @enderror"
                    placeholder="Kepada"
                    value="{{ old('kepada') }}">
                @error('kepada')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="col-sm-6">
                <label for="no_seri_indoor" class="form-label">Mengetahui</label>
                <input
                    type="text"
                    id="mengetahui"
                    name="mengetahui"
                    required
                    class="form-control form-control-md @error('mengetahui') is-invalid @enderror"
                    placeholder="Mengetahui"
                    value="{{ old('mengetahui') }}">
                @error('mengetahui')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="col-sm-6">
                  <label for="hormat_kami" class="form-label">Hormat Kami</label>
                  <select name="hormat_kami" id="hormat_kami" class="form-select @error('hormat_kami') is-invalid @enderror" required>
                      <option value="">-- Pilih Admin --</option>
                      @foreach($admin as $user)
                          <option value="{{ $user->id }}" {{ old('hormat_kami') == $user->id ? 'selected' : '' }}>
                              {{ $user->nama }}
                          </option>
                      @endforeach
                  </select>
                  @error('hormat_kami')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="col-sm-6">
                  <label for="pelaksana_ttd" class="form-label">Pelaksana SPK</label>
                  <select name="pelaksana_ttd" id="pelaksana_ttd" class="form-select @error('pelaksana_ttd') is-invalid @enderror" required>
                      <option value="">-- Pilih Pelaksana --</option>
                      @foreach($teknisi as $user)
                          <option value="{{ $user->id }}">
                              {{ $user->nama }}
                          </option>
                      @endforeach
                  </select>
                  @error('pelaksana_ttd')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="col-sm-6">
                  <label for="file_spk" class="form-label">Upload File SPK</label>
                  <input
                      type="file"
                      id="file_spk"
                      name="file_spk"
                      class="form-control form-control-md @error('file_spk') is-invalid @enderror"
                      accept=".pdf,.jpg,.jpeg,.png"  {{-- sesuaikan jenis file yang diizinkan --}}
                      required>
                  @error('file_spk')
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
  const jumlahInput = document.getElementById('jumlah_orang');
  const checkboxes = document.querySelectorAll('.teknisi-checkbox');
  const pelaksanaSelect = document.getElementById('pelaksana_ttd');

  function updateCheckboxLimit() {
      const maxTeknisi = parseInt(jumlahInput.value) || 0;
      const checkedCount = document.querySelectorAll('.teknisi-checkbox:checked').length;

      checkboxes.forEach(cb => {
          if (!cb.checked && checkedCount >= maxTeknisi) {
              cb.disabled = true;
          } else {
              cb.disabled = false;
          }
      });

      updatePelaksanaOptions();
  }

  function updatePelaksanaOptions() {
      const selectedIds = Array.from(checkboxes)
                              .filter(cb => cb.checked)
                              .map(cb => cb.value);

      Array.from(pelaksanaSelect.options).forEach(option => {
          if(option.value === "") return; // placeholder
          option.style.display = selectedIds.includes(option.value) ? 'block' : 'none';
      });

      // Reset value dropdown jika tidak ada lagi di list
      if(!selectedIds.includes(pelaksanaSelect.value)) {
          pelaksanaSelect.value = "";
      }
  }

  // Event listeners
  checkboxes.forEach(cb => cb.addEventListener('change', updateCheckboxLimit));
  jumlahInput.addEventListener('input', () => {
      const maxTeknisi = parseInt(jumlahInput.value) || 0;
      const checked = document.querySelectorAll('.teknisi-checkbox:checked');

      // Jika checked lebih dari limit, hapus yang kelebihan
      if (checked.length > maxTeknisi) {
          checked.forEach((cb, index) => {
              if (index >= maxTeknisi) cb.checked = false;
          });
      }

      updateCheckboxLimit();
  });

  // Jalankan saat load untuk inisialisasi
  updateCheckboxLimit();
</script>

</body>

</html>

