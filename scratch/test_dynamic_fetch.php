<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\WhatsappController::class);
$req = new \Illuminate\Http\Request(['refresh' => '1']);
$resp = $controller->getTemplates($req);

echo "Dynamic Live Templates Response:\n";
print_r($resp->getData(true));
