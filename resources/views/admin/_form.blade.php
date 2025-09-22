@extends('layouts.backend')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($admin) ? 'Edit Data Admin' : 'Tambah Data Admin' }}
            </h4>
        </div>

        <div class="card-body">
            <form action="{{ isset($admin) ? route('admin.update', $admin->id) : route('admin.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @if(isset($admin))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control"
                           {{ isset($admin) ? '' : 'required' }}>

                    @if(isset($admin) && $admin->foto)
                        <div class="mt-2">
                            <p>Foto Saat Ini:</p>
                            <img src="{{ asset('storage/admin/'.$admin->foto) }}" 
                                 alt="Foto Petugas" 
                                 width="120" 
                                 class="img-thumbnail">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', isset($admin) ? $admin->name : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', isset($admin) ? $admin->email : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                        {{ isset($admin) ? '' : 'required' }}>
                    @if(isset($admin))
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    @endif
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($admin) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
