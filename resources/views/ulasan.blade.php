@extends('layouts.frontend')

@section('content')
<main class="main">

    {{-- Breadcrumb --}}
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb" data-aos="fade-up">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('detail_buku', $buku->id) }}">{{ Str::limit($buku->judul, 30) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Semua Ulasan</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="reviews-page">
        <div class="container">

            {{-- Header Buku --}}
            <div class="book-header-card" data-aos="fade-up">
                <div class="book-header-image">
                    <img src="{{ asset('storage/buku/' . ($buku->foto ?? 'default.jpg')) }}"
                         alt="{{ $buku->judul }}">
                </div>
                <div class="book-header-info">
                    <span class="book-header-label">Ulasan untuk</span>
                    <h1 class="book-header-title">{{ $buku->judul }}</h1>
                    <p class="book-header-author">
                        <i class="bi bi-pen"></i> {{ $buku->penulis ?? 'Penulis tidak diketahui' }}
                    </p>
                    <a href="{{ route('detail_buku', $buku->id) }}" class="btn-back-detail">
                        <i class="bi bi-arrow-left"></i> Kembali ke Detail Buku
                    </a>
                </div>

                {{-- Rating Summary --}}
                @php
                    $allRatings = $buku->ratings;
                    $avg   = number_format($allRatings->avg('rating'), 1);
                    $total = $allRatings->count();
                    $dist  = [];
                    for($s = 5; $s >= 1; $s--) {
                        $dist[$s] = $allRatings->where('rating', $s)->count();
                    }
                @endphp
                <div class="rating-summary">
                    <div class="rating-summary-big">
                        <span class="rating-num">{{ $avg }}</span>
                        <div class="rating-stars-lg">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-total">{{ $total }} ulasan</span>
                    </div>
                    <div class="rating-bars">
                        @for($s = 5; $s >= 1; $s--)
                            @php $pct = $total > 0 ? round(($dist[$s] / $total) * 100) : 0; @endphp
                            <div class="rating-bar-row">
                                <span class="bar-label">{{ $s }} <i class="bi bi-star-fill"></i></span>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="bar-count">{{ $dist[$s] }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Filter & Sort --}}
            <div class="filter-bar" data-aos="fade-up">
                <div class="filter-left">
                    <span class="filter-label">Filter:</span>
                    @foreach([0 => 'Semua', 5 => '5 ⭐', 4 => '4 ⭐', 3 => '3 ⭐', 2 => '2 ⭐', 1 => '1 ⭐'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['bintang' => $val, 'page' => 1]) }}"
                           class="filter-chip {{ (request('bintang', 0) == $val) ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                <div class="filter-right">
                    <select class="sort-select" onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}"
                            {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'tertinggi']) }}"
                            {{ request('sort') === 'tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'terendah']) }}"
                            {{ request('sort') === 'terendah' ? 'selected' : '' }}>Rating Terendah</option>
                    </select>
                </div>
            </div>

            {{-- Daftar Ulasan --}}
            <div class="reviews-list" data-aos="fade-up">
                @forelse($ratings as $rating)
                    <div class="review-card">
                        <div class="review-card-header">
                            <div class="reviewer-avatar-lg">
                                {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                            </div>
                            <div class="reviewer-meta">
                                <strong class="reviewer-name">{{ $rating->user->name }}</strong>
                                <div class="reviewer-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="star-value">{{ $rating->rating }}/5</span>
                                </div>
                            </div>
                            <span class="review-date">
                                <i class="bi bi-clock"></i>
                                {{ $rating->created_at->format('d M Y') }}
                            </span>
                        </div>
                        <div class="review-card-body">
                            @if($rating->review)
                                <p>{{ $rating->review }}</p>
                            @else
                                <p class="review-empty">Pengguna tidak menambahkan komentar.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-reviews">
                        <i class="bi bi-chat-square-text"></i>
                        <p>Belum ada ulasan yang sesuai filter.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper">
                {{ $ratings->appends(request()->query())->links() }}
            </div>

        </div>
    </section>

</main>

<style>
:root {
    --primary: #0b1ae9;
    --secondary: #667eea;
    --light-bg: #f5f6ff;
    --dark-text: #1e2459;
    --muted: #6b7280;
    --border: #e5e7eb;
    --shadow-sm: 0 1px 8px rgba(0,0,0,0.07);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.10);
    --radius: 12px;
    --star-color: #f59e0b;
}

.main { margin-top: 90px; }

/* ── Breadcrumb ── */
.breadcrumb-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 22px 0; }
.breadcrumb { background: transparent; padding: 0; margin: 0; }
.breadcrumb-item { color: rgba(255,255,255,0.85); font-size: 14px; }
.breadcrumb-item.active { color: #fff; font-weight: 600; }
.breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.7); content: "›"; font-size: 18px; }
.breadcrumb a { color: #fff; text-decoration: none; }

/* ── Reviews Page ── */
.reviews-page { padding: 40px 0 60px; background: #fafafa; min-height: 60vh; }

/* ── Book Header Card ── */
.book-header-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: flex-start;
}
.book-header-image {
    width: 90px;
    height: 130px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}
.book-header-image img { width: 100%; height: 100%; object-fit: cover; }
.book-header-info { flex: 1; min-width: 160px; }
.book-header-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); font-weight: 700; }
.book-header-title { font-size: 20px; font-weight: 700; color: var(--dark-text); margin: 4px 0 6px; line-height: 1.3; }
.book-header-author { font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 5px; margin-bottom: 14px; }
.book-header-author i { color: var(--primary); }
.btn-back-detail {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; background: var(--light-bg);
    color: var(--primary); border-radius: 8px; font-size: 13px; font-weight: 600;
    text-decoration: none; border: 1.5px solid rgba(102,126,234,0.3);
    transition: all 0.3s ease;
}
.btn-back-detail:hover { background: var(--primary); color: #fff; }

/* ── Rating Summary ── */
.rating-summary {
    display: flex;
    gap: 24px;
    align-items: center;
    background: var(--light-bg);
    border-radius: 12px;
    padding: 18px 22px;
    flex-wrap: wrap;
    width: 100%;
    margin-top: 4px;
}
.rating-summary-big { display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 90px; }
.rating-num { font-size: 44px; font-weight: 800; color: var(--dark-text); line-height: 1; }
.rating-stars-lg { display: flex; gap: 3px; }
.rating-stars-lg i { color: var(--star-color); font-size: 18px; }
.rating-total { font-size: 12px; color: var(--muted); }
.rating-bars { flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 6px; }
.rating-bar-row { display: flex; align-items: center; gap: 8px; }
.bar-label { font-size: 12px; color: var(--muted); width: 36px; flex-shrink: 0; display: flex; align-items: center; gap: 2px; }
.bar-label i { color: var(--star-color); font-size: 10px; }
.bar-track { flex: 1; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
.bar-fill { height: 100%; background: linear-gradient(90deg, #f59e0b, #f97316); border-radius: 4px; transition: width 0.6s ease; }
.bar-count { font-size: 12px; color: var(--muted); width: 24px; text-align: right; flex-shrink: 0; }

/* ── Filter Bar ── */
.filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    background: #fff;
    padding: 14px 18px;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
}
.filter-left { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.filter-label { font-size: 13px; font-weight: 600; color: var(--dark-text); }
.filter-chip {
    padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
    background: var(--light-bg); color: var(--muted);
    text-decoration: none; border: 1.5px solid transparent;
    transition: all 0.25s ease;
}
.filter-chip:hover { border-color: var(--secondary); color: var(--secondary); }
.filter-chip.active { background: var(--primary); color: #fff; border-color: var(--primary); }
.sort-select {
    padding: 7px 12px; border-radius: 8px; border: 1.5px solid var(--border);
    font-size: 13px; color: var(--dark-text); background: #fff; cursor: pointer;
    outline: none;
}
.sort-select:focus { border-color: var(--secondary); }

/* ── Review Cards ── */
.reviews-list { display: flex; flex-direction: column; gap: 14px; }
.review-card {
    background: #fff;
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: box-shadow 0.3s ease;
}
.review-card:hover { box-shadow: var(--shadow-md); }
.review-card-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; flex-wrap: wrap; }
.reviewer-avatar-lg {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, var(--secondary), #764ba2);
    color: #fff; font-size: 16px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.reviewer-meta { flex: 1; }
.reviewer-name { font-size: 14px; color: var(--dark-text); display: block; margin-bottom: 3px; }
.reviewer-stars { display: flex; align-items: center; gap: 3px; }
.reviewer-stars i { color: var(--star-color); font-size: 12px; }
.star-value { font-size: 11px; color: var(--muted); margin-left: 4px; }
.review-date { font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 4px; margin-left: auto; white-space: nowrap; }
.review-card-body p { font-size: 14px; color: #555; line-height: 1.7; margin: 0; }
.review-empty { color: var(--muted) !important; font-style: italic; }

/* ── Empty State ── */
.empty-reviews { text-align: center; padding: 60px 20px; color: var(--muted); }
.empty-reviews i { font-size: 56px; color: #d1d5db; display: block; margin-bottom: 14px; }
.empty-reviews p { font-size: 15px; }

/* ── Pagination ── */
.pagination-wrapper { display: flex; justify-content: center; margin-top: 30px; }
.pagination-wrapper .pagination .page-link { color: var(--primary); border-radius: 8px; margin: 0 2px; }
.pagination-wrapper .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

/* ── RESPONSIVE ── */
@media (max-width: 767px) {
    .main { margin-top: 75px; }
    .reviews-page { padding: 24px 0 40px; }
    .book-header-card { padding: 16px; gap: 14px; }
    .book-header-title { font-size: 17px; }
    .rating-summary { padding: 14px 16px; gap: 16px; }
    .rating-num { font-size: 36px; }
    .filter-bar { padding: 12px 14px; }
    .review-card { padding: 16px; }
}

@media (max-width: 575px) {
    .main { margin-top: 65px; }
    .book-header-image { width: 70px; height: 100px; }
    .book-header-title { font-size: 15px; }
    .rating-summary { flex-direction: column; align-items: flex-start; }
    .rating-bars { width: 100%; }
    .filter-left { gap: 6px; }
    .filter-chip { padding: 5px 10px; font-size: 11px; }
    .review-card-header { flex-wrap: wrap; }
    .review-date { width: 100%; margin-left: 56px; margin-top: -6px; }
}
</style>
@endsection
