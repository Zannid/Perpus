@extends('layouts.frontend')

@section('content')
<main class="main">

    <!-- Page Header -->
    <section class="page-header">
        <div class="container" data-aos="fade-up">
            <div class="header-content">
                <h1 class="page-title">Katalog Buku</h1>
                <p class="page-subtitle">Jelajahi koleksi lengkap perpustakaan digital kami</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Katalog Buku</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="catalog-section section">
        <div class="container">

            <!-- Search & Filter Bar -->
            <div class="search-filter-wrapper" data-aos="fade-up" data-aos-delay="100">
                <form method="GET" action="{{ route('katalog') }}" class="filter-form">

                    <!-- Search Box -->
                    <div class="search-box-container">
                        <div class="search-input-wrapper">
                            <i class="bi bi-search search-icon"></i>
                            <input
                                type="text"
                                name="search"
                                class="search-input"
                                placeholder="Cari judul buku, penulis, atau ISBN..."
                                value="{{ request('search') }}"
                            >
                            @if(request('search'))
                            <button type="button" class="clear-search" onclick="clearSearch()">
                                <i class="bi bi-x-circle"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="filter-options">
                        <!-- Category Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-funnel"></i>
                                Kategori
                            </label>
                            <select name="kategori" class="filter-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="bi bi-sort-down"></i>
                                Urutkan
                            </label>
                            <select name="sort" class="filter-select" onchange="this.form.submit()">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="judul_az" {{ request('sort') == 'judul_az' ? 'selected' : '' }}>Judul A-Z</option>
                                <option value="judul_za" {{ request('sort') == 'judul_za' ? 'selected' : '' }}>Judul Z-A</option>
                                <option value="penulis_az" {{ request('sort') == 'penulis_az' ? 'selected' : '' }}>Penulis A-Z</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>

                        <!-- Reset Button -->
                        @if(request()->hasAny(['search', 'kategori', 'sort']))
                        <a href="{{ route('katalog') }}" class="btn-reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                        @endif
                    </div>

                </form>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'kategori']))
            <div class="active-filters" data-aos="fade-up" data-aos-delay="150">
                <span class="filter-label">Filter Aktif:</span>
                <div class="filter-tags">
                    @if(request('search'))
                    <span class="filter-tag">
                        <i class="bi bi-search"></i>
                        "{{ request('search') }}"
                        <button onclick="removeFilter('search')" class="remove-tag">×</button>
                    </span>
                    @endif
                    @if(request('kategori'))
                    <span class="filter-tag">
                        <i class="bi bi-tag"></i>
                        {{ $kategoris->find(request('kategori'))->nama_kategori ?? '' }}
                        <button onclick="removeFilter('kategori')" class="remove-tag">×</button>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Results Info -->
            <div class="results-info" data-aos="fade-up" data-aos-delay="200">
                <p class="results-text">
                    Menampilkan <strong>{{ $bukus->count() }}</strong> dari <strong>{{ $bukus->total() }}</strong> buku
                </p>
                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid" onclick="toggleView('grid')">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="view-btn" data-view="list" onclick="toggleView('list')">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="books-container grid-view" id="booksContainer" data-aos="fade-up" data-aos-delay="300">
                @forelse($bukus as $buku)
                <div class="book-item" data-aos="zoom-in" data-aos-delay="100">
                    <div class="book-card">
                        <div class="book-cover-wrapper">
                            <img src="{{ $buku->foto ? asset('/storage/buku/' . $buku->foto) : asset('/storage/buku/default-book.png') }}"
                                alt="{{ $buku->judul }}"
                                class="book-cover-img"
                                loading="lazy">
                            <!-- Book Badge -->
                            <div class="book-badges">
                                @if($buku->stok > 0)
                                <span class="badge-available">Tersedia</span>
                                @else
                                <span class="badge-unavailable">Kosong</span>
                                @endif
                            </div>

                            <!-- Hover Overlay -->
                            <div class="book-overlay-hover">
                                <div class="book-quick-info">
                                    <h5 class="book-title">{{ $buku->judul }}</h5>
                                    <p class="book-author">{{ $buku->penulis }}</p>
                                    <p class="book-category">
                                        <i class="bi bi-tag"></i>
                                        {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                                    </p>
                                    <div class="book-stock-info">
                                        <i class="bi bi-bookmarks"></i>
                                        Stok: {{ $buku->stok }} buku
                                    </div>
                                </div>
                                <div class="book-actions">
                                    <button type="button" class="btn-action btn-add-cart"
                                  data-buku-id="{{ $buku->id }}" title="Tambah ke Keranjang"
                                  {{ $buku->stok <= 0 ? 'disabled' : '' }}
                                  style="{{ $buku->stok <= 0 ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                              <i class="bi bi-cart-plus"></i> Pinjam
                                </button>
                                    <a href="{{ route('detail_buku', $buku->id) }}" class="btn-action btn-detail" title="Detail Buku">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="book-info">
                            <h4 class="book-info-title">{{ Str::limit($buku->judul, 40) }}</h4>
                            <p class="book-info-author">{{ $buku->penulis }}</p>
                            <div class="book-meta">
                                <span class="meta-item">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $buku->tahun_terbit ?? 'N/A' }}
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-bookmarks"></i>
                                    {{ $buku->stok }} tersedia
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3>Tidak Ada Buku Ditemukan</h3>
                    <p>Maaf, tidak ada buku yang sesuai dengan pencarian Anda.</p>
                    <a href="{{ route('katalog') }}" class="btn-primary">Lihat Semua Buku</a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bukus->hasPages())
            <div class="pagination-wrapper" data-aos="fade-up" data-aos-delay="400">
                {{ $bukus->links() }}
            </div>
            @endif

        </div>
    </section>

