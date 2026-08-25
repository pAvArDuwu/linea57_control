<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConductorController;
use App\Http\Controllers\Api\InternoController;
use App\Http\Controllers\Api\MicroController;
use App\Http\Controllers\Api\ParadaController;
use App\Http\Controllers\Api\PropietarioController;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\TurnoController;
use App\Http\Controllers\Api\AsignacionTurnoApi;

// Rutas públicas
Route::post('/login',    [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',       [AuthController::class, 'me']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    Route::apiResource('conductores', ConductorController::class)->parameters(['conductores' => 'conductor']);
    Route::apiResource('propietarios', PropietarioController::class);
    Route::apiResource('internos', InternoController::class);
    Route::apiResource('micros', MicroController::class);
    Route::apiResource('rutas', RutaController::class);
    Route::apiResource('paradas', ParadaController::class);
    Route::apiResource('turnos', TurnoController::class);
    Route::apiResource('asignacion-turnos', AsignacionTurnoApi::class);
});
