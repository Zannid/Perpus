@extends('layouts.frontend')

@section('content')
<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section mt-5">
  <div class="container-fluid px-0" data-aos="fade-up" data-aos-delay="100">
    <div class="row justify-content-center mx-0">
      <div class="col-12" data-aos="zoom-in" data-aos-delay="200">
        <div class="hero-content text-center">
          <div class="hero-badge" data-aos="fade-down" data-aos-delay="300">
            <i class="bi bi-star-fill"></i>
            <span>Platform Perpustakaan Digital Terpercaya</span>
          </div>
          <h1 class="hero-title" data-aos="fade-up" data-aos-delay="400">E-Perpustakaan</h1>
          <p class="hero-description" data-aos="fade-up" data-aos-delay="500">Akses mudah ke ribuan koleksi buku digital terpercaya. Pinjam, baca, dan kembangkan pengetahuan Anda kapan saja, dimana saja dengan platform perpustakaan digital kami.</p>

          <!-- Book Slider Banner -->
          <div class="book-slider-banner" data-aos="fade-up" data-aos-delay="600">
            <div class="slider-container">
              <button class="slider-nav prev" onclick="moveSlide(-1)" aria-label="Slide sebelumnya">
                <i class="bi bi-chevron-left"></i>
              </button>

              <div class="slider-wrapper">
                <div class="book-slides" id="bookSlides">
                  @foreach($buku as $b)
                  <div class="book-slide active">
                    <div class="book-image">
                      <img src="{{ asset('/storage/buku/'. $b->foto) }}" alt="{{ $b->judul }}" loading="lazy">
                    </div>
                    <div class="book-details">
                      <h4 class="book-title">{{ $b->judul }}</h4>
                      <p class="book-author">oleh {{ $b->penulis }}</p>
                      <div class="book-rating">
                        <div class="stars">
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-half"></i>
                        </div>
                        <span class="rating-value">4.5</span>
                      </div>
                      <p class="book-desc">{{ $b->deskripsi }}</p>
                      <span class="book-badge">Tersedia</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>

              <button class="slider-nav next" onclick="moveSlide(1)" aria-label="Slide berikutnya">
                <i class="bi bi-chevron-right"></i>
              </button>
            </div>

            <div class="slider-dots" id="sliderDots"></div>
          </div>

          <div class="hero-metrics" data-aos="fade-up" data-aos-delay="700">
            <div class="metric-item">
              <div class="metric-icon">
                <i class="bi bi-book"></i>
              </div>
              <div class="metric-content">
                <span class="metric-number">5000+</span>
                <span class="metric-label">Koleksi Buku</span>
              </div>
            </div>
            <div class="metric-item">
              <div class="metric-icon">
                <i class="bi bi-people"></i>
              </div>
              <div class="metric-content">
                <span class="metric-number">2500+</span>
                <span class="metric-label">Anggota Aktif</span>
              </div>
            </div>
            <div class="metric-item">
              <div class="metric-icon">
                <i class="bi bi-download"></i>
              </div>
              <div class="metric-content">
                <span class="metric-number">15K+</span>
                <span class="metric-label">Peminjaman</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center gy-5">

          <div class="col-lg-5" data-aos="fade-right" data-aos-delay="200">
            <div class="image-section">
              <div class="primary-image">
                <img src="{{ asset('assetsf/img/about/about-14.webp') }}" alt="Perpustakaan Digital Modern" class="img-fluid" loading="lazy">
                <div class="experience-badge">
                  <div class="badge-content">
                    <span class="years">10+</span>
                    <span class="text">Tahun Melayani</span>
                  </div>
                </div>
              </div>
              <div class="image-grid">
                <img src="{{ asset('assetsf/img/about/about-3.webp') }}" alt="Koleksi Buku Digital" class="img-fluid grid-img" loading="lazy">
                <img src="{{ asset('assetsf/img/about/about-7.webp') }}" alt="Akses Mudah" class="img-fluid grid-img" loading="lazy">
              </div>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-left" data-aos-delay="300">
            <div class="content-section">
              <div class="section-intro">
                @if($about)
                  <div class="company-badge">TENTANG KAMI</div>
                  <h2>{{ $about->title }}</h2>
                  <p class="intro-text">{{ $about->content }}</p>
                @endif
              </div>

              <div class="achievement-list">
                <div class="achievement-item">
                  <div class="achievement-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                  </div>
                  <div class="achievement-content">
                    <h4>Pertumbuhan Berkelanjutan</h4>
                    <p>Kami terus mengembangkan koleksi dan fitur untuk memberikan pengalaman terbaik bagi setiap pengguna dalam mengakses pengetahuan.</p>
                  </div>
                </div>
                <div class="achievement-item">
                  <div class="achievement-icon">
                    <i class="bi bi-people-fill"></i>
                  </div>
                  <div class="achievement-content">
                    <h4>Tim Profesional</h4>
                    <p>Didukung oleh pustakawan berpengalaman dan teknisi IT handal yang siap membantu kebutuhan literasi digital Anda.</p>
                  </div>
                </div>
              </div>

              <div class="action-section">
                <a href="#" class="btn btn-primary">Pelajari Lebih Lanjut</a>
                <div class="contact-info">
                  <span class="contact-label">Hubungi kami:</span>
                  <strong class="phone-number">+62 812-3456-7890</strong>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="metrics-section" data-aos="fade-up" data-aos-delay="400">
          <div class="row text-center">
            <div class="col-lg-3 col-md-6">
              <div class="metric-card">
                <div class="metric-value">5000+</div>
                <div class="metric-label">Koleksi Buku Digital</div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="metric-card">
                <div class="metric-value">98%</div>
                <div class="metric-label">Kepuasan Pengguna</div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="metric-card">
                <div class="metric-value">2500+</div>
                <div class="metric-label">Anggota Terdaftar</div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="metric-card">
                <div class="metric-value">24/7</div>
                <div class="metric-label">Akses Online</div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Buku Terpopuler</h2>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="services-tabs">
          <ul class="nav nav-pills justify-content-center mb-5" id="services-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="services-development-tab" data-bs-toggle="pill" data-bs-target="#services-development" type="button" role="tab">Semua Buku</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="services-marketing-tab" data-bs-toggle="pill" data-bs-target="#services-marketing" type="button" role="tab">Fiksi</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="services-support-tab" data-bs-toggle="pill" data-bs-target="#services-support" type="button" role="tab">Non-Fiksi</button>
            </li>
          </ul>

          <div class="tab-content" id="services-tabs-content">
            <div class="tab-pane fade show active" id="services-development" role="tabpanel">
              <div class="row g-4">
                @foreach($bukuTerpopuler as $b)
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                  <div class="book-card">
                    <div class="book-cover-wrapper">
                      <img src="{{ asset('/storage/buku/'. $b->foto) }}" alt="{{ $b->judul }}" class="book-cover-img" loading="lazy">
                      <div class="book-overlay-hover">
                        <div class="book-quick-info">
                          <h5 class="book-title">{{ $b->judul }}</h5>
                          <p class="book-author">{{ $b->penulis }}</p>
                          <span class="book-status {{ $b->stok > 0 ? 'available' : 'unavailable' }}">
                            {{ $b->stok > 0 ? 'Tersedia' : 'Kosong' }}
                          </span>
                        </div>
                        <div class="book-actions">
                           <button type="button" class="btn-action btn-add-cart"
                                  data-buku-id="{{ $b->id }}" title="Tambah ke Keranjang">
                              <i class="bi bi-cart-plus"></i> Pinjam
                          </button>

                          <a href="{{ route('detail_buku', $b->id) }}" class="btn-action btn-detail" title="Detail Buku">
                            <i class="bi bi-info-circle"></i> Detail
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

            <div class="tab-pane fade" id="services-marketing" role="tabpanel">
              <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-book"></i>
                    </div>
                    <h5>Novel & Cerita</h5>
                    <p>Koleksi novel dan cerita fiksi dari berbagai genre yang menghibur dan menginspirasi pembaca.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-stars"></i>
                    </div>
                    <h5>Fantasi & Sci-Fi</h5>
                    <p>Jelajahi dunia imajinasi dengan koleksi buku fantasi dan fiksi ilmiah terpilih.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-heart"></i>
                    </div>
                    <h5>Romansa</h5>
                    <p>Kumpulan kisah cinta yang menyentuh hati dan menghangatkan perasaan pembaca.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="services-support" role="tabpanel">
              <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Pendidikan</h5>
                    <p>Buku-buku pendidikan dan akademik untuk mendukung proses pembelajaran di berbagai jenjang.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-lightbulb"></i>
                    </div>
                    <h5>Pengembangan Diri</h5>
                    <p>Koleksi buku motivasi dan pengembangan diri untuk meningkatkan kualitas hidup Anda.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                  <div class="tab-service-card">
                    <div class="service-icon">
                      <i class="bi bi-globe"></i>
                    </div>
                    <h5>Sejarah & Budaya</h5>
                    <p>Pelajari sejarah dan budaya dari berbagai belahan dunia melalui koleksi buku pilihan.</p>
                    <a href="service-details.html" class="tab-service-link">Lihat Detail</a>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- Features 2 Section -->


    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Hubungi Kami</h2>
        <p>Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda dengan senang hati</p>
      </div>
    <section id="faq" class="faq section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row justify-content-center">
          <div class="col-lg-10">

            <div class="faq-container">

              <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Bagaimana cara mendaftar sebagai anggota E-Perpustakaan?</h3>
                    <div class="answer">
                      <p>Anda dapat mendaftar dengan mudah melalui website kami. Cukup klik tombol "Daftar" di halaman utama, isi formulir pendaftaran dengan data diri yang valid, verifikasi email Anda, dan pilih paket keanggotaan yang sesuai. Setelah pembayaran dikonfirmasi, akun Anda akan aktif dan siap digunakan.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

              <div class="faq-item" data-aos="fade-up" data-aos-delay="250">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Berapa lama durasi peminjaman buku dan bagaimana cara perpanjangannya?</h3>
                    <div class="answer">
                      <p>Durasi peminjaman tergantung paket keanggotaan Anda, mulai dari 14 hingga 30 hari. Anda dapat memperpanjang peminjaman melalui dashboard akun Anda selama buku tersebut tidak sedang dipesan oleh anggota lain. Sistem akan mengirimkan notifikasi otomatis 3 hari sebelum masa peminjaman berakhir.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

              <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Apakah saya bisa mengakses E-Perpustakaan dari perangkat mobile?</h3>
                    <div class="answer">
                      <p>Tentu saja! E-Perpustakaan dirancang dengan teknologi responsive yang dapat diakses dari berbagai perangkat seperti smartphone, tablet, laptop, dan komputer desktop. Anda dapat membaca buku favorit kapan saja dan dimana saja dengan pengalaman yang optimal di semua perangkat.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

              <div class="faq-item" data-aos="fade-up" data-aos-delay="350">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Apa yang terjadi jika saya terlambat mengembalikan buku?</h3>
                    <div class="answer">
                      <p>Jika Anda terlambat mengembalikan buku, akan dikenakan denda keterlambatan sebesar Rp 1.000 per hari per buku. Namun, sistem kami akan mengirimkan pengingat otomatis sebelum jatuh tempo untuk membantu Anda menghindari denda. Anda juga dapat mengajukan perpanjangan sebelum masa peminjaman berakhir.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

              <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Apakah koleksi buku di E-Perpustakaan terus diperbarui?</h3>
                    <div class="answer">
                      <p>Ya, kami secara rutin menambah koleksi buku baru setiap bulan dari berbagai kategori dan penerbit terkemuka. Anggota akan mendapatkan notifikasi tentang buku-buku terbaru yang ditambahkan. Anda juga dapat mengajukan usulan buku yang ingin ditambahkan ke dalam koleksi kami melalui fitur Request Book.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

              <div class="faq-item" data-aos="fade-up" data-aos-delay="450">
                <div class="question-wrapper">
                  <div class="icon-wrapper">
                    <i class="bi bi-patch-question"></i>
                  </div>
                  <div class="content-wrapper">
                    <h3 class="question">Bagaimana cara membatalkan keanggotaan jika sudah tidak membutuhkan?</h3>
                    <div class="answer">
                      <p>Anda dapat membatalkan keanggotaan kapan saja melalui menu pengaturan akun. Pastikan Anda telah mengembalikan semua buku yang dipinjam dan tidak memiliki tunggakan denda. Setelah pembatalan, akses Anda akan tetap aktif hingga akhir periode berlangganan yang telah dibayar, dan tidak akan ada perpanjangan otomatis.</p>
                    </div>
                  </div>
                </div>
              </div><!-- End FAQ Item -->

            </div>

          </div>
        </div>

      </div>

    </section><!-- /Faq Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Hubungi Kami</h2>
        <p>Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda dengan senang hati</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-0">
          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="contact-info-panel">
              <div class="panel-content">
                <div class="info-header">
                  <h2>Mari Terhubung!</h2>
                  <p>Kami senang mendengar dari Anda. Hubungi kami melalui berbagai saluran komunikasi yang tersedia atau kunjungi langsung kantor kami untuk diskusi lebih lanjut.</p>
                </div>

                <div class="info-grid">
                  <div class="info-card" data-aos="slide-up" data-aos-delay="250">
                    <div class="card-icon">
                      <i class="bi bi-house-door"></i>
                    </div>
                    <div class="card-content">
                      <h4>Kunjungi Kantor Kami</h4>
                      <p>Jl. Merdeka No. 123<br>Bandung, Jawa Barat 40111</p>
                    </div>
                  </div>

                  <div class="info-card" data-aos="slide-up" data-aos-delay="300">
                    <div class="card-icon">
                      <i class="bi bi-chat-dots"></i>
                    </div>
                    <div class="card-content">
                      <h4>Kirim Email</h4>
                      <p>info@eperpustakaan.id<br>support@eperpustakaan.id</p>
                    </div>
                  </div>

                  <div class="info-card" data-aos="slide-up" data-aos-delay="350">
                    <div class="card-icon">
                      <i class="bi bi-headset"></i>
                    </div>
                    <div class="card-content">
                      <h4>Hubungi Langsung</h4>
                      <p>+62 812-3456-7890<br>+62 22-7654-3210</p>
                    </div>
                  </div>

                  <div class="info-card" data-aos="slide-up" data-aos-delay="400">
                    <div class="card-icon">
                      <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="card-content">
                      <h4>Jam Operasional</h4>
                      <p>Senin-Jumat: 08.00-17.00 WIB<br>Sabtu: 09.00-15.00 WIB</p>
                    </div>
                  </div>
                </div>

                <div class="social-section" data-aos="fade-in" data-aos-delay="450">
                  <h5>Ikuti Media Sosial Kami</h5>
                  <div class="social-icons">
                    <a href="#" class="social-icon">
                      <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-icon">
                      <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="social-icon">
                      <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="#" class="social-icon">
                      <i class="bi bi-instagram"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="slide-left" data-aos-delay="300">
            <div class="contact-form-wrapper">
              <div class="form-header">
                <h3>Kirim Pesan</h3>
                <div class="header-line"></div>
              </div>

              <form action="forms/contact.php" method="post" class="php-email-form modern-form">
                <div class="form-group">
                  <input type="text" name="name" class="form-control" id="fullName" placeholder="Nama Lengkap" required="">
                </div>

                <div class="form-group">
                  <input type="email" class="form-control" name="email" id="emailAddress" placeholder="Alamat Email" required="">
                </div>

                <div class="form-group">
                  <input type="tel" class="form-control" name="phone" id="phoneNumber" placeholder="Nomor Telepon">
                </div>

                <div class="form-group">
                  <input type="text" class="form-control" name="subject" id="emailSubject" placeholder="Subjek" required="">
                </div>

                <div class="form-group">
                  <textarea class="form-control" name="message" id="messageContent" rows="6" placeholder="Tulis pesan Anda di sini..." required=""></textarea>
                </div>

                <div class="my-3">
                  <div class="loading">Mengirim...</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Pesan Anda telah terkirim. Terima kasih!</div>
                </div>

                <button type="submit" class="submit-btn">
                  <span class="btn-text">Kirim Pesan</span>
                  <span class="btn-icon">
                    <i class="bi bi-arrow-right"></i>
                  </span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

