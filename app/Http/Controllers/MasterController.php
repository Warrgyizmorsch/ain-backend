<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Writer;
use App\Models\SubWriter;
use App\Models\Categories;
use App\Models\Services;
use App\Models\Formatting;
use App\Models\Paper;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Bank;
use App\Models\Status;
use App\Models\User;
use App\Models\College;
use App\Models\Source;
use Illuminate\Support\Facades\Hash;


class MasterController extends Controller
{
    public function typeOfSecvices()
    {
        $services = Services::all();
        return view('dynamic.services', compact('services'));
    }



    public function insert_services(Request $req)
    {
        $services = new Services;
        $services->service_name = $req->input('services');
        $services->factor = $req->input('factor');
        $services->save();

        return redirect()->back()->with('success', 'writer submitted succesffully');
    }

    public function update_service(Request $req, $id)
    {
        $services = Services::findOrFail($id);
        $services->service_name = $req->input('services');
        $services->factor = $req->input('factor');
        $services->save();

        return redirect()->back()->with('success', 'writer submitted updated');
    }

    public function Delete_service($id)
    {
        $services = Services::find($id);
        if (!$services) {
            return redirect()->back()->with('error', 'User not found');
        }
        $services->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function formatting()
    {
        $formatting = Formatting::all();
        return view('dynamic.formatting', compact('formatting'));
    }

    public function formatting_insert(Request $req)
    {
        $formatting = new Formatting;
        $formatting->formatting_name = $req->input('formatting');
        $formatting->factor = $req->input('factor');
        $formatting->save();
        return redirect()->back()->with('success', 'writer submitted updated');
    }

    public function formatting_update(Request $req, $id)
    {
        $formatting = Formatting::findOrFail($id);
        $formatting->formatting_name = $req->input('formatting');
        $formatting->factor = $req->input('factor');
        $formatting->save();
        return redirect()->back()->with('success', 'writer submitted updated');
    }

    public function formatting_delete($id)
    {
        $formatting = Formatting::find($id);
        if (!$formatting) {
            return redirect()->back()->with('error', 'User not found');
        }
        $formatting->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }


    public function paperType()
    {
        $paper = Paper::all();
        return view('dynamic.paper', compact('paper'));
    }

    public function insert_paper(Request $req)
    {
        $paper = new paper;
        $paper->paper_type  = $req->input('paper');
        $paper->factor      = $req->input('factor');
        $paper->flag      = '0';
        $paper->save();
        return redirect()->back()->with('success', 'Paper Type Succesfully Submitted');
    }

    public function paper_update(Request $req, $id)
    {

        $paper = Paper::find($id);
        $paper->paper_type  = $req->input('paper');
        $paper->factor      = $req->input('factor');
        $paper->flag      = '0';
        $paper->save();
        return redirect()->back()->with('success', 'Paper Type Succesfully Submitted');
    }

    public function delete_paper($id)
    {
        $paper = Paper::find($id);
        if (!$paper) {
            return redirect()->back()->with('error', 'User not found');
        }
        $paper->delete();
        return redirect()->back()->with('success', 'Paper Type deleted successfully');
    }

    public function failedJobs(Request $request)
    {
        $search_o = $request->input('order_id', '');

        if (!empty($search_o)) {

            // Search for orders based on order_id
            $filterOrder = Order::where('order_id', $search_o)->first();
            // Dump the results for debugging
            // dd($filterOrder->toArray());
            return view('master.failed', ['filterOrder' => $filterOrder]);
        } else {
            $data['all_order'] = Order::where('is_fail', 1);
            $order = Order::where('is_fail', 1)->orderByDesc('id')->paginate(20);
            $order_filter = Order::where('is_fail', 1)->orderByDesc('id')->get();


            return view('master.failed', ['order' => $order, 'data' => $data, 'order_filter' => $order_filter]);
        }
    }
    public function Categories()
    {
        $categories = Categories::all();

        return view('master.categories', [
            'categories' => $categories,
        ]);
    }

    public function store_categories(Request $req)
    {
        $category = new Categories;
        $category->category_name = $req->input('name');
        $category->save();
        return redirect()->back()->with('success', "New Categories Add Successfully");
    }

    public function update_categories(Request $req, $id)
    {
        $category = Categories::find($id);
        $category->category_name = $req->input('name');
        $category->save();
        return redirect()->back()->with('success', "New Categories Add Successfully");
    }


    public function delete_categories($id)
    {
        $categories = Categories::find($id);
        if (!$categories) {
            return redirect()->back()->with('error', 'User not found');
        }
        $categories->delete();
        return redirect()->back()->with('success', 'Paper Type deleted successfully');
    }

    public function payments(Request $request)
    {
        $OrderCode = $request->input('search');
        $fromDate = $request->input('dateFrom');
        $toDate = $request->input('dateTo');
        $Uid = $request->input('uid');
        $company_accounts = $request->input('company_accounts');
        $p_name = $request->input('p_name');

        $query = Payment::with('order.user')
            ->orderByRaw("
            CASE
                WHEN account_status = 1 AND (payee_name IS NULL OR payee_name = '') THEN 0
                WHEN account_status = 1 THEN 1
                WHEN account_status = 0 AND (payee_name IS NULL OR payee_name = '') THEN 2
                ELSE 3
            END
        ")
            ->orderByDesc('id');


        if ($OrderCode != '') {
            $query->whereHas('order', function ($subQuery) use ($OrderCode) {
                $subQuery->where('order_id', $OrderCode);
            });
        }
        if ($fromDate != '' && $toDate != '') {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate != '') {
            $query->whereDate('created_at', $fromDate);
        }

        if ($Uid != '') {
            $query->whereHas('order.user', function ($subQuery) use ($Uid) {
                $subQuery->where('id', $Uid);
            });
        }

        if ($company_accounts != '') {
            $query->whereHas('order', function ($subQuery) use ($company_accounts) {
                $subQuery->where('company_accounts', $company_accounts);
            });
        }

        if ($p_name != '') {
            $query->whereHas('order', function ($subQuery) use ($p_name) {
                $subQuery->where('payee_name', 'like', '%' . $p_name . '%');
            });
        }
        $data['order_code'] = Order::all();
        $data['payments_user'] = User::all();
        $data['payments'] = $query->paginate(20);
        // $data['payments'] = $query->orderBy('id', 'desc')->paginate(20);

        return view('master.payments', compact('data'));
    }
    public function update_payments(Request $request)
    {
        // Validate the incoming request data
        $request->validate([

            'price' => 'required|numeric',
            'message' => 'required|string',
        ]);

        // Retrieve the payment by its ID
        $payment = Payment::find($request->payment_id);

        $order = Order::find($request->order_id);
        if (!$payment) {
            return back()->with('error', 'Payment not found.');
        }
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Update the payment details
        // $payment->payment_date = $request->date;
        // $payment->payment_date = date('Y-m-d H:i:s');

        $payment->paid_amount = $request->price;
        $payment->company_accounts = $request->company_accounts;
        $payment->reference = $request->message;

        // Save the updated payment
        $payment->save();

        //receive ammount
        $payments = Payment::where('order_id', $request->order_id)->get();
        $totalPaidAmount = $payments->sum('paid_amount');

        $order->received_amount = $totalPaidAmount;
        $order->save();
        // Redirect back with success message
        return back()->with('success', 'Payment updated successfully.');
    }
    public function delete_payments(Request $request, $id)
    {
        try {
            // Find the payment by ID
            $payment = Payment::findOrFail($id);

            // Get the order ID of the payment
            $orderId = $payment->order_id;

            // Delete the payment
            $payment->delete();

            // Get all payments related to the order
            $payments = Payment::where('order_id', $orderId)->get();

            // Calculate the total paid amount for the order
            $totalPaidAmount = $payments->sum('paid_amount');

            // Find the order by ID
            $order = Order::find($orderId);

            // Update the received amount for the order
            $order->received_amount = $totalPaidAmount;
            $order->save();
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'Failed to delete payment'], 500);
        }
    }
    // public function bulkUpdateStatus(Request $request)
    // {
    //     $payments = $request->input('payments');

