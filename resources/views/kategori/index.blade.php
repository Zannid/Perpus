@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Kategori</h5>
      <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="lni lni-plus me-1"></i> Tambah Kategori
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Kategori</th>
              <th>Ket</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($kategori as $data)
            <tr>
              <th scope="row">{{ $no++ }}</th>
              <td>{{ $data->nama_kategori }}</td>
              <td>{{ $data->keterangan }}</td>
              <td>
                <a href="{{ route('kategori.edit', $data->id) }}" class="btn btn-sm btn-warning">
                  <i class="mdi mdi-pencil"></i> Edit
                </a>
                <form action="{{ route('kategori.destroy', $data->id) }}" method="post" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?')">
                    <i class="lni lni-trash-can"></i> Delete
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
@endsection
