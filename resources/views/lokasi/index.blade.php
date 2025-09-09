@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Lokasi</h5>
      <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="lni lni-plus me-1"></i> Tambah Lokasi
      </a>
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
              <th>Aksi</th>
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
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('lokasi.edit', $data->id) }}" class="btn btn-sm btn-warning">
                    <i class="mdi mdi-pencil"></i> Edit
                  </a>
                  <form action="{{ route('lokasi.destroy', $data->id) }}" method="post" style="display:inline;">
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
@endsection
