@extends('layouts.backend')
@section('title', isset($petugas) ? 'E-Perpus - Edit Petugas' : 'E-Perpus - Tambah Petugas')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($petugas) ? 'Edit Data Petugas' : 'Tambah Data Petugas' }}
            </h4>
        </div>

        <div class="card-body">
            <form action="{{ isset($petugas) ? route('petugas.update', $petugas->id) : route('petugas.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if(isset($petugas))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control"
                           {{ isset($petugas) ? '' : 'required' }}>

                    @if(isset($petugas) && $petugas->foto)
                        <div class="mt-2">
                            <p>Foto Saat Ini:</p>
                            <img src="{{ asset('storage/petugas/'.$petugas->foto) }}" 
                                 alt="Foto Petugas" 
                                 width="120" 
                                 class="img-thumbnail">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', isset($petugas) ? $petugas->name : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', isset($petugas) ? $petugas->email : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                        {{ isset($petugas) ? '' : 'required' }}>
                    @if(isset($petugas))
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    @endif
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($petugas) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('petugas.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
