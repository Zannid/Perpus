<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Home - E-Perpustakaan</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
    <link rel="icon" href="{{ asset('assets/img/logo1.png') }}" />
  <link href="{{ asset('assetsf/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assetsf/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assetsf/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{ asset('assetsf/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{ asset('assetsf/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assetsf/css/main.css')}}" rel="stylesheet">
  @yield('css')

  <!-- =======================================================
  * Template Name: Landio
  * Template URL: https://bootstrapmade.com/landio-bootstrap-landing-page-template/
  * Updated: Sep 06 2025 with Bootstrap v5.3.8
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
	.book-card {
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  height: 400px;
}

.book-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

.book-cover-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.book-cover-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease, filter 0.3s ease;
}

.book-card:hover .book-cover-img {
  transform: scale(1.1);
  filter: brightness(0.4);
}

.book-overlay-hover {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.8));
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 25px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.book-card:hover .book-overlay-hover {
  opacity: 1;
}

.book-quick-info {
  text-align: center;
  color: white;
  transform: translateY(-20px);
  transition: transform 0.3s ease;
}

.book-card:hover .book-quick-info {
  transform: translateY(0);
}

.book-title {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 8px;
  color: white;
  line-height: 1.3;
}

.book-author {
  font-size: 15px;
  color: #e0e0e0;
  margin-bottom: 12px;
  font-style: italic;
}

.book-status {
  display: inline-block;
  padding: 6px 18px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.book-status.available {
  background: #28a745;
  color: white;
}

.book-status.borrowed {
  background: #dc3545;
  color: white;
}

.book-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  transform: translateY(20px);
  transition: transform 0.3s ease;
}

.book-card:hover .book-actions {
  transform: translateY(0);
}

.btn-action {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  text-align: center;
}

.btn-cover {
  background: rgba(255, 255, 255, 0.95);
  color: #333;
  border: 2px solid transparent;
}

.btn-cover:hover {
  background: white;
  border-color: #007bff;
  color: #007bff;
  transform: translateX(5px);
}

.btn-detail {
  background: #007bff;
  color: white;
  border: 2px solid #007bff;
}

.btn-detail:hover {
  background: #0056b3;
  border-color: #0056b3;
  transform: translateX(5px);
}

.btn-action i {
  font-size: 16px;
}

  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .page-loading-overlay {
      position: fixed;
      inset: 0;
      z-index: 3000;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(9, 30, 66, 0.78);
      color: #ffffff;
      transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .page-loading-overlay.hidden {
      opacity: 0;
      visibility: hidden;
    }
    .page-loading-card {
      max-width: 320px;
      width: 100%;
      padding: 24px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(12px);
      text-align: center;
      box-shadow: 0 24px 50px rgba(0,0,0,0.18);
    }
    .page-loader-ring {
      width: 56px;
      height: 56px;
      margin: 0 auto 16px;
      border: 6px solid rgba(255,255,255,0.24);
      border-top-color: #0d6efd;
      border-radius: 50%;
      animation: pageLoaderSpin 1s linear infinite;
    }
    .page-loading-title {
      margin-bottom: 8px;
      font-size: 1rem;
      font-weight: 700;
      color: #ffffff;
    }
    .page-loading-text {
      font-size: 0.95rem;
      color: rgba(255,255,255,0.8);
      line-height: 1.6;
    }
    @keyframes pageLoaderSpin { to { transform: rotate(360deg); } }
  </style>
</head>

<body class="index-page">
  <div id="pageLoadingOverlay" class="page-loading-overlay">
    <div class="page-loading-card">
      <div class="page-loader-ring"></div>
      <div class="page-loading-title">Memuat halaman...</div>
      <div class="page-loading-text">Sebentar lagi, konten perpustakaan digital akan tampil.</div>
    </div>
  </div>

@include('layouts.partf.navbar')

@yield('content')

  <!-- ======= Footer ======= -->

@include('layouts.partf.footer')
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assetsf/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assetsf/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{ asset('assetsf/vendor/aos/aos.js')}}"></script>
  <script src="{{ asset('assetsf/vendor/swiper/swiper-bundle.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assetsf/js/main.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var overlay = document.getElementById('pageLoadingOverlay');
      if (overlay) {
        overlay.classList.add('hidden');
        setTimeout(function() { overlay.remove(); }, 250);
      }
    });
  </script>

</body>

</html>
