<!DOCTYPE html>
<html lang="en">
<head>
    <title>Detail Buku - E-Perpustakaan</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('assetsf/img/favicon.png')}}" rel="icon">
    <link href="{{ asset('assetsf/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assetsf/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assetsf/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('assetsf/vendor/aos/aos.css')}}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #0b1ae9;
            --secondary-color: #667eea;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-bg: #f5f6ff;
            --dark-text: #242859;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-text);
            background: #fff;
            overflow-x: hidden;
        }

        /* Main Content */
        .main {
            margin-top: 100px;
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px 0;
            margin-bottom: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
        }

        .breadcrumb-item.active {
            color: #fff;
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.85);
            content: "›";
            font-size: 18px;
        }

        .breadcrumb a {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb a:hover {
            opacity: 0.8;
        }

        /* Product Detail Section */
        .product-detail {
            padding: 60px 0;
            background: #fff;
        }

        /* Book Image Section */
        .book-image-wrapper {
            position: sticky;
            top: 120px;
            background: var(--light-bg);
            border-radius: 16px;
            padding: 40px;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-book-image {
            width: 100%;
            max-width: 350px;
            height: 500px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            transition: transform 0.5s ease;
        }

        .main-book-image:hover {
            transform: scale(1.03);
        }

        /* Product Info */
        .product-info-wrapper {
            padding-left: 30px;
        }

        .product-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .product-author {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-author i {
            color: var(--primary-color);
        }

        .product-author strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Product Meta Info Card */
        .meta-info-card {
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-sm);
        }

        .meta-info-card h5 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-info-card h5 i {
            color: var(--primary-color);
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .meta-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .meta-label {
            font-weight: 500;
            color: #6c757d;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .meta-label i {
            font-size: 16px;
            color: var(--secondary-color);
        }

        .meta-value {
            font-weight: 600;
            color: var(--dark-text);
            font-size: 14px;
        }

        /* Stock Badges */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-available {
            background: #d4edda;
            color: var(--success-color);
        }

        .stock-low {
            background: #fff3cd;
            color: #856404;
        }

        .stock-out {
            background: #f8d7da;
            color: var(--danger-color);
        }

        /* Description Section */
        .description-section {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .description-section h5 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .description-section h5 i {
            color: var(--primary-color);
        }

        .description-section p {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
            text-align: justify;
            margin: 0;
        }

        /* Action Buttons */
        .action-section {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
        }

        .btn-borrow {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: #fff;
            padding: 16px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(11, 26, 233, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-borrow:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(11, 26, 233, 0.4);
        }

        .btn-borrow:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-wishlist {
            width: 56px;
            height: 56px;
            background: #fff;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 10px;
            font-size: 22px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-wishlist:hover {
            background: var(--primary-color);
            color: #fff;
            transform: scale(1.08);
        }

        /* Share Section */
        .share-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 12px;
            border: 2px dashed var(--border-color);
        }

        .share-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--dark-text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .share-title i {
            color: var(--primary-color);
        }

        .share-buttons {
            display: flex;
            gap: 10px;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            background: #fff;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .share-btn.fb:hover {
            background: #1877f2;
            color: #fff;
            border-color: #1877f2;
        }

        .share-btn.tw:hover {
            background: #1da1f2;
            color: #fff;
            border-color: #1da1f2;
        }

        .share-btn.wa:hover {
            background: #25d366;
            color: #fff;
            border-color: #25d366;
        }

        .share-btn.em:hover {
            background: var(--danger-color);
            color: #fff;
            border-color: var(--danger-color);
        }

        /* Related Books Section */
        .related-books {
            background: var(--light-bg);
            padding: 60px 0;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 50px;
            color: var(--dark-text);
            position: relative;
            padding-bottom: 20px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        /* Related Book Card */
        .book-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .book-card-image {
            position: relative;
            overflow: hidden;
            background: var(--light-bg);
            height: 320px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .book-card-image img {
            width: 100%;
            max-width: 200px;
            height: 300px;
            object-fit: contain;
            transition: all 0.5s ease;
        }

        .book-card:hover .book-card-image img {
            transform: scale(1.05);
        }

        .quick-borrow-btn {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            box-shadow: 0 5px 20px rgba(11, 26, 233, 0.4);
            white-space: nowrap;
        }

        .book-card:hover .quick-borrow-btn {
            bottom: 15px;
            opacity: 1;
        }

        .quick-borrow-btn:hover {
            transform: translateX(-50%) translateY(-3px);
            box-shadow: 0 8px 30px rgba(11, 26, 233, 0.5);
        }

        .book-card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .book-card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            min-height: 44px;
        }

        .book-card-title a {
            color: var(--dark-text);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .book-card-title a:hover {
            color: var(--primary-color);
        }

        .book-card-author {
            color: #6c757d;
            font-size: 13px;
            font-style: italic;
            margin-bottom: 12px;
        }

        .book-card-stock {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .book-card-stock i {
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .main {
                margin-top: 80px;
            }

            .product-info-wrapper {
                padding-left: 0;
                margin-top: 30px;
            }

            .product-title {
                font-size: 28px;
            }

            .book-image-wrapper {
                position: relative;
                top: 0;
                padding: 30px;
            }

            .main-book-image {
                max-width: 300px;
                height: 420px;
            }
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 24px;
            }

            .section-title {
                font-size: 26px;
            }

            .action-section {
                flex-direction: column;
            }

            .btn-wishlist {
                width: 100%;
                height: 50px;
            }

            .book-image-wrapper {
                padding: 20px;
            }

            .main-book-image {
                max-width: 250px;
                height: 360px;
            }

            .book-card-image {
                height: 280px;
            }

            .book-card-image img {
                max-width: 180px;
                height: 260px;
            }
        }

        @media (max-width: 576px) {
            .breadcrumb-section {
                padding: 20px 0;
            }

            .product-detail {
                padding: 40px 0;
            }

            .share-buttons {
                justify-content: center;
            }
        }
        .rating-section { background: #f7f9fc; padding:20px; border-radius:12px; }
        .average-rating { text-align:center; margin-bottom:15px; }
        .average-rating .rating-number { font-size:40px; font-weight:bold; }
        .rating-stars i { color:#ffc107; font-size:22px; }
        .review-item { padding:10px 0; }
        .review-stars i { color:#ffc107; }
    </style>
</head>

<body>
    @extends('layouts.frontend')
    @section('content')

    <main class="main">
        {{-- Breadcrumb --}}
        <section class="breadcrumb-section">
            <div class="container">
                <nav aria-label="breadcrumb" data-aos="fade-up">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Books</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $buku->judul ?? 'Detail Buku' }}</li>
                    </ol>
                </nav>
            </div>
        </section>

        {{-- Detail Buku --}}
        <section class="product-detail">
            <div class="container">
                <div class="row">
                    {{-- Book Image --}}
                    <div class="col-lg-5" data-aos="fade-right">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('storage/buku/' . ($buku->foto ?? 'default.jpg')) }}" 
                                 alt="{{ $buku->judul ?? 'Book' }}" 
                                 class="main-book-image">
                        </div>
                    </div>

                    {{-- Book Info --}}
                    <div class="col-lg-7" data-aos="fade-left">
                        <div class="product-info-wrapper">
                            <h1 class="product-title">{{ $buku->judul ?? 'Judul Buku' }}</h1>
                            <p class="product-author">
                                <i class="bi bi-pen"></i>
                                Oleh <strong>{{ $buku->penulis ?? 'Penulis Tidak Diketahui' }}</strong>
                            </p>

                            {{-- Meta Information --}}
                            <div class="meta-info-card">
                                <h5><i class="bi bi-info-circle"></i> Informasi Buku</h5>
                                <div class="meta-row">
                                    <span class="meta-label"><i class="bi bi-folder"></i> Kategori</span>
                                    <span class="meta-value">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label"><i class="bi bi-building"></i> Penerbit</span>
                                    <span class="meta-value">{{ $buku->penerbit ?? '-' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label"><i class="bi bi-calendar"></i> Tahun Terbit</span>
                                    <span class="meta-value">{{ $buku->tahun_terbit ?? '-' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label"><i class="bi bi-upc"></i> ISBN</span>
                                    <span class="meta-value">{{ $buku->isbn ?? '-' }}</span>
                                </div>
                                <div class="meta-row">
                                    <span class="meta-label"><i class="bi bi-box-seam"></i> Ketersediaan</span>
                                    <span class="meta-value">
                                        @if($buku->stok > 10)
                                            <span class="stock-badge stock-available">
                                                <i class="bi bi-check-circle-fill"></i> Tersedia ({{ $buku->stok }})
                                            </span>
                                        @elseif($buku->stok > 0)
                                            <span class="stock-badge stock-low">
                                                <i class="bi bi-exclamation-circle-fill"></i> Terbatas ({{ $buku->stok }})
                                            </span>
                                        @else
                                            <span class="stock-badge stock-out">
                                                <i class="bi bi-x-circle-fill"></i> Habis
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="description-section">
                                <h5><i class="bi bi-book"></i> Deskripsi Buku</h5>
                                <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi tersedia untuk buku ini.' }}</p>
                                {{-- Rating Buku --}}
<div class="rating-section">
    <h5><i class="bi bi-star-fill"></i> Rating Buku</h5>

    {{-- Average Rating --}}
    <div class="average-rating">
        @php
            $avg = number_format($buku->ratings->avg('rating'), 1);
            $count = $buku->ratings->count();
        @endphp

        <h3 class="rating-number">{{ $avg }} <span>/ 5</span></h3>
        <div class="rating-stars">
            @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }}"></i>
            @endfor
        </div>
        <p class="rating-count">({{ $count }} ulasan)</p>
    </div>



    {{-- Daftar Review --}}
    <div class="review-list mt-4">
        <h6 class="mb-3">Ulasan Pembaca</h6>

        @forelse($buku->ratings as $rating)
            <div class="review-item">
                <div class="review-header">
                    <strong>{{ $rating->user->name }}</strong>
                    <span class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                </div>
                <p class="review-text">{{ $rating->review }}</p>
                <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                <hr>
            </div>
        @empty
            <p class="text-muted">Belum ada ulasan untuk buku ini.</p>
        @endforelse
    </div>
</div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="action-section">
                               <form action="{{ route('keranjang.tambah', $buku->id) }}" method="POST" style="flex: 1;">
                                    @csrf

                                    <button type="submit"
                                        class="btn-borrow"
                                        {{ $buku->stok <= 0 ? 'disabled' : '' }}>

                                        <i class="bi bi-bookmark-plus-fill"></i>
                                        {{ $buku->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                                    </button>
                                </form>

                                <button type="button" class="btn-wishlist" title="Tambah ke Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                            <div class="action-section mt-2">
                                <form action="{{ route('keranjang.tambah', $buku->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100 py-3 fw-bold" style="border-radius: 10px; border-width: 2px;">
                                        <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>

                            {{-- Share Section --}}
                            <div class="share-section">
                                <h6 class="share-title">
                                    <i class="bi bi-share"></i>
                                    Bagikan Buku Ini:
                                </h6>
                                <div class="share-buttons">
                                    <a href="#" class="share-btn fb" title="Share to Facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="share-btn tw" title="Share to Twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="#" class="share-btn wa" title="Share to WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="share-btn em" title="Share via Email">
                                        <i class="bi bi-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Related Books --}}
        <section class="related-books">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Buku Terkait</h2>
                <div class="row">
                    @foreach($relatedBooks ?? [] as $book)
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="book-card">
                            <div class="book-card-image">
                                <a href="{{ route('detail_buku', $book->id) }}">
                                    <img src="{{ asset('storage/buku/' . $book->foto) }}" alt="{{ $book->judul }}">
                                </a>
                                <button type="button" class="quick-borrow-btn" data-id="{{ $book->id }}">
                                    <i class="bi bi-bookmark-plus"></i> Pinjam
                                </button>
                            </div>
                            <div class="book-card-body">
                                <h3 class="book-card-title">
                                    <a href="{{ route('detail_buku', $book->id) }}">{{ $book->judul }}</a>
                                </h3>
                                <p class="book-card-author">{{ $book->penulis }}</p>
                                <div class="book-card-stock">
                                    <i class="bi bi-box-seam"></i>
                                    Stok: {{ $book->stok }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assetsf/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assetsf/vendor/aos/aos.js')}}"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>

    {{-- SweetAlert --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0b1ae9',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0b1ae9',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @endsection
</body>
</html>