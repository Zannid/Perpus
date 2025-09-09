<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'index'])->name('search');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::resource('/kategori', KategoriController::class);
Route::resource('/lokasi', LokasiController::class);
Route::resource('/buku', BukuController::class);
Route::resource('/barangmasuk', BarangMasukController::class);
Route::resource('/barangkeluar', BarangKeluarController::class);
Route::resource('/peminjaman', PeminjamanController::class);

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


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
