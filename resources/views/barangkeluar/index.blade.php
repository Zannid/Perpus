@extends('layouts.backend')
@section('title', 'E-Perpus - Data Barang Keluar')
@section('content')
<div class="col-md-12">
   <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
          <li class="breadcrumb-item">
            <a href="{{ route('barangkeluar.index') }}" class="text-decoration-none text-primary fw-semibold">
              Barang Keluar
            </a>
          </li>
          <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Data Barang Keluar
          </li>
        </ol>
    </nav>

  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Barang Keluar</h5>
      <div class="d-flex align-items-center gap-2">
        {{-- Form Search --}}
        <form action="{{ route('barangkeluar.index') }}" method="get" class="d-flex">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari barang keluar..."
              value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>

        {{-- Tombol Tambah --}}
        <a href="{{ route('barangkeluar.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
          <i class="lni lni-plus me-1"></i> Tambah Barang Keluar
        </a>

        {{-- Tombol Export PDF --}}
        <a href="{{ route('barangkeluar.export', request()->query()) }}" 
           target="_blank" 
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
              <th>Tanggal Keluar</th>
              <th>Keterangan</th>
              <th class="text-center">Aksi</th>
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
              <td class="text-center">
                  <a href="{{ route('barangkeluar.edit', $data->id) }}" class="btn btn-sm btn-warning me-1">
                    <i class="bx bx-pencil"></i>
                  </a>
                  {{-- FORM DELETE --}}
                  <form id="form-delete-{{ $data->id }}" 
                        action="{{ route('barangkeluar.destroy', $data->id) }}" 
                        method="post" 
                        style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id }}">
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

{{-- SweetAlert Success --}}
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

{{-- SweetAlert Konfirmasi Delete --}}
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(){
        const id = this.getAttribute('data-id');
        Swal.fire({
            title: 'Apa Anda yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-'+id).submit();
            }
        })
    });
});
</script>
@endsection
