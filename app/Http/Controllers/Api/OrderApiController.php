<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;
use App\Models\Leads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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

    public function placeOrder(Request $request)
    {
        $rules = [
            'name'         => 'required|string',
            'service'      => 'required|string',
            'workType'     => 'required|string',
            'country'      => 'required|string',
            'subject'      => 'required|string',
            'urgency'      => 'required|string',
            'wordCount'    => 'required|integer|min:250',
            'topic'        => 'required|string',
            'requirements' => 'required|string',
            'email'        => 'required|email',
            'mobile'       => 'required|string',
            'countrycode'  => 'required|string',
            'finalPrice'   => 'nullable',
            'source_page'  => 'nullable',
            'fileUpload.*' => 'nullable|file|max:10240',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Calculate Delivery Date
        $today = now();
        $urgencyDays = $request->input('urgency');

        if (is_numeric($urgencyDays)) {
            $deliveryDate = $today->copy()->addDays((int) $urgencyDays);
        } elseif ($urgencyDays === '16 to 20') {
            $deliveryDate = $today->copy()->addDays(16);
        } elseif ($urgencyDays === '21+') {
            $deliveryDate = $today->copy()->addDays(21);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid urgency selected.'
            ], 422);
        }

        // Generate Order ID same as website
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // Logged-in app user
        $authUser = $request->user();

        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            if (empty($user->country) && $request->filled('country')) {
                $user->country = $request->input('country');
            }

            if (empty($user->mobile_no) && $request->filled('mobile')) {
                $user->mobile_no = $request->input('mobile');
            }

            if (empty($user->countrycode) && $request->filled('countrycode')) {
                $user->countrycode = $request->input('countrycode');
            }

            $user->save();
        } else {
            $user = User::create([
                'name'        => $request->input('name'),
                'email'       => $request->input('email'),
                'mobile_no'   => $request->input('mobile'),
                'countrycode' => $request->input('countrycode'),
                'country'     => $request->input('country'),
                'password'    => Hash::make('user@123'),
                'role_id'     => 2
            ]);
        }

        // File Upload same folder
        $uploadedFiles = [];

        if ($request->hasFile('fileUpload')) {
            foreach ($request->file('fileUpload') as $file) {
                $destinationPath = base_path('images/orders');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $uploadedFiles[] = 'images/orders/' . $fileName;
            }
        }

        // Lead Create
        $lead = Leads::create([
            'order_id'       => $newOrderId,
            'emp_id'         => $user->id,
            'deadline'       => $deliveryDate->format('Y-m-d'),
            'create_at'      => now(),
            'message'        => $request->input('requirements'),
            'email'          => $user->email,
            'user_name'      => $user->name,
            'countrycode'    => $user->countrycode,
            'mobile'         => $user->mobile_no,
            'frontendorder'  => 1,
            'is_app_lead'    => 1,
            'project_title'  => $request->input('service'),
            'pages'          => $request->input('wordCount'),
            'price'          => $request->input('finalPrice'),
            'service_type'   => str_replace('FirstClass', 'First Class Work', $request->input('workType')),
            'page_url'       => $request->source_page ?? 'Mobile App',
        ]);

        // Pending Order Create
        Order::create([
            'order_id'      => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id'       => $lead->id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Order placed successfully!',
            'order_id' => $newOrderId,
            'lead_id'  => $lead->id,
            'is_app_lead' => 1
        ], 201);
    }

   public function orderList(Request $request)
{
    $user = $request->user();

    $leads = DB::table('leads')
        ->where('is_app_lead', 1)
        ->where(function ($query) use ($user) {
            $query->where('emp_id', $user->id)
                ->orWhere('email', $user->email);
        })
        ->select(
            'id',
            'order_id',
            'emp_id',
            'user_name',
            'email',
            'countrycode',
            'mobile',
            'project_title',
            'pages',
            'price',
            'deadline',
            'delivery_time',
            'message',
            'service_type',
            'frontendorder',
            'is_app_lead',
            'is_converted',
            'converted_at',
            'create_at'
        )
        ->orderByDesc('id')
        ->limit(50)
        ->get()
        ->map(function ($lead) {
            return [
                'lead_id' => $lead->id,
                'order_id' => $lead->order_id,

                'name' => $lead->user_name,
                'email' => $lead->email,
                'mobile' => $lead->mobile,
                'countrycode' => $lead->countrycode,

                'service' => $lead->project_title,
                'work_type' => $lead->service_type,
                'word_count' => $lead->pages,
                'price' => $lead->price,
                'deadline' => $lead->deadline,
                'delivery_time' => $lead->delivery_time,
                'requirements' => $lead->message,

                'is_app_lead' => (int) $lead->is_app_lead,
                'is_converted' => (int) $lead->is_converted,
                'converted_at' => $lead->converted_at,

                'conversion_status' => $lead->is_converted == 1
                    ? 'Converted to Order'
                    : 'Lead Pending',

                'created_at' => $lead->create_at,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $leads
    ]);
}
}