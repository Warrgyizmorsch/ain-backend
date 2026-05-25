<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;


class WhatsappController extends Controller
{
    public function whatsapp()
    {
        // $response = $this->getWhatsappTemplates(); 
        // $templates = $response['data'];
        return view('whatsapp.whatsapp');
    }

    public function uploagImages(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'No image file provided'], 400);
            }
        
            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file upload'], 400);
            }
            $jwtToken = $this->loginWhatsapp();
            if (!$jwtToken) {
                return response()->json(['error' => 'Unable to authenticate with WhatsApp API'], 401);
            }
            $response = Http::withHeaders([
                'Authorization' => $jwtToken, 
                'Accept' => 'application/json',
            ])->attach(
                'file',                            
                $file->getPathname(),            
                $file->getClientOriginalName()      
            )->post('https://apis.rmlconnect.net/wba/media/v1/get-media-id'); 
        
            if ($response->successful()) {
                $jsonResponse = $response->json();
                return response()->json([
                    'message' => 'File uploaded successfully',
                    'data' => $jsonResponse
                ], 200);
            } else {
                \Log::error('File upload failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return response()->json([
                    'error' => 'File upload failed',
                    'details' => $response->json() 
                ], $response->status());
            }
        } catch (\Exception $e) {
            \Log::error('Exception occurred during file upload', ['exception' => $e]);
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
        
    }

    private function loginWhatsapp()
    {
        try {
            $response = Http::post('https://apis.rmlconnect.net/auth/v1/login/', [
                'username' => env('WHATSAPP_API_USERNAME', 'Warrgyiz'),
                'password' => env('WHATSAPP_API_PASSWORD', 'Warrg@rm99'),
            ]);

            if ($response->successful()) {
                $res = $response->json();
                return $res['JWTAUTH'] ?? null;
            }
            \Log::error('WhatsApp API login failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('WhatsApp API login exception', ['exception' => $e]);
            return null;
        }
    }

    private function getWhatsappTemplates() 
    {
        try {
            $jwtToken = $this->loginWhatsapp();

            if (!$jwtToken) {
                \Log::error("Failed to retrieve JWT token.");
                return null;
            }
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' =>  $jwtToken,
            ])->get('https://apis.rmlconnect.net/wba/templates');

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error("Failed to fetch templates: " . $response->body());
            return null;

        } catch (\Exception $e) {
            \Log::error("Exception while fetching templates: " . $e->getMessage());
            return null;
        }
    }

 
    
// public function sendMessage(Request $request)
// {
//     // Gather inputs directly from the request
//         $phone = '+919610092299';
//         $templateName = 'api_testing';
//         $langCode = 'en'; // Default to 'en_GB' if not provided
//         $mediaId = '561226669625820';
//         $fileName = 'pngaaa.com-687302.png';

//     // Prepare API URL and JWT token
//     $apiUrl = 'https://apis.rmlconnect.net/wba/v1/messages';
//     $jwtToken = $this->loginWhatsapp(); // Function to retrieve JWT token

//     // Build the complete payload
//     $payload = [
//         "phone" => $phone,
//         "media" => [
//             "type" => "media_template",
//             "template_name" => $templateName,
//             "lang_code" => $langCode,
//             "header" => [
//                 [
//                     "image" => [
//                         "id" => $mediaId,
//                         "file_name" => $fileName
//                     ]
//                 ]
//             ],
//             "body" => [
//                 [
//                     "text" => 'anjali'
//                 ]
//             ]
//         ]
//     ];

//     try {
//         // Send the HTTP POST request
//         $client = new \GuzzleHttp\Client();
//         $response = $client->post($apiUrl, [
//             'headers' => [
//                 'Authorization' => $jwtToken,
//                 'Content-Type'  => 'application/json',
//             ],
//             'json' => $payload
//         ]);

//         // Return the success response
//         return response()->json(json_decode($response->getBody()->getContents()), 200);
//     } catch (\GuzzleHttp\Exception\ClientException $e) {
//         // Handle ClientException errors
//         return response()->json([
//             'error' => 'Failed to send message',
//             'details' => json_decode($e->getResponse()->getBody()->getContents(), true),
//             'status_code' => $e->getResponse()->getStatusCode(),
//         ], 401);
//     } catch (\Exception $e) {
//         // Handle general errors
//         return response()->json([
//             'error' => 'Failed to send message',
//             'details' => $e->getMessage(),
//         ], 500);
//     }
// }
public function index()
{
  // Get all unique phone contacts
    $latestContacts = DB::table('whatsapp_messages as wm1')
        ->join(DB::raw('
            (
                SELECT phone, MAX(id) as max_id
                FROM whatsapp_messages
                GROUP BY phone
            ) as wm2
        '), function($join) {
            $join->on('wm1.id', '=', 'wm2.max_id');
        })
        ->select('wm1.phone', 'wm1.name') // or add more fields like 'message', 'created_at'
        ->orderBy('wm1.id', 'desc')
        ->get();

    // Get users matching those phone numbers
    $usersByPhone = User::whereIn('mobile_no', $latestContacts->pluck('phone'))
    ->get()
    ->keyBy('mobile_no');


    // Format each contact
    $chatUsers = $latestContacts->map(function ($contact) use ($usersByPhone) {        
    $phone = $contact->phone;
    $user = $usersByPhone->get($phone);

    // Get all messages related to that phone
    $chats = DB::table('whatsapp_messages')
        ->where('phone', $phone)
        ->orderBy('created_at')
        ->get()
        ->map(function ($msg) {
            return [
                'from' => $msg->direction === 'inbound' ? 'user' : 'me',
                'message' => $msg->message,
                'time' => Carbon::parse($msg->created_at)->format('g:i A'),
                'status' => $msg->status, // or use a real field if you store status
            ];
        });

        return [
            'id' => $user->id ?? null,
            'name' => $user->name ?? $contact->name ?? $phone,
            'Phone' => $phone,
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
    });
    $usersArray = $chatUsers->toArray();

    //  echo "<pre>";print_r($usersArray);exit;
    return view('whatsapp-api.index',compact('usersArray'));
}

public function campaigns()
{
    return view('whatsapp-api.campaigns');
    
}

public function templateMessage ()
{
    return view('whatsapp-api.template');

}

public function chatSetting()
{
    return view('whatsapp-api.chat-setting');

}
}
