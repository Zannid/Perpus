<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuApiController;
use App\Http\Controller\Api\KategoriApiController;
/*
|--------------------------------------------------------------------------
| API Routes        

|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!              
|
*/  
// Route::resource('/bukus', App\Http\Controllers\Api\BukuApiController::class);
Route::post('/notifikasi/read/{id}', [PeminjamanController::class, 'readNotif'])
    ->name('notifikasi.read');

// // 
// Route::resource('/kategoris', App\Http\Controllers\Api\KategoriApiController::class);


