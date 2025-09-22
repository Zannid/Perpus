@extends('layouts.backend')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
       <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
          <li class="breadcrumb-item">
            <a href="{{ route('buku.index') }}" class="text-decoration-none text-primary fw-semibold">
              Buku
            </a>
          </li>
          <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Tambah Buku
          </li>
        </ol>
</nav>
      <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Tambah Data Buku</h5>
        </div>
        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              {{-- Judul --}}
              <div class="col-md-6">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku" required>
              </div>

              {{-- Penulis --}}
              <div class="col-md-6">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control" placeholder="Masukkan nama penulis" required>
              </div>

              {{-- Penerbit --}}
              <div class="col-md-6">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" placeholder="Masukkan nama penerbit" required>
              </div>

              {{-- Tahun Terbit --}}
              <div class="col-md-6">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" min="1000" max="9999" placeholder="Contoh: 2022" required>
              </div>

              {{-- Kategori --}}
              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" id="id_kategori" class="form-select" required>
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Lokasi --}}
              <div class="col-md-6">
                <label class="form-label">Lokasi</label>
                <select name="id_lokasi" id="id_lokasi" class="form-select" required>
                  <option value="">-- Pilih Lokasi --</option>
                  @foreach($lokasi as $l)
                    <option value="{{ $l->id }}">{{ $l->kode_rak }} - {{ $l->keterangan }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Cover Buku --}}
              <div class="col-md-6">
                <label for="foto" class="form-label">Cover Buku</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                @error('foto')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Stok --}}
            <div class="col-md-6">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" placeholder="Masukkan jumlah stok" required>
              </div>
            </div>
            {{-- Deskripsi --}}
            <div class="mb-3 mt-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" placeholder="Masukkan deskripsi buku (opsional)"></textarea>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end mt-4">
              <a href="{{ route('buku.index') }}" class="btn btn-dark me-2">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
