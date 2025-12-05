<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Home - PT Sarana Agung Sejahtera</title>
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
          <a href="https://wa.me/628113492009" class="whatsapp"><i class="bi bi-whatsapp"></i></a>
          <a href="https://www.instagram.com/saranaagungsejahtera/" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="https://www.facebook.com/saranaagungsejahtera" class="facebook"><i class="bi bi-facebook"></i></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="{{ asset('assets/image/logo-sas.png') }}" alt="">
          <!-- <h1 class="sitename">SAS</h1> -->
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#beranda" class="active">Beranda<br></a></li>
            <li><a href="#tentangkami">Tentang Kami</a></li>
            <li><a href="#layanan">Layanan</a></li>
            <li><a href="#galeri">Galeri</a></li>
            {{-- <li><a href="#departments">Departments</a></li> --}}
            {{-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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
            </li> --}}
            <li><a href="#contact">Contact</a></li>
            {{-- <li><a href="/input-data-ac">Form Input Data</a></li> --}}
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-sm-block" href="/login">Login</a>

      </div>

    </div>

  </header>

  <main class="main">
    <!-- Hero Section -->
    <section id="beranda" class="hero section light-background">

      <img src="{{ asset('assets/img/hero.jpg') }}" alt="" data-aos="fade-in">

      <div class="container position-relative">

        <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
          <h2> Welcome <br> PT Sarana Agung Sejahtera</h2>
          <!-- <p>penyedia jasa service AC dan instalasi AC profesional <br> yang melayani berbagai kebutuhan pendingin ruangan <br> mulai dari hunian pribadi hingga fasilitas skala industri <br> seperti kantor, pabrik, dan rumah sakit.</p> -->
        </div><!-- End Welcome -->

        <div class="content row gy-4">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
              <h3>Kenapa Harus Sarana Agung?</h3>
              <p>
                Kami merupakan penyedia jasa service AC dan Instalasi AC Profesional yang melayani berbagai kebutuhan pendingin ruangan mulai dari hunian pribadi hingga fasilitas skala industri seperti kantor, pabrik, dan rumah sakit
              </p>
              <div class="text-center">
                <a href="#about" class="more-btn"><span>Lihat Selengkapnya</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="d-flex flex-column justify-content-center">
              <div class="row gy-4">

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-box-seam"></i>
                    <h4>Produk Terbaik</h4>
                    <p>Produk berkualitas sesuai standar pabrik AC, dengan harga service yang kompetitif, transparan, dan mengikuti standar industri.</p>
                  </div>
                </div><!-- End Icon Box -->

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                    <i class="bi bi-award"></i>
                    <h4>Profesional & Berpengalaman</h4>
                    <p>Tim teknisi kami terlatih, berpengalaman, dan siap membantu mengatasi berbagai masalah AC Anda.</p>
                  </div>
                </div><!-- End Icon Box -->

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                    <i class="bi bi-shield-check"></i>
                    <h4>Bergaransi Terpercaya</h4>
                    <p>Semua pelayanan kami bergaransi resmi untuk memberi Anda kenyamanan dan kepercayaan penuh.</p>
                  </div>
                </div><!-- End Icon Box -->

              </div>
            </div>
          </div>
        </div><!-- End  Content-->

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="tentangkami" class="about section">

      <div class="container">

        <div class="row gy-4 gx-5">

          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="200">
            <img src="assets/img/about-us.jpg" class="img-fluid" alt="">
            <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
          </div>

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <h3>Tentang Kami</h3>
            <p>
                PT. Sarana Agung Sejahtera adalah penyedia jasa service AC dan jasa instalasi AC profesional 
                yang telah berpengalaman menangani berbagai kebutuhan sistem pendingin ruangan. 
                Kami melayani berbagai segmen, mulai dari hunian pribadi, perkantoran, hingga fasilitas 
                industri berskala besar seperti pabrik dan rumah sakit.            
            </p>
            <p>
                Komitmen kami adalah memberikan layanan yang tepat waktu, bergaransi, dan didukung 
                oleh standar profesionalisme tinggi untuk memastikan kenyamanan serta efisiensi 
                sistem pendingin Anda.
            </p>
            <ul>
              <li>
                  <i class="fa-solid fa-award"></i>
                <div>
                  <h5>Profesional & Terpercaya</h5>
                  <p>Ditangani oleh teknisi berpengalaman dengan standar kerja profesional</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-gear"></i>
                <div>
                  <h5>Berpengalaman Menangani Berbagai Segmen</h5>
                  <p>Dari rumah tinggal hingga fasilitas industri seperti pabrik dan rumah sakit</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-user-shield"></i>
                <div>
                  <h5>Layanan Bergaransi</h5>
                  <p>layanan dilengkapi garansi resmi untuk memberikan rasa aman kepada anda</p>
                </div>
              </li>
            </ul>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-award"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="30" data-purecounter-duration="2" class="purecounter"></span>
              <p>Pengalaman</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-users-line"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="2000" data-purecounter-duration="2" class="purecounter"></span>
              <p>Pelanggan Setia</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-people-group"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2" class="purecounter"></span>
              <p>Team Profesional</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-box-open"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="100" data-purecounter-duration="2" class="purecounter"></span>
              <p>Produk Berkualitas</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="layanan" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Layanan Kami</h2>
        <p>Kami menyediakan layanan service AC dan instalasi AC profesional untuk hunian, kantor, dan industri</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="fa-solid fa-wrench"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Service AC</h3>
              </a>
              <p>Perbaikan dan maintenance AC untuk berbagai tipe, dengan teknisi berpengalaman dan bergaransi.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-plug"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Instalasi AC</h3>
              </a>
              <p>Pemasangan AC untuk rumah, kantor, dan fasilitas industri sesuai standar profesional dan aman.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Layanan Bergaransi</h3>
              </a>
              <p>Semua layanan kami dilengkapi garansi resmi untuk memberikan rasa aman dan kepercayaan penuh.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-sync-alt"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Perawatan Berkala</h3>
              </a>
              <p>Program perawatan rutin AC untuk menjaga performa optimal dan memperpanjang usia pakai AC.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-user-cog"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Konsultasi Teknis</h3>
              </a>
              <p>Kami memberikan saran dan solusi teknis untuk sistem pendingin ruangan sesuai kebutuhan anda. </p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-award"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Produk Berkualitas</h3>
              </a>
              <p>Kami menyediakan AC dan suku cadang asli berkualitas tinggi untuk memastikan kinerja optimal.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
        <p>Pertanyaan umum seputar layanan service AC, instalasi, dan garansi kami.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item">
                <h3>Apa saja jenis layanan service AC yang tersedia?</h3>
                <div class="faq-content">
                  <p>Kami menyediakan perbaikan AC, perawatan berkala, dan instalasi AC untuk rumah, kantor, dan fasilitas industri. Semua layanan dilakukan oleh teknisi berpengalaman dan profesional.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Apakah layanan service AC dilengkapi garansi?</h3>
                <div class="faq-content">
                  <p>Iya, betul sekali seluruh layanan kami dilengkapi dengan garansi resmi sesuai jenis layanan, sehingga anda mendapatkan rasa aman dan kepercayaan penuh.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Berapa lama waktu pengerjaan service AC?</h3>
                <div class="faq-content">
                  <p>Lama pengerjaan tergantung pada jenis AC dan kerusakan. Service standar biasanya selesai dalam beberapa jam hingga satu hari. Instalasi AC bisa lebih lama tergantung ukuran dan kompleksitas lokasi.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Apakah kalian menangani AC untuk industri dan gedung besar?</h3>
                <div class="faq-content">
                  <p>Iya, kami berpengalaman menangani berbagai fasilitas industri, kantor besar, rumah sakit, dan gedung komersial dengan standar profesional tinggi.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Apakah ada layanan perawatan berkala AC?</h3>
                <div class="faq-content">
                  <p>Kami menyediakan program perawatan rutin untuk menjaga performa AC tetap optimal dan memperpanjang umur pakai AC anda.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Bagaimana cara menghubungi teknisi atau membuat jadwal service?</h3>
                <div class="faq-content">
                  <p>Anda dapat menghubungi kami melalui telepon, WhatsApp, atau formulir kontak di website. Tim kami akan membantu jadwal service yang sesuai.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section><!-- /Faq Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <div class="container">

        <div class="row align-items-center">

          <div class="col-lg-5 info" data-aos="fade-up" data-aos-delay="100">
            <h3>Apa Kata Pelanggan Kami?</h3>
            <p>
                Testimonial nyata dari pelanggan yang puas dengan layanan service AC, instalasi, dan perawatan berkala yang kami berikan.
            </p>
          </div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">

            <div class="swiper init-swiper">
              <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": "auto",
                  "pagination": {
                    "el": ".swiper-pagination",
                    "type": "bullets",
                    "clickable": true
                  }
                }
              </script>
              <div class="swiper-wrapper">

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="d-flex">
                      <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img flex-shrink-0" alt="">
                      <div>
                        <h3>Andi Saputra</h3>
                        <h4>Manager &amp; Perkantoran</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>Teknisi sangat profesional dan cepat menangani AC kantor kami. Layanan bergaransi membuat kami merasa aman dan puas.</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                  </div>
                </div><!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="d-flex">
                      <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img flex-shrink-0" alt="">
                      <div>
                        <h3>Sri Wahyuni</h3>
                        <h4>Pemilik Rumah</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>AC rumah kami terpasang dengan rapi dan cepat. Teknisi memberi tips perawatan rutin sehingg AC tetap dingin maksimal.</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                  </div>
                </div><!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="d-flex">
                      <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img flex-shrink-0" alt="">
                      <div>
                        <h3>Budi Santoso</h3>
                        <h4>Owner Pabrik</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>Instalasi di pabrik kami dilakukan dengan sangat profesional. Hasilnya AC bekerja optimal dan hemat energi.</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                  </div>
                </div><!-- End testimonial item -->

                <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="d-flex">
                      <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img flex-shrink-0" alt="">
                      <div>
                        <h3>Lina Marlina</h3>
                        <h4>Owner Cafe</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>Layanan perawatan AC rutin sangat membantu menjaga kenyamanan pelanggan di cafe kami. Sangat direkomendasikan.</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                  </div>
                </div><!-- End testimonial item -->

                {{-- <div class="swiper-slide">
                  <div class="testimonial-item">
                    <div class="d-flex">
                      <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img flex-shrink-0" alt="">
                      <div>
                        <h3>John Larson</h3>
                        <h4>Entrepreneur</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                      </div>
                    </div>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                  </div>
                </div><!-- End testimonial item --> --}}

              </div>
              <div class="swiper-pagination"></div>
            </div>

          </div>

        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Gallery Section -->
    <section id="galeri" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Galeri</h2>
        <p>Dokumentasi layanan kami: instalasi AC, service, dan perawatan rutin di berbagai lokasi.</p>
      </div><!-- End Section Title -->

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/9.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/9.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/9.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/9.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/image/galeri/8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/image/galeri/8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Kontak Kami</h2>
        <p>Hubungi kami untuk layanan service, instalasi, atau konsultasi AC. Tim profesional kami siap membantu anda.</p>
      </div><!-- End Section Title -->

      <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
        <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.0947817191795!2d112.7711819!3d-7.343252799999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fad3aeb64337%3A0xb000b776c3d4b629!2sSarana%20Agung%20Sejahtera%20PT!5e0!3m2!1sid!2sid!4v1764123453303!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div><!-- End Google Maps -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Lokasi</h3>
                <p>Jl Raya Tmn Asri Jl. Palm Square City No.Ruko Kav TF/31, Palem, Wadungasri, Sidoarjo, Jawa Timur, 61256</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>No Telepon</h3>
                <p>+62 811-3492-009</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email</h3>
                <p>saranaagungsejahtera@gmail.com</p>
              </div>
            </div><!-- End Info Item -->

          </div>

          <div class="col-lg-8">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Anda" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Masukkan Email Anda" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Masukkan Subjek" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="6" placeholder="Masukkan Pesan" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Tunggu Sebentar</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Pesan kamu sudah terkirim. Terima Kasih!</div>

                  <button type="submit">Kirim Pesan</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

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
              <br>Jl Raya Tmn Asri Jl. Palm Square City <br> No.Ruko Kav TF/31, Palem, Wadungasri,</p>
            <p>Sidoarjo, Jawa Timur, 61256</p>
            <p><strong>Phone:</strong> <br> <span>+62 811-3492-009</span></p>
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

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>