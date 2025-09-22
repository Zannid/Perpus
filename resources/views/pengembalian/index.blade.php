@extends('layouts.backend')
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

  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Pengembalian</h5>
      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('pengembalian.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari pengembalian..."
              value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>
        <a href="{{ route('pengembalian.export', request()->query()) }}" 
                target="_blank" 
                class="btn btn-danger btn-sm rounded-pill px-3">
                <i class="bx bx-file"></i> Buat PDF
                </a>
        
    </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode Peminjaman</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th>Jumlah</th>
              <th>Denda</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pengembalian as $i => $data)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $data->peminjaman->kode_peminjaman }}</td>
              <td>{{ $data->user->name ?? '-' }}</td>
              <td>{{ $data->buku->judul ?? '-' }}</td>
              <td>{{ $data->tgl_pinjam }}</td>
              <td>{{ $data->tgl_kembali }}</td>
              <td class="text-center">{{ $data->jumlah }}</td>
              <td>
                @if($data->denda > 0)
                  <span class="badge bg-danger">Rp {{ number_format($data->denda, 0, ',', '.') }}</span>
                @else
                  <span class="badge bg-success">-</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      
    </div>
  </div>
</div>
@endsection
