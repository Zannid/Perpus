@extends('layouts.backend')
@section('content')
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Edit Data Lokasi</h4>
        </div>
        <div class="card-body">

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('lokasi.update', $lokasi->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select name="id_kategori" id="id_kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $k)
                  <option value="{{ $k->id }}" {{ $lokasi->id_kategori == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <input type="text" 
                     class="form-control" 
                     name="keterangan" 
                     value="{{ $lokasi->keterangan }}" 
                     placeholder="Masukkan keterangan">
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
