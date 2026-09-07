<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app(\App\Http\Controllers\WhatsappController::class);
$r = \Illuminate\Http\Request::create('/whatsapp/chat/templates');
$templatesResponse = $controller->getTemplates();

echo "Active Setting:\n";
print_r(\App\Models\WhatsappSetting::where('is_active', true)->first()->toArray());

echo "\nTemplates Response:\n";
print_r($templatesResponse->getData(true));
