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
        // Authenticated user info (from sanctum token)
        $authUser = $request->user();

        $rules = [
            'service'      => 'required|string',
            'workType'     => 'required|string',
            'country'      => 'nullable|string',
            'subject'      => 'required|string',
            'urgency'      => 'required|string',
            'wordCount'    => 'required|integer|min:250',
            'topic'        => 'required|string',
            'requirements' => 'required|string',
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

        // Get mobile and countrycode from authenticated user
        $mobile      = preg_replace('/\D/', '', $authUser->mobile_no ?? '');
        $countryCode = preg_replace('/\D/', '', $authUser->countrycode ?? '');

        if ($countryCode && substr($mobile, 0, strlen($countryCode)) === $countryCode) {
            $mobile = substr($mobile, strlen($countryCode));
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

        // Update user's country from order (optional field)
        $authUser->country = $request->input('country');
        $authUser->save();

        // Generate Order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = 1;

        if ($latestOrder && !empty($latestOrder->order_id)) {
            $lastNumber = preg_replace('/\D/', '', $latestOrder->order_id);
            $newOrderNumber = $lastNumber ? ((int) $lastNumber + 1) : 1;
        }

        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // File upload (supports fileUpload, image, images, file, files - single or array)
        $uploadedFiles = [];
        $fileKeys = ['fileUpload', 'image', 'images', 'file', 'files'];
        $filesToProcess = [];

        foreach ($fileKeys as $key) {
            if ($request->hasFile($key)) {
                $files = $request->file($key);
                if (is_array($files)) {
                    foreach ($files as $f) {
                        if ($f && $f->isValid()) {
                            $filesToProcess[] = $f;
                        }
                    }
                } elseif ($files && $files->isValid()) {
                    $filesToProcess[] = $files;
                }
            }
        }

        if (!empty($filesToProcess)) {
            $destinationPath = base_path('images/orders');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            foreach ($filesToProcess as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $filePath = 'images/orders/' . $fileName;
                $uploadedFiles[] = $filePath;

                // Save to files table
                $newFile = new \App\Models\Files();
                $newFile->file_data = $filePath;
                $newFile->order_id = $newOrderId;
                $newFile->file_name = $fileName;
                $newFile->file_type = $file->getClientMimeType();
                $newFile->save();
            }
        }

        // Lead create
        $lead = Leads::create([
            'order_id'      => $newOrderId,
            'emp_id'        => $authUser->id,
            'deadline'      => $deliveryDate->format('Y-m-d'),
            'create_at'     => now(),
            'message'       => $request->input('requirements'),
            'email'         => $authUser->email,
            'user_name'     => $authUser->name,
            'countrycode'   => $countryCode,
            'mobile'        => $mobile,
            'frontendorder' => 1,
            'is_app_lead'   => 1,
            'project_title' => $request->input('service'),
            'pages'         => $request->input('wordCount'),
            'price'         => $request->input('finalPrice'),
            'service_type'  => str_replace('FirstClass', 'First Class Work', $request->input('workType')),
            'page_url'      => $request->input('source_page') ?? 'Mobile App',
            'subject'       => $request->input('subject'),
        ]);

        // Check if user requested wallet usage
        $useWallet = $request->boolean('use_wallet')
            || $request->boolean('wallet')
            || $request->boolean('is_wallet')
            || $request->input('use_wallet') == '1'
            || strtolower((string)$request->input('use_wallet')) === 'true'
            || $request->input('wallet') == '1'
            || strtolower((string)$request->input('wallet')) === 'true';

        $finalPrice = (float) $request->input('finalPrice', 0);
        $usedWalletAmount = 0.0;
        $newWalletBalance = null;

        if ($useWallet) {
            // Get user's wallet balance
            $credits = \App\Models\WalletTransaction::where('user_id', $authUser->id)
                ->where('type', 'credit')
                ->sum('amount');

            $debits = \App\Models\WalletTransaction::where('user_id', $authUser->id)
                ->where('type', 'debit')
                ->sum('amount');

            $calcBalance = (float) ($credits - $debits);
            $userColBalance = !is_null($authUser->Wallet) ? (float) $authUser->Wallet : null;

            $currentBalance = max(0, $userColBalance ?? $calcBalance);

            if ($currentBalance > 0 && $finalPrice > 0) {
                // Deduct maximum possible amount up to finalPrice
                $usedWalletAmount = min($currentBalance, $finalPrice);
                $newWalletBalance = $currentBalance - $usedWalletAmount;

                // Create debit transaction record
                \App\Models\WalletTransaction::create([
                    'user_id'       => $authUser->id,
                    'amount'        => $usedWalletAmount,
                    'type'          => 'debit',
                    'description'   => 'Payment for Order #' . $newOrderId . ' (wallet debit)',
                    'balance_after' => $newWalletBalance,
                ]);

                // Update user's Wallet column
                $authUser->Wallet = $newWalletBalance;
                $authUser->save();
            }
        }

        $remainingDueAmount = max(0, $finalPrice - $usedWalletAmount);

        // Pending order create
        $order = Order::create([
            'order_id'        => $newOrderId,
            'projectstatus'   => 'Pending',
            'lead_id'         => $lead->id,
            'uid'             => $authUser->id,
            'title'           => $request->input('topic'),
            'amount'          => $finalPrice,
            'received_amount' => $usedWalletAmount,
            'module_code'     => $request->input('subject'),
        ]);

        if ($usedWalletAmount > 0) {
            $order->received_amount = $usedWalletAmount;
            $order->save();

            // Record in payment_details table
            $payment = new \App\Models\Payment();
            $payment->order_id = $order->id;
            $payment->payment_date = now()->format('l d F Y h:i A');
            $payment->paid_amount = $usedWalletAmount;
            $payment->reference = 'Wallet Payment for Order #' . $newOrderId;
            $payment->payee_name = $authUser->name;
            $payment->payment_update_by = 'Wallet (Mobile App)';
            $payment->account_status = 1;
            $payment->company_accounts = 'Wallet';
            $payment->save();
        }

        return response()->json([
            'success'          => true,
            'message'          => 'Order placed successfully!',
            'order_id'         => $newOrderId,
            'lead_id'          => $lead->id,
            'is_app_lead'      => 1,
            'total_amount'     => $finalPrice,
            'received_amount'  => $usedWalletAmount,
            'due_amount'       => $remainingDueAmount,
            'wallet_used'      => $usedWalletAmount > 0,
            'wallet_deducted'  => $usedWalletAmount,
            'wallet_balance'   => $newWalletBalance !== null ? $newWalletBalance : (float) ($authUser->Wallet ?? 0),
        ], 201);
    }

    public function orderList(Request $request)
    {
        $user = $request->user();

        // Confirmed orders
        $ordersRaw = DB::table('orders')
            ->where('uid', $user->id)
            ->orderByDesc('id')
            ->limit(50)
            ->select(
                'id',
                'order_id',
                'uid',
                'lead_id',
                'order_date',
                'delivery_date',
                'title',
                'module_code',
                'projectstatus',
                'pages',
                'amount',
                'received_amount',
                'created_at'
            )
            ->get();

        // Non-confirmed leads
        $leadsRaw = DB::table('leads')
            ->where('emp_id', $user->id)
            // ->where('is_app_lead', 1)
            ->orderByDesc('id')
            ->limit(50)
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
                'create_at',
                'subject'
            )
            ->get();

        // Collect all possible order identifiers (string order_id, order db id, lead id)
        $allIds = collect();
        foreach ($ordersRaw as $o) {
            if (!empty($o->order_id)) $allIds->push((string) $o->order_id);
            if (!empty($o->id)) $allIds->push((string) $o->id);
            if (!empty($o->lead_id)) $allIds->push((string) $o->lead_id);
        }
        foreach ($leadsRaw as $l) {
            if (!empty($l->order_id)) $allIds->push((string) $l->order_id);
            if (!empty($l->id)) $allIds->push((string) $l->id);
        }
        $allIds = $allIds->unique()->filter()->values()->toArray();

        // Fetch files for any of these identifiers
        $filesRaw = collect();
        if (!empty($allIds)) {
            $filesQuery = DB::table('files');
            $filesQuery->where(function ($q) use ($allIds) {
                $q->whereIn('order_id', $allIds);
                if (\Illuminate\Support\Facades\Schema::hasColumn('files', 'order_Id')) {
                    $q->orWhereIn('order_Id', $allIds);
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('files', 'lead_id')) {
                    $q->orWhereIn('lead_id', $allIds);
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('files', 'lead_Id')) {
                    $q->orWhereIn('lead_Id', $allIds);
                }
            });
            $filesRaw = $filesQuery->get();
        }

        $getFileUrls = function ($orderId, $dbId = null, $leadId = null, $onlyImages = false) use ($filesRaw) {
            $matchedFiles = $filesRaw->filter(function ($file) use ($orderId, $dbId, $leadId) {
                $fileArr = array_change_key_case((array) $file, CASE_LOWER);
                $fOrderId = isset($fileArr['order_id']) ? (string) $fileArr['order_id'] : null;
                $fLeadId  = isset($fileArr['lead_id'])  ? (string) $fileArr['lead_id']  : null;

                $targetOrderId = $orderId ? (string) $orderId : null;
                $targetDbId    = $dbId    ? (string) $dbId    : null;
                $targetLeadId  = $leadId  ? (string) $leadId  : null;

                return ($targetOrderId && $fOrderId === $targetOrderId) ||
                       ($targetDbId    && $fOrderId === $targetDbId)    ||
                       ($targetLeadId  && $fOrderId === $targetLeadId)  ||
                       ($targetLeadId  && $fLeadId  === $targetLeadId)  ||
                       ($targetDbId    && $fLeadId  === $targetDbId);
            });

            return $matchedFiles->map(function ($file) use ($onlyImages) {
                $fileArr = array_change_key_case((array) $file, CASE_LOWER);
                $path = $fileArr['file_data'] ?? $fileArr['file_name'] ?? $fileArr['path'] ?? '';
                if (empty($path)) {
                    return null;
                }

                if ($onlyImages) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
                    $fileType = strtolower($fileArr['file_type'] ?? '');
                    if (!in_array($ext, $imageExtensions) && !str_contains($fileType, 'image')) {
                        return null;
                    }
                }

                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    return $path;
                }

                if (!str_contains($path, '/')) {
                    $path = 'images/orders/' . $path;
                }

                return asset(ltrim($path, '/'));
            })->filter()->values()->all();
        };

        // Confirmed orders map
        $orders = $ordersRaw->map(function ($order) use ($getFileUrls) {
            $amount = is_numeric($order->amount) ? (float) $order->amount : 0;
            $received = is_numeric($order->received_amount) ? (float) $order->received_amount : 0;

            return [
                'type' => 'confirmed',
                'confirmed_status' => 'Confirmed',
                'order_db_id' => $order->id,
                'lead_id' => $order->lead_id,
                'order_id' => $order->order_id,
                'order_date' => $order->order_date,
                'delivery_date' => $order->delivery_date,
                'title' => $order->title,
                'module_code' => $order->module_code,
                'subject' => $order->module_code,
                'status' => $order->projectstatus,
                'word_count' => $order->pages,
                'amount' => $order->amount,
                'received_amount' => $order->received_amount,
                'due_amount' => $amount - $received,
                'created_at' => $order->created_at,
                'images' => $getFileUrls($order->order_id, $order->id, $order->lead_id, true),
                'files' => $getFileUrls($order->order_id, $order->id, $order->lead_id, false),
            ];
        });

        // Non-confirmed leads map
        $leads = $leadsRaw->map(function ($lead) use ($getFileUrls) {
            return [
                'type' => 'non_confirmed',
                'confirmed_status' => $lead->is_converted == 1 ? 'Confirmed' : 'Not Confirmed',
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
                'created_at' => $lead->create_at,
                'subject' => $lead->subject,
                'images' => $getFileUrls($lead->order_id, null, $lead->id, true),
                'files' => $getFileUrls($lead->order_id, null, $lead->id, false),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'confirmed_orders' => $orders,
                'non_confirmed_leads' => $leads,
                'summary' => [
                    'confirmed_count' => $orders->count(),
                    'non_confirmed_count' => $leads->count(),
                ]
            ]
        ]);
    }

    public function raiseTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'comment'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $orderIdInput = $request->input('order_id');
        $order = Order::where('id', $orderIdInput)
            ->orWhere('order_id', $orderIdInput)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $commentText = $request->input('comment');

        // Logic same as web order comments (insert_feedback)
        if ($order->feedbackissue == 1 && $order->status_issue == 'Case Resolved') {
            $order->status_issue = 'Issues Raised Again';
        }
        if ($order->feedbackissue != 1) {
            $order->status_issue = 'Issue Raised';
            $order->feedback_ticket = 'TCK-' . substr($order->order_id, 3);
        }
        $order->feedbackissue = 1;
        $order->comment = $commentText;
        $order->feedback_date = Carbon::now();
        $order->save();

        $feedback = new \App\Models\Feedback;
        $feedback->order_id = $order->id;
        $feedback->comment = $commentText;
        $feedback->created_by = $request->user()->id;
        $feedback->save();

        logActivity('Order', [
            'type' => 'Feedback Added',
            'order_id' => $order->order_id,
            'message' => $commentText,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket raised successfully!',
            'data' => [
                'feedback_id' => $feedback->id,
                'feedback_ticket' => $order->feedback_ticket,
                'status_issue' => $order->status_issue,
                'comment' => $feedback->comment,
                'created_at' => $feedback->created_at ? $feedback->created_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A'),
            ]
        ], 200);
    }

    public function getTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $orderIdInput = $request->input('order_id');
        $order = Order::with(['feedback.user'])
            ->where('id', $orderIdInput)
            ->orWhere('order_id', $orderIdInput)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Fetch feedback entries
        $comments = $order->feedback->sortBy('created_at')->map(function ($fb) {
            $isCurrentUser = $fb->created_by == auth()->id();
            
            // Determine type of chat item
            $type = 'Order Chat';
            $messageText = $fb->comment;
            
            if ($fb->status === 'Referred') {
                $type = 'Referral Chat';
            } elseif (!empty($fb->action_comment)) {
                $type = 'Ticket Chat';
                $messageText = $fb->action_comment;
            }

            return [
                'id' => $fb->id,
                'type' => $type,
                'message' => $messageText,
                'status' => $fb->status,
                'sender_id' => $fb->created_by,
                'sender_name' => $isCurrentUser ? 'You' : ($fb->user->name ?? 'Agent'),
                'is_current_user' => $isCurrentUser,
                'created_at' => $fb->created_at ? $fb->created_at->format('d M Y, h:i A') : null,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'order_code' => $order->order_id,
                'ticket_no' => $order->feedback_ticket,
                'ticket_status' => $order->status_issue,
                'is_ticket_raised' => (int) $order->feedbackissue === 1,
                'ticket_date' => $order->feedback_date ? Carbon::parse($order->feedback_date)->format('d M Y, h:i A') : null,
                'messages' => $comments
            ]
        ], 200);
    }

    public function userTickets(Request $request)
    {
        $user = $request->user();

        // Get all orders belonging to the user that have raised tickets
        $orders = Order::with(['feedback' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->where('uid', $user->id)
        ->where(function($q) {
            $q->where('feedbackissue', 1)
              ->orWhereNotNull('feedback_ticket');
        })
        ->orderByDesc('feedback_date')
        ->get()
        ->map(function ($order) {
            $latestFeedback = $order->feedback->first();
            $lastMessage = '';
            if ($latestFeedback) {
                $lastMessage = !empty($latestFeedback->action_comment) ? $latestFeedback->action_comment : $latestFeedback->comment;
            }

            return [
                'order_db_id'      => $order->id,
                'order_code'       => $order->order_id,
                'ticket_no'        => $order->feedback_ticket,
                'ticket_status'    => $order->status_issue ?? 'Pending',
                'ticket_date'      => $order->feedback_date ? Carbon::parse($order->feedback_date)->format('d M Y, h:i A') : null,
                'last_message'     => $lastMessage,
                'last_message_at'  => $latestFeedback && $latestFeedback->created_at ? $latestFeedback->created_at->format('d M Y, h:i A') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }

    public function walletAmount(Request $request)
    {
        $user = $request->user();

        $credits = \App\Models\WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->sum('amount');

        $debits = \App\Models\WalletTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->sum('amount');

        $walletAmount = (float) ($credits - $debits);
        if (!is_null($user->Wallet)) {
            $walletAmount = (float) $user->Wallet;
        }
        if ($walletAmount < 0) {
            $walletAmount = 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'wallet_amount' => $walletAmount,
                // 'total_credit' => (float) $credits,
                // 'total_debit' => (float) $debits,
                'currency' => 'GBP'
            ]
        ], 200);
    }

    public function walletTransactions(Request $request)
    {
        $user = $request->user();

        $transactions = \App\Models\WalletTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        $mappedTransactions = collect($transactions->items())->map(function ($tx) {
            return [
                'id' => $tx->id,
                'amount' => (float) $tx->amount,
                'type' => $tx->type,
                'description' => $tx->description,
                'is_expired' => (int) $tx->is_expired === 1,
                'expires_at' => $tx->expires_at ? Carbon::parse($tx->expires_at)->format('d M Y, h:i A') : null,
                'created_at' => $tx->created_at ? $tx->created_at->format('d M Y, h:i A') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $mappedTransactions,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'has_more' => $transactions->hasMorePages(),
                ]
            ]
        ], 200);
    }

    public function submitAppFeedback(Request $request)
    {
        $user = $request->user();

        // Fallback for raw JSON if Content-Type header is missing
        $data = $request->all();
        if (empty($data) && $request->getContent()) {
            $parsed = json_decode($request->getContent(), true);
            if (is_array($parsed)) {
                $data = $parsed;
            }
        }

        $validator = Validator::make($data, [
            'order_id' => 'required|string',
            'experience' => 'nullable|string',
            'feedback_scope' => 'nullable',
            'your_suggestion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $scopeInput = $data['feedback_scope'] ?? '';
            $finalScope = is_array($scopeInput) ? implode(', ', $scopeInput) : $scopeInput;

            DB::table('feedbacks')->insert([
                'order_id'        => $data['order_id'] ?? '',
                'experience'      => $data['experience'] ?? '',
                'feedback_scope'  => $finalScope,
                'your_suggestion' => $data['your_suggestion'] ?? '',
                // Assuming you might want to track who submitted it from the app:
                // 'user_id'         => $user->id,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->all();
        if (empty($data) && $request->getContent()) {
            $parsed = json_decode($request->getContent(), true);
            if (is_array($parsed)) {
                $data = $parsed;
            }
        }

        $validator = Validator::make($data, [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $couponCode = trim($data['coupon_code']);

        $coupon = \App\Models\Coupon::where('coupon_code', $couponCode)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive coupon code.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'data' => [
                'coupon_code' => $coupon->coupon_code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
            ]
        ], 200);
    }

    public function couponList()
    {
        $coupons = \App\Models\Coupon::where('is_active', 1)
            ->orderByDesc('id')
            ->select('id', 'coupon_code', 'discount_type', 'discount_value', 'created_at')
            ->get()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'coupon_code' => $coupon->coupon_code,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => (float) $coupon->discount_value,
                    'created_at' => $coupon->created_at ? $coupon->created_at->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Coupons fetched successfully',
            'data' => $coupons
        ], 200);
    }

    public function webPlaceOrder(Request $request)
    {
        $rules = [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'countrycode'  => 'required|string',
            'phone'        => 'required|string',
            'service'      => 'required|string',
            'workType'     => 'required|string',
            'country'      => 'required|string',
            'subject'      => 'required|string',
            'urgency'      => 'required|string',
            'wordCount'    => 'required|integer|min:250',
            'pages'        => 'required|integer|min:1',
            'topic'        => 'nullable|string',
            'requirements' => 'nullable|string',
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

        // Find or create the user by email
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            $user = User::create([
                'name'        => $request->input('name'),
                'email'       => $request->input('email'),
                'countrycode' => preg_replace('/\D/', '', $request->input('countrycode')),
                'mobile_no'   => preg_replace('/\D/', '', $request->input('phone')),
                'password'    => Hash::make(\Illuminate\Support\Str::random(12)),
                'role_id'     => 2, // standard Client role
            ]);
        } else {
            // Update user details if user exists to keep it updated
            $user->countrycode = preg_replace('/\D/', '', $request->input('countrycode'));
            $user->mobile_no   = preg_replace('/\D/', '', $request->input('phone'));
        }

        $user->country = $request->input('country');
        $user->save();

        // Get cleaned mobile and country code
        $countryCode = preg_replace('/\D/', '', $request->input('countrycode'));
        $mobile      = preg_replace('/\D/', '', $request->input('phone'));

        if ($countryCode && substr($mobile, 0, strlen($countryCode)) === $countryCode) {
            $mobile = substr($mobile, strlen($countryCode));
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

        // Generate Order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = 1;

        if ($latestOrder && !empty($latestOrder->order_id)) {
            $lastNumber = preg_replace('/\D/', '', $latestOrder->order_id);
            $newOrderNumber = $lastNumber ? ((int) $lastNumber + 1) : 1;
        }

        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // File upload
        $uploadedFiles = [];

        if ($request->hasFile('fileUpload')) {
            foreach ($request->file('fileUpload') as $file) {
                $destinationPath = base_path('images/orders');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $filePath = 'images/orders/' . $fileName;
                $uploadedFiles[] = $filePath;

                // Save to files table
                $newFile = new \App\Models\Files();
                $newFile->file_data = $filePath;
                $newFile->order_id = $newOrderId;
                $newFile->file_name = $fileName;
                $newFile->file_type = $file->getClientMimeType();
                $newFile->save();
            }
        }

        // Lead create
        $lead = Leads::create([
            'order_id'      => $newOrderId,
            'emp_id'        => $user->id,
            'deadline'      => $deliveryDate->format('Y-m-d'),
            'create_at'     => now(),
            'message'       => $request->input('requirements') ?? 'Order submitted from website.',
            'email'         => $user->email,
            'user_name'     => $user->name,
            'countrycode'   => $countryCode,
            'mobile'        => $mobile,
            'frontendorder' => 1,
            'is_app_lead'   => 0, // 0 for web lead
            'project_title' => $request->input('service'),
            'pages'         => $request->input('pages'), // Using the web 'pages' field!
            'price'         => $request->input('finalPrice'),
            'service_type'  => str_replace('FirstClass', 'First Class Work', $request->input('workType')),
            'page_url'      => $request->input('source_page') ?? 'Website',
            'subject'       => $request->input('subject'),
        ]);

        // Pending order create
        Order::create([
            'order_id'      => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id'       => $lead->id,
            'uid'           => $user->id,
            'title'         => $request->input('topic') ?? $request->input('service'),
            'amount'        => $request->input('finalPrice'),
            'module_code'   => $request->input('subject'),
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Order placed successfully!',
            'order_id'    => $newOrderId,
            'lead_id'     => $lead->id,
            'is_app_lead' => 0,
        ], 201);
    }

    public function submitMiniQuote(Request $request)
    {
        // Validation rules mapped to the fields in the 'new-hero-form' / frontend screenshot design:
        // Name, Email, Country Code, Mobile, Project Type (service), Subject, Deadline, Word Count, and Description
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string',
            'countryCode'    => 'required|string',
            'countryIso'     => 'nullable|string',
            'service'        => 'required|string',        // Project Type (service) -> mapped totypeofpaper
            'subject'        => 'required|string|max:255', // Subject dropdown value -> mapped to project_title
            'deadline'       => 'required|string',        // Time Period (deadline/urgency)
            'wordCount'      => 'required|string',        // Word Count (number e.g. 250, 500, 1000) -> pages mapping
            'description'    => 'nullable|string',        // Nullable description/requirements
            'source_page'    => 'nullable|string',
        ]);

        // Clean values
        $phoneDigits = preg_replace('/\D+/', '', (string) $request->phone);
        $cc = preg_replace('/\D+/', '', (string) $request->countryCode);

        // Map wordCount to pages: e.g. 250 -> 1, 500 -> 2, 1000 -> 4, etc.
        $words = (int) $request->wordCount;
        $pagesCount = max(1, (int) round($words / 250));

        // Calculate delivery/deadline date
        $deadlineInput = trim((string) $request->deadline);
        $today = now();
        if (is_numeric($deadlineInput)) {
            $days = max(1, (int) $deadlineInput);
            $deliveryDate = $today->copy()->addDays($days);
            $deliveryTimeStr = $days . ' Day' . ($days > 1 ? 's' : '');
        } elseif ($deadlineInput === '16 to 20') {
            $deliveryDate = $today->copy()->addDays(16);
            $deliveryTimeStr = '16 to 20 Days';
        } elseif ($deadlineInput === '21+') {
            $deliveryDate = $today->copy()->addDays(21);
            $deliveryTimeStr = '21+ Days';
        } else {
            try {
                $deliveryDate = \Carbon\Carbon::parse($deadlineInput);
                $deliveryTimeStr = $deadlineInput;
            } catch (\Exception $e) {
                $deliveryDate = $today->copy()->addDays(1);
                $deliveryTimeStr = '1 Day';
            }
        }

        // Generate Order ID UKS...
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = 1;
        if ($latestOrder && !empty($latestOrder->order_id)) {
            $lastNumber = preg_replace('/\D/', '', $latestOrder->order_id);
            $newOrderNumber = $lastNumber ? ((int) $lastNumber + 1) : 1;
        }
        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // Find/Create standard client user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'mobile_no'   => $phoneDigits,
                'countrycode' => $cc,
                'password'    => Hash::make(\Illuminate\Support\Str::random(12)),
                'role_id'     => 2, // standard Client role
            ]);
        } else {
            // Update missing user details if user exists
            $dirty = false;
            if (empty($user->mobile_no) && $phoneDigits) {
                $user->mobile_no = $phoneDigits;
                $dirty = true;
            }
            if (empty($user->countrycode) && $cc) {
                $user->countrycode = $cc;
                $dirty = true;
            }
            if (empty($user->name) && $request->name) {
                $user->name = $request->name;
                $dirty = true;
            }
            if ($dirty) {
                $user->save();
            }
        }

        // Create lead entry
        $lead = Leads::create([
            'order_id'       => $newOrderId,
            'emp_id'         => $user->id,
            'create_at'      => now(),
            'frontendorder'  => 1,
            'is_app_lead'    => 0, // 0 for website lead
            'user_name'      => $user->name,
            'email'          => $user->email,
            'countrycode'    => $cc,
            'mobile'         => $phoneDigits,
            'typeofpaper'    => $request->service,          // Project Type
            'project_title'  => $request->subject,          // Dynamic Subject
            'pages'          => $pagesCount,                // Calculated pages from wordCount
            'deadline'       => $deliveryDate->toDateString(),
            'delivery_time'  => $deliveryTimeStr,
            'message'        => $request->input('description') ?? 'Quote request from website hero form.',
            'page_url'       => $request->input('source_page') ?? ($request->headers->get('referer') ?? $request->fullUrl()),
        ]);

        // Create pending order
        Order::create([
            'order_id'      => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id'       => $lead->id,
            'uid'           => $user->id,
            'title'         => $request->subject . ' - ' . $request->service . ' - Quote Request',
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Quote submitted successfully!',
            'order_id' => $newOrderId,
        ]);
    }

    public function getBanks(Request $request)
    {
        $banks = \App\Models\Bank::select('id', 'name', 'account_holder', 'account_number', 'sort_code')->get();

        return response()->json([
            'success' => true,
            'data'    => $banks
        ], 200);
    }

    public function addPayment(Request $request)
    {
        $authUser = $request->user();

        $rules = [
            'order_db_id'      => 'nullable|integer',
            'order_id'         => 'nullable|string',
            'paid_amount'      => 'nullable|numeric|min:0.01',
            'company_accounts' => 'nullable|string',
            'payee_name'       => 'nullable|string',
            'reference'        => 'nullable|string',
            'screenshot'       => 'nullable|file|image|mimes:jpeg,png,jpg,gif,pdf|max:10240',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $orderQuery = Order::where('uid', $authUser->id);

        if ($request->filled('order_db_id')) {
            $orderQuery->where('id', $request->input('order_db_id'));
        } elseif ($request->filled('order_id')) {
            $orderQuery->where('order_id', $request->input('order_id'));
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Either order_db_id or order_id is required.'
            ], 422);
        }

        $order = $orderQuery->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        $remainingAmount = (float)$order->amount - (float)$order->received_amount;
        $paidAmount = (float)$request->input('paid_amount');

        if ($paidAmount > $remainingAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Paid amount exceeds the remaining due amount of ' . $remainingAmount
            ], 422);
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $destinationPath = base_path('images/payments');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            $screenshotPath = 'images/payments/' . $fileName;
        }

        $payment = new \App\Models\Payment();
        $payment->order_id = $order->id;
        $payment->payment_date = now()->format('l d F Y h:i A');
        $payment->paid_amount = $paidAmount;
        $payment->reference = $request->input('reference') ?? $request->input('message');
        $payment->payee_name = $request->input('payee_name') ?? $authUser->name;
        $payment->payment_update_by = 'Mobile App';
        $payment->account_status = 0;
        $payment->company_accounts = $request->input('company_accounts');
        $payment->screenshot = $screenshotPath;
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment details and receipt uploaded successfully. Awaiting admin approval.',
            'payment' => [
                'id' => $payment->id,
                'order_id' => $order->order_id,
                'paid_amount' => $payment->paid_amount,
                'account_status' => $payment->account_status,
                'screenshot_url' => $payment->screenshot ? asset($payment->screenshot) : null
            ]
        ], 201);
    }

    public function editOrder(Request $request)
    {
        // Authenticated user info (from sanctum token)
        $authUser = $request->user();

        $rules = [
            'order_id'     => 'required',
            'service'      => 'nullable|string',
            'workType'     => 'nullable|string',
            'country'      => 'nullable|string',
            'subject'      => 'nullable|string',
            'urgency'      => 'nullable|string',
            'wordCount'    => 'nullable|integer|min:250',
            'topic'        => 'nullable|string',
            'requirements' => 'nullable|string',
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

        $orderIdInput = $request->input('order_id');

        // Find Lead or Order belonging to authenticated user
        $lead = Leads::where(function ($q) use ($orderIdInput) {
            $q->where('order_id', $orderIdInput)
              ->orWhere('id', $orderIdInput);
        })->where('emp_id', $authUser->id)->first();

        $order = Order::where(function ($q) use ($orderIdInput) {
            $q->where('order_id', $orderIdInput)
              ->orWhere('id', $orderIdInput);
        })->where('uid', $authUser->id)->first();

        if (!$lead && !$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or access denied.'
            ], 404);
        }

        $actualOrderId = $lead->order_id ?? $order->order_id ?? $orderIdInput;

        // Delivery date calculation if urgency is provided
        $deliveryDate = null;
        if ($request->filled('urgency')) {
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
        }

        // Update user country if provided
        if ($request->filled('country')) {
            $authUser->country = $request->input('country');
            $authUser->save();
        }

        // Upload new files if present (supports fileUpload, image, images, file, files)
        $fileKeys = ['fileUpload', 'image', 'images', 'file', 'files'];
        $filesToProcess = [];

        foreach ($fileKeys as $key) {
            if ($request->hasFile($key)) {
                $files = $request->file($key);
                if (is_array($files)) {
                    foreach ($files as $f) {
                        if ($f && $f->isValid()) {
                            $filesToProcess[] = $f;
                        }
                    }
                } elseif ($files && $files->isValid()) {
                    $filesToProcess[] = $files;
                }
            }
        }

        if (!empty($filesToProcess)) {
            $destinationPath = base_path('images/orders');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            foreach ($filesToProcess as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $filePath = 'images/orders/' . $fileName;

                $newFile = new \App\Models\Files();
                $newFile->file_data = $filePath;
                $newFile->order_id = $actualOrderId;
                $newFile->file_name = $fileName;
                $newFile->file_type = $file->getClientMimeType();
                $newFile->save();
            }
        }

        // Update Lead if exists
        if ($lead) {
            if ($request->filled('service')) {
                $lead->project_title = $request->input('service');
            }
            if ($request->filled('workType')) {
                $lead->service_type = str_replace('FirstClass', 'First Class Work', $request->input('workType'));
            }
            if ($request->filled('subject')) {
                $lead->subject = $request->input('subject');
            }
            if ($deliveryDate) {
                $lead->deadline = $deliveryDate->format('Y-m-d');
            }
            if ($request->filled('wordCount')) {
                $lead->pages = $request->input('wordCount');
            }
            if ($request->filled('requirements')) {
                $lead->message = $request->input('requirements');
            }
            if ($request->filled('finalPrice')) {
                $lead->price = $request->input('finalPrice');
            }
            if ($request->filled('source_page')) {
                $lead->page_url = $request->input('source_page');
            }
            $lead->save();
        }

        // Update Order if exists
        if ($order) {
            if ($request->filled('topic')) {
                $order->title = $request->input('topic');
            }
            if ($request->filled('subject')) {
                $order->module_code = $request->input('subject');
            }
            if ($request->filled('finalPrice')) {
                $order->amount = $request->input('finalPrice');
            }
            if ($request->filled('wordCount')) {
                $order->pages = $request->input('wordCount');
            }
            if ($deliveryDate) {
                $order->delivery_date = $deliveryDate->format('Y-m-d');
            }
            $order->save();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Order updated successfully!',
            'order_id'  => $actualOrderId,
            'lead_id'   => $lead ? $lead->id : null,
        ], 200);
    }
}