@extends('layouts.backend')
@section('title', 'E-Perpus - Perpanjangan Pending')
@section('content')

<div class="container">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
      <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Perpanjangan Pending</li>
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
    <div class="card-header">
      <h5 class="mb-0">
        <i class="bx bx-time me-2"></i>Daftar Perpanjangan Pending
      </h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Peminjam</th>
              <th>Buku</th>
              <th>Tenggat Lama</th>
              <th>Tenggat Baru</th>
              <th>Alasan</th>
              <th>Tanggal Ajukan</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @php $no = 1; @endphp

            @forelse($perpanjangan as $data)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data->peminjaman->user->name ?? '-' }}</td>
                <td>{{ $data->peminjaman->buku->judul ?? '-' }}</td>
                <td>{{ $data->formatted_tenggat_lama }}</td>
                <td>
                  <span class="badge bg-success">{{ $data->formatted_tenggat_baru }}</span>
                </td>
                <td>{{ Str::limit($data->alasan, 50) }}</td>
                <td>{{ $data->created_at->format('d M Y H:i') }}</td>

                <td class="text-center">
                  <div class="btn-group" role="group">

                    {{-- Tombol Detail --}}
                    <button type="button"
                            class="btn btn-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#detailModal{{ $data->id }}">
                      <i class="bx bx-info-circle"></i>
                    </button>

                    {{-- Tombol ACC --}}
                    <form action="{{ route('petugas.perpanjangan.approve', $data->id) }}" 
                          method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm"
                              onclick="return confirm('Setujui perpanjangan ini?')">
                        <i class="bx bx-check"></i>
                      </button>
                    </form>

                    {{-- Tombol Tolak --}}
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal{{ $data->id }}">
                      <i class="bx bx-x"></i>
                    </button>

                  </div>
                </td>
              </tr>

              {{-- Modal Detail --}}
              <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                      <h5 class="modal-title">Detail Perpanjangan</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <table class="table table-borderless">
                        <tr>
                          <td width="30%"><strong>Peminjam</strong></td>
                          <td>: {{ $data->peminjaman->user->name }}</td>
                        </tr>
                        <tr>
                          <td><strong>Kode Peminjaman</strong></td>
                          <td>: {{ $data->peminjaman->kode_peminjaman }}</td>
                        </tr>
                        <tr>
                          <td><strong>Buku</strong></td>
                          <td>: {{ $data->peminjaman->buku->judul }}</td>
                        </tr>
                        <tr>
                          <td><strong>Tanggal Pinjam</strong></td>
                          <td>: {{ $data->peminjaman->formatted_tanggal_pinjam }}</td>
                        </tr>
                        <tr>
                          <td><strong>Tenggat Lama</strong></td>
                          <td>: {{ $data->formatted_tenggat_lama }}</td>
                        </tr>
                        <tr>
                          <td><strong>Tenggat Baru</strong></td>
                          <td>: <strong class="text-success">{{ $data->formatted_tenggat_baru }}</strong></td>
                        </tr>
                        <tr>
                          <td><strong>Durasi Tambahan</strong></td>
                          <td>: {{ $data->tenggat_baru->diffInDays($data->tenggat_lama) }} hari</td>
                        </tr>
                        <tr>
                          <td><strong>Alasan</strong></td>
                          <td>: {{ $data->alasan }}</td>
                        </tr>
                        <tr>
                          <td><strong>Sudah Perpanjang</strong></td>
                          <td>: {{ $data->peminjaman->jumlah_perpanjangan }}x</td>
                        </tr>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Modal Tolak --}}
              <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title">Tolak Perpanjangan</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('petugas.perpanjangan.reject', $data->id) }}" method="POST">
                      @csrf

                      <div class="modal-body">
                        <p class="fw-semibold">Apakah Anda yakin ingin menolak perpanjangan ini?</p>

                        <div class="alert alert-warning">
                          <strong>Peminjam:</strong> {{ $data->peminjaman->user->name }} <br>
                          <strong>Buku:</strong> {{ $data->peminjaman->buku->judul }} <br>
                          <strong>Tenggat Baru:</strong> {{ $data->formatted_tenggat_baru }}
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="alasan_tolak{{ $data->id }}">
                            Alasan Penolakan <span class="text-muted">(Opsional)</span>
                          </label>

                          <textarea class="form-control"
                                    id="alasan_tolak{{ $data->id }}"
                                    name="alasan_tolak"
                                    rows="3"
                                    placeholder="Contoh: Buku sudah dipesan orang lain, Sudah terlalu lama dipinjam, dll."></textarea>
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
                  Tidak ada perpanjangan pending
                </td>
              </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="d-flex justify-content-center mt-3">
        {{ $perpanjangan->links() }}
      </div>

    </div>
  </div>

</div>

@endsection