    //     foreach ($payments as $paymentData) {
    //         $paymentId = $paymentData['payment_id'];
    //         $status = $paymentData['status'];

    //         $payment = Payment::find($paymentId);

    //         if ($payment) {
    //             $payment->account_status = $status ? 0 : 1;
    //             $payment->save();
    //         }
    //     }

    //     return response()->json(['message' => 'Payment statuses updated successfully']);
    // }



    public function Banks()
    {
        $banks = Bank::all();

        return view('master.banks', ['banks' => $banks]);
    }
    public function store_bank(Request $request)
    {

        $bank = new Bank;
        $bank->name = $request->input('name');
        $bank->account_holder = $request->input('account_holder');
        $bank->account_number = $request->input('account_number');
        $bank->sort_code = $request->input('sort_code');
        $bank->save();
        return redirect()->back()->with('success', "New Bank Add Successfully");
    }
    public function edit(Request $request, $id)
    {
        // Fetch the bank record with the given ID
        $bank = Bank::find($id);

        $bank->name = $request->input('name');
        $bank->account_holder = $request->input('account_holder');
        $bank->account_number = $request->input('account_number');
        $bank->sort_code = $request->input('sort_code');
        $bank->save();
        return redirect()->back()->with('success', "Bank Edit Successfully");
    }