</main>

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 100px 0 60px;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    top: -100px;
    right: -100px;
}

.header-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.page-title {
    font-size: 48px;
    font-weight: 900;
    color: #fff;
    margin-bottom: 15px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 20px;
}

.breadcrumb {
    display: inline-flex;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 10px 20px;
    border-radius: 30px;
    margin: 0;
}

.breadcrumb-item {
    color: rgba(255, 255, 255, 0.8);
}

.breadcrumb-item a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
}

.breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.9);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.6);
}

/* ============================================================
   SEARCH & FILTER
   ============================================================ */
.catalog-section {
    padding: 60px 0;
}

.search-filter-wrapper {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.search-box-container {
    margin-bottom: 20px;
}

.search-input-wrapper {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 20px;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 18px 50px 18px 55px;
    border: 2px solid #e0e6ed;
    border-radius: 15px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    background: #fff;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
}

.clear-search {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #999;
    font-size: 20px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.clear-search:hover {
    color: #667eea;
}

.filter-options {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.filter-select {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e0e6ed;
    border-radius: 12px;
    font-size: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
    background: #fff;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
}

.btn-search,
.btn-reset {
    padding: 14px 30px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
}

.btn-reset {
    background: #fff;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-reset:hover {
    background: #667eea;
    color: #fff;
}

/* ============================================================
   ACTIVE FILTERS
   ============================================================ */
.active-filters {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.active-filters .filter-label {
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.filter-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.remove-tag {
    background: none;
    border: none;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    padding: 0 0 0 5px;
    line-height: 1;
}

.remove-tag:hover {
    opacity: 0.8;
}

/* ============================================================
   RESULTS INFO
   ============================================================ */
.results-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 0 5px;
}

.results-text {
    font-size: 16px;
    color: #666;
    margin: 0;
}

.view-toggle {
    display: flex;
    gap: 8px;
    background: #f8f9fa;
    padding: 5px;
    border-radius: 10px;
}

.view-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: transparent;
    border-radius: 8px;
    cursor: pointer;
    color: #999;
    font-size: 18px;
    transition: all 0.3s ease;
}

.view-btn:hover {
    color: #667eea;
}

.view-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

/* ============================================================
   BOOKS CONTAINER
   ============================================================ */
.books-container {
    display: grid;
    gap: 30px;
    margin-bottom: 40px;
}

.books-container.grid-view {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
}

.books-container.list-view {
    grid-template-columns: 1fr;
}

.books-container.list-view .book-card {
    display: flex;
    flex-direction: row;
}

.books-container.list-view .book-cover-wrapper {
    width: 200px;
    flex-shrink: 0;
    aspect-ratio: 3/4;
}

.books-container.list-view .book-info {
    flex: 1;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* ============================================================
   BOOK CARD
   ============================================================ */
.book-item {
    height: 100%;
}

.book-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.book-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.15);
}

.book-cover-wrapper {
    position: relative;
    overflow: hidden;
    aspect-ratio: 3/4;
    background: #f5f7fa;
}

.book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.book-card:hover .book-cover-img {
    transform: scale(1.08);
}

.book-badges {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 3;
}

.badge-available,
.badge-unavailable {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    backdrop-filter: blur(10px);
}

.badge-available {
    background: rgba(46, 213, 115, 0.9);
    color: #fff;
}

.badge-unavailable {
    background: rgba(255, 71, 87, 0.9);
    color: #fff;
}

.book-overlay-hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    opacity: 0;
    transition: opacity 0.4s ease;
    padding: 25px;
}

.book-card:hover .book-overlay-hover {
    opacity: 1;
}

.book-quick-info {
    text-align: center;
    color: #fff;
}

.book-quick-info .book-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #fff;
}

