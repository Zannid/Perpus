@extends('layouts.backend')
@section('content')
<div class="col-md-8 offset-md-2">
  <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    
    {{-- Header --}}
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
      <h5 class="mb-0 fw-bold">
        <i class="bx bx-book me-2"></i> Detail Buku
      </h5>
      <a href="{{ route('buku.index') }}" class="btn btn-outline-black btn-sm rounded-pill px-3">
        <i class="bx bx-arrow-back me-1"></i> Kembali
      </a>
    </div>

    {{-- Body --}}
    <div class="card-body p-4">
      <div class="row g-4">
        
        {{-- Cover Buku --}}
        <div class="col-md-4 text-center">
          <div class="border rounded-3 shadow-sm p-2 bg-light">
            <img src="{{ asset('storage/buku/' . $buku->foto) }}" 
                 alt="Cover Buku" 
                 class="img-fluid rounded-3" 
                 style="max-height: 280px; object-fit: cover;">
          </div>
        </div>

        {{-- Detail Buku --}}
        <div class="col-md-8">
          <table class="table table-sm table-borderless">
            <tbody>
              <tr>
                <th width="150" class="text-muted">Kode Buku</th>
                <td class="fw-semibold">{{ $buku->kode_buku }}</td>
              </tr>
              <tr>
                <th class="text-muted">Judul</th>
                <td class="fw-semibold">{{ $buku->judul }}</td>
              </tr>
              <tr>
                <th class="text-muted">Deskripsi</th>
                <td class="fw-semibold">{{ $buku->deskripsi ?? '-' }}</td>
              </tr>
              <tr>
                <th class="text-muted">Penulis</th>
                <td>{{ $buku->penulis }}</td>
              </tr>
              <tr>
                <th class="text-muted">Penerbit</th>
                <td>{{ $buku->penerbit }}</td>
              </tr>
              <tr>
                <th class="text-muted">Tahun Terbit</th>
                <td>{{ $buku->tahun_terbit }}</td>
              </tr>
              <tr>
                <th class="text-muted">Kategori</th>
                <td>
                  <span class="badge bg-info text-dark px-3 py-2 rounded-pill">
                    {{ $buku->kategori->nama_kategori }}
                  </span>
                </td>
              </tr>
              <tr>
                <th class="text-muted">Lokasi</th>
                <td>
                  <span class="badge bg-secondary px-3 py-2 rounded-pill">
                    Rak {{ $buku->lokasi->kode_rak }} - {{ $buku->lokasi->keterangan }}
                  </span>
                </td>
              </tr>
              <tr>
                <th class="text-muted">Stok</th>
                <td>
                  <span class="badge bg-success px-3 py-2 rounded-pill">{{ $buku->stok }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer bg-light d-flex justify-content-end gap-2 px-4 py-3">
      <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-outline-warning btn-sm rounded-pill">
        <i class="bx bx-pencil"></i> Edit
      </a>
      <form action="{{ route('buku.destroy', $buku->id) }}" 
            method="post" 
            style="display:inline;" 
            onsubmit="return confirm('Apakah anda yakin ingin menghapus buku ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
          <i class="bx bx-trash"></i> Hapus
        </button>
      </form>
    </div>

  </div>
</div>
@endsection
