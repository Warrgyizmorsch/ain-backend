<?php

namespace App\Http\Controllers;

use App\Models\NextLead;
use App\Models\Leads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class NextLeadController extends Controller
{
    /**
     * Store a new Next Lead record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50',
            'target_month' => 'required|string|max:20',
        ]);

        $mobile = preg_replace('/\D/', '', $request->mobile);
        $existingUser = User::where('mobile_no', 'LIKE', "%{$mobile}")->first();

        $email = $request->input('email');
        if (empty($email)) {
            $email = 'next_lead_' . time() . rand(100, 999) . '@gmail.com';
        }

        $nextLead = NextLead::create([
            'user_name' => $request->user_name,
            'countrycode' => $request->input('countrycode', '+44'),
            'mobile' => $request->mobile,
            'email' => $email,
            'emp_id' => $existingUser ? $existingUser->id : null,
            'target_month' => $request->target_month,
            'message' => $request->input('message'),
            'created_by' => Auth::id(),
            'is_converted' => 0,
        ]);

        $currentMonthCount = $this->getCurrentMonthCount();

        return response()->json([
            'success' => true,
            'message' => 'Next Lead created successfully.',
            'count' => $currentMonthCount,
            'data' => $nextLead,
        ]);
    }

    /**
     * List / Filter Next Leads.
     */
    public function list(Request $request)
    {
        $query = NextLead::with(['creator', 'user'])->where('is_converted', 0);

        if ($request->filled('target_month') && $request->target_month !== 'all') {
            $query->where('target_month', $request->target_month);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanDigits = preg_replace('/\D/', '', $search);
            $query->where(function ($q) use ($search, $cleanDigits) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('creator', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile_no', 'like', "%{$search}%");
                  });

                if (!empty($cleanDigits) && strlen($cleanDigits) >= 4) {
                    $q->orWhere('mobile', 'like', "%{$cleanDigits}%");
                }
            });
        }

        if ($request->filled('created_by') && $request->created_by !== 'all') {
            $query->where('created_by', $request->created_by);
        }

        $nextLeads = $query->orderBy('id', 'desc')->get();
        $creators = User::whereIn('id', NextLead::pluck('created_by')->unique())->select('id', 'name')->get();
        $currentMonth = date('Y-m');

        if ($request->ajax() && $request->has('render_table_only')) {
            return view('back-end.leads.partials.next-leads-table-rows', compact('nextLeads'))->render();
        }

        return view('back-end.leads.partials.next-leads-list-modal', compact('nextLeads', 'creators', 'currentMonth'));
    }

    /**
     * Convert Next Lead into an Active Lead.
     */
    public function convert($id)
    {
        $nextLead = NextLead::findOrFail($id);

        if ($nextLead->is_converted == 1) {
            return response()->json([
                'success' => false,
                'message' => 'This Next Lead is already converted.',
            ]);
        }

        // 1. Generate Order ID (Order Code) like normal lead creation
        $latestOrder = \App\Models\Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? intval(substr($latestOrder->order_id, 3)) : 0;
        $newOrderNumber++;
        $newOrderId = 'UKS' . $newOrderNumber;

        // 2. Customer Lookup / Association or Auto-Create New Customer
        $userId = $nextLead->emp_id;
        if (empty($userId) && !empty($nextLead->mobile)) {
            $cleanMobile = preg_replace('/\D/', '', $nextLead->mobile);
            $withoutZero = !empty($cleanMobile) ? ltrim($cleanMobile, '0') : '';

            $existingUser = User::where('mobile_no', 'LIKE', "%{$cleanMobile}%")
                ->orWhere('mobile_no2', 'LIKE', "%{$cleanMobile}%")
                ->first();

            if (!$existingUser && !empty($withoutZero)) {
                $existingUser = User::where('mobile_no', 'LIKE', "%{$withoutZero}%")
                    ->orWhere('mobile_no2', 'LIKE', "%{$withoutZero}%")
                    ->first();
            }

            if (!$existingUser && !empty($nextLead->email)) {
                $existingUser = User::where('email', $nextLead->email)->first();
            }

            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        // If customer does NOT exist in users table, create a NEW USER (like normal lead flow)
        if (empty($userId)) {
            $newUser = new User();
            $newUser->email = !empty($nextLead->email) ? $nextLead->email : ('user' . preg_replace('/\D/', '', $nextLead->mobile) . '@gmail.com');
            $newUser->mobile_no = $nextLead->mobile;
            $newUser->name = !empty($nextLead->user_name) ? $nextLead->user_name : ('user' . preg_replace('/\D/', '', $nextLead->mobile));
            $newUser->countrycode = $nextLead->countrycode ?: '+44';
            $newUser->password = \Illuminate\Support\Facades\Hash::make('user@123');
            $newUser->role_id = 2;
            $newUser->save();

            $userId = $newUser->id;
        }

        $creatorId = $nextLead->created_by ?: Auth::id();

        // 3. Create Active Lead in leads table with Order Code
        $lead = new Leads();
        $lead->order_id = $newOrderId;
        $lead->user_name = $nextLead->user_name;
        $lead->email = $nextLead->email;
        $lead->countrycode = $nextLead->countrycode;
        $lead->mobile = $nextLead->mobile;
        $lead->emp_id = $userId;
        $lead->message = $nextLead->message;
        $lead->l_status = 'Hot';
        $lead->created_by = $creatorId;
        $lead->create_at = now();
        $lead->is_converted = 0;
        $lead->status = 0;
        $lead->save();

        // 4. Create corresponding Order entry (like normal lead creation flow)
        $order = new \App\Models\Order();
        $order->uid = $userId ?: 0;
        $order->order_id = $newOrderId;
        $order->lead_id = $lead->id;
        $order->created_by = $creatorId;
        $order->message = $nextLead->message;
        $order->order_date = now();
        $order->delivery_date = now()->addDays(7);
        $order->projectstatus = 'Initiated';
        $order->l_converted_by = Auth::user()->name ?? 'System';
        $order->save();

        // 5. Update NextLead entry
        $nextLead->is_converted = 1;
        $nextLead->converted_lead_id = $lead->id;
        $nextLead->converted_at = now();
        $nextLead->save();

        $currentMonthCount = $this->getCurrentMonthCount();

        return response()->json([
            'success' => true,
            'message' => 'Next Lead successfully converted! Generated Order Code: ' . $newOrderId,
            'count' => $currentMonthCount,
            'lead_id' => $lead->id,
            'order_id' => $newOrderId,
        ]);
    }

    /**
     * Helper to get unconverted count for current running month.
     */
    public function getCurrentMonthCount()
    {
        $currentMonth = date('Y-m');
        return NextLead::where('target_month', $currentMonth)
            ->where('is_converted', 0)
            ->count();
    }
}
