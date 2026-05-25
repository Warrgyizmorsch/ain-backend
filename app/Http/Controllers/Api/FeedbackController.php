<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function submitFeedback(Request $request)
    {
        try {
            $scopeInput = $request->input('feedback_scope', );
            
            // Agar data array(list) mein aaya hai, toh use comma(,) laga kar jod do
            // if (is_array($scopeInput)) {
            //     $scopeInput = implode(', ', $scopeInput);
            // }

            $finalScope = is_array($scopeInput) ? implode(', ', $scopeInput) : '';

            // Data insert karna
            DB::table('feedbacks')->insert([
                'order_id'        => $request->input('order_id', ''),
                'experience'      => $request->input('experience', ''),
                'feedback_scope'  => $scopeInput,
                'your_suggestion' => $request->input('your_suggestion', ''),
                'created_at'      => now(),
                'updated_at'      => now()
            ]);

            return response()->json([
                'status'  => 'success', 
                'message' => 'Feedback submitted successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}