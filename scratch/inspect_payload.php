<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'palvito23@gmail.com')->first();
$conductor = $user->conductor;

$asignaciones = \App\Models\AsignacionTurno::where('conductor_id', $conductor->id)
    ->with(['turno', 'micro.interno', 'ruta.paradas', 'conductor'])
    ->orderByDesc('fecha')
    ->orderByDesc('id')
    ->get();

$payload = [
    'conductor' => [
        'id' => $conductor->id,
        'licencia' => $conductor->licencia,
        'nombre_completo' => "{$user->name} {$user->apellido}",
    ],
    'total' => $asignaciones->count(),
    'asignaciones' => $asignaciones
];

echo json_encode($payload, JSON_PRETTY_PRINT);
