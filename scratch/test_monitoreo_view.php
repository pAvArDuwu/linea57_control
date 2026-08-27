<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$controller = $app->make(\App\Http\Controllers\MonitoreoController::class);
$request = \Illuminate\Http\Request::create('/monitoreo', 'GET');
$response = $controller->index($request);

echo "View name: " . $response->name() . "\n";
$viewRendered = $response->render();
echo "Rendered length: " . strlen($viewRendered) . " bytes\n";

$jsonResponse = $controller->posicionesEnVivo();
echo "JSON response status: " . $jsonResponse->getStatusCode() . "\n";
echo "JSON content: " . $jsonResponse->getContent() . "\n";
echo "SUCCESS!\n";
