@extends('layouts.backend')
@section('title', 'E-Perpus - Data Pengembalian')
@section('content')

<style>
/* ========================================
   CARD HEADER
======================================== */
.card-header {
  padding: 0.85rem 1rem;
}

.header-top {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.6rem;
}

.header-top h5 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
}

.header-controls {
  display: flex;
  gap: 0.4rem;
  align-items: center;
}

.header-controls .search-form {
  flex: 1 1 auto;
  min-width: 0;
}

.header-controls .search-form .input-group {
  width: 100%;
}

/* ========================================
   PENGEMBALIAN GROUP CARD
======================================== */
.pg-card {
  border: 0;
  border-left: 4px solid #4e73df !important;
  border-radius: 10px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.08);
  margin-bottom: 1.2rem;
  overflow: hidden;
  transition: all 0.25s ease;
}

.pg-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.13) !important;
}

/* Card header strip */
.pg-card-header {
  background: #f8f9fc;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #e9ecef;
}

.pg-card-header-inner {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.5rem;
}

.icon-circle {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
}

.pg-card-meta {
  flex: 1;
  min-width: 0;
}

.pg-card-kode {
  font-weight: 700;
  color: #4e73df;
  font-size: 0.92rem;
  margin-bottom: 0.15rem;
}

.pg-card-sub {
  font-size: 0.78rem;
  color: #6c757d;
}

/* ========================================
   BOOK TABLE — desktop
======================================== */
.buku-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

/* ========================================
   BOOK LIST — mobile
======================================== */
.buku-mobile-list {
  display: none;
}

.buku-mobile-item {
  display: flex;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #f1f3f5;
  align-items: flex-start;
}

.buku-mobile-item:last-child {
  border-bottom: none;
}

.buku-cover {
  width: 44px;
  height: 62px;
  object-fit: cover;
  border-radius: 5px;
  border: 1px solid #dee2e6;
  flex-shrink: 0;
}

.buku-info {
  flex: 1;
  min-width: 0;
}

.buku-judul {
  font-weight: 600;
  font-size: 0.87rem;
  margin-bottom: 0.2rem;
  word-break: break-word;
}

.buku-isbn {
  font-size: 0.75rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.buku-details-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.3rem 0.7rem;
  margin-top: 0.2rem;
}

.buku-details-row span {
  font-size: 0.76rem;
  color: #555;
}

.buku-denda {
  text-align: right;
  flex-shrink: 0;
}

/* ========================================
   CARD FOOTER
======================================== */
.pg-card-footer {
  background: #f8f9fc;
  padding: 0.75rem 1rem;
  border-top: 1px solid #e9ecef;
}

.pg-footer-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.pg-footer-left {
  font-size: 0.82rem;
  color: #6c757d;
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
}

.pg-footer-right {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  flex-wrap: wrap;
}

.pg-denda-total {
  text-align: right;
}

.pg-denda-total small {
  display: block;
  font-size: 0.73rem;
  color: #6c757d;
}

.pg-denda-total .denda-amount {
  font-size: 1rem;
  font-weight: 700;
}

/* Action btn group responsive */
.pg-actions {
  display: flex;
  gap: 0.3rem;
  flex-wrap: wrap;
}

.pg-actions .btn {
  font-size: 0.78rem;
  padding: 0.28rem 0.6rem;
  white-space: nowrap;
}

/* ========================================
   PAGINATION
======================================== */
.pagination {
  flex-wrap: wrap;
  gap: 2px;
}

.page-link {
  font-size: 0.8rem;
  padding: 0.3rem 0.55rem;
}

/* ========================================
   RESPONSIVE
======================================== */
@media (max-width: 767.98px) {
  /* Show mobile book list, hide desktop table */
  .buku-table-wrap  { display: none !important; }
  .buku-mobile-list { display: block !important; }

  /* Header controls stack: search full width, export next to it */
  .header-controls {
    flex-direction: row;
    flex-wrap: wrap;
  }

  .header-controls .search-form {
    flex: 1 1 0;
    min-width: 0;
  }

  /* Footer inner: stack vertically */
  .pg-footer-inner {
    flex-direction: column;
    align-items: flex-start;
  }

  .pg-footer-right {
    width: 100%;
    justify-content: space-between;
  }

  .pg-actions .btn {
    flex: 1 1 auto;
    justify-content: center;
    text-align: center;
  }
}

