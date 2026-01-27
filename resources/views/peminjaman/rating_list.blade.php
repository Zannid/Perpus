@extends('layouts.backend')
@section('title', 'E-Perpus - Berikan Rating & Ulasan')
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
                Rating & Ulasan
            </li>
        </ol>
    </nav>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3" id="tab-belum" data-bs-toggle="tab"
                            data-bs-target="#belum-dirating" type="button" role="tab">
                        <i class="bx bx-star me-2"></i>
                        Belum Diberi Rating
                        <span class="badge bg-danger ms-2">{{ count($detailBelumDirating) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3" id="tab-sudah" data-bs-toggle="tab"
                            data-bs-target="#sudah-dirating" type="button" role="tab">
                        <i class="bx bx-check-circle me-2"></i>
                        Sudah Diberi Rating
                        <span class="badge bg-success ms-2">{{ count($detailSudahDirating) }}</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="tab-content">
        {{-- Belum Diberi Rating --}}
        <div class="tab-pane fade show active" id="belum-dirating" role="tabpanel">
            @if(count($detailBelumDirating) > 0)
                <div class="row g-4">
                    @foreach($detailBelumDirating as $detail)
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 h-100 hover-lift">
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        {{-- Book Cover --}}
                                        <div class="col-md-4">
                                            <div class="book-cover-wrapper">
                                                @if($detail->buku->foto)
                                                    <img src="{{ asset('storage/buku/' . $detail->buku->foto) }}"
                                                         class="img-fluid rounded-2 shadow-sm w-100"
                                                         alt="{{ $detail->buku->judul }}"
                                                         style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                                                         style="height: 250px;">
                                                        <i class="bx bx-book-open" style="font-size: 60px; color: #ddd;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Book Info --}}
                                        <div class="col-md-8">
                                            <h5 class="fw-bold text-dark mb-2">{{ $detail->buku->judul }}</h5>
                                            <p class="text-muted small mb-2">
                                                <i class="bx bx-user me-1"></i>{{ $detail->buku->penulis }}
                                            </p>
                                            <p class="text-muted small mb-3">
                                                <i class="bx bx-buildings me-1"></i>{{ $detail->buku->penerbit }}
                                            </p>

                                            <div class="info-table small mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Tanggal Pinjam:</span>
                                                    <span class="fw-semibold">{{ $detail->peminjaman->tgl_pinjam ? $detail->peminjaman->tgl_pinjam->format('d-m-Y') : '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Tenggat Pengembalian:</span>
                                                    <span class="fw-semibold">{{ $detail->peminjaman->tenggat ? $detail->peminjaman->tenggat->format('d-m-Y') : '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Kondisi:</span>
                                                    @if($detail->peminjaman->kondisi == 'Bagus')
                                                        <span class="badge bg-success">{{ $detail->peminjaman->kondisi }}</span>
                                                    @elseif($detail->peminjaman->kondisi == 'Rusak')
                                                        <span class="badge bg-warning text-dark">{{ $detail->peminjaman->kondisi }}</span>
                                                    @elseif($detail->peminjaman->kondisi == 'Hilang')
                                                        <span class="badge bg-danger">{{ $detail->peminjaman->kondisi }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <button type="button"
                                                    class="btn btn-primary btn-sm w-100 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ratingModal{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                <i class="bx bx-star me-1"></i>Beri Rating
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Rating --}}
                            <div class="modal fade" id="ratingModal{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold">Rating - {{ $detail->buku->judul }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            {{-- Error Display --}}
                                            @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                                    <i class="bx bx-error-circle me-2"></i>
                                                    <strong>Kesalahan Validasi:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            <form action="{{ route('rating.store') }}" method="POST" class="rating-form">
                                                @csrf
                                                <input type="hidden" name="peminjaman_id" value="{{ $detail->peminjaman->id }}">
                                                <input type="hidden" name="buku_id" value="{{ $detail->buku->id }}">

                                                {{-- Star Rating --}}
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold d-block mb-3">
                                                        Berikan Rating <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="text-center">
                                                        <div class="rating-stars" data-modal-id="{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <input type="radio" name="rating" value="5" id="star5-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <label for="star5-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="5">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="4" id="star4-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <label for="star4-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="4">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="3" id="star3-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <label for="star3-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="3">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="2" id="star2-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <label for="star2-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="2">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="1" id="star1-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <label for="star1-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="1">
                                                                <i class="bx bxs-star"></i>
                                                            </label>
                                                        </div>
                                                        <div id="rating-text-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="badge bg-primary px-4 py-2 fs-6 mt-3"></div>
                                                    </div>
                                                </div>

                                                {{-- Review Text Area --}}
                                                <div class="mb-4">
                                                    <label for="ulasan-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="form-label fw-bold">
                                                        <i class="bx bx-message-square-dots me-2"></i>Tulis Ulasan (Opsional)
                                                    </label>
                                                    <textarea name="ulasan"
                                                              id="ulasan-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}"
                                                              rows="4"
                                                              class="form-control shadow-sm"
                                                              placeholder="Bagikan pengalaman Anda membaca buku ini..."
                                                              maxlength="500"></textarea>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">Maksimal 500 karakter</small>
                                                        <small class="text-muted char-count" data-modal-id="{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">0 / 500</small>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                                        <i class="bx bx-send me-1"></i>Kirim Rating
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-5 shadow-sm rounded-3 border-0">
                    <i class="bx bx-check-circle" style="font-size: 50px;"></i>
                    <h5 class="mt-3 fw-bold">Semua Buku Sudah Diberi Rating</h5>
                    <p class="text-muted">Bagus sekali! Anda sudah memberikan rating untuk semua peminjaman.</p>
                </div>
            @endif
        </div>

        {{-- Sudah Diberi Rating --}}
        <div class="tab-pane fade" id="sudah-dirating" role="tabpanel">
            @if(count($detailSudahDirating) > 0)
                <div class="row g-4">
                    @foreach($detailSudahDirating as $detail)
                        @php $rating = $detail->existingRating @endphp
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 h-100 hover-lift">
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        {{-- Book Cover --}}
                                        <div class="col-md-4">
                                            <div class="book-cover-wrapper">
                                                @if($detail->buku->foto)
                                                    <img src="{{ asset('storage/buku/' . $detail->buku->foto) }}"
                                                         class="img-fluid rounded-2 shadow-sm w-100"
                                                         alt="{{ $detail->buku->judul }}"
                                                         style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center"
                                                         style="height: 250px;">
                                                        <i class="bx bx-book-open" style="font-size: 60px; color: #ddd;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Book Info & Rating --}}
                                        <div class="col-md-8">
                                            <h5 class="fw-bold text-dark mb-2">{{ $detail->buku->judul }}</h5>
                                            <p class="text-muted small mb-2">
                                                <i class="bx bx-user me-1"></i>{{ $detail->buku->penulis }}
                                            </p>

                                            {{-- Display Stars --}}
                                            <div class="mb-3">
                                                <div class="d-flex gap-1 mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bx bxs-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">
                                                    Diberikan pada {{ $rating->created_at ? $rating->created_at->format('d-m-Y') : '-' }}
                                                </small>
                                            </div>

                                            {{-- Review --}}
                                            @if($rating->review)
                                                <p class="small text-muted mb-3" style="font-style: italic;">
                                                    "{{ $rating->review }}"
                                                </p>
                                            @else
                                                <p class="small text-muted mb-3 italic">Tidak ada ulasan</p>
                                            @endif

                                            <div class="d-flex gap-2">
                                                <button type="button"
                                                        class="btn btn-outline-primary btn-sm flex-grow-1 fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRatingModal{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                    <i class="bx bx-edit me-1"></i>Edit Rating
                                                </button>
                                                <form action="{{ route('rating.destroy', $rating->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus rating ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Edit Rating Modal --}}
                            <div class="modal fade" id="editRatingModal{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold">Edit Rating - {{ $detail->buku->judul }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('rating.update', $rating->id) }}" method="POST" class="rating-form">
                                                @csrf
                                                @method('PUT')

                                                {{-- Star Rating --}}
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold d-block mb-3">
                                                        Perbarui Rating <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="text-center">
                                                        <div class="rating-stars" data-modal-id="edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">
                                                            <input type="radio" name="rating" value="5" id="star5-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" {{ $rating->rating == 5 ? 'checked' : '' }}>
                                                            <label for="star5-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="5">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="4" id="star4-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" {{ $rating->rating == 4 ? 'checked' : '' }}>
                                                            <label for="star4-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="4">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="3" id="star3-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" {{ $rating->rating == 3 ? 'checked' : '' }}>
                                                            <label for="star3-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="3">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="2" id="star2-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" {{ $rating->rating == 2 ? 'checked' : '' }}>
                                                            <label for="star2-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="2">
                                                                <i class="bx bxs-star"></i>
                                                            </label>

                                                            <input type="radio" name="rating" value="1" id="star1-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" {{ $rating->rating == 1 ? 'checked' : '' }}>
                                                            <label for="star1-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="star" data-value="1">
                                                                <i class="bx bxs-star"></i>
                                                            </label>
                                                        </div>
                                                        <div id="rating-text-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="badge bg-primary px-4 py-2 fs-6 mt-3"></div>
                                                    </div>
                                                </div>

                                                {{-- Review Text Area --}}
                                                <div class="mb-4">
                                                    <label for="ulasan-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}" class="form-label fw-bold">
                                                        <i class="bx bx-message-square-dots me-2"></i>Perbarui Ulasan (Opsional)
                                                    </label>
                                                    <textarea name="ulasan"
                                                              id="ulasan-edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}"
                                                              rows="4"
                                                              class="form-control shadow-sm"
                                                              placeholder="Perbarui ulasan Anda..."
                                                              maxlength="500">{{ $rating->review }}</textarea>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <small class="text-muted">Maksimal 500 karakter</small>
                                                        <small class="text-muted char-count-edit" data-modal-id="edit-{{ $detail->peminjaman->id }}-{{ $detail->buku->id }}">{{ strlen($rating->review) }} / 500</small>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                                        <i class="bx bx-save me-1"></i>Simpan Perubahan
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center py-5 shadow-sm rounded-3 border-0">
                    <i class="bx bx-empty" style="font-size: 50px;"></i>
                    <h5 class="mt-3 fw-bold">Tidak Ada Rating</h5>
                    <p class="text-muted">Belum ada peminjaman yang diberi rating.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-4">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bx bx-arrow-back me-2"></i>Kembali ke Daftar Peminjaman
        </a>
    </div>
</div>

<style>
    /* Hover effect untuk card */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    /* Rating Stars Styling */
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 12px;
    }

    .rating-stars input[type="radio"] {
        display: none;
    }

    .rating-stars .star {
        cursor: pointer;
        color: #ddd;
        transition: all 0.3s ease;
        font-size: 2.5rem;
        margin: 0;
        padding: 0;
    }

    .rating-stars .star:hover,
    .rating-stars .star:hover ~ .star,
    .rating-stars input[type="radio"]:checked ~ .star {
        color: #ffc107;
        transform: scale(1.15);
    }

    .rating-stars .star i {
        pointer-events: none;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    /* Modal styling */
    .modal-content {
        border: none;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .modal-header {
        background-color: #fff;
        border-bottom: 2px solid #f0f0f0 !important;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem !important;
    }

    /* Tab styling */
    .nav-tabs {
        border-bottom: 2px solid #f0f0f0;
    }

    .nav-link {
        color: #6c757d;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: #495057;
    }

    .nav-link.active {
        color: #0b63e5;
        border-bottom-color: #0b63e5;
        background-color: transparent;
    }

    /* Badge styling */
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
        font-weight: 600;
    }

    /* Italic text */
    .italic {
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .rating-stars .star {
            font-size: 2rem;
        }

        .col-md-4,
        .col-md-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<script>
    // Star Rating Functionality
    const ratingLabels = {
        1: '⭐ Sangat Buruk',
        2: '⭐⭐ Buruk',
        3: '⭐⭐⭐ Cukup',
        4: '⭐⭐⭐⭐ Bagus',
        5: '⭐⭐⭐⭐⭐ Sangat Bagus'
    };

    // Handle star rating
    document.querySelectorAll('.rating-stars').forEach(ratingStarsGroup => {
        const modalId = ratingStarsGroup.getAttribute('data-modal-id');
        const stars = ratingStarsGroup.querySelectorAll('.star');
        const ratingText = document.getElementById(`rating-text-${modalId}`);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                document.getElementById(`star${value}-${modalId}`).checked = true;
                updateRating(value, modalId);
            });

            star.addEventListener('mouseenter', function() {
                const value = this.dataset.value;
                highlightStars(value, ratingStarsGroup);
            });
        });

        ratingStarsGroup.addEventListener('mouseleave', function() {
            const checkedStar = ratingStarsGroup.querySelector('input[type="radio"]:checked');
            if (checkedStar) {
                updateRating(checkedStar.value, modalId);
            } else {
                resetStars(ratingStarsGroup);
            }
        });
    });

    function highlightStars(value, ratingStarsGroup) {
        ratingStarsGroup.querySelectorAll('.star').forEach(star => {
            if (star.dataset.value <= value) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
        const modalId = ratingStarsGroup.getAttribute('data-modal-id');
        const ratingText = document.getElementById(`rating-text-${modalId}`);
        if (ratingText) {
            ratingText.textContent = ratingLabels[value];
            ratingText.style.display = 'inline-block';
        }
    }

    function updateRating(value, modalId) {
        const ratingStarsGroup = document.querySelector(`[data-modal-id="${modalId}"]`);
        highlightStars(value, ratingStarsGroup);
    }

    function resetStars(ratingStarsGroup) {
        ratingStarsGroup.querySelectorAll('.star').forEach(star => {
            star.style.color = '#ddd';
        });
        const modalId = ratingStarsGroup.getAttribute('data-modal-id');
        const ratingText = document.getElementById(`rating-text-${modalId}`);
        if (ratingText) {
            ratingText.textContent = '';
            ratingText.style.display = 'none';
        }
    }

    // Character Counter for Textarea
    document.querySelectorAll('textarea[name="ulasan"]').forEach(textarea => {
        const textareaId = textarea.id;
        const modalId = textareaId.replace('ulasan-', '');
        const charCounter = document.querySelector(`[data-modal-id="${modalId}"]`)?.parentElement?.parentElement?.querySelector('.char-count');

        if (charCounter) {
            textarea.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length} / 500`;

                if (length > 450) {
                    charCounter.classList.add('text-danger');
                    charCounter.classList.remove('text-muted');
                } else {
                    charCounter.classList.add('text-muted');
                    charCounter.classList.remove('text-danger');
                }
            });
        }
    });

    // Character Counter for Edit Textarea
    document.querySelectorAll('textarea[name="ulasan"]').forEach(textarea => {
        const updateCounter = () => {
            const textareaId = textarea.id;
            let charCounter;

            if (textareaId.includes('edit')) {
                const modalId = textareaId.replace('ulasan-edit-', 'edit-');
                charCounter = document.querySelector(`[data-modal-id="${modalId}"].char-count-edit`);
            }

            if (charCounter) {
                const length = textarea.value.length;
                charCounter.textContent = `${length} / 500`;

                if (length > 450) {
                    charCounter.classList.add('text-danger');
                    charCounter.classList.remove('text-muted');
                } else {
                    charCounter.classList.add('text-muted');
                    charCounter.classList.remove('text-danger');
                }
            }
        };

        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initialize on page load
    });

    // Form Validation
    document.querySelectorAll('.rating-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const modalId = this.querySelector('[name="peminjaman_id"]')?.closest('.rating-stars')?.parentElement?.querySelector('[data-modal-id]')?.getAttribute('data-modal-id') ||
                           this.querySelector('.rating-stars')?.getAttribute('data-modal-id');

            // Cek apakah rating sudah dipilih
            const ratingChecked = this.querySelector('input[name="rating"]:checked');

            if (!ratingChecked) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Rating Belum Dipilih',
                    text: 'Silakan pilih rating bintang terlebih dahulu sebelum mengirim.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@endsection
