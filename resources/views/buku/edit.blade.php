@extends('layouts.backend')
@section('title', 'E-Perpus - Edit Buku')
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
            Edit Buku
          </li>
        </ol>
</nav>
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header text-dark">
          <h4 class="mb-0">Edit Data Buku</h4>
        </div>
        <div class="card-body">

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('buku.update', $buku->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
              {{-- Judul --}}
              <div class="col-md-6">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="judul"
                       value="{{ old('judul', $buku->judul) }}" placeholder="Masukkan judul buku">
              </div>

              {{-- Penulis --}}
              <div class="col-md-6">
                <label class="form-label">Penulis</label>
                <input type="text" class="form-control" name="penulis"
                       value="{{ old('penulis', $buku->penulis) }}" placeholder="Masukkan nama penulis">
              </div>

              {{-- Penerbit --}}
              <div class="col-md-6">
                <label class="form-label">Penerbit</label>
                <input type="text" class="form-control" name="penerbit"
                       value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Masukkan nama penerbit">
              </div>

              {{-- Tahun Terbit --}}
              <div class="col-md-6">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" name="tahun_terbit"
                       value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                       min="1000" max="9999" required>
              </div>

              {{-- Kategori --}}
              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" id="id_kategori" class="form-select" required>
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($kategori as $k)
                    <option value="{{ $k->id }}" 
                            {{ old('id_kategori', $buku->id_kategori) == $k->id ? 'selected' : '' }}>
                      {{ $k->nama_kategori }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Lokasi --}}
              <div class="col-md-6">
                <label class="form-label">Lokasi</label>
                <select name="id_lokasi" id="id_lokasi" class="form-select" required>
                  <option value="">-- Pilih Lokasi --</option>
                  @foreach($lokasi as $l)
                    <option value="{{ $l->id }}" 
                            {{ old('id_lokasi', $buku->id_lokasi) == $l->id ? 'selected' : '' }}>
                      {{ $l->kode_rak }} - {{ $l->keterangan }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Cover --}}
              <div class="col-md-6">
                <label for="foto" class="form-label">Cover</label>
                <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto">
                @error('foto')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Stok --}}
              <div class="col-md-6">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" name="stok"
                       value="{{ old('stok', $buku->stok) }}" placeholder="Masukkan jumlah stok">
              </div>
              {{-- Deskripsi --}}
              <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi buku (opsional)">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
              </div>

              {{-- Preview Cover (jika ada) --}}
              @if($buku->foto)
                <div class="col-12">
                  <div class="mt-2">
                    <img src="{{ asset('storage/buku/' . $buku->foto) }}" alt="cover" width="120" class="rounded shadow">
                  </div>
                </div>
              @endif
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between mt-4">
              <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>
              <button type="submit" class="btn btn-warning">Update</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
