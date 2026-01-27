@extends('layouts.backend')
@section('title', isset($user) ? 'E-Perpus - Edit User' : 'E-Perpus - Tambah User')
@section('content')

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-white">
            <h4 class="mb-0">
                {{ isset($user) ? 'Edit Data User' : 'Tambah Data User' }}
            </h4>
        </div>

        <div class="card-body">
            <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control"
                           {{ isset($user) ? '' : 'required' }}>

                    @if(isset($user) && $user->foto)
                        <div class="mt-2">
                            <p>Foto Saat Ini:</p>
                            <img src="{{ asset('storage/user/'.$user->foto) }}"
                                 alt="Foto User"
                                 width="120"
                                 class="img-thumbnail">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           {{ isset($user) ? '' : 'required' }}>
                    @if(isset($user))
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="no_telpon" class="form-label">No Telpon</label>
                    <input type="text" name="no_telpon" id="no_telpon" class="form-control"
                           value="{{ old('no_telpon', isset($user) ? $user->no_telpon : '') }}"
                           placeholder="Contoh: 08123456789">
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3"
                              placeholder="Masukkan alamat lengkap">{{ old('alamat', isset($user) ? $user->alamat : '') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($user) ? 'Update' : 'Simpan' }}
                    </button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
