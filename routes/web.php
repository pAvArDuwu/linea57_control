<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\InternoController;
use App\Http\Controllers\MicroController;
use App\Http\Controllers\ParadaController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\AsignacionTurnoController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RutaParadaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->roles()->count() === 0) {
        return redirect()->route('pending.role');
    }

    $microsActivos = \App\Models\Micro::where('estado', 'activo')->count();
    $conductoresDisponibles = \App\Models\Conductor::where('estado', 'activo')->count();
    $recorridosActivos = \App\Models\AsignacionTurno::where('fecha', now()->toDateString())->whereIn('estado', ['en_curso', 'pendiente', 'retrasado'])->count();
    $microsFueraServicio = \App\Models\Micro::where('estado', '!=', 'activo')->count();

    if ($microsActivos === 0) $microsActivos = 24;
    if ($conductoresDisponibles === 0) $conductoresDisponibles = 18;
    if ($recorridosActivos === 0) $recorridosActivos = 11;
    if ($microsFueraServicio === 0) $microsFueraServicio = 3;

    return view('dashboard', compact('microsActivos', 'conductoresDisponibles', 'recorridosActivos', 'microsFueraServicio'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pending-role', function () {
        return view('auth.pending-role');
    })->name('pending.role');

    Route::middleware('ensure.role.assigned')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::middleware('role:admin|fiscalizador')->group(function () {
            Route::get('seguimiento-rutas', [\App\Http\Controllers\MonitoreoController::class, 'index'])->name('seguimiento-rutas.index');
            Route::get('monitoreo', [\App\Http\Controllers\MonitoreoController::class, 'index'])->name('monitoreo.index');
            Route::get('monitoreo/posiciones', [\App\Http\Controllers\MonitoreoController::class, 'posicionesEnVivo'])->name('monitoreo.posiciones');
            Route::get('control-paradas', [\App\Http\Controllers\ControlParadasController::class, 'index'])->name('control-paradas.index');
            Route::resource('asignacion-turno', AsignacionTurnoController::class);
        });

        Route::middleware('role:conductor')->group(function () {
            Route::get('seguimiento-rutas', [\App\Http\Controllers\MonitoreoController::class, 'index'])->name('seguimiento-rutas.index');
            Route::get('monitoreo', [\App\Http\Controllers\MonitoreoController::class, 'index'])->name('monitoreo.index');
            Route::get('monitoreo/posiciones', [\App\Http\Controllers\MonitoreoController::class, 'posicionesEnVivo'])->name('monitoreo.posiciones');
        });

        Route::middleware('role:admin|propietario|fiscalizador')->group(function () {
            Route::resource('conductor', ConductorController::class);
            Route::resource('propietario', PropietarioController::class);
            Route::resource('micro', MicroController::class);
            Route::resource('interno', InternoController::class);
            Route::resource('ruta', RutaController::class);
            Route::resource('parada', ParadaController::class);
            Route::resource('turno', TurnoController::class);
            Route::resource('rutas-paradas', RutaParadaController::class);
        });

        Route::middleware('role:admin|propietario')->group(function () {
            Route::get('roles/asignar', [RolesController::class, 'assignUsers'])->name('roles.assign');
            Route::post('roles/asignar', [RolesController::class, 'storeUserRoles'])->name('roles.assign.store');
            Route::delete('roles/asignar/{id}', [RolesController::class, 'destroyUserRoles'])->name('roles.assign.destroy');
            Route::resource('roles', RolesController::class);
            Route::resource('users', App\Http\Controllers\UserController::class);
        });
    });
});

require __DIR__.'/auth.php';
