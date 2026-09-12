<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Leads;
use App\Models\Services;
use App\Models\Paper;
use App\Models\Source;
use App\Models\WhatsappChatArchive;
use App\Models\WhatsappChatPin;
use App\Models\WhatsappChatState;
use App\Models\WhatsappChatContactLabel;
use App\Models\WhatsappChatLabel;
use App\Models\WhatsappChatPanelSetting;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WhatsappController extends Controller
{
    private array $providers = ['ai-sense', 'wati', 'twilio', 'interakt'];

    public function settings(): View
    {
        $settings = WhatsappSetting::query()->get()->keyBy('provider');
        $activeSetting = $settings->firstWhere('is_active', true);
        $activeProvider = old('provider', $activeSetting?->provider ?? 'twilio');
        $providerSettings = $settings->mapWithKeys(function (WhatsappSetting $setting) {
            return [$setting->provider => $setting->settings ?? []];
        })->toArray();

        return view('back-end.whatsapp.settings', [
            'activeProvider' => $activeProvider,
            'providerSettings' => $providerSettings,
            'webhookUrl' => url('/api/webhooks/whatsapp'),
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'provider' => ['required', 'in:' . implode(',', $this->providers)],
            'settings' => ['nullable', 'array'],
        ]);

        $provider = $validated['provider'];
        $settings = $request->input("settings.{$provider}", []);
        $settings['webhook_url'] = url('/api/webhooks/whatsapp');

        WhatsappSetting::query()->update(['is_active' => false]);

        WhatsappSetting::query()->updateOrCreate(
            ['provider' => $provider],
            [
                'settings' => $this->cleanSettings($settings),
                'is_active' => true,
                'updated_by' => Auth::id(),
                'created_by' => Auth::id(),
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'WhatsApp settings saved successfully.',
            ]);
        }

        return back()->with('success', 'WhatsApp settings saved successfully.');
    }

    public function chat(Request $request): View
    {
        if ($request->has('close')) {
            session()->forget('wab_active_phone');
            $activePhone = null;
        } else {
            $activePhone = $request->query('phone') ?: session('wab_active_phone');
            if ($activePhone) {
                session(['wab_active_phone' => $activePhone]);
            }
        }

        if ($activePhone) {
            $this->markPhoneMessagesRead($activePhone);
        }

        $contactData = $this->getContactsPaginated($activePhone, 25, 1);
        $contacts = $contactData['contacts'];
        $selectedContact = collect($contacts)->firstWhere('active', true);

        // If activePhone was given but not in top 25 recent, fetch it directly
        if ($activePhone && ! $selectedContact) {
            $singleContactData = $this->getContactsPaginated($activePhone, 1, 1, $activePhone);
            if (! empty($singleContactData['contacts'])) {
                $selectedContact = $singleContactData['contacts'][0];
                $selectedContact['active'] = true;
                array_unshift($contacts, $selectedContact);
            }
        }

        $selectedPhone = $selectedContact['phone'] ?? $activePhone;

        $panelDefinitions = $this->chatPanelDefinitions();
        $enabledPanelKeys = $this->enabledPanelKeys(Auth::id(), array_keys($panelDefinitions));
        $selectedPanel = $request->query('panel');
        $selectedPanel = in_array($selectedPanel, $enabledPanelKeys, true) ? $selectedPanel : null;
        $panelRows = $selectedPanel ? $this->panelRows($selectedPanel) : collect();
        $labels = WhatsappChatLabel::query()->forWhatsapp()->ordered()->get();
        $selectedContactLabels = $selectedPhone
            ? WhatsappChatContactLabel::query()->where('phone', $selectedPhone)->pluck('label_id')->all()
            : [];

        // Load contact-label assignments for loaded sidebar contacts
        $allPhones = collect($contacts)->pluck('phone')->filter()->values()->all();
        $allContactVariants = [];
        foreach ($allPhones as $p) {
            foreach ($this->getPhoneVariants($p) as $v) {
                $allContactVariants[$v] = $p;
            }
        }
        $allContactLabelMap = !empty($allContactVariants)
            ? WhatsappChatContactLabel::query()
                ->whereIn('phone', array_keys($allContactVariants))
                ->get()
                ->groupBy(function ($item) use ($allContactVariants) {
                    return $allContactVariants[$item->phone] ?? $item->phone;
                })
                ->map(fn($rows) => $rows->pluck('label_id')->unique()->values()->all())
            : collect();

        $selectedPhoneVariants = $selectedPhone ? $this->getPhoneVariants($selectedPhone) : [];
        $messages = !empty($selectedPhoneVariants)
            ? WhatsappMessage::query()
                ->whereIn('phone', $selectedPhoneVariants)
                ->where(function ($query) {
                    $query->whereRaw("TRIM(COALESCE(message, '')) != ''")
                        ->orWhereNotNull('media_url');
                })
                ->orderByDesc('id')
                ->take(30)
                ->get()
                ->reverse()
                ->values()
            : collect();

        $firstMsgId = optional($messages->first())->id ?? 0;
        $hasMoreOlderMessages = (!empty($selectedPhoneVariants) && $firstMsgId > 0)
            ? WhatsappMessage::query()
                ->whereIn('phone', $selectedPhoneVariants)
                ->where('id', '<', $firstMsgId)
                ->where(function ($query) {
                    $query->whereRaw("TRIM(COALESCE(message, '')) != ''")
                        ->orWhereNotNull('media_url');
                })
                ->exists()
            : false;

        $customerSummary = $selectedPhone ? $this->getCustomerSummary($selectedPhone) : null;
        $existingLead = $customerSummary['lead_model'] ?? null;
        $existingUser = $customerSummary['user_model'] ?? null;

        $servicesList = Services::all();
        $papersList = Paper::all();
        $sourcesList = Source::all();
        $whatsappTemplates = $this->getAvailableTemplates();

        return view('back-end.whatsapp.chat', [
            'contacts' => $contacts,
            'dynamicContacts' => $contacts,
            'contactsHasMore' => $contactData['has_more'],
            'contactsTotal' => $contactData['total'],
            'selectedContact' => $selectedContact,
            'selectedPhone' => $selectedPhone,
            'messages' => $messages,
            'panelDefinitions' => $panelDefinitions,
            'enabledPanelKeys' => $enabledPanelKeys,
            'selectedPanel' => $selectedPanel,
            'panelRows' => $panelRows,
            'labels' => $labels,
            'selectedContactLabels' => $selectedContactLabels,
            'allContactLabelMap' => $allContactLabelMap,
            'customerSummary' => $customerSummary,
            'existingLead' => $existingLead,
            'existingUser' => $existingUser,
            'hasMoreOlderMessages' => $hasMoreOlderMessages,
            'servicesList' => $servicesList,
            'papersList' => $papersList,
            'sourcesList' => $sourcesList,
            'whatsappTemplates' => $whatsappTemplates,
        ]);
    }

    public function closeChatSession(): JsonResponse
    {
        session()->forget('wab_active_phone');

        return response()->json([
            'success' => true,
            'message' => 'Chat session closed successfully.',
        ]);
    }

    public function createLeadFromChat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'countrycode' => 'required',
            'mobile' => 'required',
            'lead_source' => 'required',
        ], [
            'countrycode.required' => 'Country Code is required.',
            'mobile.required' => 'Mobile number is required.',
            'lead_source.required' => 'Lead Source is required.',
        ]);

        $today = Carbon::today();
        $deliveryDate = $request->input('delivery_date');
        if ($deliveryDate && Carbon::parse($deliveryDate)->lt($today)) {
            return back()->with('error', 'Delivery date cannot be before today.')->withInput();
        }

        $mobile = preg_replace('/\D+/', '', (string) $request->input('mobile'));
        $countrycode = $request->input('countrycode');
        $fullPhone = $countrycode . $mobile;

        // User lookup or creation
        $user = null;
        if ($request->filled('id')) {
            $user = User::where('id', $request->input('id'))->first();
        }
        if (!$user && !empty($mobile)) {
            $user = User::where('mobile_no', $mobile)
                ->orWhere('mobile_no', $fullPhone)
                ->orWhere('mobile_no', '+' . $fullPhone)
                ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) = ?", [$mobile])
                ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile_no, '')) = ?", [$fullPhone])
                ->first();
        }

        $rawEmail = (string) $request->input('email');
        $hasRealEmail = $request->filled('email') && strpos($rawEmail, '*') === false && filter_var($rawEmail, FILTER_VALIDATE_EMAIL);

        if (!$user) {
            if ($hasRealEmail) {
                $existingUser = User::where('email', $rawEmail)->first();
                if ($existingUser) {
                    if ($existingUser->mobile_no == $mobile || $existingUser->mobile_no == $fullPhone) {
                        $user = $existingUser;
                    } else {
                        return back()->withInput()->with('error', 'Email already exists with another account.');
                    }
                }
            }

            if (!$user) {
                $user = new User();
                $user->email = $hasRealEmail ? $rawEmail : ('user' . $mobile . '@gmail.com');
                $user->mobile_no = $mobile;
                $user->name = $request->input('user_name') ?: ('WhatsApp User ' . $mobile);
                $user->countrycode = $countrycode ?: '44';
                $user->password = Hash::make('user@123');
                $user->role_id = 2;
                $user->refer_id = $request->refer_id ?? null;
                $user->save();
            }
        } else {
            if ($hasRealEmail) {
                $user->email = $rawEmail;
            }
            if ($request->filled('user_name')) {
                $user->name = $request->input('user_name');
            }
            if (!empty($countrycode)) {
                $user->countrycode = $countrycode;
            }
            $user->save();
        }

        $userId = $user->id;

        // Generate next UKS order id
        $latestOrder = Order::orderByDesc('id')->first();
        $newOrderNumber = $latestOrder ? intval(substr($latestOrder->order_id, 3)) : 0;
        $newOrderNumber++;
        $newOrderId = 'UKS' . $newOrderNumber;

        $creatorId = Auth::id() ?: 1;

        // Create Lead
        $lead = new Leads();
        $lead->order_id = $newOrderId;
        $lead->emp_id = $userId;
        $lead->user_name = $user->name;
        $lead->mobile = $mobile;
        $lead->countrycode = $countrycode;
        $lead->email = $user->email;
        $lead->project_title = $request->input('project_title') ?: 'WhatsApp Chat Inquiry';
        $lead->module_code = $request->input('module_code');
        $lead->pages = is_numeric($request->input('pages')) ? $request->input('pages') : 0;
        $lead->deadline = $deliveryDate ?: now()->addDays(3)->toDateString();
        $lead->delivery_time = $request->input('delivery_time') ?: '18:00';
        $lead->price = is_numeric($request->input('amount')) ? $request->input('amount') : 0;
        $lead->l_status = $request->input('i_status') ?: 'Waiting';
        $lead->message = $request->input('message') ?: 'Created directly from WhatsApp Chat conversation';
        $lead->service_type = $request->input('service_type');
        $lead->typeofpaper = $request->input('paper');
        $lead->tech = $request->filled('tech') ? 'on' : 'off';
        $lead->resit = $request->filled('resit') ? 'on' : 'off';
        $lead->chapter = in_array($lead->typeofpaper, ['Dissertation', 'Thesis']) ? $request->input('chapter') : null;
        $lead->semester = $request->input('semester');
        $lead->lead_source = $request->input('lead_source') ?: 'WhatsApp';
        $lead->created_by = $creatorId;
        $lead->create_at = now();
        $lead->save();

        // Create Order
        $order = new Order();
        $order->uid = $userId;
        $order->order_id = $newOrderId;
        $order->lead_id = $lead->id;
        $order->created_by = $creatorId;
        $order->title = $lead->project_title;
        $order->pages = $lead->pages;
        $order->amount = $lead->price;
        $order->projectstatus = 'Pending';
        $order->order_date = now();
        $order->delivery_date = $lead->deadline;
        $order->delivery_time = $lead->delivery_time;
        $order->service_type = $lead->service_type;
        $order->typeofpaper = $lead->typeofpaper;
        $order->chapter = $lead->chapter;
        $order->tech = $lead->tech;
        $order->resit = $lead->resit;
        $order->message = $lead->message;
        $order->save();

        return redirect()->route('whatsapp.chat', ['phone' => $request->input('return_phone') ?: $mobile])
            ->with('success', "New Lead #{$newOrderId} successfully created for {$user->name}!");
    }

    public function contactList(Request $request): JsonResponse
    {
        $activePhone = $request->query('active_phone');
        $page = max(1, (int) $request->query('page', 1));
        $limit = max(1, min(100, (int) $request->query('limit', 25)));
        $search = trim((string) $request->query('search', ''));
        $labelId = $request->query('label_id');
        $tab = $request->query('tab');

        $data = $this->getContactsPaginated($activePhone, $limit, $page, $search, $labelId, $tab);

        return response()->json([
            'success' => true,
            'contacts' => $data['contacts'],
            'total' => $data['total'],
            'has_more' => $data['has_more'],
            'page' => $page,
        ]);
    }

    public function messages(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'after_id' => ['nullable', 'integer', 'min:0'],
            'before_id' => ['nullable', 'integer', 'min:0'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'with_summary' => ['nullable', 'boolean'],
        ]);

        $phone = $validated['phone'];
        if (!empty($phone)) {
            session(['wab_active_phone' => $phone]);
        }
        $variants = $this->getPhoneVariants($phone);
        $afterId = (int) ($validated['after_id'] ?? 0);
        $beforeId = (int) ($validated['before_id'] ?? 0);
        $limit = max(1, min(50, (int) ($validated['limit'] ?? 30)));

        $query = WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where(function ($q) {
                $q->whereRaw("TRIM(COALESCE(message, '')) != ''")
                    ->orWhereNotNull('media_url');
            });

        // 1. Fetch older messages before before_id (scroll up pagination)
        if ($beforeId > 0) {
            $olderMessages = (clone $query)->where('id', '<', $beforeId)
                ->orderByDesc('id')
                ->take($limit)
                ->get()
                ->reverse()
                ->values();

            $oldestId = optional($olderMessages->first())->id ?? 0;
            $hasMoreOlder = $oldestId > 0
                ? (clone $query)->where('id', '<', $oldestId)->exists()
                : false;

            return response()->json([
                'messages' => $olderMessages->map(fn (WhatsappMessage $m) => $this->messagePayload($m))->values(),
                'has_more_older' => $hasMoreOlder,
                'first_id' => $oldestId,
                'is_older' => true,
            ]);
        }

        // 2. Fetch new real-time messages after after_id
        if ($afterId > 0) {
            $messages = (clone $query)->where('id', '>', $afterId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();
            $hasMoreOlder = false;
        } else {
            // 3. Initial load of latest messages (batch 30)
            $messages = (clone $query)->orderByDesc('id')
                ->take($limit)
                ->get()
                ->reverse()
                ->values();

            $firstMsgId = optional($messages->first())->id ?? 0;
            $hasMoreOlder = $firstMsgId > 0
                ? (clone $query)->where('id', '<', $firstMsgId)->exists()
                : false;
        }

        $this->markPhoneMessagesRead($phone);

        $response = [
            'messages' => $messages->map(fn (WhatsappMessage $message) => $this->messagePayload($message))->values(),
            'statuses' => $this->recentOutboundStatuses($phone),
            'typing' => Cache::has($this->typingCacheKey($phone)),
            'has_more_older' => $hasMoreOlder,
            'first_id' => optional($messages->first())->id ?? 0,
            'last_id' => optional($messages->last())->id ?? 0,
            'contacts' => $this->getContacts($phone),
        ];

        // Customer details for dynamic header switching
        if ($request->boolean('with_summary', true) && $afterId === 0) {
            $response['customer'] = $this->getCustomerSummary($phone);
        }

        return response()->json($response);
    }

    public function customerLeads(Request $request): JsonResponse
    {
        $phone = (string) $request->input('phone');
        $page = max(1, (int) $request->input('page', 1));
        $limit = max(1, min(50, (int) $request->input('limit', 10)));

        if (! $phone) {
            return response()->json(['success' => false, 'leads' => [], 'total' => 0, 'has_more' => false]);
        }

        $variants = $this->getPhoneVariants($phone);
        $cleanPhone = preg_replace('/\D+/', '', $phone);
        $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

        $users = User::query()
            ->where(function ($q) use ($variants, $cleanPhone, $last10) {
                $q->whereIn('mobile_no', $variants);
                if (!empty($cleanPhone)) {
                    $q->orWhere('mobile_no', 'like', "%{$cleanPhone}%");
                }
                if (!empty($last10)) {
                    $q->orWhere('mobile_no', 'like', "%{$last10}");
                }
            })
            ->get(['id', 'email', 'name', 'mobile_no']);

        $userIds = $users->pluck('id')->filter()->all();
        $userEmails = $users->pluck('email')->filter()->all();

        $query = Leads::query()
            ->where(function ($q) use ($phone, $cleanPhone, $last10, $variants, $userIds, $userEmails) {
                $q->whereIn('mobile', $variants)
                  ->orWhere('mobile', $phone)
                  ->orWhere('mobile', $cleanPhone);
                if (!empty($last10)) {
                    $q->orWhere('mobile', 'like', "%{$last10}");
                }
                if (!empty($variants)) {
                    $q->orWhereIn('mobile2', $variants);
                }
                if (!empty($userIds)) {
                    $q->orWhereIn('emp_id', $userIds);
                }
                if (!empty($userEmails)) {
                    $q->orWhereIn('email', $userEmails);
                }
            })
            ->where(function ($q) {
                $q->where('is_converted', 0)
                  ->orWhereNull('is_converted');
            })
            ->orderByDesc('id');

        $total = $query->count();
        $leads = $query->skip(($page - 1) * $limit)->take($limit)->get();

        $formatted = $leads->map(function ($lead) {
            $isCancelled = (int)$lead->status === 1 || in_array(strtolower($lead->l_status ?? ''), ['cancel', 'cancelled', 'canceled']);
            $isConverted = (int)$lead->is_converted === 1 || strtolower($lead->l_status ?? '') === 'converted';

            if ($isCancelled) {
                $displayStatus = 'Cancelled';
                $statusClass = 'badge-danger';
            } elseif ($isConverted) {
                $displayStatus = 'Converted';
                $statusClass = 'badge-success';
            } else {
                $displayStatus = $lead->l_status ?: 'Waiting';
                $statusClass = match(strtolower($lead->l_status ?? '')) {
                    'waiting' => 'badge-warning',
                    'quote' => 'badge-info',
                    'confirmation' => 'badge-primary',
                    default => 'badge-secondary',
                };
            }

            $createDateStr = !empty($lead->create_date) && strtotime($lead->create_date)
                ? date('d M Y', strtotime($lead->create_date))
                : (!empty($lead->create_at) && strtotime($lead->create_at) ? date('d M Y', strtotime($lead->create_at)) : (!empty($lead->created_at) ? $lead->created_at->format('d M Y') : '—'));

            return [
                'id' => $lead->id,
                'order_id' => $lead->order_id ?? (string) $lead->id,
                'project_title' => $lead->project_title ?: 'N/A',
                'service_type' => $lead->service_type ?: 'General',
                'pages' => $lead->pages ? number_format($lead->pages) : '—',
                'price' => is_numeric($lead->price) ? (float)$lead->price : 0,
                'price_formatted' => number_format((float)($lead->price ?: 0), 2),
                'status' => $displayStatus,
                'status_class' => $statusClass,
                'is_cancelled' => $isCancelled ? 1 : 0,
                'is_converted' => $isConverted ? 1 : 0,
                'cancel_reason' => $lead->cancel_reason ?: ($lead->reason ?: ''),
                'create_date' => $createDateStr,
                'deadline' => !empty($lead->deadline) && strtotime($lead->deadline) ? date('d M Y', strtotime($lead->deadline)) : '—',
                'delivery_time' => $lead->delivery_time ?: '',
                'edit_url' => route('lead.edit', $lead->id),
            ];
        });

        return response()->json([
            'success' => true,
            'leads' => $formatted,
            'total' => $total,
            'has_more' => ($page * $limit) < $total,
            'page' => $page,
            'all_leads_url' => route('leads') . '?search=' . urlencode($cleanPhone),
        ]);
    }

    public function customerOrders(Request $request): JsonResponse
    {
        $phone = (string) $request->input('phone');
        $page = max(1, (int) $request->input('page', 1));
        $limit = max(1, min(50, (int) $request->input('limit', 10)));

        if (! $phone) {
            return response()->json(['success' => false, 'orders' => [], 'total' => 0, 'has_more' => false]);
        }

        $variants = $this->getPhoneVariants($phone);
        $cleanPhone = preg_replace('/\D+/', '', $phone);
        $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

        $users = User::query()
            ->where(function ($q) use ($variants, $cleanPhone, $last10) {
                $q->whereIn('mobile_no', $variants);
                if (!empty($cleanPhone)) {
                    $q->orWhere('mobile_no', 'like', "%{$cleanPhone}%");
                }
                if (!empty($last10)) {
                    $q->orWhere('mobile_no', 'like', "%{$last10}");
                }
            })
            ->get(['id', 'email', 'name', 'mobile_no']);

        $userIds = $users->pluck('id')->filter()->all();
        $userEmails = $users->pluck('email')->filter()->all();

        $matchingLeads = Leads::query()
            ->where(function ($q) use ($phone, $cleanPhone, $last10, $variants, $userIds, $userEmails) {
                $q->whereIn('mobile', $variants)
                  ->orWhere('mobile', $phone)
                  ->orWhere('mobile', $cleanPhone);
                if (!empty($last10)) {
                    $q->orWhere('mobile', 'like', "%{$last10}");
                }
                if (!empty($variants)) {
                    $q->orWhereIn('mobile2', $variants);
                }
                if (!empty($userIds)) {
                    $q->orWhereIn('emp_id', $userIds);
                }
                if (!empty($userEmails)) {
                    $q->orWhereIn('email', $userEmails);
                }
            })
            ->get(['id', 'order_id', 'emp_id', 'is_converted']);

        $convertedLeadIds = $matchingLeads->where('is_converted', 1)->pluck('id')->filter()->all();
        $convertedLeadOrderIds = $matchingLeads->where('is_converted', 1)->pluck('order_id')->filter()->all();

        $query = Order::query()
            ->with(['team', 'lead', 'frontendLead'])
            ->whereNotNull('orders.uid')
            ->where('orders.uid', '!=', 0)
            ->where('orders.uid', '!=', '')
            ->where(function ($q) use ($userIds, $convertedLeadIds, $convertedLeadOrderIds) {
                if (!empty($userIds)) {
                    $q->whereIn('orders.uid', $userIds)
                      ->where(function ($sub) {
                          $sub->whereDoesntHave('lead')->whereDoesntHave('frontendLead');
                      });
                }
                if (!empty($convertedLeadIds)) {
                    $q->orWhereIn('orders.lead_id', $convertedLeadIds);
                }
                if (!empty($convertedLeadOrderIds)) {
                    $q->orWhereIn('orders.order_id', $convertedLeadOrderIds);
                }
            })
            ->orderByDesc('id');

        $hasMatchCriteria = !empty($userIds) || !empty($convertedLeadIds) || !empty($convertedLeadOrderIds);
        $total = $hasMatchCriteria ? $query->count() : 0;
        $orders = $hasMatchCriteria ? $query->skip(($page - 1) * $limit)->take($limit)->get() : collect();

        $formatted = $orders->map(function ($ord) {
            $statusClass = match(strtolower($ord->projectstatus ?? '')) {
                'completed', 'delivered' => 'badge-success',
                'working', 'in progress' => 'badge-warning',
                'failed', 'cancelled' => 'badge-danger',
                default => 'badge-primary',
            };

            $effectiveTitle = $ord->title ?: (optional($ord->lead)->project_title ?: (optional($ord->frontendLead)->project_title ?: 'N/A'));
            $effectivePages = $ord->pages ?: (optional($ord->lead)->pages ?: optional($ord->frontendLead)->pages);
            $effectiveAmount = $ord->amount ?: (optional($ord->lead)->price ?: optional($ord->frontendLead)->price);
            $effectiveDeliveryDate = $ord->delivery_date ?: (optional($ord->lead)->deadline ?: optional($ord->frontendLead)->deadline);
            $effectiveDeliveryTime = $ord->delivery_time ?: (optional($ord->lead)->delivery_time ?: optional($ord->frontendLead)->delivery_time);
            $effectiveOrderDate = $ord->order_date ?: (optional($ord->lead)->created_at ?: (optional($ord->lead)->create_at ?: $ord->created_at));
            $effectiveSemester = $ord->semester ?: (optional($ord->lead)->semester ?: optional($ord->frontendLead)->semester);
            $effectiveServices = $ord->services ?: (optional($ord->lead)->service_type ?: optional($ord->frontendLead)->service_type);

            $basePriceAmt = is_numeric($effectiveAmount) ? (float)$effectiveAmount : 0;
            $recvPriceAmt = is_numeric($ord->received_amount) ? (float)$ord->received_amount : 0;
            $calcDueAmt = max(0, $basePriceAmt - $recvPriceAmt);

            $deadlineDate = null;
            if (!empty($effectiveDeliveryDate)) {
                $dateTimeString = $effectiveDeliveryDate;
                if (!empty($effectiveDeliveryTime)) {
                    $dateTimeString .= ' ' . $effectiveDeliveryTime;
                }
                try {
                    $deadlineDate = Carbon::parse($dateTimeString);
                } catch (\Exception $e) {
                    $deadlineDate = null;
                }
            }
            $isOverdue = $deadlineDate && $deadlineDate->isPast() && !in_array(strtolower($ord->projectstatus ?? ''), ['delivered', 'completed']);

            $orderDateStr = !empty($effectiveOrderDate) && strtotime((string)$effectiveOrderDate)
                ? Carbon::parse($effectiveOrderDate)->format('d M Y')
                : (!empty($ord->created_at) ? $ord->created_at->format('d M Y') : '—');

            $writerDeadlineStr = !empty($ord->writer_deadline) && strtotime($ord->writer_deadline)
                ? Carbon::parse($ord->writer_deadline)->format('d M Y')
                : null;

            $draftDateStr = null;
            if ($ord->draftrequired == 'Y' || !empty($ord->draft_date)) {
                if (!empty($ord->draft_date) && strtotime($ord->draft_date)) {
                    $draftDateStr = Carbon::parse($ord->draft_date)->format('d M Y');
                    if (!empty($ord->draft_time)) {
                        try {
                            $draftDateStr .= ' (' . Carbon::parse($ord->draft_time)->format('H:i') . ')';
                        } catch (\Exception $e) {
                            $draftDateStr .= ' (' . $ord->draft_time . ')';
                        }
                    }
                }
            }

            $deliveryDateFormatted = '—';
            if ($deadlineDate) {
                $deliveryDateFormatted = $deadlineDate->format('d M Y');
                if (!empty($effectiveDeliveryTime)) {
                    try {
                        $deliveryDateFormatted .= ' (' . Carbon::parse($effectiveDeliveryTime)->format('H:i') . ')';
                    } catch (\Exception $e) {
                        $deliveryDateFormatted .= ' (' . $effectiveDeliveryTime . ')';
                    }
                }
            } elseif (!empty($effectiveDeliveryDate) && strtotime((string)$effectiveDeliveryDate)) {
                $deliveryDateFormatted = date('d M Y', strtotime((string)$effectiveDeliveryDate));
            }

            $feedbackDateStr = !empty($ord->f_delivery_date) && strtotime($ord->f_delivery_date)
                ? Carbon::parse($ord->f_delivery_date)->format('d M Y')
                : null;

            $failedDateStr = !empty($ord->failed_at) && strtotime($ord->failed_at)
                ? Carbon::parse($ord->failed_at)->format('d M Y H:i A')
                : null;

            $convertedBy = $ord->l_converted_by ?: (optional($ord->lead)->l_converted_by ?: optional($ord->frontendLead)->l_converted_by);
            $isConverted = !empty($convertedBy);

            return [
                'id' => $ord->id,
                'order_id' => $ord->order_id ?: (string) $ord->id,
                'title' => $effectiveTitle,
                'service_type' => $effectiveServices ?: ($ord->service_type ?: 'General'),
                'pages' => $effectivePages ? number_format((float)$effectivePages) : '—',
                'total_amount' => $basePriceAmt,
                'total_amount_formatted' => number_format($basePriceAmt, 2),
                'received_amount' => $recvPriceAmt,
                'received_amount_formatted' => number_format($recvPriceAmt, 2),
                'due_amount' => $calcDueAmt,
                'due_amount_formatted' => number_format($calcDueAmt, 2),
                'status' => $ord->projectstatus ?: 'Pending',
                'status_class' => $statusClass,
                'order_date' => $orderDateStr,
                'writer_deadline' => $writerDeadlineStr,
                'draft_date' => $draftDateStr,
                'delivery_date' => $deliveryDateFormatted,
                'f_delivery_date' => $feedbackDateStr,
                'is_overdue' => $isOverdue,
                'is_fail' => (int) ($ord->is_fail ?? 0),
                'failed_at' => $failedDateStr,
                'feedback_ticket' => $ord->feedback_ticket ?? null,
                'resit' => $ord->resit ?? null,
                'services' => $effectiveServices ?? null,
                'semester' => $effectiveSemester ?? null,
                'offer' => $ord->offer ?? null,
                'marks' => $ord->marks ?? null,
                'team_name' => $ord->team?->team_name ?? null,
                'looking_for_refund' => (int) ($ord->looking_for_refund ?? 0),
                'is_converted' => $isConverted ? 1 : 0,
                'converted_by' => $convertedBy,
                'lead_id' => $ord->lead_id ?: (optional($ord->lead)->id ?: optional($ord->frontendLead)->id),
                'edit_url' => route('edit', $ord->id),
                'payment_url' => route('orders.payment.form', $ord->id),
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $formatted,
            'total' => $total,
            'has_more' => ($page * $limit) < $total,
            'page' => $page,
            'all_orders_url' => route('orders.index') . '?search=' . urlencode($cleanPhone),
        ]);
    }

    public function markRead(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $updated = $this->markPhoneMessagesRead($validated['phone']);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'contacts' => $this->getContacts($validated['phone']),
        ]);
    }

    public function markUnread(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $variants = $this->getPhoneVariants($validated['phone']);

        $updated = WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where('direction', 'inbound')
            ->latest('id')
            ->limit(1)
            ->update(['status' => 'unread']);

        if (! $updated) {
            $latest = WhatsappMessage::query()
                ->whereIn('phone', $variants)
                ->latest('id')
                ->first();

            if ($latest) {
                $latest->update(['status' => 'unread']);
                $updated = 1;
            }
        }

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'contacts' => $this->getContacts($validated['phone']),
        ]);
    }

    public function toggleArchive(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $phone = $validated['phone'];
        $variants = $this->getPhoneVariants($phone);

        $existing = WhatsappChatArchive::query()
            ->whereIn('phone', $variants)
            ->first();

        if ($existing) {
            WhatsappChatArchive::query()->whereIn('phone', $variants)->delete();
            $isArchived = false;
            $message = 'Chat unarchived successfully.';
        } else {
            WhatsappChatArchive::query()->firstOrCreate(['phone' => $phone]);
            $isArchived = true;
            $message = 'Chat archived successfully.';
        }

        return response()->json([
            'success' => true,
            'is_archived' => $isArchived,
            'phone' => $phone,
            'message' => $message,
        ]);
    }

    public function togglePin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $phone = $validated['phone'];
        $variants = $this->getPhoneVariants($phone);
        $userId = Auth::id();

        $existing = WhatsappChatPin::query()
            ->whereIn('phone', $variants)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->first();

        if ($existing) {
            WhatsappChatPin::query()
                ->whereIn('phone', $variants)
                ->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)->orWhereNull('user_id');
                })
                ->delete();

            return response()->json([
                'success' => true,
                'is_pinned' => false,
                'phone' => $phone,
                'message' => 'Chat unpinned successfully.',
            ]);
        }

        // Enforce limit of max 3 pinned chats
        $currentPinCount = WhatsappChatPin::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->count();

        if ($currentPinCount >= 3) {
            return response()->json([
                'success' => false,
                'is_pinned' => false,
                'phone' => $phone,
                'message' => 'You can only pin up to 3 chats.',
            ], 422);
        }

        WhatsappChatPin::query()->create([
            'phone' => $phone,
            'user_id' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'is_pinned' => true,
            'phone' => $phone,
            'message' => 'Chat pinned to top.',
        ]);
    }

    public function saveChatPanelSettings(Request $request): RedirectResponse
    {
        $definitions = $this->chatPanelDefinitions();
        $enabled = collect($request->input('panels', []))
            ->filter()
            ->keys()
            ->intersect(array_keys($definitions))
            ->values()
            ->all();

        foreach ($definitions as $key => $definition) {
            WhatsappChatPanelSetting::query()->updateOrCreate(
                ['user_id' => Auth::id(), 'panel_key' => $key],
                ['is_enabled' => in_array($key, $enabled, true)]
            );
        }

        return back()->with('success', 'WhatsApp chat settings saved.');
    }

    public function storeChatLabel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        WhatsappChatLabel::query()->firstOrCreate(
            ['name' => trim($validated['name'])],
            [
                'color' => $validated['color'],
                'created_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('whatsapp.chat', array_filter(['phone' => $request->input('phone')]))
            ->with('success', 'WhatsApp label created.');
    }

    public function saveContactLabels(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:50'],
            'user_id' => ['nullable', 'integer'],
            'email' => ['nullable', 'string', 'max:150'],
            'labels' => ['nullable'],
            'label_ids' => ['nullable'],
        ]);

        $userId = $request->input('user_id');
        $user = $userId ? User::find($userId) : null;
        $phone = $validated['phone'] ?? ($user ? ($user->mobile_no ?: '') : '');
        $email = $validated['email'] ?? ($user ? ($user->email ?: '') : '');

        $rawLabels = $request->input('label_ids') ?? $request->input('labels', []);
        $labelIds = [];
        if (is_array($rawLabels)) {
            $isAssoc = array_keys($rawLabels) !== range(0, count($rawLabels) - 1);
            if ($isAssoc) {
                $labelIds = collect($rawLabels)->filter()->keys()->map(fn ($id) => (int) $id)->values()->all();
            } else {
                $labelIds = collect($rawLabels)->map(fn ($id) => (int) $id)->values()->all();
            }
        }

        if (!empty($phone)) {
            $variants = $this->getPhoneVariants($phone);
            WhatsappChatContactLabel::query()->whereIn('phone', $variants)->delete();

            foreach ($labelIds as $labelId) {
                WhatsappChatContactLabel::query()->create([
                    'phone' => $phone,
                    'label_id' => $labelId,
                    'assigned_by' => Auth::id(),
                ]);
            }

            // Cross-channel sync: automatically apply these labels to associated Email threads
            try {
                app(\App\Services\LabelSyncService::class)->syncWhatsAppToEmail($phone, $labelIds, Auth::id());
            } catch (\Throwable $e) {
                \Log::warning('Failed to sync WhatsApp labels to Email: ' . $e->getMessage());
            }
        }

        if (!empty($email)) {
            $cleanEmail = strtolower(trim($email));
            $emailEligibleLabelIds = WhatsappChatLabel::whereIn('id', $labelIds)
                ->where('is_email', true)
                ->pluck('id')
                ->all();

            $threadIds = \App\Models\EmailMessage::query()
                ->where(function ($q) use ($cleanEmail) {
                    $q->where('from_email', $cleanEmail)
                      ->orWhere('to_email', 'like', "%{$cleanEmail}%");
                })
                ->whereNotNull('thread_id')
                ->pluck('thread_id')
                ->unique()
                ->values()
                ->all();

            if (!empty($threadIds)) {
                foreach ($threadIds as $tId) {
                    \App\Models\EmailThreadLabel::where('thread_id', $tId)->delete();
                    foreach ($emailEligibleLabelIds as $lId) {
                        \App\Models\EmailThreadLabel::create([
                            'thread_id' => $tId,
                            'email' => $cleanEmail,
                            'label_id' => (int) $lId,
                            'assigned_by' => Auth::id(),
                        ]);
                    }
                }
            } else {
                \App\Models\EmailThreadLabel::where('email', $cleanEmail)->delete();
                foreach ($emailEligibleLabelIds as $lId) {
                    \App\Models\EmailThreadLabel::create([
                        'thread_id' => null,
                        'email' => $cleanEmail,
                        'label_id' => (int) $lId,
                        'assigned_by' => Auth::id(),
                    ]);
                }
            }
        }

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $assignedLabels = WhatsappChatLabel::query()->whereIn('id', $labelIds)->ordered()->get(['id', 'name', 'color']);
            return response()->json([
                'success' => true,
                'message' => 'Labels saved successfully.',
                'user_id' => $userId,
                'phone' => $phone,
                'email' => $email,
                'label_ids' => $labelIds,
                'labels' => $assignedLabels,
            ]);
        }

        return redirect()->back()->with('success', 'Labels saved successfully.');
    }

    public function startChat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_code' => ['required', 'string', 'max:8'],
            'mobile' => ['required', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $phone = $this->normalizePhone($validated['country_code'], $validated['mobile']);

        if (! empty($validated['message'])) {
            $message = $this->storeOutboundMessage($phone, $validated['message']);
            $sendResult = $this->sendViaActiveProvider($message);

            if (! $sendResult['success']) {
                return redirect()
                    ->route('whatsapp.chat', ['phone' => $phone])
                    ->with('error', $sendResult['error']);
            }
        } else {
            WhatsappMessage::query()->create([
                'phone' => $phone,
                'name' => $phone,
                'message' => '',
                'direction' => 'outbound',
                'status' => 'draft',
            ]);
        }

        return redirect()->route('whatsapp.chat', ['phone' => $phone]);
    }

    public function sendMessage(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $hasRecentInbound = WhatsappMessage::query()
            ->whereIn('phone', $this->getPhoneVariants($validated['phone']))
            ->where('direction', 'inbound')
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        if (! $hasRecentInbound) {
            $error = 'The 24-hour WhatsApp window has expired. Send an approved template and wait for the customer to reply.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $error], 422);
            }

            return redirect()
                ->route('whatsapp.chat', ['phone' => $validated['phone']])
                ->with('error', $error);
        }

        $message = $this->storeOutboundMessage($validated['phone'], $validated['message']);
        $sendResult = $this->sendViaActiveProvider($message);
        $message->refresh();

        if ($request->expectsJson()) {
            $payload = [
                'success' => $sendResult['success'],
                'message' => $this->messagePayload($message),
                'contacts' => $this->getContacts($validated['phone']),
            ];

            if (! $sendResult['success']) {
                $payload['error'] = $sendResult['error'];

                return response()->json($payload, 422);
            }

            return response()->json([
                'success' => true,
                'message' => $this->messagePayload($message),
                'contacts' => $this->getContacts($validated['phone']),
            ]);
        }

        if (! $sendResult['success']) {
            return redirect()
                ->route('whatsapp.chat', ['phone' => $validated['phone']])
                ->with('error', $sendResult['error']);
        }

        return redirect()->route('whatsapp.chat', ['phone' => $validated['phone']]);
    }

    private function getAvailableTemplates()
    {
        try {
            $aisensy = $this->activeAiSensyConfiguration();
        } catch (\RuntimeException $exception) {
            Log::warning('WhatsApp templates unavailable', ['error' => $exception->getMessage()]);

            return collect();
        }

        $projectId = $aisensy['project_id'];
        $apiKey = $aisensy['api_key'];

        // Fetch live approved templates directly from AiSensy Project API (auto-syncs on approval)
        try {
            $cacheKey = 'aisensy_wa_templates_' . $projectId;
            $apiTemplates = Cache::remember($cacheKey, 60, function () use ($projectId, $apiKey) {
                $url = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/";
                $res = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-AiSensy-Project-API-Pwd' => $apiKey,
                ])->timeout(8)->get($url);

                if ($res->successful()) {
                    $rawList = $res->json()['template'] ?? [];
                    return collect($rawList)
                        ->where('status', 'APPROVED')
                        ->map(function ($t) {
                            $rawText = $t['text'] ?? $t['sample_text'] ?? '';
                            // Strip button annotations like | [Buy NOW,...] from body preview
                            $cleanBody = trim(preg_replace('/\|\s*\[[^\]]+\]/', '', $rawText));
                            $langStr = $t['language'] ?? 'English (UK)';
                            $langCode = (stripos($langStr, 'UK') !== false || stripos($langStr, 'GB') !== false) 
                                ? 'en_GB' 
                                : ((stripos($langStr, 'US') !== false) ? 'en_US' : 'en_GB');

                            $buttons = [];
                            if (!empty($t['quick_replies']) && is_array($t['quick_replies'])) {
                                $buttons = array_values($t['quick_replies']);
                            } elseif (!empty($t['buttons']) && is_array($t['buttons'])) {
                                $buttons = array_map(fn($b) => is_array($b) ? ($b['text'] ?? $b['title'] ?? '') : (string)$b, $t['buttons']);
                            } elseif (preg_match_all('/\|\s*\[([^\]]+)\]/', $rawText, $btnMatches)) {
                                $buttons = array_map(fn($b) => explode(',', $b)[0], $btnMatches[1]);
                            }

                            $variables = [];
                            if (preg_match_all('/\{\{(\d+)\}\}/', $cleanBody, $vMatches)) {
                                foreach (array_unique($vMatches[1]) as $varNum) {
                                    $variables[$varNum] = 'Variable ' . $varNum;
                                }
                            }

                            return [
                                'id' => $t['id'] ?? $t['name'],
                                'name' => $t['name'],
                                'title' => ucwords(str_replace('_', ' ', $t['name'])),
                                'category' => $t['category'] ?? 'UTILITY',
                                'language' => $langCode,
                                'body' => $cleanBody,
                                'footer_text' => 'Assignment In Need Team',
                                'buttons' => $buttons,
                                'variables' => $variables,
                                'status' => 'APPROVED',
                                'is_active' => true,
                            ];
                        })
                        ->values()
                        ->all();
                }
                return [];
            });

            if (!empty($apiTemplates) && is_array($apiTemplates)) {
                return collect($apiTemplates)->map(fn($t) => (object) $t);
            }
        } catch (\Throwable $e) {
            Log::warning('AiSensy wa_template fetch error: ' . $e->getMessage());
        }

        return collect();
    }

    public function getTemplates(Request $request): JsonResponse
    {
        if ($request->query('refresh')) {
            try {
                $aisensy = $this->activeAiSensyConfiguration();
                Cache::forget('aisensy_wa_templates_' . $aisensy['project_id']);
            } catch (\RuntimeException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'templates' => [],
                ], 422);
            }
        }

        $templates = $this->getAvailableTemplates()
            ->map(function ($t) {
                return [
                    'id' => is_object($t) ? $t->id : ($t['id'] ?? 1),
                    'name' => is_object($t) ? $t->name : ($t['name'] ?? ''),
                    'title' => (is_object($t) ? ($t->title ?: $t->name) : ($t['title'] ?? $t['name'] ?? '')),
                    'category' => is_object($t) ? ($t->category ?? 'UTILITY') : ($t['category'] ?? 'UTILITY'),
                    'language' => is_object($t) ? ($t->language ?? 'en_GB') : ($t['language'] ?? 'en_GB'),
                    'body' => is_object($t) ? $t->body : ($t['body'] ?? ''),
                    'footer_text' => is_object($t) ? ($t->footer_text ?? '') : ($t['footer_text'] ?? ''),
                    'buttons' => is_object($t) ? ($t->buttons ?? []) : ($t['buttons'] ?? []),
                    'variables' => is_object($t) ? ($t->variables ?? []) : ($t['variables'] ?? []),
                    'status' => is_object($t) ? ($t->status ?? 'APPROVED') : ($t['status'] ?? 'APPROVED'),
                ];
            });

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    public function sendTemplate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'template_id' => ['nullable'],
            'template_name' => ['nullable', 'string'],
            'params' => ['nullable', 'array'],
        ]);

        $phone = $validated['phone'];
        try {
            $aisensy = $this->activeAiSensyConfiguration();
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        // Only allow a template returned by the currently configured AiSensy project.
        // A same-named local/seeded template may belong to a different WhatsApp number.
        $template = $this->getAvailableTemplates()->first(function ($t) use ($validated) {
            $tId = is_object($t) ? $t->id : ($t['id'] ?? null);
            $tName = is_object($t) ? $t->name : ($t['name'] ?? null);
            return (!empty($validated['template_id']) && (string)$tId === (string)$validated['template_id'])
                || (!empty($validated['template_name']) && (string)$tName === (string)$validated['template_name']);
        });

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Selected WhatsApp template not found.',
            ], 422);
        }

        $params = $validated['params'] ?? [];
        $renderedBody = is_object($template) ? $template->body : ($template['body'] ?? '');
        $paramValues = [];

        foreach ($params as $idx => $val) {
            $num = $idx + 1;
            $valStr = trim((string)$val);
            if ($valStr !== '') {
                $paramValues[] = $valStr;
                $renderedBody = str_replace("{{{$num}}}", $valStr, $renderedBody);
            }
        }

        $footerText = is_object($template) ? ($template->footer_text ?? '') : ($template['footer_text'] ?? '');
        $templateName = is_object($template) ? $template->name : ($template['name'] ?? '');
        $rawLang = is_object($template) ? ($template->language ?? 'en_GB') : ($template['language'] ?? 'en_GB');
        $templateLang = (stripos($rawLang, 'UK') !== false || stripos($rawLang, 'GB') !== false)
            ? 'en_GB'
            : ((stripos($rawLang, 'US') !== false) ? 'en_US' : 'en_GB');

        if (!empty($footerText)) {
            $fullMessageText = $renderedBody . "\n\n— " . $footerText;
        } else {
            $fullMessageText = $renderedBody;
        }

        // Store outbound template message in database
        $message = WhatsappMessage::create([
            'phone' => $phone,
            'name' => 'System',
            'message' => $fullMessageText,
            'direction' => 'outbound',
            'status' => 'pending',
            'wa_message_id' => 'tpl_' . (string) Str::uuid(),
        ]);

        $apiKey = $aisensy['api_key'];
        $apiUrl = $aisensy['messages_url'];

        $sendSuccess = false;
        $sendError = null;
        $cleanPhone = ltrim(preg_replace('/\D+/', '', $phone), '+');
            $payload = [
                'to' => $cleanPhone,
                'type' => 'template',
                'recipient_type' => 'individual',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $templateLang,
                        'policy' => 'deterministic',
                    ],
                ],
            ];

            $rawTemplateBody = is_object($template) ? $template->body : ($template['body'] ?? '');
            $hasVariables = (bool) preg_match('/\{\{\d+\}\}/', (string)$rawTemplateBody);

            if ($hasVariables && !empty($paramValues)) {
                $payload['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => array_map(fn($v) => ['type' => 'text', 'text' => (string)$v], $paramValues),
                    ],
                ];
            }

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-AiSensy-Project-API-Pwd' => $apiKey,
                ])->timeout(25)->post($apiUrl, $payload);

                if ($response->successful()) {
                    $resData = $response->json();
                    $waMsgId = $resData['messages'][0]['id'] 
                        ?? $resData['messageId'] 
                        ?? $resData['id'] 
                        ?? $resData['data']['messageId'] 
                        ?? null;

                    $message->update([
                        'wa_message_id' => $waMsgId ?: $message->wa_message_id,
                        'status' => 'sent',
                    ]);
                    $sendSuccess = true;
                } else {
                    $resData = $response->json() ?: [];
                    $errText = $resData['message'] ?? $resData['error']['message'] ?? ('AiSensy HTTP error ' . $response->status());
                    Log::error('AiSensy template send error response', [
                        'status' => $response->status(),
                        'response' => $resData,
                        'payload' => $payload,
                    ]);
                    $message->update(['status' => 'failed']);
                    $sendError = $errText;
                }
            } catch (\Throwable $e) {
                Log::error('AiSensy template exception', ['error' => $e->getMessage()]);
                $message->update(['status' => 'failed']);
                $sendError = $e->getMessage();
        }

        $message->refresh();

        return response()->json([
            'success' => $sendSuccess,
            'message' => $this->messagePayload($message),
            'contacts' => $this->getContacts($phone),
            'error' => $sendError,
        ], $sendSuccess ? 200 : 422);
    }

    /**
     * Resolve the single AiSensy project selected in WhatsApp Settings.
     * Templates and messages must use this same project because the project owns
     * both the approved templates and the WhatsApp sender number.
     */
    private function activeAiSensyConfiguration(): array
    {
        $setting = WhatsappSetting::query()->where('is_active', true)->first();

        if (! $setting) {
            throw new \RuntimeException('No active WhatsApp provider is configured in WhatsApp Settings.');
        }

        if ($setting->provider !== 'ai-sense') {
            throw new \RuntimeException('Template messages require AiSensy to be the active WhatsApp provider.');
        }

        $config = $setting->settings ?? [];
        $apiKey = trim((string) ($config['api_key'] ?? ''));
        $projectId = trim((string) ($config['project_id'] ?? ''), " \t\n\r\0\x0B/");
        $apiUrl = trim((string) ($config['api_url'] ?? ''));

        if (! $projectId && preg_match('#project-apis/v1/project/([a-zA-Z0-9_-]+)/messages#', $apiUrl, $matches)) {
            if (! in_array($matches[1], ['messages', '{project_id}'], true)) {
                $projectId = $matches[1];
            }
        }

        if (! $apiKey || ! $projectId || $projectId === '{project_id}') {
            throw new \RuntimeException('AiSensy Project ID or API Key is missing. Please check WhatsApp Settings.');
        }

        return [
            'api_key' => $apiKey,
            'project_id' => $projectId,
            'messages_url' => "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/messages",
        ];
    }

    private function getPhoneVariants(?string $phone): array
    {
        if (!$phone) return [];
        $raw = trim($phone);
        $clean = preg_replace('/\D+/', '', $raw);
        $last10 = strlen($clean) >= 10 ? substr($clean, -10) : $clean;

        return array_values(array_unique(array_filter([
            $raw,
            '+' . ltrim($raw, '+'),
            $clean,
            $last10,
            '+91' . $last10,
            '91' . $last10,
            '0' . $last10,
            '+44' . $last10,
            '44' . $last10,
        ])));
    }

    private function getContacts(?string $activePhone): array
    {
        return $this->getContactsPaginated($activePhone, 25, 1)['contacts'];
    }

    private function getContactsPaginated(?string $activePhone, int $limit = 25, int $page = 1, ?string $search = null, $labelId = null, ?string $tab = null): array
    {
        $query = DB::table('whatsapp_messages as latest')
            ->join(DB::raw('(SELECT phone, MAX(id) as max_id FROM whatsapp_messages GROUP BY phone) as grouped'), function ($join) {
                $join->on('latest.id', '=', 'grouped.max_id');
            })
            ->select('latest.phone', 'latest.name', 'latest.message', 'latest.media_type', 'latest.media_name', 'latest.created_at', 'latest.id');

        // 1. Search filter
        $matchedUsers = collect();
        $matchedLeads = collect();
        if ($search !== null && $search !== '') {
            $hasAsterisk = strpos($search, '*') !== false;
            $cleanSearch = preg_replace('/\D+/', '', $search);
            $clean10 = strlen($cleanSearch) >= 10 ? substr($cleanSearch, -10) : $cleanSearch;

            // Resolve users: utilize find_user_ids_by_search_term (handles masked phone/email/name)
            $matchedUserIds = find_user_ids_by_search_term($search);
            if (!empty($matchedUserIds)) {
                $matchedUsers = User::query()
                    ->whereIn('id', $matchedUserIds)
                    ->get(['id', 'name', 'mobile_no', 'email']);
            }

            // Leads search
            if ($hasAsterisk) {
                $rawPattern = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $search));
                $cleanPattern = ltrim($rawPattern, '0');
                $matchedLeads = Leads::query()
                    ->where(function ($q) use ($search, $rawPattern, $cleanPattern) {
                        $q->where('user_name', 'like', "%{$search}%");
                        if (strpos($search, '@') !== false) {
                            $q->orWhere('email', 'like', preg_replace('/\*+/', '%', $search));
                        }
                        if (!empty($cleanPattern) && preg_match('/\d/', $cleanPattern)) {
                            $q->orWhere('mobile', 'like', "%{$cleanPattern}%")
                              ->orWhere('mobile2', 'like', "%{$cleanPattern}%")
                              ->orWhereRaw("CONCAT(IFNULL(countrycode, ''), IFNULL(mobile, '')) LIKE ?", ["%{$cleanPattern}%"]);
                            if ($rawPattern !== $cleanPattern) {
                                $q->orWhere('mobile', 'like', "%{$rawPattern}%")
                                  ->orWhere('mobile2', 'like', "%{$rawPattern}%");
                            }
                        }
                    })
                    ->get(['id', 'user_name', 'mobile', 'email']);
            } else {
                $matchedLeads = Leads::query()
                    ->where(function ($q) use ($search, $cleanSearch, $clean10) {
                        $q->where('user_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                        if ($cleanSearch !== '') {
                            $q->orWhere('mobile', 'like', "%{$cleanSearch}%");
                        }
                        if ($clean10 !== '') {
                            $q->orWhere('mobile', 'like', "%{$clean10}");
                        }
                    })
                    ->get(['id', 'user_name', 'mobile', 'email']);
            }

            $allMatchedSearchPhones = [];
            foreach ($matchedUsers->pluck('mobile_no')->concat($matchedLeads->pluck('mobile'))->filter()->unique() as $p) {
                foreach ($this->getPhoneVariants($p) as $v) {
                    $allMatchedSearchPhones[$v] = true;
                }
            }
            $searchPhoneKeys = array_keys($allMatchedSearchPhones);

            $query->where(function ($q) use ($search, $cleanSearch, $clean10, $searchPhoneKeys, $hasAsterisk) {
                $q->where('latest.name', 'like', "%{$search}%")
                  ->orWhere('latest.message', 'like', "%{$search}%");

                if ($hasAsterisk) {
                    $rawMsgPattern = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $search));
                    $cleanMsgPattern = ltrim($rawMsgPattern, '0');
                    if (!empty($cleanMsgPattern) && preg_match('/\d/', $cleanMsgPattern)) {
                        $q->orWhere('latest.phone', 'like', "%{$cleanMsgPattern}%");
                    }
                    if ($rawMsgPattern !== $cleanMsgPattern && !empty($rawMsgPattern)) {
                        $q->orWhere('latest.phone', 'like', "%{$rawMsgPattern}%");
                    }
                } else {
                    $q->orWhere('latest.phone', 'like', "%{$search}%");
                    if ($cleanSearch !== '') {
                        $q->orWhere('latest.phone', 'like', "%{$cleanSearch}%");
                    }
                    if ($clean10 !== '') {
                        $q->orWhere('latest.phone', 'like', "%{$clean10}%");
                    }
                }

                if (!empty($searchPhoneKeys)) {
                    $q->orWhereIn('latest.phone', $searchPhoneKeys);
                }
            });
        }

        // 2. Label filter (Query across entire DB)
        if ($labelId !== null && $labelId !== '' && $labelId !== 'all') {
            $assignedPhones = WhatsappChatContactLabel::query()
                ->where('label_id', $labelId)
                ->pluck('phone')
                ->filter()
                ->all();

            if (empty($assignedPhones)) {
                $query->whereRaw('1 = 0');
            } else {
                $allLabelVariants = [];
                foreach ($assignedPhones as $ap) {
                    foreach ($this->getPhoneVariants($ap) as $v) {
                        $allLabelVariants[$v] = true;
                    }
                }
                $query->whereIn('latest.phone', array_keys($allLabelVariants));
            }
        }

        // Fetch all archived phones
        $archivedPhones = WhatsappChatArchive::query()->pluck('phone')->all();
        $archivedVariants = [];
        foreach ($archivedPhones as $ap) {
            foreach ($this->getPhoneVariants($ap) as $v) {
                $archivedVariants[$v] = true;
            }
        }
        $archivedPhoneKeys = array_keys($archivedVariants);

        // Fetch all pinned phones for current user
        $userId = Auth::id();
        $pinnedPhones = WhatsappChatPin::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->pluck('phone')
            ->all();
        $pinnedVariants = [];
        foreach ($pinnedPhones as $pp) {
            foreach ($this->getPhoneVariants($pp) as $v) {
                $pinnedVariants[$v] = true;
            }
        }

        // 3. Tab filter (Unread / Groups / Archived)
        if ($tab === 'archived') {
            if (empty($archivedPhoneKeys)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('latest.phone', $archivedPhoneKeys);
            }
        } else {
            // In All / Unread / Groups tabs, exclude archived chats unless searching
            if (!empty($archivedPhoneKeys) && ($search === null || $search === '')) {
                $query->whereNotIn('latest.phone', $archivedPhoneKeys);
            }

            if ($tab === 'active') {
                $activeInboundPhones = WhatsappMessage::query()
                    ->where('direction', 'inbound')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->pluck('phone')
                    ->filter()
                    ->all();

                if (empty($activeInboundPhones)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $activeVariants = [];
                    foreach ($activeInboundPhones as $activePhone) {
                        foreach ($this->getPhoneVariants($activePhone) as $variant) {
                            $activeVariants[$variant] = true;
                        }
                    }
                    $query->whereIn('latest.phone', array_keys($activeVariants));
                }
            } elseif ($tab === 'unread') {
                $unreadPhones = WhatsappMessage::query()
                    ->where('direction', 'inbound')
                    ->where(function ($q) {
                        $q->whereNull('status')->orWhere('status', '!=', 'read');
                    })
                    ->pluck('phone')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($unreadPhones)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('latest.phone', $unreadPhones);
                }
            } elseif ($tab === 'groups') {
                $query->where(function ($q) {
                    $q->where('latest.phone', 'like', '%@g.us%')
                      ->orWhere('latest.phone', 'like', '%-%')
                      ->orWhere('latest.name', 'like', '%group%');
                });
            } elseif ($tab === 'history' || $tab === 'closed') {
                $activeInboundPhones = WhatsappMessage::query()
                    ->where('direction', 'inbound')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->pluck('phone')
                    ->filter()
                    ->all();

                if (!empty($activeInboundPhones)) {
                    $activeVariants = [];
                    foreach ($activeInboundPhones as $activePhone) {
                        foreach ($this->getPhoneVariants($activePhone) as $variant) {
                            $activeVariants[$variant] = true;
                        }
                    }
                    $query->whereNotIn('latest.phone', array_keys($activeVariants));
                }
            }
            // Note: For 'all' tab or default, all non-archived conversations are returned.
        }

        // Fetch limit + 1 to determine has_more without expensive COUNT(*) table scan
        $latestMessages = $query->orderByDesc('latest.created_at')
            ->orderByDesc('latest.id')
            ->skip(($page - 1) * $limit)
            ->take($limit + 1)
            ->get();

        $hasMore = $latestMessages->count() > $limit;
        if ($hasMore) {
            $latestMessages = $latestMessages->slice(0, $limit);
        }

        $phones = $latestMessages->pluck('phone')->filter()->values()->all();

        // Build all phone variants for fast indexed lookup
        $allVariants = [];
        foreach ($phones as $p) {
            foreach ($this->getPhoneVariants($p) as $v) {
                $allVariants[$v] = $p;
            }
        }

        $users = !empty($allVariants)
            ? User::query()->whereIn('mobile_no', array_keys($allVariants))->get(['id', 'name', 'mobile_no'])
            : collect();

        $userMap = [];
        foreach ($users as $u) {
            $matchedPhone = $allVariants[$u->mobile_no] ?? null;
            if ($matchedPhone && !isset($userMap[$matchedPhone])) {
                $userMap[$matchedPhone] = $u;
            }
        }

        $leads = !empty($allVariants)
            ? Leads::query()->whereIn('mobile', array_keys($allVariants))->get(['id', 'user_name', 'mobile'])
            : collect();

        $leadMap = [];
        foreach ($leads as $l) {
            $matchedPhone = $allVariants[$l->mobile] ?? null;
            if ($matchedPhone && !isset($leadMap[$matchedPhone])) {
                $leadMap[$matchedPhone] = $l;
            }
        }

        // 1 Single Grouped Query for unread counts (Eliminates N+1)
        $unreadCounts = !empty($phones)
            ? WhatsappMessage::query()
                ->whereIn('phone', $phones)
                ->where('direction', 'inbound')
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', '!=', 'read');
                })
                ->select('phone', DB::raw('COUNT(*) as unread_count'))
                ->groupBy('phone')
                ->pluck('unread_count', 'phone')
                ->all()
            : [];

        // Check active 24-hour window for returned contacts to determine closed status
        $conversationStates = Schema::hasTable('whatsapp_chat_states') && !empty($allVariants)
            ? WhatsappChatState::query()
                ->whereIn('phone', array_keys($allVariants))
                ->get(['phone', 'conversation_status'])
                ->mapWithKeys(fn($state) => [
                    $allVariants[$state->phone] ?? $state->phone => strtolower($state->conversation_status),
                ])
                ->all()
            : [];

        $recentInboundPhones = !empty($allVariants)
            ? WhatsappMessage::query()
                ->whereIn('phone', array_keys($allVariants))
                ->where('direction', 'inbound')
                ->where('created_at', '>=', now()->subHours(24))
                ->pluck('phone')
                ->map(fn($phone) => $allVariants[$phone] ?? $phone)
                ->unique()
                ->all()
            : [];
        $recentInboundSet = array_flip($recentInboundPhones);

        // Also fetch latest inbound name for contacts whose latest message name is 'System' or empty or missing user name
        $inboundNames = !empty($phones)
            ? WhatsappMessage::query()
                ->whereIn('phone', $phones)
                ->where('direction', 'inbound')
                ->whereNotNull('name')
                ->where('name', '!=', '')
                ->where('name', '!=', 'System')
                ->select('phone', 'name', 'id')
                ->orderByDesc('id')
                ->get()
                ->groupBy('phone')
                ->map(fn($rows) => $rows->first()->name)
                ->all()
            : [];

        // Fetch labels for these contacts
        $labelsMap = !empty($allVariants)
            ? WhatsappChatContactLabel::query()
                ->whereIn('phone', array_keys($allVariants))
                ->get()
                ->groupBy(function ($item) use ($allVariants) {
                    return $allVariants[$item->phone] ?? $item->phone;
                })
                ->map(fn($rows) => $rows->pluck('label_id')->unique()->values()->all())
                ->all()
            : [];

        $allLabels = WhatsappChatLabel::query()->ordered()->get()->keyBy('id');

        $contacts = $latestMessages->map(function ($contact, int $index) use ($userMap, $leadMap, $inboundNames, $activePhone, $unreadCounts, $labelsMap, $allLabels, $archivedVariants, $pinnedVariants, $conversationStates, $recentInboundSet, $page, $limit) {
            $cleanP = preg_replace('/\D+/', '', (string)$contact->phone);
            $cleanP10 = strlen($cleanP) >= 10 ? substr($cleanP, -10) : $cleanP;
            $user = $userMap[$contact->phone] ?? null;
            $userName = ($user && $user->name && $user->name !== 'System' && !str_starts_with(strtolower($user->name), 'user') && strlen(preg_replace('/\D+/', '', (string)$user->name)) < 10 && preg_replace('/\D+/', '', (string)$user->name) !== $cleanP && preg_replace('/\D+/', '', (string)$user->name) !== $cleanP10) ? $user->name : null;
            $lead = $leadMap[$contact->phone] ?? null;
            $leadName = ($lead && $lead->user_name && $lead->user_name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$lead->user_name)) < 10 && preg_replace('/\D+/', '', (string)$lead->user_name) !== $cleanP && preg_replace('/\D+/', '', (string)$lead->user_name) !== $cleanP10) ? $lead->user_name : null;
            $inboundName = $inboundNames[$contact->phone] ?? null;
            if ($inboundName && (preg_replace('/\D+/', '', (string)$inboundName) === $cleanP || preg_replace('/\D+/', '', (string)$inboundName) === $cleanP10 || strlen(preg_replace('/\D+/', '', (string)$inboundName)) >= 10 || $inboundName === 'System')) {
                $inboundName = null;
            }
            $contactName = ($contact->name && $contact->name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$contact->name)) < 10 && preg_replace('/\D+/', '', (string)$contact->name) !== $cleanP && preg_replace('/\D+/', '', (string)$contact->name) !== $cleanP10) ? $contact->name : null;

            $maskedPhone = mask_phone_for_display('', $contact->phone);
            $name = $userName ?: ($leadName ?: ($inboundName ?: ($contactName ?: null)));
            if (!$name || trim($name) === '' || $name === 'Unknown User' || $name === 'System' || strlen(preg_replace('/\D+/', '', (string)$name)) >= 10) {
                $name = $maskedPhone;
            }
            $unreadCount = (int) ($unreadCounts[$contact->phone] ?? 0);
            $globalIndex = (($page - 1) * $limit) + $index;
            $contactLabelIds = $labelsMap[$contact->phone] ?? [];
            $contactLabels = collect($contactLabelIds)->map(fn($id) => $allLabels->get($id))->filter()->values();
            $isPinned = !empty($pinnedVariants[$contact->phone]) || (!empty($cleanP) && !empty($pinnedVariants[$cleanP]));
            $conversationStatus = $conversationStates[$contact->phone] ?? null;
            $isClosed = $conversationStatus === 'closed';

            return [
                'id' => $contact->id,
                'phone' => $contact->phone,
                'phone_display' => mask_phone_for_display('', $contact->phone),
                'name' => $name,
                'msg' => $this->contactPreview($contact),
                'time' => optional($contact->created_at ? \Carbon\Carbon::parse($contact->created_at) : null)->isToday()
                    ? \Carbon\Carbon::parse($contact->created_at)->format('H:i')
                    : optional($contact->created_at ? \Carbon\Carbon::parse($contact->created_at) : null)->format('D'),
                'active' => $contact->phone === $activePhone,
                'badge' => $unreadCount,
                'color' => $this->avatarColor($globalIndex),
                'status' => $unreadCount > 0 ? 'online' : 'offline',
                'label_ids' => $contactLabelIds,
                'labels' => $contactLabels,
                'is_archived' => isset($archivedVariants[$contact->phone]),
                'is_pinned' => $isPinned,
                'is_closed' => $isClosed,
                'conversation_status' => $conversationStatus,
                'template_required' => !isset($recentInboundSet[$contact->phone]),
            ];
        })->values()->toArray();

        // On page 1, ensure all pinned contacts are included even if not in latest 25 messages
        if ($page == 1 && !empty($pinnedPhones)) {
            $existingLoadedPhones = collect($contacts)->pluck('phone')->all();
            $existingLoadedVariants = [];
            foreach ($existingLoadedPhones as $ep) {
                foreach ($this->getPhoneVariants($ep) as $v) {
                    $existingLoadedVariants[$v] = true;
                }
            }

            foreach ($pinnedPhones as $pp) {
                if (!isset($existingLoadedVariants[$pp])) {
                    $ppVariants = $this->getPhoneVariants($pp);
                    $lastMsg = WhatsappMessage::query()->whereIn('phone', $ppVariants)->latest('id')->first();
                    if ($lastMsg) {
                        $cleanPP = preg_replace('/\D+/', '', (string)$pp);
                        $u = User::query()->whereIn('mobile_no', $ppVariants)->first(['id', 'name']);
                        $l = Leads::query()->whereIn('mobile', $ppVariants)->first(['id', 'user_name']);
                        $cName = ($u && $u->name && $u->name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$u->name)) < 10 && preg_replace('/\D+/', '', (string)$u->name) !== $cleanPP)
                            ? $u->name
                            : (($l && $l->user_name && $l->user_name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$l->user_name)) < 10 && preg_replace('/\D+/', '', (string)$l->user_name) !== $cleanPP)
                                ? $l->user_name
                                : ($lastMsg->name && $lastMsg->name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$lastMsg->name)) < 10 && preg_replace('/\D+/', '', (string)$lastMsg->name) !== $cleanPP ? $lastMsg->name : null));
                        $maskedPP = mask_phone_for_display('', $pp);
                        if (!$cName || trim($cName) === '' || preg_replace('/\D+/', '', (string)$cName) === $cleanPP || strlen(preg_replace('/\D+/', '', (string)$cName)) >= 10 || $cName === 'System' || $cName === 'Unknown User') {
                            $cName = $maskedPP;
                        }
                        $cLabelIds = $labelsMap[$pp] ?? [];
                        $cLabels = collect($cLabelIds)->map(fn($id) => $allLabels->get($id))->filter()->values();

                        $contacts[] = [
                            'id' => $lastMsg->id,
                            'phone' => $pp,
                            'phone_display' => mask_phone_for_display('', $pp),
                            'name' => $cName,
                            'msg' => $this->contactPreview($lastMsg),
                            'time' => optional($lastMsg->created_at ? \Carbon\Carbon::parse($lastMsg->created_at) : null)->isToday()
                                ? \Carbon\Carbon::parse($lastMsg->created_at)->format('H:i')
                                : optional($lastMsg->created_at ? \Carbon\Carbon::parse($lastMsg->created_at) : null)->format('D'),
                            'active' => $pp === $activePhone,
                            'badge' => (int) ($unreadCounts[$pp] ?? 0),
                            'color' => $this->avatarColor(0),
                            'status' => 'online',
                            'label_ids' => $cLabelIds,
                            'labels' => $cLabels,
                            'is_archived' => false,
                            'is_pinned' => true,
                            'is_closed' => ($conversationStates[$pp] ?? null) === 'closed',
                            'conversation_status' => $conversationStates[$pp] ?? null,
                            'template_required' => !isset($recentInboundSet[$pp]),
                        ];

                        foreach ($ppVariants as $v) {
                            $existingLoadedVariants[$v] = true;
                        }
                    }
                }
            }
        }

        // Sort: Pinned chats always stay on top
        usort($contacts, function ($a, $b) {
            $aPinned = !empty($a['is_pinned']) ? 1 : 0;
            $bPinned = !empty($b['is_pinned']) ? 1 : 0;
            if ($aPinned !== $bPinned) {
                return $bPinned - $aPinned;
            }
            return 0;
        });

        // If search is performed on page 1, also append matching Users/Leads who have no existing chat history
        if ($search !== null && $search !== '' && $page == 1) {
            $existingPhones = collect($contacts)->pluck('phone')->all();
            $existingVariants = [];
            foreach ($existingPhones as $ep) {
                foreach ($this->getPhoneVariants($ep) as $v) {
                    $existingVariants[$v] = true;
                }
            }

            foreach ($matchedUsers as $mu) {
                if ($mu->mobile_no && !isset($existingVariants[$mu->mobile_no])) {
                    foreach ($this->getPhoneVariants($mu->mobile_no) as $v) {
                        $existingVariants[$v] = true;
                    }
                    $contacts[] = [
                        'id' => 'u_' . $mu->id,
                        'phone' => $mu->mobile_no,
                        'phone_display' => mask_phone_for_display('', $mu->mobile_no),
                        'name' => $mu->name ?: mask_phone_for_display('', $mu->mobile_no),
                        'msg' => 'CRM User (' . ($mu->email ?: mask_phone_for_display('', $mu->mobile_no)) . ')',
                        'time' => 'User',
                        'active' => $mu->mobile_no === $activePhone,
                        'badge' => 0,
                        'color' => '#00a884',
                        'status' => 'offline',
                        'label_ids' => [],
                        'labels' => collect(),
                        'is_archived' => false,
                    ];
                }
            }

            foreach ($matchedLeads as $ml) {
                if ($ml->mobile && !isset($existingVariants[$ml->mobile])) {
                    foreach ($this->getPhoneVariants($ml->mobile) as $v) {
                        $existingVariants[$v] = true;
                    }
                    $contacts[] = [
                        'id' => 'l_' . $ml->id,
                        'phone' => $ml->mobile,
                        'phone_display' => mask_phone_for_display('', $ml->mobile),
                        'name' => $ml->user_name ?: mask_phone_for_display('', $ml->mobile),
                        'msg' => 'CRM Lead (' . ($ml->email ?: mask_phone_for_display('', $ml->mobile)) . ')',
                        'time' => 'Lead',
                        'active' => $ml->mobile === $activePhone,
                        'badge' => 0,
                        'color' => '#ff7043',
                        'status' => 'offline',
                        'label_ids' => [],
                        'labels' => collect(),
                        'is_archived' => false,
                    ];
                }
            }
        }

        return [
            'contacts' => $contacts,
            'total' => count($contacts),
            'has_more' => $hasMore,
        ];
    }

    public function customerData(Request $request): JsonResponse
    {
        $phone = $request->query('phone', '');
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Phone required']);
        }
        $summary = $this->getCustomerSummary($phone);
        return response()->json(array_merge(['success' => true], $summary));
    }

    private function getCustomerSummary(string $phone): array
    {
        $variants = $this->getPhoneVariants($phone);
        $cleanPhone = preg_replace('/\D+/', '', $phone);
        $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

        $users = User::query()
            ->where(function ($q) use ($variants, $cleanPhone, $last10) {
                $q->whereIn('mobile_no', $variants);
                if (!empty($cleanPhone)) {
                    $q->orWhere('mobile_no', 'like', "%{$cleanPhone}%");
                }
                if (!empty($last10)) {
                    $q->orWhere('mobile_no', 'like', "%{$last10}");
                }
            })
            ->get(['id', 'email', 'name', 'mobile_no', 'countrycode', 'refer_id']);

        $existingUser = $users->first();
        $userIds = $users->pluck('id')->filter()->all();
        $userEmails = $users->pluck('email')->filter()->all();

        $matchingLeads = Leads::query()
            ->where(function ($q) use ($phone, $cleanPhone, $last10, $variants, $userIds, $userEmails) {
                $q->whereIn('mobile', $variants)
                  ->orWhere('mobile', $phone)
                  ->orWhere('mobile', $cleanPhone);
                if (!empty($last10)) {
                    $q->orWhere('mobile', 'like', "%{$last10}");
                }
                if (!empty($variants)) {
                    $q->orWhereIn('mobile2', $variants);
                }
                if (!empty($userIds)) {
                    $q->orWhereIn('emp_id', $userIds);
                }
                if (!empty($userEmails)) {
                    $q->orWhereIn('email', $userEmails);
                }
            })
            ->get(['id', 'order_id', 'emp_id', 'is_converted', 'user_name', 'email', 'countrycode', 'mobile', 'l_status']);

        $existingLead = $matchingLeads->sortByDesc('id')->first();

        // If user was not found directly by mobile, check if lead has linked emp_id
        if (!$existingUser && $existingLead && !empty($existingLead->emp_id)) {
            $existingUser = User::query()->where('id', $existingLead->emp_id)->first(['id', 'email', 'name', 'mobile_no', 'countrycode', 'refer_id']);
        }

        // Fetch refer user if customer has refer_id
        $referUser = null;
        if ($existingUser && !empty($existingUser->refer_id)) {
            $referUser = User::query()->where('id', $existingUser->refer_id)->first(['id', 'name', 'email', 'mobile_no', 'countrycode']);
        }

        $unconvertedLeadsCount = $matchingLeads->where('is_converted', '!=', 1)->count();

        $convertedLeadIds = $matchingLeads->where('is_converted', 1)->pluck('id')->filter()->all();
        $convertedLeadOrderIds = $matchingLeads->where('is_converted', 1)->pluck('order_id')->filter()->all();

        $ordersCount = 0;
        if (!empty($userIds) || !empty($convertedLeadIds) || !empty($convertedLeadOrderIds)) {
            $ordersCount = Order::query()
                ->whereNotNull('orders.uid')
                ->where('orders.uid', '!=', 0)
                ->where('orders.uid', '!=', '')
                ->where(function ($q) use ($userIds, $convertedLeadIds, $convertedLeadOrderIds) {
                    if (!empty($userIds)) {
                        $q->whereIn('orders.uid', $userIds)
                          ->where(function ($sub) {
                              $sub->whereDoesntHave('lead')->whereDoesntHave('frontendLead');
                          });
                    }
                    if (!empty($convertedLeadIds)) {
                        $q->orWhereIn('orders.lead_id', $convertedLeadIds);
                    }
                    if (!empty($convertedLeadOrderIds)) {
                        $q->orWhereIn('orders.order_id', $convertedLeadOrderIds);
                    }
                })
                ->count();
        }

        $labelIds = WhatsappChatContactLabel::query()
            ->whereIn('phone', $variants)
            ->pluck('label_id')
            ->unique()
            ->all();

        $labels = !empty($labelIds)
            ? WhatsappChatLabel::query()->whereIn('id', $labelIds)->ordered()->get(['id', 'name', 'color'])
            : collect();

        $latestInbound = WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where('direction', 'inbound')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->where('name', '!=', 'System')
            ->orderByDesc('id')
            ->first(['name']);

        $resolvedName = null;
        $cleanPhoneP = preg_replace('/\D+/', '', (string)$phone);
        $cleanPhoneP10 = strlen($cleanPhoneP) >= 10 ? substr($cleanPhoneP, -10) : $cleanPhoneP;
        if ($existingUser && $existingUser->name && $existingUser->name !== 'System' && !str_starts_with(strtolower($existingUser->name), 'user') && strlen(preg_replace('/\D+/', '', (string)$existingUser->name)) < 10 && preg_replace('/\D+/', '', (string)$existingUser->name) !== $cleanPhoneP && preg_replace('/\D+/', '', (string)$existingUser->name) !== $cleanPhoneP10) {
            $resolvedName = $existingUser->name;
        } elseif ($existingLead && $existingLead->user_name && $existingLead->user_name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$existingLead->user_name)) < 10 && preg_replace('/\D+/', '', (string)$existingLead->user_name) !== $cleanPhoneP && preg_replace('/\D+/', '', (string)$existingLead->user_name) !== $cleanPhoneP10) {
            $resolvedName = $existingLead->user_name;
        } elseif ($latestInbound && $latestInbound->name && $latestInbound->name !== 'System' && strlen(preg_replace('/\D+/', '', (string)$latestInbound->name)) < 10 && preg_replace('/\D+/', '', (string)$latestInbound->name) !== $cleanPhoneP && preg_replace('/\D+/', '', (string)$latestInbound->name) !== $cleanPhoneP10) {
            $resolvedName = $latestInbound->name;
        } else {
            $resolvedName = mask_phone_for_display('', $phone);
        }

        $conversationStatus = Schema::hasTable('whatsapp_chat_states')
            ? WhatsappChatState::query()
                ->whereIn('phone', $variants)
                ->value('conversation_status')
            : null;
        $hasRecentInbound = WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where('direction', 'inbound')
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        return [
            'name' => $resolvedName,
            'phone' => $phone,
            'leads_count' => $unconvertedLeadsCount,
            'orders_count' => $ordersCount,
            'is_closed' => strtolower((string) $conversationStatus) === 'closed',
            'conversation_status' => $conversationStatus,
            'template_required' => !$hasRecentInbound,
            'lead' => $existingLead ? [
                'id' => $existingLead->id,
                'order_id' => $existingLead->order_id ?? (string) $existingLead->id,
                'edit_url' => route('lead.edit', $existingLead->id),
                'status' => $existingLead->l_status ?: 'Waiting',
                'user_name' => $existingLead->user_name,
                'email' => $existingLead->email,
                'countrycode' => $existingLead->countrycode,
                'mobile' => $existingLead->mobile,
            ] : null,
            'user' => $existingUser ? [
                'id' => $existingUser->id,
                'name' => $existingUser->name,
                'email' => $existingUser->email,
                'masked_email' => mask_email_for_display($existingUser->email),
                'countrycode' => $existingUser->countrycode,
                'mobile_no' => $existingUser->mobile_no,
                'masked_mobile' => mask_mobile_only($existingUser->countrycode, $existingUser->mobile_no),
                'refer_id' => $existingUser->refer_id,
            ] : null,
            'refer_user' => $referUser ? [
                'id' => $referUser->id,
                'name' => $referUser->name,
                'email' => $referUser->email,
                'masked_email' => mask_email_for_display($referUser->email),
                'countrycode' => $referUser->countrycode,
                'mobile_no' => $referUser->mobile_no,
                'masked_mobile' => mask_mobile_only($referUser->countrycode, $referUser->mobile_no),
            ] : null,
            'labels' => $labels,
            'lead_model' => $existingLead,
            'user_model' => $existingUser,
        ];
    }

    private function contactPreview(object $contact): string
    {
        $text = trim((string) ($contact->message ?? ''));

        if ($text !== '') {
            return $text;
        }

        return match ($contact->media_type ?? null) {
            'image' => 'Image attachment',
            'video' => 'Video attachment',
            'audio' => 'Audio attachment',
            'document' => $contact->media_name ?: 'Document attachment',
            default => 'New chat started',
        };
    }

    private function markPhoneMessagesRead(string $phone): int
    {
        $variants = $this->getPhoneVariants($phone);

        return WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where('direction', 'inbound')
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', '!=', 'read');
            })
            ->update(['status' => 'read']);
    }

    private function messagePayload(WhatsappMessage $message): array
    {
        return [
            'id'         => $message->id,
            'phone'      => $message->phone,
            'name'       => $message->name,
            'message'    => $message->message,
            'direction'  => $message->direction,
            'status'     => $message->status,
            'time'       => optional($message->created_at)->format('H:i'),
            'created_at' => optional($message->created_at)->toDateTimeString(),
            'media_url'  => $this->mediaDisplayUrl($message->media_url),
            'media_type' => $this->displayMediaType($message->media_type, $message->media_name, $message->media_url),
            'media_name' => $message->media_name,
            'media_size' => $message->media_size,
        ];
    }

    private function recentOutboundStatuses(string $phone): array
    {
        $variants = $this->getPhoneVariants($phone);

        return WhatsappMessage::query()
            ->whereIn('phone', $variants)
            ->where('direction', 'outbound')
            ->latest('id')
            ->limit(100)
            ->get(['id', 'wa_message_id', 'status'])
            ->map(fn (WhatsappMessage $message) => [
                'id' => $message->id,
                'wa_message_id' => $message->wa_message_id,
                'status' => $message->status,
            ])
            ->values()
            ->all();
    }

    private function typingCacheKey(string $phone): string
    {
        return 'whatsapp_typing:' . preg_replace('/[^0-9+]/', '', $phone);
    }

    private function storeOutboundMessage(string $phone, string $text): WhatsappMessage
    {
        return WhatsappMessage::query()->create([
            'wa_message_id' => 'wa_' . (string) Str::uuid(),
            'phone' => $phone,
            'name' => Auth::user()?->name ?? 'Admin',
            'message' => $text,
            'direction' => 'outbound',
            'status' => 'queued',
        ]);
    }

    private function sendViaActiveProvider(WhatsappMessage $message): array
    {
        $setting = WhatsappSetting::query()->where('is_active', true)->first();

        if (! $setting) {
            $message->update(['status' => 'failed']);

            return [
                'success' => false,
                'error' => 'No active WhatsApp provider is configured in WhatsApp Settings.',
            ];
        }

        $config = $setting->settings ?? [];

        // -----------------------------------------------------------------
        // 1. AiSensy Provider Sending
        // -----------------------------------------------------------------
        if ($setting->provider === 'ai-sense') {
            $apiKey = trim((string) ($config['api_key'] ?? env('AISENSY_API_KEY', '')));
            $projectId = trim((string) ($config['project_id'] ?? ''), " \t\n\r\0\x0B/");
            $apiUrl = trim((string) ($config['api_url'] ?? ''));

            // Auto-detect project ID from URL if user entered full URL containing the ID
            if (! $projectId && ! empty($apiUrl)) {
                if (preg_match('#project-apis/v1/project/([a-zA-Z0-9_-]+)/messages#', $apiUrl, $matches)) {
                    if ($matches[1] !== 'messages' && $matches[1] !== '{project_id}') {
                        $projectId = $matches[1];
                    }
                }
            }

            // Always ensure the correct AiSensy project endpoint format
            if ($projectId) {
                $apiUrl = "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/messages";
            }

            if (! $apiKey || ! $projectId || ! $apiUrl) {
                $message->update(['status' => 'failed']);

                return [
                    'success' => false,
                    'error' => 'AiSensy Project ID or API Key is missing. Please check your WhatsApp Settings.',
                ];
            }

            $cleanPhone = ltrim(preg_replace('/\D+/', '', $message->phone), '+');

            try {
                if ($message->media_url) {
                    $mediaType = $message->media_type ?: 'image';
                    $fullMediaUrl = str_starts_with($message->media_url, 'http') ? $message->media_url : url($message->media_url);

                    $payload = [
                        'to' => $cleanPhone,
                        'type' => $mediaType,
                        $mediaType => [
                            'link' => $fullMediaUrl,
                            'caption' => (string) ($message->message ?? ''),
                        ],
                    ];

                    if ($mediaType === 'document' && $message->media_name) {
                        $payload['document']['filename'] = $message->media_name;
                    }
                } else {
                    $payload = [
                        'to' => $cleanPhone,
                        'type' => 'text',
                        'recipient_type' => 'individual',
                        'text' => [
                            'body' => (string) $message->message,
                        ],
                    ];
                }

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-AiSensy-Project-API-Pwd' => $apiKey,
                ])->timeout(25)->post($apiUrl, $payload);

                if ($response->successful()) {
                    $resData = $response->json();
                    $waMsgId = $resData['messages'][0]['id'] 
                        ?? $resData['messageId'] 
                        ?? $resData['id'] 
                        ?? $resData['data']['messageId'] 
                        ?? $resData['data']['id'] 
                        ?? $resData['data']['messages'][0]['id'] 
                        ?? $resData['message_id'] 
                        ?? null;

                    $message->update([
                        'wa_message_id' => $waMsgId ?: $message->wa_message_id,
                        'status' => 'sent',
                    ]);

                    return ['success' => true, 'error' => null];
                }

                $message->update(['status' => 'failed']);
                Log::warning('AiSensy WhatsApp send failed', [
                    'payload' => $payload,
                    'response' => $response->json(),
                ]);

                return [
                    'success' => false,
                    'error' => $response->json('message') ?? $response->json('error') ?? 'AiSensy send failed.',
                ];
            } catch (\Throwable $exception) {
                $message->update(['status' => 'failed']);
                Log::error('AiSensy WhatsApp send exception', ['exception' => $exception]);

                return [
                    'success' => false,
                    'error' => 'AiSensy WhatsApp send error: ' . $exception->getMessage(),
                ];
            }
        }

        // -----------------------------------------------------------------
        // 2. Twilio Provider Sending
        // -----------------------------------------------------------------
        if ($setting->provider === 'twilio') {
            $sid = $config['account_sid'] ?? null;
            $token = $config['auth_token'] ?? null;
            $from = $config['whatsapp_from_number'] ?? null;

            if (! $sid || ! $token || ! $from) {
                $message->update(['status' => 'failed']);

                return [
                    'success' => false,
                    'error' => 'Twilio WhatsApp settings are incomplete.',
                ];
            }

            try {
                $payload = [
                    'From' => str_starts_with($from, 'whatsapp:') ? $from : 'whatsapp:' . $from,
                    'To' => str_starts_with($message->phone, 'whatsapp:') ? $message->phone : 'whatsapp:' . $message->phone,
                ];

                $statusCallback = $this->providerWebhookUrl($config);
                if ($statusCallback) {
                    $payload['StatusCallback'] = $statusCallback;
                }

                if (trim((string) $message->message) !== '') {
                    $payload['Body'] = $message->message;
                }

                if ($message->media_url) {
                    $payload['MediaUrl'] = $this->providerMediaUrl($message->media_url, $config);
                }

                if (! isset($payload['Body']) && ! isset($payload['MediaUrl'])) {
                    $message->update(['status' => 'failed']);

                    return [
                        'success' => false,
                        'error' => 'Message body or media is required.',
                    ];
                }

                $response = Http::withBasicAuth($sid, $token)
                    ->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", $payload);

                if ($response->successful()) {
                    $message->update([
                        'wa_message_id' => $response->json('sid'),
                        'status' => $response->json('status', 'sent'),
                    ]);

                    return ['success' => true, 'error' => null];
                } else {
                    $message->update(['status' => 'failed']);
                    Log::warning('Twilio WhatsApp send failed', [
                        'payload' => collect($payload)->except(['Body'])->all(),
                        'response' => $response->json(),
                    ]);

                    return [
                        'success' => false,
                        'error' => $response->json('message') ?: 'Twilio WhatsApp send failed.',
                    ];
                }
            } catch (\Throwable $exception) {
                $message->update(['status' => 'failed']);
                Log::error('Twilio WhatsApp send exception', ['exception' => $exception]);

                return [
                    'success' => false,
                    'error' => 'WhatsApp send failed: ' . $exception->getMessage(),
                ];
            }
        }

        $message->update(['status' => 'failed']);

        return [
            'success' => false,
            'error' => ucfirst($setting->provider) . ' sending is not supported yet.',
        ];
    }

    private function cleanSettings(array $settings): array
    {
        return collect($settings)
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->toArray();
    }

    private function normalizePhone(string $countryCode, string $mobile): string
    {
        return '+' . ltrim(preg_replace('/\D+/', '', $countryCode . $mobile), '+');
    }

    private function avatarColor(int $index): string
    {
        return ['#25d366', '#00bcd4', '#ff7043', '#ab47bc', '#ffa726', '#ef5350'][$index % 6];
    }

    private function chatPanelDefinitions(): array
    {
        return [
            'team' => ['label' => 'Alpha / Giga', 'short' => 'AG'],
            'failed' => ['label' => 'Failed', 'short' => 'F'],
            'ticket' => ['label' => 'Ticket', 'short' => 'T'],
            'order' => ['label' => 'Order', 'short' => 'O'],
            'working' => ['label' => 'Working', 'short' => 'W'],
        ];
    }

    private function enabledPanelKeys(?int $userId, array $defaultKeys): array
    {
        if (! $userId) {
            return $defaultKeys;
        }

        $saved = WhatsappChatPanelSetting::query()
            ->where('user_id', $userId)
            ->get();

        if ($saved->isEmpty()) {
            return $defaultKeys;
        }

        $enabled = $saved->where('is_enabled', true)->pluck('panel_key')->values();

        if ($enabled->contains('alpha') || $enabled->contains('giga')) {
            $enabled = $enabled->reject(fn ($key) => in_array($key, ['alpha', 'giga'], true))->push('team');
        }

        return $enabled->unique()->values()->all();
    }

    private function panelRows(string $panelKey)
    {
        $query = Order::query()
            ->select('id', 'order_id', 'title', 'order_date', 'delivery_date', 'projectstatus', 'feedback_ticket', 'team_id')
            ->orderByDesc('id')
            ->limit(30);

        if ($panelKey === 'team') {
            $query->whereIn('team_id', [1, 2]);
        } elseif ($panelKey === 'failed') {
            $query->where(function ($q) {
                $q->where('projectstatus', 'Failed');
                if (Schema::hasColumn('orders', 'is_fail')) {
                    $q->orWhere('is_fail', 1);
                }
            });
        } elseif ($panelKey === 'ticket') {
            $query->whereNotNull('feedback_ticket')->where('feedback_ticket', '!=', '');
        } elseif ($panelKey === 'working') {
            $query->whereNotIn('projectstatus', ['Completed', 'Delivered', 'Cancelled', 'Feedback', 'Feedback Delivered']);
        }

        return $query->get();
    }

    public function importContacts(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contacts'          => ['required', 'array', 'min:1', 'max:100'],
            'contacts.*.name'   => ['required', 'string', 'max:200'],
            'contacts.*.phone'  => ['required', 'string', 'max:30'],
        ]);

        $imported = 0;
        $failed   = 0;

        foreach ($validated['contacts'] as $contact) {
            try {
                $phone = trim($contact['phone']);
                $name  = trim($contact['name']);

                if (!str_starts_with($phone, '+')) {
                    $phone = '+' . ltrim(preg_replace('/\D+/', '', $phone), '+');
                }

                if (strlen($phone) < 8) {
                    $failed++;
                    continue;
                }

                WhatsappMessage::query()->firstOrCreate(
                    ['phone' => $phone, 'message' => ''],
                    ['name' => $name, 'direction' => 'outbound', 'status' => 'draft']
                );

                $imported++;
            } catch (\Throwable $e) {
                Log::warning('WhatsApp import contact failed', ['contact' => $contact, 'error' => $e->getMessage()]);
                $failed++;
            }
        }

        return response()->json([
            'success'  => true,
            'imported' => $imported,
            'failed'   => $failed,
            'message'  => "{$imported} contact(s) imported.",
        ]);
    }

    public function sendMedia(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'file'  => ['nullable', 'file', 'max:51200'],
            'files' => ['nullable', 'array', 'max:20'],
            'files.*' => ['file', 'max:51200'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $phone    = $validated['phone'];
        $caption  = $validated['caption'] ?? '';

        $files = collect($request->file('files', []));
        if ($request->hasFile('file')) {
            $files = $files->prepend($request->file('file'));
        }

        $files = $files->filter()->values();

        if ($files->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one file.',
            ], 422);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif', 'bmp', 'mp4', 'mov', 'avi', 'mkv', 'webm', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', 'mp3', 'ogg', 'wav', 'm4a', 'csv'];
        $messages = collect();
        $sendErrors = [];

        foreach ($files as $index => $file) {
            $extension = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, $allowedExtensions, true)) {
                return response()->json([
                    'success' => false,
                    'message' => "{$file->getClientOriginalName()} file type is not allowed.",
                ], 422);
            }

            $origName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType() ?: $file->getClientMimeType();
            $size     = $file->getSize();

            $mediaType = match (true) {
                $this->isVoiceNote($origName, $extension) => 'audio',
                str_starts_with((string) $mimeType, 'image/') => 'image',
                str_starts_with((string) $mimeType, 'video/') => 'video',
                str_starts_with((string) $mimeType, 'audio/') => 'audio',
                default                                      => 'document',
            };

            $fileName = uniqid('wa_', true) . '.' . $extension;
            $destinationPath = base_path('assets/media/whatsapp');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $relativePath = 'assets/media/whatsapp/' . $fileName;

            if ($this->isVoiceNote($origName, $extension)) {
                $converted = $this->convertVoiceNoteToOgg($destinationPath, $fileName, $origName);

                if ($converted) {
                    $fileName = $converted['file_name'];
                    $relativePath = $converted['relative_path'];
                    $origName = $converted['media_name'];
                    $size = $converted['size'];
                    $extension = 'ogg';
                } elseif ($extension === 'webm') {
                    @unlink($destinationPath . DIRECTORY_SEPARATOR . $fileName);

                    return response()->json([
                        'success' => false,
                        'message' => 'Audio recording could not be converted for WhatsApp. Please install ffmpeg on the server.',
                    ], 422);
                }
            }

            $message = WhatsappMessage::query()->create([
                'wa_message_id' => 'wa_' . (string) Str::uuid(),
                'phone'      => $phone,
                'name'       => Auth::user()?->name ?? 'Admin',
                'message'    => $index === 0 ? $caption : '',
                'direction'  => 'outbound',
                'status'     => 'queued',
                'media_url'  => $relativePath,
                'media_type' => $mediaType,
                'media_name' => $origName,
                'media_size' => $size,
            ]);

            $sendResult = $this->sendViaActiveProvider($message);
            if (! $sendResult['success']) {
                $sendErrors[] = $sendResult['error'];
            }

            $message->refresh();
            $messages->push($message);
        }

        $payload = [
            'success'  => empty($sendErrors),
            'message'  => $this->messagePayload($messages->first()),
            'messages' => $messages->map(fn (WhatsappMessage $message) => $this->messagePayload($message))->values(),
            'contacts' => $this->getContacts($phone),
        ];

        if (! empty($sendErrors)) {
            $payload['error'] = collect($sendErrors)->filter()->unique()->implode(' ');

            return response()->json($payload, 422);
        }

        return response()->json($payload);
    }

    private function providerMediaUrl(string $mediaUrl, array $config = []): string
    {
        if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
            return $mediaUrl;
        }

        $publicBaseUrl = $this->providerPublicBaseUrl($config) ?: rtrim(url('/'), '/');

        return $publicBaseUrl . '/' . ltrim($mediaUrl, '/');
    }

    private function mediaDisplayUrl(?string $mediaUrl): ?string
    {
        if (! $mediaUrl) {
            return null;
        }

        if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
            return $mediaUrl;
        }

        return url(ltrim($mediaUrl, '/'));
    }

    private function displayMediaType(?string $mediaType, ?string $mediaName = null, ?string $mediaUrl = null): ?string
    {
        $extension = strtolower(pathinfo((string) ($mediaName ?: $mediaUrl), PATHINFO_EXTENSION));

        if ($this->isVoiceNote($mediaName, $extension)) {
            return 'audio';
        }

        return $mediaType;
    }

    private function isVoiceNote(?string $mediaName, ?string $extension): bool
    {
        $name = strtolower((string) $mediaName);
        $extension = strtolower((string) $extension);

        return $extension === 'webm' && str_starts_with($name, 'voice-note-');
    }

    private function convertVoiceNoteToOgg(string $destinationPath, string $fileName, string $originalName): ?array
    {
        $ffmpeg = $this->ffmpegBinary();

        if (! $ffmpeg) {
            Log::warning('Voice note conversion skipped because ffmpeg is not available.');

            return null;
        }

        $sourcePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;
        $convertedName = pathinfo($fileName, PATHINFO_FILENAME) . '.ogg';
        $convertedPath = $destinationPath . DIRECTORY_SEPARATOR . $convertedName;

        $command = sprintf(
            '%s -y -i %s -vn -c:a libopus -b:a 32k -ar 48000 -ac 1 -f ogg %s 2>&1',
            escapeshellarg($ffmpeg),
            escapeshellarg($sourcePath),
            escapeshellarg($convertedPath)
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! file_exists($convertedPath) || filesize($convertedPath) <= 0) {
            Log::warning('Voice note conversion failed', [
                'exit_code' => $exitCode,
                'output' => implode("\n", array_slice($output, -8)),
            ]);

            return null;
        }

        @unlink($sourcePath);
        Log::info('Voice note converted for WhatsApp', [
            'source' => $fileName,
            'converted' => $convertedName,
            'size' => filesize($convertedPath),
        ]);

        return [
            'file_name' => $convertedName,
            'relative_path' => 'assets/media/whatsapp/' . $convertedName,
            'media_name' => preg_replace('/\.webm$/i', '.ogg', $originalName) ?: $convertedName,
            'size' => filesize($convertedPath),
        ];
    }

    private function ffmpegBinary(): ?string
    {
        foreach (['ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'] as $candidate) {
            $command = stripos(PHP_OS_FAMILY, 'Windows') === 0
                ? 'where ' . escapeshellarg($candidate) . ' 2>NUL'
                : 'command -v ' . escapeshellarg($candidate) . ' 2>/dev/null';

            exec($command, $output, $exitCode);

            if ($exitCode === 0) {
                return $candidate;
            }
        }

        return null;
    }

    private function providerWebhookUrl(array $config = []): ?string
    {
        $webhookUrl = $config['webhook_url'] ?? url('/api/webhooks/whatsapp');

        if (! $this->isPublicProviderUrl($webhookUrl)) {
            return null;
        }

        return $webhookUrl;
    }

    private function providerPublicBaseUrl(array $config = []): ?string
    {
        $webhookUrl = $config['webhook_url'] ?? null;

        if (! $this->isPublicProviderUrl($webhookUrl)) {
            return null;
        }

        return rtrim(preg_replace('#/api/webhooks/whatsapp/?$#', '', $webhookUrl), '/');
    }

    private function isPublicProviderUrl(?string $url): bool
    {
        if (! $url || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return ! in_array($host, ['localhost', '127.0.0.1', '::1'], true);
    }
}
