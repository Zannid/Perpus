@extends('layouts.backend')
@section('title', 'E-Perpus - Data About')
@section('content')

<div class="row d-flex justify-content-center">
  <div class="col-md-10">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
        <li class="breadcrumb-item">
          <a href="{{ route('about.index') }}" class="text-decoration-none text-primary fw-semibold">
            About
          </a>
        </li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
          Daftar About
        </li>
      </ol>
    </nav>

    {{-- Card About --}}
    <div class="card shadow-sm">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0">Data About</h5>

        <div class="d-flex flex-wrap align-items-center gap-2">

          {{-- Form Search --}}
          <form action="{{ route('about.index') }}" method="get" class="d-flex">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control"
                     placeholder="Cari about..." value="{{ request('search') }}">
              <button class="btn btn-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>

          {{-- Tombol Tambah --}}
          <a href="{{ route('about.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bx bx-plus me-1"></i> Tambah About
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Status</th>
                <th>No</th>
                <th>Judul</th>
                <th>Isi</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>

            <tbody>
              @php $no = 1; @endphp
              @forelse($about as $data)
              <tr>

                {{-- STATUS (checkbox untuk memilih About yang aktif) --}}
                <td>
                  <form action="{{ route('about.activate', $data->id) }}" method="POST">
                    @csrf
                    <input 
                      type="checkbox"
                      class="form-check-input"
                      name="is_active"
                      onchange="this.form.submit()"
                      {{ $data->is_active ? 'checked' : '' }}
                    >
                  </form>
                </td>

                <td>{{ $no++ }}</td>
                <td>{{ $data->title }}</td>
                <td>{{ Str::limit($data->content, 50) }}</td>

                <td class="text-center">
                  <a href="{{ route('about.edit', $data->id) }}" class="btn btn-sm btn-warning me-1">
                    <i class="bx bx-pencil"></i>
                  </a>

                  <form action="{{ route('about.destroy', $data->id) }}" method="post"
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
                <td colspan="5" class="text-center text-muted">Tidak ada data about</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
