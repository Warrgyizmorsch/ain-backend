<?php 

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $url;
    protected $apiKey;

    public function __construct()
    {
        $waSetting = \App\Models\WhatsappSetting::where('is_active', true)->first();
        $settings = $waSetting?->settings ?? [];

        $projectId = trim((string) ($settings['project_id'] ?? env('AISENSY_PROJECT_ID') ?? ''));
        if (!empty($projectId)) {
            $this->url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/messages";
        } elseif (!empty($settings['api_url'])) {
            $this->url = $settings['api_url'];
        } else {
            $this->url = env('AISENSY_API_URL');
        }

        $this->apiKey = !empty($settings['api_key']) ? $settings['api_key'] : env('AISENSY_API_KEY');
    }

    public function sendTextMessage($to, $message)
    {
        $cleanTo = ltrim(preg_replace('/\D+/', '', (string) $to), '+');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-AiSensy-Project-API-Pwd' => $this->apiKey,
        ])->post($this->url, [
            'to' => $cleanTo,
            'type' => 'text',
            'recipient_type' => 'individual',
            'text' => [
                'body' => $message,
            ],
        ]);

        if (! $response->successful()) {
            Log::warning('WhatsAppService::sendTextMessage failed', [
                'to' => $cleanTo,
                'status' => $response->status(),
                'response' => $response->json() ?: $response->body(),
            ]);
        }

        return $response->json();
    }

    public function sendMediaMessage($to, $mediaFile, $mediaType, $caption = '')
    {
        $cleanTo = ltrim(preg_replace('/\D+/', '', (string) $to), '+');

        // Upload media to a public path and get the public URL
        $storedPath = $mediaFile->store('uploads/whatsapp', 'public');
        $mediaUrl = asset('storage/' . $storedPath);

        // Prepare the base payload
        $payload = [
            'to' => $cleanTo,
            'type' => $mediaType,
        ];

        // Add media type-specific data
        switch ($mediaType) {
            case 'image':
            case 'video':
                $payload[$mediaType] = [
                    'caption' => $caption ?? '',
                    'link' => $mediaUrl,
                ];
                break;

            case 'document':
                $payload[$mediaType] = [
                    'caption' => $caption ?? '',
                    'link' => $mediaUrl,
                    'filename' => $mediaFile->getClientOriginalName(),
                ];
                break;

            case 'audio':
                $payload[$mediaType] = [
                    'link' => $mediaUrl,
                ];
                break;

            default:
                throw new \Exception("Unsupported media type: $mediaType");
        }

        // Send the request
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-AiSensy-Project-API-Pwd' => $this->apiKey,
        ])->post($this->url, $payload);

        if (! $response->successful()) {
            Log::warning('WhatsAppService::sendMediaMessage failed', [
                'to' => $cleanTo,
                'type' => $mediaType,
                'status' => $response->status(),
                'response' => $response->json() ?: $response->body(),
            ]);
        }

        return $response->json();
    }

}
