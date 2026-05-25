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
        $this->url = env('AISENSY_API_URL');
        $this->apiKey = env('AISENSY_API_KEY');
    }

    public function sendTextMessage($to, $message)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-AiSensy-Project-API-Pwd' => $this->apiKey,
        ])->post($this->url, [
            'to' => $to,
            'type' => 'text',
            'recipient_type' => 'individual',
            'text' => [
                'body' => $message
            ]
        ]);
        
        return $response->json();
    }
   public function sendMediaMessage($to, $mediaFile, $mediaType, $caption = '')
    {
        // Upload media to a public path and get the public URL
        $storedPath = $mediaFile->store('uploads/whatsapp', 'public');
        $mediaUrl = asset('storage/' . $storedPath);

        // Prepare the base payload
        $payload = [
            'to' => $to,
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

        return $response->json();
    }

}
