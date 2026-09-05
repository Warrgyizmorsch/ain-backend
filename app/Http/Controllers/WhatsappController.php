<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Leads;
use App\Models\Services;
use App\Models\Paper;
use App\Models\Source;
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
        $activePhone = $request->query('phone');

        if ($activePhone) {
            $this->markPhoneMessagesRead($activePhone);
        }

        $contactData = $this->getContactsPaginated($activePhone, 25, 1);
        $contacts = $contactData['contacts'];
        $selectedContact = collect($contacts)->firstWhere('active', true);
        $selectedPhone = $selectedContact['phone'] ?? $activePhone;

        // If activePhone was given but not in top 25 recent, fetch it directly
        if ($activePhone && ! $selectedContact) {
            $singleContactData = $this->getContactsPaginated($activePhone, 1, 1, $activePhone);
            if (! empty($singleContactData['contacts'])) {
                $selectedContact = $singleContactData['contacts'][0];
                $selectedContact['active'] = true;
                array_unshift($contacts, $selectedContact);
            }
        }

        $panelDefinitions = $this->chatPanelDefinitions();
        $enabledPanelKeys = $this->enabledPanelKeys(Auth::id(), array_keys($panelDefinitions));
        $selectedPanel = $request->query('panel');
        $selectedPanel = in_array($selectedPanel, $enabledPanelKeys, true) ? $selectedPanel : null;
        $panelRows = $selectedPanel ? $this->panelRows($selectedPanel) : collect();
        $labels = WhatsappChatLabel::query()->orderBy('name')->get();
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

        $messages = $selectedPhone
            ? WhatsappMessage::query()
                ->where('phone', $selectedPhone)
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
        $hasMoreOlderMessages = ($selectedPhone && $firstMsgId > 0)
            ? WhatsappMessage::query()
                ->where('phone', $selectedPhone)
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

        return view('back-end.whatsapp.chat', [
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
            'existingLead' => $existingLead,
            'existingUser' => $existingUser,
            'hasMoreOlderMessages' => $hasMoreOlderMessages,
            'servicesList' => $servicesList,
            'papersList' => $papersList,
            'sourcesList' => $sourcesList,
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
        $user = User::where('id', $request->input('id'))->first();
        if (!$user) {
            $user = User::where('mobile_no', $mobile)->orWhere('mobile_no', $fullPhone)->first();
        }

        if (!$user) {
            if ($request->filled('email')) {
                $existingUser = User::where('email', $request->input('email'))->first();
                if ($existingUser) {
                    return back()->withInput()->with('error', 'Email already exists with another account.');
                }
            }

            $user = new User();
            $user->email = $request->input('email') ?: 'user' . $mobile . '@gmail.com';
            $user->mobile_no = $mobile;
            $user->name = $request->input('user_name') ?: ('WhatsApp User ' . $mobile);
            $user->countrycode = $countrycode;
            $user->password = Hash::make('user@123');
            $user->role_id = 2;
            $user->refer_id = $request->refer_id ?? null;
            $user->save();
        } else {
            if ($request->filled('email')) {
                $user->email = $request->input('email');
            }
            if ($request->filled('user_name')) {
                $user->name = $request->input('user_name');
            }
            $user->countrycode = $countrycode;
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

        $data = $this->getContactsPaginated($activePhone, $limit, $page, $search);

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
            ->orderByDesc('id');

        $total = $query->count();
        $leads = $query->skip(($page - 1) * $limit)->take($limit)->get();

        $formatted = $leads->map(function ($lead) {
            $statusClass = match(strtolower($lead->l_status ?? '')) {
                'waiting' => 'badge-warning',
                'quote' => 'badge-info',
                'confirmation' => 'badge-primary',
                'converted' => 'badge-success',
                'cancelled', 'cancel' => 'badge-danger',
                default => 'badge-secondary',
            };

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
                'status' => $lead->l_status ?: 'Waiting',
                'status_class' => $statusClass,
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
            'all_leads_url' => route('lead.index') . '?search=' . urlencode($cleanPhone),
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
            ->get(['id', 'order_id', 'emp_id']);

        $leadEmpIds = $matchingLeads->pluck('emp_id')->filter()->all();
        $allUids = array_unique(array_filter(array_merge($userIds, $leadEmpIds)));
        $leadOrderIds = $matchingLeads->pluck('order_id')->filter()->all();
        $leadIds = $matchingLeads->pluck('id')->filter()->all();

        $query = Order::query()
            ->with(['team'])
            ->where(function ($q) use ($allUids, $leadOrderIds, $leadIds) {
                $hasCondition = false;
                if (!empty($allUids)) {
                    $q->whereIn('uid', $allUids);
                    $hasCondition = true;
                }
                if (!empty($leadOrderIds)) {
                    if ($hasCondition) {
                        $q->orWhereIn('order_id', $leadOrderIds);
                    } else {
                        $q->whereIn('order_id', $leadOrderIds);
                        $hasCondition = true;
                    }
                }
                if (!empty($leadIds)) {
                    if ($hasCondition) {
                        $q->orWhereIn('lead_id', $leadIds);
                    } else {
                        $q->whereIn('lead_id', $leadIds);
                    }
                }
            })
            ->where(function ($q) {
                $q->where(function ($noLead) {
                    $noLead->whereDoesntHave('lead')->whereDoesntHave('frontendLead');
                })
                ->orWhereHas('lead', fn ($lq) => $lq->where('is_converted', 1))
                ->orWhereHas('frontendLead', fn ($flq) => $flq->where('is_converted', 1));
            })
            ->orderByDesc('id');

        $total = (!empty($allUids) || !empty($leadOrderIds) || !empty($leadIds)) ? $query->count() : 0;
        $orders = (!empty($allUids) || !empty($leadOrderIds) || !empty($leadIds)) ? $query->skip(($page - 1) * $limit)->take($limit)->get() : collect();

        $formatted = $orders->map(function ($ord) {
            $statusClass = match(strtolower($ord->projectstatus ?? '')) {
                'completed', 'delivered' => 'badge-success',
                'working', 'in progress' => 'badge-warning',
                'failed', 'cancelled' => 'badge-danger',
                default => 'badge-primary',
            };

            $basePriceAmt = is_numeric($ord->amount) ? (float)$ord->amount : 0;
            $recvPriceAmt = is_numeric($ord->received_amount) ? (float)$ord->received_amount : 0;
            $calcDueAmt = max(0, $basePriceAmt - $recvPriceAmt);

            $deadlineDate = null;
            if (!empty($ord->delivery_date)) {
                $dateTimeString = $ord->delivery_date;
                if (!empty($ord->delivery_time)) {
                    $dateTimeString .= ' ' . $ord->delivery_time;
                }
                try {
                    $deadlineDate = Carbon::parse($dateTimeString);
                } catch (\Exception $e) {
                    $deadlineDate = null;
                }
            }
            $isOverdue = $deadlineDate && $deadlineDate->isPast() && !in_array(strtolower($ord->projectstatus ?? ''), ['delivered', 'completed']);

            $orderDateStr = !empty($ord->order_date) && strtotime($ord->order_date)
                ? Carbon::parse($ord->order_date)->format('d M Y')
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
                if (!empty($ord->delivery_time)) {
                    try {
                        $deliveryDateFormatted .= ' (' . Carbon::parse($ord->delivery_time)->format('H:i') . ')';
                    } catch (\Exception $e) {
                        $deliveryDateFormatted .= ' (' . $ord->delivery_time . ')';
                    }
                }
            } elseif (!empty($ord->delivery_date) && strtotime($ord->delivery_date)) {
                $deliveryDateFormatted = date('d M Y', strtotime($ord->delivery_date));
            }

            $feedbackDateStr = !empty($ord->f_delivery_date) && strtotime($ord->f_delivery_date)
                ? Carbon::parse($ord->f_delivery_date)->format('d M Y')
                : null;

            $failedDateStr = !empty($ord->failed_at) && strtotime($ord->failed_at)
                ? Carbon::parse($ord->failed_at)->format('d M Y H:i A')
                : null;

            return [
                'id' => $ord->id,
                'order_id' => $ord->order_id ?: (string) $ord->id,
                'title' => $ord->title ?: 'N/A',
                'service_type' => $ord->service_type ?: 'General',
                'pages' => $ord->pages ? number_format($ord->pages) : '—',
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
                'services' => $ord->services ?? null,
                'semester' => $ord->semester ?? null,
                'offer' => $ord->offer ?? null,
                'marks' => $ord->marks ?? null,
                'team_name' => $ord->team?->team_name ?? null,
                'looking_for_refund' => (int) ($ord->looking_for_refund ?? 0),
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
            'all_orders_url' => route('order') . '?search=' . urlencode($cleanPhone),
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
            'phone' => ['required', 'string', 'max:30'],
            'labels' => ['nullable'],
        ]);

        $phone = $validated['phone'];
        $rawLabels = $request->input('labels', []);
        $labelIds = [];
        if (is_array($rawLabels)) {
            $isAssoc = array_keys($rawLabels) !== range(0, count($rawLabels) - 1);
            if ($isAssoc) {
                $labelIds = collect($rawLabels)->filter()->keys()->map(fn ($id) => (int) $id)->values()->all();
            } else {
                $labelIds = collect($rawLabels)->map(fn ($id) => (int) $id)->values()->all();
            }
        }

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

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $assignedLabels = WhatsappChatLabel::query()->whereIn('id', $labelIds)->get(['id', 'name', 'color']);
            return response()->json([
                'success' => true,
                'message' => 'Chat labels saved successfully.',
                'phone' => $phone,
                'label_ids' => $labelIds,
                'labels' => $assignedLabels,
            ]);
        }

        return redirect()->route('whatsapp.chat', ['phone' => $phone])->with('success', 'Chat labels saved.');
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

    private function getContactsPaginated(?string $activePhone, int $limit = 25, int $page = 1, ?string $search = null): array
    {
        $query = DB::table('whatsapp_messages as latest')
            ->join(DB::raw('(SELECT phone, MAX(id) as max_id FROM whatsapp_messages GROUP BY phone) as grouped'), function ($join) {
                $join->on('latest.id', '=', 'grouped.max_id');
            })
            ->select('latest.phone', 'latest.name', 'latest.message', 'latest.media_type', 'latest.media_name', 'latest.created_at', 'latest.id');

        if ($search !== null && $search !== '') {
            $cleanSearch = preg_replace('/\D+/', '', $search);
            $matchedUserPhones = User::query()
                ->where('name', 'like', "%{$search}%")
                ->orWhere('mobile_no', 'like', "%{$search}%")
                ->pluck('mobile_no')
                ->filter()
                ->values()
                ->all();

            $query->where(function ($q) use ($search, $cleanSearch, $matchedUserPhones) {
                $q->where('latest.name', 'like', "%{$search}%")
                  ->orWhere('latest.phone', 'like', "%{$search}%");
                if ($cleanSearch !== '') {
                    $q->orWhere('latest.phone', 'like', "%{$cleanSearch}%");
                }
                if (!empty($matchedUserPhones)) {
                    $q->orWhereIn('latest.phone', $matchedUserPhones);
                }
            });
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

        $allLabels = WhatsappChatLabel::query()->get()->keyBy('id');

        $contacts = $latestMessages->map(function ($contact, int $index) use ($userMap, $activePhone, $unreadCounts, $labelsMap, $allLabels, $page, $limit) {
            $user = $userMap[$contact->phone] ?? null;
            $name = $user?->name ?: ($contact->name ?: $contact->phone);
            $unreadCount = (int) ($unreadCounts[$contact->phone] ?? 0);
            $globalIndex = (($page - 1) * $limit) + $index;
            $contactLabelIds = $labelsMap[$contact->phone] ?? [];
            $contactLabels = collect($contactLabelIds)->map(fn($id) => $allLabels->get($id))->filter()->values();

            return [
                'id' => $contact->id,
                'phone' => $contact->phone,
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
            ];
        })->values()->toArray();

        return [
            'contacts' => $contacts,
            'total' => count($contacts),
            'has_more' => $hasMore,
        ];
    }

    private function getCustomerSummary(string $phone): array
    {
        $variants = $this->getPhoneVariants($phone);

        $existingUser = User::query()
            ->whereIn('mobile_no', $variants)
            ->first();

        $existingLead = Leads::query()
            ->whereIn('mobile', $variants)
            ->latest('id')
            ->first();

        $labelIds = WhatsappChatContactLabel::query()
            ->whereIn('phone', $variants)
            ->pluck('label_id')
            ->unique()
            ->all();

        $labels = !empty($labelIds)
            ? WhatsappChatLabel::query()->whereIn('id', $labelIds)->get(['id', 'name', 'color'])
            : collect();

        return [
            'name' => $existingUser?->name ?? ($existingLead?->user_name ?? $phone),
            'phone' => $phone,
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
                'countrycode' => $existingUser->countrycode,
                'mobile_no' => $existingUser->mobile_no,
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
