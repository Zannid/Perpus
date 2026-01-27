<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CartAjaxController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PerpanjanganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.post');

Auth::routes();

Route::get('/', [FrontController::class, 'index'])->name('welcome');
Route::get('/detail_buku/{id}', [FrontController::class, 'show'])->name('detail_buku');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ===== AUTHENTICATED ROUTES =====
Route::middleware(['auth'])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // ===== KATEGORI =====
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/daftarkategori', [KategoriController::class, 'index'])->name('index');
        Route::get('/create', [KategoriController::class, 'create'])->name('create');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::get('/{kategori}/edit', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/{kategori}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{kategori}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // ===== LOKASI =====
    Route::prefix('lokasi')->name('lokasi.')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('index');
        Route::get('/create', [LokasiController::class, 'create'])->name('create');
        Route::post('/', [LokasiController::class, 'store'])->name('store');
        Route::get('/{lokasi}/edit', [LokasiController::class, 'edit'])->name('edit');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])->name('update');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])->name('destroy');
    });

    // ===== BUKU =====
    Route::prefix('buku')->name('buku.')->group(function () {
        Route::get('/daftarbuku', [BukuController::class, 'index'])->name('index');
        Route::get('/tambah', [BukuController::class, 'create'])->name('create');
        Route::post('/tambah', [BukuController::class, 'store'])->name('store');
        Route::get('/{buku}/edit', [BukuController::class, 'edit'])->name('edit');
        Route::get('/{buku}/show', [BukuController::class, 'show'])->name('show');
        Route::put('/{buku}', [BukuController::class, 'update'])->name('update');
        Route::delete('/{buku}', [BukuController::class, 'destroy'])->name('destroy');
        Route::get('/export-excel', [BukuController::class, 'exportExcel'])->name('export.excel');
    });

    // ===== BARANG MASUK =====
    Route::prefix('barangmasuk')->name('barangmasuk.')->group(function () {
        Route::get('/daftarbarangmasuk', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store');
        Route::get('/{barangmasuk}/edit', [BarangMasukController::class, 'edit'])->name('edit');
        Route::put('/{barangmasuk}', [BarangMasukController::class, 'update'])->name('update');
        Route::delete('/{barangmasuk}', [BarangMasukController::class, 'destroy'])->name('destroy');
        Route::get('/export-excel', [BarangMasukController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export-pdf', [BarangMasukController::class, 'exportPdf'])->name('export');
    });

    // ===== BARANG KELUAR =====
    Route::prefix('barangkeluar')->name('barangkeluar.')->group(function () {
        Route::get('/daftarbarangkeluar', [BarangKeluarController::class, 'index'])->name('index');
        Route::get('/create', [BarangKeluarController::class, 'create'])->name('create');
        Route::post('/', [BarangKeluarController::class, 'store'])->name('store');
        Route::get('/{barangkeluar}/edit', [BarangKeluarController::class, 'edit'])->name('edit');
        Route::put('/{barangkeluar}', [BarangKeluarController::class, 'update'])->name('update');
        Route::delete('/{barangkeluar}', [BarangKeluarController::class, 'destroy'])->name('destroy');
        Route::get('/export-pdf', [BarangKeluarController::class, 'exportPdf'])->name('export');
    });

    // ===== PEMINJAMAN =====
    // ===== PEMINJAMAN =====
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        // CRUD Peminjaman
        Route::get('/daftarpeminjaman', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::post('/ajax-store', [PeminjamanController::class, 'storeAjax'])->name('store.ajax');
        Route::post('/store-auto', [PeminjamanController::class, 'storeAuto'])->name('storeAuto');
        Route::get('/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('edit');
        Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
        Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');

        // Export & Payment
        Route::get('/export', [PeminjamanController::class, 'export'])->name('export');
        Route::get('/{id}/pay', [PeminjamanController::class, 'pay'])->name('pay');
        Route::post('/{id}/pay', [PeminjamanController::class, 'confirmPay'])->name('confirmPay');
        Route::get('/{id}/pay/qris', [PeminjamanController::class, 'payQris'])->name('pay.qris');

        // Return
        Route::get('/{id}/return', [PeminjamanController::class, 'showReturnForm'])->name('return.form');
        Route::post('/{id}/return', [PeminjamanController::class, 'return'])->name('return');

        // Notifikasi
        Route::post('/notif/read/{id}', [PeminjamanController::class, 'readNotif'])->name('readNotif');
        Route::get('/notif/mark-all-read', [PeminjamanController::class, 'markAllRead'])->name('markAllRead');
    });

// ===== RATING ROUTES (HARUS DI LUAR GROUP PEMINJAMAN!) =====
    Route::prefix('rating')->name('rating.')->group(function () {
        Route::get('/show', [RatingController::class, 'show'])->name('show');
        Route::post('/store', [RatingController::class, 'store'])->name('store');
        Route::put('/{id}', [RatingController::class, 'update'])->name('update');
        Route::delete('/{id}', [RatingController::class, 'destroy'])->name('destroy');
    });
    // ===== PENGEMBALIAN =====
    Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PengembalianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengembalianController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengembalianController::class, 'destroy'])->name('destroy');
        Route::get('/export', [PengembalianController::class, 'export'])->name('export');
    });

    // ===== PETUGAS (ACC & REJECT) =====
    Route::prefix('petugas')->name('petugas.')->group(function () {
        // Halaman ACC/Pending
        Route::get('/peminjaman/acc', [PeminjamanController::class, 'Pending'])->name('acc');

        // Approve & Reject
        Route::post('/peminjaman/approve/{id}', [PeminjamanController::class, 'approve'])
            ->name('peminjaman.approve');
        Route::post('/peminjaman/reject/{id}', [PeminjamanController::class, 'reject'])
            ->name('peminjaman.reject');
    });

    Route::resource('/about', AboutController::class);
    Route::post('/about/{id}/activate', [AboutController::class, 'updateStatus'])->name('about.activate');

    // ===== USER MANAGEMENT =====
    Route::resource('/admin', AdminController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/petugas', PetugasController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
});

