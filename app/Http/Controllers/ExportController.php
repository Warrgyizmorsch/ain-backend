<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Leads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\multipleswiter;


class ExportController extends Controller
{
     public function export(Request $request)
    {
        // Retrieve filter parameters from the request supporting both camelCase and snake_case
        $searchTerm = trim((string)($request->input('search') ?: $request->input('searchTerm') ?: ''));
        $uid = $request->input('uid');
        $userParam = trim((string)($request->input('user') ?: $request->input('searchInput') ?: ''));
        $status = $request->input('status');
        $writer = $request->input('writer');
        $dateStatus = $request->input('dateStatus') ?: $request->input('date_status');
        $fromDate = $request->input('fromDate') ?: $request->input('from_date');
        $toDate = $request->input('toDate') ?: $request->input('to_date');
        $WriterTL = $request->input('WriterTL') ?: $request->input('writerTL');
        $SubWriter = $request->input('SubWriter');
        $college = $request->input('college');
        $extra = $request->input('extra');
        $module_code = $request->input('module_code') ?: $request->input('secondary_mobile');
        $semester = $request->input('semester');
        $paper_type = $request->input('paper_type') ?: $request->input('typeofpaper');
        $team_id = $request->input('team_id') ?: $request->input('filter_team_id');
        $offer = $request->input('offer');
        $duec = $request->input('duec');
        $marks_filter = $request->input('marks_filter');
        $deadline_status = $request->input('deadline_status');
        $today_deadline_filter = $request->input('today_deadline_filter');
        $yesterday_deadline_filter = $request->input('yesterday_deadline_filter');
        $today_writer_deadline_filter = $request->input('today_writer_deadline_filter');
        $month = $request->input('month');

        // Query orders based on the filter parameters
        $orders = Order::with(['user', 'lead.user', 'frontendLead.user', 'team', 'writer', 'mulsubwriter.user'])
            ->where(function ($q) {
                $q->whereNotNull('orders.uid')
                  ->orWhereNotNull('orders.lead_id');
            });

        // Apply search by order ID or title
        if ($searchTerm) {
            $orders->where(function ($query) use ($searchTerm) {
                $query->where('orders.order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('orders.title', 'like', '%' . $searchTerm . '%');
            });
        }

        // Customer search (UID or Name/Email/Mobile)
        if ($uid) {
            $orders->where('orders.uid', $uid);
        } elseif ($userParam) {
            $matchingUserIds = User::where('name', 'like', '%' . $userParam . '%')
                ->orWhere('email', 'like', '%' . $userParam . '%')
                ->orWhere('mobile_no', 'like', '%' . $userParam . '%')
                ->pluck('id')->toArray();

            $orders->where(function ($q) use ($matchingUserIds, $userParam) {
                if (!empty($matchingUserIds)) {
                    $q->whereIn('orders.uid', $matchingUserIds)
                      ->orWhereHas('lead', fn($lq) => $lq->whereIn('emp_id', $matchingUserIds))
                      ->orWhereHas('frontendLead', fn($flq) => $flq->whereIn('emp_id', $matchingUserIds));
                }
                $q->orWhereHas('lead', function ($lq) use ($userParam) {
                    $lq->where('user_name', 'like', '%' . $userParam . '%')
                       ->orWhere('email', 'like', '%' . $userParam . '%')
                       ->orWhere('mobile', 'like', '%' . $userParam . '%');
                });
            });
        }

        if ($semester) {
            $orders->where('orders.semester', $semester);
        }

        if ($module_code) {
            $orders->where('orders.module_code', 'like', '%' . $module_code . '%');
        }

        if ($paper_type) {
            $orders->where('orders.typeofpaper', $paper_type);
        }

        if ($status) {
            if ($status === 'Hold Work') {
                $orders->where(function ($sq) {
                    $sq->where('orders.projectstatus', 'Hold Work')
                       ->orWhere('orders.projectstatus', 'Hold(writer query)');
                });
            } else {
                $orders->where('orders.projectstatus', $status);
            }
        }

        if ($writer) {
            if ($writer === 'team 13') {
                $orders->where('orders.admin_id', 8392);
            } elseif ($writer === 'Not Assign') {
                $orders->where(function ($q) {
                    $q->whereNull('orders.writer_name')->orWhere('orders.writer_name', '');
                });
            } else {
                $orders->where('orders.writer_name', 'like', $writer);
            }
        }

        // Role & Team scoping
        if (auth()->check() && (int)auth()->user()->role_id === 9 && !empty(auth()->user()->team_id)) {
            $orders->where('orders.team_id', auth()->user()->team_id);
        } elseif (!empty($team_id)) {
            $orders->where('orders.team_id', $team_id);
        }

        // Date filtering
        if ($fromDate && $toDate && $dateStatus) {
            if ($dateStatus === 'draft_date') {
                $orders->whereBetween($dateStatus, [$fromDate, $toDate])->where('draftrequired', 'y');
            } else {
                $orders->whereBetween($dateStatus, [$fromDate, $toDate]);
            }
        } elseif ($fromDate && $toDate) {
            $orders->whereBetween('order_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $orders->whereDate('order_date', $fromDate);
        } elseif ($toDate) {
            $orders->whereDate('order_date', '<=', $toDate);
        } elseif ($dateStatus) {
            $orders->whereDate('order_date', Carbon::today());
        }

        if ($WriterTL) {
            $orders->where('orders.wid', $WriterTL);
        }

        if ($SubWriter) {
            $orderIds = multipleswiter::where('user_id', $SubWriter)->pluck('order_id')->toArray();
            $orders->whereIn('orders.id', $orderIds);
        }

        if ($college) {
            $orders->where('orders.college_name', $college);
        }

        if ($offer) {
            $orders->where('orders.offer', 'like', '%' . $offer . '%');
        }

        if ($duec) {
            if ($duec === 'due') {
                $orders->whereRaw('(amount - received_amount) > 0');
            } elseif ($duec === 'no due') {
                $orders->whereRaw('(amount - received_amount) <= 0');
            }
        }

        if ($marks_filter) {
            $orders->where('orders.marks', $marks_filter);
        }

        if ($deadline_status === 'overdue') {
            $now = now();
            $orders->where(function ($q) use ($now) {
                $q->where('delivery_date', '<', $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->where('delivery_date', '=', $now->toDateString())
                            ->whereNotNull('delivery_time')
                            ->where('delivery_time', '<', $now->toTimeString());
                    });
            })->whereNotIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered']);
        } elseif ($deadline_status === 'missed') {
            $orders->whereIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered'])
                ->whereColumn('updated_at', '>', 'delivery_date');
        }

        if ($today_deadline_filter) {
            $orders->whereDate('delivery_date', Carbon::today());
        }
        if ($yesterday_deadline_filter) {
            $orders->whereDate('delivery_date', Carbon::yesterday());
        }
        if ($today_writer_deadline_filter) {
            $orders->whereDate('writer_deadline', Carbon::today());
        }

        if ($extra) {
            if ($extra === 'tech') {
                $orders->where('tech', '1');
            } elseif ($extra === 'resit') {
                $orders->where('resit', 'on');
            } elseif ($extra === 'failedjob') {
                $orders->where('is_fail', '1');
            } elseif ($extra === '1') {
                $orders->where('services', 'First Class Work');
            }
        }

        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $filename = 'Orders_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens it with proper encoding
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Order Code', 'User Name', 'User Email', 'Mobile', 'Order Date',
                'Deadline', 'Delivery Time', 'Pages', 'Title', 'Status',
                'Amount', 'Paid', 'Due', 'Team', 'Writer Name',
                'SubWriter', 'Writer Deadline', 'Chapter', 'Draft Date', 'Draft Time'
            ]);

