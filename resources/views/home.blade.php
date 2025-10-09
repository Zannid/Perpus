<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Dashboard - Analytics</title>

  <!-- Favicon -->
  <link rel="icon" href="../assets/img/logo1.png" />b

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/js/config.js"></script>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      @include('layouts.part.sidebar')

      <div class="layout-page">
        @include('layouts.part.navbar')

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <!-- Welcome Card -->
              <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                      <div class="card-body">
                        <h5 class="card-title text-primary">
                          Selamat datang {{ Auth::user()->name }} 🎉
                        </h5>
                        <p class="mb-4">
                          Anda telah menyelesaikan <span class="fw-bold">72%</span> lebih banyak transaksi hari ini.
                        </p>
                        <a href="javascript:;" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                      </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                      <div class="card-body pb-0 px-0 px-md-4">
                        <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="User Illustration" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistik Buku & Rak -->
              <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                <div class="col-lg-6 col-md-12 col-3 mb-4">
                  <div class="card">
                    <div class="card-body d-flex align-items-center">
                      <div class="avatar flex-shrink-0 bg-primary rounded p-2 me-3">
                        <i class="bx bx-book text-white fs-4"></i>
                      </div>
                      <div>
                        <span class="fw-semibold d-block mb-1">Buku</span>
                        <h3 class="card-title mb-0">{{ \App\Models\Buku::count() }}</h3>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Rak -->
                <div class="col-lg-6 col-md-12 col-3 mb-4">
                  <div class="card">
                    <div class="card-body d-flex align-items-center">
                      <div class="avatar flex-shrink-0 bg-info rounded p-2 me-3">
                        <i class="bx bx-grid-alt text-white fs-4"></i>
                      </div>
                      <div>
                        <span class="fw-semibold d-block mb-1">Rak</span>
                        <h3 class="card-title mb-0">{{ \App\Models\Lokasi::count() }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-lg-6 col-md-12 col-3 mb-4">
                  <div class="card">
                    <div class="card-body d-flex align-items-center">
                      <div class="avatar flex-shrink-0 bg-success rounded p-2 me-3">
                        <i class="bx bx-log-in-circle text-white fs-4"></i>
                      </div>
                      <div>
                        <span class="fw-semibold d-block mb-1">Barang Masuk</span>
                        <h3 class="card-title mb-0">{{ \App\Models\BarangMasuk::count() }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 bg-danger rounded p-2 me-3">
            <i class="bx bx-log-out-circle text-white fs-4"></i>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1">Barang Keluar</span>
            <h3 class="card-title mb-0">{{ \App\Models\BarangKeluar::count() }}</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 bg-warning rounded p-2 me-3">
            <i class="bx bx-user-pin text-white fs-4"></i>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1">Petugas</span>
            <h3 class="card-title mb-0">{{ \App\Models\User::where('role', 'petugas')->count() }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- User -->
    <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body d-flex align-items-center">
          <div class="avatar flex-shrink-0 bg-secondary rounded p-2 me-3">
            <i class="bx bx-user text-white fs-4"></i>
          </div>
          <div>
            <span class="fw-semibold d-block mb-1">User</span>
            <h3 class="card-title mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h3>
          </div>
        </div>
      </div>
    </div>
                </div>
              </div>

              <!-- Chart Peminjaman & Pengembalian -->
              <div class="col-md-12 col-lg-8 order-1 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <ul class="nav nav-pills" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-peminjaman">
                          Peminjaman
                        </button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengembalian">
                          Pengembalian
                        </button>
                      </li>
                    </ul>
                  </div>

                  <div class="card-body p-4">
                    <div class="tab-content p-0">
                      <!-- Tab Peminjaman -->
                      <div class="tab-pane fade show active" id="tab-peminjaman">
                        <div class="d-flex align-items-center mb-3">
                          <div>
                            <small class="text-muted d-block">Total Peminjaman</small>
                            <h4 class="mb-0">{{ array_sum($dataPeminjaman) }}</h4>
                          </div>
                        </div>
                        <div id="chartPeminjaman" style="min-height: 350px;"></div>
                      </div>

                      <!-- Tab Pengembalian -->
                      <div class="tab-pane fade" id="tab-pengembalian">
                        <div class="d-flex align-items-center mb-3">
                          <div>
                            <small class="text-muted d-block">Total Pengembalian</small>
                            <h4 class="mb-0">{{ array_sum($dataPengembalian) }}</h4>
                          </div>
                        </div>
                        <div id="chartPengembalian" style="min-height: 350px;"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statistik Barang & User -->
            </div> <!-- end row -->
          </div>
        </div>

        <footer class="content-footer footer bg-footer-theme">
          <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
              © <script>document.write(new Date().getFullYear());</script>,
              ZannId<a href="https://themeselection.com" class="footer-link fw-bolder"></a>
            </div>
          </div>
        </footer>

        <div class="content-backdrop fade"></div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const bulanLabels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

      // Chart Peminjaman
      new ApexCharts(document.querySelector("#chartPeminjaman"), {
        chart: { type: "area", height: 200, toolbar: { show: false } },
        series: [{ name: "Peminjaman", data: @json($dataPeminjaman) }],
        xaxis: { categories: bulanLabels },
        colors: ["#7367F0"],
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: 2 }
      }).render();

      // Chart Pengembalian
      new ApexCharts(document.querySelector("#chartPengembalian"), {
        chart: { type: "area", height: 200, toolbar: { show: false } },
        series: [{ name: "Pengembalian", data: @json($dataPengembalian) }],
        xaxis: { categories: bulanLabels },
        colors: ["#00CFE8"],
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: 2 }
      }).render();
    });
  </script>

  <!-- Vendor JS -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/dashboards-analytics.js"></script>
</body>
</html>
