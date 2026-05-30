<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Bank;
use App\Models\LoginHistory;
use App\Models\UserLog;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // public function index(Request $request)
    //     {
    //         $searchUserId = $request->input('user_id');
    //         $startDate = $request->input('start_date');
    //         $endDate = $request->input('end_date');
    //         $roleId = $request->input('role');
    //         $countrycode = $request->input('countrycode'); 
    //         $orderFilter = $request->input('order_category');
    //         $perPage = 10;

    //         // 1. MASTER LIST (Duniya ki sari badi countries)
    //         $globalCountries = [
    //             'India' => ['+91', '91', '091'],
    //             'USA/Canada' => ['+1', '1'],
    //             'UK' => ['+44', '44'],
    //             'Australia' => ['+61', '61'],
    //             'UAE' => ['+971', '971'],
    //             'Pakistan' => ['+92', '92'],
    //             'Bangladesh' => ['+880', '880'],
    //             'Sri Lanka' => ['+94', '94'],
    //             'Nepal' => ['+977', '977'],
    //             'Azerbaijan' => ['+994', '994'],
    //             'Romania' => ['+40', '40'],
    //             'New Zealand' => ['+64', '64'],
    //             'Ghana' => ['+233', '233'],
    //             'Denmark' => ['+45', '45'],
    //             'Germany' => ['+49', '49'],
    //             'Nigeria' => ['+234', '234'],
    //             'Saudi Arabia' => ['+966', '966'],
    //             'South Africa' => ['+27', '27'],
    //             'Singapore' => ['+65', '65'],
    //             'Malaysia' => ['+60', '60'],
    //             'Ireland' => ['+353', '353'],
    //             'Oman' => ['+968', '968'],
    //             'Qatar' => ['+974', '974'],
    //             'Kuwait' => ['+965', '965'],
    //             'Bahrain' => ['+973', '973'],
    //             'France' => ['+33', '33'],
    //             'Italy' => ['+39', '39'],
    //             'Spain' => ['+34', '34'],
    //             'Netherlands' => ['+31', '31'],
    //             'Sweden' => ['+46', '46'],
    //             'Switzerland' => ['+41', '41'],
    //             'China' => ['+86', '86'],
    //             'Japan' => ['+81', '81'],
    //             'South Korea' => ['+82', '82'],
    //             'Russia' => ['+7', '7'],
    //             'Brazil' => ['+55', '55'],
    //             'Mexico' => ['+52', '52'],
    //             'Egypt' => ['+20', '20'],
    //             'Kenya' => ['+254', '254'],
    //             'Uganda' => ['+256', '256'],
    //             'Turkey' => ['+90', '90'],
    //             'Iran' => ['+98', '98'],
    //             'Ukraine' => ['+380', '380'],
    //             'Bulgaria' => ['+359', '359'],
    //             'Guinea' => ['+224', '224'],
    //             'Malta' => ['+356', '356'],
    //             'Liberia' => ['+231', '231'],
    //             'Mauritius' => ['+230', '230'],
    //             'Greece' => ['+30', '30'],
    //             'Austria' => ['+43', '43'],
    //             'Iraq' => ['+964', '964'],
    //             'Cameroon' => ['+237', '237'],
    //             'Albania' => ['+355', '355'],
    //             'Belgium' => ['+32', '32'],
    //             'Israel' => ['+972', '972'],
    //             'Zambia' => ['+260', '260'],
    //             'Latvia' => ['+371', '371']
    //         ];

    //         // "Other" option ke liye saare codes ek array me ikattha kar rahe hain
    //         $allKnownCodes = [];
    //         foreach ($globalCountries as $codes) {
    //             $allKnownCodes = array_merge($allKnownCodes, $codes);
    //         }

    //         $oneAndHalfYearAgo = \Carbon\Carbon::now()->subMonths(18);
    //         $oneYearAgo = \Carbon\Carbon::now()->subYear();

    //         $query = User::withCount([
    //             'orders' => function ($q) use ($oneAndHalfYearAgo) {
    //                 $q->where('order_date', '>=', $oneAndHalfYearAgo);
    //             },
    //             'followups' => function ($q) use ($oneYearAgo) {
    //                 $q->where('created_at', '>=', $oneYearAgo);
    //             }
    //         ])->where('flag', 0);

    //         // Basic Filters
    //         if ($searchUserId) { $query->where('id', $searchUserId); }
    //         if ($startDate && !$endDate) { $query->whereDate('created_at', $startDate); }
    //         if ($startDate && $endDate) { $query->whereBetween('created_at', [$startDate, $endDate]); }
    //         if ($roleId) { $query->where('role_id', $roleId); }

    //         // ==========================================
    //         // 2. ADVANCED COUNTRY CODE & MOBILE NO FILTER
    //         // ==========================================
    //         if ($countrycode) {
    //             // Regex pattern create karna taaki 50+ conditions ki jagah 1 hi query chale
    //             $escapedAllCodes = array_map(function($c) { return str_replace('+', '\\\\+', $c); }, $allKnownCodes);
    //             $allCodesRegex = '^(' . implode('|', $escapedAllCodes) . ')';

    //             if ($countrycode === 'Other') {
    //                 $query->where(function($q) use ($allKnownCodes, $allCodesRegex) {
    //                     // Condition A
    //                     $q->where(function($subQ) use ($allKnownCodes) {
    //                         $subQ->whereNotIn('countrycode', $allKnownCodes)
    //                             ->orWhereNull('countrycode')
    //                             ->orWhere('countrycode', '');
    //                     });

    //                     // Condition B: Loop hata kar 1 fast REGEXP query lagayi hai
    //                     $q->where(function($subQ) use ($allCodesRegex) {
    //                         $subQ->where('mobile_no', 'NOT REGEXP', $allCodesRegex)
    //                              ->orWhereNull('mobile_no')
    //                              ->orWhere('mobile_no', '');
    //                     });
    //                 });
    //             } 
    //             elseif (array_key_exists($countrycode, $globalCountries)) {
    //                 $selectedCodes = $globalCountries[$countrycode];

    //                 $escapedSelected = array_map(function($c) { return str_replace('+', '\\\\+', $c); }, $selectedCodes);
    //                 $selectedRegex = '^(' . implode('|', $escapedSelected) . ')';

    //                 $query->where(function($q) use ($selectedCodes, $selectedRegex) {
    //                     $q->whereIn('countrycode', $selectedCodes)
    //                       ->orWhere('mobile_no', 'REGEXP', $selectedRegex);
    //                 });
    //             }
    //         }
    //         // ==========================================

    //         if ($orderFilter) {
    //             $query->whereHas('orders', function($q) use ($oneAndHalfYearAgo) {
    //                 $q->where('order_date', '>=', $oneAndHalfYearAgo);
    //             }, ($orderFilter == 'loyal' ? '>' : ($orderFilter == 'repeated' ? '>=' : '<=')), 
    //             ($orderFilter == 'loyal' ? 10 : ($orderFilter == 'repeated' ? 4 : 3)));
    //         }

    //         $data['users'] = $query->orderBy('id', 'desc')->paginate($perPage);
    //         // OPTIMIZED: Pura User model load karne ki jagah sirf wahi fields load kiye jo dropdown me chahiye.
    //         $data['all_user'] = User::where('flag', 0)
    //                                 ->select('id', 'name', 'email', 'mobile_no', 'mobile_no2')
    //                                 ->orderBy('id', 'desc')
    //                                 ->get();

    //         $data['role'] = Role::all();
    //         $data['bank'] = Bank::all();

    //         // View ko list bhejna
    //         $data['countryList'] = array_keys($globalCountries); 

    //         return view('user.user_view', compact('data'));
    //     }

    public function index(Request $request)
    {
        $searchUserId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $roleId = $request->input('role');
        $countrycode = $request->input('countrycode');
        $orderFilter = $request->input('order_category');
        $collegeName = $request->input('college_name');
        $perPage = 10;

        // 1. MASTER LIST (Duniya ki sari badi countries)
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

        // "Other" option ke liye saare codes ek array me ikattha kar rahe hain
        $allKnownCodes = [];
        foreach ($globalCountries as $codes) {
            $allKnownCodes = array_merge($allKnownCodes, $codes);
        }

        $codeToCountry = [];
        foreach ($globalCountries as $countryName => $codes) {
            foreach ($codes as $code) {
                $cleanCode = ltrim($code, '+');
                $codeToCountry[$cleanCode] = $countryName;
            }
        }

        $oneAndHalfYearAgo = \Carbon\Carbon::now()->subMonths(18);
        $oneYearAgo = \Carbon\Carbon::now()->subYear();
        $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3);

        $query = User::withCount([
            'orders' => function ($q) use ($oneAndHalfYearAgo) {
                $q->where('order_date', '>=', $oneAndHalfYearAgo);
            },
            'followups' => function ($q) use ($oneYearAgo) {
                $q->where('created_at', '>=', $oneYearAgo);
            }
        ])->where('flag', 0);

        // Basic Filters
        if ($searchUserId) {
            $query->where('id', $searchUserId);
        }
        if ($startDate && !$endDate) {
            $query->whereDate('created_at', $startDate);
        }
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]); 
        }
        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        // ==========================================
        // ADVANCED COUNTRY CODE & MOBILE NO FILTER (OPTIMIZED REGEXP)
        // ==========================================
        if ($countrycode) {
            $escapedAllCodes = array_map(function ($c) {
                return str_replace('+', '\\\\+', $c);
            }, $allKnownCodes);
            $allCodesRegex = '^(' . implode('|', $escapedAllCodes) . ')';

            if ($countrycode === 'Other') {
                $query->where(function ($q) use ($allKnownCodes, $allCodesRegex) {
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
                $escapedSelected = array_map(function ($c) {
                    return str_replace('+', '\\\\+', $c);
                }, $selectedCodes);
                $selectedRegex = '^(' . implode('|', $escapedSelected) . ')';

                $query->where(function ($q) use ($selectedCodes, $selectedRegex) {
                    $q->whereIn('countrycode', $selectedCodes)
                        //   ->orWhere('mobile_no', 'REGEXP', $selectedRegex);
                        ->whereIn('countrycode', $selectedCodes);
                });
            }
        }

        if ($collegeName) {
    $query->whereHas('orders', function ($q) use ($collegeName) {
        $q->where('college_name', $collegeName);
    });
}

        if ($orderFilter) {
            $query->whereHas(
                'orders',
                function ($q) use ($oneAndHalfYearAgo) {
                    $q->where('order_date', '>=', $oneAndHalfYearAgo);
                },
                ($orderFilter == 'loyal' ? '>' : ($orderFilter == 'repeated' ? '>=' : '<=')),
                ($orderFilter == 'loyal' ? 10 : ($orderFilter == 'repeated' ? 4 : 3))
            );
        }

        // =======================================================
        // TABS COUNT LOGIC
        // =======================================================
        // $countAll = (clone $query)->count();
        $countAll = User::where('flag', 0)->count(); // Total users without any filters
        $countNew = (clone $query)->where('created_at', '>=', $oneYearAgo)->count();
        $countRetained = (clone $query)
            ->where('created_at', '<', $oneYearAgo)
            ->whereHas('orders', function ($q) use ($threeMonthsAgo) {
                $q->where('order_date', '>=', $threeMonthsAgo);
            })->count();

        // Loyal Customer: Jisne last 1.5 years me 10 se zyada order diye hon
        $countLoyal = (clone $query)->whereHas('orders', function ($q) use ($oneAndHalfYearAgo) {
            $q->where('order_date', '>=', $oneAndHalfYearAgo);
        }, '>', 10)->count();

        // APPLY TAB FILTER
        $tab = $request->get('tab', 'all');
        if ($tab === 'new') {
            $query->where('created_at', '>=', $oneYearAgo);
        } elseif ($tab === 'retained') {
            $query->where('created_at', '<', $oneYearAgo)
                ->whereHas('orders', function ($q) use ($threeMonthsAgo) {
                    $q->where('order_date', '>=', $threeMonthsAgo);
                });
        } elseif ($tab === 'loyal') {
            $query->whereHas('orders', function ($q) use ($oneAndHalfYearAgo) {
                $q->where('order_date', '>=', $oneAndHalfYearAgo);
            }, '>', 10);
        }
        // =======================================================

        $data['users'] = $query->orderBy('id', 'desc')->paginate($perPage);

        $data['all_user'] = User::where('flag', 0)
            ->select('id', 'name', 'email', 'mobile_no', 'mobile_no2')
            ->orderBy('id', 'desc')->get();
        $data['role'] = Role::all();
        $data['bank'] = Bank::all();
        $data['countryList'] = array_keys($globalCountries);
        $data['collegeList'] = Order::whereNotNull('college_name')->where('college_name', '!=', '')->distinct()->orderBy('college_name')->pluck('college_name');

        // Compacting tab variables for user_view
        return view('user.user_view', compact('data', 'countAll', 'countNew', 'countRetained', 'countLoyal', 'tab', 'codeToCountry'));
    }

    public function EditUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->countrycode = $request->input('countrycode');
            $user->mobile_no = $request->input('phone');
            $user->countrycode2 = $request->input('countrycode2');
            $user->mobile_no2 = $request->input('phone2');
            $user->role_id = $request->input('role');
            $user->bank_id = $request->input('bank');
            $user->address = $request->input('address');
            $user->call_id = $request->input('call_id');

            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
                $destinationPath = public_path('assets/media/avatars');
                $uploadedFile->move($destinationPath, $fileName);
                $user->photo = 'assets/media/avatars/' . $fileName;
            } else {
                $user->photo = 'assets/media/avatars/blank.png';
            }
            // dd($user);
            // Check if the provided old password is correct

            $user->sip = $request->input('sip');
            $user->save();

            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    public function new_user()
    {
        $data['role'] = Role::all();

        return view('user.add_user', compact('data'));
    }


    public function insert_new_user(Request $request)
    {
        // Validate input data, including a unique rule for email

        $existingUser = User::where('email', $request->input('email'))

            ->first();

        if ($existingUser) {
            // User already exists, show an alert or handle accordingly
            return redirect()->back()->with('error', 'User with the same email or phone number already exists.');
        }

        // Continue with user creation
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->countrycode = $request->input('country_code');
        $user->mobile_no = $request->input('primary_mobile');
        $user->countrycode2 = $request->input('country_code2');
        $user->mobile_no2 = $request->input('secondary_mobile');
        $user->role_id = $request->input('role');
        $user->address = $request->input('address');
        $user->password = Hash::make('user@123');

        if ($request->hasFile('photo')) {
            // Handle file upload
            $uploadedFile = $request->file('photo');
            $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $destinationPath = public_path('assets/media/avatars');
            $uploadedFile->move($destinationPath, $fileName);
            $user->photo = 'assets/media/avatars/' . $fileName;
        } else {
            $user->photo = 'assets/media/avatars/blank.png';
        }

        $user->save();

        return redirect('/user')->with('success', 'User created successfully. Users Login Password Is "user@123"');
    }

    public function followupuser(Request $request, $id)
    {
        $user = User::find($id);
        $followup = $request->input('followup'); // Get the follow-up value from the request

        // Toggle follow-up status
        $user->followup = $followup ? 1 : 0;
        $user->save();

        // Prepare response message
        $message = $followup ? 'User marked for follow-up' : 'User no longer marked for follow-up';

        return response()->json(['message' => $message]);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed, create session
            $request->session()->regenerate();

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function indexLog()
    {
        // Step 1: Get all session user_ids (currently logged-in users)
        $loggedInUserIds = DB::table('sessions')->pluck('user_id')->unique()->filter();

        $userRole = Role::all();

        // Step 2: Get users excluding role_id = 2
        $users = User::with('loginHistories')
            ->where('role_id', '!=', 2)
            ->orderByRaw("FIELD(id, " . $loggedInUserIds->implode(',') . ") DESC") // push logged-in to top
            ->latest()
            ->paginate(20);

        // Step 3: Fetch sessions for paginated users only
        $userIds = $users->pluck('id');
        $sessions = DB::table('sessions')->whereIn('user_id', $userIds)->get()->keyBy('user_id');

        return view('history.user.loginHistory', compact('users', 'sessions', 'userRole'));
    }


    public function forceLogout(User $user)
    {
        // Delete all sessions for this user (force logout)
        DB::table('sessions')->where('user_id', $user->id)->delete();


        $loginHistory = LoginHistory::where('user_id', $user->id)
            ->whereColumn('created_at', 'updated_at') // means no logout yet
            ->latest('created_at')
            ->first();

        if ($loginHistory) {
            // Update logout time
            $loginHistory->update([
                'updated_at' => now(),
                // 'type' => 'logout', // optional, if you want to mark explicitly
            ]);
        }

        return back()->with('success', 'User has been logged out.');
    }

    public function userHistory($userId)
    {
        $user = User::with([
            'loginHistories' => function ($query) {
                $query->orderBy('id', 'desc');
            }
        ])->findOrFail($userId);

        return view('history.user.history', compact('user'));
    }

    public function filterLoginHistory(Request $request)
    {
        $loggedInUserIds = DB::table('sessions')->pluck('user_id')->unique()->filter();

        $query = User::with('loginHistories');
        $userRole = Role::all();

        // Conditionally apply role filter
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id); // allow role_id = 2 here
        } else {
            $query->where('role_id', '!=', 2); // default case, exclude role_id = 2
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Date filter
        if ($request->filled('from') && $request->filled('to')) {
            $from = \Carbon\Carbon::parse($request->from)->startOfDay();
            $to = \Carbon\Carbon::parse($request->to)->endOfDay();
            $query->whereHas('loginHistories', fn($q) => $q->whereBetween('created_at', [$from, $to]));
        } elseif ($request->filled('from')) {
            $date = \Carbon\Carbon::parse($request->from)->toDateString();
            $query->whereHas('loginHistories', fn($q) => $q->whereDate('created_at', $date));
        }

        $users = $query
            ->orderByRaw("FIELD(id, " . $loggedInUserIds->implode(',') . ") DESC")
            ->latest()
            ->paginate(20)
            ->appends(request()->query());

        $userIds = $users->pluck('id');
        $sessions = DB::table('sessions')->whereIn('user_id', $userIds)->get()->keyBy('user_id');

        return view('history.user.userList', compact('users', 'sessions', 'userRole'))->render();
    }

    public function updateTime(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $today = date('Y-m-d');
            $seconds = $request->seconds;

            // Aaj ka record update karein ya naya banayein
            \DB::table('user_working_times')->updateOrInsert(
                ['user_id' => $userId, 'date' => $today], // Match conditions
                [
                    'active_seconds' => $seconds,
                    'updated_at' => now()
                ]
            );

            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function adminWorkingReport(Request $request)
    {
        $today = $request->input('date', date('Y-m-d'));

        $query = User::where('role_id', '!=', 2);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(15);

        // Aaj ke sessions ek baar mein fetch karein
        $loggedInUserIds = \DB::table('sessions')->pluck('user_id')->toArray();

        foreach ($users as $user) {
            $workingData = \DB::table('user_working_times')
                ->where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            $firstLogin = \DB::table('login_histories')
                ->where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->oldest()
                ->first();

            $user->today_active_seconds = $workingData->active_seconds ?? 0;
            $user->first_login_at = $firstLogin->created_at ?? null;
            $user->is_online = in_array($user->id, $loggedInUserIds);
        }

        return view('back-end.working_time', compact('users', 'today'));
    }

    public function saveClientBehaviour(Request $request)
    {
        if (!$request->filled('user_id') || !$request->filled('client_review')) {
            return response()->json([
                'success' => false,
                'message' => 'User ID and behaviour are required'
            ], 422);
        }

        DB::table('client_behaviours')->insert([
            'uid' => $request->user_id,
            'behaviour' => $request->client_review,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Behaviour saved successfully'
        ]);
    }

    public function latestClientBehaviour($userId)
    {
        $behaviour = DB::table('client_behaviours')
            ->where('uid', $userId)
            ->latest('id')
            ->first();

        return response()->json([
            'success' => true,
            'behaviour' => $behaviour
        ]);
    }

    // public function saveMarks(Request $request) 
    // {
    //     $user = \App\Models\User::find($request->user_id);

    //     if ($user) {
    //         $user->marks = $request->marks;
    //         $user->save();

    //         return response()->json(['success' => true]);
    //     }

    //     return response()->json(['success' => false]);
    // }

    public function showUserProfile($id) // Ya aapka jo bhi function name ho
    {
        $user = User::withCount('orders')->findOrFail($id);

        // Revenue (received_amount ka sum)
        $totalRevenue = \App\Models\Order::where('uid', $id)->sum('received_amount');

        // Tickets Count
        $totalTickets = \App\Models\Order::where('uid', $id)
            ->whereNotNull('feedback_ticket')
            ->where('feedback_ticket', '!=', '')
            ->count();

        $solvedTickets = \App\Models\Order::where('uid', $id)
            ->whereNotNull('feedback_ticket')
            ->where('feedback_ticket', '!=', '')
            ->where('status_issue', 'Resolved') // Ise apne DB ke actual status se match kar lena
            ->count();

        $pendingTickets = $totalTickets - $solvedTickets;

        // YAHAN DHYAN DEIN: File ka naam userreporting kar diya gaya hai
        return view('user.userreporting', compact('user', 'totalRevenue', 'totalTickets', 'solvedTickets', 'pendingTickets'));
    }

    // public function userReportList(Request $request)
    // {
    //     // 1. Apni Master List
    //     $globalCountries = [
    //         'India' => ['+91', '91', '091'], 'USA/Canada' => ['+1', '1'], 'UK' => ['+44', '44'], 
    //         'Australia' => ['+61', '61'], 'UAE' => ['+971', '971'], 'Pakistan' => ['+92', '92'], 
    //         'Bangladesh' => ['+880', '880'], 'Sri Lanka' => ['+94', '94'], 'Nepal' => ['+977', '977'], 
    //         // ... (Apni saari countries yahan add rakhein) ...
    //     ];

    //     $codeToCountry = [];
    //     foreach ($globalCountries as $countryName => $codes) {
    //         foreach ($codes as $code) {
    //             $codeToCountry[$code] = $countryName;
    //         }
    //     }

    //     // 2. Base Query
    //     $baseQuery = \App\Models\User::where('role_id', 2);

    //     // Filters (Search, Country, Date)
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $baseQuery->where(function($q) use ($search) {
    //             $q->where('name', 'LIKE', "%{$search}%")
    //               ->orWhere('email', 'LIKE', "%{$search}%")
    //               ->orWhere('mobile_no', 'LIKE', "%{$search}%");
    //         });
    //     }

    //     if ($request->filled('country')) {
    //         $selectedCountry = $request->country;
    //         if ($selectedCountry === 'Other') {
    //             $allCodes = array_keys($codeToCountry);
    //             $baseQuery->where(function($q) use ($allCodes) {
    //                 $q->whereNotIn('countrycode', $allCodes)
    //                   ->orWhereNull('countrycode')
    //                   ->orWhere('countrycode', '');
    //             });
    //         } elseif (isset($globalCountries[$selectedCountry])) {
    //             $baseQuery->whereIn('countrycode', $globalCountries[$selectedCountry]);
    //         }
    //     }

    //     if ($request->filled('from_date')) {
    //         $baseQuery->whereDate('created_at', '>=', $request->from_date);
    //     }
    //     if ($request->filled('to_date')) {
    //         $baseQuery->whereDate('created_at', '<=', $request->to_date);
    //     }

    //     // ================== TABS COUNT LOGIC ==================
    //     $oneYearAgo = now()->subYear();
    //     $threeMonthsAgo = now()->subMonths(3);

    //     $countAll = (clone $baseQuery)->count();

    //     $countNew = (clone $baseQuery)->where('created_at', '>=', $oneYearAgo)->count();

    //     $countRetained = (clone $baseQuery)
    //         ->where('created_at', '<', $oneYearAgo)
    //         ->whereHas('orders', function($q) use ($threeMonthsAgo) {
    //             $q->where('order_date', '>=', $threeMonthsAgo);
    //         })->count();

    //     // ================== APPLY TAB FILTER ==================
    //     $tab = $request->get('tab', 'all');
    //     if ($tab === 'new') {
    //         $baseQuery->where('created_at', '>=', $oneYearAgo);
    //     } elseif ($tab === 'retained') {
    //         $baseQuery->where('created_at', '<', $oneYearAgo)
    //                   ->whereHas('orders', function($q) use ($threeMonthsAgo) {
    //                       $q->where('order_date', '>=', $threeMonthsAgo);
    //                   });
    //     }

    //     // ================== RELATIONSHIPS & SORTING ==================
    //     $query = $baseQuery
    //         ->withCount('orders') 
    //         ->withSum('orders as total_revenue', 'received_amount') 
    //         ->withCount(['orders as total_tickets' => function ($q) {
    //             $q->whereNotNull('feedback_ticket')->where('feedback_ticket', '!=', '');
    //         }])
    //         ->withCount(['orders as solved_tickets' => function ($q) {
    //             $q->whereNotNull('feedback_ticket')->where('feedback_ticket', '!=', '')
    //               // YAHAN TABLE KA NAAM feddbacksheet FIX KAR DIYA GAYA HAI
    //               ->join('feddbacksheet', 'orders.order_id', '=', 'feddbacksheet.order_id') 
    //               ->where('feddbacksheet.status', 'Case Resolved'); 
    //         }]);

    //     $sortBy = $request->get('sort_by', 'id'); 
    //     $sortOrder = $request->get('order', 'desc'); 

    //     if (in_array($sortBy, ['orders_count', 'total_revenue'])) {
    //         $query->orderBy($sortBy, $sortOrder);
    //     } else {
    //         $query->orderBy('id', 'desc');
    //     }

    //     $users = $query->paginate(20)->appends($request->all());
    //     $countryList = array_keys($globalCountries);

    //     return view('user.report_list', compact('users', 'codeToCountry', 'countryList', 'countAll', 'countNew', 'countRetained', 'tab'));
    // }

public function userReportList(Request $request)
{
    // =========================
    // 1. Country Mapping
    // =========================

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
    ];

    $codeToCountry = [];

    foreach ($globalCountries as $countryName => $codes) {
        foreach ($codes as $code) {
            $codeToCountry[$code] = $countryName;
        }
    }

    // =========================
    // 2. Base Query
    // =========================

    $query = \App\Models\User::where('role_id', 2)
        ->has('orders');

    // =========================
    // 3. Filters
    // =========================

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile_no', 'LIKE', "%{$search}%");
        });
    }

    // Country Filter
    if ($request->filled('country')) {

        $selectedCountry = $request->country;

        if ($selectedCountry === 'Other') {

            $allCodes = array_keys($codeToCountry);

            $query->where(function ($q) use ($allCodes) {

                $q->whereNotIn('countrycode', $allCodes)
                    ->orWhereNull('countrycode')
                    ->orWhere('countrycode', '');
            });

        } elseif (isset($globalCountries[$selectedCountry])) {

            $query->whereIn('countrycode', $globalCountries[$selectedCountry]);
        }
    }

    // Date Filter
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // =========================
    // 4. GET FILTERED USER IDS
    // =========================

    $filteredUserIds = (clone $query)->pluck('id');

    // =========================
    // 5. GRAND TOTALS
    // =========================

    // Total Orders
    $totalOrders = \App\Models\Order::whereIn('uid', $filteredUserIds)
        ->count();

    // Total Revenue
    $totalRevenue = \App\Models\Order::whereIn('uid', $filteredUserIds)
        ->sum('received_amount');

    // Total Tickets
    $totalTickets = \App\Models\Order::whereIn('uid', $filteredUserIds)
        ->whereNotNull('feedback_ticket')
        ->where('feedback_ticket', '!=', '')
        ->count();

    // Total Failed Orders
    $totalFailedOrders = \App\Models\Order::whereIn('uid', $filteredUserIds)
        ->where('is_fail', 1)
        ->count();

    $totalTicketsAndFailed = $totalTickets + $totalFailedOrders;    

    // =========================
    // 6. USER TABLE COUNTS
    // =========================

    $query->withCount('orders')
        ->withSum('orders as total_revenue', 'received_amount')
        ->withCount([
            'orders as total_tickets' => function ($q) {
                $q->whereNotNull('feedback_ticket')
                    ->where('feedback_ticket', '!=', '');
            }
        ])
        ->withCount([
            'orders as failed_orders_count' => function ($q) {
                $q->where('is_fail', 1);
            }
        ]);

    // =========================
    // 7. Sorting
    // =========================

    $allowedSorts = [
        'orders_count',
        'total_revenue',
        'total_tickets',
        'failed_orders_count',
        'tickets_failed_total'
    ];

    $sortBy = in_array($request->get('sort_by'), $allowedSorts)
        ? $request->get('sort_by')
        : 'orders_count';

    $sortOrder = in_array($request->get('order'), ['asc', 'desc'])
        ? $request->get('order')
        : 'desc';

    if ($sortBy == 'tickets_failed_total') {
        $query->orderByRaw(
            '(COALESCE(total_tickets,0) + COALESCE(failed_orders_count,0)) ' . $sortOrder
        );
    } else {
        $query->orderBy($sortBy, $sortOrder);
    }

    // =========================
    // 8. Pagination
    // =========================

    // $perPage = $request->get('per_page', 20);
    // $users = $query->orderBy($sortBy, $sortOrder)
    //     ->paginate($perPage)
    //     ->appends($request->all());
    $perPage = $request->get('per_page', 20);

    $users = $query
        ->paginate($perPage)
        ->appends($request->all());

    // =========================
    // 9. Country List
    // =========================

    $countryList = array_keys($globalCountries);

    // =========================
    // 10. Return View
    // =========================

    return view('user.report_list', compact(
        'users',
        'codeToCountry',
        'countryList',
        'totalOrders',
        'totalRevenue',
        'totalTickets',
        'totalFailedOrders',
        'totalTicketsAndFailed'
    ));
}

    public function userLogs()
    {
        $logs = UserLog::orderBy('id', 'desc')->paginate(20);
        return view('user.userlogs', compact('logs'));
    }

 public function referUserReport(Request $request)
{
    $sort = $request->get('sort', 'desc');

    $users = User::where('role_id', 2)
        ->whereHas('referredUsers')
        ->withCount(['referredUsers as refer_count'])
        ->with(['referredUsers' => function ($q) {
            $q->select('id', 'name', 'email', 'mobile_no', 'refer_id', 'created_at')
              ->orderByDesc('id');
        }])
        ->orderBy('refer_count', $sort == 'asc' ? 'asc' : 'desc')
        ->paginate(20)
        ->appends($request->query());

    return view('user.refer-user-report', compact('users', 'sort'));
}


}
