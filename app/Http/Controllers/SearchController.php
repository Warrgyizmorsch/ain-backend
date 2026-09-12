<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;


class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $results = Order::where('order_id', 'LIKE', "%$query%")
            ->orWhere('title', 'LIKE', "%$query%")
            ->get();

        return response()->json($results);
    }


    public function search(Request $request)
    {
        $query = trim((string)$request->input('user'));
    
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $hasAsterisk = strpos($query, '*') !== false;
        $userIds = find_user_ids_by_search_term($query);
        $cleanDigits = preg_replace('/\D+/', '', $query);
        $pattern = $hasAsterisk ? preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $query)) : '';
        $cleanPattern = ltrim($pattern, '0');

        // Fetch data from the database based on the query, limiting to 10 results
        $results = User::select('id', 'name', 'email', 'mobile_no', 'mobile_no2', 'countrycode')
                        ->where(function($q) use ($query, $userIds, $cleanDigits, $hasAsterisk, $pattern, $cleanPattern) {
                            if (!empty($userIds)) {
                                $q->whereIn('id', $userIds);
                            }
                            $q->orWhere('name', 'like', "%$query%")
                                ->orWhere('email', 'like', "%$query%");
                            if ($hasAsterisk) {
                                if (!empty($cleanPattern) && preg_match('/\d/', $cleanPattern)) {
                                    $q->orWhere('mobile_no', 'like', "%$cleanPattern%")
                                      ->orWhere('mobile_no2', 'like', "%$cleanPattern%")
                                      ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ["%$cleanPattern%"])
                                      ->orWhereRaw("CONCAT(IFNULL(countrycode2, ''), IFNULL(mobile_no2, '')) LIKE ?", ["%$cleanPattern%"]);
                                }
                            } else if (strlen($cleanDigits) >= 2) {
                                $q->orWhere('mobile_no', 'like', "%$cleanDigits%")
                                  ->orWhere('mobile_no2', 'like', "%$cleanDigits%")
                                  ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ["%$cleanDigits%"]);
                            }
                        })
                        ->take(10)
                        ->get();

        $results->transform(function ($user) {
            $isSuperAdmin = auth()->check() && (int) auth()->user()->role_id === 1;

            if (!$isSuperAdmin) {
                $user->masked_mobile = mask_mobile_only($user->countrycode, $user->mobile_no);
                $user->masked_email = mask_email_for_display($user->email);

                $displayName = (string)$user->name;
                if (preg_match('/^user\d{7,}$/i', $displayName)) {
                    $user->display_name = 'user' . mask_mobile_only(null, substr($displayName, 4));
                } else {
                    $user->display_name = $displayName;
                }

                $user->mobile_no = $user->masked_mobile;
                $user->email = $user->masked_email;
                $user->name = $user->display_name;
            } else {
                $user->display_name = (string)$user->name;
            }

            $user->countrycode = null;
            return $user;
        });

        return response()->json($results);
    }


    public function searchUser(Request $request)
    {
        $query = trim((string) ($request->input('user') ?? $request->input('query', $request->input('term', ''))));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $hasAsterisk = strpos($query, '*') !== false;
        $userIds = find_user_ids_by_search_term($query);
        $cleanDigits = preg_replace('/\D/', '', $query);
        $withoutLeadingZero = !empty($cleanDigits) ? ltrim($cleanDigits, '0') : '';
        $pattern = $hasAsterisk ? preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $query)) : '';
        $cleanPattern = ltrim($pattern, '0');

        $results = User::select('id', 'name', 'email', 'mobile_no', 'mobile_no2', 'countrycode')
            ->where('flag', 0)
            ->where(function ($userQuery) use ($query, $cleanDigits, $withoutLeadingZero, $userIds, $hasAsterisk, $pattern, $cleanPattern) {
                if (!empty($userIds)) {
                    $userQuery->whereIn('id', $userIds);
                }
                $userQuery->orWhere('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");

                if ($hasAsterisk) {
                    if (!empty($cleanPattern) && preg_match('/\d/', $cleanPattern)) {
                        $userQuery->orWhere('mobile_no', 'like', "%{$cleanPattern}%")
                            ->orWhere('mobile_no2', 'like', "%{$cleanPattern}%")
                            ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ["%{$cleanPattern}%"])
                            ->orWhereRaw("CONCAT(IFNULL(countrycode2, ''), IFNULL(mobile_no2, '')) LIKE ?", ["%{$cleanPattern}%"]);
                        if ($pattern !== $cleanPattern) {
                            $userQuery->orWhere('mobile_no', 'like', "%{$pattern}%")
                                ->orWhere('mobile_no2', 'like', "%{$pattern}%");
                        }
                    }
                } else {
                    $userQuery->orWhere('mobile_no', 'like', "%{$query}%")
                        ->orWhere('mobile_no2', 'like', "%{$query}%");

                    if (!empty($cleanDigits) && strlen($cleanDigits) >= 2) {
                        $userQuery->orWhere('mobile_no', 'like', "%{$cleanDigits}%")
                            ->orWhere('mobile_no2', 'like', "%{$cleanDigits}%")
                            ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ["%{$cleanDigits}%"]);

                        if (!empty($withoutLeadingZero)) {
                            $userQuery->orWhere('mobile_no', 'like', "%{$withoutLeadingZero}%")
                                ->orWhere('mobile_no2', 'like', "%{$withoutLeadingZero}%")
                                ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) LIKE ?", ["%{$withoutLeadingZero}%"]);
                        }
                    }
                }
            })
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        $results->transform(function ($user) {
            $cleanCC = preg_replace('/\D+/', '', (string)$user->countrycode);
            $cleanMob = preg_replace('/\D+/', '', (string)$user->mobile_no);

            $user->masked_mobile = mask_mobile_only($user->countrycode, $user->mobile_no);
            $user->masked_mobile2 = mask_mobile_only(null, $user->mobile_no2);
            $user->masked_email = mask_email_for_display($user->email);

            $displayName = (string)$user->name;
            if (preg_match('/^user\d{7,}$/i', $displayName)) {
                $numPart = substr($displayName, 4);
                $user->display_name = 'user' . mask_mobile_only(null, $numPart);
            } else {
                $user->display_name = $displayName;
            }

            $user->countrycode = !empty($cleanCC) ? ('+' . $cleanCC) : '+44';
            $user->raw_mobile = $cleanMob;

            if (auth()->check() && auth()->user()->role_id != 1) {
                $user->mobile_no = $user->masked_mobile;
                $user->mobile_no2 = $user->masked_mobile2;
                $user->email = $user->masked_email;
                $user->name = $user->display_name;
            }

            return $user;
        });

        return response()->json($results);
    }
    


    public function searchOrders(Request $request)
    {
        $search = $request->input('search');
    
        $orders = Order::where('order_id', 'like', "%$search%")
                        ->orWhere('title', 'like', "%$search%")
                        ->get();
    
        return response()->json($orders);
    }

    public function searchQc(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        $writer = $request->input('writer');
        $SubWriter = $request->input('SubWriter');
        $dateStatus = $request->input('date_status');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $admin =$request->input('admin');
        $qc_standard =$request->input('qc_standard');
        $secondaryMobile = $request->input('secondary_mobile'); // Add this line to get the secondary mobile number
        $selectedDataTextBox = $request->input('selectedDataTextBox'); // Add this line to get the secondary mobile number
        $edited_on = $request->input('edited_on'); // Add this line to get the secondary mobile number


        $orders = Order::query();
    
        if ($searchTerm != '') {
            $orders->where(function($query) use ($searchTerm) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('title', $searchTerm);
            });
        }
      
    
        if ($status != '') {
            $orders->where('qc_status',  $status );
        }
    
        if($writer != '' && $SubWriter != ''){
            $orders->where('wid',  $writer )->where('swid',  $SubWriter );
        }
        if ($writer != '') {
           
                $orders->where('wid',  $writer );
           
        }
        if($admin != '')
        {
            $orders->where('qc_admin', $admin);
            // dd($admin);
        }

        if($qc_standard)
        {
            $orders->where('qc_standard', $qc_standard);

        }

        if($fromDate != '')
        {
            if($edited_on != '' && $fromDate != '' && $toDate != '')
             {
                $orders->whereBetween('qc_date', [$fromDate, $toDate ]);

             }
             else if ($fromDate != '' && $toDate != '')
             {
                $orders->whereBetween('writer_deadline', [$fromDate, $toDate ]);

             }
 
 
            
         }

       
    
        $orders = $orders->with('writer')->orderBy('id', 'desc')
                            ->where('uid', '!=' ,'0')
                            ->whereNotNull('admin_id')
                            ->where('admin_id', '!=', 0)->get();
            
        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No data found']);
        }
    
        $output = '';
        $index = 1;
    
        foreach ($orders as $order) {
            // Your existing code for creating the output string goes here
            $output .= '<tr>';
                               $output = '<td>' . ($order->qc_checked == '1' ?
                                "<div class='form-check form-check-sm form-check-custom form-check-solid m-5'>
                                    <input class='form-check-input widget-13-check' type='checkbox' id='{$order->id}' checked onchange='checkedLead(this, {$order->id})'>
                                </div>" :
                                "<div class='form-check form-check-sm form-check-custom form-check-solid m-5'>
                                    <input onchange='toggleCheck(this, {$order->id})' class='form-check-input widget-13-check' type='checkbox' value='1'>
                                </div>") . '</td>';

                            $output .= '     
                            <td>' .  $index++. '</td>
                            <td>
                                ' . $order->order_id . '
                            </td>  
                            <td>
                                '.\Carbon\Carbon::parse($order->writer_deadline)->format('d M Y').'
                            </td> 
                                
                           
                           
                            <td>
                                '.($order->qc_status     == 'Not Assigned'   ? '<span class="badge badge-light-success fs-7 fw-bold" >'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'In Progress' ? '<span class="badge badge-light-info fs-7 fw-bold" >'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Draft Ready' ? '<span class="badge badge-light-danger fs-7 fw-bold" >'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Draft Feedback' ? '<span class="badge badge-light-warning fs-7 fw-bold" >'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Attached to Email (Draft)' ? '<span class="badge badge-light-danger fs-7 fw-bold" >'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Draft Delivered' ? '<span class="badge badge-light-success fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Complete file Ready' ? '<span class="badge badge-light-warning fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Feedback' ? '<span class="badge badge-light-warning fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Attached to Email (Complete file)' ? '<span class="badge badge-light-info fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Delivered' ? '<span class="badge badge-light-success fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                                '.($order->qc_status ==  'Hold' ? '<span class="badge badge-light-success fs-7 fw-bold">'.$order->qc_status .'</span>' : '') .'
                            </td>
                      
                            <td>
                            '.($order->qc_date != null  ? 
                                 \Carbon\Carbon::parse($order->edited_on)->format('d M Y H:i') 
                            : '') .'

                           </td>
                             <td>'.$order->qc_admin.'</td>
                           
                          
                          
                            <td>
                            '.($order->qc_standard     == 'poor'   ? '<span class="badge badge-light-success fs-7 fw-bold" style="background:#795548; color:white">'.$order->qc_standard .'</span>' : '') .'
                            '.($order->qc_standard ==  'Good' ? '<span class="badge badge-light-info fs-7 fw-bold" style="background:#eaea00; color:black">'.$order->qc_standard .'</span>' : '') .'
                            '.($order->qc_standard ==  'moderate' ? '<span class="badge badge-light-danger fs-7 fw-bold" style="background:#4caf50; color:white">'.$order->qc_standard .'</span>' : '') .'
                            </td> 
                            
                            <td id="ai-score-'.$order->id.'" class="editable" data-order-id="'.$order->id.'">'.$order->ai_score.' %</td>                            
                             ';
                           

                             $output .= '<script>
                             $(document).ready(function() {
                                 $(".editable").click(function() {
                                     var orderId = $(this).data("order-id");
                                     
                                     Swal.fire({
                                         title: "Enter new AI Score",
                                         input: "text",
                                         inputAttributes: {
                                             autocapitalize: "off"
                                         },
                                         showCancelButton: true,
                                         confirmButtonText: "Ok",
                                         showLoaderOnConfirm: true,
                                         preConfirm: (value) => {
                                             if (!value) {
                                                 Swal.showValidationMessage("Please enter a value");
                                             }
                                             return value;
                                         },
                                         allowOutsideClick: () => !Swal.isLoading()
                                     }).then((result) => {
                                         if (result.isConfirmed) {
                                             var newScore = result.value;
                                             var CSRF_TOKEN = $("meta[name=\'csrf-token\']").attr("content");
                                             $.ajax({
                                                 type: "POST",
                                                 url: "/update-ai-score/" + orderId,
                                                 headers: {
                                                     "X-CSRF-TOKEN": CSRF_TOKEN
                                                 },
                                                 data: {
                                                     _token: CSRF_TOKEN,
                                                     newScore: newScore
                                                 },
                                                 success: function(response) {
                                                     $("#ai-score-"+orderId).text(newScore + " %"); // Update the displayed score
                                                     Swal.fire("Updated!", "AI Score has been updated.", "success");
                                                 },
                                                 error: function(xhr, status, error) {
                                                     Swal.fire("Error", "Failed to update AI Score.", "error");
                                                 }
                                             });
                                         }
                                     });
                                 });
                             });
                         </script>';

                            
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
                            
                            $output .= ($order->writer != '' ? '<span class="badge badge-light-info fs-7 fw-bold">('.$order->writer->name.')</span>' : '');
                            
                             
                             
     
                             $output .= '</td>';
     
                             $output .='<td>'
                            .$order->qc_comment.'</td>
                          
                            
                            
                            <td class="text-end">
                                <div class="card-toolbar">
                                    <a href="Qc-edit.'.$order->id.'" target="_blank" id="kt_toolbar_primary_button" class="btn btn-sm btn-light-primary">
                                        <li class="fa fa-edit"> </li>
                                    </a>
                                </div>
                            </td>
                        </tr>';
        }
    
        return response()->json($output);
    }
    

    public function Qc_edit($id)
    {
        $order = Order::find($id);
        $data['executive'] = User::where('role_id', 3)->get();
        $data['writer'] = User::where('role_id', 6)->where('flag' , 0)->get();

        return view('order.qc-edit', compact('data','order'));

        // dd($order);

    }
    
    public function searchFw(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        $uid = $request->input('uid');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
    
        $orders = Order::with('user');
    
        if ($searchTerm) {
            $searchUserIds = find_user_ids_by_search_term($searchTerm);
            $orders->where(function($query) use ($searchTerm, $searchUserIds) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('title', 'like', '%' . $searchTerm . '%');
                if (!empty($searchUserIds)) {
                    $query->orWhereIn('uid', $searchUserIds);
                }
            });
        }
    
        if ($status) {
            $orders->where('follow_status', $status);
        }
    
        if ($uid) { 
            $orders->where('uid', $uid); 
        }

        if($fromDate || $toDate)
        {
            if($fromDate && $toDate)
            {
                $orders->whereBetween('order_date', [$fromDate, $toDate]);

            }
            elseif($toDate)
            {
                $orders->whereBetween('order_date', [$toDate, $toDate]);

            }
            else{
                $orders->whereBetween('order_date', [$fromDate, $fromDate]);
            }

        }
        
    
      
    
        $orders = $orders->orderBy('id', 'desc')->where('uid', '!=' ,'0')->get();
    
        $output = '';
        $index = 1;
    
        foreach ($orders as $order) {
            // Your existing code for creating the output string goes here
            $output .= '<tr>
            <td>' . ($order->user && $order->user->followup == 1 ?
            '<div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                <input onchange="FollowUpUser(this, ' . $order->user->id . ')" class="form-check-input widget-13-check" type="checkbox" checked value="1">
            </div>' :
            '<div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                '. ($order->user ? '<input ' . ($order->user) . ' onchange="FollowUpUser(this, ' . $order->user->id . ')" class="form-check-input widget-13-check" type="checkbox" value="1">' : '') .'
            </div>') . 
        '</td>';
        $output .= '<script>
                    function FollowUpUser(checkbox, UserId) {
                        var checkedValue = checkbox.checked ? 1 : 0;
                        $.ajax({
                            url: \'/followUpUser/\' + UserId,
                            method: \'PUT\',
                            data: {
                                followup: checkedValue
                            },
                            headers: {
                                \'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
                            },
                            success: function(response) {
                                if (checkbox.checked) {
                                    console.log("Lead with ID " + UserId + " cancelled successfully.");
                                } else {
                                    console.log("Lead with ID " + UserId + " restored successfully.");
                                }
                             location.reload();

                                
                            },
                            error: function(xhr, status, error) {
                                console.error("Error: " + error);
                            }
                        });
                    }
                </script>';

                    $output .= '<td>' .  $index++. '</td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <span>' . e($order->order_id) . '</span>
                                ' . (!empty($order->order_id) ? '<button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order Code" onclick="event.stopPropagation(); crmCopyToClipboard(\'' . e($order->order_id) . '\', \'Order code copied!\');"><i class="fa fa-clone fs-8 text-muted"></i></button>' : '') . '
                            </div>
                        </td>';

                    if ($order->user) {
                        $rawName = $order->user->name ?? '';
                        $rawEmail = $order->user->email ?? '';
                        $rawMobile = $order->user->mobile_no ?? '';
                        $rawCC = $order->user->countrycode ?? '';
                        $cleanCC = preg_replace('/\D+/', '', (string)$rawCC);
                        $maskedEmail = $rawEmail ? mask_email_for_display($rawEmail) : '';
                        $maskedMobile = $rawMobile ? mask_mobile_only($cleanCC, $rawMobile) : '';

                        $userHtml = '';
                        if (!empty($rawName)) {
                            $userHtml .= '<div class="d-inline-flex align-items-center">
                                <span class="fw-bold">' . e($rawName) . '</span>
                                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Name" onclick="event.stopPropagation(); crmCopyToClipboard(\'' . e(addslashes($rawName)) . '\', \'Customer name copied!\');"><i class="fa fa-clone fs-8 text-muted"></i></button>
                            </div>';
                        }

                        if (!empty($maskedEmail)) {
                            $userHtml .= '<div class="d-inline-flex align-items-center my-1">
                                <span class="text-gray-600 fs-8 text-break">' . e($maskedEmail) . '</span>
                                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard(\'' . e($maskedEmail) . '\', \'Email copied!\');"><i class="fa fa-clone fs-8 text-muted"></i></button>
                            </div>';
                        }

                        if (!empty($maskedMobile)) {
                            $callHtml = '';
                            if (!empty($rawMobile)) {
                                $callHtml = '<a href="#" onclick="event.preventDefault(); initiateCustomerCall(\'' . e($cleanCC . $rawMobile) . '\', \'' . e(addslashes($rawName ?: 'Customer')) . '\');" title="Call via Twilio" class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1"><span class="svg-icon svg-icon-3"><i class="fa fa-phone fa-lg"></i></span></a>';
                            }
                            $userHtml .= '<div class="d-inline-flex align-items-center gap-1 my-1">
                                ' . (!empty($cleanCC) ? '<span class="badge badge-light-primary fs-8 fw-bold">+' . e($cleanCC) . '</span>' : '') . '
                                <span class="badge badge-light-danger fs-7 fw-bold">' . e($maskedMobile) . '</span>
                                <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard(\'' . e($maskedMobile) . '\', \'Mobile number copied!\');"><i class="fa fa-clone fs-8 text-danger"></i></button>
                                ' . $callHtml . '
                            </div>';
                        }

                        $output .= '<td>' . $userHtml . '</td>';
                    } else {
                        $output .= '<td><span class="badge badge-light-danger">User Deleted</span></td>';
                    }

                    $output .= '<td>' . $order->order_date . '</td>
                        <td>' . $order->followupdate . '</td>
                        <td>' . ($order->follow_status == 'negative but convinced' || $order->follow_status == 'negative' ? '<span class="badge badge-light-danger fs-7 fw-bold">' . $order->follow_status . '</span><br>' : ($order->follow_status == 'positive' || $order->follow_status == 'positive and referral' ? '<span class="badge badge-light-warning fs-7 fw-bold">' . $order->follow_status . '</span><br>' : ($order->follow_status == 'positive and own order' ? '<span class="badge badge-light-success fs-7 fw-bold">' . $order->follow_status . '</span><br>' : ($order->follow_status == 'No response' ? '<span class="badge badge-light-primary fs-7 fw-bold">' . $order->follow_status . '</span><br>' : '')))) . '</td>
                        <td>' . $order->follow_comment . ' ' . ($order->follow_comment ? '<a href="#" id="' . $order->order_id . '" data-bs-toggle="modal" data-bs-target="#confirmationModal' . $order->order_id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">More...</a>' : '') . '</td>
                        <td>' . $order->follow_up_user . '</td>
                        <td class="text-center">
                                <div class="card-toolbar">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa_newLeads' . $order->id . '" id="kt_toolbar_primary_button" class="btn btn-sm btn-light-primary">
                                        <li class="fa fa-edit"></li>
                                    </a>
                                </div>

                                <div class="modal fade" id="kt_modal_create_appaa_newLeads' . $order->id . '" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered mw-950px">
                                    <div class="modal-content rounded">
                                        <div class="modal-header pb-0 border-0 justify-content-end">
                                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                <span class="svg-icon svg-icon-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black">
                                                        </rect><rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <form id="kt_modal_new_target_form" class="form" method="POST"  action="'.route('follow.update', ['id' => $order->id]) . '">
                                        '.csrf_field().'
                                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                    <div class="mb-13 text-center">
                                                        <h1 class="mb-3">Status Edit Of Follow Up ' . $order->order_id . '</h1>
                                                        <div class="text-muted fw-bold fs-5">
                                                        </div>
                                                    </div>
                                                    <div class="row g-9 mb-8 text-start">
                                                        <div class="col-md-6 fv-row">
                                                            <label class="fs-6 fw-bold mb-2">Follow-Up Status</label>
                                                            <select name="follow_up_status" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-16-796922" tabindex="-1" >
                                                                <option value=""></option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'negative but convinced' ? 'selected' : '') . ' value="negative but convinced">negative but convinced</option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'negative' ? 'selected' : '') . ' value="negative">negative</option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'positive' ? 'selected' : '') . ' value="positive">positive</option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'positive and referral' ? 'selected' : '') . ' value="positive and referral">positive and referral</option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'positive and own order' ? 'selected' : '') . ' value="positive and own order">positive and own order</option>
                                                                <option ' . (old('follow_up_status', $order->follow_status) == 'No response' ? 'selected' : '') . ' value="No response">No response</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-9 mb-8 text-start">
                                                        <div class="col-md-12 fv-row">
                                                            <label class="fs-6 fw-bold mb-2">Comment</label>
                                                                <textarea name="comment" value="" class="form-control form-control-solid" id="" cols="30" rows="3">' . $order->follow_comment . '</textarea>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                          
                            
                            
                            
                        </tr>';
        }
    
        return response()->json($output);    
    }


    public function updateAi(Request $request ,$orderId)
    {
        $newScore = $request->input('newScore');

        // Update the AI score in the database
        $order = Order::find($orderId);
        $order->ai_score = $newScore;
        $order->save();
    
        return response()->json(['success' => true]);
    }
    
    public function qcchecked($leadId)
    {
        $lead = Order::findOrFail($leadId);
        if($lead->qc_checked == 1)
        {
            $lead->qc_checked = 0;
        }
        else
        {
            $lead->qc_checked = 1;
        }
        $lead->qc_admin = auth()->user()->name;
        $lead->save();

        return response()->json(['message' => 'Lead checked successfully ' ]);
    }

}