.book-quick-info .book-author {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 12px;
}

.book-quick-info .book-category {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 8px;
}

.book-stock-info {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.book-actions {
    display: flex;
    gap: 12px;
    width: 100%;
}

.btn-action {
    flex: 1;
    padding: 12px;
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(10px);
}

.btn-action:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.35);
    border-color: rgba(255, 255, 255, 0.8);
    transform: translateY(-2px);
}

.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.book-info {
    padding: 20px;
}

.book-info-title {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    line-height: 1.4;
}

.book-info-author {
    font-size: 14px;
    color: #667eea;
    margin-bottom: 12px;
    font-weight: 500;
}

.book-meta {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #999;
}

.meta-item i {
    font-size: 14px;
}

/* ============================================================
   NO RESULTS
   ============================================================ */
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.no-results-icon {
    font-size: 80px;
    color: #e0e6ed;
    margin-bottom: 20px;
}

.no-results h3 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
}

.no-results p {
    font-size: 16px;
    color: #999;
    margin-bottom: 30px;
}

.btn-primary {
    display: inline-block;
    padding: 14px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
}

/* ============================================================
   PAGINATION
   ============================================================ */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 992px) {
    .page-title {
        font-size: 36px;
    }

    .filter-options {
        flex-direction: column;
    }

    .filter-group {
        width: 100%;
    }

    .books-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-header {
        padding: 80px 0 40px;
    }

    .page-title {
        font-size: 32px;
    }

    .search-filter-wrapper {
        padding: 20px;
    }

    .results-info {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .books-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .books-container.list-view .book-card {
        flex-direction: column;
    }

    .books-container.list-view .book-cover-wrapper {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .books-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }

    .book-info {
        padding: 15px;
    }

    .book-info-title {
        font-size: 14px;
    }

    .filter-options {
        gap: 10px;
    }

    .btn-search,
    .btn-reset {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Clear search input
function clearSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.value = '';
        searchInput.form.submit();
    }
}

// Remove specific filter
function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Toggle view (grid/list)
function toggleView(view) {
    const container = document.getElementById('booksContainer');
    const buttons = document.querySelectorAll('.view-btn');

    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.view === view) {
            btn.classList.add('active');
        }
    });

    if (view === 'list') {
        container.classList.remove('grid-view');
        container.classList.add('list-view');
    } else {
        container.classList.remove('list-view');
        container.classList.add('grid-view');
    }

    // Save preference to localStorage
    localStorage.setItem('catalogView', view);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('catalogView');
    if (savedView) {
        toggleView(savedView);
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  $('.btn-add-cart').click(function(e){
    e.preventDefault();
    let bukuId = $(this).data('buku-id');

    $.ajax({
      url: '{{ route("cart.add") }}',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        buku_id: bukuId,
        quantity: 1
      }),
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(res){
        if(res.success){
          // Gunakan function updateCartFromOutside dari navbar untuk auto-update cart dropdown
          if(typeof updateCartFromOutside !== 'undefined'){
            updateCartFromOutside(res);
          } else {
            // Fallback jika function tidak tersedia
            $('.cart-badge').text(res.totalItems).show();
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: res.message,
              timer: 1500,
              showConfirmButton: false
            });
          }
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: res.message,
            timer: 1500,
            showConfirmButton: false
          });
        }
      },
      error: function(xhr){
        let errorMessage = 'Terjadi kesalahan, coba lagi!';
        if(xhr.responseJSON && xhr.responseJSON.message){
          errorMessage = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: errorMessage,
        });
      }
    });

  });

});
</script>
@endsection