    public function destroy($id)
    {

        $bank = Bank::find($id);
        if (!$bank) {
            return redirect()->back()->with('error', 'Bank not found');
        }
        $bank->delete();
        return redirect()->back()->with('success', 'Bank deleted successfully');
    }

    public function status()
    {
        $status = Status::all();
        // dd($status);
        return view('master.status', ['status' => $status]);
    }

    public function College()
    {
        $College = College::all();

        return view('master.college', [
            'College' => $College,
        ]);
    }
    public function store_college(Request $req)
    {
        $college = new College;
        $college->college_name = $req->input('name');
        $college->save();
        return redirect()->back()->with('success', "New College Add Successfully");
    }
    public function college_update(Request $req, $id)
    {
        $college = College::find($id);
        $college->college_name = $req->input('name');
        $college->save();
        return redirect()->back()->with('success', "New College Add Successfully");
    }


    public function delete_college($id)
    {
        $college = College::find($id);
        if (!$college) {
            return redirect()->back()->with('error', 'college not found');
        }
        $college->delete();
        return redirect()->back()->with('success', 'college deleted successfully');
    }
    //writer section
    public function Writer()
    {
        $writer = User::where('role_id', 8)->where('status', '0')->get();
        return view('dynamic.writerlist', compact('writer'));
    }

    public function store_writer(Request $req)
    {
        $Writer = new User;
        $Writer->name = $req->input('name');
        $Writer->email = $req->input('email');
        $Writer->password = Hash::make('user@123');
        $Writer->role_id = 8;
        $Writer->save();

        return redirect()->back()->with('success', 'Writer Created Successfully');
    }

    public function update_writer(Request $req, $id)
    {
        $writer = User::find($id);
        $writer->name = $req->input('name');
        $writer->email = $req->input('email');
        $writer->save();
        return redirect()->back()->with('success', 'Writer Updated Successfully');
    }

    public function delete_writer($id)
    {
        $writer = User::find($id);
        if (!$writer) {
            return redirect()->back()->with('error', 'Writer not found');
        }
        $writer->flag = 1;
        $writer->save();
        return redirect()->back()->with('success', 'Writer deleted successfully');
    }

    public function SubWriter(Request  $request)
    {
        $tlId       = $request->input('writerTL_id');

        if (auth()->user()->role_id == 1) {
            $data['tl'] = User::where('role_id', 6)->where('flag', '0')->get();
            if ($tlId) {
                $subwriter = User::where('role_id', 7)->where('tl_id', $tlId)->where('flag', '0')->orderByDesc('id')->paginate(100);
            } else {
                $subwriter = User::where('role_id', 7)->where('flag', '0')->orderByDesc('id')->get();
            }
        } else {
            $data['tl'] = User::where('role_id', 6)->where('flag', '0')->where('admin_id', auth()->user()->id)->get();
            if ($tlId) {
                $subwriter = User::where('role_id', 7)->where('tl_id', $tlId)->where('flag', '0')->where('admin_id', auth()->user()->id)->orderByDesc('id')->paginate(100);
            } else {
                $subwriter = User::where('role_id', 7)->where('flag', '0')->where('admin_id', auth()->user()->id)->orderByDesc('id')->get();
            }
        }




        return view('dynamic.subwriterlist', compact('subwriter', 'data'));
    }

    public function getSubWriters($writerTLId)
    {
        // Using the Eloquent ORM to query the 'users' table
        $subwriters = User::where('tl_id', $writerTLId)
            ->where('flag', '0')
            ->get();

        // Returning a JSON response with the retrieved subwriters
        return response()->json($subwriters);
    }
    //for edit modal 
    public function getWriterTLs()
    {
        $writerTLs = User::where('role_id', 6)->where('flag', '0')->get(); // You may need to adjust this based on your actual model and data

        return response()->json($writerTLs);
    }

    public function store_subwriter(Request $req)
    {
        $SubWriter = new User;
        $SubWriter->name = $req->input('name');
        $SubWriter->email = $req->input('email');
        $SubWriter->admin_id = auth()->user()->id;
        $SubWriter->password = Hash::make('user@123');
        $SubWriter->role_id = 7;
        $SubWriter->tl_id = $req->input('writerTL_id');

        if (!$req->input('writerTL_id')) {
            return redirect()->back()->with('error', 'Select a WriterTL first.');
        } else {
            $SubWriter->save();
            return redirect()->back()->with('success', 'SubWriter Created Successfully');
        }
    }

    public function update_subwriter(Request $req, $id)
    {
        $SubWriter = User::find($id);
        $SubWriter->name = $req->input('name');
        $SubWriter->email = $req->input('email');
        $SubWriter->tl_id = $req->input('writerTL_id');
        $SubWriter->save();
        return redirect()->back()->with('success', 'SubWriter Updated Successfully');
    }

