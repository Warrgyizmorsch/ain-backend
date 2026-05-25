<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Team;
use App\Models\User;
use App\Models\Writer;
use App\Models\Status;
use App\Models\Formatting;
use App\Models\Services;
use App\Models\Writting;
use App\Models\Paper;
use App\Models\Payment;
use App\Models\Feedback;
use App\Models\College;
use App\Models\multipleswiter;
use App\Models\Leads;
use App\Models\Ordercall;
use App\Models\ProjectStatusCount;
use App\Models\FollowUpComment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderComplete;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ExportOrdersJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;


class OrderController extends Controller
{

    public function export(Request $request)
    {
        $filters = $request->all();
        $timestamp = now()->format('YmdHis');
        $filename = 'exports/orders_export_' . $timestamp . '.xlsx';

        $userId = auth()->id();

        ExportOrdersJob::dispatch($filters, $filename, $userId);

        return response()->json([
            'status' => 'queued',
            'message' => 'Export started. It will be available shortly.',
        ]);
    }

    public function exportStatus()
    {
        $filePath = Cache::get('order_export_' . auth()->id());

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            // Clear the cache so that next export will be generated fresh  
            Cache::forget('order_export_' . auth()->id());
            return response()->json([
                'status' => 'ready',
                'url' => asset('storage/app/public/' . $filePath),
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    public function index(Request $request)
    {
        $userRole = auth()->user()->role_id;

        switch ($userRole) {
            case 1:
                return $this->handleRoleOne($request);
            case 2:
                return $this->handleRoleTwo($request);
            case 3:
                return $this->handleRoleThree($request);
            case 4:
                return $this->handleRoleOne($request);
            case 5:
                return $this->handleRoleOne($request);
            case 6:
                return $this->handleRoleSix($request);
            case 7:
                return $this->handleRoleSeven($request);
            case 8:
                return $this->handleRoleEight($request);
            case 9:
                return $this->handleRoleOne($request);
            default:
                return abort(403, 'Unauthorized action.');
        }
    }

    private function handleRoleOne(Request $request)
    {
        $ordersQuery = Order::with('user', 'payment', 'feedback', 'team')->where('uid', '!=', 0);
        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
            'projectStatusCounts' => ProjectStatusCount::all()
        ];
        $totalOrders = $ordersQuery->count();
        $totalWordCount = 0;

        foreach ($ordersQuery->get() as $order) {
            if (is_numeric($order->pages)) {
                $totalWordCount += $order->pages;
            }
        }
        if ($request->input('search') || $request->input('status') || $request->input('writer') || $request->input('writerTL') || $request->input('uid') || $request->input('date_status') || $request->input('from_date') || $request->input('to_date') || $request->input('SubWriter') || $request->input('college') || $request->input('extra') || $request->input('secondary_mobile') || $request->input('paper_type')) {
            $data['orders'] = $ordersQuery->orderByDesc('id')->get();
        } else {
            $perPage = 10;
            $page = $request->input('page') ?? 1;
            $ordersQuery->offset(($page - 1) * $perPage)->limit($perPage);


            $totalPages = ceil($totalOrders / $perPage);

            $data['orders'] = $ordersQuery->orderByDesc('id')->get();
            $data['totalOrders'] = $totalOrders;
            $data['totalWordCount'] = $totalWordCount;
            $data['totalPages'] = $totalPages;
        }

        if (auth()->user()->role_id == 1) {
            return view('order.my_orders', compact('data'));
        } elseif (auth()->user()->role_id == 4) {
            return view('order.marketingteam_order', compact('data'));
        } elseif (auth()->user()->role_id == 5) {
            return view('order.projectteam_order', compact('data'));
        } elseif (auth()->user()->role_id == 9) {
            return view('order.subadmin_order', compact('data'));
        }
    }

    private function handleRoleTwo()
    {
        $id  = auth()->user()->id;
        $data['orders'] = Order::with('payment')->where('uid', $id)->paginate(10);
        $data['leads'] = Leads::where('emp_id', $id)->where('status', 0)->get();

        return view('order.user_order', compact('data'));
    }

    public function handleRoleSix(Request $request)
    {
        $id = auth()->user()->id;
        $status = $request->input('status');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $WriterTL = $request->input('WriterTL');
        $SubWriter = $request->input('SubWriter');
        $extra = $request->input('extra');
        $startDateud = $request->input('startDate');
        $endDateud = $request->input('endDate');
        $startDatefd = $request->input('startDate1');
        $endDatefd = $request->input('endDate1');
        $daterange1 = $request->input('daterange');
        $daterange2 = $request->input('daterange1');


        $orders = Order::query();

        // Applying filters
        if ($request->input('search')) {
            $searchTerm = $request->input('search');
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', $searchTerm);
            });
        }

        if ($daterange1 != '') {
            $dateRange = explode(' to ', $daterange1);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_fd', [$startDate, $endDate]);
        }

        if ($daterange2 != '') {


            $dateRange = explode(' to ', $daterange2);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_ud', [$startDate, $endDate]);
        }


        if ($status != '') {
            if ($status == 'Not Assign') {
                $orders->where('writer_status',  '');
            } else {

                $orders->where('writer_status',  $status);
            }
        }

        if ($fromDate != '') {

            if ($fromDate != '' && $toDate != '') {
                $orders->whereBetween('writer_deadline', [$fromDate, $toDate]);
            } else {
                $orders->where('writer_deadline', $fromDate);
            }
        }

        if ($WriterTL != '') {
            $orders->where('wid', $WriterTL);
        }

        if ($SubWriter != '') {
            $orders->where('swid', $SubWriter);
        }

        if ($extra != '') {
            if ($extra == 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra == 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra == 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra == '1') {
                $orders->where('services', 'First Class Work');
            }
        }


        // Fetching data for dropdowns
        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];

        // Fetching orders
        $data['orders'] = $orders->where('wid', $id)->orderBy('order_id', 'desc')->paginate(10);

