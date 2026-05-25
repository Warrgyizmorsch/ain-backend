<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Writer;

class CallController extends Controller
{
public function getAppTokens()
{
    try {
        $response = Http::post('https://samwad.iotcom.io/api/applogin', [
            'username' => 'warrgyi',
            'password' => 'Warrgyi@123',
        ]);

        if ($response->successful()) {
            $tokens = $response->json();
            return $tokens; 
        } else {
            return null; 
        }
    } catch (\Exception $e) {
        // Handle any exceptions and return null
        return null;
    }
}

public function Accesstoken()
{
    $tokens = $this->getAppTokens();
    if ($tokens) {
        try {
            $response = Http::post('https://samwad.iotcom.io/api/refresh-token', [
                'refreshToken' => $tokens['refreshToken'],
            ]);

            if ($response->successful()) {
                $newTokens = $response->json();
                // dd($newTokens , $tokens);
                $accessToken = $newTokens['accessToken'];
                // echo "New Access Token: $accessToken\n";
                return $accessToken;
            } else {
                echo "Failed to obtain new access token\n";
                return null;
            }
        } catch (\Exception $e) {
            // Handle any exceptions and return null
            echo "Exception occurred: " . $e->getMessage() . "\n";
            return null;
        }
    } else {
        echo "Failed to obtain tokens\n";
        return null;
    }
}
public function initiateCall(Request $request)
{
    $orderId = $request->input('order_id');
    $order = Order::find($orderId);
    if ($order) {
        $team = $order->writer_name;
        $teamnumber = Writer::where('writer_name', $team)->first(); // Use first() to retrieve a single result
        if ($teamnumber) {
            $number = $teamnumber->writer_number;
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->Accesstoken(),
                ])->post('https://samwad.iotcom.io/api/appdialnumber', [
                    'caller' => auth()->user()->call_id,
                    'receiver' => $number,
                ]);
        
                if ($response->successful()) {
                    $responseData = $response->json();
                    if (isset($responseData['Status']) && $responseData['Status'] === 'Success dial req received') {
                        return response()->json([
                            'message' => 'Call initiated successfully',
                            'call_id' => $responseData['CallID'] ?? null,
                            'success' => $responseData['success'] ?? true,
                        ], 200);
                    }   
                    else
                    {
                        return response()->json(['error' => $responseData['Status'] ?? 'Agent portal not logged in'], 500); // Return JSON response with error message
                    }        
                } else {
                    return response()->json(['error' => 'Failed to initiate call', 'status' => $response->status()], 500); // Return JSON response with error message
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500); // Return JSON response with error message
            }
        } 
        else {
            dd("Writer not found for name: $team");
        }
    } 
    
}

public function HangUp(Request $request)
{

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->Accesstoken(),
        ])->post('https://samwad.iotcom.io/api/appdirectHangup', [
            'caller' => auth()->user()->call_id,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
        } else {
            return response()->json('Failed to initiate call', 500); // Return JSON response with error message
        }
    } catch (\Exception $e) {
        return response()->json('An error occurred', 500); // Return JSON response with error message
    }
}

public function HoldCall(Request $request)
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->Accesstoken(),
        ])->post('https://samwad.iotcom.io/api/appreqHold/'.auth()->user()->call_id
           );

        if ($response->successful()) {
            $responseData = $response->json();
            // dd( $responseData);
        } else {
            return response()->json('Failed to initiate call', 500); // Return JSON response with error message
        }
    } catch (\Exception $e) {
        return response()->json('An error occurred', 500); // Return JSON response with error message
    }
}

public function UnHoldCall(Request $request)
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->Accesstoken(),
        ])->post('https://samwad.iotcom.io/api/appreqUnHold/'.auth()->user()->call_id
           );

        if ($response->successful()) {
            $responseData = $response->json();
            // dd( $responseData);
        } else {
            return response()->json('Failed to initiate call', 500); // Return JSON response with error message
        }
    } catch (\Exception $e) {
        return response()->json('An error occurred', 500); // Return JSON response with error message
    }
}



}
