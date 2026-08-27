<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$a = \App\Models\AsignacionTurno::find(3);
if ($a) {
    $a->update([
        'estado' => 'pendiente',
        'hora_salida' => null,
        'hora_llegada' => null,
    ]);
    echo "Asignacion ID 3 activada a 'pendiente' para el conductor Pablo Villegas.\n";
} else {
    echo "Asignacion no encontrada.\n";
}
