@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Barang Keluar</h5>
      <a href="{{ route('barangkeluar.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="lni lni-plus me-1"></i> Tambah Barang Keluar
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Buku</th>
              <th>Kode</th>
              <th>Jumlah</th>
              <th>Tanggal Keluar</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($bk as $data)
            <tr>
              <th scope="row">{{ $no++ }}</th>
              <td>{{ $data->buku->judul }}</td>
              <td>{{ $data->kode_keluar }}</td>
              <td>{{ $data->jumlah }}</td>
              <td>{{ $data->tgl_keluar }}</td>
              <td>{{ $data->ket }}</td>
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('barangkeluar.edit', $data->id) }}" class="btn btn-sm btn-warning">
                    <i class="mdi mdi-pencil"></i> Edit
                  </a>
                  <form action="{{ route('barangkeluar.destroy', $data->id) }}" method="post" style="display:inline;">
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
