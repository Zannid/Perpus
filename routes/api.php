<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuApiController;
use App\Http\Controllers\Api\KategoriApiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\LokasiApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::resource('/books', BukuApiController::class);
    Route::resource('/kategoris', KategoriApiController::class);
    Route::resource('/lokasis', LokasiApiController::class);
});

Route::get('/users', [AuthController::class, 'index']);
Route::get('/users/search', [UserController::class, 'searchUsers']);

Route::get('/me', fn(Request $request) => $request->user())
    ->middleware('auth:sanctum');
// //
// Route::resource('/kategoris', App\Http\Controllers\Api\KategoriApiController::class);
Route::get('/books', [BukuApiController::class, 'index']);
Route::get('/books/latest', [BukuApiController::class, 'latest']);
Route::get('/books/search', [BukuApiController::class, 'search']);
Route::get('/books/categories', [BukuApiController::class, 'categories']);
Route::get('/books/{id}', [BukuApiController::class, 'show']);
Route::get('/books/popular', [BukuApiController::class, 'popular']);