</head>

</main>

<style>
/* ============================================================
   GLOBAL STYLES
   ============================================================ */

/* Hero Section */
.hero.section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  padding: 60px 0 40px;
  position: relative;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  overflow: hidden;
}

.hero.section::before {
  content: '';
  position: absolute;
  width: 400px;
  height: 400px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  top: -100px;
  right: -100px;
}

.hero.section::after {
  content: '';
  position: absolute;
  width: 300px;
  height: 300px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 50%;
  bottom: -80px;
  left: 10%;
}

.hero-content {
  width: 100%;
  position: relative;
  z-index: 2;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.95);
  padding: 12px 28px;
  border-radius: 50px;
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 600;
  color: #667eea;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  backdrop-filter: blur(10px);
  transition: transform 0.3s ease;
}

.hero-badge:hover {
  transform: translateY(-2px);
}

.hero-badge i {
  color: #ffd700;
  font-size: 16px;
}

.hero-title {
  font-size: 68px;
  font-weight: 900;
  margin-bottom: 25px;
  color: #fff;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
  letter-spacing: -1px;
}

.hero-description {
  font-size: 18px;
  color: rgba(255, 255, 255, 0.95);
  max-width: 800px;
  margin: 0 auto 50px;
  line-height: 1.8;
  font-weight: 300;
}

