<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/')}}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title', 'E-Perpus')</title>


    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/logo1.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
 @yield('css')
 @include('sweetalert::alert')

    <!-- Page CSS -->

    <style>
    .page-loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.78);
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
        padding: 22px 24px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(14px);
        text-align: center;
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.2);
    }
    .page-loader-ring {
        width: 60px;
        height: 60px;
        margin: 0 auto 18px;
        border: 6px solid rgba(255, 255, 255, 0.2);
        border-top-color: #0d6efd;
        border-radius: 50%;
        animation: pageLoaderSpin 1s linear infinite;
    }
    .page-loading-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #ffffff;
    }
    .page-loading-text {
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.6;
    }
    @keyframes pageLoaderSpin {
        to { transform: rotate(360deg); }
    }
    </style>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js')}}"></script>
  </head>

  <body>
    <div id="pageLoadingOverlay" class="page-loading-overlay">
      <div class="page-loading-card">
        <div class="page-loader-ring"></div>
        <div class="page-loading-title">Memuat halaman...</div>
        <div class="page-loading-text">Tunggu sebentar, perpustakaan digital sedang menyiapkan konten.</div>
      </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

@include('layouts.part.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

@include('layouts.part.navbar')

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                          <div class="row">
                            @yield('content')

                          </div>
                        </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  ZannId
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder"></a>
                </div>
                <div>

                  <a
                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    ></a
                  >

                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link me-4"
                    ></a
                  >
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js')}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            setTimeout(function () {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 350);
        }

        // Gunakan setTimeout untuk memastikan DOM benar-benar siap
        setTimeout(function() {
            const body = document.body;
            const layoutMenu = document.getElementById('layout-menu');
            const sidebarToggleBtns = document.querySelectorAll('.layout-menu-toggle');

            // Restore state dari localStorage saat halaman dimuat
            if (localStorage.getItem('sidebarState') === 'collapsed') {
                body.classList.add('layout-menu-collapsed');
                if (layoutMenu) {
                    layoutMenu.classList.add('collapsed');
                }
            }

            // Gunakan event delegation - attach ke semua toggle button
            if (sidebarToggleBtns.length > 0) {
                sidebarToggleBtns.forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        body.classList.toggle('layout-menu-collapsed');
                        if (layoutMenu) {
                            layoutMenu.classList.toggle('collapsed');
                        }

                        // Simpan state ke localStorage
                        localStorage.setItem(
                            'sidebarState',
                            body.classList.contains('layout-menu-collapsed') ? 'collapsed' : 'expanded'
                        );
                    });
                });
            }
        }, 100); // Delay 100ms untuk memastikan semua element siap
    });
    </script>
@yield('js')
  </body>
</html>
