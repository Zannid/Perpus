@extends('layouts.backend')
@section('title', 'E-Perpus - wDaftar Buku')
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
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari buku...">
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-search-alt"></i>
            </button>
          </div>
        </form>

        {{-- Tombol Tambah Buku --}}
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
        <a href="{{ url('buku/tambah') }}" class="btn btn-primary btn-sm rounded-pill px-3">
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
                  <form id="form-delete-{{ $data->id }}" action="{{ route('buku.destroy', $data->id) }}" method="post" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-id="{{ $data->id }}" class="btn btn-sm btn-danger btn-delete" title="Hapus">
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

{{-- SweetAlert untuk Session Success --}}
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

{{-- SweetAlert untuk Konfirmasi Delete --}}
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
<script>
const searchInput = document.getElementById('searchInput');
const table = document.getElementById('basic-datatables').getElementsByTagName('tbody')[0];
searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = table.getElementsByTagName('tr');

    Array.from(rows).forEach(row => {
        const kode = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
        const judul = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() ?? '';
        const kategori = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() ?? '';

        if(kode.includes(filter) || judul.includes(filter) || kategori.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
