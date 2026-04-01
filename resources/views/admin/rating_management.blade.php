@extends('layouts.backend')
@section('title', 'E-Perpus - Manajemen Rating & Ulasan')
@section('content')
<div class="col-md-12">
    <nav>
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-3">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary fw-semibold">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                Manajemen Rating & Ulasan
            </li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">
                <i class="bx bx-star me-2"></i>Manajemen Rating & Ulasan Buku
            </h5>
            <span class="badge bg-white text-primary fs-6">Total: {{ $bukuSudahDirating->count() + $bukuBelumDirating->count() }} Buku</span>
        </div>

        <div class="card-body">
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3" id="tab-sudah" data-bs-toggle="tab"
                            data-bs-target="#sudah-dirating" type="button" role="tab">
                        <i class="bx bx-check-circle me-2"></i>
                        Sudah Diberi Rating
                        <span class="badge bg-success ms-2">{{ $bukuSudahDirating->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3" id="tab-belum" data-bs-toggle="tab"
                            data-bs-target="#belum-dirating" type="button" role="tab">
                        <i class="bx bx-time me-2"></i>
                        Belum Diberi Rating
                        <span class="badge bg-warning ms-2">{{ $bukuBelumDirating->count() }}</span>
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content">
                {{-- Sudah Diberi Rating --}}
                <div class="tab-pane fade show active" id="sudah-dirating" role="tabpanel">
                    @if($bukuSudahDirating->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Buku</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Rating Rata-rata</th>
                                        <th class="text-center">Jumlah Rating</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($bukuSudahDirating as $book)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $book->foto ? asset('storage/buku/' . $book->foto) : asset('storage/buku/default-book.png') }}"
                                                     alt="{{ $book->judul }}" class="img-thumbnail" width="50" height="70" style="object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $book->judul }}</h6>
                                                    <small class="text-muted">{{ $book->penulis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $book->kategori->nama_kategori ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="d-flex gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bx bxs-star {{ $i <= round($book->avg_rating) ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="fw-bold">{{ round($book->avg_rating, 1) }}/5</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">{{ $book->total_rating }} Rating</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $book->id }}" title="Lihat Detail">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Detail Modal --}}
                                    <div class="modal fade" id="detailModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bx bx-star me-2"></i>Detail Rating - {{ $book->judul }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-4">
                                                        <h6 class="fw-bold mb-3">Ringkasan Rating</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p class="mb-2">
                                                                    <strong>Rating Rata-rata:</strong>
                                                                    <span class="badge bg-primary fs-6">{{ round($book->avg_rating, 1) }}/5</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    <strong>Total Rating:</strong>
                                                                    <span class="badge bg-success fs-6">{{ $book->total_rating }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 text-end">
                                                                <div class="d-flex gap-1 justify-content-end mb-2">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="bx bxs-star {{ $i <= round($book->avg_rating) ? 'text-warning' : 'text-muted' }}" style="font-size: 24px;"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <h6 class="fw-bold mb-3">Ulasan Pengguna</h6>
                                                    @if($book->ratings->count() > 0)
                                                        <div class="list-group">
                                                            @foreach($book->ratings as $rating)
                                                            <div class="list-group-item border-bottom pb-3 mb-3">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <div>
                                                                        <h6 class="mb-1 fw-bold">{{ $rating->user->name ?? 'User Tidak Ditemukan' }}</h6>
                                                                        <small class="text-muted">
                                                                            <i class="bx bx-calendar me-1"></i>{{ $rating->created_at->format('d-m-Y H:i') }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="d-flex gap-1">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="bx bxs-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                @if($rating->review)
                                                                    <p class="mb-0 text-muted">
                                                                        <em>"{{ $rating->review }}"</em>
                                                                    </p>
                                                                @else
                                                                    <p class="mb-0 text-muted">
                                                                        <em>Tidak ada ulasan</em>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info">
                                                            <i class="bx bx-info-circle me-2"></i>Tidak ada ulasan untuk buku ini
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5 rounded-3">
                            <i class="bx bx-info-circle" style="font-size: 50px; color: #0dcaf0;"></i>
                            <h5 class="mt-3 fw-bold">Tidak Ada Buku dengan Rating</h5>
                            <p class="text-muted">Belum ada buku yang mendapatkan rating dari pengguna.</p>
                        </div>
                    @endif
                </div>

                {{-- Belum Diberi Rating --}}
                <div class="tab-pane fade" id="belum-dirating" role="tabpanel">
                    @if($bukuBelumDirating->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Buku</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($bukuBelumDirating as $book)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $book->foto ? asset('storage/buku/' . $book->foto) : asset('storage/buku/default-book.png') }}"
                                                     alt="{{ $book->judul }}" class="img-thumbnail" width="50" height="70" style="object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $book->judul }}</h6>
                                                    <small class="text-muted">{{ $book->penulis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $book->kategori->nama_kategori ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-purple">{{ $book->stok }} Item</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                <i class="bx bx-time me-1"></i>Menunggu Rating
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success text-center py-5 rounded-3">
                            <i class="bx bx-check-circle" style="font-size: 50px; color: #198754;"></i>
                            <h5 class="mt-3 fw-bold">Semua Buku Sudah Mendapat Rating!</h5>
                            <p class="text-muted">Semua buku di perpustakaan sudah mendapatkan rating dari pengguna.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f5f6f7;
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
        padding: 0;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #dee2e6 !important;
    }

    .list-group-item:last-child {
        border-bottom: none !important;
    }
</style>
@endsection
