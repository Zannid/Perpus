@extends('layouts.backend')
@section('content')

<style>
/* ========================================
   CARD HEADER - FILTER & ACTIONS
======================================== */
.card-header {
  padding: 0.85rem 1rem;
}

.header-title {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 0.65rem 0;
}

/* Filter row: date + search + reset */
.filter-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  align-items: flex-end;
  margin-bottom: 0.5rem;
}

.filter-row .filter-field {
  display: flex;
  flex-direction: column;
  flex: 1 1 130px;
  min-width: 0;
}

.filter-row .filter-field label {
  font-size: 0.75rem;
  margin-bottom: 0.2rem;
  color: #6c757d;
  white-space: nowrap;
}

.filter-row .btn-reset {
  flex-shrink: 0;
  align-self: flex-end;
}

/* Action buttons row */
.action-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  align-items: center;
}

.action-row .btn {
  font-size: 0.8rem;
  padding: 0.3rem 0.7rem;
  white-space: nowrap;
}

/* ========================================
   DESKTOP TABLE
======================================== */
.table-desktop {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-desktop table {
  min-width: 900px;
}

/* ========================================
   MOBILE CARD LIST
======================================== */
.mobile-list {
  display: none;
}

.pm-card {
  background: #fff;
  border: 1px solid #e3e6f0;
  border-radius: 12px;
  padding: 0.9rem 1rem;
  margin-bottom: 0.7rem;
  box-shadow: 0 1px 5px rgba(0,0,0,0.07);
  position: relative;
}

.pm-card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.pm-card-kode {
  font-size: 0.78rem;
  color: #6c757d;
}

.pm-card-nama {
  font-weight: 600;
  font-size: 0.95rem;
  color: #212529;
}

.pm-card-body .pm-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 0.22rem 0;
  font-size: 0.84rem;
  border-bottom: 1px solid #f1f3f5;
}

.pm-card-body .pm-row:last-child {
  border-bottom: none;
}

.pm-row .pm-label {
  color: #6c757d;
  min-width: 95px;
  flex-shrink: 0;
  font-size: 0.8rem;
}

.pm-row .pm-value {
  text-align: right;
  word-break: break-word;
  font-size: 0.84rem;
}

/* Action buttons inside mobile card */
.pm-card-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  margin-top: 0.65rem;
  padding-top: 0.6rem;
  border-top: 1px solid #e9ecef;
}

.pm-card-actions .btn {
  font-size: 0.78rem;
  padding: 0.28rem 0.65rem;
  display: inline-flex;
  align-items: center;
  gap: 3px;
}

/* Return form in mobile */
.pm-return-form {
  display: flex;
  gap: 0.3rem;
  align-items: center;
  flex-wrap: wrap;
  margin-top: 0.4rem;
  padding-top: 0.5rem;
  border-top: 1px dashed #dee2e6;
}

.pm-return-form select {
  font-size: 0.78rem;
  padding: 0.25rem 0.5rem;
}

.pm-return-form .btn {
  font-size: 0.78rem;
  padding: 0.28rem 0.65rem;
}

/* ========================================
   PAGINATION
======================================== */
.pagination {
  flex-wrap: wrap;
  gap: 2px;
}

.page-link {
  font-size: 0.8rem;
  padding: 0.3rem 0.55rem;
}

/* ========================================
   RESPONSIVE BREAKPOINTS
======================================== */
@media (max-width: 767.98px) {
  .table-desktop { display: none !important; }
  .mobile-list   { display: block !important; }

  .filter-row .filter-field {
    flex: 1 1 calc(50% - 0.4rem);
  }

  .filter-row .filter-field:last-of-type {
    flex: 1 1 100%;
  }

  .action-row .btn {
    flex: 1 1 calc(50% - 0.4rem);
    justify-content: center;
    text-align: center;
  }
}

@media (max-width: 400px) {
  .action-row .btn {
    flex: 1 1 100%;
  }
}
</style>

