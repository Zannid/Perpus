@extends('layouts.backend')
@section('content')
<div class="col-md-12">
    <nav>
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
            <li class="breadcrumb-item">
                <a href="{{ route('peminjaman.index') }}" class="text-decoration-none text-primary fw-semibold">
                    Peminjaman
                </a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                Data Peminjaman
            </li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Peminjaman</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" id="tanggal_awal" class="form-control form-control-sm" placeholder="Tanggal Awal">
                    <input type="date" id="tanggal_akhir" class="form-control form-control-sm" placeholder="Tanggal Akhir">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari peminjaman...">
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="resetBtn">Reset</button>
                </div>

                <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="bx bx-plus me-1"></i> Tambah Peminjaman
                </a>
                <a href="{{ route('peminjaman.export', request()->query()) }}" target="_blank" 
                   class="btn btn-danger btn-sm rounded-pill px-3">
                    <i class="bx bx-file"></i> Buat PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="display table table-striped table-hover align-middle" id="peminjamanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Buku</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tenggat</th>
                            <th class="text-center">Status</th>
                            <th>Denda</th>
                            <th class="text-center">Rating</th>
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
                            <td class="nama">{{ $data->user->name ?? '-' }}</td>
                            <td class="buku">
                                @if($data->details->count() > 1)
                                    <span class="badge bg-primary">{{ $data->details->count() }} Buku</span>
                                    <button class="btn btn-sm btn-link p-0" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#bukuList{{ $data->id }}">
                                        Lihat Detail
                                    </button>
                                    <div class="collapse mt-2" id="bukuList{{ $data->id }}">
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($data->details as $detail)
                                                <li>• {{ optional($detail->buku)->judul ?? '-' }} ({{ $detail->jumlah }}x)</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif($data->details->count() == 1)
                                    {{ Str::limit(optional($data->details->first()->buku)->judul ?? '-', 30) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $data->details->sum('jumlah') }}</td>
                            <td class="tgl_pinjam">{{ $data->formatted_tanggal_pinjam ?? $data->tgl_pinjam }}</td>
                            <td class="tgl_kembali">{{ $data->formatted_tanggal_kembali ?? $data->tenggat }}</td>
                            <td class="text-center status">
                                @switch($data->status)
                                    @case('Kembali') <span class="badge bg-success">{{ $data->status }}</span> @break
                                    @case('Dipinjam') <span class="badge bg-warning text-dark">{{ $data->status }}</span> @break
                                    @case('Lunas') <span class="badge bg-info">{{ $data->status }}</span> @break
                                    @case('Pending') <span class="badge bg-secondary">{{ $data->status }}</span> @break
                                    @case('Ditolak') <span class="badge bg-danger">{{ $data->status }}</span> @break
                                    @default <span class="badge bg-secondary">{{ $data->status }}</span>
                                @endswitch
                            </td>
                            <td class="text-center denda">
                                @if($data->denda_berjalan > 0)
                                    <span class="text-danger fw-bold">
                                        Rp {{ number_format($data->denda_berjalan, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $nilai = $data->rating->rating ?? 0;
                                @endphp
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bx bxs-star" style="color: {{ $i <= $nilai ? '#ffc107' : '#ddd' }};"></i>
                                    @endfor
                                </div>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                                    <a href="{{ route('peminjaman.show', $data->id) }}" 
                                       class="btn btn-sm btn-info d-flex align-items-center justify-content-center" 
                                       title="Detail">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    
                                    @if($data->status == 'Pending')
                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                                            {{-- Approve Button (Trigger Modal) --}}
                                            <button type="button" class="btn btn-sm btn-success d-flex align-items-center justify-content-center" 
                                                    title="Approve" data-bs-toggle="modal" data-bs-target="#approveModal{{ $data->id }}">
                                                <i class="bx bx-check"></i>
                                            </button>

                                            {{-- Reject Button (trigger modal) --}}
                                            <button type="button" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" 
                                                    title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        @endif

                                        {{-- Edit Button (User can edit if still pending) --}}
                                        <a href="{{ route('peminjaman.edit', $data->id) }}" 
                                           class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                           title="Edit">
                                            <i class="bx bx-pencil"></i>
                                        </a>
                                    @endif

                                    @if($data->denda > 0 && $data->status != 'Lunas' && $data->status != 'Pending')
                                    <form action="{{ route('peminjaman.confirmPay', $data->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Bayar denda ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success d-flex align-items-center justify-content-center" title="Bayar Denda">
                                            <i class="bx bx-credit-card"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($data->status != 'Pending' && $data->status != 'Dipinjam')
                                    <form action="{{ route('peminjaman.destroy', $data->id) }}" method="post" class="d-inline m-0" onsubmit="return confirm('Hapus peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>

                            {{-- PERPANJANGAN --}}
                            <td class="text-center">
                                @if($data->status == 'Dipinjam' && $data->canExtend())
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#perpanjangModal{{ $data->id }}"
                                        title="Perpanjang">
                                    <i class="bx bx-time"></i>
                                </button>
                                @elseif($data->perpanjanganPending)
                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Menunggu Persetujuan">
                                    <i class="bx bx-hourglass"></i>
                                </button>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                                    @if($data->status == 'Dipinjam')
                                    <form action="{{ route('peminjaman.return', $data->id) }}" method="post" class="d-flex gap-1 justify-content-center">
                                        @csrf
                                        <select name="kondisi" class="form-select form-select-sm w-auto" required>
                                            <option value="">Kondisi</option>
                                            <option value="Bagus">Bagus</option>
                                            <option value="Rusak">Rusak</option>
                                            <option value="Hilang">Hilang</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Kembalikan buku ini?')" title="Kembalikan">
                                            <i class="bx bx-undo"></i>
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @else
                                    <span class="text-muted">Proses Petugas</span>
                                @endif
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
                               <div class="modal-body text-start">
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
                                 <button type="submit" class="btn btn-success px-4">
                                   <i class="bx bx-check me-1"></i>Setujui
                                 </button>
                               </div>
                             </form>
                           </div>
                         </div>
                       </div>

                       {{-- MODAL REJECT --}}
                        <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Tolak Peminjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('petugas.peminjaman.reject', $data->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Anda yakin ingin menolak peminjaman dari <strong>{{ $data->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan</label>
                                                <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
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

                        {{-- MODAL PERPANJANGAN --}}
                        <div class="modal fade" id="perpanjangModal{{ $data->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-dark">
                                        <h5 class="modal-title">
                                            <i class="bx bx-time me-2"></i>Ajukan Perpanjangan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('perpanjangan.store', $data->id) }}" method="POST">
                                        @csrf

                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Info Peminjaman:</strong><br>
                                                <i class="bx bx-book"></i> Jumlah Buku: {{ $data->details->count() }}<br>
                                                <i class="bx bx-calendar"></i> Tenggat: {{ $data->formatted_tanggal_kembali ?? $data->tenggat }}<br>
                                                <i class="bx bx-refresh"></i> Perpanjangan: {{ $data->jumlah_perpanjangan }}x
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="durasi{{ $data->id }}">
                                                    <i class="bx bx-calendar-plus"></i> Durasi Perpanjangan <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select durasi-select" 
                                                        id="durasi{{ $data->id }}" 
                                                        name="durasi" 
                                                        data-tenggat="{{ $data->tenggat }}"
                                                        data-id="{{ $data->id }}"
                                                        required>
                                                    <option value="">Pilih Durasi...</option>
                                                    <option value="3">3 Hari</option>
                                                    <option value="7">7 Hari (1 Minggu)</option>
                                                    <option value="14">14 Hari (2 Minggu)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 preview-tenggat" id="previewTenggat{{ $data->id }}" style="display:none;">
                                                <div class="alert alert-success">
                                                    <strong>Tenggat Baru:</strong> <span class="tenggat-baru"></span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="alasan{{ $data->id }}">
                                                    <i class="bx bx-message-square-detail"></i> Alasan <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control" id="alasan{{ $data->id }}" name="alasan" rows="3" 
                                                        placeholder="Jelaskan alasan perpanjangan..." 
                                                        required></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bx bx-send"></i> Ajukan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $peminjaman->appends(request()->all())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const tanggalAwal = document.getElementById('tanggal_awal');
    const tanggalAkhir = document.getElementById('tanggal_akhir');
    const resetBtn = document.getElementById('resetBtn');
    const tableRows = document.querySelectorAll('#peminjamanTable tbody tr');

    function filterTable() {
        const keyword = searchInput.value.toLowerCase();
        const startDate = tanggalAwal.value ? new Date(tanggalAwal.value) : null;
        const endDate = tanggalAkhir.value ? new Date(tanggalAkhir.value) : null;

        tableRows.forEach(row => {
            const nama = row.querySelector('.nama')?.textContent.toLowerCase() ?? '';
            const buku = row.querySelector('.buku')?.textContent.toLowerCase() ?? '';
            const status = row.querySelector('.status')?.textContent.toLowerCase() ?? '';
            const tglPinjam = row.querySelector('.tgl_pinjam')?.textContent ?? '';
            const tglPinjamDate = tglPinjam ? new Date(tglPinjam) : null;

            let matchKeyword = nama.includes(keyword) || buku.includes(keyword) || status.includes(keyword);
            let matchDate = true;
            if(startDate && tglPinjamDate) matchDate = tglPinjamDate >= startDate;
            if(endDate && tglPinjamDate) matchDate = matchDate && tglPinjamDate <= endDate;

            row.style.display = (matchKeyword && matchDate) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    tanggalAwal.addEventListener('change', filterTable);
    tanggalAkhir.addEventListener('change', filterTable);
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        tanggalAwal.value = '';
        tanggalAkhir.value = '';
        tableRows.forEach(row => row.style.display = '');
    });

    // Preview Tenggat
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.durasi-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const durasi = parseInt(this.value);
                const tenggatLama = new Date(this.dataset.tenggat);
                const dataId = this.dataset.id;
                const previewDiv = document.getElementById('previewTenggat' + dataId);
                const tenggatBaruSpan = previewDiv.querySelector('.tenggat-baru');
                
                if (durasi) {
                    const tenggatBaru = new Date(tenggatLama);
                    tenggatBaru.setDate(tenggatBaru.getDate() + durasi);
                    
                    const options = { day: '2-digit', month: 'long', year: 'numeric' };
                    const formatted = tenggatBaru.toLocaleDateString('id-ID', options);
                    
                    tenggatBaruSpan.textContent = formatted;
                    previewDiv.style.display = 'block';
                } else {
                    previewDiv.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection