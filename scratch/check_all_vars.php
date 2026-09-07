<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = \App\Models\WhatsappMessage::where('phone', 'like', '%7239849705%')->get(['id', 'phone', 'direction', 'message', 'status', 'created_at']);
echo "Total found: " . count($rows) . "\n";
print_r($rows->toArray());
