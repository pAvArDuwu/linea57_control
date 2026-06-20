<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 

// Rutas públicas
Route::post('/login',    [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:api')->group(function () {
    Route::get('/me',       [AuthController::class, 'me']);
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});