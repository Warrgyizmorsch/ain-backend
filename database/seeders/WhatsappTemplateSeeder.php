<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappTemplate;
use App\Models\WhatsappSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $projectId = '64b7904a3702730b51b76dc1';
        $apiKey = '798699e56bbe28cc0b669';

        // 1. Sync Active WhatsApp Setting for AiSensy (+44 7917 481696)
        WhatsappSetting::query()->updateOrCreate(
            ['provider' => 'ai-sense'],
            [
                'settings' => [
                    'project_id' => $projectId,
                    'api_key' => $apiKey,
                    'webhook_url' => url('/api/webhooks/whatsapp'),
                ],
                'is_active' => true,
            ]
        );

        // 2. Clear template cache
        \Illuminate\Support\Facades\Cache::forget('aisensy_wa_templates_' . $projectId);

        // 3. Sync live approved templates from AiSensy if any exist
        if (Schema::hasTable('whatsapp_templates')) {
            WhatsappTemplate::truncate();
            try {
                $url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/";
                $res = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-AiSensy-Project-API-Pwd' => $apiKey,
                ])->timeout(8)->get($url);

                if ($res->successful()) {
                    $rawList = $res->json()['template'] ?? [];
                    foreach ($rawList as $t) {
                        if (($t['status'] ?? '') === 'APPROVED') {
                            $rawText = $t['text'] ?? $t['sample_text'] ?? '';
                            $cleanBody = trim(preg_replace('/\|\s*\[[^\]]+\]/', '', $rawText));
                            $langStr = $t['language'] ?? 'English (UK)';
                            $langCode = (stripos($langStr, 'UK') !== false || stripos($langStr, 'GB') !== false) ? 'en_GB' : 'en_US';

                            WhatsappTemplate::create([
                                'name' => $t['name'],
                                'title' => ucwords(str_replace('_', ' ', $t['name'])),
                                'category' => $t['category'] ?? 'UTILITY',
                                'language' => $langCode,
                                'body' => $cleanBody,
                                'footer_text' => 'Assignment In Need Team',
                                'variables' => [],
                                'status' => 'APPROVED',
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Seeder template sync error: ' . $e->getMessage());
            }
        }
    }
}
