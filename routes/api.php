<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuApiController;
use App\Http\Controller\Api\KategoriApiController;
use App\Http\Controllers\Api\AuthController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
route::middleware('auth:sanctum')->group(function () {
    Route::resource('/bukus', BukuApiController::class);
    Route::resource('/kategoris', KategoriApiController::class);
});

Route::get('/users', [AuthController::class, 'index']);

Route::get('/me', fn(Request $request) => $request->user())
    ->middleware('auth:sanctum');
// // 
// Route::resource('/kategoris', App\Http\Controllers\Api\KategoriApiController::class);


