<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projectId = '67e109077c4b230bed2fb1ff';
$apiKey = '222488aa8678e32a9069d';
$url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/";

$res = \Illuminate\Support\Facades\Http::withHeaders([
    'Accept' => 'application/json',
    'X-AiSensy-Project-API-Pwd' => $apiKey,
])->get($url);

$templates = $res->json()['template'] ?? [];
foreach ($templates as $t) {
    if (($t['status'] ?? '') === 'APPROVED') {
        echo "========================================\n";
        echo "Name: {$t['name']}\n";
        echo "Language: {$t['language']}\n";
        echo "Category: {$t['category']}\n";
        echo "Total Params: " . ($t['total_parameters'] ?? 'none') . "\n";
        echo "Text:\n{$t['text']}\n";
        echo "Sample Text:\n{$t['sample_text']}\n";
    }
}