// ===== TEST ROUTES (HAPUS DI PRODUCTION) =====
Route::get('/test-mail', function () {
    $peminjaman = \App\Models\Peminjaman::first();
    Mail::to('user@example.com')->send(new \App\Mail\PeminjamanReminder($peminjaman));
    return 'Email reminder terkirim!';
});

Route::get('/test-email', function () {
    $peminjaman = \App\Models\Peminjaman::first();
    if ($peminjaman) {
        Mail::to('fauzanbdg986@gmail.com')->send(new \App\Mail\PeminjamanApproved($peminjaman));
        return 'Email sent! Check your inbox.';
    }
    return 'No peminjaman found';
});

Route::middleware(['auth'])->group(function () {

    // Halaman daftar semua notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // Tandai notifikasi sebagai dibaca dan redirect ke link
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    // Tandai semua notifikasi sebagai dibaca
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    // Hapus notifikasi
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');

    // Detail peminjaman (untuk link notifikasi)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->name('peminjaman.show');
});

Route::middleware(['auth'])->group(function () {

    // USER - ajukan perpanjangan
    Route::post(
        '/perpanjangan/{peminjaman}',
        [PerpanjanganController::class, 'store']
    )->name('perpanjangan.store');

    // USER - riwayat perpanjangan
    Route::get(
        '/perpanjangan/history/{peminjaman}',
        [PerpanjanganController::class, 'history']
    )->name('perpanjangan.history');

    // PETUGAS / ADMIN
    Route::prefix('petugas')->name('petugas.')->group(function () {

        Route::get(
            '/perpanjangan/pending',
            [PerpanjanganController::class, 'pending']
        )->name('perpanjangan.pending');

        Route::post(
            '/perpanjangan/{id}/approve',
            [PerpanjanganController::class, 'approve']
        )->name('perpanjangan.approve');

        Route::post(
            '/perpanjangan/{id}/reject',
            [PerpanjanganController::class, 'reject']
        )->name('perpanjangan.reject');

    });
});
Route::middleware(['auth'])->prefix('keranjang')->name('keranjang.')->group(function () {
    // Halaman keranjang (gunakan CartAjaxController)
    Route::get('/', [CartAjaxController::class, 'index'])->name('index');

    // Tambah buku (non-ajax - fallback)
    Route::post('/tambah/{id}', [KeranjangController::class, 'tambah'])->name('tambah');

    // Kurang jumlah (non-ajax - fallback)
    Route::post('/kurang/{id}', [KeranjangController::class, 'kurang'])->name('kurang');

    // Hapus buku (non-ajax - fallback)
    Route::post('/hapus/{id}', [KeranjangController::class, 'hapus'])->name('hapus');

    // Submit peminjaman
    Route::post('/submit', [KeranjangController::class, 'submit'])->name('submit');
});

// ========================================
// ROUTES CART AJAX (AJAX Cart Routes)
// ========================================
Route::middleware(['auth'])->prefix('cart')->name('cart.')->group(function () {
    // Cart index (JSON atau view)
    Route::get('/', [CartAjaxController::class, 'index'])->name('index');

    // Tambah ke keranjang (AJAX)
    Route::post('/add', [CartAjaxController::class, 'addToCart'])->name('add');

    // Update quantity (AJAX)
    Route::post('/update-quantity', [CartAjaxController::class, 'updateQuantity'])->name('update-quantity');

    // Remove item (AJAX)
    Route::post('/remove', [CartAjaxController::class, 'removeItem'])->name('remove');
});

Route::post('/keranjang/tambah-ajax', [KeranjangController::class, 'tambahAjax'])->name('keranjang.tambah-ajax')->middleware('auth');
