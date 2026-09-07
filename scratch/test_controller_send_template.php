<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/whatsapp/chat/send-template', 'POST', [
    'phone' => '+917239849705',
    'template_name' => 'introduction',
    'params' => ['Mangilal'],
]);

$controller = app(\App\Http\Controllers\WhatsappController::class);
$response = $controller->sendTemplate($request);

echo "Response:\n";
print_r($response->getData(true));
