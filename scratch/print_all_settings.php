<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$settings = \App\Models\WhatsappSetting::all();
foreach ($settings as $s) {
    echo "ID: {$s->id} | Provider: {$s->provider} | Active: " . ($s->is_active ? 'YES' : 'NO') . "\n";
    print_r($s->settings);
    echo "====================================\n";
}
