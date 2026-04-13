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
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
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

                        {{-- ============================================================
                             ACTION BUTTONS — di atas deskripsi & rating
                             ============================================================ --}}
                        <div class="action-section">
                            @auth
                                <form class="add-to-cart-form" data-buku-id="{{ $buku->id }}" style="flex:1;">
                                    @csrf
                                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-borrow" {{ $buku->stok <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus"></i>
                                        Tambah Keranjang
                                    </button>
                                </form>
                                <form class="direct-borrow-form" data-buku-id="{{ $buku->id }}" action="{{ route('peminjaman.storeAuto') }}" method="POST" style="flex:1;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $buku->id }}">
                                    <button type="submit" class="btn-borrow btn-borrow-green" {{ $buku->stok <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-check-circle"></i>
                                        Pinjam Langsung
                                    </button>
                                </form>
                                <button type="button" class="btn-wishlist" title="Tambah ke Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-borrow" style="flex:1; text-decoration:none;">
                                    <i class="bi bi-cart-plus"></i> Tambah Keranjang
                                </a>
                                <a href="{{ route('login') }}" class="btn-borrow btn-borrow-green" style="flex:1; text-decoration:none;">
                                    <i class="bi bi-check-circle"></i> Pinjam Langsung
                                </a>
                                <button type="button" class="btn-wishlist" title="Tambah ke Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                            @endauth
                        </div>

                        {{-- Share Section --}}
                        <div class="share-section">
                            <h6 class="share-title">
                                <i class="bi bi-share"></i> Bagikan Buku Ini:
                            </h6>
                            <div class="share-buttons">
                                <a href="#" class="share-btn fb" title="Share to Facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="share-btn tw" title="Share to Twitter"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="share-btn wa" title="Share to WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                <a href="#" class="share-btn em" title="Share via Email"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="description-section">
                            <h5><i class="bi bi-book"></i> Deskripsi Buku</h5>
                            <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi tersedia untuk buku ini.' }}</p>
                        </div>

                        {{-- Rating & Ulasan --}}
                        <div class="rating-section">
                            <h5><i class="bi bi-star-fill"></i> Rating & Ulasan</h5>

                            {{-- Average Rating --}}
                            @php
                                $avg   = number_format($buku->ratings->avg('rating'), 1);
                                $count = $buku->ratings->count();
                                $preview = $buku->ratings->take(5);
                            @endphp
                            <div class="average-rating">
                                <div class="rating-big-number">{{ $avg }}</div>
                                <div class="rating-stars-row">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="rating-count">{{ $count }} ulasan</p>
                            </div>

                            {{-- Preview 5 Ulasan --}}
                            <div class="review-list">
                                <h6 class="review-list-title">Ulasan Pembaca</h6>

                                @forelse($preview as $rating)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-avatar">
                                                {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                            </div>
                                            <div class="reviewer-info">
                                                <strong>{{ $rating->user->name }}</strong>
                                                <div class="review-stars-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="review-time text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="review-text">{{ $rating->review }}</p>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-3">Belum ada ulasan untuk buku ini.</p>
                                @endforelse

                                @if($count > 5)
                                    <a href="{{ route('buku.ulasan', $buku->id) }}" class="btn-see-all-reviews">
                                        <i class="bi bi-chat-square-text"></i>
                                        Lihat Semua {{ $count }} Ulasan
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                @endif
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
            <div class="row g-3">
                @foreach($relatedBooks ?? [] as $book)
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="book-card">
                        <div class="book-card-image">
                            <a href="{{ route('detail_buku', $book->id) }}">
                                <img src="{{ $book->foto ? asset('storage/buku/' . $book->foto) : asset('storage/buku/default-book.png') }}"
                                     alt="{{ $book->judul }}">
                            </a>
                            @auth
                                <button type="button" class="quick-borrow-btn" data-id="{{ $book->id }}">
                                    <i class="bi bi-bookmark-plus"></i> Pinjam
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="quick-borrow-btn">
                                    <i class="bi bi-bookmark-plus"></i> Pinjam
                                </a>
                            @endauth
                        </div>
                        <div class="book-card-body">
                            <h3 class="book-card-title">
                                <a href="{{ route('detail_buku', $book->id) }}">{{ $book->judul }}</a>
                            </h3>
                            <p class="book-card-author">{{ $book->penulis }}</p>
                            <div class="book-card-stock">
                                <i class="bi bi-box-seam"></i> Stok: {{ $book->stok }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</main>

<style>
:root {
    --primary: #0b1ae9;
    --primary-dark: #0815b8;
    --secondary: #667eea;
    --success: #16a34a;
    --danger: #dc2626;
    --warning: #d97706;
    --light-bg: #f5f6ff;
    --dark-text: #1e2459;
    --muted: #6b7280;
    --border: #e5e7eb;
    --shadow-sm: 0 1px 8px rgba(0,0,0,0.07);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.10);
    --shadow-lg: 0 10px 40px rgba(0,0,0,0.13);
    --radius: 12px;
}

.main { margin-top: 90px; }

/* ── Breadcrumb ── */
.breadcrumb-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 22px 0;
}
.breadcrumb { background: transparent; padding: 0; margin: 0; }
.breadcrumb-item { color: rgba(255,255,255,0.85); font-size: 14px; }
.breadcrumb-item.active { color: #fff; font-weight: 600; }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.7); content: "›"; font-size: 18px; }
.breadcrumb a { color: #fff; text-decoration: none; }
.breadcrumb a:hover { opacity: 0.8; }

/* ── Product Detail ── */
.product-detail { padding: 50px 0 60px; background: #fff; }

/* ── Book Image ── */
.book-image-wrapper {
    position: sticky;
    top: 110px;
    background: var(--light-bg);
    border-radius: var(--radius);
    padding: 36px;
    box-shadow: var(--shadow-md);
    display: flex;
    justify-content: center;
    align-items: center;
}
.main-book-image {
    width: 100%;
    max-width: 320px;
    height: 460px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: var(--shadow-lg);
    transition: transform 0.4s ease;
}
.main-book-image:hover { transform: scale(1.03); }

/* ── Product Info ── */
.product-info-wrapper { padding-left: 20px; }
.product-title { font-size: 30px; font-weight: 700; color: var(--dark-text); margin-bottom: 10px; line-height: 1.3; }
.product-author { font-size: 16px; color: var(--muted); margin-bottom: 20px; display: flex; align-items: center; gap: 7px; }
.product-author i { color: var(--primary); }
.product-author strong { color: var(--primary); }

/* ── Meta Card ── */
.meta-info-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}
.meta-info-card h5 { font-size: 16px; font-weight: 700; color: var(--dark-text); margin-bottom: 14px; display: flex; align-items: center; gap: 7px; }
.meta-info-card h5 i { color: var(--primary); }
.meta-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 6px; }
.meta-row:last-child { border-bottom: none; padding-bottom: 0; }
.meta-label { font-weight: 500; color: var(--muted); font-size: 13px; display: flex; align-items: center; gap: 5px; }
.meta-label i { color: var(--secondary); font-size: 15px; }
.meta-value { font-weight: 600; color: var(--dark-text); font-size: 13px; text-align: right; }
.stock-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.stock-available { background: #dcfce7; color: #15803d; }
.stock-low { background: #fef9c3; color: #92400e; }
.stock-out { background: #fee2e2; color: #b91c1c; }

/* ── Action Buttons ── */
.action-section { display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
.btn-borrow {
    flex: 1;
    min-width: 140px;
    background: linear-gradient(135deg, var(--primary), #0056b3);
    color: #fff;
    padding: 14px 18px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(11,26,233,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-align: center;
}
.btn-borrow:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 7px 25px rgba(11,26,233,0.35); color: #fff; }
.btn-borrow:disabled { background: #d1d5db; cursor: not-allowed; box-shadow: none; }
.btn-borrow-green {
    background: linear-gradient(135deg, var(--success), #14532d) !important;
    box-shadow: 0 4px 15px rgba(22,163,74,0.25) !important;
}
.btn-borrow-green:hover:not(:disabled) { box-shadow: 0 7px 25px rgba(22,163,74,0.35) !important; }
.btn-wishlist {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    background: #fff;
    border: 2px solid var(--primary);
    color: var(--primary);
    border-radius: 10px;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-wishlist:hover { background: var(--primary); color: #fff; transform: scale(1.06); }

/* ── Share ── */
.share-section { background: #f8f9fa; padding: 16px 18px; border-radius: var(--radius); border: 1.5px dashed var(--border); margin-bottom: 20px; }
.share-title { font-size: 14px; font-weight: 600; margin-bottom: 10px; color: var(--dark-text); display: flex; align-items: center; gap: 7px; }
.share-title i { color: var(--primary); }
.share-buttons { display: flex; gap: 8px; }
.share-btn { width: 38px; height: 38px; background: #fff; border: 1.5px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--muted); text-decoration: none; font-size: 15px; transition: all 0.3s ease; }
.share-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }
.share-btn.fb:hover { background: #1877f2; color: #fff; border-color: #1877f2; }
.share-btn.tw:hover { background: #1da1f2; color: #fff; border-color: #1da1f2; }
.share-btn.wa:hover { background: #25d366; color: #fff; border-color: #25d366; }
.share-btn.em:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

/* ── Description ── */
.description-section { background: var(--light-bg); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; }
.description-section h5 { font-size: 16px; font-weight: 700; color: var(--dark-text); margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
.description-section h5 i { color: var(--primary); }
.description-section p { font-size: 14px; line-height: 1.8; color: #555; text-align: justify; margin: 0; }

/* ── Rating & Ulasan ── */
.rating-section { background: #fff; border: 1.5px solid var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; }
.rating-section h5 { font-size: 16px; font-weight: 700; color: var(--dark-text); margin-bottom: 16px; display: flex; align-items: center; gap: 7px; }
.rating-section h5 i { color: #f59e0b; }
.average-rating { display: flex; flex-direction: column; align-items: center; margin-bottom: 18px; padding-bottom: 18px; border-bottom: 1px solid var(--border); }
.rating-big-number { font-size: 48px; font-weight: 800; color: var(--dark-text); line-height: 1; }
.rating-stars-row { display: flex; gap: 3px; margin: 6px 0 4px; }
.rating-stars-row i { color: #f59e0b; font-size: 20px; }
.rating-count { font-size: 13px; color: var(--muted); margin: 0; }

.review-list-title { font-size: 14px; font-weight: 700; color: var(--dark-text); margin-bottom: 12px; }
.review-item { padding: 14px 0; border-bottom: 1px solid var(--border); }
.review-item:last-of-type { border-bottom: none; }
.review-header { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px; flex-wrap: wrap; }
.reviewer-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--secondary), #764ba2);
    color: #fff; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.reviewer-info { flex: 1; }
.reviewer-info strong { font-size: 13px; color: var(--dark-text); display: block; }
.review-stars-sm i { color: #f59e0b; font-size: 11px; }
.review-time { font-size: 11px; color: var(--muted); margin-left: auto; white-space: nowrap; }
.review-text { font-size: 13px; color: #555; line-height: 1.7; margin: 0; padding-left: 46px; }

.btn-see-all-reviews {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
    padding: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-see-all-reviews:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102,126,234,0.35); color: #fff; }

/* ── Related Books ── */
.related-books { background: var(--light-bg); padding: 50px 0; }
.section-title { font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 40px; color: var(--dark-text); position: relative; padding-bottom: 16px; }
.section-title::after { content: ''; position: absolute; left: 50%; bottom: 0; transform: translateX(-50%); width: 70px; height: 4px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 2px; }
.book-card { background: #fff; border-radius: var(--radius); overflow: hidden; transition: all 0.3s ease; box-shadow: var(--shadow-sm); height: 100%; display: flex; flex-direction: column; }
.book-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
.book-card-image { position: relative; overflow: hidden; background: var(--light-bg); height: 260px; display: flex; justify-content: center; align-items: center; }
.book-card-image img { width: 100%; max-width: 160px; height: 240px; object-fit: contain; transition: all 0.4s ease; }
.book-card:hover .book-card-image img { transform: scale(1.05); }
.quick-borrow-btn { position: absolute; bottom: -45px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--primary), #0056b3); color: #fff; border: none; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.3s ease; opacity: 0; box-shadow: 0 5px 15px rgba(11,26,233,0.35); white-space: nowrap; text-decoration: none; }
.book-card:hover .quick-borrow-btn { bottom: 12px; opacity: 1; }
.book-card-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; }
.book-card-title { font-size: 14px; font-weight: 700; margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
.book-card-title a { color: var(--dark-text); text-decoration: none; transition: color 0.3s; }
.book-card-title a:hover { color: var(--primary); }
.book-card-author { color: var(--muted); font-size: 12px; font-style: italic; margin-bottom: 8px; }
.book-card-stock { font-weight: 600; color: var(--primary); font-size: 13px; margin-top: auto; display: flex; align-items: center; gap: 4px; }

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    .main { margin-top: 75px; }
    .product-info-wrapper { padding-left: 0; margin-top: 24px; }
    .product-title { font-size: 24px; }
    .book-image-wrapper { position: relative; top: 0; padding: 24px; }
    .main-book-image { max-width: 260px; height: 380px; }
}

@media (max-width: 767px) {
    .product-detail { padding: 30px 0 40px; }
    .product-title { font-size: 20px; }
    .product-author { font-size: 14px; }
    .book-image-wrapper { padding: 18px; border-radius: 10px; }
    .main-book-image { max-width: 220px; height: 320px; }
    .action-section { gap: 8px; }
    .btn-borrow { font-size: 13px; padding: 12px 10px; min-width: 120px; }
    .btn-wishlist { width: 44px; height: 44px; font-size: 18px; }
    .section-title { font-size: 22px; }
    .book-card-image { height: 200px; }
    .book-card-image img { max-width: 130px; height: 185px; }
    .review-text { padding-left: 0; margin-top: 6px; }
}

@media (max-width: 575px) {
    .main { margin-top: 65px; }
    .book-image-wrapper { padding: 14px; }
    .main-book-image { max-width: 180px; height: 270px; }
    .product-title { font-size: 18px; }
    .btn-borrow { font-size: 12px; padding: 11px 8px; gap: 5px; }
    .btn-wishlist { width: 42px; height: 42px; }
    .meta-row { flex-direction: column; align-items: flex-start; gap: 4px; }
    .meta-value { text-align: left; }
    .rating-big-number { font-size: 38px; }
    .rating-stars-row i { font-size: 17px; }
    .book-card-image { height: 180px; }
    .book-card-image img { max-width: 110px; height: 165px; }
    .quick-borrow-btn { font-size: 11px; padding: 6px 14px; }
}
</style>

<script>
function initDetailBukuPage() {
    if (window.AOS) {
        AOS.init({ duration: 700, easing: 'ease-in-out', once: true });
    }

    const directBorrowForm = document.querySelector('.direct-borrow-form');
    if (!directBorrowForm) {
        return;
    }

    directBorrowForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const bukuId = this.dataset.bukuId;

        if (!window.Swal) {
            return this.submit();
        }

        Swal.fire({
            title: 'Konfirmasi Pinjam Langsung',
            text: 'Apakah Anda yakin ingin meminjam buku ini langsung?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0b1ae9',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pinjam!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDetailBukuPage);
} else {
    initDetailBukuPage();
}

@if(session('success'))
if (window.Swal) {
    Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session("success") }}', confirmButtonColor:'#0b1ae9', timer:2002 });
} else {
    alert('{{ session("success") }}');
}
@endif
@if(session('error'))
if (window.Swal) {
    Swal.fire({ icon:'error', title:'Gagal!', text:'{{ session("error") }}', confirmButtonColor:'#0b1ae9' });
} else {
    alert('{{ session("error") }}');
}
@endif
</script>
@endsection
