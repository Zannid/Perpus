@extends('layouts.backend')
@section('title', 'E-Perpus - Data Petugas')
@section('content')
<div class="row d-flex justify-content-center">
  <div class="col-md-10">
    <div class="card shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Petugas</h5>
        <div class="d-flex flex-wrap align-items-center gap-2">
          {{-- Form Search --}}
          <form action="{{ route('petugas.index') }}" method="get" class="d-flex">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control"
                     placeholder="Cari Petugas..." value="{{ request('search') }}">
              <button class="btn btn-outline-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>
        <a href="{{ route('petugas.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
          <i class="lni lni-plus me-1"></i> Tambah Petugas
        </a>
      </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table id="basic-datatables" class="display table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @foreach($petugas as $data)
              <tr>
                <th scope="row">{{ $no++ }}</th>
                <td>
                  <img src="{{ asset('storage/petugas/' . $data->foto) }}" 
                       alt="cover" 
                       class="img-thumbnail rounded-circle" 
                       width="50" height="50"
                       style="object-fit: cover;">
                </td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->email }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('petugas.edit', $data->id) }}" class="btn btn-sm btn-warning">
                      <i class="mdi mdi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('petugas.destroy', $data->id) }}" method="post" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?')">
                        <i class="lni lni-trash-can"></i> Delete
                      </button>
                    </form>
                  </div>
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
