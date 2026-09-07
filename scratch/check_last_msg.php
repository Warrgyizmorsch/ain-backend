<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$msgs = \App\Models\WhatsappMessage::orderBy('id', 'desc')->take(5)->get();
foreach ($msgs as $m) {
    echo "ID: {$m->id} | Phone: {$m->phone_number} | Status: {$m->status} | Sender: {$m->sender_type} | Type: {$m->message_type}\n";
    echo "Message: {$m->message}\n";
    echo "Meta Resp: " . json_encode($m->meta_response) . "\n";
    echo "Created: {$m->created_at}\n";
    echo "----------------------------------------\n";
}
