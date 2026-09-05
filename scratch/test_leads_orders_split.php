<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\WhatsappController::class);

$request = new \Illuminate\Http\Request(['phone' => '447481280711']);
$resLeads = $controller->customerLeads($request);
echo "CUSTOMER LEADS: " . $resLeads->getContent() . "\n";

$resOrders = $controller->customerOrders($request);
echo "CUSTOMER ORDERS: " . $resOrders->getContent() . "\n";
