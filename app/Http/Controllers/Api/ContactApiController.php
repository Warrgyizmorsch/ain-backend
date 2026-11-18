<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    /**
     * Get contact details for mobile application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function contactDetails(Request $request): JsonResponse
    {
        $callNumber = config('services.contact.call_number', '+44 7917481696');
        $whatsappNumber = config('services.contact.whatsapp_number', '+44 78262 33106');
        $email = config('services.contact.email', 'order@assignnmentinneed.com');

        return response()->json([
            'success' => true,
            'data' => [
                'call_number' => $callNumber,
                'email' => $email,
                'whatsapp_number' => $whatsappNumber,
            ]
        ], 200);
    }
}
