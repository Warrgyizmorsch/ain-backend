<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Calls;
use App\Models\User;
use App\Models\Order;
use App\Models\Services;
use App\Models\Paper;
use Illuminate\Support\Facades\Hash;
use App\Mail\LeadsConvertMail;
use Mail;
use App\Models\Files;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; //add this for captcha
use App\Mail\OrderConfirmation;
use App\Events\LeadFilterApplied;
use App\Events\LeadUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ExportLeadsJob;
use App\Models\Source;

class LeadsController extends Controller
{
    public function index()
    {
        // Retrieve all services
        $service   = Services::all();
        $papers = Paper::all();

        // Retrieve leads with status 0, eager loading user and related calls
        $status0Leads = Leads::with('user', 'call.user')
            ->where('status', 0)
            ->where('is_converted', 0) // Exclude completed leads
            ->orderByDesc('id')
            ->paginate(20);

        // Uncomment the line below for debugging purposes
        // echo '<pre>'; print_r($status0Leads); exit;

        return view('leads.leads', ['status0Leads' => $status0Leads, 'service' => $service, 'papers' => $papers]);
    }

    public function cancelleads(Request $request)
    {
        $query = Leads::with(['user', 'call.user'])->where('status', 1)->orderByDesc('id');
        if ($request->filled('search')) {
            $query->where('order_id', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('uid')) {
            $user = User::where('email', $request->input('uid'))
                ->orWhere('mobile_no', 'like', '%' . $request->input('uid') . '%')
                ->first();



            $query->where('emp_id', $user->id);
        }

        if ($request->filled('additional_filter3')) {
            if ($request->filled('additional_filter6')) {
                $query->whereBetween('create_at', [$request->input('additional_filter3'), $request->input('additional_filter6')]);
            } elseif ($request->filled('additional_filter7')) {
                $query->whereBetween('deadline', [$request->input('additional_filter3'), $request->input('additional_filter6')]);
            } else {
                $query->whereDate('create_at', $request->input('additional_filter3'));
            }
        }
        if ($request->filled('search') || $request->filled('uid') || $request->filled('additional_filter3') || $request->input('additional_filter6') || $request->input('additional_filter7')) {
            $status1Leads = $query->paginate(200);
        } else {
            $status1Leads = $query->paginate(20);
        }
        return view('leads.cleads', ['status1Leads' => $status1Leads,]);
    }


    public function leads_update(Request $req, $id)
    {
        $leads = Leads::find($id);
        $id = $leads->emp_id;

        $deliveryDate = $req->input('delivery_date');
        $today = \Carbon\Carbon::today()->format('Y-m-d'); // Get today's date
        // Check if the delivery date is before today
        if ($deliveryDate < $today) {
            // Redirect back with an error message if the date is invalid
            return redirect()->back()->with('error', 'Delivery date cannot be before the order date.');
        }

        $user = User::find($id);

        $user_name = $req->input('user_name');
        $email = $req->input('email');
        $countrycode = $req->input('country_code');
        $countrycode2 = $req->input('country_code2');
        $mobile_no = $req->input('mobile');
        $mobile2_no2 = $req->input('mobile2');
        // Check if email already exists with another user
        // Check if email already exists with another user
        if (User::where('email', $email)->where('id', '!=', $id)->exists()) {
            $existingUser = User::where('email', $email)->where('id', '!=', $id)->first();
            return back()->with('danger', 'Email already exists with another user')->with('existingUser', $existingUser);
        }

        // Check if mobile number already exists with another user
        if (User::where('mobile_no', $mobile_no)->where('id', '!=', $id)->exists()) {
            $existingUser = User::where('mobile_no', $mobile_no)->where('id', '!=', $id)->first();
            return back()->with('danger', 'Mobile number already exists with another user')->with('existingUser', $existingUser);
        }

        // Update user details
        $user->name = $user_name;
        $user->email = $email;
        $user->countrycode = $countrycode;
        $user->countrycode2 = $countrycode2;
        $user->mobile_no = $mobile_no;
        $user->mobile_no2 = $mobile2_no2;

        $user->save();
        $leads->project_title = $req->input('project_title');
        // Check if the input is a numeric value
        if ($req->filled('pages') && !is_numeric($req->input('pages'))) {
            // Redirect back with a warning message if not numeric
            return back()->with('warning', 'Word must be a numeric value');
        }
        $leads->pages = $req->input('pages');
        $leads->l_status = $req->input('l_status') ?? null;
        $leads->created_at = $req->input('order_date'); // Assuming you meant 'created_at' instead of 'create_at'
        $leads->deadline = $req->input('delivery_date');
        $leads->delivery_time = $req->input('delivery_time');
        $leads->price = $req->input('price');
        $leads->message = $req->input('message');
        $leads->service_type = $req->input('service_type');
        $leads->resit = $req->input('resit');
        $leads->tech = $req->input('tech');
        $leads->module_code = $req->input('module_code');

        $leads->draft_required = $req->input('draft_required');
        $leads->draft_date = $req->input('draft_date');
        $leads->draft_time = $req->input('draft_time');
        $leads->semester = $req->input('semester');

        $leads->typeofpaper = $req->input('paper');
        if ($leads->typeofpaper === 'Dissertation' || $leads->typeofpaper === 'Thesis') {
            $leads->chapter = $req->input('chapter');
        } else {
            $leads->chapter = null;
        }
        $leads->save();



        return back()->with('success', 'Lead updated successfully.');
    }

    public function leads_Cancel(Request $request, $id)
    {
        try {
            $lead = Leads::findOrFail($id);

            // Check if the lead is not already canceled
            if ($lead->status !== 1) {
                $lead->status = 1;
                $lead->save();

                if (!empty($request->message)) {
                    $call = new Calls();
                    $call->created_by = auth()->id();
                    $call->lead_id = $id;
                    $call->description = $request->message;
                    $call->save();
                }
                logActivity('Lead', [
                    'type' => 'Lead Cancelled',
                    'lead_id' => $lead->id,
                    'updated_by' => auth()->user()->name,
                ]);
                return response()->json(['message' => 'Lead canceled successfully']);
            } else {
                return response()->json(['message' => 'Lead is already canceled']);
            }
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to cancel lead', 'message' => $e->getMessage()], 500);
        }
    }
    public function leads_Active($id)
    {
        try {
            $lead = Leads::findOrFail($id);

            // Check if the lead is not already canceled
            if ($lead->status !== 0) {
                $lead->status = 0;
                $lead->save();

                return response()->json(['message' => 'Lead Activated successfully']);
            } else {
                return response()->json(['message' => 'Lead is already Active']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to Active lead', 'message' => $e->getMessage()], 500);
        }
    }
    public function new_lead(Request $req)
    {
        // $lead = new Leads;
        // $lead->user_name = $req->input('user_name');
        // $lead->email = $req->input('email');
        // $lead->save();

        // return redirect()->back()->with('success', 'writer submitted succesffully');
        dd($req->all());
    }

    public function delete_leads($id)
    {
        Leads::destroy($id);

        return response()->json(['success' => true]);
    }

    public function userData(Request $request)
    {
        $mobile = $request->input('mobile');
        $userData = User::where('mobile_no', $mobile)
            ->orWhere('mobile_no2', $mobile)
            ->first();

        $referUser = null;
        if ($userData && $userData->refer_id) {

            $referUser = User::select('id', 'name', 'email', 'mobile_no')
                ->where('id', $userData->refer_id)
                ->first();
        }    

        return response()->json(['user' => $userData, 'referUser' => $referUser]);
    }


    //    public function insert_leads(Request $request)
    // {
    //     // dd($request->all());
    //     $deliveryDate = $request->input('delivery_date');
    //     $today = Carbon::today();

    //     // Check if the delivery date is before today
    //     if (Carbon::parse($deliveryDate)->lt($today)) {
    //         // Redirect back with an error message if the date is invalid
    //         return redirect()->back()->with('error', 'Delivery date cannot be before today.');
    //     }
    //     // Get the latest order to generate a new order ID
    //     $latestOrder = Order::orderByDesc('id')->first();
    //     $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
    //     $newOrderId = 'UKS' . $newOrderNumber;

    //     $user = User::where('id', $request->input('id'))->first();

    //     if (!$user) {
    //         if ($request->input('email')) {

    //             $existingUser = User::where('email', $request->input('email'))->first();

    //             if ($existingUser) {
    //                 // If email exists with another number, prompt user to update details
    //                 return redirect()->back()->withInput()->with('error', 'Email already exists with another number. Please update your details.');
    //             }
    //         }

    //         $user = new User;
    //         $user->email = $request->input('email') ?: 'user' . $request->input('mobile') . '@gmail.com';
    //         $user->mobile_no = $request->input('mobile');
    //         $user->name = $request->input('user_name') ?: 'user' . $request->input('mobile');
    //         $user->countrycode = $request->input('countrycode');
    //         $user->password = Hash::make('user@123');
    //         $user->role_id = 2;
    //         $user->save();

    //         $userId = $user->id;
    //         $name = $user->name;
    //         $email = $user->email;
    //     } else {
    //         // User exists, update user details
    //         $user->email = $request->input('email');
    //         $user->name = $request->input('user_name');
    //         $user->countrycode = $request->input('countrycode');
    //         $user->save();

    //         $userId = $user->id;
    //     }

    //     // Create a new Leads instance
    //     $leads = new Leads;
    //     $leads->order_id = $newOrderId;
    //     $leads->emp_id = $userId;
    //     $leads->project_title = $request->input('project_title');
    //     $leads->module_code = $request->input('module_code');
    //     // Check if the input is a numeric value
    //         if ( $request->filled('pages') && !is_numeric($request->input('pages'))) {
    //             // Redirect back with a warning message if not numeric
    //             return redirect()->back()->with('warning', 'Word must be a numeric value');
    //         }
    //     $leads->pages = $request->input('pages');
    //     if($request->input('delivery_date') == '')
    //     {
    //         $leads->deadline = now();

    //     }
    //     else
    //     {
    //         $leads->deadline = $request->input('delivery_date');

    //     }
    //     $leads->delivery_time = $request->input('delivery_time');
    //     $leads->price = $request->input('amount');
    //     $leads->l_status = $request->input('i_status');
    //     $leads->message = $request->input('message');

    //     $leads->service_type = $request->input('service_type');
    //     $leads->resit = $request->input('resit');
    //     $leads->tech = $request->input('tech');
    //     $leads->countrycode2 = $request->input('countrycode2');
    //     $leads->mobile2 = $request->input('mobile_no2');
    //     $leads->draft_required = $request->input('draft_required');
    //     $leads->draft_date = $request->input('draft_date');
    //     $leads->draft_time = $request->input('draft_time');
    //     $leads->typeofpaper = $request->input('paper');
    //     if ($leads->typeofpaper === 'Dissertation' || $leads->typeofpaper === 'Thesis') {
    //         $leads->chapter = $request->input('chapter');
    //     }else {
    //         $leads->chapter = null;
    //     }
    //      $leads->semester = $request->input('semester');
    //     $leads->save();
    //     $leadsId = $leads->id;

    //     $order = new Order;
    //     $order->uid = 0;
    //     $order->order_id = $newOrderId;
    //     $order->lead_id = $leadsId;
    //     $order->title = $request->input('project_title');
    //     // Check if the input is a numeric value
    //         if ( $request->filled('pages') && !is_numeric($request->input('pages'))) {
    //             // Redirect back with a warning message if not numeric
    //             return redirect()->back()->with('warning', 'Word must be a numeric value');
    //         }
    //     $order->pages = $request->input('pages');
    //     $order->amount = $request->input('amount');
    //     $order->message = $request->input('message');
    //     $order->order_date = now();

    //     $order->delivery_date = $request->input('delivery_date');
    //     $order->typeofpaper = $request->input('paper');
    //     if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
    //         $order->chapter = $request->input('chapter');
    //     }else {
    //         $order->chapter = null;
    //     }
    //     $order->semester = $request->input('semester');
    //     $order->save();

    //     return redirect()->back()->with('success', 'New Leads Insert Successfully');
    // }

    public function insert_leads(Request $request)
    {
        $today = Carbon::today();

        // =========================
        // VALIDATE DELIVERY DATE ARRAY
        // =========================
        if ($request->delivery_date) {
            foreach ($request->delivery_date as $date) {
                if ($date && Carbon::parse($date)->lt($today)) {
                    return redirect()->back()->with('error', 'Delivery date cannot be before today.');
                }
            }
        }

        // =========================
        // USER CREATE / UPDATE
        // =========================
        $user = User::where('id', $request->input('id'))->first();

        if (!$user) {

            if ($request->input('email')) {
                $existingUser = User::where('email', $request->input('email'))->first();

                if ($existingUser) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Email already exists with another number.');
                }
            }

            $user = new User;
            $user->email = $request->input('email') ?: 'user' . $request->input('mobile') . '@gmail.com';
            $user->mobile_no = $request->input('mobile');
            $user->name = $request->input('user_name') ?: 'user' . $request->input('mobile');
            $user->countrycode = $request->input('countrycode');
            $user->password = Hash::make('user@123');
            $user->role_id = 2;
            $user->refer_id = $request->refer_id ?? null;
            $user->save();
        } else {
            $user->email = $request->input('email');
            $user->name = $request->input('user_name');
            $user->countrycode = $request->input('countrycode');
            $oldReferId = $user->refer_id;
            $newReferId = $request->refer_id ?? null;
            if (empty($oldReferId)) {
                // agar pehle refer nahi hai, koi bhi add kar sakta hai
                $user->refer_id = $newReferId;
            } else {
                // agar pehle refer hai, sirf admin change kar sakta hai
                if (Auth::check() && Auth::user()->role_id == 1) {
                    $user->refer_id = $newReferId;
                }
            }
            $user->save();
        }

        $userId = $user->id;

        // =========================
        // GET LAST ORDER NUMBER (OLD LOGIC)
        // =========================

        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? intval(substr($latestOrder->order_id, 3)) : 0;

        // =========================
        // LOOP START
        // =========================
        $total = count($request->project_title);

        for ($i = 0; $i < $total; $i++) {

            // 🔥 INCREMENT ORDER NUMBER LIKE OLD
            $newOrderNumber++;
            $newOrderId = 'UKS' . $newOrderNumber;


            // =========================
            // VALIDATE PAGES
            // =========================
            if (!empty($request->pages[$i]) && !is_numeric($request->pages[$i])) {
                return redirect()->back()->with('warning', 'Word must be numeric');
            }

            // =========================
            // INSERT LEADS
            // =========================
            $leads = new Leads;
            $leads->order_id = $newOrderId;
            $leads->emp_id = $userId;

            $leads->project_title = $request->project_title[$i];
            $leads->module_code   = $request->module_code[$i];
            $leads->pages         = $request->pages[$i];

            $leads->deadline = !empty($request->delivery_date[$i])
                ? $request->delivery_date[$i]
                : now();

            $leads->delivery_time = $request->delivery_time[$i];
            $leads->price         = $request->amount[$i];
            $leads->l_status      = $request->i_status[$i] ?? null;

            $leads->message       = $request->message[$i];

            $leads->service_type  = $request->service_type[$i];
            $leads->typeofpaper   = $request->paper[$i];

            // ✅ CHECKBOX FIX (ON / OFF STRING)
            $leads->tech  = isset($request->tech[$i]) ? 'on' : 'off';
            $leads->resit = isset($request->resit[$i]) ? 'on' : 'off';

            // =========================
            // DRAFT
            // =========================
            $leads->draft_required = $request->draft_required[$i] ?? null;
            $leads->draft_date     = $request->draft_date[$i] ?? null;
            $leads->draft_time     = $request->draft_time[$i] ?? null;

            // =========================
            // CHAPTER
            // =========================
            if ($leads->typeofpaper === 'Dissertation' || $leads->typeofpaper === 'Thesis') {
                $leads->chapter = $request->chapter[$i] ?? null;
            } else {
                $leads->chapter = null;
            }

            // ✅ SAME SEMESTER
            $leads->semester = $request->semester;
            $leads->lead_source = $request->lead_source ?? null;
            if (Auth::check()) {
                $leads->created_by = Auth::id();
            } else {
                $leads->created_by = null;
            }

            $leads->save();

            // =========================
            // INSERT ORDER
            // =========================
            $order = new Order;
            $order->uid = 0;
            $order->order_id = $newOrderId;
            $order->lead_id = $leads->id;

            $order->title   = $request->project_title[$i];
            $order->pages   = $request->pages[$i];
            $order->amount  = $request->amount[$i];
            $order->message = $request->message[$i];

            $order->order_date = now();
            $order->delivery_date = $request->delivery_date[$i];

            $order->typeofpaper = $request->paper[$i];

            if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
                $order->chapter = $request->chapter[$i] ?? null;
            } else {
                $order->chapter = null;
            }

            // ✅ SAME SEMESTER
            $order->semester = $request->semester;

            $order->save();

            logActivity('Lead', [
                'type' => 'Lead Created',
                'lead_id' => $leads->id,
                'order_id' => $newOrderId,
                'project' => $leads->project_title,
                'created_by' => auth()->user()->name ?? 'System',
            ]);
        }


        return redirect()->back()->with('success', 'Multiple Leads Inserted Successfully');
    }
    public function convert(Request $request, $id)
    {
        try {
            // Find the lead by ID
            $lead = Leads::findOrFail($id);
            $emp_id = $lead->emp_id;
            $order_code = $lead->order_id;

            // Check if an order already exists for this lead
            $order = Order::where('lead_id', $id)->first();

            // If the order doesn't exist, create a new one
            if (!$order) {
                $order = new Order();
                $order->lead_id = $lead->id;
            }

            // Update order fields with the provided values
            $order->title = $request->input('project_title');
            // Check if the input is a numeric value
            if ($request->filled('pages') && !is_numeric($request->input('pages'))) {
                // Redirect back with a warning message if not numeric
                return redirect()->back()->with('warning', 'Word must be a numeric value');
            }
            $order->pages = $request->input('pages');
            $order->services = $request->input('service_type');
            $order->tech = $request->has('tech') ? 1 : 0;
            // $order->delivery_date = $request->input('delivery_date');
            if ($request->input('delivery_date') != null) {
                $order->delivery_date = $request->input('delivery_date');
            } else {
                $order->delivery_date = now()->format('Y-m-d');
            }
            $order->amount = $request->input('price');
            $order->message = $request->input('message');
            $order->projectstatus = 'Other';
            $order->uid = $emp_id;
            $order->order_date = now()->format('Y-m-d');
            $order->is_read = '1';
            $order->module_code = $request->input('module_code');
            $order->draftrequired = $request->input('draft_required');
            $order->draft_date = $request->input('draft_date');
            $order->draft_time = $request->input('draft_time');
            $order->semester = $request->input('semester');

            $order->typeofpaper = $request->input('paper');
            if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
                $order->chapter = $request->input('chapter');
            } else {
                $order->chapter = null;
            }

            // New fields
            $order->l_converted_by = Auth::user()->name;

            // Save the order
            $order->save();

            // Update user information if provided
            if ($request->filled(['email', 'mobile', 'countrycode', 'user_name'])) {
                $user = User::where('id', $emp_id)->first();
                if ($user) {
                    // Check if the provided email is already registered with a different user
                    $existingUserWithEmail = User::where('email', $request->input('email'))->where('id', '!=', $emp_id)->first();
                    if ($existingUserWithEmail) {
                        // Prompt user to change email
                        return response()->json(['error' => 'Email already registered with a different user', 'message' => 'Email is already registered with a different user. Please change your email.'], 400);
                    }

                    $user->email = $request->input('email');
                    $user->mobile_no = $request->input('mobile');
                    $user->mobile_no2 = $request->input('mobile_no2');
                    $user->countrycode = $request->input('countrycode');
                    $user->countrycode2 = $request->input('countrycode2');
                    $user->name = $request->input('user_name');
                    $user->save();
                    // Define data for the email
                    $mailData = [
                        'name' => $request->input('user_name'),
                        'email' => $request->input('email'),
                        'title' => $request->input('project_title'),
                        'order_code' => $order_code,
                        'date'     => $request->input('delivery_date')
                    ];
                    Mail::to($mailData['email'])->cc('order@assignnmentinneed.com')->send(new LeadsConvertMail($mailData));
                } else {
                    \Log::error('User not found with ID: ' . $emp_id);
                }
            }

            // Delete the lead
            $lead->delete();

            // Return a response with updated data
            return response()->json(['message' => 'Lead converted successfully', 'order' => $order]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error($e);

            // Log the error for debugging purposes
            $errorMessage = 'An error occurred. Please try again.';
            if (config('app.debug')) {
                $errorMessage .= ' Error: ' . $e->getMessage();
            }

            return response()->json(['error' => 'Error converting lead', 'message' => $errorMessage], 500);
        }
    }

    public function insert_call(Request $request)
    {
        $id = $request->input('id');
        $description   = $request->input('description');


        $Calls = new Calls;
        $Calls->created_by = auth()->user()->id;
        $Calls->lead_id = $id;
        $Calls->description = $description;
        $Calls->save();
        $message = 'Record created successfully.';
        $user = auth()->user();

        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => $Calls->description,
            'created_at' => $Calls->created_at->diffForHumans(),
            'user' => [
                'name' => $user->name,
                'avatar' => asset($user->photo),
            ],
        ]);
    }

    public function fetchData($lead_id)
    {
        // Fetch data based on the provided lead ID
        $data = Calls::where('lead_id', $lead_id)->get();

        return response()->json(['data' => $data]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('additionalFilter1');
        $userdtail = $request->input('additionalFilter2');
        $Status = $request->input('additionalFilter4');
        $techn = $request->input('additionalFilter5');
        $fromDate = $request->input('additionalFilter3');
        $UptoDate = $request->input('additionalFilter6');
        $datetatus = $request->input('additionalFilter7');

        $leads = Leads::query();

        if ($searchTerm != '') {
            $leads->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('project_title', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($Status != '') {
            $leads->where('l_status',  $Status);
        }

        if ($techn != '') {

            if ($techn == 'Technical') {
                $leads->where('tech',  'on');
            } elseif ($techn == 'Resit') {
                $leads->where('resit',  'on');
            } elseif ($techn == 'First') {
                $leads->where('service_type',  'First Class Work');
            }
        }

        if ($userdtail != '') {
            // Assuming $leads is an instance of a database query builder

            $leads->where(function ($query) use ($userdtail) {
                $query->where('emp_id', $userdtail);
            });
        }

        if ($fromDate != '') {
            if ($UptoDate != '') {
                $leads->whereBetween('create_at', [$fromDate, $UptoDate]);
            } else {
                $leads->whereDate('create_at', $fromDate);
            }
        } elseif ($datetatus != '') {
            if ($fromDate != '' && $UptoDate != '') {
                $leads->whereBetween('deadline', [$fromDate, $UptoDate]);
            } elseif ($fromDate != '') {
                $leads->where('deadline', $fromDate);
            } else {
                // Assuming $datetatus is a date string in a valid format, such as 'Y-m-d'
                $leads->whereDate('deadline', $datetatus);
            }
        }

        $leads->orderBy('id', 'desc')->where('status', 0);

        $output = '';
        $index = 1;

        foreach ($leads->get() as $lead) {
            $output .= "
            <tr>
                <td>{$index}</td>
                
                <td class='icon-container my-auto d-flex '>
               
                " . ($lead->flag == '1' ? "
                
                    <div class='form-check form-check-sm form-check-custom form-check-solid m-5'>
                        <input class='form-check-input widget-13-check' type='checkbox' id='{$lead->id}'  checked onchange='checkedLead(this, {$lead->id})'>
                    </div>
               
                " : '') . "
                
                  " . ($lead->flag == '0' ? "
                
                    <div class='form-check form-check-sm form-check-custom form-check-solid m-5'>
                        <input class='form-check-input widget-13-check' type='checkbox' id='{$lead->id}'   onchange='checkedLead(this, {$lead->id})'>
                    </div>
               
                " : '') . "
                    <div class='form-check form-switch my-auto'>
                        <input class='form-check-input' type='checkbox' id='{$lead->id}' role='switch' checked onchange='handleChange(this, {$lead->id})'>
                    </div>
                   
                    <button type='button' class='btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1' data-bs-toggle='modal' data-bs-target='#leadCallModal{$lead->id}'>Call</button>";

            // Add modal HTML here for each lead
            $output .= "
                        <style>
                            /* Style for modal dialog */
                            .modal-content {
                                height: 100%;
                            }
                            .modal-dialog.modal-dialog-end {
                                margin: 0;
                                position: fixed;
                                right: 0;
                                top: 0;
                                height: 100%;
                                width: 40%; /* Set your desired width */
                                max-width: 100%;
                                z-index: 1050;
                                transform: translateX(100%);
                                transition: transform 0.3s ease;
                            }
                    
                            /* Style for modal dialog when it's shown */
                            .modal.show .modal-dialog.modal-dialog-end {
                                transform: translateX(0);
                            }
                            /* Style for modal body */
                            .modal-body {
                                overflow-y: auto; /* Make the modal body vertically scrollable */
                                max-height: calc(100vh - 200px); /* Set max height to avoid modal extending beyond viewport */
                            }
                        </style>
    


                        <div class='modal fade' id='leadCallModal{$lead->id}' tabindex='-1' aria-labelledby='leadCallModalLabel' aria-hidden='true'>
                            <div class='modal-dialog  modal-dialog-end'>
                                <div class='modal-content'>
                                    <div class='modal-header' >
                                        <div class='card-title' >
                                            <!--begin::User-->
                                            <div class='d-flex justify-content-center flex-column me-3'>
                                                <a href='#' class='fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1'>$lead->order_id</a>
                                                <!--begin::Info-->
                                                <div class='mb-0 lh-1'>
                                                    <span class='badge badge-success badge-circle w-10px h-10px me-1'></span>
                                                    <span class='fs-7 fw-bold text-muted'>Active</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body' data-kt-element='messages{$lead->id}'>";

            // Loop through calls
            foreach ($lead->call->sortByDesc('created_at') as $call) {
                if ($call->created_by != Auth::user()->id) {
                    $output .= "<div class='d-flex justify-content-start mb-10'>
                                                            <div class='d-flex flex-column align-items-start'>
                                                                <div class='d-flex align-items-center mb-2'>
                                                                    <div class='symbol symbol-35px symbol-circle'>";

                    // Check if user photo exists
                    if ($call->user && $call->user->photo) {
                        $output .= "<img alt='Pic' src='" . asset($call->user->photo) . "' />";
                    } else {
                        $output .= "<img alt='Pic' src='assets/media/avatars/blank.png' />";
                    }

                    $output .= "</div>
                                                                    <div class='ms-3'>
                                                                        <a href='#' class='fs-5 fw-bolder text-gray-900 text-hover-primary me-1'>";
                    if ($call->user) {
                        $output .= $call->user->name;
                    }
                    $output .= "</a>
                                                                        <span class='text-muted fs-7 mb-1'>{$call->created_at->diffForHumans()}</span>
                                                                    </div>
                                                                </div>
                                                                <div class='p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start' data-kt-element='message-text'>{$call->description}</div>
                                                            </div>
                                                        </div>";
                } else {
                    $output .= "<div class='d-flex justify-content-end mb-10'>
                                                            <div class='d-flex flex-column align-items-end'>
                                                                <div class='d-flex align-items-center mb-2'>
                                                                    <div class='me-3'>
                                                                        <span class='text-muted fs-7 mb-1'>{$call->created_at->diffForHumans()}</span>
                                                                        <a href='#' class='fs-5 fw-bolder text-gray-900 text-hover-primary ms-1'>You</a>
                                                                    </div>
                                                                    <div class='symbol symbol-35px symbol-circle'>";

                    // Check if user photo exists
                    if ($call->user && $call->user->photo) {
                        $output .= "<img src='" . asset(Auth::user()->photo) . "' />";
                    } else {
                        $output .= "<img alt='Pic' src='assets/media/avatars/blank.png' />";
                    }

                    $output .= "</div>
                                                                </div>
                                                                <div class='p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end' data-kt-element='message-text'>{$call->description}</div>
                                                            </div>
                                                        </div>";
                }
            }

            $output .= "</div>
                                    <div class='modal-footer'>
                                        <textarea class='form-control form-control-flush mb-3' rows='1' id='description{$lead->id}'  placeholder='Type a message'></textarea>
                                        <input type='hidden' name='lead_id' value='{$lead->id}' id='lead_id{$lead->id}'>
                                        <div class='d-flex flex-stack text-end' style='margin-right:auto;'>
                                            <button class='btn btn-primary'  onclick='sendData({$lead->id},$(`#description{$lead->id}`).val())'>Send</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ";



            $output .= "  <a target='_blank' href='/leadedit.{$lead->id}' 
                    
                    class='btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1'>
                        <span class='svg-icon svg-icon-3'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24'
                                viewBox='0 0 24 24' fill='none'>
                                <path opacity='0.3'
                                    d='M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z'
                                    fill='black'></path>
                                <path
                                    d='M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z'
                                    fill='black'></path>
                            </svg>
                        </span>
                    </a>

                    <a  href='javascript:void(0);' id='clickToCallBtn{$lead->id}' href='#' data-bs-toggle='modal' 
                        id='kt_toolbar_primary_button'
                        class='btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1'>
                        <span class='svg-icon svg-icon-3'>
                            <li class='fa fa-phone fa-lg'></li>
                        </span>
                    </a>

                    <form action='/convertleads/{$lead->id}'method='post'>
                    " . csrf_field() . "
                        <button type='submit' class='btn btn-icon btn-bg-secondary btn-active-color-light btn-sm me-1'>
                            <span class='svg-icon svg-icon-3'>C</span>
                        </button>
                    </form>
                </a>
                 <a href='#' id='clickToDownload{$lead->order_id}' class='btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn{{$lead->id}}' onclick='downloadFiles(this)' >
                    <span class='svg-icon svg-icon-3'>
                        <i class='fa fa-download fa-lg'></i>
                    </span>
                </a>
                </td>
                <td class='text-center'>{$lead->order_id}  <br>
                    " . ($lead->resit == 'on' ? '<span class="badge badge-light-danger fs-7 fw-bold">Resit</span>' : '') . "
                    " . ($lead->service_type == 'First Class Work' ? '<span class="badge badge-light-info fs-7 fw-bold">First Class Word</span>' : '') . "
                </td>
                <td>
                    

                    " . ($lead->user != '' ?   $lead->user->name : '') . "
                " . ($lead->user != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $lead->user->mobile_no . '</span>' : '') . "
                </td>
                <td>" . \Carbon\Carbon::parse($lead->create_at)->format('d M Y') . "</td>
                <td class='text-center'>{$lead->project_title}  <br>Semester : {$lead->semester}
                " . ($lead->tech == 'on' ? '<span class="badge badge-light-success fs-7 fw-bold">Technical</span>' : '') . "
                " . ($lead->module_code != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $lead->module_code . '</span>' : '') . "
                
                </td>
                <td>{$lead->pages}
                </td>
                <td>{$lead->price}</td>
                <td>" . $lead->deadline . "
                 <br>
                  " . ($lead->draft_required == 'Yes' ? '<span class="badge badge-light-success fs-7 fw-bold">' . $lead->draft_date . ' (' . $lead->draft_time . ')</span>' : '') . "
                 
                </td>
            </tr>";
            $index++;
        }

        return $output;
    }
    public function leadEditPage($id)
    {
        $service = Services::all();
        $papers = Paper::all();
        $lead = Leads::where('id', $id)->first();
        // echo '<pre>'; print_r($lead);exit;

        return view('leads.section.lead_edit', compact('lead', 'service', 'papers'));
    }
    public function swapUserData(Request $request)
    {
        $userId = $request->input('user_id');

        // Retrieve the user by ID
        $user = Leads::find($userId);
        if ($user) {
            // Swap the values
            $tempCountryCode = $user->countrycode;
            $tempMobileNo = $user->mobile;

            $user->countrycode = $user->countrycode2;
            $user->mobile = $user->mobile2;

            $user->countrycode2 = $tempCountryCode;
            $user->mobile2 = $tempMobileNo;

            // Save the changes
            $user->save();


            // Include the swapped data in the response
            $responseData = [
                'success' => true,
                'message' => 'Data swapped successfully',
                'mobile1' => [
                    'country_code' => $user->countrycode,
                    'mobile_number' => $user->mobile,
                ],
                'mobile2' => [
                    'country_code' => $user->countrycode2,
                    'mobile_number' => $user->mobile2,
                ],
            ];

            return response()->json($responseData, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }
    public function leadCallPage($id)
    {
        $lead = Leads::with('call.user')->find($id);
        $service = Services::all();

        return view('leads.section.call_lead', compact('lead', 'service'));
    }

    public function orderByuser(Request $request)
    {

        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . $newOrderNumber;

        $lead = new Leads;
        $lead->order_id = $newOrderId;
        $lead->user_name = auth()->user()->name;
        $lead->email = auth()->user()->email;
        $lead->mobile = auth()->user()->mobile_no;
        $lead->emp_id = auth()->user()->id;
        $lead->project_title = $request->input('title');
        $lead->deadline = $request->input('deadline');
        $lead->create_at = now();
        $lead->save();


        $order = new Order;
        $order->order_id = $newOrderId;
        $order->lead_id = $lead->id;
        $order->save();
        return redirect()->back()->with('success', 'New Leads Insert Successfully');
    }

    public function updateUser(Request $request, $id)
    {
        $userId = $request->input('user_id');

        // Retrieve the user by ID
        $lead = Leads::find($id);

        if ($lead) {
            // Swap the values
            $tempCountryCode = $lead->countrycode;
            $tempMobileNo = $lead->mobile;

            $lead->countrycode = $lead->countrycode2;
            $lead->mobile = $lead->mobile2;

            $lead->countrycode2 = $tempCountryCode;
            $lead->mobile2 = $tempMobileNo;

            // Save the changes
            $lead->save();

            // Include the swapped data in the response
            $responseData = [
                'success' => true,
                'message' => 'Data swapped successfully',
                'mobile1' => [
                    'country_code' => $lead->countrycode,
                    'mobile_number' => $lead->mobile,
                ],
                'mobile2' => [
                    'country_code' => $lead->countrycode2,
                    'mobile_number' => $lead->mobile2,
                ],
            ];

            return response()->json($responseData, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }


    public function checked(Request $request, $id)
    {
        $leads = Leads::find($id);

        if ($leads->flag == 0) {
            $leads->flag = 1;
        } else {
            // If flag is already 1, set it to 0
            $leads->flag = 0;
        }

        $leads->save();

        return response()->json(['message' => 'Lead Check successfully']);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['exists' => true, 'email' => $user->email, 'mobile_no' => $user->mobile_no]);
        } else {
            return response()->json(['exists' => false, 'email' => null, 'mobile_no' => null]);
        }
    }

    public function updateEmail(Request $request, $email)
    {
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            // Email already exists, return a response indicating that the email was not updated
            return response()->json(['success' => false, 'message' => 'Email already exists.']);
        } else {
            // Update the email for the authenticated user
            $user = $request->user(); // Assuming you are using Laravel's built-in authentication
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found.']);
            }

            $user->email = $email;
            $user->save();

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Email updated successfully.']);
        }
    }

    public function convertLead($lead)
    {
        $leadData = Leads::find($lead);



        if (!$leadData) {
            return response()->json(['success' => false, 'message' => 'Lead not found.'], 404);
        }

        $order = Order::where('lead_id', $leadData->id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found for the lead.'], 404);
        }

        $order->uid = $leadData->emp_id;
        $order->title = $leadData->project_title;
        // Check if the input is a numeric value
        if (!is_numeric($leadData->pages) && !empty($leadData->pages)) {
            // Redirect back with a warning message if not numeric
            return response()->json(['success' => false, 'message' => 'Word must be a numeric value.'], 404);
        }
        $order->pages = $leadData->pages;
        $order->services = $leadData->service_type;
        if ($leadData->tech == 'on') {
            $order->tech = '1';
        }
        // $order->delivery_date = $leadData->deadline;
        if ($leadData->deadline != null) {
            $order->delivery_date = $leadData->deadline;
        } else {
            $order->delivery_date = now()->format('Y-m-d');
        }
        $order->amount = $leadData->price;
        $order->message = $leadData->messages;
        $order->resit = $leadData->resit;
        $order->projectstatus = 'Other';

        $order->order_date = now()->format('Y-m-d');
        $order->is_read = '1';
        $order->module_code =  $leadData->module_code;
        if ($leadData->draft_required == 'Yes') {
            $order->draftrequired = 'Y';
        }
        $order->draft_date = $leadData->draft_date;
        $order->draft_time = $leadData->draft_time;
        $order->semester = $leadData->semester;

        $order->typeofpaper = $leadData->typeofpaper;
        if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
            $order->chapter = $leadData->chapter;
        } else {
            $order->chapter = null;
        }
        // New fields
        $order->l_converted_by = Auth::user()->name;

        $user = User::where('id', $leadData->emp_id)->first();

        $mailData = [
            'name' => $user->name,
            'email' => $user->email,
            'title' => $leadData->project_title,
            'order_code' => $leadData->order_id,
            'date'     => $leadData->deadline,
        ];
        // dd ($mailData); EXIT;

        Mail::to($mailData['email'])->cc('order@assignnmentinneed.com')->send(new LeadsConvertMail($mailData));

        $order->save();


        $leadData->delete();


        return redirect('/order');
    }

    public function FrontEndLeads(Request $request)
    {
        // Validate the reCAPTCHA token
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$recaptchaResponse) {
            return redirect()->back()->with('warning', 'This feature has been temporarily disabled for security reasons. Please contact us via WhatsApp for assistance.');
        }
        $recaptchaSecret = config('services.recaptcha.secret_key');
        $recaptchaVerification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        if (!$recaptchaVerification->json('success')) {
            return redirect()->back()->withErrors(['captcha' => 'ReCAPTCHA verification failed.']);
        }
        //date validation
        $deliveryDate = $request->input('delivery_date');
        $today = date('Y-m-d'); // Get today's date
        // echo  $deliveryDate, "",$today; exit;
        // Check if the delivery date is before today        
        if ($deliveryDate < $today) {
            // Redirect back with an error message if the date is invalid
            return redirect()->back()->withErrors(['delivery_date' => 'Assignment Deadline cannot be before today.']);
        }

        // Validate the incoming request
        $request->validate([
            'doc.*' => 'file|max:102400', // Example validation for file uploads
        ]);

        // Get the latest order to generate a new order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . $newOrderNumber;

        if (Auth::user()) {
            $user = Auth::user();

            // Create a new Leads instance
            $leads = new Leads;
            $leads->order_id = $newOrderId;
            $leads->emp_id = $user->id;

            $leads->deadline = $request->input('delivery_date');

            $leads->message = $request->input('message');

            $leads->email = $user->email;
            $leads->user_name = $user->name;
            $leads->countrycode = $user->countrycode;
            $leads->mobile = $user->mobile_no;
            $leads->frontendorder = 1;
            $leads->project_title = $request->input('topic');



            $leads->save();
            $leadsId = $leads->id;

            $order = new Order;
            $order->uid = 0;
            $order->order_id = $newOrderId;
            $order->lead_id = $leadsId;
            $order->u_email = $user->email;
            $order->uname = $user->name;

            $order->message = $request->input('message');
            $order->order_date =  Carbon::now();

            $order->delivery_date = $request->input('delivery_date');
            $order->save();

            if ($request->file('doc')) {
                // Loop through each uploaded file
                foreach ($request->file('doc') as $file) {
                    // Generate a unique file name to prevent conflicts
                    $fileName = $newOrderId . '_' . $file->getClientOriginalName();

                    // Move the uploaded file to the public/files directory
                    $file->move(public_path('files'), $fileName);

                    // Store the file metadata in the database
                    $newFile = new Files;
                    $newFile->file_data = 'files/' . $fileName; // Store file path relative to public directory
                    $newFile->order_id = $newOrderId;
                    $newFile->file_name = $fileName; // Store the unique file name
                    $newFile->file_type = $file->getClientMimeType();
                    $newFile->save();
                }
            }
        }
        if (!Auth::user()) {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                $user = new User;
                $user->email = $request->input('email') ?: 'user' . $request->input('mobile') . '@gmail.com';
                $user->mobile_no = $request->input('mobile');
                $user->name = $request->input('user_name') ?: 'user' . $request->input('mobile');
                $user->countrycode = $request->input('countrycode');
                $user->password = Hash::make('user@123');
                $user->role_id = 2;
                $user->save();

                $userId = $user->id;
                $name = $user->name;
                $email = $user->email;
            }

            // Create a new Leads instance
            $leads = new Leads;
            $leads->order_id = $newOrderId;
            $leads->emp_id = $user->id;

            $leads->deadline = $request->input('delivery_date');

            $leads->message = $request->input('message');

            $leads->email = $user->email ?? $request->input('email');
            $leads->user_name = $user->name ?? $request->input('user_name');
            $leads->countrycode = $user->countrycode ?? $request->input('countrycode');
            $leads->mobile = $user->mobile_no ?? $request->input('mobile');
            $leads->frontendorder = 1;
            $leads->project_title = $request->input('topic');


            $leads->save();
            $leadsId = $leads->id;

            $order = new Order;
            $order->uid = 0;
            $order->order_id = $newOrderId;
            $order->lead_id = $leadsId;
            $order->u_email = $user->email ?? $request->input('email');
            $order->uname = $user->name ?? $request->input('user_name');

            $order->message = $request->input('message');
            $order->order_date =  Carbon::now();

            $order->delivery_date = $request->input('delivery_date');
            $order->save();

            if ($request->file('doc')) {
                // Loop through each uploaded file
                foreach ($request->file('doc') as $file) {
                    // Generate a unique file name to prevent conflicts
                    $fileName = $newOrderId . '_' . $file->getClientOriginalName();
                    // Move the uploaded file to the public/files directory
                    $file->move(public_path('files'), $fileName);

                    // Store the file metadata in the database
                    $newFile = new Files;
                    $newFile->file_data = 'files/' . $fileName; // Store file path relative to public directory
                    $newFile->order_id = $newOrderId;
                    $newFile->file_name = $fileName; // Store the unique file name
                    $newFile->file_type = $file->getClientMimeType();
                    $newFile->save();
                }
            }
        }

        // return redirect('/MyOrders')->with('success', 'Your Order Place');
        return redirect('/thank-you')->with([
            'success' => 'Your order has been placed successfully!',
            'email' => $request->input('email'), // Pass the email to the session
        ]);
    }

    public function findfiles(Request $request)
    {
        $orderId = $request->input('order_id');

        // Fetch files by order ID
        $files = Files::where('order_id', $orderId)->pluck('file_data')->toArray();

        // Return file URLs
        $fileUrls = [];
        foreach ($files as $file) {
            $fileUrls[] = asset($file); // Assuming files are stored in the public/files directory
        }

        return response()->json($fileUrls);
    }

    public function FrontEndLeadsNew(Request $request)
    {
        // Validate the reCAPTCHA token
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$recaptchaResponse) {
            return redirect()->back()->with('warning', 'This feature has been temporarily disabled for security reasons. Please contact us via WhatsApp for assistance.');
        }
        $recaptchaSecret = config('services.recaptcha.secret_key');
        $recaptchaVerification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        if (!$recaptchaVerification->json('success')) {
            return redirect()->back()->withErrors(['captcha' => 'ReCAPTCHA verification failed.']);
        }

        //date validation

        $urgencyDays = $request->input('urgency');
        $today = now();
        $deliveryDate = $today->copy();
        if (is_numeric($urgencyDays)) {
            // For numeric urgency (1 to 15 days)
            $deliveryDate = $today->addDays($urgencyDays)->format('Y-m-d');
        } elseif ($urgencyDays === '16 to 20') {
            // For '16 to 20 Days' urgency
            $deliveryDate = $today->addDays(16)->format('Y-m-d');
        } elseif ($urgencyDays === '21+') {
            // For '21+ Days' urgency
            $deliveryDate = $today->addDays(21)->format('Y-m-d');
        } else {
            // Handle any unexpected values
            return redirect()->back()->withErrors(['urgency' => 'Invalid urgency selected.']);
        }

        // Additional logic to check if delivery date is before today
        if ($deliveryDate < $today->format('Y-m-d')) {
            return redirect()->back()->withErrors(['delivery_date' => 'Assignment Deadline cannot be before today.']);
        }


        // Validate the incoming request
        $request->validate([
            'fileUpload.*' => 'file|max:102400', // Example validation for file uploads
        ]);

        // Get the latest order to generate a new order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . $newOrderNumber;

        if (Auth::user()) {
            $user = Auth::user();

            // Create a new Leads instance
            $leads = new Leads;
            $leads->order_id = $newOrderId;
            $leads->emp_id = $user->id;

            $leads->deadline = $deliveryDate;

            $leads->message = $request->input('requirements');

            $leads->email = $user->email;
            $leads->user_name = $user->name;
            $leads->countrycode = $user->countrycode;
            $leads->mobile = $user->mobile_no;
            $leads->frontendorder = 1;
            $leads->project_title = $request->input('service');
            $leads->pages = $request->input('wordCount');
            $leads->price = $request->input('finalPrice');

            $workType = $request->input('workType');
            if (strpos($workType, 'FirstClass') !== false) {
                $workType = str_replace('FirstClass', 'First Class Work', $workType);
            }

            $leads->service_type = $workType;




            $leads->save();
            $leadsId = $leads->id;

            $order = new Order;
            $order->uid = 0;
            $order->order_id = $newOrderId;
            $order->lead_id = $leadsId;
            $order->u_email = $user->email;
            $order->uname = $user->name;

            $order->message = $request->input('requirements');
            $order->order_date =  Carbon::now();

            $order->delivery_date = $deliveryDate;
            $order->save();

            if ($request->file('fileUpload')) {
                // Loop through each uploaded file
                foreach ($request->file('fileUpload') as $file) {
                    // Generate a unique file name to prevent conflicts
                    $fileName = $newOrderId . '_' . $file->getClientOriginalName();

                    // Move the uploaded file to the public/files directory
                    $file->move(public_path('files'), $fileName);

                    // Store the file metadata in the database
                    $newFile = new Files;
                    $newFile->file_data = 'files/' . $fileName; // Store file path relative to public directory
                    $newFile->order_id = $newOrderId;
                    $newFile->file_name = $fileName; // Store the unique file name
                    $newFile->file_type = $file->getClientMimeType();
                    $newFile->save();
                }
            }
        }
        if (!Auth::user()) {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                $user = new User;
                $user->email = $request->input('email') ?: 'user' . $request->input('mobile') . '@gmail.com';
                $user->mobile_no = $request->input('mobile');
                $user->name = 'user' . $request->input('mobile');
                $user->countrycode = $request->input('countrycode');
                $user->password = Hash::make('user@123');
                $user->role_id = 2;
                $user->save();

                $userId = $user->id;
                $name = $user->name;
                $email = $user->email;
            }

            // Create a new Leads instance
            $leads = new Leads;
            $leads->order_id = $newOrderId;
            $leads->emp_id = $user->id;

            $leads->deadline = $deliveryDate;

            $leads->message = $request->input('requirements');

            $leads->email = $user->email ?? $request->input('email');
            $leads->user_name = $user->name ?? 'user' . $request->input('mobile');
            $leads->countrycode = $user->countrycode ?? $request->input('countrycode');
            $leads->mobile = $user->mobile_no ?? $request->input('mobile');
            $leads->frontendorder = 1;
            $leads->project_title = $request->input('service');
            $leads->pages = $request->input('wordCount');
            $leads->price = $request->input('finalPrice');

            $workType = $request->input('workType');
            if (strpos($workType, 'FirstClass') !== false) {
                $workType = str_replace('FirstClass', 'First Class Work', $workType);
            }

            $leads->service_type = $workType;

            $leads->save();
            $leadsId = $leads->id;

            $order = new Order;
            $order->uid = 0;
            $order->order_id = $newOrderId;
            $order->lead_id = $leadsId;
            $order->u_email = $user->email ?? $request->input('email');
            $order->uname = $user->name ?? 'user' . $request->input('mobile');

            $order->message = $request->input('requirements');
            $order->order_date =  Carbon::now();

            $order->delivery_date = $deliveryDate;
            $order->save();

            if ($request->file('fileUpload')) {
                // Loop through each uploaded file
                foreach ($request->file('fileUpload') as $file) {
                    // Generate a unique file name to prevent conflicts
                    $fileName = $newOrderId . '_' . $file->getClientOriginalName();
                    // Move the uploaded file to the public/files directory
                    $file->move(public_path('files'), $fileName);

                    // Store the file metadata in the database
                    $newFile = new Files;
                    $newFile->file_data = 'files/' . $fileName; // Store file path relative to public directory
                    $newFile->order_id = $newOrderId;
                    $newFile->file_name = $fileName; // Store the unique file name
                    $newFile->file_type = $file->getClientMimeType();
                    $newFile->save();
                }
            }
        }

        //email
        $requestDetails = [
            'orderCode' => $newOrderId,
            'service' => $request->input('service'),
            'workType' => $request->input('workType'),
            'subject' => $request->input('subject'),
            'urgency' => $request->input('urgency'),
            'wordCount' => $request->input('wordCount'),
            'topic' => $request->input('topic'),
            'estimatedPrice' => $request->input('estimatedPrice'),
            'discount' => $request->input('discount'),
            'finalPrice' => $request->input('finalPrice'),
            'requirements-msg' => $request->input('requirements'),
            'email' => $request->input('email') ?: (Auth::user() ? Auth::user()->email : 'default@example.com'),
            'mobile' => $request->input('mobile') ?: (Auth::user() ? Auth::user()->mobile_no : '0000000000'),
            'countrycode' => $request->input('countrycode') ?: (Auth::user() ? Auth::user()->countrycode : '00'),
            'deliveryDate' => $deliveryDate,
            'files' => $request->file('fileUpload') ? array_map(function ($file) use ($newOrderId) {
                return [
                    'name' => $file->getClientOriginalName(),
                    'path' => 'files/' . $newOrderId . '_' . $file->getClientOriginalName(),
                ];
            }, $request->file('fileUpload')) : []
        ];

        // Send email with the request details
        Mail::to('order@assignnmentinneed.com')->send(new OrderConfirmation($requestDetails));
        return redirect('/MyOrders')->with('success', 'Your Order Place');
    }
    public function leads()
    {
        $startDate = '2024-12-01';
        $endDate = '2025-02-31 23:59:59';

        // Fetch leads with a non-null, non-zero uid and created within October 2024
        $status0Leads = Order::with('user')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereNotNull('uid')
            ->where('uid', '!=', 0)
            ->latest('id')
            ->get();

        return view('leads.leads_data', compact('status0Leads'));
    }

    public function user()
    {
        $startDate = '2025-01-01';
        $endDate = '2025-03-31 23:59:59';
        $data['all_user'] = User::whereBetween('created_at', [$startDate, $endDate])
            ->latest('id')
            ->get();
        return view('leads.user-data', compact('data'));
    }

    public function indexLead()
    {
        $latestHotDate = Leads::where('lead_status', 'Hot')
            ->max('created_at');
        // 1. Leads fetch (Top 30)
        // $leads = Leads::with('user', 'source')
        $leads = Leads::with(['user', 'source'])
            ->where('status', 0)
            ->where('is_converted', 0)
            ->where('duplicate_lead', 0)
            ->orderByRaw("
        CASE 
            WHEN lead_status IS NULL 
                 AND created_at > ? THEN 0

            WHEN lead_status = 'Hot' THEN 1
            WHEN lead_status = 'Warm' THEN 2
            WHEN lead_status = 'Cold' THEN 3
            ELSE 4
        END
    ", [$latestHotDate])
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        // 2. Counts for tabs
        $status_counts = [
            'All'             => Leads::where('status', 0)->where('is_converted', 0)->where('duplicate_lead', 0)->count(),
            'Hot'             => Leads::where('status', 0)->where('is_converted', 0)->where('lead_status', 'Hot')->where('duplicate_lead', 0)->count(),
            'Cold'            => Leads::where('status', 0)->where('is_converted', 0)->where('lead_status', 'Cold')->where('duplicate_lead', 0)->count(),
            'Warm'            => Leads::where('status', 0)->where('is_converted', 0)->where('lead_status', 'Warm')->where('duplicate_lead', 0)->count(),
            'Price'           => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Price')->where('duplicate_lead', 0)->count(),
            'Deadline'        => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Deadline')->where('duplicate_lead', 0)->count(),
            // 'Feedback'        => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Feedback')->count(),
            'Serious Concern' => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Serious Concern')->where('duplicate_lead', 0)->count(),
            'Marks'           => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Marks')->where('duplicate_lead', 0)->count(),
            'Quality'         => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Quality')->where('duplicate_lead', 0)->count(),
            'Customer Service' => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Customer Service')->where('duplicate_lead', 0)->count(),
            'Unknown'         => Leads::where('status', 0)->where('is_converted', 0)->where('l_status', 'Unknown')->where('duplicate_lead', 0)->count(),
        ];

        $service = Services::all();
        $papers = Paper::all();

        // 3. Employees Fetch (Graph Filter ke liye sabse zaroori)
        // Yahan ensure karein ki User model import ho upar
        $employees = User::where('role_id', '!=', 2)->get();

        $sources = Source::where('is_delete', 0)->get();

        // Sab variables ko compact mein pass karein
        return view('back-end.leads.index', compact('leads', 'sources', 'service', 'papers', 'status_counts', 'employees'));
    }

    public function getLeadTrackingData(Request $request)
    {
        $employeeId = $request->employee_id;
        $range = $request->range;

        // ===============================================
        // 0. Fetch Marketing Users (All role_id = 4)
        // ===============================================
        $marketingUsers = \App\Models\User::where('role_id', 4)->pluck('name', 'id')->toArray();
        $marketingUserIds = array_keys($marketingUsers);
        $marketingUserNames = array_values($marketingUsers);

        $selectedEmpName = null;
        $allNames = [];

        // Agar kisi ek employee ko select kiya hai, toh sirf uska naam dikhao
        if ($employeeId !== 'all') {
            $selectedEmpName = \App\Models\User::where('id', $employeeId)->value('name');
            $allNames = [$selectedEmpName];
        } else {
            // Agar 'All Employees' select hai, toh saare Role 4 walon ke naam graph me set kar do
            $allNames = $marketingUserNames;
        }

        // ===============================================
        // 1. Date Filtering Logic (Background filter ke liye)
        // ===============================================
        $startDate = null;
        $endDate = null;

        if ($range === 'today') {
            $startDate = \Carbon\Carbon::today();
            $endDate = \Carbon\Carbon::today()->endOfDay();
        } elseif ($range === 'yesterday') {
            $startDate = \Carbon\Carbon::yesterday();
            $endDate = \Carbon\Carbon::yesterday()->endOfDay();
        } elseif ($range === 'last_week') {
            $startDate = \Carbon\Carbon::now()->subDays(7)->startOfDay();
            $endDate = \Carbon\Carbon::now()->endOfDay();
        } elseif ($range === 'last_month') {
            $startDate = \Carbon\Carbon::now()->subMonth()->startOfDay();
            $endDate = \Carbon\Carbon::now()->endOfDay();
        } elseif ($range === 'custom' && $request->start_date && $request->end_date) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        }

        // ===============================================
        // 2. WORKED LEADS QUERY (Grouped by Employee ID)
        // ===============================================
        $workedQuery = \App\Models\Leads::query();

        if ($employeeId !== 'all') {
            $workedQuery->where('created_by', $employeeId);
        } else {
            $workedQuery->whereIn('created_by', $marketingUserIds);
        }

        if ($startDate && $endDate) {
            $workedQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $workedDataById = $workedQuery->selectRaw('created_by, COUNT(*) as count')
            ->groupBy('created_by')
            ->pluck('count', 'created_by')
            ->toArray();

        $workedDataByName = [];
        foreach ($workedDataById as $id => $count) {
            $name = $marketingUsers[$id] ?? $selectedEmpName ?? 'Unknown';
            $workedDataByName[$name] = ($workedDataByName[$name] ?? 0) + $count;
        }

        // ===============================================
        // 3. CONVERTED LEADS QUERY (Grouped by Employee Name)
        // ===============================================
        $convertedQuery = \App\Models\Leads::query()
            ->join('orders', 'leads.order_id', '=', 'orders.order_id')
            ->where('leads.is_converted', 1)
            ->whereNotNull('leads.converted_at');

        if ($employeeId !== 'all') {
            $convertedQuery->where('orders.l_converted_by', $selectedEmpName);
        } else {
            $convertedQuery->whereIn('orders.l_converted_by', $marketingUserNames);
        }

        if ($startDate && $endDate) {
            $convertedQuery->whereBetween('leads.converted_at', [$startDate, $endDate]);
        }

        $convertedDataByName = $convertedQuery->selectRaw('orders.l_converted_by as emp_name, COUNT(*) as count')
            ->groupBy('orders.l_converted_by')
            ->pluck('count', 'emp_name')
            ->toArray();

        // ===============================================
        // 4. PREPARE FINAL GRAPH DATA
        // ===============================================
        // Blank values hata kar unko Alphabetically sort karna
        $allNames = array_filter(array_unique($allNames));
        sort($allNames);

        $labels = [];
        $worked = [];
        $converted = [];

        foreach ($allNames as $name) {
            $labels[] = ucwords($name); // Ex: aman -> Aman

            // Agar array me inka record mila, toh count dikhao warna default 0
            $worked[] = $workedDataByName[$name] ?? 0;
            $converted[] = $convertedDataByName[$name] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'worked' => $worked,
            'converted' => $converted,
        ]);
    }

    public function loadMore(Request $request)
    {
        $offset = $request->get('offset', 0);
        $leads = Leads::with('user')
            ->where('status', 0)
            ->where('is_converted', 0)
            ->latest('id')->skip($offset)->take(30)->get();

        $html = '';
        foreach ($leads as $index => $lead) {
            $html .= view('back-end.leads.partials.row', [
                'lead' => $lead,
                'index' => $index + $offset, // acts like $loop->iteration
            ])->render();
        }

        return response()->json(['html' => $html, 'count' => $leads->count()]);
    }
    public function filter(Request $request)
    {
        $query = Leads::query();
        $query->where('duplicate_lead', 0);

        // Filter by order or project title
        if ($request->filled('order')) {
            $order = $request->input('order');
            $query->where(function ($q) use ($order) {
                $q->where('order_id', 'like', '%' . $order . '%')
                    ->orWhere('project_title', 'like', '%' . $order . '%');
            });
        }

        if ($request->filled('lead_status_tab')) {
            if ($request->lead_status_tab != 'All') {
                $query->where('lead_status', $request->lead_status_tab);
            }
        }

        // Filter by status
        // if ($request->filled('status')) {
        //     $query->where('l_status', $request->input('status'));
        // }
        if ($request->filled('status')) {
            $status = $request->input('status');

            if (in_array($status, ['Hot', 'Warm', 'Cold'])) {
                $query->where('lead_status', $status);
            } elseif ($status != 'All') {
                $query->where('l_status', $status);
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $type = $request->input('type');

            if ($type === 'Technical') {
                $query->where('tech', 'on');
            } elseif ($type === 'Resit') {
                $query->where('resit', 'on');
            } elseif ($type === 'First') {
                $query->where('service_type', 'First Class Work');
            }
        }

        // ================= DATE FILTER ONLY UPDATED =================

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $dateType = $request->input('date_type');

        $dateColumn = 'created_at';

        if (!empty($dateType) && in_array($dateType, ['created_at', 'deadline'], true)) {
            $dateColumn = $dateType;
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $query->whereDate($dateColumn, '>=', $dateFrom)
                ->whereDate($dateColumn, '<=', $dateTo);
        } elseif (!empty($dateFrom)) {
            $query->whereDate($dateColumn, '=', $dateFrom);
        } elseif (!empty($dateTo)) {
            $query->whereDate($dateColumn, '=', $dateTo);
        }

    // ================= END DATE FILTER =================

        // Filter by emp_id
        if ($request->filled('selectedValue')) {
            $query->where('emp_id', $request->input('selectedValue'));
        }

        // Filter by assign_type (0/1 both work)
        if ($request->filled('assign_type')) {
            $query->where('assign_type', (int) $request->assign_type);
        }

        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }

        // Filter by status = 0 and order descending
        $leads = $query->where('status', 0)
            ->orderBy('id', 'desc')
            ->where('is_converted', 0)
            ->get();

        $employees = User::where('role_id', '!=', 2)->get();

        $html = '';
        foreach ($leads as $index => $lead) {
            $html .= view('back-end.leads.partials.row', [
                'lead'  => $lead,
                'index' => $index,
            ])->render();
        }

        // Broadcast the applied filters (not the data)
        broadcast(new LeadFilterApplied($request->all()))->toOthers();

        return response()->json(['html' => $html]);
    }


    public function editLead($id)
    {
        $lead = Leads::findOrFail($id);
        $service = Services::all();
        $papers = Paper::all();
        return view('back-end.leads.edit', compact('lead', 'service', 'papers'));
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'module_code' => 'nullable|string|max:255',
            'project_title' => 'required|string|max:255',
            'pages' => 'nullable|numeric|min:1',
            'price' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable|date_format:H:i',
            'draft_required' => 'nullable|in:Yes,No',
            'draft_date' => 'nullable|date|after_or_equal:today',
            'draft_time' => 'nullable|date_format:H:i',
            'l_status' => 'nullable|in:Waiting,Quote,Confirmation',
            'service_type' => 'nullable|string|max:255',
            'paper' => 'nullable|string|max:255',         // For typeofpaper
            'semester' => 'nullable|string|max:255',         // e.g., Semester I, II...
            'tech' => 'required|in:on,off',
            'resit' => 'required|in:on,off',
        ]);

        // Find lead
        $lead = Leads::findOrFail($id);

        // Update lead values
        $lead->module_code = $validated['module_code'] ?? null;
        $lead->project_title = $validated['project_title'];
        $lead->pages = $validated['pages'] ?? null;
        $lead->price = $validated['price'] ?? null;
        $lead->deadline = $validated['delivery_date'] ?? null;
        $lead->delivery_time = $validated['delivery_time'] ?? null;
        $lead->draft_required = $validated['draft_required'] ?? 'No';
        $lead->draft_date = $validated['draft_date'] ?? null;
        $lead->draft_time = $validated['draft_time'] ?? null;
        $lead->l_status = $validated['l_status'] ?? null;
        $lead->service_type = $validated['service_type'] ?? null;
        $lead->tech = $validated['tech'] ?? null;
        $lead->resit = $validated['resit'] ?? null;
        $lead->typeofpaper = $validated['paper'] ?? null;       // Ensure "paper" field maps to "typeofpaper"
        $lead->semester = $validated['semester'] ?? null;    // Store string like "Semester I", etc.



        $lead->save();

        logActivity('Lead', [
            'type' => 'Lead Updated',
            'lead_id' => $lead->id,
            'module_code' => $lead->module_code,
            'project_title' => $lead->project_title,
            'status' => $lead->l_status,
            'service_type' => $lead->service_type,
            'action_by' => auth()->user()->name,
        ]);

        // Broadcast to others
        broadcast(new LeadUpdated($lead))->toOthers();

        return response()->json(['success' => true]);
    }



    public function updateUserNew(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Validate inputs
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'mobile' => 'required|string|max:20',
            'country_code2' => 'nullable|string|max:10',
            'mobile2' => 'nullable|string|max:20',
        ]);

        // Check if email is taken by another user
        $emailExists = User::where('email', $validated['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists for another user.'
            ], 422);
        }

        // Check if mobile is already used (only for primary mobile)
        $mobileConflict = User::where('id', '!=', $user->id)
            ->where(function ($query) use ($validated) {
                $query->where('mobile_no', $validated['mobile'])
                    ->orWhere('mobile_no2', $validated['mobile']);
            })
            ->exists();

        if ($mobileConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number is already in use by another user.'
            ], 422);
        }

        // Save updated user data
        $user->name = $validated['user_name'];
        $user->email = $validated['email'];
        $user->countrycode = $validated['country_code'];
        $user->mobile_no = $validated['mobile'];
        $user->countrycode2 = $validated['country_code2'];
        $user->mobile_no2 = $validated['mobile2'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }

    public function convertLeadData(Request $request, $lead_id)
    {
        // Step 1: Find the lead
        $lead = Leads::find($lead_id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        // Step 2: Check if order exists
        $order = Order::where('lead_id', $lead_id)->first();
        // dd($order);
        // Step 3: Create new order if not found
        if (!$order) {
            $order = new Order();
            $order->lead_id = $lead->id;
        }

        // Step 4: Map lead data to order
        $order->offer = $request->convert_type;
        $order->title = $lead->project_title;
        $order->pages = $lead->pages;
        $order->services = $lead->service_type;
        $order->uid = $lead->emp_id;
        $order->tech = $lead->tech === 'on' ? 1 : 0;
        $order->amount = $lead->price;
        $order->message = $lead->message;
        $order->module_code = $lead->module_code;
        $order->draftrequired = $lead->draft_required;
        $order->draft_date = $lead->draft_date;
        $order->draft_time = $lead->draft_time;
        $order->semester = $lead->semester;
        $order->typeofpaper = $lead->typeofpaper;
        $order->delivery_date = $lead->deadline ?? now()->toDateString();
        $order->order_date = now()->toDateString();
        $order->is_read = 1;
        $order->projectstatus = 'Other';
        $order->l_converted_by = Auth::user()->name ?? 'System';
        $order->resit = $lead->resit;

        // Step 5: Save order
        $order->save();

        // Step 6: Update lead

        $lead->is_converted = 1;
        $lead->converted_at = now();
        $lead->save();

        logActivity('Lead', [
            'type' => 'Lead Converted to Order',
            'lead_id' => $lead->id,
            'order_id' => $order->id,
            'convert_type' => $request->convert_type,
            'converted_by' => auth()->user()->name,
        ]);

        // Step 7: Return success response
        return response()->json([
            'success' => true,
            'message' => 'Lead successfully converted to order.',
            'order_id' => $order->id,
        ]);
    }


    public function fetchTemplates($userId)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-AiSensy-Project-API-Pwd' => '222488aa8678e32a9069d'
        ])->get('https://apis.aisensy.com/project-apis/v1/project/67e109077c4b230bed2fb1ff/wa_template/');

        if ($response->successful()) {
            $templates = collect($response->json()['template'] ?? [])
                ->where('status', 'APPROVED')
                ->map(function ($t) {
                    return [
                        'id' => $t['id'],
                        'name' => $t['name']
                    ];
                })
                ->values();

            return response()->json(['templates' => $templates]);
        }

        return response()->json(['error' => 'Failed to fetch templates'], 500);
    }


    public function whatsapp(Request $request, $id)
    {
        // dd($request->all());
        // 1. Find the user
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // 2. Build the full WhatsApp number
        $whatsappNumber = $user->countrycode . $user->mobile_no;

        // 3. Prepare the payload
        $payload = [
            "to" => $whatsappNumber,
            "type" => "template",
            "template" => [
                "language" => [
                    "policy" => "deterministic",
                    "code" => "en_GB"
                ],
                "name" => $request->input('template_name'), // Use the template name from the request
            ]
        ];

        // 4. Make the POST request to AiSensy API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-AiSensy-Project-API-Pwd' => '222488aa8678e32a9069d'
        ])->post('https://apis.aisensy.com/project-apis/v1/project/67e109077c4b230bed2fb1ff/messages', $payload);

        // 5. Handle the response and redirect
        if ($response->successful()) {
            return redirect('/chat/' . $whatsappNumber)
                ->with('success', 'WhatsApp message sent successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to send message. ' . $response->json('message'));
        }
    }


    public function LeadChat($leadId)
    {
        $lead = Leads::with(['call.user']) // Eager load everything needed
            ->findOrFail($leadId);

        return view('back-end.leads.partials.lead-chat-modal', compact('lead'));
    }


    public function callStore(Request  $req)
    {
        $Calls = new Calls;
        $Calls->created_by = auth()->user()->id;
        $Calls->lead_id = $req->lead_id;
        $Calls->description = $req->message;
        $Calls->save();

        logActivity('Lead', [
            'type' => 'lead chat',
            'lead_id' => $req->lead_id,
            'message' => $req->message,
            'action_by' => auth()->user()->name,
        ]);


        return response()->json(['success' => true, 'message' => 'Call details saved successfully.']);
    }


    public function export(Request $request)
    {
        $filters = $request->all();
        $timestamp = now()->format('YmdHis');
        $filename = 'exports/leads_export_' . $timestamp . '.xlsx';

        $userId = auth()->id();

        ExportLeadsJob::dispatch($filters, $filename, $userId);

        return response()->json([
            'status' => 'queued',
            'message' => 'Export started. It will be available shortly.',
        ]);
    }

    public function exportStatus()
    {
        $filePath = Cache::get('lead_export_' . auth()->id());

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            // Clear the cache so that next export will be generated fresh
            Cache::forget('lead_export_' . auth()->id());
            return response()->json([
                'status' => 'ready',
                'url' => asset('storage/app/public/' . $filePath),
            ]);
        }

        return response()->json(['status' => 'pending']);
    }
    public function assignType(Request $request)
    {
        $data = $request->validate([
            'lead_id' => 'required|integer|exists:leads,id',
            'assign_type'    => 'required|in:0,1',
        ]);

        $lead = Leads::find($data['lead_id']);

        $lead->assign_type = (int) $data['assign_type'];
        $lead->save();

        logActivity('Lead', [
            'type' => 'Assign Type Updated',
            'lead_id' => $lead->id,
            'assign_type' => $lead->assign_type,
            'updated_by' => auth()->user()->name,
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Assign type updated',
            'data' => [
                'id' => $lead->id,
                'assign_type' => $lead->assign_type
            ]
        ]);
    }


    public function updateLeadReason(Request $request)
    {
        $lead = Leads::find($request->lead_id);
        if (!$lead) {
            return response()->json(['status' => false]);
        }

        $lead->l_status = $request->l_status;
        $lead->save();

        logActivity('Lead', [
            'type' => 'Lead Reason Updated',
            'lead_id' => $lead->id,
            'status' => $lead->l_status,
            'updated_by' => auth()->user()->name,
        ]);

        return response()->json(['status' => true, 'l_status' => $request->l_status]);
    }

    public function updateLeadStatus(Request $request)
    {
        $lead = Leads::find($request->lead_id);
        if (!$lead) {
            return response()->json(['status' => false]);
        }
        $lead->lead_status = $request->status;
        $lead->save();
        logActivity('Lead', [
            'type' => 'Lead status Updated',
            'lead_id' => $lead->id,
            'status' => $lead->status,
            'updated_by' => auth()->user()->name,
        ]);
        return response()->json(['status' => true, 'lead_status' => $request->status, 'hello' => true]);
    }

    public function searchReferUsers(Request $request)
{
    $search = $request->search;

    if (!$search || strlen($search) < 2) {
        return response()->json([]);
    }

    $users = User::where('role_id', 2)
        ->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('mobile_no', 'LIKE', "%{$search}%");
        })
        ->select('id', 'name', 'email', 'mobile_no')
        ->limit(10)
        ->get();

    return response()->json($users);
}

