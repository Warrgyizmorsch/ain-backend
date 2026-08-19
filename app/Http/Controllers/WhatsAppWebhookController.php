<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Leads;
use App\Models\WhatsappChatLabel;
use App\Models\WhatsappChatContactLabel;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;

class WhatsAppWebhookController extends Controller
{

public function receive(Request $request)
{
    $data = $request->all();
    Log::info('WhatsApp Webhook Received:', $data);

    // ----------------------------------------------------
    // 0. Twilio Webhook Handling (Status Callback & Inbound)
    // ----------------------------------------------------
    if (($request->has('MessageStatus') || $request->has('SmsStatus')) && ($request->has('MessageSid') || $request->has('SmsMessageSid'))) {
        $waMessageId = $request->input('MessageSid') ?? $request->input('SmsMessageSid');
        $status = strtolower((string) ($request->input('MessageStatus') ?? $request->input('SmsStatus')));

        if ($status !== 'received') {
            $message = WhatsappMessage::where('wa_message_id', $waMessageId)->first();

            if ($message) {
                $currentStatus = strtolower((string) $message->status);
                $terminalStatuses = ['failed', 'undelivered'];

                if (in_array($currentStatus, $terminalStatuses, true) && ! in_array($status, ['read', 'delivered'], true)) {
                    Log::info('WhatsApp message status ignored because current status is terminal', [
                        'wa_message_id' => $waMessageId,
                        'current_status' => $currentStatus,
                        'incoming_status' => $status,
                    ]);

                    return response()->json(['status' => 'ignored'], 200);
                }

                $message->status = $status;
                $message->save();

                event(new MessageStatusUpdated($message));
                Log::info('WhatsApp message status updated', [
                    'wa_message_id' => $waMessageId,
                    'status' => $status,
                    'message_id' => $message->id,
                ]);
            } else {
                Log::warning('WhatsApp status callback without matching message', [
                    'wa_message_id' => $waMessageId,
                    'status' => $status,
                ]);
            }

            return response()->json(['status' => 'updated'], 200);
        }
    }

    if ($request->has('From') && ($request->has('Body') || (int) $request->input('NumMedia', 0) > 0)) {
        $phone = str_replace('whatsapp:', '', $request->input('From'));
        $text = (string) ($request->input('Body') ?? '');
        $waMessageId = $request->input('MessageSid') ?? $request->input('SmsMessageSid');
        $userName = $request->input('ProfileName') ?: $phone;
        $numMedia = (int) $request->input('NumMedia', 0);
        $messageText = $this->bodyLooksLikeFileName($text) ? '' : $text;
        $createdMessages = collect();

        if ($numMedia > 0) {
            for ($index = 0; $index < $numMedia; $index++) {
                $mediaUrl = $request->input("MediaUrl{$index}");
                $mediaContentType = $request->input("MediaContentType{$index}");
                $mediaType = $this->mediaTypeFromContentType($mediaContentType);
                $mediaSid = $numMedia > 1 ? "{$waMessageId}_{$index}" : $waMessageId;
                $storedMedia = $this->storeTwilioMedia($mediaUrl, $mediaContentType, $mediaSid);
                $mediaName = $this->mediaNameFromBodyOrUrl($text, $storedMedia['media_url'] ?? $mediaUrl, $mediaType);

                $createdMessages->push(WhatsappMessage::create([
                    'phone' => $phone,
                    'name' => $userName,
                    'message' => $index === 0 ? ($messageText ?: '') : '',
                    'direction' => 'inbound',
                    'wa_message_id' => $mediaSid,
                    'status' => 'received',
                    'media_url' => $storedMedia['media_url'] ?? $mediaUrl,
                    'media_type' => $mediaType,
                    'media_name' => $storedMedia['media_name'] ?? $mediaName,
                    'media_size' => $storedMedia['media_size'] ?? null,
                ]));
            }
        } else {
            $createdMessages->push(WhatsappMessage::create([
                'phone' => $phone,
                'name' => $userName,
                'message' => $messageText ?: '',
                'direction' => 'inbound',
                'wa_message_id' => $waMessageId,
                'status' => 'received',
            ]));
        }

        Cache::forget($this->typingCacheKey($phone));

        $createdMessages->each(fn (WhatsappMessage $message) => event(new MessageSent($message)));

        return response()->json(['status' => 'received'], 200);
    }

    // ----------------------------------------------------
    // AiSensy / Standard Webhook Event Routing
    // ----------------------------------------------------
    $topic = $data['topic'] ?? null;
    $eventName = strtolower((string) ($topic ?? $data['event'] ?? $data['type'] ?? ''));

    // Typing Event
    if (str_contains($eventName, 'typing')) {
        $typingPhone = $data['phone']
            ?? $data['from']
            ?? $data['data']['phone']
            ?? $data['data']['phone_number']
            ?? $data['data']['message']['phone_number']
            ?? null;

        if ($typingPhone) {
            $typingPhone = str_replace('whatsapp:', '', $typingPhone);
            Cache::put($this->typingCacheKey($typingPhone), true, now()->addSeconds(8));
        }

        return response()->json(['status' => 'typing'], 200);
    }

    // ----------------------------------------------------
    // 1. Hook: message.created OR message.sender.user (Incoming & Outgoing Messages)
    // ----------------------------------------------------
    if (in_array($topic, ['message.created', 'message.sender.user'], true) || str_contains($eventName, 'message.created') || str_contains($eventName, 'message.sender.user')) {
        $messageData = $data['data']['message'] ?? $data['data'] ?? [];

        $phone = $messageData['phone_number'] ?? $messageData['phone'] ?? $messageData['to'] ?? $messageData['from'] ?? null;
        $sender = strtoupper((string) ($messageData['sender'] ?? ($topic === 'message.sender.user' ? 'USER' : 'USER')));
        $userName = $messageData['userName'] ?? $messageData['name'] ?? 'WhatsApp User';
        $waMessageId = $messageData['messageId'] ?? $messageData['id'] ?? null;

        // Parse text content (supports direct text, button text, or interactive reply)
        $text = $messageData['message_content']['text'] 
            ?? $messageData['text']['body'] 
            ?? $messageData['text'] 
            ?? $messageData['button_reply']['title'] 
            ?? $messageData['interactive']['button_reply']['title'] 
            ?? $messageData['interactive']['list_reply']['title'] 
            ?? '';

        // Media fields
        $mediaUrl = $messageData['message_content']['media_url'] 
            ?? $messageData['media_url'] 
            ?? $messageData['image']['link'] 
            ?? $messageData['video']['link'] 
            ?? $messageData['document']['link'] 
            ?? $messageData['audio']['link'] 
            ?? null;

        $mediaType = $messageData['message_content']['media_type'] 
            ?? $messageData['media_type'] 
            ?? $messageData['type'] 
            ?? ($mediaUrl ? 'image' : null);

        $mediaName = $messageData['message_content']['media_name'] 
            ?? $messageData['media_name'] 
            ?? $messageData['document']['filename'] 
            ?? null;

        if ($phone && ($text !== '' || $mediaUrl !== '')) {
            $phone = ltrim(str_replace('whatsapp:', '', $phone), '+');

            if ($sender === 'USER' || $topic === 'message.sender.user') {
                // Check if already stored to avoid duplicate webhook processing
                $existingMessage = $waMessageId ? WhatsappMessage::where('wa_message_id', $waMessageId)->first() : null;

                if (! $existingMessage) {
                    $whatsappMessage = WhatsappMessage::create([
                        'phone' => $phone,
                        'name' => $userName,
                        'message' => (string) $text,
                        'direction' => 'inbound',
                        'wa_message_id' => $waMessageId,
                        'status' => 'received',
                        'media_url' => $mediaUrl,
                        'media_type' => $mediaType,
                        'media_name' => $mediaName,
                    ]);

                    Cache::forget($this->typingCacheKey($phone));
                    event(new MessageSent($whatsappMessage));
                    Log::info('AiSensy Inbound WhatsApp message created', ['message_id' => $whatsappMessage->id, 'phone' => $phone]);
                }
            } else {
                // Outbound message from campaign/bot
                if ($waMessageId && ! WhatsappMessage::where('wa_message_id', $waMessageId)->exists()) {
                    WhatsappMessage::create([
                        'phone' => $phone,
                        'name' => 'System',
                        'message' => (string) $text,
                        'direction' => 'outbound',
                        'wa_message_id' => $waMessageId,
                        'status' => 'sent',
                        'media_url' => $mediaUrl,
                        'media_type' => $mediaType,
                        'media_name' => $mediaName,
                    ]);
                }
            }
        }
    }

    // ----------------------------------------------------
    // 2. Hook: message.status.updated (Message Status: SENT / DELIVERED / READ / FAILED)
    // ----------------------------------------------------
    if ($topic === 'message.status.updated' || str_contains($eventName, 'status')) {
        $msg = $data['data']['message'] ?? $data['data'] ?? [];
        $waMessageId = $msg['messageId'] ?? $msg['id'] ?? null;
        $status = strtolower((string) ($msg['status'] ?? ''));

        if ($waMessageId && $status !== '') {
            $message = WhatsappMessage::where('wa_message_id', $waMessageId)->first();

            if ($message) {
                $message->status = $status;
                $message->save();

                event(new MessageStatusUpdated($message));
                Log::info('AiSensy message status updated', [
                    'wa_message_id' => $waMessageId,
                    'phone' => $message->phone,
                    'status' => $status,
                ]);
            } else {
                Log::warning('AiSensy message status update without matching message', [
                    'wa_message_id' => $waMessageId,
                    'status' => $status,
                ]);
            }
        }
    }

    // ----------------------------------------------------
    // 3. Hook: contact.first_message.updated (New Lead Creation on First Contact)
    // ----------------------------------------------------
    if ($topic === 'contact.first_message.updated' || str_contains($eventName, 'first_message')) {
        $contactData = $data['data']['contact'] ?? $data['data'] ?? [];
        $contactPhone = $contactData['phone_number'] ?? $contactData['phone'] ?? null;
        $contactName = $contactData['name'] ?? $contactData['userName'] ?? 'WhatsApp Lead';

        if ($contactPhone) {
            $cleanPhone = ltrim(str_replace('whatsapp:', '', $contactPhone), '+');

            // Check if lead already exists
            $existingLead = Leads::where('mobile', $cleanPhone)
                ->orWhere('mobile', '+' . $cleanPhone)
                ->first();

            if (! $existingLead) {
                Leads::create([
                    'user_name' => $contactName,
                    'mobile' => $cleanPhone,
                    'l_status' => 'New Lead',
                    'lead_source' => 'WhatsApp',
                    'message' => 'First message received via WhatsApp AiSensy',
                ]);

                Log::info('New Lead automatically created from WhatsApp first_message hook', [
                    'phone' => $cleanPhone,
                    'name' => $contactName,
                ]);
            }
        }
    }

    // ----------------------------------------------------
    // 4. Hook: contact.tag.updated (Sync AiSensy Tags with WhatsApp Chat Labels)
    // ----------------------------------------------------
    if ($topic === 'contact.tag.updated' || str_contains($eventName, 'tag')) {
        $contactData = $data['data']['contact'] ?? $data['data'] ?? [];
        $contactPhone = $contactData['phone_number'] ?? $contactData['phone'] ?? null;
        $tags = (array) ($contactData['tags'] ?? $contactData['tag'] ?? $data['tag'] ?? []);

        if ($contactPhone && ! empty($tags)) {
            $cleanPhone = ltrim(str_replace('whatsapp:', '', $contactPhone), '+');

            foreach ($tags as $tagName) {
                $tagName = trim((string) $tagName);
                if ($tagName === '') {
                    continue;
                }

                $label = WhatsappChatLabel::firstOrCreate(
                    ['name' => $tagName],
                    ['color' => '#25d366']
                );

                WhatsappChatContactLabel::firstOrCreate([
                    'phone' => $cleanPhone,
                    'label_id' => $label->id,
                ]);
            }

            Log::info('AiSensy contact tags synced with chat labels', [
                'phone' => $cleanPhone,
                'tags' => $tags,
            ]);
        }
    }

    return response()->json(['status' => 'received'], 200);
}

private function typingCacheKey(string $phone): string
{
    return 'whatsapp_typing:' . preg_replace('/[^0-9+]/', '', $phone);
}

private function mediaTypeFromContentType(?string $contentType): ?string
{
    if (! $contentType) {
        return null;
    }

    return match (true) {
        str_starts_with($contentType, 'image/') => 'image',
        str_starts_with($contentType, 'video/') => 'video',
        str_starts_with($contentType, 'audio/') => 'audio',
        default => 'document',
    };
}

private function mediaNameFromBodyOrUrl(?string $body, ?string $url, ?string $mediaType): ?string
{
    $body = trim((string) $body);

    if ($this->bodyLooksLikeFileName($body)) {
        return $body;
    }

    return $this->mediaNameFromUrl($url, $mediaType);
}

private function mediaNameFromUrl(?string $url, ?string $mediaType): ?string
{
    if (! $url) {
        return null;
    }

    $path = parse_url($url, PHP_URL_PATH);
    $name = $path ? basename($path) : null;

    if ($name && $name !== '/') {
        return $name;
    }

    return $mediaType ? 'WhatsApp ' . ucfirst($mediaType) : 'WhatsApp attachment';
}

private function bodyLooksLikeFileName(?string $body): bool
{
    if (! $body) {
        return false;
    }

    $extension = strtolower(pathinfo(trim($body), PATHINFO_EXTENSION));

    return in_array($extension, [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif', 'bmp',
        'mp4', 'mov', 'avi', 'mkv', 'webm',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', 'csv',
        'mp3', 'ogg', 'wav', 'm4a',
    ], true);
}

private function storeTwilioMedia(?string $mediaUrl, ?string $contentType, ?string $messageSid): array
{
    if (! $mediaUrl) {
        return [];
    }

    $setting = WhatsappSetting::query()->where('is_active', true)->where('provider', 'twilio')->first();
    $config = $setting?->settings ?? [];
    $sid = $config['account_sid'] ?? null;
    $token = $config['auth_token'] ?? null;

    if (! $sid || ! $token) {
        return [];
    }

    try {
        Log::info('Twilio media download started', [
            'media_url' => $mediaUrl,
            'content_type' => $contentType,
            'message_sid' => $messageSid,
        ]);

        $response = Http::withBasicAuth($sid, $token)
            ->withOptions(['allow_redirects' => true])
            ->timeout(30)
            ->get($mediaUrl);

        if (! $response->successful()) {
            Log::warning('Twilio media download failed', [
                'media_url' => $mediaUrl,
                'status' => $response->status(),
                'content_type' => $contentType,
                'response_content_type' => $response->header('Content-Type'),
            ]);

            return [];
        }

        $content = $response->body();
        $responseContentType = $response->header('Content-Type');
        $extension = $this->extensionFromContentType($contentType ?: $responseContentType);
        $fileName = uniqid('wa_in_', true) . '.' . $extension;
        $destinationPath = base_path('assets/media/whatsapp');

        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        file_put_contents($destinationPath . DIRECTORY_SEPARATOR . $fileName, $content);

        Log::info('Twilio media downloaded', [
            'media_url' => 'assets/media/whatsapp/' . $fileName,
            'content_type' => $contentType,
            'response_content_type' => $responseContentType,
            'size' => strlen($content),
        ]);

        return [
            'media_url' => 'assets/media/whatsapp/' . $fileName,
            'media_name' => ($messageSid ?: 'whatsapp-media') . '.' . $extension,
            'media_size' => strlen($content),
        ];
    } catch (\Throwable $exception) {
        Log::error('Twilio media download exception', [
            'media_url' => $mediaUrl,
            'exception' => $exception->getMessage(),
        ]);

        return [];
    }
}

private function extensionFromContentType(?string $contentType): string
{
    $contentType = strtolower((string) $contentType);
    $contentType = trim(explode(';', $contentType)[0]);

    return match ($contentType) {
        'image/jpeg', 'image/jpg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        'image/heic' => 'heic',
        'image/heif' => 'heif',
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
        'video/webm' => 'webm',
        'audio/mpeg' => 'mp3',
        'audio/ogg' => 'ogg',
        'audio/wav', 'audio/x-wav' => 'wav',
        'audio/mp4', 'audio/x-m4a' => 'm4a',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'text/plain' => 'txt',
        'text/csv' => 'csv',
        'application/zip' => 'zip',
        'application/x-rar-compressed' => 'rar',
        default => 'bin',
    };
}

}
