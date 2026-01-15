@extends('layouts.backend')
@section('title', 'E-Perpus - Peminjaman Pending')
@section('content')

<div class="container">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">
          Peminjaman
        </a>
      </li>
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Pending</li>
    </ol>
  </nav>

  {{-- Alert Messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bx bx-error me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Daftar Peminjaman Pending</h5>

      <div class="d-flex flex-wrap align-items-center gap-2">
        <input type="text" id="searchInput" class="form-control form-control-sm"
               placeholder="Cari Pengajuan..." style="max-width: 250px;">
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle" id="peminjamanTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Peminjam</th>
              <th>Buku</th>
              <th>Jumlah</th>
              <th>Tanggal Pinjam</th>
              <th>Tenggat</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @php $no = 1; @endphp

            @forelse($peminjaman as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td class="kode">{{ $data->kode_peminjaman }}</td>
                <td class="nama">{{ $data->user->name ?? '-' }}</td>
                <td class="buku">
                  @if($data->details->count() > 1)
                    <span class="badge bg-primary">{{ $data->details->count() }} Buku</span>
                  @else
                    {{ optional($data->buku)->judul ?? '-' }}
                  @endif
                </td>
                <td>{{ $data->jumlah }}</td>
                <td>{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
                <td>{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>

                <td class="text-center">
                  <div class="btn-group" role="group">

                    {{-- Tombol ACC dengan modal --}}
                    <button type="button" class="btn btn-success btn-sm rounded-start px-3"
                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $data->id }}">
                      <i class="bx bx-check"></i> ACC
                    </button>

                    {{-- Tombol Tolak --}}
                    <button type="button"
                            class="btn btn-danger btn-sm rounded-end px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal{{ $data->id }}">
                      <i class="bx bx-x"></i> Tolak
                    </button>

                  </div>
                </td>
              </tr>

              {{-- Modal Konfirmasi ACC --}}
              <div class="modal fade" id="approveModal{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                      <h5 class="modal-title text-white"><i class="bx bx-check-circle me-2"></i>Setujui Peminjaman</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('petugas.peminjaman.approve', $data->id) }}" method="POST">
                      @csrf
                      <div class="modal-body">
                        <div class="alert alert-info py-2 mb-3">
                          <small><i class="bx bx-info-circle me-1"></i>Silakan verifikasi tanggal pinjam dan batas pengembalian sebelum menyetujui.</small>
                        </div>
                        
                        <div class="mb-3">
                          <label class="form-label fw-bold">Tanggal Pinjam</label>
                          <input type="date" name="tgl_pinjam" class="form-control" 
                                 value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                          <label class="form-label fw-bold">Tenggat Pengembalian</label>
                          <input type="date" name="tenggat" class="form-control" 
                                 value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                          <small class="text-muted italic">* Default 7 hari ke depan</small>
                        </div>

                        <div class="border-top pt-3">
                            <p class="mb-1 fw-bold">Ringkasan Item:</p>
                            <ul class="small text-muted mb-0">
                                @foreach($data->details as $detail)
                                    <li>{{ optional($detail->buku)->judul }} ({{ $detail->jumlah }} item)</li>
                                @endforeach
                            </ul>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4">
                          <i class="bx bx-check me-1"></i>Setujui & Pinjamkan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              {{-- Modal Konfirmasi Tolak --}}
              <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title">Tolak Peminjaman</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('petugas.peminjaman.reject', $data->id) }}" method="POST">
                      @csrf

                      <div class="modal-body">
                        <p class="fw-semibold">Apakah Anda yakin ingin menolak peminjaman ini?</p>

                        <div class="alert alert-warning">
                          <strong>Peminjam:</strong> {{ $data->user->name }} <br>
                          <strong>Buku:</strong>
                          <ul class="mb-0">
                            @foreach($data->details as $detail)
                              <li>{{ optional($detail->buku)->judul ?? '-' }} ({{ $detail->jumlah }})</li>
                            @endforeach
                          </ul>
                          <strong>Total:</strong> {{ $data->jumlah }} eksemplar
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="alasan_tolak{{ $data->id }}">
                            Alasan Penolakan <span class="text-muted">(Opsional)</span>
                          </label>

                          <textarea class="form-control"
                                    id="alasan_tolak{{ $data->id }}"
                                    name="alasan_tolak"
                                    rows="3"
                                    placeholder="Contoh: Stok buku tidak tersedia, Buku sedang dalam perawatan, dll."></textarea>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                          <i class="bx bx-x"></i> Ya, Tolak
                        </button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>

            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="bx bx-info-circle fs-1 d-block mb-2"></i>
                  Tidak ada peminjaman pending
                </td>
              </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="d-flex justify-content-center mt-3">
        {{ $peminjaman->links() }}
      </div>

    </div>
  </div>

</div>

{{-- Script Search --}}
<script>
  const searchInput = document.getElementById('searchInput');
  const tableBody = document
    .getElementById('peminjamanTable')
    .getElementsByTagName('tbody')[0];

  searchInput.addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = tableBody.getElementsByTagName('tr');

    Array.from(rows).forEach(row => {
      const kode = row.querySelector('.kode')?.textContent.toLowerCase() ?? '';
      const nama = row.querySelector('.nama')?.textContent.toLowerCase() ?? '';
      const buku = row.querySelector('.buku')?.textContent.toLowerCase() ?? '';

      row.style.display = 
        (kode.includes(filter) || nama.includes(filter) || buku.includes(filter))
        ? '' 
        : 'none';
    });
  });
</script>

@endsection
