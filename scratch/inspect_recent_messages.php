<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$msgs = \App\Models\WhatsappMessage::orderByDesc('id')->take(6)->get();
foreach ($msgs as $m) {
    echo "ID: {$m->id} | Phone: {$m->phone} | Status: {$m->status} | Direction: {$m->direction} | wa_msg_id: {$m->wa_message_id}\n";
    echo "Text: " . substr($m->message, 0, 80) . "\n";
    echo "Created: {$m->created_at}\n";
    echo "--------------------------------------------------------\n";
}
