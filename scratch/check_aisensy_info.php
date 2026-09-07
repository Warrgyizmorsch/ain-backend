<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projectId = '67e109077c4b230bed2fb1ff';
$apiKey = '222488aa8678e32a9069d';

// Let's check project info endpoints on AiSensy
$urls = [
    "https://apis.aisensy.com/project-apis/v1/project/{$projectId}",
    "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/users",
    "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/profile",
    "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/",
];

foreach ($urls as $url) {
    $res = \Illuminate\Support\Facades\Http::withHeaders([
        'Accept' => 'application/json',
        'X-AiSensy-Project-API-Pwd' => $apiKey,
    ])->get($url);
    echo "URL: $url -> Status: " . $res->status() . "\n";
    if ($res->successful()) {
        $json = $res->json();
        echo "Response: " . substr(json_encode($json), 0, 300) . "...\n";
    }
}
