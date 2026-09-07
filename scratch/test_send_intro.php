<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projectId = '67e109077c4b230bed2fb1ff';
$apiKey = '222488aa8678e32a9069d';
$url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/messages";

$payload = [
    'to' => '917239849705',
    'type' => 'template',
    'recipient_type' => 'individual',
    'template' => [
        'name' => 'introduction',
        'language' => [
            'code' => 'en_GB',
            'policy' => 'deterministic',
        ],
        'components' => [
            [
                'type' => 'body',
                'parameters' => [
                    ['type' => 'text', 'text' => 'Mangilal']
                ]
            ]
        ]
    ]
];

$res = \Illuminate\Support\Facades\Http::withHeaders([
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'X-AiSensy-Project-API-Pwd' => $apiKey,
])->post($url, $payload);

echo "Status: " . $res->status() . "\n";
echo "Body:\n" . $res->body() . "\n";
