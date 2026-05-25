<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {

        DB::table('primeassiment')->insert([

            'name' => $request->name,

            'email' => $request->email,

            'country' => $request->country,

            'mobile_number' => $request->mobile_number,

            'services' => $request->services,

            'subject' => $request->subject,

            'work_type' => $request->work_type,

            'select_urgency' => $request->select_urgency,

            'word_count' => $request->word_count,

            'enter_topic' => $request->enter_topic,

            'requirements' => $request->requirements,

            'source_url' => $request->source_url,

            'created_at' => Carbon::now(),

            'updated_at' => Carbon::now()

        ]);

        return response()->json([

            'status' => true,

            'message' => 'Order saved successfully'

        ], 200);
    }
}