<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryApiController extends Controller
{
    public function store(Request $request)
    {
        // Parameter normalization fallbacks for Next.js client integrations
        if (!$request->has('mobile')) {
            if ($request->has('phone_number')) {
                $request->merge(['mobile' => $request->input('phone_number')]);
            } elseif ($request->has('phone')) {
                $request->merge(['mobile' => $request->input('phone')]);
            }
        }
        if (!$request->has('country_code') && $request->has('countrycode')) {
            $request->merge(['country_code' => $request->input('countrycode')]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'inquiry_type' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $enquiry = Enquiry::create($validator->validated());

            // Send notification email
            try {
                \Illuminate\Support\Facades\Mail::to('order@assignnmentinneed.com')->send(new \App\Mail\ContactMail($enquiry->toArray()));
            } catch (\Exception $mailEx) {
                // Log and continue to return the success response
                \Illuminate\Support\Facades\Log::warning('Enquiry contact mail failed: ' . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Enquiry submitted successfully!',
                'data' => $enquiry
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save enquiry: ' . $e->getMessage()
            ], 500);
        }
    }
}
