<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsappMessage;
use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;

class WhatsAppWebhookController extends Controller
{


public function receive(Request $request)
{
    $data = $request->all();
    Log::info('WhatsApp Webhook Received:', $data);

    if ($request->has('From') && ($request->has('Body') || (int) $request->input('NumMedia', 0) > 0)) {
        $phone = str_replace('whatsapp:', '', $request->input('From'));
        $text = $request->input('Body', '');
        $waMessageId = $request->input('MessageSid') ?? $request->input('SmsMessageSid');
        $userName = $request->input('ProfileName') ?: $phone;
        $mediaUrl = $request->input('MediaUrl0');
        $mediaContentType = $request->input('MediaContentType0');
        $mediaType = $this->mediaTypeFromContentType($mediaContentType);
        $mediaName = $this->mediaNameFromUrl($mediaUrl, $mediaType);

        $whatsappMessage = WhatsappMessage::create([
            'phone' => $phone,
            'name' => $userName,
            'message' => $text,
            'direction' => 'inbound',
            'wa_message_id' => $waMessageId,
            'status' => 'received',
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
            'media_name' => $mediaName,
        ]);
        Cache::forget($this->typingCacheKey($phone));

        event(new MessageSent($whatsappMessage));

        return response()->json(['status' => 'received'], 200);
    }

    if (($request->has('MessageStatus') || $request->has('SmsStatus')) && ($request->has('MessageSid') || $request->has('SmsMessageSid'))) {
        $waMessageId = $request->input('MessageSid') ?? $request->input('SmsMessageSid');
        $status = strtolower($request->input('MessageStatus') ?? $request->input('SmsStatus'));

        $message = WhatsappMessage::where('wa_message_id', $waMessageId)->first();

        if ($message) {
            $message->status = $status;
            $message->save();

            event(new MessageStatusUpdated($message));
        }

        return response()->json(['status' => 'updated'], 200);
    }

    $topic = $data['topic'] ?? null;
    $eventName = strtolower((string) ($topic ?? $data['event'] ?? $data['type'] ?? ''));

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

    // 1. Handle inbound messages (from user)
    if ($topic === 'message.created' && isset($data['data']['message'])) {
        $messageData = $data['data']['message'];

        $phone = $messageData['phone_number'] ?? null;
        $text = $messageData['message_content']['text'] ?? '';
        $sender = $messageData['sender'] ?? '';
        $userName = $messageData['userName'] ?? 'Unknown';
        $waMessageId = $messageData['messageId'] ?? null;

        if ($sender === 'USER' && $phone && $text) {
            $whatsappMessage = WhatsappMessage::create([
                'phone' => $phone,
                'name' => $userName,
                'message' => $text,
                'direction' => 'inbound',
                'wa_message_id' => $waMessageId,
            ]);
            Cache::forget($this->typingCacheKey($phone));

            event(new MessageSent($whatsappMessage));
            Log::info('Broadcasting message event', ['message' => $whatsappMessage]);
        }
    }

    // 2. Handle message status update (e.g., delivered, read)
    if ($topic === 'message.status.updated') {
        $msg = $data['data']['message'];
        $waMessageId = $msg['messageId'];
        $status = strtolower($msg['status']); // SENT / DELIVERED / READ

        $message = WhatsappMessage::where('wa_message_id', $waMessageId)->first();

        if ($message) {
            $message->status = $status;
            $message->save();

            event(new MessageStatusUpdated($message)); // Make sure this event exists
            Log::info('Broadcasting message status update event', [
                'id' => $message->wa_message_id,
                'phone' => $message->phone,
                'status' => $status,
            ]);
        } else {
            Log::warning('No matching message found for status update', [
                'wa_message_id' => $waMessageId,
                'status' => $status,
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

}
