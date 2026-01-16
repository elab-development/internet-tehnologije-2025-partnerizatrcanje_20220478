<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/lokacije', [App\Http\Controllers\LokacijaController::class, 'index']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register']);

Route::get('/trke', [App\Http\Controllers\TrkaController::class, 'index']);
Route::get('/trke/buduce', [App\Http\Controllers\TrkaController::class, 'buduceTrke']);
Route::get('/trke/{id}', [App\Http\Controllers\TrkaController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/trke', [App\Http\Controllers\TrkaController::class, 'store']);
    Route::delete('/trke/{id}', [App\Http\Controllers\TrkaController::class, 'destroy'])->middleware(\App\Http\Middleware\AdminMiddleware::class);
    Route::get('/trke/{id}/ucesca', [App\Http\Controllers\TrkaController::class, 'ucesca']);
    Route::get('/ucesca', [App\Http\Controllers\UcescaController::class, 'index']);
    Route::get('/users/{userId}/ucesca', [App\Http\Controllers\UcescaController::class, 'pretragaPoKorisniku']);
    Route::post('/ucesca', [App\Http\Controllers\UcescaController::class, 'store']);
    Route::delete('/ucesca/{id}', [App\Http\Controllers\UcescaController::class, 'destroy']);
    Route::get('/postovi', [App\Http\Controllers\PostController::class, 'index']);
    Route::get('/postovi/{id}', [App\Http\Controllers\PostController::class, 'show']);
    Route::post('/postovi', [App\Http\Controllers\PostController::class, 'store']);
    Route::delete('/postovi/{id}', [App\Http\Controllers\PostController::class, 'destroy']);
    Route::resource('/komentari', App\Http\Controllers\KomentarController::class)->only(['index', 'store', 'destroy']);

    Route::get('/ucesca/paginacija', [App\Http\Controllers\UcescaController::class, 'paginacija']);

});