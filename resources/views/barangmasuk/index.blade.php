@extends('layouts.backend')
@section('title', 'E-Perpus - Data Barang Masuk')
@section('content')
<div class="col-md-12">
  {{-- Breadcrumb --}}
  <nav>
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('barangmasuk.index') }}" class="text-decoration-none text-primary fw-semibold">
          Barang Masuk
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
        Data Barang Masuk
      </li>
    </ol>
  </nav>

  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Barang Masuk</h5>
      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('barangmasuk.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari barang masuk..."
              value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>

        {{-- Tombol Tambah & PDF --}}
        <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
          <i class="lni lni-plus me-1"></i> Tambah Barang Masuk
        </a>
        <a href="{{ route('barangmasuk.export', request()->query()) }}" target="_blank"
          class="btn btn-danger btn-sm rounded-pill px-3">
          <i class="bx bx-file"></i> Buat PDF
        </a>
      </div>
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
              <th>Tanggal Masuk</th>
              <th>Keterangan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($barangmasuk as $data)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $data->buku->judul }}</td>
              <td>{{ $data->kode_masuk }}</td>
              <td class="text-center">{{ $data->jumlah }}</td>
              <td>{{ $data->tgl_masuk }}</td>
              <td>{{ $data->ket }}</td>
              <td class="text-center">
                <a href="{{ route('barangmasuk.edit', $data->id) }}" class="btn btn-sm btn-warning me-1">
                  <i class="bx bx-pencil"></i>
                </a>
                <form id="form-delete-{{ $data->id }}" 
                      action="{{ route('barangmasuk.destroy', $data->id) }}" 
                      method="post" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="button" 
                          class="btn btn-sm btn-danger btn-delete" 
                          data-id="{{ $data->id }}">
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

{{-- SweetAlert jika ada pesan sukses --}}
@if(session('success'))
<script>
Swal.fire({
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
@endif

{{-- SweetAlert konfirmasi delete --}}
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        Swal.fire({
            title: 'Apa Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    });
});
</script>
@endsection
