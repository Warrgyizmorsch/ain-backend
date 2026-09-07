<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projectId = '64b7904a3702730b51b76dc1';
$apiKey = '798699e56bbe28cc0b669';
$url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/";
$res = \Illuminate\Support\Facades\Http::withHeaders([
    'Accept' => 'application/json',
    'X-AiSensy-Project-API-Pwd' => $apiKey,
])->get($url);

echo "Templates in 696 Project:\n";
$templates = $res->json()['template'] ?? [];
foreach ($templates as $t) {
    echo "- Name: {$t['name']} | Status: {$t['status']} | Lang: {$t['language']} | Params: " . ($t['total_parameters'] ?? '0') . "\n";
    echo "  Text: " . substr(str_replace("\n", " ", $t['text'] ?? $t['sample_text'] ?? ''), 0, 100) . "...\n";
}
