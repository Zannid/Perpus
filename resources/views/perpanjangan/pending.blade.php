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

  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0">
      <i class="bx bx-time me-2"></i>Daftar Perpanjangan Pending
    </h5>

    {{-- Form Search --}}
    <form action="{{ route('petugas.perpanjangan.pending') }}" method="get" class="d-flex">
      <div class="input-group input-group-sm">
        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Cari perpanjangan..."
               value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">
          <i class="bx bx-search-alt"></i>
        </button>
      </div>
    </form>
  </div>

  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light text-nowrap">
          <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Buku</th>
            <th>Tenggat</th>
            <th>Alasan</th>
            <th>Tanggal</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($perpanjangan as $index => $data)
          <tr>
            {{-- AUTO NUMBER PAGINATION --}}
            <td>
              {{ $perpanjangan->firstItem() + $index }}
            </td>

            <td class="text-nowrap">
              {{ $data->peminjaman->user->name ?? '-' }}
            </td>

            <td style="max-width:200px">
              <div class="text-wrap fw-semibold">
                {{ $data->peminjaman->buku->judul ?? '-' }}
              </div>
            </td>

            <td class="text-nowrap">
              <div class="small text-muted">
                {{ $data->formatted_tenggat_lama }}
              </div>
              <span class="badge bg-success">
                {{ $data->formatted_tenggat_baru }}
              </span>
            </td>

            <td style="max-width:180px" class="text-wrap">
              {{ Str::limit($data->alasan, 60) }}
            </td>

            <td class="text-nowrap small">
              {{ $data->created_at->format('d M Y') }}<br>
              <span class="text-muted">{{ $data->created_at->format('H:i') }}</span>
            </td>

            <td class="text-center">
              <div class="d-flex justify-content-center gap-1 flex-wrap">

                {{-- DETAIL --}}
                <button class="btn btn-info btn-sm"
                        onclick="showDetail(
                          '{{ $data->peminjaman->user->name }}',
                          '{{ $data->peminjaman->buku->judul }}',
                          '{{ $data->formatted_tenggat_lama }}',
                          '{{ $data->formatted_tenggat_baru }}',
                          '{{ $data->alasan }}'
                        )">
                  <i class="bx bx-info-circle"></i>
                </button>

                {{-- APPROVE --}}
                <form action="{{ route('petugas.perpanjangan.approve', $data->id) }}"
                      method="POST">
                  @csrf
                  <button class="btn btn-success btn-sm"
                          onclick="return confirm('Setujui perpanjangan ini?')">
                    <i class="bx bx-check"></i>
                  </button>
                </form>

                {{-- REJECT --}}
                <button class="btn btn-danger btn-sm"
                        onclick="openRejectModal({{ $data->id }}, '{{ $data->peminjaman->user->name }}', '{{ $data->peminjaman->buku->judul }}')">
                  <i class="bx bx-x"></i>
                </button>

              </div>
            </td>
          </tr>

          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bx bx-info-circle fs-1 d-block mb-2"></i>
              Tidak ada perpanjangan pending
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
      {{ $perpanjangan->links() }}
    </div>

  </div>
</div>

</div>

<div class="modal fade" id="detailModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Perpanjangan</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><strong>Peminjam:</strong> <span id="dUser"></span></p>
        <p><strong>Buku:</strong> <span id="dBook"></span></p>
        <p><strong>Tenggat Lama:</strong> <span id="dOld"></span></p>
        <p><strong>Tenggat Baru:</strong> <span id="dNew"></span></p>
        <p><strong>Alasan:</strong> <span id="dReason"></span></p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rejectModal">
  <div class="modal-dialog">
    <form method="POST" id="rejectForm">
      @csrf

      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Tolak Perpanjangan</h5>
          <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p><strong id="rUser"></strong> - <span id="rBook"></span></p>

          <textarea name="alasan_tolak" class="form-control mt-2"
                    placeholder="Alasan penolakan (opsional)"></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-danger">Tolak</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const table = document.querySelector('.table')?.getElementsByTagName('tbody')[0];

if (searchInput && table) {
  // Set initial value from URL parameter
  const urlParams = new URLSearchParams(window.location.search);
  const searchParam = urlParams.get('search');
  if (searchParam) {
    searchInput.value = searchParam;
  }

  searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = table.getElementsByTagName('tr');

    Array.from(rows).forEach(row => {
      const peminjam = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
      const buku = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() ?? '';
      const tenggat = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() ?? '';
      const alasan = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() ?? '';

      if(peminjam.includes(filter) || buku.includes(filter) || tenggat.includes(filter) || alasan.includes(filter)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
}

function showDetail(user, book, oldDate, newDate, reason){
    document.getElementById('dUser').innerText = user;
    document.getElementById('dBook').innerText = book;
    document.getElementById('dOld').innerText = oldDate;
    document.getElementById('dNew').innerText = newDate;
    document.getElementById('dReason').innerText = reason;

    new bootstrap.Modal(document.getElementById('detailModal')).show();
}

function openRejectModal(id, user, book){
    document.getElementById('rUser').innerText = user;
    document.getElementById('rBook').innerText = book;

    document.getElementById('rejectForm').action = `/petugas/perpanjangan/${id}/reject`;

    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endsection
