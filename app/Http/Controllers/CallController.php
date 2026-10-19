<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Writer;
use App\Models\User;

class CallController extends Controller
{
    public function getAppTokens()
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->post('https://samwad.iotcom.io/api/applogin', [
                'username' => 'warrgyi',
                'password' => 'Warrgyi@123',
            ]);

            if ($response->successful()) {
                return $response->json(); 
            } else {
                Log::error('Samwad applogin failed: ' . $response->body());
                return null; 
            }
        } catch (\Exception $e) {
            Log::error('Samwad applogin exception: ' . $e->getMessage());
            return null;
        }
    }

    public function Accesstoken()
    {
        $tokens = $this->getAppTokens();
        if ($tokens) {
            if (!empty($tokens['refreshToken'])) {
                try {
                    $response = Http::withoutVerifying()->timeout(10)->post('https://samwad.iotcom.io/api/refresh-token', [
                        'refreshToken' => $tokens['refreshToken'],
                    ]);

                    if ($response->successful()) {
                        $newTokens = $response->json();
                        return $newTokens['accessToken'] ?? $tokens['token'] ?? null;
                    } else {
                        Log::warning('Samwad refresh-token failed, using initial token: ' . $response->body());
                        return $tokens['token'] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::warning('Samwad refresh-token exception, using initial token: ' . $e->getMessage());
                    return $tokens['token'] ?? null;
                }
            }
            return $tokens['token'] ?? null;
        } else {
            Log::error('Samwad failed to obtain login tokens');
            return null;
        }
    }

    public function initiateCall(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['success' => false, 'error' => 'Order not found.'], 404);
        }

        $team = trim($order->writer_name ?? '');
        if (empty($team)) {
            return response()->json(['success' => false, 'error' => 'No writer is assigned to this order.'], 400);
        }

        // 1. Resolve Writer phone number:
        $teamnumber = Writer::where('writer_name', $team)
            ->orWhere('id', $order->writer_id)
            ->first();

        $number = null;
        if ($teamnumber && !empty($teamnumber->writer_number)) {
            $number = preg_replace('/\D+/', '', (string) $teamnumber->writer_number);
        }

        // Fallback: check users table if writer is stored as a user (Role 6, 7, 8, etc.)
        if (empty($number)) {
            $writerUser = User::where('name', $team)
                ->orWhere('id', $order->writer_id)
                ->first();
            if ($writerUser && !empty($writerUser->mobile_no) && !str_contains($writerUser->mobile_no, '*')) {
                $number = preg_replace('/\D+/', '', (string) $writerUser->mobile_no);
            }
        }

        if (empty($number)) {
            return response()->json([
                'success' => false,
                'error' => "Writer phone number not found for '{$team}'. Please update writer phone in Writer List."
            ], 400);
        }

        // 2. Resolve Agent Caller ID
        $caller = auth()->user()->call_id ?? null;
        if (empty($caller) && auth()->check() && in_array((int) auth()->user()->role_id, [1, 4, 5])) {
            // For Admin or Managers without a personal call_id, fallback to first configured subadmin call_id
            $caller = $request->input('caller_id') ?: User::where('role_id', 9)->whereNotNull('call_id')->where('call_id', '!=', '')->value('call_id');
        }

        if (empty($caller)) {
            return response()->json([
                'success' => false,
                'error' => 'Your account does not have a Samwad Calling ID (e.g. agent@warrgyi) configured. Please update Calling ID in user settings.',
                'writer_phone' => $number,
                'writer_name' => $team
            ], 400);
        }

        // 3. Obtain Access Token
        $token = $this->Accesstoken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to authenticate with Samwad Calling Portal. Please verify Samwad API credentials.',
                'writer_phone' => $number,
                'writer_name' => $team
            ], 500);
        }

        // 4. Send Dial Request to Samwad
        try {
            $response = Http::withoutVerifying()->timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://samwad.iotcom.io/api/appdialnumber', [
                'caller' => $caller,
                'receiver' => $number,
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                if (isset($responseData['Status']) && $responseData['Status'] === 'Success dial req received') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Call initiated successfully via Samwad.',
                        'call_id' => $responseData['CallID'] ?? null,
                        'writer_phone' => $number,
                        'writer_name' => $team
                    ], 200);
                } else {
                    $statusMsg = $responseData['Status'] ?? $responseData['error'] ?? 'Agent portal not logged in or busy.';
                    return response()->json([
                        'success' => false,
                        'error' => $statusMsg,
                        'writer_phone' => $number,
                        'writer_name' => $team
                    ], 400);
                }
            } else {
                $errMsg = $responseData['error'] ?? $responseData['message'] ?? $responseData['Status'] ?? 'Failed to initiate call via Samwad.';
                if (str_contains(strtolower($errMsg), 'not in a ready state')) {
                    $errMsg = "Agent '{$caller}' is not in a Ready state. Please log into Samwad Calling Portal and set your status to Ready.";
                } elseif (str_contains(strtolower($errMsg), 'invalid agent')) {
                    $errMsg = "Invalid Samwad Agent ID '{$caller}'. Please check your Calling ID format.";
                }
                return response()->json([
                    'success' => false,
                    'error' => $errMsg,
                    'status' => $response->status(),
                    'writer_phone' => $number,
                    'writer_name' => $team
                ], $response->status() >= 400 && $response->status() < 500 ? $response->status() : 500);
            }
        } catch (\Exception $e) {
            Log::error('Samwad initiateCall exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while connecting to Samwad: ' . $e->getMessage(),
                'writer_phone' => $number,
                'writer_name' => $team
            ], 500);
        }
    }

    public function HangUp(Request $request)
    {
        $caller = auth()->user()->call_id ?? null;
        if (empty($caller)) {
            return response()->json(['success' => false, 'error' => 'No Calling ID configured.'], 400);
        }
        try {
            $token = $this->Accesstoken();
            $response = Http::withoutVerifying()->timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://samwad.iotcom.io/api/appdirectHangup', [
                'caller' => $caller,
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'data' => $response->json()]);
            } else {
                return response()->json(['success' => false, 'error' => 'Failed to hang up call'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function HoldCall(Request $request)
    {
        $caller = auth()->user()->call_id ?? null;
        if (empty($caller)) {
            return response()->json(['success' => false, 'error' => 'No Calling ID configured.'], 400);
        }
        try {
            $token = $this->Accesstoken();
            $response = Http::withoutVerifying()->timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://samwad.iotcom.io/api/appreqHold/' . $caller);

            if ($response->successful()) {
                return response()->json(['success' => true, 'data' => $response->json()]);
            } else {
                return response()->json(['success' => false, 'error' => 'Failed to hold call'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function UnHoldCall(Request $request)
    {
        $caller = auth()->user()->call_id ?? null;
        if (empty($caller)) {
            return response()->json(['success' => false, 'error' => 'No Calling ID configured.'], 400);
        }
        try {
            $token = $this->Accesstoken();
            $response = Http::withoutVerifying()->timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://samwad.iotcom.io/api/appreqUnHold/' . $caller);

            if ($response->successful()) {
                return response()->json(['success' => true, 'data' => $response->json()]);
            } else {
                return response()->json(['success' => false, 'error' => 'Failed to unhold call'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

