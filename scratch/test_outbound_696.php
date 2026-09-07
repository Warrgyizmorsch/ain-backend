<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projectId = '64b7904a3702730b51b76dc1';
$apiKey = '798699e56bbe28cc0b669';
$apiUrl = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/messages";

$payload = [
    'to' => '917239849705',
    'type' => 'text',
    'recipient_type' => 'individual',
    'text' => [
        'body' => 'Test outbound text message from 696 project',
    ],
];

$res = \Illuminate\Support\Facades\Http::withHeaders([
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'X-AiSensy-Project-API-Pwd' => $apiKey,
])->post($apiUrl, $payload);

echo "Status: " . $res->status() . "\n";
echo "Response Body:\n" . $res->body() . "\n";