<div class="col-md-12">
  {{-- Breadcrumb --}}
  <nav>
    <ol class="breadcrumb bg-light p-2 rounded shadow-sm mb-3" style="font-size:0.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">Peminjaman</a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Data Peminjaman</li>
    </ol>
  </nav>

  <div class="card shadow-lg">

    {{-- ===== CARD HEADER ===== --}}
    <div class="card-header">
      <h5 class="header-title">Data Peminjaman</h5>

      {{-- Filter Row --}}
      <div class="filter-row">
        <div class="filter-field">
          <label for="tanggal_awal">Tanggal Awal</label>
          <input type="date" id="tanggal_awal" class="form-control form-control-sm">
        </div>
        <div class="filter-field">
          <label for="tanggal_akhir">Tanggal Akhir</label>
          <input type="date" id="tanggal_akhir" class="form-control form-control-sm">
        </div>
        <div class="filter-field">
          <label for="searchInput">Cari</label>
          <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari kode, nama, buku, status...">
        </div>
        <button class="btn btn-outline-secondary btn-sm btn-reset" id="resetBtn" type="button">Reset</button>
      </div>

      {{-- Action Buttons --}}
      <div class="action-row">
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm rounded-pill">
          <i class="bx bx-plus me-1"></i> Tambah
        </a>
        @endif
        <a href="{{ route('rating.show') }}" class="btn btn-warning btn-sm rounded-pill">
          <i class="bx bx-star me-1"></i> Rating
        </a>
        @if(Auth::user()->role == 'admin')
        <a href="{{ route('admin.rating.index') }}" class="btn btn-success btn-sm rounded-pill">
          <i class="bx bx-bar-chart me-1"></i> Kelola Rating
        </a>
        @endif
        <a href="{{ route('peminjaman.export', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill">
          <i class="bx bx-file me-1"></i> PDF
        </a>
      </div>
    </div>

    <div class="card-body px-2 px-sm-3">

      {{-- ===== DESKTOP TABLE ===== --}}
      <div class="table-responsive table-desktop">
        <table class="display table table-striped table-hover align-middle" id="peminjamanTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Peminjam</th>
              <th>Buku</th>
              <th>Jml</th>
              <th>Tgl Pinjam</th>
              <th>Tenggat</th>
              <th class="text-center">Status</th>
              <th>Denda</th>
              <th class="text-center">Aksi</th>
              <th class="text-center">Perpanjang</th>
              <th class="text-center">Kembali</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; use Illuminate\Support\Str; @endphp
            @foreach($peminjaman as $data)
            <tr>
              <td>{{ $no++ }}</td>
              <td class="kode">{{ $data->kode_peminjaman }}</td>
              <td class="nama">{{ $data->user->name ?? '-' }}</td>
              <td class="buku">
                @if($data->details->count() > 1)
                  <span class="badge bg-primary">{{ $data->details->count() }} Buku</span>
                  <button class="btn btn-sm btn-link p-0" type="button"
                          data-bs-toggle="collapse" data-bs-target="#bukuList{{ $data->id }}">
                    Lihat
                  </button>
                  <div class="collapse mt-1" id="bukuList{{ $data->id }}">
                    <ul class="list-unstyled mb-0 small">
                      @foreach($data->details as $detail)
                        <li>• {{ optional($detail->buku)->judul ?? '-' }} ({{ $detail->jumlah }}x)</li>
                      @endforeach
                    </ul>
                  </div>
                @elseif($data->details->count() == 1)
                  {{ Str::limit(optional($data->details->first()->buku)->judul ?? '-', 25) }}
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td class="text-center">{{ $data->details->sum('jumlah') }}</td>
              <td class="tgl_pinjam">{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
              <td class="tgl_kembali">{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
              <td class="text-center status">
                @switch($data->status)
                  @case('Kembali')  <span class="badge bg-success">{{ $data->status }}</span>   @break
                  @case('Dipinjam') <span class="badge bg-warning text-dark">{{ $data->status }}</span> @break
                  @case('Lunas')    <span class="badge bg-info">{{ $data->status }}</span>       @break
                  @case('Pending')  <span class="badge bg-secondary">{{ $data->status }}</span>  @break
                  @case('Ditolak')  <span class="badge bg-danger">{{ $data->status }}</span>     @break
                  @default          <span class="badge bg-secondary">{{ $data->status }}</span>
                @endswitch
              </td>
              <td class="text-center denda">
                @if($data->denda_berjalan > 0)
                  <span class="text-danger fw-bold">Rp {{ number_format($data->denda_berjalan, 0, ',', '.') }}</span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>

              {{-- AKSI DESKTOP --}}
              <td class="text-center">
                <div class="d-flex justify-content-center align-items-center gap-1 flex-nowrap">
                  <a href="{{ route('peminjaman.show', $data->id) }}" class="btn btn-sm btn-info" title="Detail">
                    <i class="bx bx-show"></i>
                  </a>
                  @if($data->status == 'Pending')
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                      <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $data->id }}" title="Approve">
                        <i class="bx bx-check"></i>
                      </button>
                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}" title="Reject">
                        <i class="bx bx-x"></i>
                      </button>
                    @endif
                    <a href="{{ route('peminjaman.edit', $data->id) }}" class="btn btn-sm btn-warning" title="Edit">
                      <i class="bx bx-pencil"></i>
                    </a>
                  @endif
                  @if($data->due_denda > 0 && $data->status != 'Lunas' && $data->status != 'Pending')
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                      <form action="{{ route('peminjaman.confirmPay', $data->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Set denda ini sebagai lunas?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" title="Set Lunas"><i class="bx bx-credit-card"></i></button>
                      </form>
                    @else
                      <a href="{{ route('peminjaman.pay', $data->id) }}" class="btn btn-sm btn-success" title="Bayar Denda"><i class="bx bx-credit-card"></i></a>
                    @endif
                  @endif
                  @if($data->status != 'Pending' && $data->status != 'Dipinjam')
                  <form action="{{ route('peminjaman.destroy', $data->id) }}" method="post" class="d-inline m-0" onsubmit="return confirm('Hapus peminjaman ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bx bx-trash"></i></button>
                  </form>
                  @endif
                </div>
              </td>

              {{-- PERPANJANG DESKTOP --}}
              <td class="text-center">
                @if($data->status == 'Dipinjam' && $data->canExtend())
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#perpanjangModal{{ $data->id }}">
                    <i class="bx bx-time"></i>
                  </button>
                @elseif($data->perpanjanganPending)
                  <button class="btn btn-secondary btn-sm" disabled><i class="bx bx-hourglass"></i></button>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>

              {{-- KEMBALI DESKTOP --}}
              <td class="text-center">
                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                  @if($data->status == 'Dipinjam')
                  <form action="{{ route('peminjaman.return', $data->id) }}" method="post" class="d-flex gap-1 justify-content-center">
                    @csrf
                    <select name="kondisi" class="form-select form-select-sm w-auto" required aria-label="Kondisi">
                      <option value="">Kondisi</option>
                      <option value="Bagus">Bagus</option>
                      <option value="Rusak">Rusak</option>
                      <option value="Hilang">Hilang</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Kembalikan buku ini?')">
                      <i class="bx bx-undo"></i>
                    </button>
                  </form>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                @else
                  <span class="text-muted" style="font-size:0.78rem;">Proses Petugas</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- ===== MOBILE CARD LIST ===== --}}
      <div class="mobile-list">
        @php $no = 1; @endphp
        @forelse($peminjaman as $data)
        <div class="pm-card" data-nama="{{ strtolower($data->user->name ?? '') }}"
             data-buku="{{ strtolower($data->details->pluck('buku.judul')->implode(' ')) }}"
             data-status="{{ strtolower($data->status) }}"
             data-tgl="{{ $data->tgl_pinjam }}"
             data-kode="{{ strtolower($data->kode_peminjaman) }}">

          {{-- Card Header --}}
          <div class="pm-card-header">
            <div>
              <div class="pm-card-kode">#{{ $no++ }} · {{ $data->kode_peminjaman }}</div>
              <div class="pm-card-nama">{{ $data->user->name ?? '-' }}</div>
            </div>
            <div>
              @switch($data->status)
                @case('Kembali')  <span class="badge bg-success">{{ $data->status }}</span>         @break
                @case('Dipinjam') <span class="badge bg-warning text-dark">{{ $data->status }}</span> @break
                @case('Lunas')    <span class="badge bg-info">{{ $data->status }}</span>             @break
                @case('Pending')  <span class="badge bg-secondary">{{ $data->status }}</span>        @break
                @case('Ditolak')  <span class="badge bg-danger">{{ $data->status }}</span>           @break
                @default          <span class="badge bg-secondary">{{ $data->status }}</span>
              @endswitch
            </div>
          </div>

          {{-- Card Body --}}
          <div class="pm-card-body">
            <div class="pm-row">
              <span class="pm-label">Buku</span>
              <span class="pm-value">
                @if($data->details->count() > 1)
                  {{ $data->details->count() }} Buku
                @elseif($data->details->count() == 1)
                  {{ Str::limit(optional($data->details->first()->buku)->judul ?? '-', 35) }}
                @else -
                @endif
              </span>
            </div>
            <div class="pm-row">
              <span class="pm-label">Jumlah</span>
              <span class="pm-value">{{ $data->details->sum('jumlah') }} item</span>
            </div>
            <div class="pm-row">
              <span class="pm-label">Tgl Pinjam</span>
              <span class="pm-value">{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</span>
            </div>
            <div class="pm-row">
              <span class="pm-label">Tenggat</span>
              <span class="pm-value">{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</span>
            </div>
            @if($data->denda_berjalan > 0)
            <div class="pm-row">
              <span class="pm-label">Denda</span>
              <span class="pm-value text-danger fw-bold">Rp {{ number_format($data->denda_berjalan, 0, ',', '.') }}</span>
            </div>
            @endif
          </div>

          {{-- Card Actions --}}
          <div class="pm-card-actions">
            <a href="{{ route('peminjaman.show', $data->id) }}" class="btn btn-sm btn-info">
              <i class="bx bx-show"></i> Detail
            </a>

            @if($data->status == 'Pending')
              @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $data->id }}">
                  <i class="bx bx-check"></i> Setujui
                </button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
                  <i class="bx bx-x"></i> Tolak
                </button>
              @endif
              <a href="{{ route('peminjaman.edit', $data->id) }}" class="btn btn-sm btn-warning">
                <i class="bx bx-pencil"></i> Edit
              </a>
            @endif

            @if($data->due_denda > 0 && $data->status != 'Lunas' && $data->status != 'Pending')
              @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                <form action="{{ route('peminjaman.confirmPay', $data->id) }}" method="POST" onsubmit="return confirm('Set denda ini sebagai lunas?')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-success"><i class="bx bx-credit-card"></i> Set Lunas</button>
                </form>
              @else
                <a href="{{ route('peminjaman.pay', $data->id) }}" class="btn btn-sm btn-success"><i class="bx bx-credit-card"></i> Bayar Denda</a>
              @endif
            @endif

            @if($data->status != 'Pending' && $data->status != 'Dipinjam')
            <form action="{{ route('peminjaman.destroy', $data->id) }}" method="post" onsubmit="return confirm('Hapus peminjaman ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"><i class="bx bx-trash"></i> Hapus</button>
            </form>
            @endif

            @if($data->status == 'Dipinjam' && $data->canExtend())
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#perpanjangModal{{ $data->id }}">
              <i class="bx bx-time"></i> Perpanjang
            </button>
            @elseif($data->perpanjanganPending)
            <button class="btn btn-sm btn-secondary" disabled>
              <i class="bx bx-hourglass"></i> Menunggu
            </button>
            @endif
          </div>

          {{-- Return Form (Petugas/Admin Only) --}}
          @if((Auth::user()->role == 'admin' || Auth::user()->role == 'petugas') && $data->status == 'Dipinjam')
          <form action="{{ route('peminjaman.return', $data->id) }}" method="post" class="pm-return-form">
            @csrf
            <small class="text-muted me-1 fw-semibold">Kembalikan:</small>
            <select name="kondisi" class="form-select form-select-sm w-auto" required aria-label="Kondisi Buku">
              <option value="">Kondisi</option>
              <option value="Bagus">Bagus</option>
              <option value="Rusak">Rusak</option>
              <option value="Hilang">Hilang</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Kembalikan buku ini?')">
              <i class="bx bx-undo me-1"></i> Kembali
            </button>
          </form>
          @elseif($data->status == 'Dipinjam')
          <div class="mt-2 pt-2 border-top" style="font-size:0.78rem; color:#6c757d;">
            <i class="bx bx-info-circle me-1"></i> Pengembalian diproses oleh Petugas
          </div>
          @endif

        </div>
        @empty
        <div class="text-center text-muted py-4">
          <i class="bx bx-info-circle fs-4 d-block mb-1"></i>
          Tidak ada data peminjaman.
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
     @if(!request()->has('search') || empty(request('search')))
            <div class="mt-4">
                {{ $peminjaman->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
    </div>
  </div>
</div>

{{-- ===== MODALS (di luar loop card body agar tidak nested) ===== --}}
@foreach($peminjaman as $data)

  {{-- Modal Approve --}}
  <div class="modal fade" id="approveModal{{ $data->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="bx bx-check-circle me-2"></i>Setujui Peminjaman</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST">
          @csrf
          <div class="modal-body text-start">
            <div class="mb-3">
              <label class="form-label fw-bold">Tanggal Pinjam</label>
              <input type="date" name="tgl_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Tenggat Pengembalian</label>
              <input type="date" name="tenggat" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
              <small class="text-muted">* Default 7 hari ke depan</small>
            </div>
            <div class="border-top pt-3">
              <p class="mb-1 fw-bold">Peminjam: <span class="fw-normal">{{ $data->user->name }}</span></p>
              <p class="mb-1 fw-bold">Buku:</p>
              <ul class="small text-muted mb-0">
                @foreach($data->details as $detail)
                  <li>{{ optional($detail->buku)->judul }} ({{ $detail->jumlah }} item)</li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success px-4"><i class="bx bx-check me-1"></i>Setujui</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Reject --}}
  <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Tolak Peminjaman</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('petugas.peminjaman.reject', $data->id) }}" method="POST">
          @csrf
          <div class="modal-body">
            <p>Anda yakin ingin menolak peminjaman dari <strong>{{ $data->user->name }}</strong>?</p>
            <div class="mb-3">
              <label class="form-label">Alasan Penolakan</label>
              <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Masukkan alasan..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Perpanjang --}}
  <div class="modal fade" id="perpanjangModal{{ $data->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title"><i class="bx bx-time me-2"></i>Ajukan Perpanjangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('perpanjangan.store', $data->id) }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="alert alert-info small">
              <strong>Info Peminjaman:</strong><br>
              <i class="bx bx-book"></i> Jumlah Buku: {{ $data->details->count() }}<br>
              <i class="bx bx-calendar"></i> Tenggat: {{ $data->formatted_tanggal_kembali ?? $data->tenggat }}<br>
              <i class="bx bx-refresh"></i> Perpanjangan ke-{{ $data->jumlah_perpanjangan + 1 }}
            </div>
            <div class="mb-3">
              <label class="form-label" for="durasi{{ $data->id }}">Durasi Perpanjangan <span class="text-danger">*</span></label>
              <select class="form-select durasi-select" id="durasi{{ $data->id }}" name="durasi"
                      data-tenggat="{{ $data->tenggat }}" data-id="{{ $data->id }}" required>
                <option value="">Pilih Durasi...</option>
                <option value="3">3 Hari</option>
                <option value="7">7 Hari (1 Minggu)</option>
                <option value="14">14 Hari (2 Minggu)</option>
              </select>
            </div>
            <div class="mb-3 preview-tenggat" id="previewTenggat{{ $data->id }}" style="display:none;">
              <div class="alert alert-success small">
                <strong>Tenggat Baru:</strong> <span class="tenggat-baru"></span>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="alasan{{ $data->id }}">Alasan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="alasan{{ $data->id }}" name="alasan" rows="3"
                        placeholder="Jelaskan alasan perpanjangan..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning"><i class="bx bx-send me-1"></i>Ajukan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== FILTER =====
  const searchInput  = document.getElementById('searchInput');
  const tanggalAwal  = document.getElementById('tanggal_awal');
  const tanggalAkhir = document.getElementById('tanggal_akhir');
  const resetBtn     = document.getElementById('resetBtn');

  // Desktop rows
  const tableRows  = document.querySelectorAll('#peminjamanTable tbody tr');
  // Mobile cards
  const mobileCards = document.querySelectorAll('.pm-card');

  function filterAll() {
    const keyword   = searchInput.value.toLowerCase().trim();
    const startDate = tanggalAwal.value  ? new Date(tanggalAwal.value)  : null;
    const endDate   = tanggalAkhir.value ? new Date(tanggalAkhir.value) : null;

    // Desktop
    tableRows.forEach(row => {
      const nama   = row.querySelector('.nama')?.textContent.toLowerCase()   ?? '';
      const buku   = row.querySelector('.buku')?.textContent.toLowerCase()   ?? '';
      const status = row.querySelector('.status')?.textContent.toLowerCase() ?? '';
      const kode   = row.querySelector('.kode')?.textContent.toLowerCase()   ?? '';
      const tgl    = row.querySelector('.tgl_pinjam')?.textContent ?? '';
      const tglDate = tgl ? new Date(tgl) : null;

      let matchKw   = nama.includes(keyword) || buku.includes(keyword) || status.includes(keyword) || kode.includes(keyword);
      let matchDate = true;
      if (startDate && tglDate) matchDate = tglDate >= startDate;
      if (endDate   && tglDate) matchDate = matchDate && tglDate <= endDate;

      row.style.display = (matchKw && matchDate) ? '' : 'none';
    });

    // Mobile
    mobileCards.forEach(card => {
      const nama   = card.dataset.nama   ?? '';
      const buku   = card.dataset.buku   ?? '';
      const status = card.dataset.status ?? '';
      const kode   = card.dataset.kode   ?? '';
      const tgl    = card.dataset.tgl    ?? '';
      const tglDate = tgl ? new Date(tgl) : null;

      let matchKw   = nama.includes(keyword) || buku.includes(keyword) || status.includes(keyword) || kode.includes(keyword);
      let matchDate = true;
      if (startDate && tglDate) matchDate = tglDate >= startDate;
      if (endDate   && tglDate) matchDate = matchDate && tglDate <= endDate;

      card.style.display = (matchKw && matchDate) ? '' : 'none';
    });
  }

  searchInput.addEventListener('keyup', filterAll);
  tanggalAwal.addEventListener('change', filterAll);
  tanggalAkhir.addEventListener('change', filterAll);
  resetBtn.addEventListener('click', function () {
    searchInput.value  = '';
    tanggalAwal.value  = '';
    tanggalAkhir.value = '';
    tableRows.forEach(r  => r.style.display    = '');
    mobileCards.forEach(c => c.style.display   = '');
  });

  // ===== PREVIEW TENGGAT BARU =====
  document.querySelectorAll('.durasi-select').forEach(function (select) {
    select.addEventListener('change', function () {
      const durasi      = parseInt(this.value);
      const tenggatLama = new Date(this.dataset.tenggat);
      const dataId      = this.dataset.id;
      const previewDiv  = document.getElementById('previewTenggat' + dataId);
      const tenggatSpan = previewDiv?.querySelector('.tenggat-baru');

      if (durasi && previewDiv && tenggatSpan) {
        const tenggatBaru = new Date(tenggatLama);
        tenggatBaru.setDate(tenggatBaru.getDate() + durasi);
        tenggatSpan.textContent = tenggatBaru.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
        previewDiv.style.display = 'block';
      } else if (previewDiv) {
        previewDiv.style.display = 'none';
      }
    });
  });

});
</script>

@endsection
