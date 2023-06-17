<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// ambil semua data
Route::get('/sampah', [SampahController::class, 'index']);
// tambah data baru
Route::post('/sampah/tambah-data', [SampahController::class, 'store']);
// generate token csrrf
Route::get('/generate-token', [SampahController::class, 'createToken']);
// ambil satu data spesifik
Route::get('/sampah/{id}', [SampahController::class, 'show']);
// mengubah data tertentu
Route::post('/sampah/update/{id}', [SampahController::class, 'update']);
// menghapus data tertentu
Route::delete('/sampah/delete/{id}', [SampahController::class, 'destroy']);
// menampilkan seluruh data yang sudah dihapus sementara oleh softdeletes
Route::get('/sampah/show/trash', [SampahController::class, 'trash']);
// mengembalikan data spesifik yang sudah dihapus
Route::get('/sampah/trash/restore/{id}', [SampahController::class, 'restore']);
// menghapus permanen data tertentu
Route::get('/sampah/trash/delete/permanent/{id}', [SampahController::class, 'permanentDelete']);
