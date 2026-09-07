<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$msgs = \App\Models\WhatsappMessage::orderBy('id', 'desc')->take(10)->get();
foreach ($msgs as $m) {
    echo "ID: {$m->id} | Phone: {$m->phone_number} / {$m->phone} | Status: {$m->status} | Direction: {$m->direction} | Type: {$m->message_type}\n";
    echo "Message: " . substr($m->message, 0, 80) . "...\n";
    echo "Meta ID: {$m->wa_message_id} | Created: {$m->created_at}\n";
    echo "----------------------------------------\n";
}