@media (max-width: 400px) {
  .pg-actions {
    width: 100%;
  }

  .pg-actions .btn {
    flex: 1 1 100%;
  }
}
</style>

<div class="col-md-12">

  {{-- Breadcrumb --}}
  <nav>
    <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-3" style="font-size:0.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('pengembalian.index') }}" class="text-decoration-none text-primary fw-semibold">
          Pengembalian
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Data Pengembalian</li>
    </ol>
  </nav>

  <div class="card shadow-lg border-0">

    {{-- ===== CARD HEADER ===== --}}
    <div class="card-header bg-white border-bottom">
      <div class="header-top">
        <i class="bx bx-book-bookmark fs-5 text-primary"></i>
        <h5>Data Pengembalian Buku</h5>
      </div>
      <div class="header-controls">
        <form action="{{ route('pengembalian.index') }}" method="get" class="search-form">
          <div class="input-group input-group-sm">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari kode peminjaman, nama, buku...">
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>
        <a href="{{ route('pengembalian.export', request()->query()) }}"
           target="_blank"
           class="btn btn-danger btn-sm rounded-pill px-3 flex-shrink-0">
          <i class="bx bxs-file-pdf me-1"></i>
          <span class="d-none d-sm-inline">Export </span>PDF
        </a>
      </div>
    </div>

    <div class="card-body p-2 p-sm-4">

      @php
        $groupedPengembalian = $pengembalian->groupBy('peminjaman.kode_peminjaman');
      @endphp

      @forelse($groupedPengembalian as $kodePeminjaman => $items)
        @php
          $firstItem  = $items->first();
          $totalDenda = $items->sum('denda');
          $totalBuku  = $items->count();
        @endphp

        <div class="pg-card card">

          {{-- GROUP CARD HEADER --}}
          <div class="pg-card-header">
            <div class="pg-card-header-inner">
              <div class="d-flex align-items-center gap-2">
                <div class="icon-circle bg-primary text-white">
                  <i class="bx bx-receipt"></i>
                </div>
                <div class="pg-card-meta">
                  <div class="pg-card-kode">{{ $kodePeminjaman }}</div>
                  <div class="pg-card-sub">
                    <i class="bx bx-user"></i> {{ $firstItem->user->name }}
                    &nbsp;·&nbsp;
                    <i class="bx bx-calendar"></i>
                    {{ \Carbon\Carbon::parse($firstItem->tgl_pinjam)->format('d M Y') }}
                  </div>
                </div>
              </div>
              <span class="badge bg-success px-2 py-1 rounded-pill flex-shrink-0" style="font-size:0.72rem;">
                <i class="bx bx-check-circle"></i> KEMBALI
              </span>
            </div>
          </div>

          {{-- ===== DESKTOP BOOK TABLE ===== --}}
          <div class="buku-table-wrap">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4" style="width:5%;">No</th>
                  <th style="width:42%;">Buku</th>
                  <th class="text-center" style="width:12%;">Jumlah</th>
                  <th style="width:18%;">Tgl Kembali</th>
                  <th class="text-end pe-4" style="width:23%;">Denda</th>
                </tr>
              </thead>
              <tbody>
                @foreach($items as $index => $data)
                @php
                  $selisihHari = \Carbon\Carbon::parse($data->tgl_pinjam)->diffInDays(\Carbon\Carbon::parse($data->tgl_kembali));
                @endphp
                <tr>
                  <td class="ps-4 align-middle">
                    <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ asset('/storage/buku/' . $data->buku->foto) }}"
                           class="rounded border"
                           style="width:45px;height:63px;object-fit:cover;"
                           alt="Cover Buku">
                      <div>
                        <div class="fw-semibold" style="font-size:0.88rem;">{{ $data->buku->judul }}</div>
                        <small class="text-muted"><i class="bx bx-barcode"></i> {{ $data->buku->isbn ?? '-' }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge bg-info text-white px-2">{{ $data->jumlah }} Buku</span>
                  </td>
                  <td class="align-middle" style="font-size:0.85rem;">
                    <i class="bx bx-calendar-check text-success"></i>
                    {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d M Y') }}<br>
                    <small class="text-muted">({{ $selisihHari }} hari)</small>
                  </td>
                  <td class="text-end pe-4 align-middle">
                    @if($data->denda > 0)
                      <div class="fw-bold text-danger">Rp {{ number_format($data->denda, 0, ',', '.') }}</div>
                      <small class="text-muted">Keterlambatan</small>
                    @else
                      <span class="badge bg-success-subtle text-success px-2 py-1">
                        <i class="bx bx-check"></i> Tepat Waktu
                      </span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- ===== MOBILE BOOK LIST ===== --}}
          <div class="buku-mobile-list">
            @foreach($items as $index => $data)
            @php
              $selisihHari = \Carbon\Carbon::parse($data->tgl_pinjam)->diffInDays(\Carbon\Carbon::parse($data->tgl_kembali));
            @endphp
            <div class="buku-mobile-item">
              <img src="{{ asset('/storage/buku/' . $data->buku->foto) }}"
                   class="buku-cover" alt="Cover Buku">
              <div class="buku-info">
                <div class="buku-judul">{{ $data->buku->judul }}</div>
                <div class="buku-isbn"><i class="bx bx-barcode"></i> {{ $data->buku->isbn ?? '-' }}</div>
                <div class="buku-details-row">
                  <span><i class="bx bx-book"></i> {{ $data->jumlah }} buku</span>
                  <span><i class="bx bx-calendar-check text-success"></i>
                    {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d M Y') }}
                    ({{ $selisihHari }} hari)
                  </span>
                </div>
              </div>
              <div class="buku-denda">
                @if($data->denda > 0)
                  <div class="fw-bold text-danger" style="font-size:0.82rem;">
                    Rp {{ number_format($data->denda, 0, ',', '.') }}
                  </div>
                  <small class="text-muted" style="font-size:0.7rem;">Denda</small>
                @else
                  <span class="badge bg-success-subtle text-success" style="font-size:0.7rem;">Tepat</span>
                @endif
              </div>
            </div>
            @endforeach
          </div>

          {{-- GROUP CARD FOOTER --}}
          <div class="pg-card-footer">
            <div class="pg-footer-inner">
              <div class="pg-footer-left">
                <span><i class="bx bx-book"></i> Total: <strong>{{ $totalBuku }}</strong> Buku</span>
                <span><i class="bx bx-hash"></i> ID: <strong>#{{ $firstItem->id_peminjaman }}</strong></span>
              </div>
              <div class="pg-footer-right">
                <div class="pg-denda-total">
                  <small>Total Denda:</small>
                  <div class="denda-amount {{ $totalDenda > 0 ? 'text-danger' : 'text-success' }}">
                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                  </div>
                </div>
                <div class="pg-actions">
                  <a href="{{ route('peminjaman.show', $firstItem->id_peminjaman) }}"
                     class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-show me-1"></i> Detail
                  </a>
                  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <a href="{{ route('pengembalian.edit', $firstItem->id) }}"
                       class="btn btn-warning btn-sm">
                      <i class="bx bx-pencil me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            onclick="confirmDelete('{{ $kodePeminjaman }}', '{{ $firstItem->id }}')">
                      <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                    <form id="delete-form-{{ $firstItem->id }}"
                          action="{{ route('pengembalian.destroy', $firstItem->id) }}"
                          method="POST" class="d-none">
                      @csrf
                      @method('DELETE')
                    </form>
                  @endif
                </div>
              </div>
            </div>
          </div>

        </div>{{-- end pg-card --}}

      @empty
        <div class="text-center py-5">
          <i class="bx bx-book-open text-muted" style="font-size:72px;"></i>
          <h5 class="text-muted mt-3">Tidak ada data pengembalian</h5>
          <p class="text-muted">Belum ada buku yang dikembalikan</p>
        </div>
      @endforelse

      {{-- Pagination --}}
      <div class="mt-3">
        {{ $pengembalian->appends(request()->all())->links('vendor.pagination.bootstrap-5') }}
      </div>

    </div>
  </div>
</div>

<script>
  function confirmDelete(kodePeminjaman, id) {
    if (confirm('Hapus data pengembalian ' + kodePeminjaman + '?')) {
      document.getElementById('delete-form-' + id).submit();
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (alert) {
      setTimeout(function () {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(function () { alert.remove(); }, 500);
      }, 5000);
    });
  });
</script>

@endsection