        return view('order.writer-tl_order', compact('data'));
    }



    public function handleRoleSeven(Request $request)
    {
        $id = auth()->user()->id;
        $status = $request->input('status');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $WriterTL = $request->input('WriterTL');
        $SubWriter = $request->input('SubWriter');
        $extra = $request->input('extra');
        $startDateud = $request->input('startDate');
        $endDateud = $request->input('endDate');
        $startDatefd = $request->input('startDate1');
        $endDatefd = $request->input('endDate1');
        $daterange1 = $request->input('daterange');
        $daterange2 = $request->input('daterange1');

        $orders = Order::query();

        // Applying filters
        if ($request->input('search')) {
            $searchTerm = $request->input('search');
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', $searchTerm);
            });
        }

        if ($daterange1 != '') {
            $dateRange = explode(' to ', $daterange1);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_fd', [$startDate, $endDate]);
        }

        if ($daterange2 != '') {


            $dateRange = explode(' to ', $daterange2);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_ud', [$startDate, $endDate]);
        }



        if ($status != '') {
            if ($status == 'Not Assign') {
                $orders->where('writer_status',  '');
            } else {

                $orders->where('writer_status',  $status);
            }
        }



        if ($fromDate != '') {

            if ($fromDate != '' && $toDate != '') {
                $orders->whereBetween('writer_deadline', [$fromDate, $toDate]);
            } else {
                $orders->where('writer_deadline', $fromDate);
            }
        }
        if ($WriterTL != '') {
            $orders->where('wid', $WriterTL);
        }

        if ($SubWriter != '') {
            $orders->where('swid', $SubWriter);
        }



        if ($extra != '') {
            if ($extra == 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra == 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra == 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra == '1') {
                $orders->where('services', 'First Class Work');
            }
        }


        // Fetching data for dropdowns
        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];

        // Fetching orders
        if ($request->input('status') || $request->input('date_status') || $fromDate = $request->input('from_date') || $request->input('to_date') || $request->input('WriterTL') || $request->input('SubWriter') || $request->input('extra') || $request->input('startDate') || $request->input('endDate') || $request->input('startDate1') || $request->input('endDate1')) {

            $data['orders'] = $orders->where('swid', $id)->orderBy('order_id', 'desc')->get();
        } else {
            $data['orders'] = $orders->with('mulsubwriter')
                ->where(function ($query) {
                    $userId = auth()->user()->id;
                    $query->where('swid', $userId)
                        ->orWhereHas('mulsubwriter', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        });
                })
                ->orderBy('order_id', 'desc')
                ->paginate(10);
        }
        return view('order.sub-writer_order', compact('data'));
    }

    public function handleRoleEight(Request $request)
    {
        $id = auth()->user()->id;


        $orders = Order::query();

        // Applying filters



        // Fetching data for dropdowns
        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->where('admin_id', auth()->user()->id)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];

        // Fetching orders
        if ($request->input('status') || $request->input('date_status') || $fromDate = $request->input('from_date') || $request->input('to_date') || $request->input('WriterTL') || $request->input('SubWriter') || $request->input('extra') || $request->input('startDate') || $request->input('endDate') || $request->input('startDate1') || $request->input('endDate1')) {
            $data['orders'] = $orders->where('admin_id', $id)->orderBy('order_id', 'desc')->get();
        } else {
            $data['orders'] = $orders->with('writer', 'subwriter', 'mulsubwriter')->where('admin_id', $id)->orderBy('writer_deadline', 'desc')->paginate(10);
            // echo '<pre>'; print_r($data['orders']); exit;

        }
        return view('order.writer-admin_order', compact('data'));
    }




    public function fail($id)
    {
        dd($id);
        try {
            $order = Order::find($id);
            $order->is_fail = 1;
            $order->failed_by = auth()->user()->name;
            $order->failed_at = now();
            $order->save();

            $uid = $order->uid;
            $user = User::find($uid);
            $user->is_fail = 1;
            $user->save();


            return response()->json(['message' => 'Order updated successfully', 'log' => $log]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::find($id);

            if ($order->is_fail == 1) {
                // If the order is failed, cancel the failed status
                $order->is_fail = 0;
                $action = 'cancelled';
            } else {
                // If the order is not failed, mark it as failed
                $order->is_fail = 1;
                $order->failed_by = auth()->user()->name;
                $order->failed_at = now();

                $uid = $order->uid;
                $user = User::find($uid);
                $user->is_fail = 1;
                $user->save();
                $action = 'failed';
            }

            $order->save();

            logActivity('Order', [
                'type' => 'Order Failed',
                'order_id' => $order->order_id,
                'message' => 'Order Failed by ' . auth()->user()->name,
            ]);

            return response()->json(['message' => 'Order ' . $action . ' successfully']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }


    public function payment(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01', // Minimum amount should be 0.01
            // 'payee_name' => 'required|string',
            // 'company_accounts' => 'required|string',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Find the order
        $order = Order::find($id);

        if (!$order) {
            return Redirect::back()->with('error', 'Order not found.');
        }

        // Calculate the remaining amount to be paid
        $remainingAmount = $order->amount - $order->received_amount;

        // Check if the paid amount is valid
        $paidAmount = $request->input('amount');
        if ($paidAmount > $remainingAmount) {
            return Redirect::back()->with('error', 'Paid amount exceeds the remaining due amount.');
        }

        // Save payment details
        $payment = new Payment;
        $payment->order_id = $id;
        $payment->payment_date = now()->format('l d F Y h:i A');
        $payment->paid_amount = $paidAmount;
        $payment->reference = $request->input('message');
        $payment->payee_name = $request->input('payee_name');
        $payment->payment_update_by = auth()->user()->name;
        $payment->account_status = 1; // Assuming this is the default value
        $payment->company_accounts = $request->input('company_accounts');
        $payment->save();

        // Update the received amount in the order
        $order->received_amount += $paidAmount;
        $order->save();

        logActivity('Order', [
            'type' => 'Payment Added',
            'order_id' => $order->order_id,
            'paid_amount' => $paidAmount,
            'action_by' => auth()->user()->name,
        ]);

        return Redirect::back()->with('success', 'Payment details updated successfully.');
    }


    public function OrderEdit(Request $req, $id)
    {
        // Find the order by ID
        $order = Order::find($id);
        $search =   $order->order_id;

        // Check if the user has the necessary role to edit this order


        // Check user role and update fields accordingly
        if (auth()->user()->role_id == 1) {
            // Fields specific to the admin role
            $order->title = $req->input('title');
            $order->order_date = $req->input('order_date');
            $order->writer_deadline = $req->input('writer_deadline');
            if ($order->order_date <= $req->input('delivery_date')) {
                // Delivery Date must be on or after the Order Date               
                $order->delivery_date = $req->input('delivery_date');
            }
            $order->amount = $req->input('amount');
            $order->received_amount = $req->input('r_amount');
            $order->delivery_time = $req->input('delivery_time');
            // Check if the input is a numeric value
            if ($req->filled('word') && !is_numeric($req->input('word'))) {
                // Redirect back with a warning message if not numeric
                return redirect()->back()->with('warning', 'Word must be a numeric value');
            }
            $order->pages = $req->input('word');
            $order->writer_deadline_time = $req->input('writer_deadline_time');

            if ($req->input('status') == 'Completed') {
                $order->projectstatus = $req->input('status');
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;

                $orderData = [
                    'name' => $req->input('user_name'),
                    'email' => $req->input('email'),
                    'title' => $req->input('title'),
                    'order_code' => $order->order_id,
                    'date'     => $order->delivery_date,
                    'due'     => $req->input('amount') - $req->input('r_amount'),
                ];
                try {
                    Mail::to($orderData['email'])->cc('order@assignnmentinneed.com')->send(new OrderComplete($orderData));
                } catch (\Throwable $e) {
                    // Log the error but do not stop execution
                    Log::error('Mail sending failed | Error: ' . $e->getMessage());
                }
            } elseif ($req->input('status') == 'Delivered') {

                if ((int)$order->amount - (int)$order->received_amount !== 0) {
                    return redirect()->back()->with('warning', 'Order cannot be marked as Delivered if there is any due payment remaining.');
                }
                $order->projectstatus = $req->input('status');
                $order->delivery_date = Carbon::now('Asia/Kolkata')->toDateString();
            } else {
                $order->projectstatus = $req->input('status');
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;
            }

            if ($req->input('writer_name') == 'team 13') {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '8392';
            } elseif ($req->input('writer_name') == 'team 02') {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '10123';
            } else {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '0';
            }


            $order->formatting = $req->input('formatting');
            $order->services = $req->input('services');
            $order->typeofwritting = $req->input('writting_type');
            $order->typeofpaper = $req->input('paper');
            if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
                $order->chapter = $req->input('chapter');
            } else {
                $order->chapter = null;
            }
            $order->college_name = $req->input('college_name');
            $order->draftrequired = $req->input('daraft_status');
            $order->draft_date = $req->input('draft_date');
            $order->draft_time = $req->input('draft_time');
            $order->message = $req->input('messages');
            $order->college_name = $req->input('college_name');
            $order->resit = $req->input('resit');
            $order->tech = $req->input('tech');
            $order->module_code = $req->input('module_code');
            $order->semester = $req->input('semester');




            if ($req->input('uid') != '') {

                $order->uid = $req->input('uid');
            } else {

                $user = User::find($order->uid);
                if ($req->filled('user_name')) {
                    $user->name = $req->input('user_name');
                }
                if ($req->filled('mobile')) {
                    $user->mobile_no = $req->input('mobile');
                }
                if ($req->filled('country_code')) {
                    $user->countrycode = $req->input('country_code');
                }
                if ($req->filled('mobile2')) {
                    $user->mobile_no2 = $req->input('mobile2');
                }
                if ($req->filled('country_code2')) {
                    $user->countrycode2 = $req->input('country_code2');
                }
                if ($req->filled('email')) {
                    $user->email = $req->input('email');
                }
                $user->save();
            }
        }

        if (auth()->user()->role_id == 4 || auth()->user()->role_id == 9) {

            // Fields specific to the MarketingTeam role
            $order->title = $req->input('title');
            // $order->order_date = $req->input('order_date');


            // $order->delivery_date = $req->input('delivery_date');
            if ($order->order_date <= $req->input('delivery_date')) {
                // Delivery Date must be on or after the Order Date               
                $order->delivery_date = $req->input('delivery_date');
            }
            $order->amount = $req->input('amount');
            $order->received_amount = $req->input('r_amount');
            $order->delivery_time = $req->input('delivery_time');
            // Check if the input is a numeric value
            if ($req->filled('word') && !is_numeric($req->input('word'))) {
                // Redirect back with a warning message if not numeric
                return redirect()->back()->with('warning', 'Word must be a numeric value');
            }
            $order->pages = $req->input('word');
            if ($req->input('status') == 'Completed') {
                $order->projectstatus = $req->input('status');
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;

                $orderData = [
                    'name' => $req->input('user_name'),
                    'email' => $req->input('email'),
                    'title' => $req->input('title'),
                    'order_code' => $order->order_id,
                    'date'     => $order->delivery_date,
                    'due'     => $req->input('amount') - $req->input('r_amount'),
                ];
                try {
                    Mail::to($orderData['email'])->cc('order@assignnmentinneed.com')->send(new OrderComplete($orderData));
                } catch (\Throwable $e) {
                    // Log the error but do not stop execution
                    Log::error('Mail sending failed | Error: ' . $e->getMessage());
                }
            } elseif ($req->input('status') == 'Delivered') {
                if ((int)$order->amount - (int)$order->received_amount !== 0) {
                    return redirect()->back()->with('warning', 'Order cannot be marked as Delivered if there is any due payment remaining.');
                }
                $order->projectstatus = $req->input('status');
            } else {
                $order->projectstatus = $req->input('status');
                $order->status_date = Carbon::now('Asia/Kolkata');
                $order->status_by   = auth()->user()->name;
            }
            if ($req->input('writer_name') == 'team 13') {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '8392';
            } elseif ($req->input('writer_name') == 'team 02') {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '10123';
            } else {
                $order->writer_name = $req->input('writer_name');
                $order->admin_id =  '0';
            }


            $order->college_name = $req->input('college_name');

            $order->services = $req->input('services');

            // $order->chapter = $req->input('chapter');

            $order->draftrequired = $req->input('daraft_status');
            $order->draft_date = $req->input('draft_date');
            $order->draft_time = $req->input('draft_time');
            $order->message = $req->input('messages');
            $order->semester = $req->input('semester');
            $order->resit = $req->input('resit');
            $order->tech = $req->input('tech');
            $order->module_code = $req->input('module_code');
            $order->writer_deadline = $req->input('writer_deadline');
            $order->typeofpaper = $req->input('paper');
            if ($order->typeofpaper === 'Dissertation' || $order->typeofpaper === 'Thesis') {
                $order->chapter = $req->input('chapter');
            } else {
                $order->chapter = null;
            }
            $user = User::find($order->uid);
            if ($req->filled('user_name')) {
                $user->name = $req->input('user_name');
            }
            if ($req->filled('mobile')) {
                $user->mobile_no = $req->input('mobile');
            }
            if ($req->filled('country_code')) {
                $user->countrycode = $req->input('country_code');
            }
            if ($req->filled('mobile2')) {
                $user->mobile_no2 = $req->input('mobile2');
            }
            if ($req->filled('country_code2')) {
                $user->countrycode2 = $req->input('country_code2');
            }
            if ($req->filled('email')) {
                $user->email = $req->input('email');
            }

            // Save user changes
            $user->save();
        }



        // Save order changes
        $order->save();

        if (Str::lower($req->input('status')) === 'initiated') {
            event(new \App\Events\OrderStatusChanged($order));
        }

        // Update or create a record in the ProjectStatusCount table
        $statusCount = ProjectStatusCount::where('order_Id', $order->id)
            ->where('status', $req->input('status'))
            ->first();
        $lastStatusCount = ProjectStatusCount::where('order_Id', $order->id)
            ->orderBy('updated_at', 'desc')
            ->first();
        // dd($lastStatusCount->status, $req->input('status'), $statusCount);
        if ($statusCount && $lastStatusCount->status != $req->input('status')) {
            $statusCount->increment('count');
            $statusCount->save(); // Save the updated record
        } elseif (!$statusCount) {
            $statusCount = ProjectStatusCount::create([
                'order_Id' => $order->id,
                'status' => $req->input('status'),
                'count' => 1
            ]);
        }

        logActivity('Order', [
            'module' => 'Order Management',
            'type' => 'Order Updated',
            'order_id' => $order->order_id,
            'status' => $order->projectstatus,
            'updated_by' => auth()->user()->name,
        ]);
        // Now $statusCount contains the saved record

        // Update user details only if the corresponding input fields have a value


        return redirect('/orders')->with(['Success' => "Order Updated", 'search' => $search]);
    }

    public function search(Request $request)
    {
        // Get all filters from the request
        $filters = $request->all();

        // Check if all filters are empty, return a message if so
        if (empty($filters['search']) && empty($filters['uid']) && empty($filters['status']) && empty($filters['writer']) && empty($filters['date_status']) && empty($filters['from_date']) && empty($filters['to_date']) && empty($filters['from_date']) && empty($filters['to_date']) && empty($filters['WriterTL']) && empty($filters['SubWriter']) && empty($filters['college']) && empty($filters['extra']) && empty($filters['secondary_mobile']) && empty($filters['selectedDataTextBox']) && empty($filters['paper_type']) && empty($filters['semester'])) {
            return response()->json(['message' => 'No filters applied'], 200);
        }

        $searchTerm = $request->input('search');
        $uid = $request->input('uid');
        $status = $request->input('status');
        $writer = $request->input('writer');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $WriterTL = $request->input('WriterTL');
        $SubWriter = $request->input('SubWriter');
        $college = $request->input('college');
        $extra = $request->input('extra');
        $secondaryMobile = $request->input('secondary_mobile'); // Add this line to get the secondary mobile number
        $selectedDataTextBox = $request->input('selectedDataTextBox'); // Add this line to get the secondary mobile number
        $paper_type = $request->input('paper_type');
        $semester = $request->input('semester');

        $data = [
            'projectStatusCounts' => ProjectStatusCount::all()
        ];

        $orders = Order::query();

        if ($semester != '') {
            $orders->where('semester',  $semester);
        }

        if ($searchTerm != '') {
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($selectedDataTextBox) {
            $orders->where('order_id', $selectedDataTextBox);
        }


        if ($uid != '') {


            $orders->where('uid', $uid);
        }


        // Add the condition to filter by secondary mobile number
        if ($secondaryMobile != '') {
            $orders->where('module_code', 'like', '%' . $secondaryMobile . '%');
        }

        // ... (remaining code)
        if ($status != '') {
            $orders->where('projectstatus',  $status);
        }

        if ($writer != '') {
            if ($writer == 'team 13') {
                $orders->where('admin_id',  8392);
            } elseif ($writer == 'Not Assign') {
                $orders->where(function ($query) {
                    $query->whereNull('writer_name')
                        ->orWhere('writer_name', '');
                });
            } else {

                $orders->where('writer_name', 'Like', $writer);
            }
        }

        if ($dateStatus != '' || $fromDate != '' || $toDate != '') {
            if ($fromDate != '' && $toDate != '' && $dateStatus != '') {

                if ($dateStatus == 'draft_date') {
                    $orders->whereBetween($dateStatus, [$fromDate, $toDate])->where('draftrequired', 'y');
                } else {
                    if ($dateStatus == 'failed_at') {
                        $orders = Order::where('is_fail', 1)
                            ->whereBetween(DB::raw("STR_TO_DATE($dateStatus, '%Y-%m-%d')"), [$fromDate, $toDate]);
                    } else {
                        $orders->whereBetween($dateStatus, [$fromDate, $toDate]);
                    }
                }
            } elseif ($fromDate != '' && $toDate != '') {
                $orders->whereBetween('order_date', [$fromDate, $toDate]);
            } elseif ($fromDate != '') {
                $orders->where('order_date', $fromDate);
            } elseif ($dateStatus != '') {
                $orders->where('order_date', Carbon::today());
            }
        }

        if ($WriterTL != '') {
            $orders->where('wid', $WriterTL);
        }

        if ($SubWriter != '') {
            // $orders->where('swid', $SubWriter );
            $multipleWriters = multipleswiter::where('user_id', $SubWriter)->get();

            $orderIds = $multipleWriters->pluck('order_id')->toArray();

            $orders->whereIn('id', $orderIds);
        }

        if ($college != '') {
            $orders->where('college_name', $college);
        }

        if ($extra != '') {
            if ($extra == 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra == 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra == 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra == '1') {
                $orders->where('services', 'First Class Work');
            }
        }

        if ($paper_type != '') {
            $orders->where('typeofpaper',  $paper_type);
        }

        // ======= Temporary Solution Start: Prevent timeout by chunking large query =======
        // $orders = $orders->orderBy('id', 'desc')->where('uid', '!=' ,'0')->get();
        set_time_limit(300); // Optional: Increase time limit to 5 minutes

        $ordersQuery = $orders->with('team')->orderBy('id', 'desc')->where('uid', '!=', '0');

        $orders = []; // If you need to collect final orders

        $ordersQuery->chunk(100, function ($chunk) use (&$orders) {
            foreach ($chunk as $order) {
                // You can directly process the order here if no collection is needed
                $orders[] = $order;
            }
        });
        $orders = collect($orders);

        // ======= Temporary Solution End =======

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No data found']);
        }

        $totalOrderCount = $orders->count();


        $totalWordCount = $orders->reduce(function ($carry, $order) {
            return $carry + (is_numeric($order->pages) ? $order->pages : 0);
        }, 0);


        $output = '';
        $index = 1;

        foreach ($orders as $order) {
            // Your existing code for creating the output string goes here
            $output .= '<tr';

            // Check if the order has a user and the user is_fail property is set to 1
            if ($order->user && $order->user->is_fail == 1) {
                $output .= ' style="color:blue"';
            }

            $output .= '>';
            $output .= '<td>' .  $index++ . '</td>
                            <td>
                                ' . $order->order_id . '
                                ' . ($order->feedback_ticket != '' ? '<span class="badge badge-light-danger fs-7 fw-bold ">' . $order->feedback_ticket . '</span>' : '') . '
                                ' . ($order->team?->team_name != '' ? '<span class="badge badge-light-primary fs-7 fw-bold ">' . $order->team->team_name . '</span>' : '') . '

                                ' . ($order->is_fail == 1 ? '<span class="badge badge-light-danger fs-7 fw-bold">Fail Order <br> ' . (auth()->user()->role_id ==  '1' ? \Carbon\Carbon::parse($order->failed_at)->format('d M Y H:i:s A') : '') . '</span>' : '') . '
                                ' . ($order->services == 'First Class Work' ? '<span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>' : '') . '
                                ' . ($order->resit == 'on' ? '<span class="badge badge-light-danger fs-7 fw-bold">Resit</span>' : '') . '
                           
                                </td>  
                            
                                ' . ($order->user !=  ''  && auth()->user()->role_id !=  '5' ?

                '<td>
                                ' . $order->user->name . '
                                <span class="badge badge-light-danger fs-7 fw-bold">' . $order->user->mobile_no . '</span>                                
                                </td> '

                : '') . '

                                ' . ($order->user ==  ''  &&  auth()->user()->role_id !=  '5'  ?

                '<td>
                                Deleted User
                                </td> '

                : '') . '
                                
                            <td>' . \Carbon\Carbon::parse($order->order_date)->format('d M Y') . '</td>
                            
                            <td  onclick="updateDeliveryDate(' . $order->id . ')">
                            ' . ($order->delivery_date != null ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : 'Not Available') . '
                            ' . ($order->draftrequired == 'Y' ? '<span class="badge badge-light-success fs-7 fw-bold">' . \Carbon\Carbon::parse($order->draft_date)->format('d M Y') . ' (' . (\Carbon\Carbon::parse($order->draft_time)->format('H:i')) . ')</span>' : '') . '
                            </td>
                            <td>' . $order->title . '
                                  ' . (auth()->user()->role_id ==  '1' ||  auth()->user()->role_id ==  '4' ||  auth()->user()->role_id ==  '9' ?
                '
                                 <br>  ' . ($order->semester != '' ? '' . $order->semester . '' : '') . '
                            '
                : '') . '
                            ' . ($order->chapter != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->chapter . '</span>' : '') . '
                            ' . ($order->tech == '1' ? '<span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>' : '') . '
                            ' . ($order->module_code != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->module_code . '</span>' : '') . '
                            </td>
                            <td onclick="status(' . $order->id . ')">
                            ' . ($order->projectstatus ==  'Pending' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:pink; color:white">' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Other' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Advance Assignment' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'In Progress' ? '<span class="badge badge-light-info fs-7 fw-bold" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Hold Work' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Completed' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:#eaea00; color:black" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Delivered' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:green; color:white" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Feedback' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Feedback Delivered' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Cancelled' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Draft Ready' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:#eaea00; color:black" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Draft Delivered' ? '<span class="badge badge-light-danger fs-7 fw-bold"  style="background:green; color:white" >' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Initiated' ? '<span class="badge badge-light-danger fs-7 fw-bold"  style="background:pink; color:white">' . $order->projectstatus . '</span>' : '') . '
                            ' . ($order->projectstatus ==  'Hold(writer query)' ? '<span class="badge badge-light-danger fs-7 fw-bold"  >' . $order->projectstatus . '</span>' : '') . '

                            
                        ';
            if (auth()->user()->role_id == '1') {
                $statusCounts = $data["projectStatusCounts"]->where("order_Id", $order->id)
                    ->where("status", $order->projectstatus);
                if ($statusCounts->isNotEmpty()) {
                    foreach ($statusCounts as $statusCount) {
                        $output .= '<span class="badge badge-sm badge-circle badge-light-success">' . $statusCount->count . '</span>';
                    }
                }
            }
            $output .= '</td>';

            $output .= '

                        ' . (auth()->user()->role_id ==  '1' ||  auth()->user()->role_id ==  '4' ||  auth()->user()->role_id ==  '9' ?
                '<td>
                                ' . ($order->status_issue ==  'Issue Raised' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  'Client Discussion Done' ? '<span class="badge badge-light-info fs-7 fw-bold">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  'Writer discussion Done' ? '<span class="badge badge-light-success fs-7 fw-bold">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  'Work in progress' ? '<span class="badge badge-light-warning fs-7 fw-bold">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  'Case Resolved' ? '<span class="badge badge-light-success fs-7 fw-bold">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  'Issues Raised Again' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">' . $order->status_issue . '</span>' : '') . '
                                ' . ($order->status_issue ==  '' ? '<span class="badge badge-light-warning fs-7 fw-bold"> </span>' : '') . '
                            </td>'
                : '') . '
                        

                       ' . (auth()->user()->role_id !=  '5' ?


                ' 
                            <td>' . $order->pages . '</td>
                            <td>' . $order->amount . '</td> 
                            <td>' . $order->received_amount . '</td>
                            <td> ' . (is_numeric($order->amount) !=  '' ? '<span >' . $order->amount - $order->received_amount . '</span>' : '') . '</td>
                       '

                : '') . '
                            
                            ' .

                '<td>       
                            ' . ($order->writer_name ==  ''  || $order->writer_name ==  NULL ? '<span class="badge badge-light-danger fs-7 fw-bold" >N/A</span>' : '') . '

                            
                            ' . $order->writer_name . ' 
                            ' . ($order->writer_deadline !=  '' ? '<span class="badge badge-light-info fs-7 fw-bold" ">' . \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') . '</span>' : '') . '
                            
                            </td> '

                . '

                          </td>';

            if (auth()->user()->role_id == '1') {
                $output .= '<td>';
                if ($order->l_converted_by != null) {
                    $output .= 'Convert By (' . $order->l_converted_by . ')';
                } else {
                    $output .= 'Convert By (N/A)';
                }
                if ($order->failed_by != null) {
                    $output .= '<br>Failed By: ' . $order->failed_by . ' at ' . $order->failed_at;
                } else {
                    $output .= '<br>Failed By: (N/A)';
                }
                $output .= '</td>';
            }


            $showQuestionMark = false;

            if (!empty($order->payment) && count($order->payment) >= 1) {
                foreach ($order->payment as $payment) {
                    if (empty($payment['payee_name']) || empty($payment['company_accounts'])) {
                        $showQuestionMark = true;
                        break;
                    }
                }
            }

            $output .= '<td class="text-end">
    <a target="_blank" href="edit.' . $order->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
        <span class="svg-icon svg-icon-3">
            <i class="fa fa-eye"></i>
        </span>
    </a>';

            if (auth()->user()->role_id != '5') {
                $output .= '
    <a href="orderpayments.' . $order->id . '" target="_blank" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 position-relative" style="display: inline-flex; align-items: center; justify-content: center;">
        <span class="svg-icon svg-icon-3" style="position: relative; display: inline-block;">
            <li class="fa fa-money"></li>';

                if ($showQuestionMark) {
                    $output .= '
            <span style="position: absolute; top: -18px; right: -15px; background-color: red; color: white; font-size: 10px; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: flash 1s infinite;">
                ?
            </span>';
                }

                $output .= '
        </span>
    </a>

    <a href="#" id="clickToDownload' . $order->order_id . '" class="btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn' . $order->id . '" onclick="downloadFiles(this)">
        <span class="svg-icon svg-icon-3">
            <i class="fa fa-download fa-lg"></i>
        </span>
    </a>

    <a href="#" onclick="showConfirmation(' . $order->id . ',' . $order->is_fail . ')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
        <span class="svg-icon svg-icon-3">
            <i>F</i>
        </span>
    </a>

    <a href="/comment.' . $order->order_id . '" target="_blank" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
        <span class="svg-icon svg-icon-3">
            <i>T</i>
        </span>
    </a>

    <a href="#" onclick="showConfirmationclick(' . $order->id . ')" id="clickToCallBtn' . $order->id . '" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
        <span class="svg-icon svg-icon-3">
            <i class="fa fa-phone fa-lg"></i>
        </span>
    </a>';
            }

            if (auth()->user()->role_id == '9') {
                $output .= '
    <a href="#" onclick="CallToWriter(' . $order->id . ')" id="clickToCallBtn' . $order->id . '" class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">
        <span class="svg-icon svg-icon-3">
            <i class="fa fa-phone fa-lg"></i>
        </span>
    </a>';
            }

            $output .= '</td></tr>';
        }

        return response()->json([
            'output' => $output,
            'totalWordCount' => $totalWordCount,
            'totalOrderCount' => $totalOrderCount,
        ]);
    }

    public function fetchSubWriters(Request $request)
    {
        $tlId = $request->input('tlId');

        // Fetch SubWriters based on TL ID
        $subWriters = User::where('tl_id', $tlId)->get();

        // Return the SubWriters as JSON response
        return response()->json($subWriters);
    }

    public function orderEditPage($id)
    {
        $order = Order::with('user')->find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }
        if (auth()->user()->role_id == 7) {
            $id = auth()->user()->id;
            $data['orders'] = Order::with('user', 'payment', 'feedback')
                ->where('swid', $id)
                ->orderBy('order_id', 'desc')
                ->paginate(10);

            // echo '<pre>'; print_r($data['leds']) ; exit;
        }

        $data['Team']         = Writer::all();
        $data['Status']       = Status::all();
        $data['formatting']   = Formatting::all();
        $data['service']      = Services::all();
        $data['Writting']     = Writting::all();
        $data['paper']        = Paper::all();
        $data['college']      = College::all();
        $data['admin']        = User::where('role_id', '=', '8')->where('flag', '=', 0)->get();
        $data['writerTL']     = User::where('role_id', '=', '6')->where('flag', '=', 0)->get();
        $data['SubWriter']    = User::where('role_id', '=', '7')->where('flag', '=', 0)->get();
        $data['ordersub']     = multipleswiter::where('order_id', $id)->get();


        // echo '<pre>'; print_r($data['ordersub']); exit;
        $userDetails = $order->user;
        if (auth()->user()->role_id == 8) {
            // echo '<pre>'; print_r( $data['ordersub']); exit;
            return view('order.edit.admin_edit', compact('order', 'data'));
        } elseif (auth()->user()->role_id == 4 || auth()->user()->role_id == 9) {
            return view('order.Order View & Edit.order_edit_marketing-team', compact('order', 'userDetails', 'data'));
        } elseif (auth()->user()->role_id == 5) {
            return view('order.Order View & Edit.order_edit_project-team', compact('order', 'userDetails', 'data'));
        } elseif (auth()->user()->role_id == 7) {
            return view('order.Order View & Edit.order_edit_sub-writer', compact('order', 'userDetails', 'data'));
        } elseif (auth()->user()->role_id == 6) {
            return view('order.Order View & Edit.order_edit_writerTl', compact('order', 'userDetails', 'data'));
        } else {
            return view('order.order_edit', compact('order', 'userDetails', 'data'));
        }
    }
    public function orderCallPage($id)
    {
        $order = Order::with('user', 'ordercall.user')->find($id);

        // echo '<pre>'; print_r($order);exit;

        if (!$order) {
            abort(404, 'Order not found');
        }
        $data['orders'] = Order::with('user', 'payment')
            ->where('uid', '!=', 0) // Fix: Use '!=' for "not equal to"
            ->orderByDesc('id')
            ->paginate(10);

        $data['Team'] = Writer::all();
        $data['Status'] = Status::all();
        $data['formatting'] = Formatting::all();
        $data['service'] = Services::all();
        $data['Writting'] = Writting::all();
        $data['paper'] = Paper::all();
        $data['college'] = College::all();
        $data['user'] = User::all();

        $userDetails = $order->user;

        return view('order.order_call', compact('order', 'userDetails', 'data'));
    }

    public function orderCommentPage($id)
    {
        $order = Order::with('feedback.user')->where('order_id', $id)->first();

        // Check if the order exists
        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('order.order_comment', compact('order'));
    }



    public function saveFeedback(Request $request)
    {
        // Validate the request if needed
        $request->validate([
            'orderId' => 'required',
            'inputVal' => 'required',
        ]);

        // Save data to the feedback table
        $feedback = new Feedback;
        $feedback->order_id = $request->orderId;
        $feedback->message = $request->inputVal;
        $feedback->save();

        // Update feedback status in the order table
        $order = Order::find($request->orderId); // Assuming you have an Order model
        $order->feedback_issue = 1;
        $order->save();

        // You can return a response if needed
        return response()->json(['message' => 'Feedback saved successfully']);
    }


    public function feedbacksheet(Request $req)
    {
        $data['writer'] = Writer::all();
        // $query = Order::with(['feedback' => function ($query) {
        //     $query->orderByDesc('id');
        // }, 'feedback.user'])->where('feedbackissue', '1')->orderByDesc('feedback_date');
        $query = Order::with(['feedback' => function ($query) {
            $query->orderByDesc('id');
        }, 'feedback.user'])->where('feedbackissue', '1')->orderByDesc(
            Feedback::select('created_at')
                ->whereColumn('order_id', 'orders.id')
                ->latest('id')
                ->limit(1)
        )
            ->orderByDesc('feedback_date');

        $edited_on = $req->input('edited_on');
        $fromDate = $req->input('fromDate');
        $toDate = $req->input('toDate');

        if ($fromDate != '' && $toDate != '') {
            if ($edited_on == 'Order-date') {
                $query->whereBetween('order_date', [$fromDate, $toDate])->orderBy('order_date', 'desc');
            } elseif ($edited_on == 'Feedback-date') {
                $query->whereBetween('feedback_date', [$fromDate, $toDate])->orderBy('feedback_date', 'desc');
            }
        } elseif ($fromDate != '') {
            if ($edited_on == 'Order-date') {
                $query->whereDate('order_date', $fromDate)->orderBy('order_date', 'desc');
            } elseif ($edited_on == 'Feedback-date') {
                $query->whereDate('feedback_date', $fromDate)->orderBy('feedback_date', 'desc');
            }
        }

        if ($req->filled('search')) {
            $order_id = $req->input('search');
            $query->where('order_id', $order_id);
        }
        if ($req->filled('ticket_no')) {
            $ticket_no = $req->input('ticket_no');
            $query->where('feedback_ticket', $ticket_no);
        }

        if ($req->filled('writer')) {
            $writer = $req->input('writer');
            $query->where('writer_name', $writer);
        }


        if ($req->filled('status')) {
            $status = $req->input('status');
            $query->where('status_issue', $status);
        }

        if ($req->filled('team_id')) {
            $team_id = $req->input('team_id');
            $query->where('team_id', $team_id);
        }

        // Get the pagination status based on search and filter conditions
        $perPage = ($req->filled('search') || $req->filled('status')) ? Order::count() : 20;
        $currentPage = Paginator::resolveCurrentPage('page');

        // Apply pagination
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $orders = $query->paginate($perPage);
        $alphaCount = Order::where('feedbackissue', '1')->where('team_id', 1)->count();
        $gigaCount  = Order::where('feedbackissue', '1')->where('team_id', 2)->count();

        return view('order.feedbacksheet', compact('orders', 'data', 'alphaCount', 'gigaCount'));
    }

    public function orderPayment($id)
    {
        $order = Order::with('user')->find($id);
        return view('order.order_payment', compact('order'));
    }

    public function orderPayment_delete(Request $req, $id)
    {
        $order = $req->input('order_id');
        $newOrder = Order::find($order);
        // Safely subtract only if enough amount is available
        $amountToSubtract = $req->input('amount');
        $currentReceived = $newOrder->received_amount;

        if ($amountToSubtract > $currentReceived) {
            return redirect()->back()->with('error', 'Cannot subtract more than received amount.');
        }
        $newOrder->received_amount = $newOrder->received_amount - $req->input('amount');
        $newOrder->SAVE();

        Payment::destroy($id);

        return redirect()->back()->with('Success', 'Action Submitted');
    }

    public function insert_feedback(Request $req, $id)
    {

        $order = Order::find($id);

        $uks = substr($order->order_id, 0, 3);
        if ($order->feedbackissue == 1 && $order->status_issue == 'Case Resolved') {
            $order->status_issue = 'Issues Raised Again';
            // dd($order->feedbackissue,$order->status_issue);
        }
        if ($order->feedbackissue != 1) {
            $order->status_issue = 'Issue Raised';
            $order->feedback_ticket = substr(uniqid(), 0, 7);
        }
        $order->feedbackissue = 1;
        $order->comment = $req->input('comment');
        $order->feedback_date = Carbon::now();
        $order->save();
        $feedback = new Feedback;
        $feedback->order_id = $id;
        $feedback->comment = $req->input('comment');
        $feedback->created_by = auth()->user()->id; // Assuming you are using authentication

        $feedback->save();

        $logResponse = logActivity('Order', [
            'type' => 'Feedback Added',
            'order_id' => $order->order_id,
            'message' => $req->input('comment'),
        ]);

        return response()->json(['status' => 'success', 'created_at' => $feedback->created_at->format('d M Y, h:i A'), 'message' => $feedback->comment, 'log' => $logResponse]);
    }

    public function sendFeedback(Request $request)
    {
        // 1. Validation
        $request->validate([
            'message' => 'required|string',
            'order_id' => 'required|string',
        ]);

        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // 2. Auto Ticket Logic (Based on Feedback entries count)
        $feedbackCount = Feedback::where('order_id', $request->order_id)->count();

        if ($feedbackCount >= 1 && empty($order->feedback_ticket)) {
            $order->feedback_ticket = $order->writer_name . '-' . substr($order->order_id, 3);
            $order->status_issue = 'Issue Raised';
            $order->feedbackissue = 1;
        }

        // 3. Status handling
        if ($order->feedbackissue == 1 && $order->status_issue == 'Case Resolved') {
            $order->status_issue = 'Issues Raised Again';
        }

        if ($request->filled('status')) {
            $order->status_issue = $request->status;
        }

        $order->save();

        // 4. Save feedback history
        $feedback = new Feedback;
        $feedback->action_comment = $request->input('message');
        $feedback->order_id = $request->input('order_id');
        $feedback->status = $order->status_issue;
        $feedback->created_by = auth()->user()->id;
        $feedback->save();

        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => $feedback->action_comment,
            'f_status' => $order->status_issue,
            'ticket' => $order->feedback_ticket,
            'created_at' => $feedback->created_at->diffForHumans(),
            'user' => [
                'name' => $user->name,
                'avatar' => asset($user->photo ?? 'assets/media/avatars/blank.png'),
            ],
        ]);
    }

    public function status_update(Request $req, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $order->status_issue = $req->input('status');
        $order->save();

        // Agar AJAX request hai toh JSON bhejein
        if ($req->ajax()) {
            return response()->json([
                'status' => 'success',
                'f_status' => $order->status_issue,
                'message' => 'Status updated successfully'
            ]);
        }

        return redirect()->back()->with('Success', 'Feedback status update successfully');
    }

    public function markAsRead(Request $request)
    {
        $orderId = $request->input('order_id');

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }

        // Assuming 'is_read' is a boolean field
        $order->is_read = 0; // Set it to 0 to mark it as read

        try {
            $order->save();
            return response()->json(['success' => true, 'message' => 'Order marked as read']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to mark as read']);
        }
    }
    public function OrderCallInsert(Request $request, $id)
    {
        $order = Order::find($id);
        $callBack = new Ordercall;
        $callBack->order_id = $order->id;
        $callBack->created_by = auth()->user()->id;
        $callBack->message = $request->input('callcontent');
        $callBack->save();

        return back()->with('success', 'Your call detail submitted successfully');
    }

    public function OrderEditwriterAdmin(Request $req, $id)
    {
        if (auth()->user()->role_id == 8) {

            // $subwriter = ;
            // dd();
            $order = Order::find($id);
            $order->writer_status = $req->input('status'); // Update the field name
            $order->wid = $req->input('tlSelect');



            // Update the field name
            $order->writer_fd = $req->input('fromdate'); // Update the field name
            $order->writer_ud = $req->input('uptodate'); // Update the field name
            $order->writer_fd_h = $req->input('writer_fd_half'); // Update the field name
            $order->writer_ud_h = $req->input('writer_ud_half'); // Update the field name
            $order->save();

            if ($req->input('subwriterSelect') != '') {
                $subwriterIds = $req->input('subwriterSelect');

                // Delete existing entries with the same order_id
                multipleswiter::where('order_id', $id)->delete();

                // Insert new entries
                foreach ($subwriterIds as $subwriterId) {
                    $writer = new multipleswiter;
                    $writer->order_id = $id;
                    $writer->user_id = $subwriterId;
                    $writer->save();
                }
            }
        } elseif (auth()->user()->role_id == 6) {
            $order = Order::find($id);
            $order->writer_status = $req->input('status'); // Update the field name
            // $order->swid = $req->input('subwriterSelect'); 

            if ($req->input('subwriterSelect') == 'SelfTlwriter') {
                $order->wid  = auth()->user()->id;
            }

            $order->save();

            foreach ($req->input('subwriterSelect') as $subwriterId) {
                $writer = new multipleswiter;
                $writer->order_id = $id;
                $writer->user_id = $subwriterId; // Assigning individual subwriter ID from the array
                $writer->save();
            }
        } elseif (auth()->user()->role_id == 7) {
            $order = Order::find($id);
            $order->writer_status = $req->input('status'); // Update the field name
            $order->save();
        }
        return redirect()->back()->with('success', 'Order Update Successfully');
    }

    public function search_writer(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $WriterTL = $request->input('WriterTL');
        $SubWriter = $request->input('SubWriter');
        $extra = $request->input('extra');
        $startDateud = $request->input('startDate');
        $endDateud = $request->input('endDate');
        $startDatefd = $request->input('startDate1');
        $endDatefd = $request->input('endDate1');
        $daterange1 = $request->input('daterange');
        $daterange2 = $request->input('daterange1');



        $orders = Order::query();

        if ($searchTerm != '') {
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', $searchTerm);
            });
        }



        if ($daterange1 != '') {
            $dateRange = explode(' to ', $daterange1);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_fd', [$startDate, $endDate]);
        }

        if ($daterange2 != '') {


            $dateRange = explode(' to ', $daterange2);
            $startDate1 = date('Y-m-d', strtotime($dateRange[0]));
            $endDate1 = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_ud', [$startDate1, $endDate1]);
        }


        if ($status != '') {
            if ($status == 'Not Assign') {
                $orders->where(function ($query) {
                    $query->whereNull('writer_status')
                        ->orWhere('writer_status', '');
                });
            } else {
                $orders->where('writer_status', $status);
            }
        }



        if ($dateStatus != '' || $fromDate != '' || $toDate != '') {
            if ($fromDate != '' && $toDate != '' && $dateStatus != '') {

                if ($dateStatus == 'draft_date') {
                    $orders->whereBetween($dateStatus, [$fromDate, $toDate])->where('draftrequired', 'y');
                } else {
                    $orders->whereBetween($dateStatus, [$fromDate, $toDate]);
                }
            } elseif ($fromDate != '' && $toDate != '') {
                $orders->whereBetween('writer_deadline', [$fromDate, $toDate]);
            } elseif ($fromDate != '') {
                $orders->where('writer_deadline', $fromDate);
            } elseif ($dateStatus != '') {
                $orders->where('writer_deadline', Carbon::today());
            }
        }
        if ($WriterTL != '') {
            if ($WriterTL == 'Not Assigned') {
                $orders->where(function ($query) {
                    $query->where('wid', '')
                        ->orWhereNull('wid');
                });
            } else {
                $orders->where('wid', $WriterTL);
            }
        }


        if ($SubWriter != '') {
            // if ($SubWriter == 'Not Assigned') {
            //     // Retrieve all records from multipleswiter where user_id is empty or null
            //     $multipleWriters = multipleswiter::whereNull('user_id')->orWhere('user_id', '')->get();

            //     // Extract order IDs from the retrieved multipleWriters
            //     $orderIds = $multipleWriters->pluck('order_id')->toArray();

            //     // Exclude orders with the extracted order IDs
            //     $orders->whereNotIn('id', $orderIds);
            // }

            $multipleWriters = multipleswiter::where('user_id', $SubWriter)->get();

            $orderIds = $multipleWriters->pluck('order_id')->toArray();

            $orders->whereIn('id', $orderIds);
        }




        if ($extra != '') {
            if ($extra == 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra == 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra == 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra == '1') {
                $orders->where('services', 'First Class Work');
            }
        }

        $id = auth()->user()->id;


        $orders = $orders->orderBy('writer_ud', 'desc')->where('admin_id', $id)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No data found']);
        }
        // Calculate total order count
        $totalOrderCount = $orders->count();

        // Calculate total word count
        // $totalWordCount = $orders->sum('pages');
        $totalWordCount = $orders->reduce(function ($carry, $order) {
            return $carry + (is_numeric($order->pages) ? $order->pages : 0);
        }, 0);
        // dd($totalOrderCount,$totalWordCount);
        $output = '';
        $index = 1;

        foreach ($orders as $order) {
            // Your existing code for creating the output string goes here
            $output .= '<tr>
                            <td>' .  $index++ . '</td>
                            <td>
                                ' . $order->order_id . '
                                ' . ($order->is_fail == 1 ? '<span class="badge badge-light-danger fs-7 fw-bold">Fail Order</span>' : '') . '
                                ' . ($order->services == 'First Class Work' ? '<span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>' : '') . '
                                ' . ($order->resit == 'on' ? '<span class="badge badge-light-danger fs-7 fw-bold">Resit</span>' : '') . '
                           
                                </td>  
                            
                               

                               
                                
                            <td>
                            ' . ($order->writer_fd != 0000 - 00 - 00 ?   \Carbon\Carbon::parse($order->writer_fd)->format('d M ')  : '') . '
                            <span class="badge badge-light-danger fs-7 fw-bold">' . $order->writer_fd_h . '</span>

                            </br>

                            ' . ($order->writer_ud != 0000 - 00 - 00 ?   \Carbon\Carbon::parse($order->writer_ud)->format('d M ')  : '') . '
                            <span class="badge badge-light-danger fs-7 fw-bold">' . $order->writer_ud_h . '</span>
                            
                            
                            </td>
                            
                            
                            <td>' . $order->title . '
                            ' . ($order->chapter != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->chapter . '</span>' : '') . '
                            ' . ($order->tech == '1' ? '<span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>' : '') . '
                            </td>
                            <td>
                            ' . ($order->writer_status ==  'In Progress' || $order->writer_status ==  'In progress' ? '<span class="badge badge-light-info fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Completed' ? '<span class="badge badge-light-warning fs-7 fw-bold">' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Delivered' ? '<span class="badge badge-light-info fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Hold' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Feedback' ? '<span class="badge badge-light-warning fs-7 fw-bold"  >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Feedback Delivered' ? '<span class="badge badge-success fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Draft Delivered' ? '<span class="badge badge-secondary fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Quality Accepted' ? '<span class="badge badge-light-info fs-7 fw-bold"  >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Quality Rejected' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Draft Ready' ? '<span class="badge badge-light-primary fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Draft Feedback' ? '<span class="badge badge-success fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Attached to Email (Draft)' ? '<span class="badge badge-light-warning fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Complete file Ready' ? '<span class="badge badge-secondary fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Attached to Email (Complete file)' ? '<span class="badge badge-light-primary fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  '' || $order->writer_status ==  'Not Assigned' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . "Not Assign" . '</span>' : '') . '
                            
                       </td>

                       <td>' .  $order->pages . '</td>';

            $output .= '<td>';

            $subWriterNames = [];

            foreach ($order->mulsubwriter as $writer) {
                if ($writer !== null && $writer->user !== null) {
                    if ($writer->user->name !== null) {
                        $subWriterNames[] = $writer->user->name;
                    }
                }
            }


            if (empty($subWriterNames) && $order->subwriter != null && $order->subwriter->name != null) {
                $output .= $order->subwriter->name; // Display if no sub-writer names are found and $order->subwriter->name is not null
            } else {
                foreach ($subWriterNames as $name) {
                    $output .= $name . '<br>'; // Display sub-writer names
                }
            }

            $output .= ($order->writer != '' ? '<span class="badge badge-light-info fs-7 fw-bold">(' . $order->writer->name . ')</span>' : '');




            $output .= '</td>';

            $output .= '<td>';
            // Formatting writer deadline
            $output .= ($order->writer_deadline != '0000-00-00') ? \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') : '';

            // If draft required, include date and time within a span
            if ($order->draftrequired == 'Y') {
                $output .= '<span class="badge badge-light-info fs-7 fw-bold">(';
                if ($order->draft_date && $order->draft_date != '0000-00-00') {
                    $output .= \Carbon\Carbon::parse($order->draft_date)->format('d M Y') . ' ';
                }
                if ($order->draft_time && $order->draft_time != '00:00:00') {
                    $output .= \Carbon\Carbon::parse($order->draft_time)->format('g:i A');
                }
                $output .= ')</span>';
            }
            $output .= '</td>
                       <td class="text-center">
                       <a href="/edit.' . $order->id . '"
                       target="_blank"
                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"">
                               <span class=" svg-icon svg-icon-3">
                           <li class="fa fa-edit"></li>
                           </span>
                       </a>
                    </td>
                        </tr>';
        }

        // return response()->json($output);
        return response()->json([
            'output' => $output,
            'totalWordCount' => $totalWordCount,
            'totalOrderCount' => $totalOrderCount,
        ]);
    }



    public function search_writerTl(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $WriterTL = $request->input('WriterTL');
        $SubWriter = $request->input('SubWriter');
        $extra = $request->input('extra');
        $startDateud = $request->input('startDate');
        $endDateud = $request->input('endDate');
        $startDatefd = $request->input('startDate1');
        $endDatefd = $request->input('endDate1');
        $daterange1 = $request->input('daterange');
        $daterange2 = $request->input('daterange1');



        $orders = Order::query();

        if ($searchTerm != '') {
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', $searchTerm);
            });
        }




        if ($daterange1 != '') {
            $dateRange = explode(' to ', $daterange1);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_fd', [$startDate, $endDate]);
        }

        if ($daterange2 != '') {


            $dateRange = explode(' to ', $daterange2);
            $startDate1 = date('Y-m-d', strtotime($dateRange[0]));
            $endDate1 = date('Y-m-d', strtotime($dateRange[1]));
            $orders->whereBetween('writer_ud', [$startDate1, $endDate1]);
        }


        if ($status != '') {
            if ($status == 'Not Assign') {
                $orders->where('writer_status',  '');
            } else {

                $orders->where('writer_status',  $status);
            }
        }



        if ($fromDate != '') {

            if ($fromDate != '' && $toDate != '') {
                $orders->whereBetween('writer_deadline', [$fromDate, $toDate]);
            } else {
                $orders->where('writer_deadline', $fromDate);
            }
        }







        if ($WriterTL != '') {
            $orders->where('wid', $WriterTL);
        }

        if ($SubWriter != '') {

            $multipleWriters = multipleswiter::where('user_id', $SubWriter)->get();

            $orderIds = $multipleWriters->pluck('order_id')->toArray();

            $orders->whereIn('id', $orderIds);
        }



        if ($extra != '') {
            if ($extra == 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra == 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra == 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra == '1') {
                $orders->where('services', 'First Class Work');
            }
        }

        $id = auth()->user()->id;

        if (auth()->user()->role_id == 6) {
            $orders = $orders->orderBy('id', 'desc')->where('wid', $id)->get();
        } elseif (auth()->user()->role_id == 7) {
            $orders = $orders->orderBy('id', 'desc')->where('swid', $id)->get();
        }

        if ($orders->isEmpty()) {
            return response()->json(['message' =>  'gv']);
        }

        $output = '';
        $index = 1;

        foreach ($orders as $order) {
            // Your existing code for creating the output string goes here
            $output .= '<tr>
                            <td>' .  $index++ . '</td>
                            <td>
                                ' . $order->order_id . '
                                ' . ($order->is_fail == 1 ? '<span class="badge badge-light-danger fs-7 fw-bold">Fail Order</span>' : '') . '
                                ' . ($order->services == 'First Class Work' ? '<span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>' : '') . '
                                ' . ($order->resit == 'on' ? '<span class="badge badge-light-danger fs-7 fw-bold">Resit</span>' : '') . '
                           
                                </td>  
                            
                               

                               
                                
                            <td>
                            ' . ($order->writer_fd != 0000 - 00 - 00 ?   \Carbon\Carbon::parse($order->writer_fd)->format('d M ')  : '') . '
                            <span class="badge badge-light-danger fs-7 fw-bold">' . $order->writer_fd_h . '</span>

                            </br>

                            ' . ($order->writer_ud != 0000 - 00 - 00 ?   \Carbon\Carbon::parse($order->writer_ud)->format('d M ')  : '') . '
                            <span class="badge badge-light-danger fs-7 fw-bold">' . $order->writer_ud_h . '</span>
                            
                            
                            </td>
                            
                            
                            <td>' . $order->title . '
                            ' . ($order->chapter != '' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->chapter . '</span>' : '') . '
                            ' . ($order->tech == '1' ? '<span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>' : '') . '
                            </td>
                            <td>
                            ' . ($order->writer_status ==  'In Progress'  ? '<span class="badge badge-light-info fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Completed' ? '<span class="badge badge-light-warning fs-7 fw-bold">' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Delivered' ? '<span class="badge badge-light-success fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Hold' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Feedback' ? '<span class="badge badge-light-warning fs-7 fw-bold"  >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Feedback Delivered' ? '<span class="badge badge-light-success fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Draft Delivered' ? '<span class="badge badge-light-success fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Quality Accepted' ? '<span class="badge badge-light-info fs-7 fw-bold"  >' . $order->writer_status . '</span>' : '') . '
                            ' . ($order->writer_status ==  'Quality Rejected' ? '<span class="badge badge-light-danger fs-7 fw-bold" >' . $order->writer_status . '</span>' : '') . '
                            
                       </td>

                       <td>' .  $order->pages . '</td>';

            if (auth()->user()->role_id !=  '7') {
                $output .= '<td>';

                $subWriterNames = [];

                foreach ($order->mulsubwriter as $writer) {
                    if ($writer != '') {
                        $subWriterNames[] = $writer->user->name;
                    }
                }

                if (empty($subWriterNames) && $order->subwriter != null && $order->subwriter->name != null) {
                    $output .= $order->subwriter->name; // Display if no sub-writer names are found and $order->subwriter->name is not null
                } else {
                    foreach ($subWriterNames as $name) {
                        $output .= $name . '<br>'; // Display sub-writer names
                    }
                }

                $output .= ($order->writer != '' ? '<span class="badge badge-light-info fs-7 fw-bold">(' . $order->writer->name . ')</span>' : '');

                $output .= '</td>';
            }


            $output .= '<td class="text-center">
                       <a href="/edit.' . $order->id . '"
                       target="_blank"
                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"">
                               <span class=" svg-icon svg-icon-3">
                           <li class="fa fa-edit"></li>
                           </span>
                       </a>
                    </td>
                        </tr>';
        }

        return response()->json($output);
    }

    public function swapUserData(Request $request)
    {
        $userId = $request->input('user_id');

        // Retrieve the user by ID
        $user = User::find($userId);

        if ($user) {
            // Swap the values
            $tempCountryCode = $user->countrycode;
            $tempMobileNo = $user->mobile_no;

            $user->countrycode = $user->countrycode2;
            $user->mobile_no = $user->mobile_no2;

            $user->countrycode2 = $tempCountryCode;
            $user->mobile_no2 = $tempMobileNo;

            // Save the changes
            $user->save();


            // Include the swapped data in the response
            $responseData = [
                'success' => true,
                'message' => 'Data swapped successfully',
                'mobile1' => [
                    'country_code' => $user->countrycode,
                    'mobile_number' => $user->mobile_no,
                ],
                'mobile2' => [
                    'country_code' => $user->countrycode2,
                    'mobile_number' => $user->mobile_no2,
                ],
            ];

            return response()->json($responseData, 200);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }

    public function call($id)
    {
        $user = User::find($id);
        return view('api.clic2call', compact('user'));
    }

    public function myOrder()
    {
        $data['title'] = 'Assignment Writing Help in London,UK | Assignment Writing Service in UK';
        $data['description'] = '';
        $data['keyword'] = '';

        $user = Auth::user();

        $orders = Order::where('uid', $user->id)->orderBy('created_at', 'desc')->get();

        $leads = Leads::where('emp_id', $user->id)->get();
        return view('frontend.my-orders', compact('data', 'orders', 'leads'));
    }

    public function qc(Request $request)
    {
        $data['executive'] = User::where('role_id', 3)->get();
        $data['writer'] = User::where('role_id', 6)->where('flag', 0)->get();
        $data['SubWriter'] = User::where('role_id', 7)->where('flag', 0)->get();

        $ordersQuery = Order::with('writer', 'mulsubwriter', 'subwriter')
            ->whereNotNull('admin_id')
            ->where('admin_id', '!=', 0)
            ->orderBy('created_at', 'desc');

        $searchTerm = $request->input('search');
        $status = $request->input('status');
        $writer = $request->input('writer');
        $SubWriter = $request->input('SubWriter');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $admin = $request->input('admin');
        $qc_standard = $request->input('qc_standard');
        $secondaryMobile = $request->input('secondary_mobile');
        $selectedDataTextBox = $request->input('selectedDataTextBox');
        $edited_on = $request->input('edited_on');
        $OldSubWriter = $request->input('OldSubWriter');

        if ($fromDate != '' && $toDate != '') {
            if ($edited_on == 'Order-date') {
                $ordersQuery->whereBetween('writer_deadline', [$fromDate, $toDate]);
            } elseif ($edited_on == 'Qc-date') {
                $ordersQuery->whereBetween('qc_date', [$fromDate, $toDate]);
            }
        } elseif ($fromDate != '') {
            if ($edited_on == 'Order-date') {
                $ordersQuery->whereDate('writer_deadline', $fromDate);
            } elseif ($edited_on == 'Qc-date') {
                $ordersQuery->whereDate('qc_date', $fromDate);
            }
        }

        if ($searchTerm != '') {
            $ordersQuery->where(function ($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', $searchTerm);
            });
        }

        // if ($status != '') {
        //     $ordersQuery->where('qc_status', $status);
        // }
        if ($status != '') {
            $ordersQuery->where(function ($query) use ($status) {
                if ($status == 'Not Assigned') {
                    $query->where('writer_status', '')->orWhereNull('writer_status')->orWhere('writer_status', 'Not Assigned');
                } else {
                    $query->where('writer_status', $status);
                }
            });
        }

        if ($writer != '') {
            $ordersQuery->where('wid', $writer);
        }
        if ($SubWriter != '') {

            $multipleWriters = multipleswiter::where('user_id', $SubWriter)->get();

            $orderIds = $multipleWriters->pluck('order_id')->toArray();

            $ordersQuery->whereIn('id', $orderIds);
        }


        if ($OldSubWriter != '') {
            $ordersQuery->where('swid', $OldSubWriter);
        }

        if ($admin != '') {
            $ordersQuery->where('qc_admin', $admin);
        }

        if ($qc_standard != '') {
            $ordersQuery->where('qc_standard', $qc_standard);
        }


        if ($fromDate != '' || $toDate != '' || $searchTerm != '' || $status != '' || $writer != '' || $SubWriter != '' || $admin != '' || $qc_standard != '' || $OldSubWriter != '') {
            $orders = $ordersQuery->paginate(1000);
            $data['orders'] = $orders;
        } else {
            $orders = $ordersQuery->paginate(10);
            $data['orders'] = $orders;
        }


        return view('order.qc-sheet', compact('data'));
    }


    public function QcUpdate(Request $request, $id)
    {


        $validatedData = $request->validate([
            'qc_status' => 'required',
            'qc_standard' => 'required',
            'comment' => 'required',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $order->writer_status = $validatedData['qc_status'];

        $order->qc_standard = $validatedData['qc_standard'];

        $order->qc_comment = $validatedData['comment'];

        $order->writer_deadline = $request->input('writer_date');

        $order->qc_date = Carbon::now('Asia/Kolkata');

        $order->save();

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function followUp()
    {
        $data['orders'] = Order::with(['user', 'followUpComments'])->orderBy('writer_deadline', 'desc')->paginate(10);
        foreach ($data['orders'] as $order) {
            $order->allCommentsByUid = FollowUpComment::where('uid', $order->uid)->get();
        }
        return view('follow.follow', compact('data'));
    }

    public function followUpUpdate(Request $request, $id)
    {

        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('danger', 'Order Not Found');
        }


        $uid = $order->uid;


        Order::where('uid', $uid)->each(function ($order) use ($request) {
            $order->follow_status = $request->input('follow_up_status');
            $order->follow_comment = $request->input('comment');
            $order->followupdate = now();
            $order->follow_up_user = auth()->user()->name;
            $order->save();
        });
        FollowUpComment::create([
            'order_id' => $order->id,
            'uid' => $order->uid,
            'comment' => $request->input('comment'),
            'commented_by' => auth()->user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Orders Updated');
    }

    public function orderWD(Request $request)
    {
        $ordersQuery = Order::with('user', 'payment', 'feedback')->where('uid', '!=', 0);
        $tlId = $request->input('tlId');
        if ($tlId != '') {
            // Fetch orders for the selected TL
            $orders = Order::where('wid', $tlId)->get();

            return response()->json(['orders' => $orders]);
        }



        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
        ];

        $data['orders'] = $ordersQuery->orderByDesc('id')->paginate(10);
        return view('order.writer-WD', compact('data'));
    }

    public function orderWD2(Request $request)
    {
        $tlId = $request->input('tlId');
        $swId = $request->input('swId');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$tlId && !$swId && !$fromDate && !$toDate) {
            return response()->json(['message' => 'No parameters provided.'], 400);
        }
        $query = Order::query()->with('writer', 'mulsubwriter')->where('admin_id', '!=', 0);
        if ($tlId === "Not Assigned") {
            $query->where(function ($query) {
                $query->whereNull('wid')
                    ->orWhere('wid', '');
            });
        } elseif ($tlId) {
            $query->where('wid', $tlId);
        }
        if ($swId) {
            $multipleWriters = multipleswiter::where('user_id', $swId)->get();
            $orderIds = $multipleWriters->pluck('order_id')->toArray();
            $query->whereIn('id', $orderIds);
        }

        if ($fromDate && $toDate) {
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('writer_fd', [$fromDate, $toDate])
                    ->orWhereBetween('writer_ud', [$fromDate, $toDate]);
            });
        }

        $orders = $query->orderByDesc('created_at')->get();
        $expandedOrders = [];
        $totalWordCount = 0;
        $totalWordCount = $orders->reduce(function ($carry, $order) {
            return $carry + (is_numeric($order->pages) ? $order->pages : 0);
        }, 0); // Initialize total word count
        $totalOrders = $orders->count(); // Count total number of orders

        foreach ($orders as $order) {
            $startDate = $order->writer_fd && $order->writer_fd !== '0000-00-00' ? Carbon::parse($order->writer_fd) : null;
            $endDate = $order->writer_ud && $order->writer_ud !== '0000-00-00' ? Carbon::parse($order->writer_ud) : null;
            $subWriterNames = [];
            foreach ($order->mulsubwriter as $mulsubwriter) {
                if ($mulsubwriter->user !== null) {
                    $subWriterNames[] = $mulsubwriter->user->name;
                }
            }
            if ($order->writer !== null) {
                $writerName2 = $order->writer->name;
            } else {
                $writerName2 = "";
            }
            if (!$startDate || !$endDate) {
                $expandedOrder = [
                    'order_id' => $order->order_id,
                    'date' => 'Not Mentioned',
                    'title' => $order->title ? $order->title : 'Not Mentioned', // Check if title is null or empty
                    'pages' => $order->pages ? $order->pages : 'Not Mentioned', // Check if pages is null or empty
                    'writer_name' => $writerName2,
                    'sub_writer_names' => implode(', ', $subWriterNames),
                ];

                $expandedOrders[] = $expandedOrder;
                continue;
            }
            $title = $order->title ? $order->title : 'Not Mentioned';
            $pages = $order->pages ? $order->pages : 'Not Mentioned';

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $expandedOrder = [
                    'order_id' => $order->order_id,
                    'date' => $date->toDateString(),
                    'title' => $title, // Use the title value set earlier
                    'pages' => $pages, // Use the pages value set earlier
                    'writer_name' => $writerName2,
                    'sub_writer_names' => implode(', ', $subWriterNames),

                ];
                $expandedOrders[] = $expandedOrder;
            }
        }
        // Sort expanded orders by date in ascending order
        usort($expandedOrders, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        if ($fromDate && $toDate) {
            $filteredOrders = [];
            foreach ($expandedOrders as $expandedOrder) {
                if ($expandedOrder['date'] >= $fromDate && $expandedOrder['date'] <= $toDate) {
                    $filteredOrders[] = $expandedOrder;
                }
            }
            return response()->json([
                'orders' => $filteredOrders,
                'total_word_count' => $totalWordCount, // Include total word count in the response
                'total_orders' => $totalOrders,
            ]);
        }
        return response()->json([
            'orders' => $expandedOrders,
            'total_word_count' => $totalWordCount, // Include total word count in the response
            'total_orders' => $totalOrders,
        ]);
    }

    public function updateDate(Request $request)
    {
        // Check if a date has been selected
        if ($request->input('selectedDate') != '') {
            $orderId = $request->input('orderId');
            $date = $request->input('selectedDate');

            // Find the order by its ID, or fail if not found
            $order = Order::findOrFail($orderId);

            // Check if the selected date is on or after the order date
            if ($order->order_date <= $date) {
                // Update the delivery date and save the order
                // $order->delivery_date = $date;
                $order->delivery_date = $date;
                $order->save();

                // Return a success response
                return response()->json(['message' => 'Date updated successfully', 'order' => $order]);
            } else {
                // Return an error response if the delivery date is before the order date
                return response()->json(['Error' => 'Delivery date cannot be before the order date.']);
            }
        } else {
            // Return an error response if no date is selected
            return response()->json(['Error' => 'Date not selected']);
        }
    }

    public function updateStatus(Request $request)
    {
        $statusInput = $request->input('status');
        $orderId = $request->input('orderId');
        $userComment = $request->input('comment');

        if ($request->filled('status')) {
            // Status fetch kar rahe hain directly string name se
            $statusName = Status::where('status', $statusInput)->first();

            if (!$statusName) {
                return response()->json(['error' => 'Status category not found in database']);
            }

            try {
                $order = Order::findOrFail($orderId);
                $userDetails = User::findOrFail($order->uid);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Order or User not found']);
            }

            // 1. Ticket logic check (Previous status count)
            $currentStatusLog = ProjectStatusCount::where('order_Id', $orderId)
                ->where('status', $statusName->status)
                ->first();

            // 2. AUTOMATIC TICKET LOGIC
            // if ($currentStatusLog && $currentStatusLog->count >= 1 && empty($order->feedback_ticket)) {
            //     if ($statusName->status == 'Feedback' || $statusName->status == 'Other') {
            //         $order->feedback_ticket = 'TCK-' . substr($order->order_id, 3);
            //         $order->status_issue = 'Issue Raised';
            //         $order->feedbackissue = 1;
            //         $order->save();
            //     }
            // }
            
            if ($currentStatusLog && $currentStatusLog->count >= 1 && empty($order->feedback_ticket)) {
                    $skipDissertationFeedbackTicket = $statusName->status == 'Feedback' && $order->typeofpaper == 'Dissertation';
                    if (!$skipDissertationFeedbackTicket && ($statusName->status == 'Feedback' || $statusName->status == 'Other')) {
                    $order->feedback_ticket = 'TCK-' . substr($order->order_id, 3);
                    $order->status_issue = 'Issue Raised';
                    $order->feedbackissue = 1;
                    $order->save();
                }
            }

            // 3. Delivered status validation (Payment check)
            if ($statusName->status == 'Delivered' && ((int)$order->amount - (int)$order->received_amount !== 0)) {
                return response()->json(['warning' => 'Order cannot be marked as Delivered if there is any due payment remaining.']);
            }

            // 4. Order Update
            if ($statusName->status == 'Delivered') {
                $order->delivery_date = Carbon::now('Asia/Kolkata')->toDateString();
            }
            $order->projectstatus = $statusName->status;
            $order->status_date = Carbon::now('Asia/Kolkata');
            $order->status_by = auth()->user()->name;
            if (trim(Str::lower($statusName->status)) === 'writer query') {
                $order->writerstatus_date = Carbon::now('Asia/Kolkata');
            }
            if ($statusName->status == 'Feedback' && $request->filled('feedback_date')) {
                $order->f_delivery_date = $request->feedback_date;
            }
            $order->save();

            // 5. Feedback Table Entry (Chat Box aur Sheet dono ke liye)
            $feedback = new Feedback();
            $feedback->order_id = $order->id;

            // Logical Fix: Comment ko dono columns mein save karwa rahe hain
            $finalComment = $userComment ? $userComment : "Status updated to " . $statusName->status;

            $feedback->comment = $finalComment;        // Ye aapki Chat UI (Order Comment) mein dikhega
            $feedback->action_comment = $finalComment; // Ye aapki Ticket Sheet mein dikhega

            $feedback->status = $statusName->status;
            $feedback->created_by = auth()->user()->id;
            $feedback->save();
            $mailSent = null;
            $mailError = null;

            if ($statusName->status == 'Completed') {

                $orderData = [
                    'name' => $userDetails->name,
                    'email' => $userDetails->email,
                    'title' => $order->title,
                    'order_code' => $order->order_id,
                    'date' => $order->delivery_date,
                    'due' => (int)$order->amount - (int)$order->received_amount,
                ];

                if (!filter_var($orderData['email'], FILTER_VALIDATE_EMAIL)) {

                    $mailSent = false;
                    $mailError = 'Invalid email address';
                } else {

                    try {
                        Mail::to($orderData['email'])
                            ->cc('order@assignnmentinneed.com')
                            ->bcc('yourmail@gmail.com')
                            ->send(new OrderComplete($orderData));

                        $mailSent = true;
                    } catch (\Throwable $e) {
                        $mailSent = false;
                        $mailError = $e->getMessage();

                        Log::error('Completed mail sending failed | Order: ' . $order->order_id . ' | Error: ' . $mailError);
                    }
                }
            }

            // 6. Update Status Count History
            $statusCount = ProjectStatusCount::where('order_Id', $orderId)
                ->where('status', $statusName->status)
                ->first();

            if (!$statusCount) {
                ProjectStatusCount::create([
                    'order_Id' => $order->id,
                    'status' => $statusName->status,
                    'count' => 1
                ]);
            } else {
                $statusCount->increment('count');
            }

            logActivity('Order', [
                'type' => 'Status Updated',
                'order_id' => $order->order_id,
                'new_status' => $statusName->status,
                'comment' => $finalComment,
                'ticket' => $order->feedback_ticket ?? null,
                'action_by' => auth()->user()->name,
            ]);




            return response()->json(['message' => 'Status updated successfully', 'order' => $order]);
        }

        return response()->json(['message' => 'Status Not Selected']);
    }

    public function statusDetails(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = Order::with('user')->where('uid', '!=', 0)
            ->where('status_date', '!=', '0000-00-00 00:00:00');

        if ($from_date && $to_date) {
            $query->whereDate('status_date', '>=', $from_date)->whereDate('status_date', '<=', $to_date);
        }

        if ($from_date && $to_date) {
            $data['order'] = $query->get();
        } else {
            $data['order'] = $query->paginate(10);
        }

        return view('order.order-status-details', compact('data'));
    }

    public function writerAvailable(Request $request)
    {
        $today = $request->input('available_date', Carbon::today()->format('Y-m-d'));

        $users = User::with(['writerWork' => function ($query) {
            $query->select(['id', 'user_id', 'order_id']);
        }, 'writerWork.order' => function ($query) {
            $query->select(['id', 'order_id', 'writer_fd', 'writer_fd_h', 'writer_ud', 'writer_ud_h']);
        }])
            ->where('role_id', 7)
            ->whereDoesntHave('writerWork.order', function ($query) use ($today) {
                $query->whereDate('writer_fd', '<=', $today)
                    ->whereDate('writer_ud', '>=', $today);
            })
            ->where('flag', 0)
            ->get(['id', 'name', 'email', 'mobile_no']);

        $usersWithTime = User::whereHas('writerWork.order', function ($query) use ($today) {
            $query->whereDate('writer_ud', '=', $today)->where('writer_ud_h', '!=', '');
        })
            ->with(['writerWork' => function ($query) use ($today) {
                $query->whereHas('order', function ($query) use ($today) {
                    $query->whereDate('writer_ud', '=', $today)->where('writer_ud_h', '!=', '');
                });
            }])
            ->where('role_id', 7)
            ->where('flag', 0)
            ->get(['id', 'name', 'email', 'mobile_no']);



        return view('order.writerAvailable', compact('users', 'usersWithTime', 'today'));
    }
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'policy' => 'nullable|boolean',

            // optional fields
            'countryCode' => 'nullable|string',
            'deadline' => 'nullable|string', // can be numeric days OR date string
            'pages' => 'nullable|integer|min:1',
            'service' => 'nullable|string',
            'subject' => 'nullable|string',

            'message' => 'nullable|string',
            'delivery_time' => 'nullable|string',
            'tech' => 'nullable|string',
            'resit' => 'nullable|string',

            'finalPrice' => 'nullable|numeric',
            'estimatedPrice' => 'nullable|numeric',
            'discount' => 'nullable',
        ]);

        // ✅ sanitize phone (for USER table only)
        $phoneDigits = preg_replace('/\D+/', '', (string) $request->phone);
        $cc = $request->filled('countryCode')
            ? preg_replace('/\D+/', '', (string) $request->countryCode)
            : null;

        // Generate Order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // Find/Create user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $phoneDigits,
                'countrycode' => $cc,
                'password' => Hash::make('user@123'),
            ]);
        } else {
            // optional: update missing info
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

            if ($dirty)
                $user->save();
        }

        // ✅ Lead data: keep minimal + store only required new fields
        $leadData = [
            'order_id' => $newOrderId,
            'emp_id' => $user->id,
            'create_at' => now(),
            'frontendorder' => 1,
        ];

        // ✅ show in leads table
        if ($request->filled('service'))
            $leadData['service_type'] = $request->service;
        if ($request->filled('subject'))
            $leadData['project_title'] = $request->subject;
        if ($request->filled('pages'))
            $leadData['pages'] = (int) $request->pages;

        // ✅ deadline normalize for blade (numeric days OR date string)
        if ($request->filled('deadline')) {
            $deadline = trim((string) $request->deadline);

            if (is_numeric($deadline)) {
                $days = max(1, (int) $deadline);
                $leadData['deadline'] = now()->addDays($days)->toDateString(); // YYYY-MM-DD (blade safe)
                $leadData['delivery_time'] = $days . ' Day' . ($days > 1 ? 's' : '');
            } else {
                try {
                    $leadData['deadline'] = Carbon::parse($deadline)->toDateString();
                } catch (\Exception $e) {
                    // fallback avoid blade crash
                    $leadData['deadline'] = now()->addDays(1)->toDateString();
                    $leadData['delivery_time'] = '1 Day';
                }
            }
        }

        // optional extras (if you want later)
        if ($request->filled('message'))
            $leadData['message'] = $request->message;
        if ($request->filled('tech'))
            $leadData['tech'] = $request->tech;
        if ($request->filled('resit'))
            $leadData['resit'] = $request->resit;

        // price (optional)
        if ($request->filled('finalPrice')) {
            $leadData['price'] = $request->finalPrice;
        } elseif ($request->filled('estimatedPrice')) {
            $leadData['price'] = $request->estimatedPrice;
        }

        $lead = Leads::create($leadData);

        Order::create([
            'order_id' => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id' => $lead->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quote submitted successfully!',
            'order_id' => $newOrderId,
        ]);
    }

    public function submitWithOrderNowPage(Request $request)
    {
        // Validation rules
        $rules = [
            'name'    => 'required|string',
            'service' => 'required|string',
            'workType' => 'required|string',
            'country'  => 'required|string',
            'subject' => 'required|string',
            'urgency' => 'required|string',
            'wordCount' => 'required|integer|min:250',
            'topic' => 'required|string',
            'requirements' => 'required|string',
            'fileUpload.*' => 'nullable|file|max:10240',
        ];

        if (!Auth::check()) {
            $rules = array_merge($rules, [
                'email' => 'required|email',
                'mobile' => 'required|string',
                'countrycode' => 'required|string',
            ]);
        }

        $request->validate($rules);


        // Calculate Delivery Date
        $today = now();
        $urgencyDays = $request->input('urgency');
        if (is_numeric($urgencyDays)) {
            $deliveryDate = $today->copy()->addDays($urgencyDays);
        } elseif ($urgencyDays === '16 to 20') {
            $deliveryDate = $today->copy()->addDays(16);
        } elseif ($urgencyDays === '21+') {
            $deliveryDate = $today->copy()->addDays(21);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid urgency selected.']);
        }

        // Generate Order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // Handle User
        if (Auth::check()) {
            $user = Auth::user();
            if (empty($user->country) && $request->filled('country')) {
                $user->country = $request->input('country');
                $user->save();
            }
        } else {
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                if (empty($user->country) && $request->filled('country')) {
                    $user->country = $request->input('country');
                    $user->save();
                }
            }

            if (!$user) {
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'mobile_no' => $request->input('mobile'),
                    'countrycode' => $request->input('countrycode'),
                    'country' => $request->input('country'),
                    'password' => Hash::make('user@123'),
                    'role_id' => 2
                ]);
            }
        }

        $uploadedFiles = [];
        if ($request->hasFile('fileUpload')) {
            foreach ($request->file('fileUpload') as $file) {
                $destinationPath = base_path('images/orders');  // base_path points to project root

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);  // Create directory if doesn't exist
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $uploadedFiles[] = 'images/orders/' . $fileName;
            }
        }

        // Leads
        $leads = Leads::create([
            'order_id' => $newOrderId,
            'emp_id' => $user->id,
            'deadline' => $deliveryDate->format('Y-m-d'),
            'create_at' => now(),
            'message' => $request->input('requirements'),
            'email' => $user->email,
            'user_name' => $user->name,
            'countrycode' => $user->countrycode,
            'mobile' => $user->mobile_no,
            'frontendorder' => 1,
            'project_title' => $request->input('service'),
            'pages' => $request->input('wordCount'),
            'price' => $request->input('finalPrice'),
            'service_type' => str_replace('FirstClass', 'First Class Work', $request->input('workType')),
            'page_url' =>  $request->source_page ?? null,
        ]);

        // Order
        Order::create([
            'order_id' => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id' => $leads->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $newOrderId,
        ]);
    }

    public function orderPaymentwithData(Request $request, $id, $paymentID)
    {
        $editOrder = Payment::find($paymentID);
        if (!$editOrder) {
            return redirect()->back()->with('error', 'Payment not found');
        }
        $order = Order::with('user')->find($id);
        return view('order.order_payment', compact('order', 'editOrder'));
    }

    public function dataUpdate(Request $req, $paymentID)
    {
        $editOrder = Payment::find($paymentID);
        if (!$editOrder) {
            return redirect()->back()->with('error', 'Payment not found');
        }
        $editOrder->payee_name = $req->input('payee_name') ?? '';
        $editOrder->company_accounts = $req->input('company_accounts') ?? '';
        $editOrder->reference = $req->input('message') ?? '';
        $editOrder->save();
        logActivity('Order', [
            'type' => 'Payment Updated',
            'payment_id' => $editOrder->id,
            'order_id' => $editOrder->order_id ?? null,
            'payee_name' => $req->input('payee_name'),
            'company_accounts' => $req->input('company_accounts'),
            'action_by' => auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Payment details updated successfully');
    }

    public function indexOrder()
    {
        $data = [
            'Team' => Writer::all(),
            'Status' => Status::all(),
            'formatting' => Formatting::all(),
            'service' => Services::all(),
            'Writting' => Writting::all(),
            'paper' => Paper::all(),
            'user' => User::all(),
            'college' => College::all(),
            'admin' => User::where('role_id', 8)->where('flag', 0)->get(),
            'writerTL' => User::where('role_id', 6)->where('flag', 0)->get(),
            'SubWriter' => User::where('role_id', 7)->where('flag', 0)->get(),
            'projectStatusCounts' => ProjectStatusCount::all()
        ];


        // Priority Logic: Due balance (Amount - Received) > 0 wale orders sabse upar
        $orders = Order::with(['user', 'payment', 'team', 'feedback.user', 'feedback.order', 'lead.call.user', 'followupComments.user', 'followupComments.order', 'additionals'])
            ->where('uid', '!=', 0)
            ->select('*')
            ->selectRaw('(CAST(amount AS SIGNED) - CAST(received_amount AS SIGNED)) as due_balance')
            ->orderByRaw('due_balance > 0 DESC') // Pending payment pehle
            ->latest('id')
            ->take(20)
            ->get();

        $totals = [
            'total_amount' => $orders->sum(function ($o) {
                return (int) $o->amount;
            }),

            'total_paid' => $orders->sum(function ($o) {
                return (int) $o->received_amount;
            }),

            'total_due' => $orders->sum(function ($o) {
                return (int)$o->amount - (int)$o->received_amount;
            }),
        ];

        // dd($orders->first()->lead);
        // dd($orders[1]->feedback);

        $overdueCount = Order::whereNotIn('projectstatus', ['Delivered', 'Completed'])
            ->whereNotNull('delivery_date')
            // ->whereMonth('delivery_date', now()->month)
            // ->whereYear('delivery_date', now()->year)
            ->where(function ($q) {
                $q->whereRaw("STR_TO_DATE(CONCAT(delivery_date, ' ', IFNULL(delivery_time,'00:00')), '%Y-%m-%d %H:%i') < NOW()");
            })
            ->count();
        $alphaCount = \App\Models\Order::where('team_id', 1)->count();
        $gigaCount = \App\Models\Order::where('team_id', 2)->count();
        $teams = Team::all();
        return view('back-end.order.index', compact('orders', 'totals', 'overdueCount', 'data', 'alphaCount', 'gigaCount', 'teams'));
    }

    public function changeTeam(Request $request)
    {
        // Only Admin
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        $order = Order::findOrFail($request->order_id);

        $order->team_id = $request->team_id;

        $order->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function editOrder($id)
    {
        $order = Order::with('user')->find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        $data['Team'] = Writer::where('flag', '1')->get();
        $data['Status'] = Status::where('status', '!=', 'pending')->get();
        $data['formatting'] = Formatting::all();
        $data['service'] = Services::all();
        $data['Writting'] = Writting::all();
        $data['paper'] = Paper::all();
        $data['college'] = College::all();
        $data['ordersub'] = multipleswiter::where('order_id', $id)->get();

        $userDetails = $order->user;
        if (auth()->user()->role_id == 4 || auth()->user()->role_id == 9) {
            return view('back-end.order.edit', compact('order', 'userDetails', 'data'));
        } else {
            return view('back-end.order.edit', compact('order', 'userDetails', 'data'));
        }
    }

    public function filter(Request $request)
    {
        // Get all filters from the request
        $filters = $request->all();

        // Check if all filters are empty, return a message if so
        if (empty($filters['search']) && empty($filters['uid']) && empty($filters['status']) && empty($filters['writer']) && empty($filters['dateStatus']) && empty($filters['fromDate']) && empty($filters['toDate']) && empty($filters['from_date']) && empty($filters['to_date']) && empty($filters['WriterTL']) && empty($filters['SubWriter']) && empty($filters['college']) && empty($filters['extra']) && empty($filters['module_code']) &&  empty($filters['paper_type']) && empty($filters['semester']) && empty($filters['month']) && empty($filters['payment']) && empty($filters['deadline_status']) && empty($filters['offer']) && empty($filters['duec']) && empty($filters['team_id']) && empty($filters['today_deadline_filter']) && empty($filters['today_writer_deadline_filter'])) {
            return response()->json(['message' => 'No filters applied'], 200);
        }

        // Special case: only month (one order per UID, only if no newer order exists for that UID)
        if ($request->filled('month')) {
            try {
                $startOfMonth = Carbon::parse($request->month . '-01')->startOfMonth();
                $endOfMonth = Carbon::parse($request->month . '-01')->endOfMonth();

                // Get all UIDs with orders in the selected month
                $uidsInMonth = Order::whereBetween('order_date', [$startOfMonth, $endOfMonth])
                    ->pluck('uid')
                    ->unique();

                if ($uidsInMonth->isEmpty()) {
                    return response()->json([
                        'html' => '<tr><td colspan="10" class="text-center">No orders in the selected month.</td></tr>',
                        'total' => 0,
                        'count' => 0,
                        'has_more' => false,
                    ]);
                }

                // Get UIDs with orders AFTER the selected month
                $uidsWithNewerOrders = Order::whereIn('uid', $uidsInMonth)
                    ->where('order_date', '>', $endOfMonth)
                    ->pluck('uid')
                    ->unique();

                // Final UIDs with no newer orders
                $validUIDs = $uidsInMonth->diff($uidsWithNewerOrders);

                // Get the latest order for each valid UID within the month
                $latestOrders = Order::whereIn('id', function ($query) use ($startOfMonth, $endOfMonth, $validUIDs) {
                    $query->selectRaw('MAX(id)')
                        ->from('orders')
                        ->whereBetween('order_date', [$startOfMonth, $endOfMonth])
                        ->whereIn('uid', $validUIDs)
                        ->groupBy('uid');
                })->orderByDesc('id')->get();


                // Generate HTML
                if ($latestOrders->isNotEmpty()) {
                    $html = '';
                    $index = 0;
                    // Fetch statuses once (not in loop)
                    $allStatus = Status::all();

                    foreach ($latestOrders as $order) {
                        $projectStatusCounts = ProjectStatusCount::where('order_id', $order->id)->get();

                        $html .= view('back-end.order.partials.row', [
                            'order' => $order,
                            'index' => $index++,
                            'data' => [
                                'projectStatusCounts' => $projectStatusCounts,
                                'Status' => $allStatus,
                            ],
                        ])->render();
                    }

                    return response()->json([
                        'html' => $html,
                        'total' => $latestOrders->count(),
                        'count' => $latestOrders->count(),
                        'has_more' => false,
                    ]);
                } else {
                    return response()->json([
                        'html' => '<tr><td colspan="10" class="text-center">No valid orders found for the selected month.</td></tr>',
                        'total' => 0,
                        'count' => 0,
                        'has_more' => false,
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'html' => '<tr><td colspan="10" class="text-center">Invalid month format.</td></tr>',
                    'total' => 0,
                    'count' => 0,
                    'has_more' => false,
                ]);
            }
        }

        // Default filter logic continues here if month+uid not both present

        $limit = $request->get('limit', 50);
        $offset = $request->get('offset', 0);

        $query = Order::with(['user', 'payment', 'team', 'feedback.user', 'feedback.order', 'lead.call.user']);
        $query->where('uid', '!=', 0);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_id', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        // Basic filters
        $query->when($request->filled('uid'), fn($q) => $q->where('uid', $request->uid));
        $query->when($request->filled('semester'), fn($q) => $q->where('semester', $request->semester));
        $query->when($request->filled('status'), fn($q) => $q->where('projectstatus', $request->status));
        $query->when($request->filled('module_code'), fn($q) => $q->where('module_code', 'like', '%' . $request->module_code . '%'));
        $query->when($request->filled('paper_type'), fn($q) => $q->where('typeofpaper', $request->paper_type));
        $query->when($request->filled('college'), fn($q) => $q->where('college_name', $request->college));

        if ($request->filled('offer')) {
            $query->where('offer', 'like', '%' . $request->offer . '%');
        }

        if ($request->filled('duec')) {
            if ($request->duec == 'due') {
                // Pending (due > 0)
                $query->whereRaw('(CAST(amount AS SIGNED) - CAST(received_amount AS SIGNED)) > 0');
            }
            if ($request->duec == 'no due') {
                // No due (<= 0)
                $query->whereRaw('(CAST(amount AS SIGNED) - CAST(received_amount AS SIGNED)) <= 0');
            }
        }

        // Writer logic
        if ($request->filled('writer')) {
            if ($request->writer === 'team 13') {
                $query->where('admin_id', 8392);
            } elseif ($request->writer === 'Not Assign') {
                $query->where(function ($q) {
                    $q->whereNull('writer_name')->orWhere('writer_name', '');
                });
            } else {
                $query->where('writer_name', 'like', $request->writer);
            }
        }

        // Writer TL
        $query->when($request->filled('WriterTL'), fn($q) => $q->where('wid', $request->WriterTL));

        // SubWriter logic (external model)
        if ($request->filled('SubWriter')) {
            $orderIds = multipleswiter::where('user_id', $request->SubWriter)->pluck('order_id')->toArray();
            $query->whereIn('id', $orderIds);
        }

        // if ($request->extra == 'overdue') {
        //     $query->whereDate('delivery_date', '<', Carbon::today())
        //         ->whereNotIn('projectstatus', ['Delivered', 'Completed']);
        // }

        $deadlineStatus = $request->input('deadline_status');
        if ($deadlineStatus == 'overdue') {
            // Overdue: Aaj ki date nikal gayi aur order complete nahi hua
            $query->whereDate('delivery_date', '<', now())
                ->whereNotIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered']);
        } elseif ($deadlineStatus == 'missed') {
            // Missed: Order complete ho gaya h, lekin deadline nikalne ke baad (updated_at > delivery_date)
            $query->whereIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered'])
                ->whereColumn('updated_at', '>', 'delivery_date');
        }

        if ($request->filled('team_id') && $request->team_id != '') {
            $query->where('team_id', $request->team_id);
        }

        // if ($request->filled('today_deadline_filter')) {
        //     $query->whereDate('delivery_date', Carbon::today());
        // }

        if ($request->filled('today_deadline_filter')) {
            $query->where(function ($q) {
                $q->whereDate('delivery_date', Carbon::today())
                    ->orWhereDate('f_delivery_date', Carbon::today());
            });
        }

        if ($request->filled('today_writer_deadline_filter')) {
            $query->whereDate('writer_deadline', Carbon::today());
        }


        // Extra conditions
        switch ($request->extra) {
            case 'tech':
                $query->where('tech', 1);
                break;
            case 'resit':
                $query->where('resit', 'on');
                break;
            case 'failedjob':
                $query->where('is_fail', 1);
                break;
            case '1':
                $query->where('services', 'First Class Work');
                break;
        }

        // Date filtering
        $from = $request->input('fromDate');
        $to = $request->input('toDate');
        $dateField = $request->input('dateStatus');

        if ($from && $to && $dateField) {
            if ($dateField === 'draft_date') {
                $query->whereBetween($dateField, [$from, $to])->where('draftrequired', 'y');
            } else {
                $query->whereBetween($dateField, [$from, $to]);
            }
        } elseif ($from && $to) {
            $query->whereBetween('order_date', [$from, $to]);
        } elseif ($from) {
            $query->where('order_date', $from);
        } elseif ($dateField) {
            $query->where('order_date', Carbon::today());
        }

        $applyMissingPayee = function ($q) {
            $q->whereHas('payment', function ($p) {
                $p->whereNull('payee_name')
                    ->orWhere('payee_name', '');
            });
        };

        if ($request->input('payment') === 'empty') {

            $currentYearStart = Carbon::now()->startOfYear();
            $currentYearEnd   = Carbon::now()->endOfYear();

            $query->whereBetween('order_date', [$currentYearStart, $currentYearEnd])
                ->whereHas('payment', function ($p) {
                    $p->where(function ($pp) {
                        $pp->whereNull('payee_name')
                            ->orWhere('payee_name', '');
                    });
                });
        }

        $query->orderBy('id', 'desc');
        $total = $query->count();
        $orders = $query->skip($offset)->take($limit)->get();

        $totals = [
            'total_amount' => $orders->sum(function ($o) {
                return (int) $o->amount;
            }),

            'total_paid' => $orders->sum(function ($o) {
                return (int) $o->received_amount;
            }),

            'total_due' => $orders->sum(function ($o) {
                return (int)$o->amount - (int)$o->received_amount;
            }),
        ];

        // dd($orders);
        // 👇 Fetch project status counts for the given orders
        $projectStatusCounts = ProjectStatusCount::whereIn('order_id', $orders->pluck('id'))
            ->get();

        $data = [
            'projectStatusCounts' => $projectStatusCounts,
            'Status' => Status::all(),
        ];

        // 👇 Ensure you pass both $orders and $data to rows view
        // $html = '';

        // foreach ($orders as $index => $order) {
        //     $html .= view('back-end.order.partials.row', [
        //         'order' => $order,
        //         'index' => $offset + $index,
        //         'data' => $data,
        //     ])->render();
        // }
        $teams = Team::all();
        $html = '';
        foreach ($orders as $index => $order) {
            $html .= view('back-end.order.partials.row', [
                'order' => $order,
                'index' => $offset + $index,
                'data' => $data,
                'teams' => $teams,
            ])->render();
        }

        return response()->json([
            'html' => $html,
            'total' => $total,
            'totals' => $totals,
            'count' => $orders->count(),
            'has_more' => ($offset + $limit < $total),
        ]);
    }

    public function loadCommentDrawer($id)
    {
        $order = Order::with('user', 'feedback')->findOrFail($id);
        return view('back-end.order.partials.comment', compact('order'));
    }

    public function showPaymentForm($orderId, $paymentId = null)
    {
        $order = Order::with('payment')->findOrFail($orderId);
        $editPayment = $paymentId ? Payment::findOrFail($paymentId) : null;
        return view('back-end.order.payment-page', compact('order', 'editPayment'));
    }

    public function storePayment(Request $request, $orderId)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'amount'           => 'required|numeric|min:0.01',
            'company_accounts' => 'nullable|string',
            'message'          => 'nullable|string|max:255',
            'payee_name'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        // Find order
        $order = Order::find($orderId);
        // dd($order);
        if (!$order) {
            return Redirect::back()->with('error', 'Order not found.');
        }

        // Remaining amount
        $remainingAmount = $order->amount - $order->received_amount;

        $paidAmount       = (float) $request->input('amount');
        $companyAccount   = $request->input('company_accounts');
        $referenceMessage = $request->input('message');
        // dd($companyAccount);

        if ($paidAmount > $remainingAmount) {
            return Redirect::back()->with('error', 'Paid amount exceeds the remaining due amount.');
        }

        // Kya yeh payment "Wallet" se aa raha hai?
        $fromWallet = ($companyAccount === 'Wallet');   // case exact "Wallet" hona chahiye

        DB::beginTransaction();

        try {
            $walletUser = null;

            if ($fromWallet) {
                // ✅ Yahan ACTUAL User model lao
                // Option 1: agar relation defined hai -> $order->user
                // Option 2: direct id se find karo (uid column)
                $walletUser = User::find($order->uid);

                // Debug ke liye ek baar check kar sakte ho:
                // dd($order->uid, $walletUser);

                if (!$walletUser) {
                    DB::rollBack();
                    return Redirect::back()->with('error', 'User not linked with this order.');
                }

                // Ab wallet sahi read hoga
                $walletBalance = (float) ($walletUser->Wallet ?? 0);

                if ($paidAmount > $walletBalance) {
                    DB::rollBack();
                    return Redirect::back()->with(
                        'error',
                        'Wallet balance is not sufficient for this payment.'
                    );
                }
            }

            // 1️⃣ Save payment entry
            $payment                       = new Payment();
            $payment->order_id             = $orderId;
            $payment->payment_date         = now()->format('l d F Y h:i A');
            $payment->paid_amount          = $paidAmount;
            $payment->reference            = $referenceMessage;
            $payment->payee_name           = $request->input('payee_name');
            $payment->payment_update_by    = auth()->user()->name;
            $payment->account_status       = 1;
            $payment->company_accounts     = $companyAccount;
            $payment->save();

            // 2️⃣ Order ka received amount update
            $order->received_amount += $paidAmount;
            $order->save();

            // 3️⃣ Agar Wallet se pay hua hai → wallet se DEBIT karo + transaction entry
            if ($fromWallet && $walletUser) {
                $currentBalance = (float) ($walletUser->wallet ?? 0);
                $newBalance     = $currentBalance - $paidAmount;

                // Safety: negative se bachao
                if ($newBalance < 0) {
                    $newBalance = 0;
                }

                WalletTransaction::create([
                    'user_id'       => $walletUser->id,
                    'amount'        => $paidAmount,
                    'type'          => 'debit',
                    'description'   => $referenceMessage
                        ? 'Order #' . $order->order_id . ' - ' . $referenceMessage
                        : 'Payment for Order #' . $order->order_id . ' (wallet debit)',
                    'balance_after' => $newBalance,
                ]);

                // User wallet update
                $walletUser->wallet = $newBalance;
                $walletUser->save();
            }

            DB::commit();

            logActivity('Order', [
                'type' => 'Payment Created',
                'action' => 'ADD_PAYMENT',
                'payment_id' => $payment->id,
                'order_id' => $orderId,
                'amount' => $paidAmount,
                'payment_method' => $companyAccount,
                'wallet_used' => $fromWallet ? 1 : 0,
                'action_by' => auth()->user()->name,
            ]);

            return redirect()
                ->route('orders.payment.form', $orderId)
                ->with('success', 'Payment added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function updatePayment(Request $request, $orderId, $paymentId)
    {

        $payment = Payment::find($paymentId);
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }
        $payment->payee_name = $request->input('payee_name') ?? '';
        $payment->company_accounts = $request->input('company_accounts') ?? '';
        $payment->reference = $request->input('message') ?? '';
        $payment->payment_update_by = auth()->user()->name;
        $payment->save();

        logActivity('Order', [
            'type' => 'Payment Updated',
            'payment_id' => $payment->id,
            'order_id' => $orderId,
            'payee_name' => $request->input('payee_name'),
            'company_accounts' => $request->input('company_accounts'),
            'action_by' => auth()->user()->name,
        ]);

        return redirect()->route('orders.payment.form', $orderId)->with('success', 'Payment updated successfully.');
    }


    public function submitMiniQuote(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'email'       => 'required|email',
            'phone'       => 'required|string',
            'policy'      => 'nullable|boolean',

            // mini-form fields
            'countryCode' => 'nullable|string',
            'countryIso'  => 'nullable|string',
            'deadline'    => 'nullable|string', // numeric days OR date string
            'words'       => 'nullable|integer|min:1', // ✅ will be stored in pages
            'service'     => 'nullable|string',        // ✅ will be stored in typeofpaper
            'subject'     => 'nullable|string',        // ✅ will be stored in project_title

            // optional
            'message'        => 'nullable|string',
            'delivery_time'  => 'nullable|string',
            'tech'           => 'nullable|string',
            'resit'          => 'nullable|string',

            'finalPrice'     => 'nullable|numeric',
            'estimatedPrice' => 'nullable|numeric',
            'discount'       => 'nullable',
            'source_page'    => 'nullable|string', // ✅ new field (page url)
        ]);

        // ✅ sanitize phone (for USER table only)
        $phoneDigits = preg_replace('/\D+/', '', (string) $request->phone);

        // ✅ keep countryCode same as your current logic
        $cc = $request->filled('countryCode')
            ? preg_replace('/\D+/', '', (string) $request->countryCode)
            : null;

        // ✅ Generate Order ID
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? (intval(substr($latestOrder->order_id, 3)) + 1) : 1;
        $newOrderId = 'UKS' . str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT);

        // ✅ Find/Create user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'mobile_no'  => $phoneDigits,
                'countrycode' => $cc,
                'password'   => Hash::make('user@123'),
            ]);
        } else {
            // optional update missing info
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

            if ($dirty) $user->save();
        }

        // ✅ Lead data
        $leadData = [
            'order_id'       => $newOrderId,
            'emp_id'         => $user->id,
            'create_at'      => now(),
            'frontendorder'  => 1,
        ];

        // ✅ Mapping changes
        // subject -> project_title
        if ($request->filled('subject')) {
            $leadData['project_title'] = $request->subject;
        }

        // service -> typeofpaper
        if ($request->filled('service')) {
            $leadData['typeofpaper'] = $request->service;
        }

        // words -> pages (as you asked)
        if ($request->filled('words')) {
            $leadData['pages'] = (int) $request->words;
        }

        // ✅ source page (new)
        // If frontend sends source_page, take it. Otherwise auto from referer/url.
        $leadData['page_url'] = $request->filled('source_page')
            ? $request->source_page
            : ($request->headers->get('referer') ?? $request->fullUrl());

        // ✅ deadline normalize (numeric days OR date string)
        if ($request->filled('deadline')) {
            $deadline = trim((string) $request->deadline);

            if (is_numeric($deadline)) {
                $days = max(1, (int) $deadline);
                $leadData['deadline'] = now()->addDays($days)->toDateString();
                $leadData['delivery_time'] = $days . ' Day' . ($days > 1 ? 's' : '');
            } else {
                try {
                    $leadData['deadline'] = Carbon::parse($deadline)->toDateString();
                } catch (\Exception $e) {
                    $leadData['deadline'] = now()->addDays(1)->toDateString();
                    $leadData['delivery_time'] = '1 Day';
                }
            }
        }

        // optional extras
        if ($request->filled('message')) $leadData['message'] = $request->message;
        if ($request->filled('tech'))    $leadData['tech'] = $request->tech;
        if ($request->filled('resit'))   $leadData['resit'] = $request->resit;

        // price (optional)
        if ($request->filled('finalPrice')) {
            $leadData['price'] = $request->finalPrice;
        } elseif ($request->filled('estimatedPrice')) {
            $leadData['price'] = $request->estimatedPrice;
        }

        $lead = Leads::create($leadData);

        Order::create([
            'order_id'      => $newOrderId,
            'projectstatus' => 'Pending',
            'lead_id'       => $lead->id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Quote submitted successfully!',
            'order_id' => $newOrderId,
        ]);
    }

    // feedback Rating
    public function feedbackList(Request $request)
    {
        $query = DB::table('feedbacks');

        // Search Filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('experience', 'like', '%' . $searchTerm . '%')
                    ->orWhere('feedback_scope', 'like', '%' . $searchTerm . '%');
            });
        }

        $feedbacks = $query->orderBy('id', 'desc')->paginate(20);
        return view('back-end.feedback.index', compact('feedbacks'));
    }

    public function feedbackDelete($id)
    {
        DB::table('feedbacks')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Feedback deleted successfully');
    }

    // Update Function (Simple Update example)
    public function feedbackUpdate(Request $request, $id)
    {
        DB::table('feedbacks')->where('id', $id)->update([
            'experience' => $request->experience,
            'feedback_scope' => $request->feedback_scope,
            'your_suggestion' => $request->your_suggestion,
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Feedback updated successfully');
    }

    public function ticketReport(Request $request)
    {
        $month = $request->input('month', date('Y-m')); // Default current month

        // 1. Team-wise Report
        $teamReport = DB::table('orders')
            ->select(
                'writer_name as team',
                DB::raw('count(*) as total_orders'),
                DB::raw('sum(case when feedbackissue = 1 then 1 else 0 end) as ticket_count'),
                DB::raw('sum(case when is_fail = 1 then 1 else 0 end) as failed_jobs')
            )
            ->where('writer_name', '!=', '')
            ->where('order_date', 'like', $month . '%')
            ->groupBy('writer_name')
            ->get()
            ->map(function ($item) {
                $item->ticket_percentage = $item->total_orders > 0 ? round(($item->ticket_count / $item->total_orders) * 100, 2) : 0;
                $item->failed_percentage = $item->total_orders > 0 ? round(($item->failed_jobs / $item->total_orders) * 100, 2) : 0;
                return $item;
            });

        // 2. Client-wise Report (Based on UID)
        $clientReport = DB::table('orders')
            ->join('users', 'orders.uid', '=', 'users.id')
            ->select(
                'users.name as client_name',
                'users.email as client_email',
                DB::raw('count(*) as total_orders'),
                DB::raw('sum(case when orders.feedbackissue = 1 then 1 else 0 end) as ticket_count'),
                DB::raw('sum(case when orders.is_fail = 1 then 1 else 0 end) as failed_jobs')
            )
            ->where('orders.order_date', 'like', $month . '%')
            ->groupBy('orders.uid', 'users.name', 'users.email')
            ->orderByDesc('ticket_count')
            ->limit(20)
            ->get();

        return view('back-end.reports.ticket_report', compact('teamReport', 'clientReport', 'month'));
    }

    public function rateWriter(Request $request)
    {
        // Order details nikalein
        $order = \App\Models\Order::find($request->order_id);

        if ($order) {
            // 1. Points ke hisaab se text set karein
            $points = (int) $request->rating;

            if ($points === 1) {
                $feedbackType = 'Good work';
            } elseif ($points === -1) {
                $feedbackType = 'Poor work';
            } else {
                $feedbackType = 'Average';
            }

            // 2. writer_list table se id nikalein
            $writerId = \Illuminate\Support\Facades\DB::table('writer_list')
                ->where('writer_name', $order->writer_name)
                ->value('id'); // direct ID nikalne ke liye 'value' use kiya

            // 3. Data ko writer_feedbacks table mein insert ya update karein
            \Illuminate\Support\Facades\DB::table('writer_feedbacks')->updateOrInsert(
                ['order_id' => $order->id], // Is order ki rating pehle se hai toh update hogi
                [
                    'uid' => $order->uid ?? 0,
                    'writer_id' => $writerId ?? 0,
                    'feedback_type' => $feedbackType,
                    'points' => $points,
                    'created_at' => now(), // Optional if DB handles it, but good practice in Laravel
                    'updated_at' => now(),
                ]
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    public function urgentOrders()
    {
        $now = now();
        $limit = $now->copy()->addMinutes(30);

        $orders = Order::whereNotIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered'])
            ->whereNotNull('delivery_date')
            ->get()
            ->filter(function ($order) use ($now, $limit) {

                $dateTime = $order->delivery_date;

                if ($order->delivery_time) {
                    $dateTime .= ' ' . $order->delivery_time;
                }

                try {
                    $deadline = \Carbon\Carbon::parse($dateTime, config('app.timezone'));
                } catch (\Exception $e) {
                    return false;
                }

                return $deadline->between($now, $limit);
            })
            ->sortBy(function ($order) {
                return $order->delivery_date . ' ' . $order->delivery_time;
            })
            ->values();

        return response()->json($orders);
    }

    public function getStatuses()
    {
        return response()->json(
            DB::table('status')->select('status')->get()
        );
    }

    public function saveMarks(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            $order->marks = $request->marks;
            $order->save();
            logActivity('Order', [
                'type' => 'Marks Updated',
                'order_id' => $order->order_id,
                'marks' => $request->marks,
                'action_by' => auth()->user()->name,
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function orderFeedback(Request $request)
    {
        $ordersQuery = Order::with('user')
            ->where('projectstatus', 'Delivered')
            ->whereNotNull('delivery_date');

        if ($request->filled('feedback_status')) {
            if ($request->feedback_status == 'yes') {
                $ordersQuery->whereIn('feedback_status', ['yes', 'completed']);
            }

            if ($request->feedback_status == 'no') {
                $ordersQuery->where(function ($query) {
                    $query->whereNull('feedback_status')
                        ->orWhere('feedback_status', '')
                        ->orWhere('feedback_status', 'no')
                        ->orWhere('feedback_status', 'pending');
                });
            }
        }

        if ($request->filled('order_date_from')) {
            $ordersQuery->whereDate('order_date', '>=', $request->order_date_from);
        }

        if ($request->filled('order_date_to')) {
            $ordersQuery->whereDate('order_date', '<=', $request->order_date_to);
        }

        if ($request->filled('feedback_date_from')) {
            $ordersQuery->whereDate('delivery_date', '>=', $request->feedback_date_from);
        }

        if ($request->filled('feedback_date_to')) {
            $ordersQuery->whereDate('delivery_date', '<=', $request->feedback_date_to);
        }

        $collection = $ordersQuery
            ->orderByRaw("
            CASE 
                WHEN LOWER(COALESCE(feedback_status, 'no')) IN ('yes', 'completed') THEN 1
                ELSE 0
            END ASC
        ")
            ->orderBy('delivery_date', 'desc')
            ->get()
            ->groupBy('uid')
            ->map(function ($userOrders) {
                $sortedOrders = $userOrders
                    ->sortBy(function ($order) {
                        $status = strtolower($order->feedback_status ?? '');
                        return in_array($status, ['yes', 'completed']) ? 1 : 0;
                    })
                    ->values();

                return [
                    'user' => $sortedOrders->first()->user,
                    'uid' => $sortedOrders->first()->uid,
                    'orders' => $sortedOrders,
                    'is_completed_group' => $sortedOrders->every(function ($order) {
                        $status = strtolower($order->feedback_status ?? '');
                        return in_array($status, ['yes', 'completed']);
                    }),
                ];
            })
            ->sortBy('is_completed_group')
            ->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;

        $currentItems = $collection
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $data = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        $statuses = Status::select('id', 'status')->get();
        $projectStatusCounts = ProjectStatusCount::get();

        return view('back-end.orderfeedback.index', compact(
            'data',
            'statuses',
            'projectStatusCounts'
        ));
    }

    public function markFeedback(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'feedback_status' => 'required|in:yes,no',
        ]);

        $order = Order::findOrFail($request->order_id);

        $order->update([
            'feedback_status' => $request->feedback_status,
            'feedback_date' => $request->feedback_status == 'yes' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback status updated.',
            'feedback_status' => $request->feedback_status,
            'order_id' => $order->id,
        ]);
    }

    public function moduleCodeReport(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $moduleCode = $request->module_code;

        $query = Order::select(
            'module_code',
            DB::raw('COUNT(*) as total_orders')
        )
            ->whereNotNull('module_code')
            ->where('module_code', '!=', '');

        if ($fromDate && $toDate) {
            $query->whereBetween('order_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('order_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('order_date', '<=', $toDate);
        }

        if ($moduleCode) {
            $query->where('module_code', $moduleCode);
        }

        $moduleCodes = $query
            ->groupBy('module_code')
            ->orderBy('total_orders', $sort == 'asc' ? 'asc' : 'desc')
            ->paginate(10)
            ->appends($request->query());

        foreach ($moduleCodes as $module) {
            $ordersQuery = Order::where('module_code', $module->module_code);

            if ($fromDate && $toDate) {
                $ordersQuery->whereBetween('order_date', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $ordersQuery->whereDate('order_date', '>=', $fromDate);
            } elseif ($toDate) {
                $ordersQuery->whereDate('order_date', '<=', $toDate);
            }

            $module->orders = $ordersQuery->orderByDesc('id')->get();
        }

        return view('back-end.order.module-code-report', compact('moduleCodes', 'sort', 'fromDate', 'toDate', 'moduleCode'));
    }

    public function searchModuleCodes(Request $request)
    {
        $search = $request->search;

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $moduleCodes = Order::whereNotNull('module_code')
            ->where('module_code', '!=', '')
            ->where('module_code', 'LIKE', "%{$search}%")
            ->select('module_code')
            ->distinct()
            ->limit(10)
            ->pluck('module_code');

        return response()->json($moduleCodes);
    }

    // public function universityReport(Request $request)
    // {
    //     $sort = $request->get('sort', 'desc');

    //     $fromDate = $request->from_date;
    //     $toDate = $request->to_date;
    //     $university = $request->university;

    //     $query = Order::select(
    //             'college_name',
    //             DB::raw('COUNT(*) as total_orders')
    //         )
    //         ->whereNotNull('college_name')
    //         ->where('college_name', '!=', '');

    //     // DATE FILTER
    //     if ($fromDate && $toDate) {

    //         $query->whereBetween('order_date', [$fromDate, $toDate]);

    //     } elseif ($fromDate) {

    //         $query->whereDate('order_date', '>=', $fromDate);

    //     } elseif ($toDate) {

    //         $query->whereDate('order_date', '<=', $toDate);
    //     }

    //     // UNIVERSITY FILTER
    //     if ($university) {

    //         $query->where('college_name', $university);
    //     }

    //     $universities = $query
    //         ->groupBy('college_name')
    //         ->orderBy('total_orders', $sort == 'asc' ? 'asc' : 'desc')
    //         ->paginate(10)
    //         ->appends($request->query());

    //     // ORDERS LIST
    //     foreach ($universities as $uni) {

    //         $ordersQuery = Order::where('college_name', $uni->college_name);

    //         if ($fromDate && $toDate) {

    //             $ordersQuery->whereBetween('order_date', [$fromDate, $toDate]);

    //         } elseif ($fromDate) {

    //             $ordersQuery->whereDate('order_date', '>=', $fromDate);

    //         } elseif ($toDate) {

    //             $ordersQuery->whereDate('order_date', '<=', $toDate);
    //         }

    //         $uni->orders = $ordersQuery
    //             ->orderByDesc('id')
    //             ->get();
    //     }

    //     return view(
    //         'back-end.order.university-report',
    //         compact(
    //             'universities',
    //             'sort',
    //             'fromDate',
    //             'toDate',
    //             'university'
    //         )
    //     );
    // }


    public function universityReport(Request $request)
    {
        $sort = $request->get('sort', 'desc');

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $university = $request->university;

        // MAIN QUERY
        $query = Order::select(
            'college_name',
            DB::raw('COUNT(DISTINCT uid) as total_users')
        )
            ->whereNotNull('college_name')
            ->where('college_name', '!=', '')
            ->whereNotNull('uid');

        // DATE FILTER
        if ($fromDate && $toDate) {

            $query->whereBetween('order_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {

            $query->whereDate('order_date', '>=', $fromDate);
        } elseif ($toDate) {

            $query->whereDate('order_date', '<=', $toDate);
        }

        // UNIVERSITY FILTER
        if ($university) {

            $query->where('college_name', $university);
        }

        $universities = $query
            ->groupBy('college_name')
            ->orderBy('total_users', $sort == 'asc' ? 'asc' : 'desc')
            ->paginate(10)
            ->appends($request->query());

        // USERS LIST
        foreach ($universities as $uni) {

            $usersQuery = User::whereIn('id', function ($q) use ($uni, $fromDate, $toDate) {

                $q->select('uid')
                    ->from('orders')
                    ->where('college_name', $uni->college_name);

                if ($fromDate && $toDate) {

                    $q->whereBetween('order_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {

                    $q->whereDate('order_date', '>=', $fromDate);
                } elseif ($toDate) {

                    $q->whereDate('order_date', '<=', $toDate);
                }
            });

            $uni->users = $usersQuery
                ->orderByDesc('id')
                ->get();
        }

        return view(
            'back-end.order.university-report',
            compact(
                'universities',
                'sort',
                'fromDate',
                'toDate',
                'university'
            )
        );
    }
    public function searchUniversity(Request $request)
    {
        $search = $request->search;

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $universities = Order::whereNotNull('college_name')
            ->where('college_name', '!=', '')
            ->where('college_name', 'LIKE', "%{$search}%")
            ->select('college_name')
            ->distinct()
            ->limit(10)
            ->pluck('college_name');

        return response()->json($universities);
    }

    public function followUpReport(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        $followupQuery = \App\Models\Feedback::query()
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->whereNotNull('created_by');

        if ($fromDate && $toDate) {
            $followupQuery->whereBetween('updated_at', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        } elseif ($fromDate) {
            $followupQuery->whereDate('updated_at', $fromDate);
        } elseif ($toDate) {
            $followupQuery->whereDate('updated_at', $toDate);
        }

        $followupUsers = (clone $followupQuery)
            ->select(
                'created_by',
                \DB::raw('COUNT(*) as followup_count')
            )
            ->groupBy('created_by')
            ->orderByDesc('followup_count')
            ->get();

        foreach ($followupUsers as $user) {

            $user->userData = \App\Models\User::find($user->created_by);

            $user->followups = (clone $followupQuery)
                ->where('created_by', $user->created_by)
                ->with('order.user')
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('back-end.order.follow-up-report', compact(
            'followupUsers',
            'fromDate',
            'toDate'
        ));
    }

    public function markLookingRefund(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->looking_for_refund = 1;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order marked as looking for refund'
        ]);
    }

   public function refundOrders(Request $request)
    {
        $query = Order::with(['user'])
            ->where('looking_for_refund', 1);

        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('user')) {
            $userSearch = $request->user;

            $query->whereHas('user', function ($q) use ($userSearch) {
                $q->where('name', 'like', '%' . $userSearch . '%')
                    ->orWhere('email', 'like', '%' . $userSearch . '%')
                    ->orWhere('mobile_no', 'like', '%' . $userSearch . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('projectstatus', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('order_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('order_date', '<=', $request->to_date);
        }

        $orders = $query->latest('id')->get();

        $statuses = Status::all();

        return view('back-end.order.refund-orders', compact('orders', 'statuses'));
    }

    public function saveAdditional(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        DB::table('additional')->insert([
            'order_id' => $order->order_id,
            'additional_word_count' => $request->additional_word_count,
            'additional_price' => $request->additional_price,
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Additional details saved successfully'
        ]);
    }

    public function additionalHistory($orderId)
{
    $history = DB::table('additional')
        ->leftJoin('users', 'users.id', '=', 'additional.created_by')
        ->where('additional.order_id', $orderId)
        ->select('additional.*', 'users.name as added_by')
        ->orderByDesc('additional.id')
        ->get();

    if ($history->isEmpty()) {
        return response()->json([
            'html' => '<div class="text-center text-muted py-5">No additional history found.</div>'
        ]);
    }

    $html = '';

    foreach ($history as $item) {
        $html .= '
            <div class="border rounded p-3 mb-3 bg-light">
                <div class="d-flex justify-content-between">
                    <strong>' . e($item->added_by ?? 'Unknown User') . '</strong>
                    <span class="text-muted fs-8">' . date('d M Y, h:i A', strtotime($item->created_at)) . '</span>
                </div>

                <div class="mt-2">
                    <span class="badge badge-light-primary me-2">
                        Words: ' . e($item->additional_word_count ?: 0) . '
                    </span>

                    <span class="badge badge-light-success">
                        Price: £' . e($item->additional_price ?: 0) . '
                    </span>
                </div>
            </div>';
    }

    return response()->json(['html' => $html]);
}
}
