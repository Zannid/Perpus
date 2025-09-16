<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/search', [SearchController::class, 'index'])->name('search');


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::resource('/kategori', KategoriController::class);
Route::resource('/lokasi', LokasiController::class);
Route::resource('/buku', BukuController::class);
Route::resource('/barangmasuk', BarangMasukController::class);
Route::resource('/barangkeluar', BarangKeluarController::class);
Route::resource('/peminjaman', PeminjamanController::class);
Route::get('peminjaman-export', [PeminjamanController::class, 'export'])->name('peminjaman.export');
Route::get('/buku/export-excel', [BukuController::class, 'exportExcel'])->name('buku.export.excel');
Route::get('barangmasuk/export-excel', [BarangMasukController::class, 'exportExcel'])->name('barangmasuk.export.excel');


Route::get('/pengembalian', function () {
    $pengembalian = \App\Models\Pengembalian::with('user', 'buku', 'peminjaman')->get();
    return view('pengembalian.index', compact('pengembalian'));
})->name('pengembalian.index');

Route::get('/petugas/peminjaman', [PeminjamanController::class, 'pending'])
    ->name('acc');
Route::get('/peminjaman/{id}/pay', [PeminjamanController::class, 'pay'])->name('peminjaman.pay');
Route::post('/peminjaman/{id}/pay', [PeminjamanController::class, 'confirmPay'])->name('peminjaman.confirmPay');

Route::post('/petugas/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])
    ->name('petugas.peminjaman.approve');
Route::post('/peminjaman/{id}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');
Route::get('/send-reminder/{id}', [PeminjamanController::class, 'sendReminder']);

Route::resource('/user', UserController::class);
Route::resource('/petugas', App\Http\Controllers\PetugasController::class);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/test-mail', function () {
    $peminjaman = Peminjaman::first(); // ambil data peminjaman pertama
    Mail::to('user@example.com')->send(new PeminjamanReminder($peminjaman));
    return 'Email reminder terkirim!';
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::resource('/kategori', KategoriController::class);
    Route::resource('/lokasi', LokasiController::class);
    Route::resource('/buku', BukuController::class);
    Route::resource('/barangmasuk', BarangMasukController::class);
    Route::resource('/barangkeluar', BarangKeluarController::class);
});
