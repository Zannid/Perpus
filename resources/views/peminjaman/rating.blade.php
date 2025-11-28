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
                Berikan Rating
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
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>Perhatian!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex align-items-center">
                <i class="bx bx-star fs-4 me-2"></i>
                <h5 class="mb-0 fw-bold">Berikan Rating & Ulasan</h5>
            </div>
        </div>
        <div class="card-body p-4">
            {{-- Book Information Section --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-5">
                    <div class="book-cover-wrapper position-relative">
                        @if($peminjaman->buku->gambar)
                            <img src="{{ asset('storage/buku/'.$peminjaman->buku->gambar) }}" 
                                 class="img-fluid rounded-3 shadow-lg w-100" 
                                 alt="{{ $peminjaman->buku->judul }}"
                                 style="object-fit: cover; max-height: 450px;">
                        @else
                            <div class="bg-light rounded-3 shadow d-flex align-items-center justify-content-center" 
                                 style="height: 450px;">
                                <div class="text-center">
                                    <i class="bx bx-book-open" style="font-size: 100px; color: #ddd;"></i>
                                    <p class="text-muted mt-3">Tidak ada cover</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Status Badge on Cover --}}
                        <div class="position-absolute top-0 end-0 m-3">
                            @if($peminjaman->kondisi == 'Bagus')
                                <span class="badge bg-success px-3 py-2 shadow">
                                    <i class="bx bx-check-circle me-1"></i>{{ $peminjaman->kondisi }}
                                </span>
                            @elseif($peminjaman->kondisi == 'Rusak')
                                <span class="badge bg-warning text-dark px-3 py-2 shadow">
                                    <i class="bx bx-error-circle me-1"></i>{{ $peminjaman->kondisi }}
                                </span>
                            @elseif($peminjaman->kondisi == 'Hilang')
                                <span class="badge bg-danger px-3 py-2 shadow">
                                    <i class="bx bx-x-circle me-1"></i>{{ $peminjaman->kondisi }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-7">
                    <div class="book-details">
                        <h3 class="fw-bold text-dark mb-3">{{ $peminjaman->buku->judul }}</h3>
                        
                        <div class="info-grid">
                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-user text-primary fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Penulis</small>
                                    <span class="fw-semibold">{{ $peminjaman->buku->penulis }}</span>
                                </div>
                            </div>

                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-buildings text-success fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Penerbit</small>
                                    <span class="fw-semibold">{{ $peminjaman->buku->penerbit }}</span>
                                </div>
                            </div>

                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-category text-info fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Kategori</small>
                                    <span class="fw-semibold">{{ $peminjaman->buku->kategori->nama_kategori ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-calendar text-warning fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Tanggal Pinjam</small>
                                    <span class="fw-semibold">{{ $peminjaman->formatted_tanggal_pinjam ?? $peminjaman->tgl_pinjam }}</span>
                                </div>
                            </div>

                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-calendar-check text-danger fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Tanggal Kembali</small>
                                    <span class="fw-semibold">{{ now()->format('d-m-Y') }}</span>
                                </div>
                            </div>

                            <div class="info-item d-flex align-items-start mb-3">
                                <div class="icon-wrapper bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bx bx-package text-secondary fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Jumlah Buku</small>
                                    <span class="fw-semibold">{{ $peminjaman->jumlah }} buku</span>
                                </div>
                            </div>
                        </div>

                        @if($peminjaman->denda > 0)
                            <div class="alert alert-danger mt-4 shadow-sm border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle fs-3 me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Total Denda</h6>
                                        <h4 class="mb-0 text-danger">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success mt-4 shadow-sm border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle fs-3 me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Tidak Ada Denda</h6>
                                        <small>Buku dikembalikan tepat waktu</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-4">

            {{-- Rating Form Section --}}
            <div class="rating-section">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-dark mb-2">Bagaimana pengalaman membaca Anda?</h4>
                    <p class="text-muted">Rating dan ulasan Anda sangat membantu pembaca lainnya</p>
                </div>

                <form action="{{ route('peminjaman.rating.store', $peminjaman->id) }}" method="POST">
                    @csrf
                    
                    {{-- Star Rating --}}
                    <div class="rating-input-wrapper bg-light rounded-3 p-4 mb-4 shadow-sm">
                        <label class="form-label fw-bold text-center d-block mb-3">
                            Berikan Rating <span class="text-danger">*</span>
                        </label>
                        
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="rating-stars mb-3">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5" class="star" data-value="5">
                                    <i class="bx bxs-star"></i>
                                </label>
                                
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" class="star" data-value="4">
                                    <i class="bx bxs-star"></i>
                                </label>
                                
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" class="star" data-value="3">
                                    <i class="bx bxs-star"></i>
                                </label>
                                
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" class="star" data-value="2">
                                    <i class="bx bxs-star"></i>
                                </label>
                                
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" class="star" data-value="1">
                                    <i class="bx bxs-star"></i>
                                </label>
                            </div>
                            
                            <div id="rating-text" class="badge bg-primary px-4 py-2 fs-6"></div>
                        </div>
                    </div>

                    {{-- Review Text Area --}}
                    <div class="review-input-wrapper mb-4">
                        <label for="ulasan" class="form-label fw-bold">
                            <i class="bx bx-message-square-dots me-2"></i>Tulis Ulasan (Opsional)
                        </label>
                        <textarea name="ulasan" 
                                  id="ulasan" 
                                  rows="6" 
                                  class="form-control shadow-sm" 
                                  placeholder="Bagikan pengalaman Anda membaca buku ini... Apa yang Anda sukai? Apakah buku ini sesuai harapan? Apakah Anda merekomendasikannya?"
                                  maxlength="500"></textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                <i class="bx bx-info-circle me-1"></i>Maksimal 500 karakter
                            </small>
                            <small class="text-muted" id="char-count">0 / 500</small>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bx bx-send me-2"></i>Kirim Rating & Ulasan
                        </button>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="bx bx-skip-next me-2"></i>Lewati
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gradient Header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Rating Stars Styling */
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 8px;
    }
    
    .rating-stars input[type="radio"] {
        display: none;
    }
    
    .rating-stars .star {
        cursor: pointer;
        color: #ddd;
        transition: all 0.3s ease;
        font-size: 3rem;
        margin: 0;
        padding: 0;
    }
    
    .rating-stars .star:hover,
    .rating-stars .star:hover ~ .star,
    .rating-stars input[type="radio"]:checked ~ .star {
        color: #ffc107;
        transform: scale(1.1);
    }
    
    .rating-stars .star i {
        pointer-events: none;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    /* Info Grid Responsive */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Book Cover Animation */
    .book-cover-wrapper img {
        transition: transform 0.3s ease;
    }

    .book-cover-wrapper:hover img {
        transform: scale(1.02);
    }

    /* Textarea Focus Effect */
    #ulasan:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    /* Button Hover Effects */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }

    /* Card Shadow */
    .card {
        transition: transform 0.3s ease;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .rating-stars .star {
            font-size: 2.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }

        .book-cover-wrapper img,
        .book-cover-wrapper > div {
            max-height: 350px !important;
        }

        .btn-lg {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .rating-stars .star {
            font-size: 2rem;
        }
        
        .icon-wrapper {
            width: 35px;
            height: 35px;
        }
    }

    /* Alert Animations */
    .alert {
        animation: slideInDown 0.5s ease;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Star Rating Functionality
    const stars = document.querySelectorAll('.star');
    const ratingText = document.getElementById('rating-text');
    const ratingLabels = {
        1: '⭐ Sangat Buruk',
        2: '⭐⭐ Buruk',
        3: '⭐⭐⭐ Cukup',
        4: '⭐⭐⭐⭐ Bagus',
        5: '⭐⭐⭐⭐⭐ Sangat Bagus'
    };

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            document.getElementById('star' + value).checked = true;
            updateRating(value);
        });

        star.addEventListener('mouseenter', function() {
            const value = this.dataset.value;
            highlightStars(value);
        });
    });

    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        const checkedStar = document.querySelector('input[name="rating"]:checked');
        if (checkedStar) {
            updateRating(checkedStar.value);
        } else {
            resetStars();
        }
    });

    function highlightStars(value) {
        stars.forEach(star => {
            if (star.dataset.value <= value) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
        ratingText.textContent = ratingLabels[value];
        ratingText.style.display = 'inline-block';
    }

    function updateRating(value) {
        highlightStars(value);
    }

    function resetStars() {
        stars.forEach(star => {
            star.style.color = '#ddd';
        });
        ratingText.textContent = '';
        ratingText.style.display = 'none';
    }

    // Character Counter for Textarea
    const textarea = document.getElementById('ulasan');
    const charCount = document.getElementById('char-count');

    textarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length} / 500`;
        
        if (length > 450) {
            charCount.classList.add('text-danger');
            charCount.classList.remove('text-muted');
        } else {
            charCount.classList.add('text-muted');
            charCount.classList.remove('text-danger');
        }
    });

    // Form Validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const ratingChecked = document.querySelector('input[name="rating"]:checked');
        
        if (!ratingChecked) {
            e.preventDefault();
            alert('Silakan pilih rating terlebih dahulu!');
            document.querySelector('.rating-stars').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-dismissible')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
</script>
@endsection