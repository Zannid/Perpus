@extends('layouts.backend')
@section('title', 'E-Perpus - Profil Saya')
@section('content')
<div class="container mt-4">
  <div class="card shadow-lg">
    <div class="card-header text-white">
      <h4 class="mb-0">Profil Saya</h4>
    </div>
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="text-center mb-4">
            @if(Auth::user()->role == 'admin')
                <img src="{{ asset('storage/admin/' . Auth::user()->foto) }}" 
                     alt="Avatar"
                     class="rounded-circle mb-3"
                     style="width: 120px; height: 120px; object-fit: cover;">
            @elseif(Auth::user()->role == 'petugas')
                <img src="{{ asset('storage/petugas/' . Auth::user()->foto) }}" 
                     alt="Foto Petugas"
                     class="rounded-circle mb-3"
                     style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <img src="{{ asset('storage/user/' . Auth::user()->foto) }}" 
                     alt="Foto User"
                     class="rounded-circle mb-3"
                     style="width: 120px; height: 120px; object-fit: cover;">
            @endif

          <div class="mb-3">
            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
            @error('photo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $user->name) }}">
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                 value="{{ old('email', $user->email) }}">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
@endsection
