@extends('layouts.backend')
@section('content')
<div class="col-md-12">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Buku</h5>
      <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
        <i class="lni lni-plus me-1"></i> Tambah Buku
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>No</th>
              <th>Kode Buku</th>
              <th>Judul</th>
              <th>Penulis</th>
              <th>Penerbit</th>
              <th>Tahun Terbit</th>
              <th>Kategori</th>
              <th>Cover</th>
              <th>Lokasi</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($buku as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->kode_buku }}</td>
                <td>{{ $data->judul }}</td>
                <td>{{ $data->penulis }}</td>
                <td>{{ $data->penerbit }}</td>
                <td>{{ $data->tahun_terbit }}</td>
                <td>{{ $data->kategori->nama_kategori }}</td>
                <td>
                  <img src="{{ asset('storage/buku/' . $data->foto)}}" alt="cover" class="img-thumbnail" width="50">
                </td>
                <td>{{ $data->lokasi->kode_rak }} - {{ $data->lokasi->keterangan }}</td>
                <td>{{ $data->stok }}</td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="{{ route('buku.edit', $data->id) }}" class="btn btn-sm btn-warning">
                      <i class="lni lni-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('buku.destroy', $data->id) }}" method="post" onsubmit="return confirm('Apakah anda yakin?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="lni lni-trash-can me-1"></i> Hapus
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
