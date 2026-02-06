<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Dashboard - Analytics</title>

  <!-- Favicon -->
  <link rel="icon" href="../assets/img/logo1.png" />

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

  <style>
    /* Bar Chart Styles */
    .bar-chart {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      height: 300px;
      gap: 8px;
      padding: 20px 10px;
    }

    .bar-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      min-width: 0;
    }

    .bar {
      width: 100%;
      background: linear-gradient(180deg, #7367F0 0%, #A084DC 100%);
      border-radius: 8px 8px 0 0;
      transition: all 0.3s ease;
      position: relative;
      min-height: 10px;
    }

    .bar:hover {
      transform: scaleY(1.05);
      box-shadow: 0 -4px 12px rgba(115, 103, 240, 0.3);
    }

    .bar-value {
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 12px;
      font-weight: 600;
      color: #7367F0;
      white-space: nowrap;
    }

    .bar-label {
      margin-top: 8px;
      font-size: 11px;
      color: #6c757d;
      text-align: center;
      font-weight: 500;
    }

    /* Tab Styles */
    .chart-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      border-bottom: 2px solid #e9ecef;
    }

    .chart-tab {
      padding: 10px 20px;
      border: none;
      background: transparent;
      color: #6c757d;
      font-weight: 600;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      transition: all 0.3s ease;
    }

    .chart-tab.active {
      color: #7367F0;
      border-bottom-color: #7367F0;
    }

    .chart-tab:hover {
      color: #7367F0;
    }

    .chart-content {
      display: none;
    }

    .chart-content.active {
      display: block;
    }

    /* Stat Cards Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .stat-card-animated {
      animation: fadeInUp 0.5s ease;
    }

    /* User Dashboard Styles */
    .gradient-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      overflow: hidden;
      position: relative;
    }

    .gradient-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: translate(30%, -30%);
    }

    .stat-card {
      border: none;
      border-radius: 15px;
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
    }

    .book-card {
      border: none;
      border-radius: 12px;
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .book-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .book-img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }

    .progress-custom {
      height: 8px;
      border-radius: 10px;
      background-color: #e9ecef;
    }

    .progress-custom .progress-bar {
      border-radius: 10px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .activity-item {
      padding: 15px;
      border-radius: 10px;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    .activity-item:hover {
      background-color: #f8f9fa;
      border-left-color: #667eea;
    }

    .quick-action-btn {
      border: none;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .quick-action-btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .quick-action-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #667eea;
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      width: 20px;
      height: 20px;
      background: #ff4757;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      color: white;
      font-weight: 700;
    }

    @media (max-width: 768px) {
      .stat-card {
        margin-bottom: 15px;
      }
      .book-card {
        margin-bottom: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      @include('layouts.part.sidebar')

      <div class="layout-page">
        @include('layouts.part.navbar')

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">

            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
            <!-- ADMIN/PETUGAS DASHBOARD -->
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
                  <!-- Buku -->
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card stat-card-animated">
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
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card stat-card-animated" style="animation-delay: 0.1s;">
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

                  <!-- Barang Masuk -->
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card stat-card-animated" style="animation-delay: 0.2s;">
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

                  <!-- Barang Keluar -->
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card stat-card-animated" style="animation-delay: 0.3s;">
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

                  <!-- Petugas -->
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card stat-card-animated" style="animation-delay: 0.4s;">
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
                    <div class="card stat-card-animated" style="animation-delay: 0.5s;">
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
                    <div class="chart-tabs">
                      <button type="button" class="chart-tab active" onclick="switchTab('peminjaman')">
                        <i class="bx bx-book-open me-1"></i> Peminjaman
                      </button>
                      <button type="button" class="chart-tab" onclick="switchTab('pengembalian')">
                        <i class="bx bx-book-bookmark me-1"></i> Pengembalian
                      </button>
                    </div>
                  </div>

                  <div class="card-body p-4">
                    <!-- Tab Peminjaman -->
                    <div class="chart-content active" id="tab-peminjaman">
                      <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                          <small class="text-muted d-block">Total Peminjaman</small>
                          <h4 class="mb-0">{{ array_sum($dataPeminjaman) }}</h4>
                        </div>
                        <span class="badge bg-label-primary">Tahun Ini</span>
                      </div>
                      <div class="bar-chart">
                        @php
                          $maxPeminjaman = max($dataPeminjaman) ?: 1;
                        @endphp
                        @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $bulan)
                          <div class="bar-item">
                            <div class="bar" style="height: {{ ($dataPeminjaman[$index] / $maxPeminjaman) * 100 }}%">
                              @if($dataPeminjaman[$index] > 0)
                                <span class="bar-value">{{ $dataPeminjaman[$index] }}</span>
                              @endif
                            </div>
                            <div class="bar-label">{{ $bulan }}</div>
                          </div>
                        @endforeach
                      </div>
                    </div>

                    <!-- Tab Pengembalian -->
                    <div class="chart-content" id="tab-pengembalian">
                      <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                          <small class="text-muted d-block">Total Pengembalian</small>
                          <h4 class="mb-0">{{ array_sum($dataPengembalian) }}</h4>
                        </div>
                        <span class="badge bg-label-info">Tahun Ini</span>
                      </div>
                      <div class="bar-chart">
                        @php
                          $maxPengembalian = max($dataPengembalian) ?: 1;
                        @endphp
                        @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $bulan)
                          <div class="bar-item">
                            <div class="bar" style="height: {{ ($dataPengembalian[$index] / $maxPengembalian) * 100 }}%; background: linear-gradient(180deg, #00CFE8 0%, #5EDAFF 100%);">
                              @if($dataPengembalian[$index] > 0)
                                <span class="bar-value" style="color: #00CFE8;">{{ $dataPengembalian[$index] }}</span>
                              @endif
                            </div>
                            <div class="bar-label">{{ $bulan }}</div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @else
            <!-- USER DASHBOARD -->
            <!-- Welcome Banner -->
            <div class="row">
              <div class="col-12 mb-4">
                <div class="card gradient-card">
                  <div class="card-body p-4">
                    <div class="row align-items-center">
                      <div class="col-md-8">
                        <h3 class="mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h3>
                        <p class="mb-3 opacity-75">Jelajahi koleksi buku terbaru kami dan nikmati pengalaman membaca yang menyenangkan</p>
                        <div class="d-flex gap-2">
                          <a href="{{ route('katalog') }}" class="btn btn-light btn-sm">
                            <i class="bx bx-book me-1"></i> Jelajahi Katalog
                          </a>
                          <a href="{{ route('keranjang.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bx bx-cart me-1"></i> Lihat Keranjang
                          </a>
                        </div>
                      </div>
                      <div class="col-md-4 text-center d-none d-md-block">
                        <img src="../assets/img/illustrations/man-with-laptop-light.png" height="180" alt="Reading" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
              <!-- Sedang Dipinjam -->
              <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="stat-icon bg-label-primary me-3">
                        <i class="bx bx-book-open text-primary"></i>
                      </div>
                      <div class="flex-grow-1">
                        <span class="d-block mb-1 text-muted">Sedang Dipinjam</span>
                        <h4 class="mb-0">{{ $peminjamanAktif ?? 0 }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Total Peminjaman -->
              <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="stat-icon bg-label-success me-3">
                        <i class="bx bx-history text-success"></i>
                      </div>
                      <div class="flex-grow-1">
                        <span class="d-block mb-1 text-muted">Total Peminjaman</span>
                        <h4 class="mb-0">{{ $totalPeminjaman ?? 0 }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Buku Favorit -->
              <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="stat-icon bg-label-warning me-3">
                        <i class="bx bx-heart text-warning"></i>
                      </div>
                      <div class="flex-grow-1">
                        <span class="d-block mb-1 text-muted">Buku Favorit</span>
                        <h4 class="mb-0">{{ $bukuFavorit ?? 0 }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Denda Aktif -->
              <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card">
                  <div class="card-body">
                    <div class="d-flex align-items-center position-relative">
                      <div class="stat-icon bg-label-danger me-3">
                        <i class="bx bx-error text-danger"></i>
                      </div>
                      <div class="flex-grow-1">
                        <span class="d-block mb-1 text-muted">Denda Aktif</span>
                        <h4 class="mb-0">Rp {{ number_format($dendaAktif ?? 0) }}</h4>
                      </div>
                      @if(($dendaAktif ?? 0) > 0)
                        <span class="notification-badge">!</span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Left Column -->
              <div class="col-lg-8 mb-4">

                <!-- Quick Actions -->
                <div class="mb-4">
                  <h5 class="section-title">
                    <i class="bx bx-zap"></i>
                    Aksi Cepat
                  </h5>
                  <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                      <a href="{{ route('katalog') }}" class="quick-action-btn d-block text-decoration-none">
                        <div class="quick-action-icon bg-label-primary">
                          <i class="bx bx-search text-primary"></i>
                        </div>
                        <div class="fw-semibold text-dark">Cari Buku</div>
                      </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <a href="{{ route('keranjang.index') }}" class="quick-action-btn d-block text-decoration-none">
                        <div class="quick-action-icon bg-label-success">
                          <i class="bx bx-cart text-success"></i>
                        </div>
                        <div class="fw-semibold text-dark">Keranjang</div>
                      </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <a href="{{ route('peminjaman.index') }}" class="quick-action-btn d-block text-decoration-none">
                        <div class="quick-action-icon bg-label-info">
                          <i class="bx bx-book-bookmark text-info"></i>
                        </div>
                        <div class="fw-semibold text-dark">Peminjaman</div>
                      </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <a href="{{ route('profile.index') }}" class="quick-action-btn d-block text-decoration-none">
                        <div class="quick-action-icon bg-label-warning">
                          <i class="bx bx-user text-warning"></i>
                        </div>
                        <div class="fw-semibold text-dark">Profil</div>
                      </a>
                    </div>
                  </div>
                </div>

                <!-- Buku Sedang Dipinjam -->
                <div class="card mb-4">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                      <i class="bx bx-book-open me-2 text-primary"></i>
                      Buku Sedang Dipinjam
                    </h5>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                  </div>
                  <div class="card-body">
                    @if(isset($peminjamanTerbaru) && $peminjamanTerbaru->count() > 0)
                      @foreach($peminjamanTerbaru as $peminjaman)
                        <div class="activity-item mb-3">
                          <div class="row align-items-center">
                            <div class="col-md-2 col-3">
                              <img src="{{ asset('storage/buku/' . $peminjaman->buku->foto) }}"
                                   alt="{{ $peminjaman->buku->judul }}"
                                   class="img-fluid rounded">
                            </div>
                            <div class="col-md-7 col-9">
                              <h6 class="mb-1">{{ $peminjaman->buku->judul }}</h6>
                              <small class="text-muted d-block mb-1">
                                <i class="bx bx-calendar me-1"></i>
                                Dipinjam: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }}
                              </small>
                              <small class="text-muted">
                                <i class="bx bx-time me-1"></i>
                                Batas: {{ \Carbon\Carbon::parse($peminjaman->tenggat)->format('d M Y') }}
                              </small>
                            </div>
                            <div class="col-md-3 col-12 text-md-end mt-2 mt-md-0">
                              @php
                                $now = \Carbon\Carbon::now();
                                $tenggat = \Carbon\Carbon::parse($peminjaman->tenggat);
                                $sisaHari = (int) $now->diffInDays($tenggat, false);
                              @endphp
                              @if($sisaHari < 0)
                                <span class="badge bg-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                              @elseif($sisaHari == 0)
                                <span class="badge bg-warning">Hari terakhir</span>
                              @elseif($sisaHari <= 3)
                                <span class="badge bg-warning">{{ $sisaHari }} hari lagi</span>
                              @else
                                <span class="badge bg-success">{{ $sisaHari }} hari lagi</span>
                              @endif
                            </div>
                          </div>

                          @if($sisaHari >= 0)
                            <div class="mt-2">
                              <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Progress waktu peminjaman</small>
                                <small class="text-muted">{{ 100 - round(($sisaHari / 7) * 100) }}%</small>
                              </div>
                              <div class="progress progress-custom">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ 100 - round(($sisaHari / 7) * 100) }}%"></div>
                              </div>
                            </div>
                          @endif
                        </div>
                      @endforeach
                    @else
                      <div class="text-center py-4">
                        <i class="bx bx-book fs-1 text-muted mb-2"></i>
                        <p class="text-muted">Belum ada buku yang dipinjam</p>
                        <a href="{{ route('katalog') }}" class="btn btn-sm btn-primary">Mulai Pinjam Buku</a>
                      </div>
                    @endif
                  </div>
                </div>

                <!-- Rekomendasi Buku -->
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">
                      <i class="bx bx-star me-2 text-warning"></i>
                      Rekomendasi Buku Untukmu
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      @if(isset($rekomendasiBuku) && $rekomendasiBuku->count() > 0)
                        @foreach($rekomendasiBuku->take(4) as $buku)
                          <div class="col-md-3 col-sm-6 mb-3">
                            <div class="book-card card">
                              <div class="card-body p-3">
                                <img src="{{ asset('storage/buku/' . $buku->foto) }}"
                                     alt="{{ $buku->judul }}"
                                     class="book-img mb-2">
                                <h6 class="mb-1" style="font-size: 0.9rem;">{{ Str::limit($buku->judul, 40) }}</h6>
                                <small class="text-muted d-block mb-2">{{ $buku->penulis }}</small>
                                <div class="d-flex justify-content-between align-items-center">
                                  <span class="badge bg-label-{{ $buku->stok > 0 ? 'success' : 'danger' }}">
                                    {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                                  </span>
                                  <a href="{{ route('detail_buku', $buku->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      @else
                        <div class="col-12 text-center py-4">
                          <i class="bx bx-book-bookmark fs-1 text-muted mb-2"></i>
                          <p class="text-muted">Belum ada rekomendasi</p>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-lg-4">

                <!-- Reading Progress -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="mb-0">
                      <i class="bx bx-line-chart me-2 text-success"></i>
                      Progress Membaca
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="text-center mb-3">
                      <h2 class="mb-0">{{ $totalPeminjaman ?? 0 }}</h2>
                      <small class="text-muted">Total Buku Dibaca Tahun Ini</small>
                    </div>
                    <div class="progress-circle" style="position: relative; width: 200px; height: 200px; margin: 0 auto;">
                      <svg width="200" height="200" viewBox="0 0 200 200">
                        <circle cx="100" cy="100" r="80" stroke="#e9ecef" stroke-width="20" fill="none"/>
                        <circle cx="100" cy="100" r="80" stroke="#667eea" stroke-width="20" fill="none"
                                stroke-dasharray="{{ min((($totalPeminjaman ?? 0) / 20) * 502.65, 502.65) }} 502.65"
                                stroke-linecap="round" transform="rotate(-90 100 100)"/>
                      </svg>
                      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                        <div style="font-size: 24px; font-weight: 600; color: #667eea;">{{ min(round((($totalPeminjaman ?? 0) / 20) * 100), 100) }}%</div>
                        <div style="font-size: 12px; color: #6c757d;">{{ $totalPeminjaman ?? 0 }} / 20</div>
                      </div>
                    </div>

                    <div class="mt-3">
                      <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Target Tahunan</span>
                        <span class="fw-semibold">20 Buku</span>
                      </div>
                      <div class="progress progress-custom">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ min((($totalPeminjaman ?? 0) / 20) * 100, 100) }}%"></div>
                      </div>
                      <small class="text-muted">
                        {{ 20 - ($totalPeminjaman ?? 0) > 0 ? (20 - ($totalPeminjaman ?? 0)) . ' buku lagi untuk mencapai target' : 'Target tercapai! 🎉' }}
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Riwayat Aktivitas -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="mb-0">
                      <i class="bx bx-time me-2 text-info"></i>
                      Riwayat Terbaru
                    </h5>
                  </div>
                  <div class="card-body">
                    @if(isset($riwayatAktivitas) && $riwayatAktivitas->count() > 0)
                      @foreach($riwayatAktivitas->take(5) as $aktivitas)
                        <div class="activity-item mb-2">
                          <div class="d-flex align-items-start">
                            <div class="avatar avatar-sm bg-label-primary rounded me-2">
                              <i class="bx bx-book-bookmark"></i>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="mb-1" style="font-size: 0.85rem;">{{ $aktivitas->buku->judul }}</h6>
                              <small class="text-muted">
                                {{ $aktivitas->status }} - {{ \Carbon\Carbon::parse($aktivitas->created_at)->diffForHumans() }}
                              </small>
                            </div>
                          </div>
                        </div>
                      @endforeach
                      <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary w-100 mt-2">
                        Lihat Semua Riwayat
                      </a>
                    @else
                      <div class="text-center py-3">
                        <i class="bx bx-time-five fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                      </div>
                    @endif
                  </div>
                </div>

                <!-- Tips & Pengumuman -->
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">
                      <i class="bx bx-bell me-2 text-warning"></i>
                      Tips & Pengumuman
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-primary mb-3" role="alert">
                      <h6 class="alert-heading mb-1">💡 Tips Hari Ini</h6>
                      <small>Kembalikan buku tepat waktu untuk menghindari denda dan tetap bisa meminjam buku lainnya!</small>
                    </div>

                    <div class="alert alert-info mb-0" role="alert">
                      <h6 class="alert-heading mb-1">📚 Koleksi Baru</h6>
                      <small>Ada {{ $bukuBaru ?? 5 }} buku baru bulan ini. Jelajahi sekarang!</small>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            @endif

          </div>

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                © <script>document.write(new Date().getFullYear());</script>,
                E-Perpustakaan
              </div>
            </div>
          </footer>

          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <!-- Vendor JS -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="../assets/js/main.js"></script>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
    // Switch Tab Function for Admin Chart
    function switchTab(tabName) {
      // Hide all content
      document.querySelectorAll('.chart-content').forEach(content => {
        content.classList.remove('active');
      });

      // Remove active from all tabs
      document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.classList.remove('active');
      });

      // Show selected content
      document.getElementById('tab-' + tabName).classList.add('active');

      // Add active to clicked tab
      event.currentTarget.classList.add('active');
    }

    // Animate bars on load
    document.addEventListener('DOMContentLoaded', function() {
      const bars = document.querySelectorAll('.bar');
      bars.forEach((bar, index) => {
        const height = bar.style.height;
        bar.style.height = '0';
        setTimeout(() => {
          bar.style.height = height;
        }, index * 50);
      });
    });
  </script>
</body>
</html>
