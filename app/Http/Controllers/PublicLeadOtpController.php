<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use App\Models\Order;
use App\Models\User;
use Hash;
use Http;
use Illuminate\Http\Request;
use Log;
use Session;

class PublicLeadOtpController extends Controller
{
// 1. Send OTP and Create Lead Function
// 1. Send OTP and Create Lead Function
// public function sendOtpAndCreateLead(Request $request)
// {
//     // 1. Data Formatting
//     $mobile = $request->input('mobile');
//     $countryCode = $request->input('country_code'); // e.g., +91
//     $email = $request->input('email');

//     $phoneForAisensy = ltrim($countryCode . $mobile, '+'); // e.g. 919876543210

//     // ✅ 2. User Creation or Retrieval (PRIORITY: mobile match)
//     $user = User::where('mobile_no', $mobile)->first();

//     // fallback: email match
//     if (!$user && $email) {
//         $user = User::where('email', $email)->first();
//     }

//     if (!$user) {
//         $user = new User;
//         $user->email = $email;
//         $user->mobile_no = $mobile;
//         $user->name = $request->input('full_name') ?: 'user' . $mobile;
//         $user->countrycode = $countryCode;
//         $user->password = Hash::make('user@123');
//         $user->role_id = 2;
//         $user->verifyed = 0; // default
//         $user->save();
//     } else {
//         // Optional: keep info updated
//         if ($email && empty($user->email)) $user->email = $email;
//         if ($countryCode) $user->countrycode = $countryCode;
//         if ($request->input('full_name')) $user->name = $request->input('full_name');
//         $user->save();
//     }

//     // 3. Generate UKS Order ID
//     $latestOrder = Order::orderByDesc('id')->first();
//     $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
//     $newOrderId = 'UKS' . $newOrderNumber;

//     // 4. Create Lead
//     $leads = new Leads;
//     $leads->order_id = $newOrderId;
//     $leads->emp_id = $user->id;
//     $leads->project_title = "Academic Support Request";
//     $leads->pages = $request->input('pages', 0);
//     $leads->deadline = $request->input('delivery_date') ?: now();
//     $leads->message = $request->input('message');
//     $leads->service_type = $request->input('study_level');
//     $leads->page_url = $request->input('page_url');
//     $leads->save();

//     // 5. Create Order
//     $order = new Order;
//     $order->uid = 0;
//     $order->order_id = $newOrderId;
//     $order->lead_id = $leads->id;
//     $order->title = $leads->project_title;
//     $order->pages = $leads->pages;
//     $order->amount = $request->input('amount', 0);
//     $order->order_date = now();
//     $order->delivery_date = $leads->deadline;
//     $order->save();

//     // ✅ 6. Generate OTP & Save in DATABASE (update existing user otp)
//     $otpString = (string) rand(100000, 999999);
//     $user->otp = $otpString;
//     $user->save();

//     // ✅ 6.5 Thankyou route ke liye session set (aapka route isi pe depend hai)
//     Session::put('order_placed', true);

//     // 7. Aisensy API Call
//     try {
//         $response = Http::withHeaders([
//             'Authorization' => 'Bearer ' . env('AISENSY_API_KEY'),
//             'Content-Type' => 'application/json'
//         ])->post('https://backend.aisensy.com/campaign/t1/api/v2', [
//             "apiKey" => env('AISENSY_API_KEY'),
//             "campaignName" => env('AISENSY_CAMPAIGN'),
//             "destination" => $phoneForAisensy,
//             "userName" => env('AISENSY_USERNAME') ?: 'Customer',
//             "templateParams" => [$otpString],
//             "buttons" => [
//                 [
//                     "type" => "button",
//                     "sub_type" => "url",
//                     "index" => 0,
//                     "parameters" => [
//                         [
//                             "type" => "text",
//                             "text" => $otpString
//                         ]
//                     ]
//                 ]
//             ],
//             "source" => "otp-verification"
//         ]);

//         if ($response->successful()) {
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Lead created & OTP sent to WhatsApp!',
//             ]);
//         }

//         return response()->json([
//             'success' => false,
//             'message' => 'OTP sending failed: ' . $response->body()
//         ], 500);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'WhatsApp API Error: ' . $e->getMessage()
//         ], 500);
//     }
// }

public function sendOtpAndCreateLead(Request $request)
{
    // 1. Data Formatting
    $mobile = $request->input('mobile');
    $countryCode = $request->input('country_code'); // e.g., +91
    $email = $request->input('email');

    $phoneForAisensy = ltrim($countryCode . $mobile, '+'); // e.g. 919876543210

    // ✅ 2. User Creation or Retrieval (PRIORITY: mobile match)
    $user = User::where('mobile_no', $mobile)->first();

    // fallback: email match
    if (!$user && $email) {
        $user = User::where('email', $email)->first();
    }

    if (!$user) {
        $user = new User;
        $user->email = $email;
        $user->mobile_no = $mobile;
        $user->name = $request->input('full_name') ?: 'user' . $mobile;
        $user->countrycode = $countryCode;
        $user->password = Hash::make('user@123');
        $user->role_id = 2;
        $user->verifyed = 0; // default
        // ❌ OTP removed
        $user->save();
    } else {
        // Optional: keep info updated
        if ($email && empty($user->email)) $user->email = $email;
        if ($countryCode) $user->countrycode = $countryCode;
        if ($request->input('full_name')) $user->name = $request->input('full_name');
        // ❌ OTP removed
        $user->save();
    }

    // 3. Generate UKS Order ID
    $latestOrder = Order::orderByDesc('id')->first();
    $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
    $newOrderId = 'UKS' . $newOrderNumber;

    // 4. Create Lead
    $leads = new Leads;
    $leads->order_id = $newOrderId;
    $leads->emp_id = $user->id;
    $leads->project_title = "Academic Support Request";
    $leads->pages = $request->input('pages', 0);
    $leads->deadline = $request->input('delivery_date') ?: now();
    $leads->message = $request->input('message');
    $leads->service_type = $request->input('study_level');
    $leads->page_url = $request->input('page_url');
    $leads->save();

    // 5. Create Order
    $order = new Order;
    $order->uid = 0;
    $order->order_id = $newOrderId;
    $order->lead_id = $leads->id;
    $order->title = $leads->project_title;
    $order->pages = $leads->pages;
    $order->amount = $request->input('amount', 0);
    $order->order_date = now();
    $order->delivery_date = $leads->deadline;
    $order->save();

    // ✅ 6. Thankyou route ke liye session set (aapka route isi pe depend hai)
    Session::put('order_placed', true);

    // ✅ OTP + WhatsApp API call REMOVED
    return response()->json([
        'success' => true,
        'message' => 'Lead & Order created successfully!',
        'order_id' => $newOrderId,
        'lead_id' => $leads->id,
        'user_id' => $user->id,
    ]);
}


// 2. Verify OTP Function (Using Database)
public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
        'mobile' => 'required',
    ]);

    $otp = (string) $request->input('otp');
    $mobile = $request->input('mobile');

    // ✅ Find user by mobile
    $user = User::where('mobile_no', $mobile)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    // ✅ OTP missing means send-otp call nahi hua / DB update nahi hua
    if (!$user->otp) {
        return response()->json([
            'success' => false,
            'message' => 'No OTP request found. Please resend.'
        ], 400);
    }

    // ✅ Match OTP
    if ($user->otp === $otp) {
        $user->verifyed = 1;   // ✅ yahi chahiye tha
        $user->otp = null;     // ✅ OTP clear
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!',
            'verified' => true
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid OTP.'], 400);
}


}