/* Book Slider Banner */
.book-slider-banner {
  margin: 60px 0;
  padding: 0;
  overflow: visible;
  width: 100%;
}

.slider-container {
  position: relative;
  width: 100%;
  margin: 0;
  padding: 0 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.slider-wrapper {
  overflow: hidden;
  border-radius: 30px;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(30px);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
  transition: box-shadow 0.3s ease;
  width: 100%;
  flex: 1;
  max-width: 100%;
}

.slider-wrapper:hover {
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
}

.book-slides {
  display: flex;
  width: 100%;
  transition: transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  will-change: transform;
}

.book-slide {
  min-width: 100%;
  flex-basis: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 100px;
  padding: 70px 100px;
  opacity: 0;
  transition: opacity 0.8s ease;
  visibility: hidden;
}

.book-slide.active {
  opacity: 1;
  visibility: visible;
  z-index: 10;
}

.book-image {
  flex-shrink: 0;
  width: 340px;
  height: 500px;
  border-radius: 25px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
  transform: perspective(1200px) rotateY(-8deg);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.book-slide.active .book-image {
  transform: perspective(1200px) rotateY(0deg);
}

.book-slide:hover .book-image {
  transform: perspective(1200px) rotateY(0deg) scale(1.08);
  box-shadow: 0 35px 80px rgba(0, 0, 0, 0.4);
}

.book-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.book-details {
  flex: 1;
  max-width: 620px;
  text-align: left;
  color: #2c3e50;
}

.book-title {
  font-size: 44px;
  font-weight: 800;
  margin-bottom: 12px;
  color: #2c3e50;
  line-height: 1.2;
}

.book-author {
  font-size: 18px;
  color: #667eea;
  margin-bottom: 20px;
  font-weight: 600;
}

.book-rating {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 24px;
}

.book-rating .stars {
  display: flex;
  gap: 3px;
}

.book-rating i {
  color: #ffd700;
  font-size: 18px;
}

.book-rating .rating-value {
  font-weight: 700;
  color: #2c3e50;
  font-size: 18px;
}

.book-desc {
  font-size: 16px;
  line-height: 1.8;
  color: #666;
  margin-bottom: 28px;
  font-weight: 300;
}

.book-badge {
  display: inline-block;
  padding: 10px 24px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 30px;
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.book-badge:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
}

/* Navigation Buttons */
.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(15px);
  border: 2px solid rgba(255, 255, 255, 0.3);
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  color: #667eea;
  z-index: 10;
  outline: none;
  padding: 0;
}

.slider-nav:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  transform: translateY(-50%) scale(1.15);
  box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
  border-color: transparent;
}

