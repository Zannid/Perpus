@extends('layouts.backend')
@section('title', 'E-Perpus - Data Kategori')
@section('content')
<div class="row d-flex justify-content-center">
  <div class="col-md-10">

    {{-- Breadcrumb --}}
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

    {{-- Card Kategori --}}
    <div class="card shadow-sm">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0">Data Kategori</h5>

        <div class="d-flex flex-wrap align-items-center gap-2">
          {{-- Form Search --}}
          <form action="{{ route('kategori.index') }}" method="get" class="d-flex">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control"
                     placeholder="Cari kategori..." value="{{ request('search') }}">
              <button class="btn btn-outline-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>

          {{-- Tombol Tambah --}}
          <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="lni lni-plus me-1"></i> Tambah Kategori
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $no = ($kategori->currentPage() - 1) * $kategori->perPage() + 1; @endphp
              @forelse($kategori as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->nama_kategori }}</td>
                <td>{{ $data->keterangan }}</td>
                <td class="text-center">
                  <a href="{{ route('kategori.edit', $data->id) }}" class="btn btn-sm btn-warning me-1">
                    <i class="bx bx-pencil"></i>
                  </a>
                  <form action="{{ route('kategori.destroy', $data->id) }}" method="post"
                        style="display:inline;" onsubmit="return confirm('Apakah anda yakin?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bx bx-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada data kategori</td>
              </tr>
              @endforelse
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
