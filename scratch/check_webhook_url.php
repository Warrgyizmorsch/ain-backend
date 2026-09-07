<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = \App\Models\WhatsappSetting::where('is_active', true)->first();
echo "Active Provider: " . ($s->provider ?? 'none') . "\n";
echo "Settings: " . json_encode($s->settings ?? [], JSON_PRETTY_PRINT) . "\n";
