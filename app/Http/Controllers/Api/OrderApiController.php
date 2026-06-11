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
            ->leftJoin('orders', 'orders.lead_id', '=', 'leads.id')
            ->where(function ($query) use ($user) {
                $query->where('leads.emp_id', $user->id)
                    ->orWhere('leads.email', $user->email);
            })
            ->where('leads.is_app_lead', 1)
            ->select(
                'leads.id as lead_id',
                'leads.order_id',
                'leads.user_name',
                'leads.email',
                'leads.countrycode',
                'leads.mobile',
                'leads.project_title',
                'leads.pages',
                'leads.price',
                'leads.deadline',
                'leads.delivery_time',
                'leads.message',
                'leads.service_type',
                'leads.frontendorder',
                'leads.is_app_lead',
                'leads.is_converted',
                'leads.converted_at',
                'leads.create_at',

                'orders.id as order_db_id',
                'orders.projectstatus',
                'orders.paymentstatus',
                'orders.amount',
                'orders.received_amount',
                'orders.delivery_date',
                'orders.order_date'
            )
            ->orderByDesc('leads.id')
            ->get()
            ->map(function ($item) {
                return [
                    'lead_id' => $item->lead_id,
                    'order_id' => $item->order_id,

                    'name' => $item->user_name,
                    'email' => $item->email,
                    'mobile' => $item->mobile,
                    'countrycode' => $item->countrycode,

                    'service' => $item->project_title,
                    'work_type' => $item->service_type,
                    'word_count' => $item->pages,
                    'price' => $item->price,
                    'deadline' => $item->deadline,
                    'delivery_time' => $item->delivery_time,
                    'requirements' => $item->message,

                    'is_app_lead' => (int) $item->is_app_lead,
                    'is_converted' => (int) $item->is_converted,
                    'converted_at' => $item->converted_at,

                    'order' => [
                        'order_db_id' => $item->order_db_id,
                        'status' => $item->projectstatus,
                        'payment_status' => $item->paymentstatus,
                        'amount' => $item->amount,
                        'received_amount' => $item->received_amount,
                        'order_date' => $item->order_date,
                        'delivery_date' => $item->delivery_date,
                    ],

                    'conversion_status' => $item->is_converted == 1
                        ? 'Converted to Order'
                        : 'Lead Pending',

                    'created_at' => $item->create_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }
}