.slider-nav:active {
  transform: translateY(-50%) scale(1.05);
}

.slider-nav.prev {
  left: 10px;
}

.slider-nav.next {
  right: 10px;
}

.slider-nav i {
  font-size: 28px;
  font-weight: 700;
  pointer-events: none;
}

/* Dots Navigation */
.slider-dots {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 40px;
  flex-wrap: wrap;
}

.dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.2);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 2px solid transparent;
  outline: none;
  padding: 0;
  border: none;
}

.dot:hover {
  background: rgba(102, 126, 234, 0.5);
}

.dot.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  width: 40px;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Hero Metrics */
.hero-metrics {
  display: flex;
  justify-content: center;
  gap: 50px;
  margin-top: 70px;
  flex-wrap: wrap;
}

.metric-item {
  display: flex;
  align-items: center;
  gap: 18px;
  background: rgba(255, 255, 255, 0.95);
  padding: 24px 36px;
  border-radius: 20px;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
  backdrop-filter: blur(20px);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 1px solid rgba(255, 255, 255, 0.5);
}

.metric-item:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
}

.metric-icon {
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  color: #fff;
  font-size: 28px;
  flex-shrink: 0;
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.metric-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.metric-number {
  font-size: 32px;
  font-weight: 900;
  color: #2c3e50;
}

.metric-label {
  font-size: 13px;
  color: #999;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Features Grid */
.feature-item {
  padding: 30px;
  background: #fff;
  border-radius: 20px;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 1px solid #eee;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.feature-item:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 50px rgba(102, 126, 234, 0.15);
  border-color: #667eea;
}

.feature-icon {
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  color: #fff;
  font-size: 40px;
  margin: 0 auto 20px;
  box-shadow: 0 12px 30px rgba(102, 126, 234, 0.3);
}

.feature-content h4 {
  font-size: 20px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 12px;
}

.feature-content p {
  font-size: 14px;
  color: #999;
  line-height: 1.8;
  font-weight: 300;
}

/* Book Card Styling */
.book-card {
  position: relative;
  height: 100%;
  overflow: hidden;
  border-radius: 20px;
}

.book-cover-wrapper {
  position: relative;
  overflow: hidden;
  border-radius: 20px;
  background: #f5f7fa;
  aspect-ratio: 3/4;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.book-cover-wrapper:hover {
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
}

.book-cover-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.book-cover-wrapper:hover .book-cover-img {
  transform: scale(1.08);
}

.book-overlay-hover {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
  opacity: 0;
  transition: opacity 0.4s ease;
  padding: 20px;
}

.book-cover-wrapper:hover .book-overlay-hover {
  opacity: 1;
}

.book-quick-info {
  text-align: center;
  color: #fff;
}

.book-quick-info .book-title {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #fff;
}

.book-quick-info .book-author {
  font-size: 14px;
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
}

.book-status {
  display: inline-block;
  padding: 8px 18px;
  background: rgba(255, 255, 255, 0.25);
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  color: #fff;
  margin-top: 12px;
  backdrop-filter: blur(10px);
}

.book-actions {
  display: flex;
  gap: 12px;
  width: 100%;
}

.btn-action {
  flex: 1;
  padding: 12px;
  background: rgba(255, 255, 255, 0.25);
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.3s ease;
  cursor: pointer;
  backdrop-filter: blur(10px);
}

.btn-action:hover {
  background: rgba(255, 255, 255, 0.35);
  border-color: rgba(255, 255, 255, 0.8);
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .book-slide {
    gap: 60px;
    padding: 60px 80px;
  }

  .book-image {
    width: 300px;
    height: 450px;
  }

  .book-title {
    font-size: 40px;
  }

  .slider-container {
    padding: 0 60px;
  }

  .hero-title {
    font-size: 56px;
  }
}

@media (max-width: 992px) {
  .hero-title {
    font-size: 48px;
  }

  .slider-container {
    padding: 0 45px;
  }

  .book-slide {
    flex-direction: column;
    text-align: center;
    padding: 50px 30px;
    gap: 30px;
  }

  .book-image {
    width: 280px;
    height: 420px;
    transform: perspective(1000px) rotateY(0deg) !important;
  }

  .book-details {
    text-align: center;
    max-width: 100%;
  }

  .book-rating {
    justify-content: center;
  }

  .slider-nav {
    width: 50px;
    height: 50px;
  }

  .slider-nav i {
    font-size: 24px;
  }

  .hero-metrics {
    gap: 30px;
  }
}

@media (max-width: 768px) {
  .hero.section {
    padding: 40px 0 20px;
  }

  .hero-title {
    font-size: 36px;
  }

  .hero-description {
    font-size: 16px;
  }

  .hero-metrics {
    gap: 20px;
  }

  .slider-container {
    padding: 0 35px;
  }

  .book-slide {
    padding: 40px 20px;
    gap: 20px;
  }

  .book-image {
    width: 220px;
    height: 340px;
  }

  .book-title {
    font-size: 28px;
  }

  .book-author {
    font-size: 16px;
  }

  .book-desc {
    font-size: 15px;
  }

  .slider-nav {
    width: 45px;
    height: 45px;
  }

  .slider-nav i {
    font-size: 20px;
  }

  .slider-dots {
    margin-top: 30px;
    gap: 8px;
  }

  .dot {
    width: 12px;
    height: 12px;
  }

  .dot.active {
    width: 32px;
  }

  .metric-item {
    padding: 18px 24px;
  }

  .metric-number {
    font-size: 24px;
  }

  .metric-icon {
    width: 50px;
    height: 50px;
    font-size: 24px;
  }
}

@media (max-width: 576px) {
  .hero.section {
    padding: 30px 0 20px;
    min-height: auto;
  }

  .hero-title {
    font-size: 28px;
    margin-bottom: 15px;
  }

  .hero-description {
    font-size: 15px;
    margin-bottom: 25px;
  }

  .slider-container {
    padding: 0 15px;
  }

  .book-slide {
    padding: 20px 10px;
    gap: 15px;
  }

  .book-image {
    width: 180px;
    height: 280px;
  }

  .book-title {
    font-size: 22px;
  }

  .book-author {
    font-size: 14px;
  }

  .book-details {
    max-width: 100%;
  }

  .slider-nav {
    width: 40px;
    height: 40px;
  }

  .slider-nav i {
    font-size: 18px;
  }

  .slider-nav.prev {
    left: 5px;
  }

  .slider-nav.next {
    right: 5px;
  }

  .book-slider-banner {
    margin: 40px 0;
  }

  .slider-dots {
    margin-top: 25px;
    gap: 6px;
  }

  .dot {
    width: 10px;
    height: 10px;
  }

  .dot.active {
    width: 28px;
  }

  .hero-metrics {
    flex-direction: column;
    gap: 15px;
    margin-top: 40px;
  }

  .metric-item {
    width: 100%;
    max-width: 280px;
    margin: 0 auto;
  }

  .services-tabs {
    align-items: center;
  }

  .nav-pills {
    flex-wrap: wrap;
    gap: 8px !important;
  }

  .nav-pills .nav-link {
    padding: 8px 16px;
    font-size: 13px;
  }
}
</style>

<script>
// Book Slider Functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.book-slide');
const totalSlides = slides.length;
let autoSlideInterval;

// Create dots
function createDots() {
  const dotsContainer = document.getElementById('sliderDots');
  if (!dotsContainer) return;

  for (let i = 0; i < totalSlides; i++) {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if (i === 0) dot.classList.add('active');
    dot.setAttribute('data-slide', i);
    dot.onclick = () => goToSlide(i);
    dot.setAttribute('role', 'button');
    dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
    dotsContainer.appendChild(dot);
  }
}

// Update dots
function updateDots() {
  const dots = document.querySelectorAll('.dot');
  dots.forEach((dot, index) => {
    dot.classList.toggle('active', index === currentSlide);
  });
}

// Move slide
function moveSlide(direction) {
  if (slides.length === 0) return;

  slides[currentSlide].classList.remove('active');
  currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
  slides[currentSlide].classList.add('active');

  const slidesContainer = document.getElementById('bookSlides');
  if (slidesContainer) {
    slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
  }

  updateDots();
  resetAutoSlide();
}

// Go to specific slide
function goToSlide(index) {
  if (index < 0 || index >= totalSlides) return;

  slides[currentSlide].classList.remove('active');
  currentSlide = index;
  slides[currentSlide].classList.add('active');

  const slidesContainer = document.getElementById('bookSlides');
  if (slidesContainer) {
    slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
  }

  updateDots();
  resetAutoSlide();
}

// Auto slide
function startAutoSlide() {
  autoSlideInterval = setInterval(() => {
    moveSlide(1);
  }, 6000);
}

// Reset auto slide
function resetAutoSlide() {
  clearInterval(autoSlideInterval);
  startAutoSlide();
}

// Touch/Swipe support
let touchStartX = 0;
let touchEndX = 0;

function handleSwipe() {
  if (touchEndX < touchStartX - 50) {
    moveSlide(1); // Swipe left -> next slide
  }
  if (touchEndX > touchStartX + 50) {
    moveSlide(-1); // Swipe right -> prev slide
  }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
  if (totalSlides > 0) {
    createDots();
    startAutoSlide();

    // Pause on hover
    const sliderContainer = document.querySelector('.slider-container');
    if (sliderContainer) {
      sliderContainer.addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
      });

      sliderContainer.addEventListener('mouseleave', () => {
        startAutoSlide();
      });

      // Touch events
      sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
      });

      sliderContainer.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
      });
    }
  }
});

// Keyboard navigation
document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') moveSlide(-1);
  if (e.key === 'ArrowRight') moveSlide(1);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  $('.btn-add-cart').click(function(e){
    e.preventDefault();
    let bukuId = $(this).data('buku-id');

    $.ajax({
      url: '{{ route("cart.add") }}',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        buku_id: bukuId,
        quantity: 1
      }),
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(res){
        if(res.success){
          // Gunakan function updateCartFromOutside dari navbar untuk auto-update cart dropdown
          if(typeof updateCartFromOutside !== 'undefined'){
            updateCartFromOutside(res);
          } else {
            // Fallback jika function tidak tersedia
            $('.cart-badge').text(res.totalItems).show();
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: res.message,
              timer: 1500,
              showConfirmButton: false
            });
          }
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: res.message,
            timer: 1500,
            showConfirmButton: false
          });
        }
      },
      error: function(xhr){
        let errorMessage = 'Terjadi kesalahan, coba lagi!';
        if(xhr.responseJSON && xhr.responseJSON.message){
          errorMessage = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: errorMessage,
        });
      }
    });

  });

});
</script>
@endsection
