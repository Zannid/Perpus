@extends('layouts.backend')
@section('content')
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-10">
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

            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input type="text" class="form-control" name="judul"
                     value="{{ old('judul', $buku->judul) }}" placeholder="Masukkan judul buku">
            </div>

            <div class="mb-3">
              <label class="form-label">Penulis</label>
              <input type="text" class="form-control" name="penulis"
                     value="{{ old('penulis', $buku->penulis) }}" placeholder="Masukkan nama penulis">
            </div>

            <div class="mb-3">
              <label class="form-label">Penerbit</label>
              <input type="text" class="form-control" name="penerbit"
                     value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Masukkan nama penerbit">
            </div>

            <div class="mb-3">
              <label class="form-label">Tahun Terbit</label>
              <input type="number" class="form-control" name="tahun_terbit"
                     value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                     min="1000" max="9999" required>
            </div>

            <div class="mb-3">
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

            <div class="mb-3">
              <label for="foto" class="form-label">Cover</label>
              <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto">
              @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              @if($buku->foto)
                <div class="mt-2">
                  <img src="{{ asset('storage/buku/' . $buku->foto) }}" alt="cover" width="100" class="rounded shadow">
                </div>
              @endif
            </div>

            <div class="mb-3">
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

            <div class="mb-3">
              <label class="form-label">Stok</label>
              <input type="number" class="form-control" name="stok"
                     value="{{ old('stok', $buku->stok) }}" placeholder="Masukkan jumlah stok">
            </div>

            <div class="d-flex justify-content-between">
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
