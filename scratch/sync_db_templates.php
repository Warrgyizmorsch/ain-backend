<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappTemplate;

$realTemplates = [
    [
        'name' => 'introduction',
        'title' => 'Re-engage Follow-up (Introduction)',
        'category' => 'MARKETING',
        'language' => 'en_GB',
        'body' => "Hello {{1}}, we noticed your previous inquiry with Assignment In Need. Our team is available 24/7 to help you with your assignments, essays, and reports. Please reply to this message if you would like to proceed.\n\n— Assignment In Need Team",
        'footer_text' => 'Assignment In Need Team',
        'variables' => ['1' => 'Customer Name'],
        'status' => 'APPROVED',
        'is_active' => true,
    ],
    [
        'name' => 'offer_message',
        'title' => 'Special Offer & Inquiry',
        'category' => 'MARKETING',
        'language' => 'en_GB',
        'body' => "Hello {{1}}, we noticed your previous inquiry with Assignment In Need. Our team is available 24/7 to help you with your assignments, essays, and reports. Please reply to this message if you would like to proceed.\n\n— Assignment In Need Team",
        'footer_text' => 'Assignment In Need Team',
        'variables' => ['1' => 'Customer Name'],
        'status' => 'APPROVED',
        'is_active' => true,
    ],
    [
        'name' => 'after_orderconfirmation_welcome_messge',
        'title' => 'Order Confirmation Welcome Message',
        'category' => 'MARKETING',
        'language' => 'en_GB',
        'body' => "Hello! Hope you're doing well today\n\nI'm a representative from Assignment In Need (AIN).\n\nYou have recently confirmed the order with us.\n\nWe would like to communicate with you about your recent assignment on this primary number, which offers instant response and hassle-free services.\n\nI'll be waiting for your response to the discussion.\n\nThanks,\nTeam AIN",
        'footer_text' => 'Assignment In Need Team',
        'variables' => [],
        'status' => 'APPROVED',
        'is_active' => true,
    ],
];

WhatsappTemplate::truncate();
foreach ($realTemplates as $t) {
    WhatsappTemplate::create($t);
}

echo "Database whatsapp_templates successfully synced with AiSensy Approved templates!\n";
