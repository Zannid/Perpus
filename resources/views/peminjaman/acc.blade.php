@extends('layouts.backend')

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
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Pending
      </li>
    </ol>
  </nav>

  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Daftar Peminjaman Pending</h5>
      <div class="d-flex flex-wrap align-items-center gap-2">
          {{-- Form Search --}}
          <form action="{{ route('petugas.acc') }}" method="get" class="d-flex">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control"
                     placeholder="Cari Pengajuan..." value="{{ request('search') }}">
              <button class="btn btn-outline-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Peminjam</th>
              <th>Buku</th>
              <th>Jumlah</th>
              <th>Tanggal Pinjam</th>
              <th>Tenggat</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @forelse($peminjaman as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->kode_peminjaman }}</td>
                <td>{{ $data->user->name ?? '-' }}</td>
                <td>{{ $data->buku->judul ?? '-' }}</td>
                <td>{{ $data->jumlah }}</td>
                <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
                <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
                <td class="text-center">
                  <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3"
                            onclick="return confirm('Setujui peminjaman ini?')">
                      ACC
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada peminjaman pending</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
