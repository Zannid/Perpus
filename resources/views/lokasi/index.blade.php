@extends('layouts.backend')
@section('title', 'E-Perpus - Data Lokasi')
@section('content')
<div class="row d-flex justify-content-center">

  <div class="col-md-10">
      <nav>
          <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
          <li class="breadcrumb-item">
            <a href="{{ route('lokasi.index') }}" class="text-decoration-none text-primary fw-semibold">
              Lokasi
            </a>
          </li>
          <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Data Lokasi
          </li>
        </ol>
      </nav>
    <div class="card shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Lokasi</h5>
        <div class="d-flex align-items-center gap-2">
          {{-- Form Search --}}
          <form action="{{ route('lokasi.index') }}" method="get" class="d-flex">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control" placeholder="Cari lokasi..."
                value="{{ request('search') }}">
              <button class="btn btn-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>
            {{-- Tombol Tambah --}}
          <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bx bx-plus me-1"></i> Tambah Lokasi
          </a>
      </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="basic-datatables" class="display table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Kode Rak</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @foreach($lokasi as $data)
              <tr>
                <th scope="row">{{ $no++ }}</th>
                <td>{{ $data->kode_rak }}</td>
                <td>{{ $data->kategori->nama_kategori }}</td>
                <td>{{ $data->keterangan }}</td>
                <td class="text-center">
                  <a href="{{ route('lokasi.edit', $data->id) }}" class="btn btn-sm btn-warning me-1">
                    <i class="bx bx-pencil"></i>
                  </a>
                  <form action="{{ route('lokasi.destroy', $data->id) }}" method="post"
                        style="display:inline;" onsubmit="return confirm('Apakah anda yakin?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
