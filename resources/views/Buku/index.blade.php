@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('buku.index') }}" class="text-decoration-none text-primary fw-semibold">
          Buku
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Daftar Buku
      </li>
    </ol>
  </nav>

  <div class="card mt-3 shadow-sm">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h5 class="mb-0">Data Buku</h5>

      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('buku.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari buku..."
              value="{{ $request->get('search') }}">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>

        {{-- Tombol Tambah Buku --}}
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
        <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
          <i class="bx bx-plus me-1"></i> Tambah Buku
        </a>
        @endif
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Buku</th>
              <th>Judul</th>
              <th class="text-center">Kategori</th>
              <th>Cover</th>
              <th>Lokasi</th>
              <th>Stok</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($buku as $data)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $data->kode_buku }}</td>
              <td>{{ $data->judul }}</td>
              <td class="text-center">{{ $data->kategori->nama_kategori }}</td>
              <td>
                <img src="{{ asset('storage/buku/' . $data->foto)}}" alt="cover" class="img-thumbnail" width="50">
              </td>
              <td>{{ $data->lokasi->kode_rak }} - {{ $data->lokasi->keterangan }}</td>
              <td class="text-center">{{ $data->stok }}</td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                  {{-- Show --}}
                  <a href="{{ route('buku.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail">
                    <i class="bx bx-show"></i>
                  </a>
                  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                  {{-- Edit --}}
                  <a href="{{ route('buku.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="bx bx-pencil"></i>
                  </a>
                  {{-- Delete --}}
                  <form action="{{ route('buku.destroy', $data->id) }}" method="post" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                      onclick="return confirm('Apakah anda yakin?')">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
