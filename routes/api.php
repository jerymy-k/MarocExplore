<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraireController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavorisController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/itineraires', [ItineraireController::class, 'index']);
Route::get('/itineraires/{id}', [ItineraireController::class, 'show']);

Route::get('/search', [SearchController::class, 'search']);
Route::get('/filter', [SearchController::class, 'filter']);
Route::get('/popular', [SearchController::class, 'popular']);
Route::get('/stats/categories', [SearchController::class, 'statsByCategorie']);
Route::get('/stats/months', [SearchController::class, 'statsByMonth']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/itineraires', [ItineraireController::class, 'store']);
    Route::put('/itineraires/{id}', [ItineraireController::class, 'update']);
    Route::delete('/itineraires/{id}', [ItineraireController::class, 'destroy']);
    Route::post('/itineraires/{id}/favori', [FavorisController::class, 'store']);
});
