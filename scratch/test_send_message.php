<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$req = \Illuminate\Http\Request::create('/whatsapp/chat/send', 'POST', [
    'phone' => '+917239849705',
    'message' => 'Hello from controller test',
]);
$req->headers->set('Accept', 'application/json');

$controller = app(\App\Http\Controllers\WhatsappController::class);
$resp = $controller->sendMessage($req);

echo "Status Code: " . $resp->getStatusCode() . "\n";
print_r($resp->getData(true));
