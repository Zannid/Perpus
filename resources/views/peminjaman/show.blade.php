@extends('layouts.backend')
@section('title', 'E-Perpus - Detail Peminjaman')
@section('content')

<div class="container">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">
          Peminjaman
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Detail</li>
    </ol>
  </nav>

  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <i class="bx bx-book-content me-2"></i>Detail Peminjaman
      </h5>
      
      {{-- Badge Status --}}
      @if($peminjaman->status == 'Dipinjam')
        <span class="badge bg-success">{{ $peminjaman->status }}</span>
      @elseif($peminjaman->status == 'Pending')
        <span class="badge bg-warning">{{ $peminjaman->status }}</span>
      @elseif($peminjaman->status == 'Ditolak')
        <span class="badge bg-danger">{{ $peminjaman->status }}</span>
      @elseif($peminjaman->status == 'Dikembalikan')
        <span class="badge bg-info">{{ $peminjaman->status }}</span>
      @else
        <span class="badge bg-secondary">{{ $peminjaman->status }}</span>
      @endif
    </div>

    <div class="card-body">
      <div class="row">
        
        {{-- Info Peminjaman --}}
        <div class="col-md-6 mb-3">
          <h6 class="text-primary fw-bold mb-3">
            <i class="bx bx-info-circle me-1"></i>Informasi Peminjaman
          </h6>
          <table class="table table-borderless">
            <tr>
              <td width="40%"><strong>Kode Peminjaman</strong></td>
              <td>: {{ $peminjaman->kode_peminjaman }}</td>
            </tr>
            <tr>
              <td><strong>Nama Peminjam</strong></td>
              <td>: {{ $peminjaman->user->name ?? '-' }}</td>
            </tr>
            <tr>
              <td><strong>Email</strong></td>
              <td>: {{ $peminjaman->user->email ?? '-' }}</td>
            </tr>
            <tr>
              <td><strong>Status</strong></td>
              <td>: 
                @if($peminjaman->status == 'Dipinjam')
                  <span class="badge bg-success">{{ $peminjaman->status }}</span>
                @elseif($peminjaman->status == 'Pending')
                  <span class="badge bg-warning">{{ $peminjaman->status }}</span>
                @elseif($peminjaman->status == 'Ditolak')
                  <span class="badge bg-danger">{{ $peminjaman->status }}</span>
                @else
                  <span class="badge bg-secondary">{{ $peminjaman->status }}</span>
                @endif
              </td>
            </tr>
          </table>
        </div>

        {{-- Daftar Buku --}}
        <div class="col-md-12 mb-3">
          <h6 class="text-primary fw-bold mb-3">
            <i class="bx bx-book me-1"></i>Informasi Buku ({{ $peminjaman->details->count() }})
          </h6>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Judul Buku</th>
                  <th>Penulis</th>
                  <th>Penerbit</th>
                  <th class="text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @foreach($peminjaman->details as $index => $detail)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ optional($detail->buku)->judul ?? '-' }}</td>
                  <td>{{ optional($detail->buku)->penulis ?? '-' }}</td>
                  <td>{{ optional($detail->buku)->penerbit ?? '-' }}</td>
                  <td class="text-center">{{ $detail->jumlah }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="4" class="text-end">Total Jumlah :</td>
                  <td class="text-center">{{ $peminjaman->jumlah }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        {{-- Tanggal --}}
        <div class="col-md-12 mb-3">
          <h6 class="text-primary fw-bold mb-3">
            <i class="bx bx-calendar me-1"></i>Informasi Tanggal
          </h6>
          <table class="table table-borderless">
            <tr>
              <td width="20%"><strong>Tanggal Pinjam</strong></td>
              <td>: {{ $peminjaman->formatted_tanggal_pinjam ?? $peminjaman->tgl_pinjam }}</td>
            </tr>
            <tr>
              <td><strong>Tanggal Tenggat</strong></td>
              <td>: {{ $peminjaman->formatted_tanggal_kembali ?? $peminjaman->tenggat }}</td>
            </tr>
            @if($peminjaman->tgl_kembali)
            <tr>
              <td><strong>Tanggal Kembali</strong></td>
              <td>: {{ $peminjaman->formatted_tgl_kembali ?? $peminjaman->tgl_kembali }}</td>
            </tr>
            @endif
          </table>
        </div>

        {{-- Alasan Tolak (jika ditolak) --}}
        @if($peminjaman->status == 'Ditolak' && $peminjaman->alasan_tolak)
        <div class="col-md-12">
          <div class="alert alert-danger">
            <h6 class="fw-bold mb-2">
              <i class="bx bx-error-circle me-1"></i>Alasan Penolakan:
            </h6>
            <p class="mb-0">{{ $peminjaman->alasan_tolak }}</p>
          </div>
        </div>
        @endif

      </div>

      {{-- Tombol Kembali --}}
      <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
          <i class="bx bx-arrow-back me-1"></i>Kembali
        </a>
      </div>
    </div>
  </div>

</div>

@endsection