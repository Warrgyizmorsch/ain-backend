<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\WhatsAppService;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;



class ChatController extends Controller
{
     public function showChat($phone = null)
    {
        $latestContacts = DB::table('whatsapp_messages as wm1')
        ->join(DB::raw('
        (
            SELECT phone, MAX(id) as max_id
            FROM whatsapp_messages
            GROUP BY phone
        ) as wm2
        '), function ($join) {
            $join->on('wm1.id', '=', 'wm2.max_id');
        })
    ->select('wm1.phone', 'wm1.name', 'wm1.message', 'wm1.created_at')
    ->orderBy('wm1.id', 'desc')
    ->get();

    $phones = $latestContacts->pluck('phone')->toArray();
    $usersByPhone = User::whereIn('mobile_no', $phones)->get()->keyBy('mobile_no');
    $chatContacts = $latestContacts->map(function ($contact) use ($usersByPhone) {
    $user = $usersByPhone[$contact->phone] ?? null;
    $unreadCount = WhatsappMessage::where('phone', $contact->phone)
            ->where('direction', 'inbound')
            ->where('status', '!=', 'read')
            ->count();
    // $lastSeen = Carbon::parse($contact->created_at);
    // $isActive = $lastSeen->gt(now()->subMinutes(60)); 
    // $status = $unreadCount > 0 ? 'unread' : 'read';
    //     if ($isActive) {
    //         $status = 'active';
    //     }

        return [
            'phone' => $contact->phone,
            'name' => $user?->name ?? $contact->name,
            'last_message' => $contact->message,
            'last_seen' => $contact->created_at,
            'avatar' => $user?->avatar ?? asset('./assets/media/avatars/blank.png'),
            'unread_count' => $unreadCount,
            'is_unread' => $unreadCount > 0,
            'status' => $unreadCount > 0 ? 'unread' : 'read',
        ];
    });
    $contacts = [
        'all' => $chatContacts,
        'read' => $chatContacts->where('status', 'read')->values(),
        'unread' => $chatContacts->where('status', 'unread')->values(),
        // 'active' => $chatContacts->where('status', 'active')->values(),
        // 'active' => $chatContacts->filter(function ($c) {
        //     return true; 
        // })->values(),
    ];

    // $contacts = $chatContacts;
    $chatUser = $this->getChatUserByPhone($phone);
    //   echo "<pre>";print_r($contacts);exit;
        return view('whatsapp-api.chatnew', compact('contacts', 'chatUser', 'phone'));
    }


    public function send(Request $request, WhatsAppService $whatsapp)
    {
        $phone = $request->input('phone'); // Example: 919664100138
        $message = $request->input('message'); // Example: Testing By Prakash
        $mediaType = $request->input('media_type');
        $mediaFile = $request->file('media');
        $caption = $request->input('caption');
        $auth = Auth::user();

        try {
        if ($mediaFile && $mediaType) {
            // Upload and send media
            $response = $whatsapp->sendMediaMessage($phone, $mediaFile, $mediaType, $caption);
        } else {
            // Send text message
            $response = $whatsapp->sendTextMessage($phone, $message);
        }

        if (isset($response['messages'][0]['id'])) {
            $messageData = WhatsappMessage::create([
                'phone' => $phone,
                'name' => $auth->name ?? 'Unknown',
                'message' => $caption ?? $message ?? '',
                'created_at' => now()->toISOString(),
                'direction' => 'outbound',
                'wa_message_id' => $response['messages'][0]['id'],
                'status' => 'sent'
            ]);

            event(new MessageSent($messageData));

            return response()->json(['success' => true, 'message_id' => $messageData->wa_message_id]);
        } else {
            $error = $response['error']['message'] ?? 'Unknown error.';
            return response()->json(['success' => false, 'error' => $error], 400);
        }

    } catch (\Exception $e) {
        Log::error('WhatsApp send failed', ['exception' => $e]);
        return response()->json(['success' => false, 'error' => 'Something went wrong.'], 500);
    }

}

    public function getChatUserByPhone($phone)
    {
      
    $contact = WhatsappMessage::where('phone', $phone)->orderByDesc('id')->first();
    $user = User::where('mobile_no', $phone)->first();

    $chats = WhatsappMessage::where('phone', $phone)
        ->orderBy('created_at')
        ->get()
        ->map(function ($msg) {
            return [
                'direction' => $msg->direction === 'inbound' ? 'inbound' : 'outbound',
                'message' => $msg->message,
                'created_at' => Carbon::parse($msg->created_at)->format('g:i A'),
                'status' => $msg->status,
                'id' => $msg->wa_message_id,
            ];
        });

    return [
        'id' => $user->id ?? null,
        'name' => $user->name ?? $contact->name ?? $phone,
        'Phone' => $phone,
        'unread_count' => 2,
        'is_unread' => false,
        'lastseen' => 'Last seen today at ' . now()->subMinutes(rand(5, 180))->format('g:i A'),
        'message' => $chats->last()['message'] ?? '',
        'time' => $chats->last()['time'] ?? '',
        'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
        'extra' => [
            'Email' => $user->email ?? 'N/A',
            'Opted In' => $user->opted_in ?? false,
            'Status' => 'Closed',
            'Last Active' => '5/1/2025, 1:00 PM',
            'Template Messages' => 0,
            'Session Messages' => 20,
            'Unresolved Queries' => 0,
            'Source' => 'ORGANIC',
            'First Message' => '.....',
            'WA Conversation' => 'Inactive',
            'MAU Status' => 'Active',
            'Incoming' => 'Allowed',
        ],
        'chats' => $chats,
    ];
    }
    public function markAsRead(Request $request)
    {
        $phone = $request->phone;

       WhatsappMessage::where('phone', $phone)
        ->where('direction', 'inbound')
        ->where('status', '!=', 'read')
        ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }


}
