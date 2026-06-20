<?php

use Illuminate\Support\Facades\Route;
use Infrastructure\Http\Controllers\AuthController;
use Infrastructure\Http\Controllers\GifController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function (): void {
    Route::get('/gifs/search', [GifController::class, 'search']);
    Route::get('/gifs/{id}', [GifController::class, 'show']);
    Route::post('/favorites', [GifController::class, 'saveFavorite']);
});
