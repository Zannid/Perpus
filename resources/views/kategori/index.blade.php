@extends('layouts.backend')
@section('content')
<div class="row d-flex justify-content-center">
  <div class="col-md-8">
     <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
          <li class="breadcrumb-item">
            <a href="{{ route('kategori.index') }}" class="text-decoration-none text-primary fw-semibold">
              Kategori
            </a>
          </li>
          <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Daftar Kategori
          </li>
        </ol>
</nav>
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
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = ($kategori->currentPage() - 1) * $kategori->perPage() + 1; @endphp
              @foreach($kategori as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->nama_kategori }}</td>
                <td>{{ $data->keterangan }}</td>
                <td class="text-center">
                  <a href="{{ route('kategori.edit', $data->id) }}" class="btn btn-sm btn-warning">
                    <i class="bx bx-pencil"></i>
                  </a>
                  <form action="{{ route('kategori.destroy', $data->id) }}" method="post" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?')">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
          {{ $kategori->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
