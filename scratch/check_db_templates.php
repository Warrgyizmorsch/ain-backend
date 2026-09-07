<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Database whatsapp_templates:\n";
if (\Illuminate\Support\Facades\Schema::hasTable('whatsapp_templates')) {
    $rows = \App\Models\WhatsappTemplate::all();
    foreach ($rows as $r) {
        echo "- ID: {$r->id} | Name: {$r->name} | Title: {$r->title} | Status: {$r->status}\n";
    }
} else {
    echo "No whatsapp_templates table.\n";
}
