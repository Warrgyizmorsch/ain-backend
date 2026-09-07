<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = \App\Models\WhatsappSetting::all();
foreach ($rows as $r) {
    echo "Provider: {$r->provider} | Active: " . ($r->is_active ? 'YES' : 'NO') . "\n";
    echo "Settings: " . json_encode($r->settings, JSON_PRETTY_PRINT) . "\n";
    echo "-----------------------------------------\n";
}
