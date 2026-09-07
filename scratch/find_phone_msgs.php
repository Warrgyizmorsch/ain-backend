<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$phoneSearch = '7239849705';
$msgs = \App\Models\WhatsappMessage::where('phone', 'like', "%{$phoneSearch}%")
    ->orderBy('id', 'desc')
    ->get();

echo "Messages found for {$phoneSearch}: " . count($msgs) . "\n";
foreach ($msgs as $m) {
    echo "ID: {$m->id} | Phone: {$m->phone} | Status: {$m->status} | Direction: {$m->direction} | Created: {$m->created_at}\n";
    echo "Msg: {$m->message}\n";
    echo "Meta ID: {$m->wa_message_id}\n";
    echo "-----------------------------------------\n";
}
