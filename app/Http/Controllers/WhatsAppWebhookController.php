<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsappMessage;
use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;

class WhatsAppWebhookController extends Controller
{


public function receive(Request $request)
{
    $data = $request->all();
    Log::info('AiSensy Webhook Received:', $data);

    $topic = $data['topic'] ?? null;

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

}
