@extends('layouts.backend')
@section('content')
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header text-white">
          <h4 class="mb-0">Tambah Data Karyawan</h4>
        </div>
        <div class="card-body">

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form method="POST" action="{{ route('kategori.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <input type="text" class="form-control" name="nama_kategori"
                     value="{{ old('nama_kategori') }}" placeholder="Masukkan kategori">
            </div>

            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <input type="text" class="form-control" name="keterangan"
                     value="{{ old('keterangan') }}" placeholder="Masukkan keterangan">
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
