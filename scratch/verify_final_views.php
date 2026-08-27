<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

// 1. Test ControlParadasController
$cpController = $app->make(\App\Http\Controllers\ControlParadasController::class);
$responseCP = $cpController->index(\Illuminate\Http\Request::create('/control-paradas', 'GET'));
echo "Control Paradas View: " . $responseCP->name() . " (Render: " . strlen($responseCP->render()) . " bytes)\n";

// 2. Test MonitoreoController (Seguimiento de Rutas)
$mController = $app->make(\App\Http\Controllers\MonitoreoController::class);
$responseM = $mController->index(\Illuminate\Http\Request::create('/seguimiento-rutas', 'GET'));
echo "Seguimiento Rutas View: " . $responseM->name() . " (Render: " . strlen($responseM->render()) . " bytes)\n";

$jsonPos = $mController->posicionesEnVivo();
echo "Live GPS JSON: " . $jsonPos->getContent() . "\n";

echo "ALL TESTS PASSED!\n";