    public function delete_subwriter($id)
    {
        $SubWriter = User::find($id);
        if (!$SubWriter) {
            return redirect()->back()->with('error', 'SubWriter not found');
        }
        $SubWriter->flag = 1;
        $SubWriter->save();
        return redirect()->back()->with('success', 'SubWriter deleted successfully');
    }

    public function writerTL()
    {
        $data['admin'] = User::where('role_id', 8)->where('flag', '0')->get();

        if (auth()->user()->role_id == 1) {
            $writerTL = User::where('role_id', 6)->where('flag', '0')->orderByDesc('id')->get();
        } else {
            $writerTL = User::where('role_id', 6)->where('flag', '0')->where('admin_id', auth()->user()->id)->orderByDesc('id')->get();
        }
        return view('dynamic.writerTL', compact('writerTL', 'data'));
    }

    public function writerTLinsert(Request $request)
    {
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email already exists');
        }

        // Create a new user with role_id = 6
        $writerTL = new User;
        $writerTL->name = $request->input('name');
        if (auth()->user()->role_id == 1) {
            $writerTL->admin_id = $request->input('admin_id');
        } else {
            $writerTL->admin_id = auth()->user()->id;
        }
        $writerTL->email = $request->input('email');
        $writerTL->password = Hash::make('user@123');
        $writerTL->role_id = 6;
        $writerTL->save();

        // Create a user with role_id = 7 and email based on the name of the previous user
        $SelfWriter = new User;
        $SelfWriter->name = 'SelfAsWriter';
        $SelfWriter->admin_id = auth()->user()->id;
        $SelfWriter->email = 'SelfAsWriter@' . $request->input('name') . '.com';
        $SelfWriter->tl_id = $writerTL->id; // Assigning the same id as the previous user
        $SelfWriter->role_id = 7;
        $SelfWriter->password = Hash::make('user@123');
        $SelfWriter->save();



        return redirect()->back()->with('success', 'Writer Team Leader Insert Successfully');
    }

    public function update_writerTL(Request $request, $id)
    {
        $writerTL = User::find($id);
        $writerTL->name = $request->input('name');
        $writerTL->email = $request->input('email');
        $writerTL->password = Hash::make('user@123');
        $writerTL->role_id = 6;
        if (auth()->user()->role_id == 1) {
            $writerTL->admin_id = $request->input('admin_id');
            $subwriter = User::where('tl_id', $writerTL->id)->get();

            foreach ($subwriter as $subwriter) {
                $subwriter->admin_id = $request->input('admin_id');
                $subwriter->save();
            }
        } else {
            $writerTL->admin_id = auth()->user()->id;
        }
        $writerTL->save();






        return redirect()->back()->with('sucess', 'Writer Team Leader updated Successfully');
    }

    public function delete_writerTL($id)
    {
        // echo $id; exit;
        $deleted = User::destroy($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Writer Team Leader deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Writer Team Leader not found');
        }
    }
    public function updateAccountStatus(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->account_status = $request->input('account_status');
            $payment->save();

            return response()->json(['success' => true, 'message' => 'Account status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $payments = $request->input('payments');

        foreach ($payments as $paymentData) {
            $paymentId = $paymentData['payment_id'];
            $status = $paymentData['status'];

            $payment = Payment::find($paymentId);

            if ($payment) {
                // $payment->account_status = $status ? 0 : 1;
                $payment->account_status = $status;

                $payment->save();
            }
        }
    }

    public function dataLoad()
    {
        // Define the start date as Jan 1, 2019
        $startDate = Carbon::createFromDate(2019, 1, 1)->startOfDay();

        // Define the end date as Dec 31, 2025
        $endDate = Carbon::createFromDate(2025, 8, 31)->endOfDay();

        // Fetch users created within that date range
        $users = User::whereBetween('created_on', [$startDate, $endDate])->get();

        // Pass the data to the view
        return view('leads.leads_data', compact('users'));
    }

    public function sources()
    {
        $sources = Source::where('is_delete', 0)->orderByDesc('id')->get();
        return view('master.sources', compact('sources'));
    }

    // Store
    public function store_source(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255'
        ]);

        Source::create([
            'source_name' => $req->name
        ]);

        return back()->with('success', 'Source Added Successfully');
    }

    // Update
    public function update_source(Request $req, $id)
    {
        $req->validate([
            'name' => 'required|string|max:255'
        ]);

        $source = Source::findOrFail($id);
        $source->update([
            'source_name' => $req->name
        ]);

        return back()->with('success', 'Source Updated Successfully');
    }

    // Delete
    public function delete_source($id)
    {
        $source = Source::findOrFail($id);
        $source->is_delete = 1;
        $source->save();

        return back()->with('success', 'Source Deleted Successfully');
    }
}
