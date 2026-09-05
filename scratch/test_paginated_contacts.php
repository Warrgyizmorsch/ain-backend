<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\WhatsappController::class);

$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getContactsPaginated');
$method->setAccessible(true);

$res = $method->invoke($controller, '447481280711', 10, 1, null);
echo "TOTAL CONTACTS: " . $res['total'] . "\n";
foreach (array_slice($res['contacts'], 0, 5) as $c) {
    echo "- " . $c['name'] . " (" . $c['phone'] . ")\n";
}
