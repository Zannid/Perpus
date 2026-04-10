@extends('layouts.backend')
@section('title', 'E-Perpus - Data Lokasi')
@section('content')

<style>
  /* ===== BREADCRUMB ===== */
  .breadcrumb {
    flex-wrap: wrap;
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem !important;
  }

  /* ===== CARD HEADER ===== */
  .card-header {
    padding: 0.75rem 1rem;
  }

  .header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.6rem;
  }

  .header-top h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
  }

  /* Search + Tambah dalam satu baris */
  .header-controls {
    display: flex;
    gap: 0.4rem;
    align-items: center;
  }

  .header-controls .search-form {
    flex: 1 1 auto;
    min-width: 0;
  }

  .header-controls .search-form .input-group {
    width: 100%;
  }

  .header-controls .btn-tambah {
    flex-shrink: 0;
    white-space: nowrap;
  }

  /* ===== TABLE ===== */
  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* ===== MOBILE CARD LIST ===== */
  .mobile-list {
    display: none;
  }

  .mobile-item {
    background: #fff;
    border: 1px solid #e3e6f0;
    border-radius: 10px;
    padding: 0.85rem 1rem;
    margin-bottom: 0.65rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  }

  .mobile-item .item-number {
    font-size: 0.7rem;
    color: #adb5bd;
    margin-bottom: 0.35rem;
  }

  .mobile-item .item-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.28rem;
    font-size: 0.875rem;
  }

  .mobile-item .item-label {
    color: #6c757d;
    font-weight: 500;
    min-width: 90px;
    flex-shrink: 0;
  }

  .mobile-item .item-value {
    color: #212529;
    text-align: right;
    word-break: break-word;
  }

  .mobile-item .item-actions {
    display: flex;
    gap: 0.4rem;
    margin-top: 0.6rem;
    justify-content: center;
  }

  /* ===== PAGINATION ===== */
  .pagination {
    flex-wrap: wrap;
    gap: 2px;
  }

  .page-link {
    font-size: 0.8rem;
    padding: 0.3rem 0.55rem;
  }

  /* ===== RESPONSIVE BREAKPOINTS ===== */
  @media (max-width: 575.98px) {
    .table-desktop {
      display: none !important;
    }
    .mobile-list {
      display: block !important;
    }

    /* Judul + controls dalam satu kolom, tapi controls tetap 1 baris */
    .header-top {
      flex-direction: column;
      align-items: stretch;
      gap: 0.5rem;
      margin-bottom: 0;
    }

    /* Search mengambil sisa ruang, tombol Tambah di sebelah kanan */
    .header-controls {
      flex-direction: row; /* TETAP row, bukan column */
      align-items: center;
    }

    .header-controls .search-form {
      flex: 1 1 0;
      min-width: 0;
    }

    /* Tombol Tambah: hanya icon + teks singkat, JANGAN full width */
    .header-controls .btn-tambah {
      flex-shrink: 0;
    }
  }
</style>

<div class="row d-flex justify-content-center">
  <div class="col-12 col-md-10">

    {{-- Breadcrumb --}}
    <nav>
      <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-3">
        <li class="breadcrumb-item">
          <a href="{{ route('lokasi.index') }}" class="text-decoration-none text-primary fw-semibold">
            Lokasi
          </a>
        </li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
          Data Lokasi
        </li>
      </ol>
    </nav>

    <div class="card shadow-lg">

      {{-- Card Header --}}
      <div class="card-header">
        {{-- Baris 1: Judul --}}
        <div class="header-top">
          <h5>Data Lokasi</h5>
        </div>
        {{-- Baris 2: Search + Tambah selalu 1 baris --}}
        <div class="header-controls">
          <form action="{{ route('lokasi.index') }}" method="get" class="search-form">
            <div class="input-group input-group-sm">
              <input type="text" name="search" class="form-control"
                placeholder="Cari lokasi..."
                value="{{ request('search') }}">
              <button class="btn btn-primary" type="submit">
                <i class="bx bx-search-alt"></i>
              </button>
            </div>
          </form>

          <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 btn-tambah">
            <i class="bx bx-plus me-1"></i>
            <span class="d-none d-sm-inline">Tambah </span>Lokasi
          </a>
        </div>
      </div>

      <div class="card-body px-2 px-sm-3">

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="table-responsive table-desktop">
          <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:50px;">No</th>
                <th>Kode Rak</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th class="text-center" style="width:110px;">Aksi</th>
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
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('lokasi.edit', $data->id) }}" class="btn btn-sm btn-warning">
                      <i class="bx bx-pencil"></i>
                    </a>
                    <form action="{{ route('lokasi.destroy', $data->id) }}" method="post"
                          style="display:inline;"
                          onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bx bx-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- ===== MOBILE CARD LIST ===== --}}
        <div class="mobile-list">
          @php $no = 1; @endphp
          @forelse($lokasi as $data)
          <div class="mobile-item">
            <div class="item-number">#{{ $no++ }}</div>
            <div class="item-row">
              <span class="item-label">Kode Rak</span>
              <span class="item-value fw-semibold">{{ $data->kode_rak }}</span>
            </div>
            <div class="item-row">
              <span class="item-label">Kategori</span>
              <span class="item-value">{{ $data->kategori->nama_kategori }}</span>
            </div>
            <div class="item-row">
              <span class="item-label">Keterangan</span>
              <span class="item-value text-muted">{{ $data->keterangan ?: '-' }}</span>
            </div>
            <div class="item-actions">
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('lokasi.edit', $data->id) }}" class="btn btn-sm btn-warning">
                  <i class="bx bx-pencil me-1"></i> Edit
                </a>
                <form action="{{ route('lokasi.destroy', $data->id) }}" method="post"
                      onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bx bx-trash me-1"></i> Hapus
                  </button>
                </form>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center text-muted py-4">
            <i class="bx bx-info-circle fs-4 d-block mb-1"></i>
            Tidak ada data lokasi.
          </div>
          @endforelse
        </div>

        {{-- Pagination --}}
        @if(!request()->has('search') || empty(request('search')))
        <nav aria-label="Page navigation" class="mt-3">
          {{ $lokasi->links('pagination::bootstrap-4') }}
        </nav>
        @endif

      </div>
    </div>

  </div>
</div>

<script>
  const searchInput = document.querySelector('input[name="search"]');
  const desktopTbody = document.querySelector('.table-desktop tbody');
  const mobileItems  = document.querySelectorAll('.mobile-item');

  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      const filter = this.value.toLowerCase().trim();

      if (desktopTbody) {
        Array.from(desktopTbody.getElementsByTagName('tr')).forEach(row => {
          const kodeRak    = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
          const kategori   = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() ?? '';
          const keterangan = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() ?? '';
          row.style.display = (kodeRak.includes(filter) || kategori.includes(filter) || keterangan.includes(filter))
            ? '' : 'none';
        });
      }

      mobileItems.forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(filter) ? '' : 'none';
      });
    });
  }
</script>

@endsection
