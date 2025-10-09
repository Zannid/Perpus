<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/welcome', [FrontController::class, 'index'])->name('welcome');

Route::middleware(['auth'])->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Kategori
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/daftarkategori', [KategoriController::class, 'index'])->name('index');
        Route::get('/create', [KategoriController::class, 'create'])->name('create');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::get('/{kategori}/edit', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/{kategori}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{kategori}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // Lokasi
    Route::prefix('lokasi')->name('lokasi.')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('index');
        Route::get('/create', [LokasiController::class, 'create'])->name('create');
        Route::post('/', [LokasiController::class, 'store'])->name('store');
        Route::get('/{lokasi}/edit', [LokasiController::class, 'edit'])->name('edit');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])->name('update');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])->name('destroy');
    });

    // Buku
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

    // Barang Masuk
    Route::prefix('barangmasuk')->name('barangmasuk.')->group(function () {
        Route::get('/daftarbarangmasuk', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store');
        Route::get('/{barangmasuk}/edit', [BarangMasukController::class, 'edit'])->name('edit');
        Route::put('/{barangmasuk}', [BarangMasukController::class, 'update'])->name('update');
        Route::delete('/{barangmasuk}', [BarangMasukController::class, 'destroy'])->name('destroy');
        Route::get('/export-excel', [BarangMasukController::class, 'exportExcel'])->name('export.excel');
    });

    Route::get('/barangmasuk/export-pdf', [BarangMasukController::class, 'exportPdf'])->name('barangmasuk.export');
    Route::get('/barangkeluar/export-pdf', [BarangKeluarController::class, 'exportPdf'])->name('barangkeluar.export');

    // Barang Keluar
    Route::prefix('barangkeluar')->name('barangkeluar.')->group(function () {
        Route::get('/daftarbarangkeluar', [BarangKeluarController::class, 'index'])->name('index');
        Route::get('/create', [BarangKeluarController::class, 'create'])->name('create');
        Route::post('/', [BarangKeluarController::class, 'store'])->name('store');
        Route::get('/{barangkeluar}/edit', [BarangKeluarController::class, 'edit'])->name('edit');
        Route::put('/{barangkeluar}', [BarangKeluarController::class, 'update'])->name('update');
        Route::delete('/{barangkeluar}', [BarangKeluarController::class, 'destroy'])->name('destroy');
    });

    // Peminjaman & Pengembalian
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/daftarpeminjaman', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('edit');
        Route::put('/{peminjaman}', [PeminjamanController::class, 'update'])->name('update');
        Route::delete('/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('destroy');
        Route::get('/export', [PeminjamanController::class, 'export'])->name('export');
        Route::get('/{id}/pay', [PeminjamanController::class, 'pay'])->name('pay');
        Route::post('/{id}/pay', [PeminjamanController::class, 'confirmPay'])->name('confirmPay');
        Route::get('/{id}/pay/qris', [PeminjamanController::class, 'payQris'])->name('pay.qris');
        Route::post('/{id}/return', [PeminjamanController::class, 'return'])->name('return');
    });

    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalianIndex'])->name('pengembalian.index');
    Route::get('/pengembalian/export', [PengembalianController::class, 'export'])->name('pengembalian.export');

    // Petugas
    Route::prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/pengajuan', [PeminjamanController::class, 'pending'])->name('acc');
        Route::get('/peminjaman', [PeminjamanController::class, 'pending'])->name('peminjaman.pending');
        Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    });

    // Admin, User, Petugas
    Route::resource('/admin', AdminController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/petugas', PetugasController::class);
});

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Test email
Route::get('/test-mail', function () {
    $peminjaman = \App\Models\Peminjaman::first();
    Mail::to('user@example.com')->send(new \App\Mail\PeminjamanReminder($peminjaman));
    return 'Email reminder terkirim!';
});