            if (ob_get_level() > 0) {
                @ob_flush();
            }
            @flush();

            $orders->orderBy('orders.id', 'desc')->chunk(500, function ($batchOrders) use ($handle) {
                foreach ($batchOrders as $order) {
                    $userObj = $order->user ?? $order->lead?->user ?? $order->frontendLead?->user;
                    $userName = $userObj->name ?? $order->lead?->user_name ?? '';
                    $userEmail = $userObj->email ?? $order->lead?->email ?? '';
                    $userMobile = $userObj->mobile_no ?? $order->lead?->mobile ?? '';

                    $subWriterNames = [];
                    if ($order->relationLoaded('mulsubwriter') && $order->mulsubwriter) {
                        foreach ($order->mulsubwriter as $msw) {
                            if (isset($msw->user) && !empty($msw->user->name)) {
                                $subWriterNames[] = $msw->user->name;
                            }
                        }
                    }

                    $amount = is_numeric($order->amount) ? (float)$order->amount : 0;
                    $received = is_numeric($order->received_amount) ? (float)$order->received_amount : 0;
                    $due = $amount - $received;

                    fputcsv($handle, [
                        $order->order_id,
                        $userName,
                        $userEmail,
                        $userMobile,
                        $order->order_date ?? '',
                        $order->delivery_date ?? '',
                        $order->delivery_time ?? '',
                        $order->pages ?? '',
                        $order->title ?? '',
                        $order->projectstatus ?? '',
                        number_format($amount, 2, '.', ''),
                        number_format($received, 2, '.', ''),
                        number_format($due, 2, '.', ''),
                        $order->team?->team_name ?? '',
                        $order->writer_name ?? '',
                        implode(', ', $subWriterNames),
                        $order->writer_deadline ?? '',
                        $order->chapter ?? '',
                        $order->draft_date ?? '',
                        $order->draft_time ?? ''
                    ]);
                }
                if (ob_get_level() > 0) {
                    @ob_flush();
                }
                @flush();
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function exportLeads(Request $request)
    {
        // Retrieve filter parameters from the request
        $searchTerm = $request->input('additional_filter1');
        $userDetail = $request->input('additional_filter2');
        $status = $request->input('additional_filter4');
        $techn = $request->input('additional_filter5');
        $fromDate = $request->input('additional_filter3');
        $uptoDate = $request->input('additional_filter6');
        $dateStatus = $request->input('additional_filter7');
    
        // Start query to retrieve leads
        $leadsQuery = Leads::with('user')->where('status', 0);
    
        // Apply filters if provided
        if ($searchTerm) {
            $searchUserIds = find_user_ids_by_search_term($searchTerm);
            $cleanSearchMasked = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $searchTerm));
            $cleanSearchDigits = preg_replace('/\D+/', '', $searchTerm);

            $leadsQuery->where(function ($query) use ($searchTerm, $searchUserIds, $cleanSearchMasked, $cleanSearchDigits) {
                $query->where('order_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('project_title', 'like', '%' . $searchTerm . '%');

                if (!empty($searchUserIds)) {
                    $query->orWhereIn('emp_id', $searchUserIds);
                }

                if (strpos($searchTerm, '*') !== false && !empty($cleanSearchMasked) && preg_match('/\d/', $cleanSearchMasked)) {
                    $query->orWhere('mobile', 'like', '%' . $cleanSearchMasked . '%')
                        ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile, '')) LIKE ?", ['%' . $cleanSearchMasked . '%']);
                }

                if (!empty($cleanSearchDigits) && strlen($cleanSearchDigits) >= 4) {
                    $last10 = strlen($cleanSearchDigits) >= 10 ? substr($cleanSearchDigits, -10) : $cleanSearchDigits;
                    $query->orWhere('mobile', 'like', '%' . $cleanSearchDigits . '%')
                        ->orWhere('mobile', 'like', '%' . $last10 . '%')
                        ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile, '')) LIKE ?", ['%' . $cleanSearchDigits . '%']);
                }

                if (strpos($searchTerm, '@') !== false) {
                    $cleanEmail = preg_replace('/\*+/', '%', $searchTerm);
                    $query->orWhere('email', 'like', '%' . $cleanEmail . '%');
                }
            });
        }
    
        if ($userDetail) {
            if (is_numeric($userDetail)) {
                $leadsQuery->where('emp_id', $userDetail);
            } else {
                $matchingUserIds = find_user_ids_by_search_term((string)$userDetail);
                $cleanUserMasked = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', (string)$userDetail));
                $cleanUserDigits = preg_replace('/\D+/', '', (string)$userDetail);

                $leadsQuery->where(function ($query) use ($userDetail, $matchingUserIds, $cleanUserMasked, $cleanUserDigits) {
                    if (!empty($matchingUserIds)) {
                        $query->whereIn('emp_id', $matchingUserIds);
                    }

                    if (strpos((string)$userDetail, '*') !== false && !empty($cleanUserMasked) && preg_match('/\d/', $cleanUserMasked)) {
                        $query->orWhere('mobile', 'like', '%' . $cleanUserMasked . '%')
                            ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile, '')) LIKE ?", ['%' . $cleanUserMasked . '%']);
                    }

                    if (!empty($cleanUserDigits) && strlen($cleanUserDigits) >= 4) {
                        $last10 = strlen($cleanUserDigits) >= 10 ? substr($cleanUserDigits, -10) : $cleanUserDigits;
                        $query->orWhere('mobile', 'like', '%' . $cleanUserDigits . '%')
                            ->orWhere('mobile', 'like', '%' . $last10 . '%')
                            ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile, '')) LIKE ?", ['%' . $cleanUserDigits . '%']);
                    }

                    if (strpos((string)$userDetail, '@') !== false) {
                        $cleanEmail = preg_replace('/\*+/', '%', (string)$userDetail);
                        $query->orWhere('email', 'like', '%' . $cleanEmail . '%');
                    } else {
                        $query->orWhere('user_name', 'like', '%' . $userDetail . '%')
                            ->orWhere('email', 'like', '%' . $userDetail . '%');
                    }
                });
            }
        }
    
        if ($status) {
            $leadsQuery->where('l_status', $status);
        }
    
        if ($techn) {
            if ($techn == 'Technical') {
                $leadsQuery->where('tech', 'on');
            } elseif ($techn == 'Resit') {
                $leadsQuery->where('resit', 'on');
            } elseif ($techn == 'First') {
                $leadsQuery->where('service_type', 'First Class Work');
            }
        }
    
        $dateField = ($dateStatus === 'Deadline') ? 'deadline' : 'create_at';
        if ($fromDate && $uptoDate) {
            if ($dateField === 'create_at') {
                $leadsQuery->whereBetween('create_at', [$fromDate . ' 00:00:00', $uptoDate . ' 23:59:59']);
            } else {
                $leadsQuery->whereBetween('deadline', [$fromDate, $uptoDate]);
            }
        } elseif ($fromDate) {
            $leadsQuery->whereDate($dateField, '>=', $fromDate);
        } elseif ($uptoDate) {
            $leadsQuery->whereDate($dateField, '<=', $uptoDate);
        }
    
        // Fetch the leads
        $leads = $leadsQuery->orderByDesc('id');
    
        // Check if filters are provided
        $filtersProvided = $searchTerm || $userDetail || $status || $techn || $fromDate || $uptoDate || $dateStatus;

        // Fetch leads based on whether filters are provided or not
        $leads = $filtersProvided ? $leadsQuery->get() : Leads::where('status', 0)->orderByDesc('id')->get();

        // Prepare CSV file content with headers
        
        $csvData = 'Order Code,User Name,User Email,Mobile,Order Date,Pages,Title,Delivery Date,Delivery Time,Draft Date,Draft Time' . PHP_EOL;
    
        // Generate CSV data
        
        foreach ($leads as $lead) {
            // Access user's name and email from the user relationship
            $userName = '"' . ($lead->user->name ?? '') . '"';
            $userEmail = '"' . ($lead->user->email ?? '') . '"';
            $userMobile = '"' . ($lead->user->mobile_no ?? '') . '"';
            // Enclose fields in double quotes to treat commas as a single block in Excel
            $order_id = '"' . $lead->order_id . '"';
            $title = '"' . $lead->project_title . '"';
            $pages = '"' . $lead->pages . '"';
            $amount = '"' . $lead->price . '"';
            $deliveryDate = '"' . $lead->deadline . '"';
            $deliveryTime = '"' . $lead->delivery_time . '"';
            $orderDate = '"' . $lead->create_at . '"';
            $draftDate = '"' . $lead->draft_date . '"';
            $draftTime = '"' . $lead->draft_time . '"';
        
            $csvData .= $order_id . ',' . $userName . ',' . $userEmail . ',' . $userMobile . ',' . $orderDate . ',' . $pages . ',' . $title . ','  . $deliveryDate . ',' . $deliveryTime . ',' . $draftDate . ',' . $draftTime .  PHP_EOL;
        }
    
        // Generate file name
        $filename = 'leads_' . now()->format('YmdHis') . '.csv';
    
        // Save CSV content to storage
        Storage::disk('local')->put($filename, $csvData);
    
        // Return the file path to the AJAX request
        return response()->download(storage_path('app/' . $filename), $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    private function getRoleName($roleId)
    {
        switch ($roleId) {
            case 1:
                return 'Super Admin';
            case 2:
                return 'User';
            case 3:
                return 'Sub Admin';
            case 4:
                return 'Marketing Team';
            case 5:
                return 'Project Team';
            case 6:
                return 'WriterTL';
            case 7:
                return 'Sub Writer';
            case 8:
                return 'Writer Admin';
            default:
                return 'Unknown';
        }
    }

    public function exportUsers(Request $request)
    {
        // Retrieve filter parameters from the request
        $searchUserId = $request->input('user_id') ?? $request->input('uid');
        $userText = trim((string) ($request->input('user') ?? $request->input('user_name') ?? $request->input('search') ?? ''));
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $roleId = $request->input('role');
        $countrycode = $request->input('countrycode');
        $orderFilter = $request->input('order_category');
        $collegeName = $request->input('college_name');
        $groupId = $request->input('group_id');
        $tab = $request->get('tab', 'all');

        $globalCountries = [
            'India' => ['+91', '91', '091'],
            'USA/Canada' => ['+1', '1'],
            'UK' => ['+44', '44'],
            'Australia' => ['+61', '61'],
            'UAE' => ['+971', '971'],
            'Pakistan' => ['+92', '92'],
            'Bangladesh' => ['+880', '880'],
            'Sri Lanka' => ['+94', '94'],
            'Nepal' => ['+977', '977'],
            'Azerbaijan' => ['+994', '994'],
            'Romania' => ['+40', '40'],
            'New Zealand' => ['+64', '64'],
            'Ghana' => ['+233', '233'],
            'Denmark' => ['+45', '45'],
            'Germany' => ['+49', '49'],
            'Nigeria' => ['+234', '234'],
            'Saudi Arabia' => ['+966', '966'],
            'South Africa' => ['+27', '27'],
            'Singapore' => ['+65', '65'],
            'Malaysia' => ['+60', '60'],
            'Ireland' => ['+353', '353'],
            'Oman' => ['+968', '968'],
            'Qatar' => ['+974', '974'],
            'Kuwait' => ['+965', '965'],
            'Bahrain' => ['+973', '973'],
            'France' => ['+33', '33'],
            'Italy' => ['+39', '39'],
            'Spain' => ['+34', '34'],
            'Netherlands' => ['+31', '31'],
            'Sweden' => ['+46', '46'],
            'Switzerland' => ['+41', '41'],
            'China' => ['+86', '86'],
            'Japan' => ['+81', '81'],
            'South Korea' => ['+82', '82'],
            'Russia' => ['+7', '7'],
            'Brazil' => ['+55', '55'],
            'Mexico' => ['+52', '52'],
            'Egypt' => ['+20', '20'],
            'Kenya' => ['+254', '254'],
            'Uganda' => ['+256', '256'],
            'Turkey' => ['+90', '90'],
            'Iran' => ['+98', '98'],
            'Ukraine' => ['+380', '380'],
            'Bulgaria' => ['+359', '359'],
            'Guinea' => ['+224', '224'],
            'Malta' => ['+356', '356'],
            'Liberia' => ['+231', '231'],
            'Mauritius' => ['+230', '230'],
            'Greece' => ['+30', '30'],
            'Austria' => ['+43', '43'],
            'Iraq' => ['+964', '964'],
            'Cameroon' => ['+237', '237'],
            'Albania' => ['+355', '355'],
            'Belgium' => ['+32', '32'],
            'Israel' => ['+972', '972'],
            'Zambia' => ['+260', '260'],
            'Latvia' => ['+371', '371']
        ];

        $allKnownCodes = [];
        foreach ($globalCountries as $codes) {
            $allKnownCodes = array_merge($allKnownCodes, $codes);
        }

        $oneAndHalfYearAgo = Carbon::now()->subMonths(18);

        // Initialize the user query
        $usersQuery = User::query()->where('flag', 0);

        // Basic Filters
        if ($searchUserId) {
            $usersQuery->where('id', $searchUserId);
        } elseif ($userText !== '') {
            $cleanUserText = preg_replace('/\D+/', '', $userText);
            $last10 = strlen($cleanUserText) >= 10 ? substr($cleanUserText, -10) : $cleanUserText;

            $usersQuery->where(function ($q) use ($userText, $cleanUserText, $last10) {
                $q->where('name', 'like', '%' . $userText . '%')
                  ->orWhere('email', 'like', '%' . $userText . '%')
                  ->orWhere('mobile_no', 'like', '%' . $userText . '%')
                  ->orWhere('mobile_no2', 'like', '%' . $userText . '%');
                if (!empty($cleanUserText)) {
                    $q->orWhere('mobile_no', 'like', '%' . $cleanUserText . '%')
                      ->orWhereRaw("CONCAT(COALESCE(countrycode, ''), COALESCE(mobile_no, '')) LIKE ?", ["%{$cleanUserText}%"]);
                }
                if (!empty($last10)) {
                    $q->orWhere('mobile_no', 'like', '%' . $last10 . '%');
                }
            });
        }

        if ($startDate && !$endDate) {
            $usersQuery->whereDate('created_at', $startDate);
        }

        if ($startDate && $endDate) {
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($roleId) {
            $usersQuery->where('role_id', $roleId);
        }

        if ($groupId) {
            $usersQuery->whereHas('groups', fn ($q) => $q->where('group_masters.id', $groupId));
        }

        if ($countrycode) {
            $escapedAllCodes = array_map(function ($c) {
                return str_replace('+', '\\\\+', $c);
            }, $allKnownCodes);
            $allCodesRegex = '^(' . implode('|', $escapedAllCodes) . ')';

            if ($countrycode === 'Other') {
                $usersQuery->where(function ($q) use ($allKnownCodes, $allCodesRegex) {
                    $q->where(function ($subQ) use ($allKnownCodes) {
                        $subQ->whereNotIn('countrycode', $allKnownCodes)
                            ->orWhereNull('countrycode')
                            ->orWhere('countrycode', '');
                    });
                    $q->where(function ($subQ) use ($allCodesRegex) {
                        $subQ->where('mobile_no', 'NOT REGEXP', $allCodesRegex)
                            ->orWhereNull('mobile_no')
                            ->orWhere('mobile_no', '');
                    });
                });
            } elseif (array_key_exists($countrycode, $globalCountries)) {
                $selectedCodes = $globalCountries[$countrycode];
                $usersQuery->whereIn('countrycode', $selectedCodes);
            }
        }

        if ($collegeName) {
            $usersQuery->whereHas('orders', function ($q) use ($collegeName) {
                $q->where('college_name', $collegeName);
            });
        }

        if ($orderFilter) {
            $usersQuery->whereHas(
                'orders',
                function ($q) use ($oneAndHalfYearAgo) {
                    $q->where('order_date', '>=', $oneAndHalfYearAgo);
                },
                ($orderFilter == 'loyal' ? '>' : ($orderFilter == 'repeated' ? '>=' : '<=')),
                ($orderFilter == 'loyal' ? 10 : ($orderFilter == 'repeated' ? 4 : 3))
            );
        }

        // Base customer requirement (matches UI tab rules: only users with an order or lead)
        if (!$searchUserId && $userText === '') {
            $usersQuery->where(function ($customerQuery) {
                $customerQuery->whereHas('orders')->orWhereHas('leads');
            });
        }

        // Tab selection filtering
        if ($tab === 'confirmed') {
            $usersQuery->whereHas('orders');
        } elseif ($tab === 'not_confirmed') {
            $usersQuery->whereHas('leads')->whereDoesntHave('orders');
        }

        // Retrieve filtered users data from the database
        $users = $usersQuery->orderBy('id', 'desc')->get();

        // Prepare CSV file content with headers
        $csvData = "Name,Email,CountryCode,MobileNo,CountryCode2,MobileNo2,Role,JoinDate" . PHP_EOL;

        // Generate CSV data
        foreach ($users as $user) {
            $csvData .= '"' . implode('","', [
                str_replace('"', '""', (string)$user->name),
                str_replace('"', '""', (string)$user->email),
                str_replace('"', '""', (string)$user->countrycode),
                str_replace('"', '""', (string)$user->mobile_no),
                str_replace('"', '""', (string)$user->countrycode2),
                str_replace('"', '""', (string)$user->mobile_no2),
                str_replace('"', '""', (string)$this->getRoleName($user->role_id)),
                $user->created_at ? $user->created_at->format('d M Y (h:i:s a)') : ''
            ]) . '"' . PHP_EOL;
        }

        // Generate file name
        $filename = 'users_' . now()->format('YmdHis') . '.csv';

        // Save CSV content to storage
        Storage::disk('local')->put($filename, $csvData);

        // Return the file as a download response
        return Response::download(storage_path('app/' . $filename), $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportWD(Request $request)
    {
        // Retrieve parameters from the request
        $tlId = $request->input('writerTL');
        $swId = $request->input('SubWriter');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        // Check if all request parameters are not available
        if (!$tlId && !$swId && !$fromDate && !$toDate) {
            // Return a response indicating that no parameters are available
            return response()->json(['message' => 'No parameters provided.'], 400);
        }
    
        // Initialize the query builder
        $query = Order::query()->with('writer', 'mulsubwriter')->where('admin_id', '!=', 0);
    
        // Check if tlId is "Not Assigned"
        if ($tlId === "Not Assigned") {
            // Fetch orders where 'wid' is null or empty
            $query->where(function ($query) {
                $query->whereNull('wid')
                    ->orWhere('wid', '');
            });
        } elseif ($tlId) {
            // Fetch orders for the selected TL
            $query->where('wid', $tlId);
        }
        if ($swId) {
            // Fetch orders for the selected SW
            $multipleWriters = multipleswiter::where('user_id', $swId)->get();
            $orderIds = $multipleWriters->pluck('order_id')->toArray();
            $query->whereIn('id', $orderIds);
        }
        // Apply date range filter if both from_date and to_date are provided
        if ($fromDate && $toDate) {
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('writer_fd', [$fromDate, $toDate])
                    ->orWhereBetween('writer_ud', [$fromDate, $toDate]);
            });
        }
    
        // Fetch orders with applied filters and order by created_at in descending order
        $orders = $query->orderByDesc('created_at')->get();
    
        $expandedOrders = [];
        $totalWordCount = 0; // Initialize total word count
        $totalWordCount = $orders->reduce(function ($carry, $order) {
            return $carry + (is_numeric($order->pages) ? $order->pages : 0);
        }, 0); // Initialize total word count
        $totalOrders = $orders->count(); // Count total number of orders
    
        // Iterate over each order
        foreach ($orders as $order) {
            // Parse the start and end dates, handling null, empty, or "0000-00-00" values
            $startDate = $order->writer_fd && $order->writer_fd !== '0000-00-00' ? Carbon::parse($order->writer_fd) : null;
            $endDate = $order->writer_ud && $order->writer_ud !== '0000-00-00' ? Carbon::parse($order->writer_ud) : null;
    
            // Initialize the variable to store SubWriter names
            $subWriterNames = [];
    
            // Retrieve SubWriter names
            foreach ($order->mulsubwriter as $mulsubwriter) {
                if ($mulsubwriter->user !== null) {
                    $subWriterNames[] = $mulsubwriter->user->name;
                }
            }
            // Retrieve Writer name
            if ($order->writer !== null) {
                $writerName = $order->writer->name;
            } else {
                $writerName = "";
            }
    
            // If start or end date is null or empty, set it to "Not Mentioned"
            if (!$startDate || !$endDate) {
                $expandedOrder = [
                    'order_id' => $order->order_id,
                    'date' => 'Not Mentioned',
                    'title' => $order->title ? $order->title : 'Not Mentioned', // Check if title is null or empty
                    'pages' => $order->pages ? $order->pages : 'Not Mentioned', // Check if pages is null or empty
                    'writer_name' => $writerName,
                    'sub_writer_names' => implode(', ', $subWriterNames),
                ];
    
                $expandedOrders[] = $expandedOrder;
                continue; // Skip further processing for this order
            }
    
            // If title is null or empty, set it to "Not Mentioned"
            $title = $order->title ? $order->title : 'Not Mentioned';
    
            // If pages is null or empty, set it to "Not Mentioned"
            $pages = $order->pages ? $order->pages : 'Not Mentioned';
    
            // $totalWordCount += is_numeric($pages) ? $pages : 0;
    
            // Generate records for each day within the date range
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Create a new record with the same order details but different date
                $expandedOrder = [
                    'order_id' => $order->order_id,
                    'date' => $date->toDateString(),
                    'title' => $title, // Use the title value set earlier
                    'pages' => $pages, // Use the pages value set earlier
                    'writer_name' => $writerName,
                    'sub_writer_names' => implode(', ', $subWriterNames),
                ];
    
                // Add the expanded order to the array
                $expandedOrders[] = $expandedOrder;
            }
        }
    
        // Sort expanded orders by date in ascending order
        usort($expandedOrders, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
    
        if ($fromDate && $toDate) {
            // Initialize an empty array to store filtered orders within the date range
            $filteredOrders = [];
    
            // Filter expanded orders based on the date range
            foreach ($expandedOrders as $expandedOrder) {
                if ($expandedOrder['date'] >= $fromDate && $expandedOrder['date'] <= $toDate) {
                    $filteredOrders[] = $expandedOrder;
                }
            }
    
            // Prepare CSV file content with headers
            $csvData = 'Total Orders,Total Word Count' . PHP_EOL;
    
            // Add the total orders and total word count in the first row
            $csvData .= $totalOrders . ',' . $totalWordCount . PHP_EOL;
    
            // Add headers for order details
            $csvData .= 'Order Code, Date, Title, Word, Writer, SubWriter' . PHP_EOL;
    
            // Generate CSV data for each order
            foreach ($filteredOrders as $order) {
                // Enclose fields in double quotes to treat commas as a single block in Excel
                $OrderCode = '"' . $order['order_id'] . '"';
                $Date = '"' . $order['date'] . '"';
                $Title = '"' . $order['title'] . '"';
                $Word = '"' . $order['pages'] . '"';
                $Writer = '"' . $order['writer_name'] . '"';
                $SubWriter = '"' . $order['sub_writer_names'] . '"';
    
                // Concatenate order details
                $csvData .= $OrderCode . ',' . $Date . ',' . $Title . ',' . $Word . ',' . $Writer . ',' . $SubWriter . PHP_EOL;
            }
        }
    
        // Prepare CSV file content with headers if both from_date and to_date are not available
        if (!$fromDate && !$toDate) {
            // Prepare CSV file content with headers
            $csvData = 'Total Orders,Total Word Count' . PHP_EOL;
    
            // Add the total orders and total word count in the first row
            $csvData .= $totalOrders . ',' . $totalWordCount . PHP_EOL;
    
            // Add headers for order details
            $csvData .= 'Order Code, Date, Title, Word, Writer, SubWriter' . PHP_EOL;
    
            // Generate CSV data for each order
            foreach ($expandedOrders as $order) {
                // Enclose fields in double quotes to treat commas as a single block in Excel
                $OrderCode = '"' . $order['order_id'] . '"';
                $Date = '"' . $order['date'] . '"';
                $Title = '"' . $order['title'] . '"';
                $Word = '"' . $order['pages'] . '"';
                $Writer = '"' . $order['writer_name'] . '"';
                $SubWriter = '"' . $order['sub_writer_names'] . '"';
    
                // Concatenate order details
                $csvData .= $OrderCode . ',' . $Date . ',' . $Title . ',' . $Word . ',' . $Writer . ',' . $SubWriter . PHP_EOL;
            }
        }
    
        // Generate file name
        $filename = 'Writer_orders_' . now()->format('YmdHis') . '.csv';
    
        // Save CSV content to storage
        Storage::disk('local')->put($filename, $csvData);
    
        // Return the file path to the AJAX request
        return response()->download(storage_path('app/' . $filename), $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }






}