public function duplicateLead(Request $request)
{
    $request->validate([
        'lead_id' => 'required|integer',
        'order_id' => 'required'
    ]);

    // current lead
    $currentLead = Leads::find($request->lead_id);

    if (!$currentLead) {
        return response()->json([
            'status' => false,
            'message' => 'Current lead not found.'
        ]);
    }

    // original lead
    $originalLead = Leads::where('order_id', $request->order_id)->first();

    if (!$originalLead) {
        return response()->json([
            'status' => false,
            'message' => 'Lead not found for this Order ID.'
        ]);
    }

    // same lead check
    if ($currentLead->id == $originalLead->id) {
        return response()->json([
            'status' => false,
            'message' => 'Same lead cannot be marked as duplicate.'
        ]);
    }

    // save duplicate info
    $currentLead->duplicate_lead = 1;
    $currentLead->duplicate_lead_id = $originalLead->id;
    $currentLead->duplicate_order_id = $originalLead->order_id;
    $currentLead->save();

    return response()->json([
        'status' => true,
        'message' => 'Lead marked as duplicate successfully.'
    ]);
}

public function duplicateLeads(Request $request)
{
    // $query = Leads::with('user', 'source')
    $query = Leads::with('user', 'source')
        ->where('duplicate_lead', 1)
        ->orderByDesc('id');

    // optional filters
    if ($request->filled('order_id')) {
        $query->where('order_id', 'LIKE', '%' . $request->order_id . '%');
    }
    if ($request->filled('duplicate_order_id')) {
        $query->where('duplicate_order_id', 'LIKE', '%' . $request->duplicate_order_id . '%');
    }
    if ($request->filled('user_id')) {
        $query->where('emp_id', $request->user_id);
    }

    if ($request->filled('status')) {

        $query->where('lead_status', $request->status);
    }

    $leads = $query->paginate(20)->appends($request->query());

    return view(
        'back-end.leads.duplicate-leads',
        compact('leads')
    );
}

}
