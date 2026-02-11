@extends('layouts.backend')
@section('title', 'E-Perpus - Data Pengembalian')
@section('content')
<div class="col-md-12">
  <nav>
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('pengembalian.index') }}" class="text-decoration-none text-primary fw-semibold">
          Pengembalian
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Data Pengembalian
      </li>
    </ol>
  </nav>

  <div class="card shadow-lg border-0">
    <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center py-3">
      <div class="d-flex align-items-center">
        <i class="bx bx-book-bookmark fs-4 me-2 text-primary"></i>
        <h5 class="mb-0 fw-bold">Data Pengembalian Buku</h5>
      </div>
      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('pengembalian.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control form-control-sm border-white" 
                   placeholder="Cari kode/nama/buku..."
                   style="min-width: 200px;">
            <button class="btn btn-light text-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>
        <a href="{{ route('pengembalian.export', request()->query()) }}" 
           target="_blank" 
           class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
          <i class="bx bxs-file-pdf"></i> Export PDF
        </a>
      </div>
    </div>

    <div class="card-body p-4">
      @php
        // Group pengembalian by kode_peminjaman
        $groupedPengembalian = $pengembalian->groupBy('peminjaman.kode_peminjaman');
      @endphp

      @forelse($groupedPengembalian as $kodePeminjaman => $items)
        @php
          $firstItem = $items->first();
          $totalDenda = $items->sum('denda');
          $totalBuku = $items->count();
        @endphp
        
        <div class="card mb-4 border-0 shadow-hover" style="border-left: 4px solid #4e73df !important;">
          {{-- HEADER CARD --}}
          <div class="card-header bg-light border-0 py-3">
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="d-flex align-items-center">
                  <div class="icon-circle bg-primary text-white me-3">
                    <i class="bx bx-receipt"></i>
                  </div>
                  <div>
                    <h6 class="mb-0 fw-bold text-primary">{{ $kodePeminjaman }}</h6>
                    <small class="text-muted">
                      <i class="bx bx-user"></i> {{ $firstItem->user->name }} • 
                      <i class="bx bx-calendar"></i> {{ \Carbon\Carbon::parse($firstItem->tgl_pinjam)->format('d M Y') }}
                    </small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-end">
                <span class="badge bg-success px-3 py-2 rounded-pill">
                  <i class="bx bx-check-circle"></i> DIKEMBALIKAN
                </span>
              </div>
            </div>
          </div>

          {{-- BODY - LIST BUKU --}}
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="border-0 ps-4" width="5%">No</th>
                    <th class="border-0" width="45%">Buku</th>
                    <th class="border-0 text-center" width="15%">Jumlah</th>
                    <th class="border-0" width="15%">Tgl Kembali</th>
                    <th class="border-0 text-end pe-4" width="20%">Denda</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($items as $index => $data)
                  <tr>
                    <td class="ps-4 align-middle">
                      <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                    </td>
                    <td class="align-middle">
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('/storage/buku/'. $data->buku->foto) }}"
                             class="rounded border me-3"
                             style="width: 50px; height: 70px; object-fit: cover;"
                             alt="Cover Buku">
                        <div>
                          <h6 class="mb-0 fw-semibold">{{ $data->buku->judul }}</h6>
                          <small class="text-muted">
                            <i class="bx bx-barcode"></i> {{ $data->buku->isbn ?? '-' }}
                          </small>
                        </div>
                      </div>
                    </td>
                    <td class="text-center align-middle">
                      <span class="badge bg-info text-white px-3">{{ $data->jumlah }} Buku</span>
                    </td>
                    <td class="align-middle">
                      <i class="bx bx-calendar-check text-success"></i>
                      {{ \Carbon\Carbon::parse($data->tgl_kembali)->format('d M Y') }}
                      @php
                        $tglPinjam = \Carbon\Carbon::parse($data->tgl_pinjam);
                        $tglKembali = \Carbon\Carbon::parse($data->tgl_kembali);
                        $selisihHari = $tglPinjam->diffInDays($tglKembali);
                      @endphp
                      <br>
                      <small class="text-muted">({{ $selisihHari }} hari)</small>
                    </td>
                    <td class="text-end pe-4 align-middle">
                      @if($data->denda > 0)
                        <div class="fw-bold text-danger fs-6">
                          Rp {{ number_format($data->denda, 0, ',', '.') }}
                        </div>
                        <small class="text-muted">Denda keterlambatan</small>
                      @else
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                          <i class="bx bx-check"></i> Tepat Waktu
                        </span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          {{-- FOOTER CARD --}}
          <div class="card-footer bg-light border-0 py-3">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="d-flex align-items-center">
                  <span class="text-muted me-3">
                    <i class="bx bx-book"></i> Total: <strong>{{ $totalBuku }}</strong> Buku
                  </span>
                  <span class="text-muted">
                    <i class="bx bx-time"></i> ID Peminjaman: <strong>#{{ $firstItem->id_peminjaman }}</strong>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex justify-content-end align-items-center gap-3">
                  <div class="text-end">
                    <small class="text-muted d-block">Total Denda:</small>
                    <h5 class="mb-0 fw-bold {{ $totalDenda > 0 ? 'text-danger' : 'text-success' }}">
                      Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </h5>
                  </div>
                  
                  <div class="btn-group" role="group">
                    <a href="{{ route('peminjaman.show', $firstItem->id_peminjaman) }}" 
                       class="btn btn-outline-primary btn-sm">
                      <i class="bx bx-show"></i> Detail
                    </a>
                    
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                      <a href="{{ route('pengembalian.edit', $firstItem->id) }}" 
                         class="btn btn-warning btn-sm">
                        <i class="bx bx-pencil"></i> Edit
                      </a>
                      
                      <button type="button" 
                              class="btn btn-outline-danger btn-sm"
                              onclick="confirmDelete('{{ $kodePeminjaman }}', '{{ $firstItem->id }}')">
                        <i class="bx bx-trash"></i> Hapus
                      </button>
                      
                      <form id="delete-form-{{ $firstItem->id }}" 
                            action="{{ route('pengembalian.destroy', $firstItem->id) }}" 
                            method="POST" 
                            class="d-none">
                        @csrf
                        @method('DELETE')
                      </form>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      @empty
        <div class="text-center py-5">
          <div class="mb-3">
            <i class="bx bx-book-open text-muted" style="font-size: 80px;"></i>
          </div>
          <h5 class="text-muted">Tidak ada data pengembalian</h5>
          <p class="text-muted">Belum ada buku yang dikembalikan</p>
        </div>
      @endforelse

      {{-- Pagination --}}

        <div class="mt-4">
          {{ $pengembalian->appends(request()->all())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>

    </div>
  </div>
</div>

<style>
  .bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
  }
  
  .icon-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
  
  .shadow-hover {
    transition: all 0.3s ease;
  }
  
  .shadow-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
  }
  
  .table tbody tr {
    transition: background-color 0.2s ease;
  }
  
  .table tbody tr:hover {
    background-color: #f8f9fc;
  }
  
  .badge {
    font-weight: 500;
    letter-spacing: 0.5px;
  }
  
  .bg-success-subtle {
    background-color: #d4edda !important;
  }
  
  .btn-group .btn {
    border-radius: 0;
  }
  
  .btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
  }
  
  .btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
  }
</style>

<script>
  function confirmDelete(kodePeminjaman, id) {
    if (confirm('Apakah Anda yakin ingin menghapus data pengembalian ' + kodePeminjaman + '?')) {
      document.getElementById('delete-form-' + id).submit();
    }
  }

  // Auto-hide alerts after 5 seconds
  document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
      setTimeout(function() {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(function() {
          alert.remove();
        }, 500);
      }, 5000);
    });
  });
</script>
@endsection