<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\WhatsappController::class);

$request = new \Illuminate\Http\Request(['phone' => '447481280711']);
$res = $controller->customerData($request);
echo "CUSTOMER DATA: " . $res->getContent() . "\n";
