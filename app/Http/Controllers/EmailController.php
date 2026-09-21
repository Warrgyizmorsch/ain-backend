<?php

namespace App\Http\Controllers;

use App\Models\EmailMessage;
use App\Models\EmailAttachment;
use App\Models\EmailConfiguration;
use App\Models\User;
use App\Services\EmailService;
use App\Services\EmailHtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService, EmailHtmlSanitizer $htmlSanitizer)
    {
        $this->emailService = $emailService;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    protected EmailHtmlSanitizer $htmlSanitizer;

    /**
     * Show Email Inbox / Threaded UI
     */
    public function index(Request $request)
    {
        $folder = $request->get('folder', 'inbox');
        $search = $request->get('search') ?? $request->get('q');
        $selectedThreadId = $request->get('thread_id');
        $accountId = $request->get('account_id');
        $selectedLabelId = $request->integer('label_id') ?: null;

        // Query based on folder
        $query = EmailMessage::query();

        switch ($folder) {
            case 'all':
            case 'all_mail':
                $query->where('folder', '!=', 'trash');
                break;
            case 'sent':
                $query->where(function ($q) {
                    $q->where('folder', 'sent')
                      ->orWhere('direction', 'outbound');
                })->where('is_draft', false)->where('folder', '!=', 'trash');
                break;
            case 'draft':
            case 'drafts':
                $query->where(function ($q) {
                    $q->where('folder', 'drafts')
                      ->orWhere('is_draft', true);
                })->where('folder', '!=', 'trash');
                break;
            case 'starred':
                $query->where('is_starred', true)->where('folder', '!=', 'trash');
                break;
            case 'trash':
                $query->where('folder', 'trash');
                break;
            case 'inbox':
            default:
                if (!empty($search)) {
                    $query->where('folder', '!=', 'trash');
                } else {
                    $query->where(function ($q) {
                        $q->where('folder', 'inbox')
                          ->orWhere('direction', 'inbound');
                    })->where('is_draft', false)->where('folder', '!=', 'trash');
                }
                break;
        }

        // Filter by specific configured account if selected or default to active account
        $configurations = EmailConfiguration::where('is_active', true)->get();
        $selectedAccount = null;
        if ($accountId) {
            $selectedAccount = $configurations->firstWhere('id', (int) $accountId) ?: EmailConfiguration::find($accountId);
        } elseif (session()->has('active_email_account_id')) {
            $sessAccountId = session('active_email_account_id');
            $selectedAccount = $configurations->firstWhere('id', (int) $sessAccountId) ?: EmailConfiguration::find($sessAccountId);
        }
        if (!$selectedAccount && $configurations->isNotEmpty()) {
            $selectedAccount = $configurations->first();
            $accountId = $selectedAccount?->id;
        }

        if ($selectedAccount) {
            session(['active_email_account_id' => $selectedAccount->id]);
            $accountId = $selectedAccount->id;
            $query->where('email_configuration_id', $selectedAccount->id);
        }

        if (!empty($search)) {
            $cleanSearch = trim($search);
            $terms = collect(preg_split('/\s+/', $cleanSearch))->filter()->values();
            $matchingContactEmails = [];

            // Only search users table if query is an email address or customer name, not an alphanumeric order code like UKS60312
            if (filter_var($cleanSearch, FILTER_VALIDATE_EMAIL)) {
                $matchingContactEmails = User::query()->where('email', $cleanSearch)->limit(5)->pluck('email')->all();
            } elseif (!preg_match('/^[a-zA-Z]{1,5}\d+$/', $cleanSearch) && strlen($cleanSearch) >= 3) {
                $matchingContactEmails = User::query()
                    ->where(function ($userQuery) use ($cleanSearch) {
                        $userQuery->where('name', 'like', "%{$cleanSearch}%")
                            ->orWhere('email', 'like', "%{$cleanSearch}%")
                            ->orWhere('mobile_no', 'like', "%{$cleanSearch}%");
                    })
                    ->whereNotNull('email')
                    ->limit(20)
                    ->pluck('email')
                    ->filter()
                    ->all();
            }

            $query->where(function ($deepQuery) use ($terms, $matchingContactEmails) {
                foreach ($terms as $term) {
                    $deepQuery->where(function ($fieldQuery) use ($term, $matchingContactEmails) {
                        $like = "%{$term}%";
                        $fieldQuery->where('subject', 'like', $like)
                            ->orWhere('from_email', 'like', $like)
                            ->orWhere('from_name', 'like', $like)
                            ->orWhere('to_email', 'like', $like)
                            ->orWhere('to_name', 'like', $like)
                            ->orWhere('cc', 'like', $like)
                            ->orWhere('bcc', 'like', $like)
                            ->orWhere('body_plain', 'like', $like)
                            ->orWhere('body_html', 'like', $like);

                        if (!empty($matchingContactEmails)) {
                            $fieldQuery->orWhereIn('from_email', $matchingContactEmails)
                                ->orWhereIn('to_email', $matchingContactEmails);
                        }
                    });
                }
            });
        }

        // Filter by labels created in Label Master. Labels belong to an email
        // conversation, so every message in the matching thread is included.
        if ($selectedLabelId) {
            $query->whereExists(function ($labelQuery) use ($selectedLabelId) {
                $labelQuery->selectRaw('1')
                    ->from('email_thread_labels')
                    ->whereColumn('email_thread_labels.thread_id', 'email_messages.thread_id')
                    ->where('email_thread_labels.label_id', $selectedLabelId);
            });
        }

        // ── Quick & Advanced Filters ──────────────────────────────────────
        // 1. Has Attachment
        if ($request->boolean('has_attachment') || $request->input('has_attachment') === '1') {
            $query->where(function($q) {
                $q->where('has_attachments', true)
                  ->orWhereHas('attachments');
            });
        }

        // 2. From Me (Outbound / Sent)
        if ($request->boolean('from_me') || $request->input('from_me') === '1') {
            $query->where(function ($q) use ($selectedAccount) {
                $q->where('direction', 'outbound')
                  ->orWhere('folder', 'sent');
                if ($selectedAccount && !empty($selectedAccount->email_address)) {
                    $q->orWhere('from_email', $selectedAccount->email_address);
                }
            });
        }

        // 3. Read / Unread Status
        if ($request->has('is_read')) {
            $isReadVal = $request->input('is_read');
            if ($isReadVal === 'unread' || $isReadVal === '0' || $isReadVal === false) {
                $query->where('is_read', false);
            } elseif ($isReadVal === 'read' || $isReadVal === '1' || $isReadVal === true) {
                $query->where('is_read', true);
            }
        }

        // 4. Date Range (e.g. last_7_days, last_30_days, last_year)
        $dateRange = $request->input('date_range');
        if ($dateRange === 'last_7_days') {
            $query->where(function ($q) {
                $q->where('received_at', '>=', now()->subDays(7))
                  ->orWhere('created_at', '>=', now()->subDays(7));
            });
        } elseif ($dateRange === 'last_30_days') {
            $query->where(function ($q) {
                $q->where('received_at', '>=', now()->subDays(30))
                  ->orWhere('created_at', '>=', now()->subDays(30));
            });
        } elseif ($dateRange === 'last_year') {
            $query->where(function ($q) {
                $q->where('received_at', '>=', now()->subYear())
                  ->orWhere('created_at', '>=', now()->subYear());
            });
        }

        // 5. Advanced Filter Popup fields
        if ($request->filled('filter_from')) {
            $fromTerm = trim($request->input('filter_from'));
            $query->where(function($q) use ($fromTerm) {
                $q->where('from_email', 'like', "%{$fromTerm}%")
                  ->orWhere('from_name', 'like', "%{$fromTerm}%");
            });
        }

        if ($request->filled('filter_to')) {
            $toTerm = trim($request->input('filter_to'));
            $query->where(function($q) use ($toTerm) {
                $q->where('to_email', 'like', "%{$toTerm}%")
                  ->orWhere('to_name', 'like', "%{$toTerm}%");
            });
        }

        if ($request->filled('filter_subject')) {
            $subjTerm = trim($request->input('filter_subject'));
            $query->where('subject', 'like', "%{$subjTerm}%");
        }

        if ($request->filled('filter_words')) {
            $wordsTerm = trim($request->input('filter_words'));
            $query->where(function($q) use ($wordsTerm) {
                $q->where('subject', 'like', "%{$wordsTerm}%")
                  ->orWhere('body_plain', 'like', "%{$wordsTerm}%")
                  ->orWhere('body_html', 'like', "%{$wordsTerm}%");
            });
        }

        if ($request->filled('filter_doesnt_have')) {
            $notTerm = trim($request->input('filter_doesnt_have'));
            $query->where('subject', 'not like', "%{$notTerm}%")
                  ->where('body_plain', 'not like', "%{$notTerm}%");
        }

        if ($request->filled('filter_date_within')) {
            $dateWithin = $request->input('filter_date_within'); // 1d, 3d, 7d, 14d, 1m, 2m, 6m, 1y
            $refDate = $request->filled('filter_date_ref') ? \Carbon\Carbon::parse($request->input('filter_date_ref')) : now();
            $days = match($dateWithin) {
                '1d' => 1,
                '3d' => 3,
                '7d' => 7,
                '14d' => 14,
                '1m' => 30,
                '2m' => 60,
                '6m' => 180,
                '1y' => 365,
                default => 7,
            };
            $query->whereBetween('created_at', [$refDate->copy()->subDays($days)->startOfDay(), $refDate->copy()->addDays($days)->endOfDay()]);
        }

        // Fetch latest message per thread to display in list (lightweight 20 per page)
        $threadsQuery = clone $query;
        $latestMessageIds = $threadsQuery->selectRaw('MAX(id) as id')
            ->groupBy('thread_id')
            ->pluck('id')
            ->filter()
            ->map(fn($v) => (int)$v)
            ->values()
            ->all();

        $totalThreads = count($latestMessageIds);
        rsort($latestMessageIds); // Most recent message IDs first

        $page = max(1, (int) $request->input('page', 1));
        $perPage = 20;
        $pageIds = array_slice($latestMessageIds, ($page - 1) * $perPage, $perPage);

        $threadsCollection = !empty($pageIds)
            ? EmailMessage::select([
                    'id', 'thread_id', 'from_email', 'from_name', 'to_email', 'to_name',
                    'subject', 'body_plain', 'folder', 'direction', 'status',
                    'is_read', 'is_starred', 'is_draft', 'has_attachments', 'received_at', 'created_at'
                ])
                ->whereIn('id', $pageIds)
                ->orderByDesc('id')
                ->get()
            : collect();

        $threads = new \Illuminate\Pagination\LengthAwarePaginator(
            $threadsCollection,
            $totalThreads,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $allLabels = \App\Models\WhatsappChatLabel::forEmail()->ordered()->get();
        $threadIds = $threadsCollection->pluck('thread_id')->filter()->unique()->all();
        $threadLabelsMap = !empty($threadIds)
            ? \App\Models\EmailThreadLabel::with('label')
                ->whereIn('thread_id', $threadIds)
                ->get()
                ->groupBy('thread_id')
            : collect();

        $emailClientContacts = $this->clientContactsForEmails($threadsCollection);

        if ($request->ajax() && $request->boolean('partial')) {
            return response(
                view('emails._rows', compact('emailClientContacts', 'threadLabelsMap', 'allLabels') + ['emails' => $threads, 'isAppend' => false])->render()
            )->withHeaders([
                'Cache-Control' => 'no-store, private',
                'X-Email-Partial' => 'rows',
            ]);
        }

        if ($request->ajax() && ($request->get('scroll') == '1' || $request->has('page'))) {
            return response()->json([
                'success' => true,
                'html' => view('emails._rows', compact('emailClientContacts', 'threadLabelsMap', 'allLabels') + ['emails' => $threads, 'isAppend' => true])->render(),
                'has_more' => $threads->hasMorePages(),
                'current_page' => $threads->currentPage(),
                'total' => $threads->total(),
            ]);
        }

        // Calculate accurate count stats for sidebar badges in a single fast query
        $countsQuery = EmailMessage::query();
        if ($selectedAccount && !empty($selectedAccount->email_address)) {
            $countsQuery->where('email_configuration_id', $selectedAccount->id);
        }

        $stats = $countsQuery->selectRaw("
            COUNT(CASE WHEN folder != 'trash' THEN 1 END) as all_count,
            COUNT(CASE WHEN direction = 'inbound' AND folder != 'trash' AND is_read = 0 THEN 1 END) as inbox_count,
            COUNT(CASE WHEN (folder = 'sent' OR direction = 'outbound') AND is_draft = 0 AND folder != 'trash' THEN 1 END) as sent_count,
            COUNT(CASE WHEN (folder = 'drafts' OR is_draft = 1) AND folder != 'trash' THEN 1 END) as drafts_count,
            COUNT(CASE WHEN is_starred = 1 AND folder != 'trash' THEN 1 END) as starred_count,
            COUNT(CASE WHEN folder = 'trash' THEN 1 END) as trash_count
        ")->first();

        $counts = [
            'all' => (int) ($stats->all_count ?? 0),
            'inbox' => (int) ($stats->inbox_count ?? 0),
            'sent' => (int) ($stats->sent_count ?? 0),
            'drafts' => (int) ($stats->drafts_count ?? 0),
            'starred' => (int) ($stats->starred_count ?? 0),
            'trash' => (int) ($stats->trash_count ?? 0),
        ];

        // Thread details are loaded on demand by openEmailThread(); do not pull large
        // message bodies into the initial inbox response.
        $activeThread = null;
        $activeMessages = collect();

        $configurations = EmailConfiguration::where('is_active', true)->get();
        $currentAccount = $selectedAccount;

        // If no account is explicitly selected and configurations exist, default to the first active account or selected
        $unreadCount = $counts['inbox'] ?? 0;
        $emails = $threads;

        // Only pre-render folder cache on fresh default inbox loads (skip on search to make opening emails instant)
        $folderHtmlCache = [];
        if (empty($search)) {
            $cacheSource = EmailMessage::select([
                    'id', 'thread_id', 'from_email', 'from_name', 'to_email', 'to_name',
                    'subject', 'body_plain', 'folder', 'direction', 'status',
                    'is_read', 'is_starred', 'is_draft', 'has_attachments', 'received_at', 'created_at'
                ])
                ->when($selectedAccount, fn ($q) => $q->where('email_configuration_id', $selectedAccount->id))
                ->orderByDesc('id')
                ->limit(100)
                ->get();

            $cacheThreadIds = $cacheSource->pluck('thread_id')->filter()->unique()->all();
            $cacheThreadLabelsMap = !empty($cacheThreadIds)
                ? \App\Models\EmailThreadLabel::with('label')
                    ->whereIn('thread_id', $cacheThreadIds)
                    ->get()
                    ->groupBy('thread_id')
                : collect();

            $cacheClientContacts = $this->clientContactsForEmails(
                $cacheSource->concat($threadsCollection)
            );

            foreach (['inbox', 'all', 'sent', 'drafts', 'starred', 'trash'] as $cacheFolder) {
                $folderMessages = $cacheSource->filter(function ($message) use ($cacheFolder) {
                    return match ($cacheFolder) {
                        'all' => $message->folder !== 'trash',
                        'sent' => ($message->folder === 'sent' || $message->direction === 'outbound')
                            && !$message->is_draft && $message->folder !== 'trash',
                        'drafts' => ($message->folder === 'drafts' || $message->is_draft)
                            && $message->folder !== 'trash',
                        'starred' => $message->is_starred && $message->folder !== 'trash',
                        'trash' => $message->folder === 'trash',
                        default => ($message->folder === 'inbox' || $message->direction === 'inbound')
                            && !$message->is_draft && $message->folder !== 'trash',
                    };
                })->unique('thread_id')->take(20)->values();

                $folderHtmlCache[$cacheFolder] = view('emails._rows', [
                    'emails' => $folderMessages,
                    'isAppend' => false,
                    'emailClientContacts' => $cacheClientContacts,
                    'threadLabelsMap' => $cacheThreadLabelsMap,
                    'allLabels' => $allLabels,
                ])->render();
            }
        }

        return view('emails.inbox', compact(
            'threads',
            'emails',
            'counts',
            'folder',
            'search',
            'selectedThreadId',
            'activeThread',
            'activeMessages',
            'configurations',
            'currentAccount',
            'accountId',
            'selectedLabelId',
            'unreadCount',
            'folderHtmlCache',
            'allLabels',
            'threadLabelsMap',
            'emailClientContacts'
        ));
    }

    /**
     * Format countrycode and mobile number into a clean digits-only phone string
     * suitable for WhatsApp URL (e.g. 447490902601).
     */
    public static function formatWhatsAppPhone(?string $countryCode, ?string $mobile): ?string
    {
        if (empty($mobile)) return null;
        $mob = preg_replace('/\D+/', '', (string) $mobile);
        if (empty($mob)) return null;
        $cc = preg_replace('/\D+/', '', (string) $countryCode);

        // Strip leading zeros if countrycode is present (e.g. 07490902601 with CC 44 -> 7490902601)
        if (!empty($cc) && str_starts_with($mob, '0')) {
            $mob = ltrim($mob, '0');
        }

        if (!empty($cc)) {
            if (str_starts_with($mob, $cc)) {
                return $mob;
            }
            return $cc . $mob;
        }

        return $mob;
    }

    /**
     * Match each conversation's external email address to a registered client or lead.
     * Searches users table first, then falls back to leads table to ensure WhatsApp
     * phone number is resolved for all clients.
     */
    private function clientContactsForEmails($emails): \Illuminate\Support\Collection
    {
        $addresses = collect($emails)
            ->map(function ($email) {
                if ($email instanceof EmailMessage) {
                    return $email->customer_email ?: ($email->from_email ?: $email->to_email);
                }
                return is_string($email) ? $email : null;
            })
            ->filter()
            ->map(fn ($email) => strtolower(trim(EmailMessage::extractCleanEmail($email) ?: $email)))
            ->unique()
            ->values();

        if ($addresses->isEmpty()) {
            return collect();
        }

        // Match only registered clients/users from the users table with valid mobile number
        return User::query()
            ->whereIn('email', $addresses->all())
            ->whereNotNull('mobile_no')
            ->where('mobile_no', '!=', '')
            ->orderByRaw("CASE WHEN role_id = 2 THEN 0 ELSE 1 END")
            ->get(['id', 'email', 'name', 'countrycode', 'mobile_no'])
            ->keyBy(fn (User $user) => strtolower(trim($user->email)));
    }

    /** Lightweight real-time change & new email detector. */
    public function updates(Request $request)
    {
        $accountId = $request->filled('account_id') ? $request->integer('account_id') : session('active_email_account_id');

        $query = EmailMessage::query();
        if ($accountId) {
            $query->where('email_configuration_id', (int) $accountId);
        }

        $latest = (clone $query)->select([
                'id', 'thread_id', 'from_name', 'from_email', 'to_name', 'to_email',
                'subject', 'body_plain', 'folder', 'direction', 'is_read', 'created_at'
            ])
            ->orderByDesc('id')
            ->first();

        $unreadCount = EmailMessage::where('direction', 'inbound')
            ->where('folder', '!=', 'trash')
            ->where('is_read', false)
            ->when($accountId, fn ($q) => $q->where('email_configuration_id', (int) $accountId))
            ->count();

        $state = (clone $query)->selectRaw('COALESCE(MAX(id), 0) as latest_id, COALESCE(MAX(UNIX_TIMESTAMP(updated_at)), 0) as latest_update, COUNT(*) as total')
            ->first();

        return response()->json([
            'fingerprint' => implode(':', [$state->latest_id, $state->latest_update, $state->total]),
            'latest_id' => (int) ($state->latest_id ?? 0),
            'unread_count' => $unreadCount,
            'latest_email' => $latest ? [
                'id' => $latest->id,
                'thread_id' => $latest->thread_id,
                'from_name' => $latest->from_name ?: $latest->from_email,
                'from_email' => $latest->from_email,
                'subject' => $latest->subject ?: '(No Subject)',
                'preview' => Str::limit($latest->body_plain, 80),
                'direction' => $latest->direction,
                'folder' => $latest->folder,
                'created_at' => optional($latest->created_at)->diffForHumans(),
            ] : null,
        ])->header('Cache-Control', 'no-store, private');
    }

    public function csrfToken()
    {
        return response()->json(['token' => csrf_token()])
            ->header('Cache-Control', 'no-store, private');
    }

    /**
     * Show single email message details via JSON for detail reading pane
     */
    /**
     * Show single email message details on dedicated page or via JSON
     */
    public function show($id, Request $request)
    {
        $email = EmailMessage::with('attachments')->find($id);
        if (!$email) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => 'Email not found'], 404);
            }
            return redirect()->route('emails.index')->with('error', 'Email not found');
        }

        // Mark this email and thread as read immediately
        if (!$email->is_read) {
            $email->update(['is_read' => true]);
            if (!empty($email->thread_id)) {
                EmailMessage::where('thread_id', $email->thread_id)->update(['is_read' => true]);
            }
        }

        // Fetch all messages in this conversation thread safely
        if (!empty($email->thread_id)) {
            $threadMessages = EmailMessage::with('attachments')
                ->where('thread_id', $email->thread_id)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $threadMessages = collect([$email]);
        }

        $isSuperAdmin = Auth::check() && (int) Auth::user()->role_id === 1;

        // Fetch labels attached to this thread
        $customerEmail = $email->customer_email ?: $email->from_email;
        $contacts = $this->clientContactsForEmails(collect([$email]));
        $clientContact = $contacts->get(strtolower((string) $customerEmail))
            ?: ($email->from_email ? $contacts->get(strtolower((string) $email->from_email)) : null);
        $whatsAppPhone = null;
        $clientWhatsAppUrl = null;
        if ($clientContact && !empty($clientContact->mobile_no)) {
            $whatsAppPhone = self::formatWhatsAppPhone($clientContact->countrycode ?? '', $clientContact->mobile_no);
            if (!empty($whatsAppPhone)) {
                $clientWhatsAppUrl = route('whatsapp.chat', ['phone' => $whatsAppPhone]);
            }
        }
        $fallbackWhatsAppUrl = $clientWhatsAppUrl;

        $threadLabelIds = \App\Models\EmailThreadLabel::where('thread_id', $email->thread_id)
            ->when(empty($email->thread_id) && !empty($customerEmail), function($q) use ($customerEmail) {
                $q->orWhere('email', $customerEmail);
            })
            ->pluck('label_id')
            ->unique()
            ->all();

        $threadLabels = \App\Models\WhatsappChatLabel::whereIn('id', $threadLabelIds)
            ->ordered()
            ->get(['id', 'name', 'color']);

        $allLabels = \App\Models\WhatsappChatLabel::forEmail()->ordered()->get();

        if ($request->ajax() || $request->wantsJson()) {
            $maskEmail = fn(?string $val) => $isSuperAdmin ? (string)$val : mask_email_for_display($val);
            $maskName = function(?string $name, ?string $fallbackEmail) use ($isSuperAdmin) {
                if ($isSuperAdmin) return $name ?: $fallbackEmail;
                if (!empty($name) && filter_var($name, FILTER_VALIDATE_EMAIL)) {
                    return mask_email_for_display($name);
                }
                return $name ?: mask_email_for_display($fallbackEmail);
            };

            return response()->json([
                'success' => true,
                'is_super_admin' => $isSuperAdmin,
                'email' => [
                    'id' => $email->id,
                    'thread_id' => $email->thread_id,
                    'message_id' => $email->message_id,
                    'from_name' => $maskName($email->from_name, $email->from_email),
                    'from_email' => $maskEmail($email->from_email),
                    'to_name' => $maskName($email->to_name, $email->to_email),
                    'to_email' => $maskEmail($email->to_email),
                    'raw_from_email' => $email->from_email,
                    'raw_to_email' => $email->to_email,
                    'customer_email' => $maskEmail($customerEmail),
                    'client_name' => $clientContact?->name,
                    'whatsapp_phone' => $whatsAppPhone,
                    'whatsapp_url' => $fallbackWhatsAppUrl,
                    'cc' => $email->cc,
                    'bcc' => $email->bcc,
                    'subject' => $email->subject,
                    'body_html' => $this->formatIsolatedBodyHtml($email->body_html, $email->body_plain),
                    'body_plain' => $email->body_plain,
                    'direction' => $email->direction,
                    'folder' => $email->folder,
                    'status' => $email->status,
                    'is_read' => true,
                    'is_starred' => (bool) $email->is_starred,
                    'is_draft' => (bool) $email->is_draft,
                    'labels' => $threadLabels,
                    'received_at' => optional($email->received_at ?: $email->created_at)->format('M d, Y h:i A'),
                    'created_at' => optional($email->created_at)->format('M d, Y h:i A'),
                    'attachments' => $email->attachments->map(function ($att) {
                        return [
                            'id' => $att->id,
                            'filename' => $att->filename,
                            'file_size' => $att->formatted_size,
                            'mime_type' => $att->mime_type,
                            'url' => route('emails.attachment.download', $att->id),
                        ];
                    }),
                ],
                'labels' => $threadLabels,
                'all_labels' => $allLabels,
                'messages' => $threadMessages->map(function ($msg) use ($maskEmail, $maskName, $fallbackWhatsAppUrl, $whatsAppPhone) {
                    return [
                        'id' => $msg->id,
                        'from_name' => $maskName($msg->from_name, $msg->from_email),
                        'from_email' => $maskEmail($msg->from_email),
                        'to_name' => $maskName($msg->to_name, $msg->to_email),
                        'to_email' => $maskEmail($msg->to_email),
                        'raw_from_email' => $msg->from_email,
                        'whatsapp_phone' => $whatsAppPhone,
                        'whatsapp_url' => $fallbackWhatsAppUrl,
                        'subject' => $msg->subject,
                        'snippet' => $this->getCleanSnippet($msg, 120),
                        'body_html' => $this->formatIsolatedBodyHtml($msg->body_html, $msg->body_plain),
                        'body_plain' => $msg->body_plain,
                        'direction' => $msg->direction,
                        'is_starred' => (bool) $msg->is_starred,
                        'date_formatted' => optional($msg->received_at ?: $msg->created_at)->format('M d, h:i A'),
                        'attachments' => $msg->attachments->map(function ($att) {
                            return [
                                'id' => $att->id,
                                'filename' => $att->filename,
                                'file_size' => $att->formatted_size,
                                'mime_type' => $att->mime_type,
                                'url' => route('emails.attachment.download', $att->id),
                            ];
                        }),
                    ];
                }),
            ]);
        }

        $configurations = EmailConfiguration::where('is_active', true)->get();
        $currentAccount = null;
        $accountId = $request->get('account_id');
        if ($accountId) {
            $currentAccount = EmailConfiguration::find($accountId);
        }

        $stats = EmailMessage::selectRaw("
            COUNT(CASE WHEN direction = 'inbound' AND folder != 'trash' AND is_read = 0 THEN 1 END) as inbox_count,
            COUNT(CASE WHEN (folder = 'sent' OR direction = 'outbound') AND is_draft = 0 AND folder != 'trash' THEN 1 END) as sent_count,
            COUNT(CASE WHEN (folder = 'drafts' OR is_draft = 1) AND folder != 'trash' THEN 1 END) as drafts_count,
            COUNT(CASE WHEN is_starred = 1 AND folder != 'trash' THEN 1 END) as starred_count,
            COUNT(CASE WHEN folder = 'trash' THEN 1 END) as trash_count
        ")->first();

        $counts = [
            'inbox' => (int) ($stats->inbox_count ?? 0),
            'sent' => (int) ($stats->sent_count ?? 0),
            'drafts' => (int) ($stats->drafts_count ?? 0),
            'starred' => (int) ($stats->starred_count ?? 0),
            'trash' => (int) ($stats->trash_count ?? 0),
        ];

        return view('emails.show', compact(
            'email',
            'threadMessages',
            'configurations',
            'currentAccount',
            'counts',
            'threadLabels',
            'allLabels',
            'threadLabelIds',
            'clientContact',
            'clientWhatsAppUrl',
            'fallbackWhatsAppUrl',
            'whatsAppPhone',
            'isSuperAdmin'
        ));
    }

    /**
     * Toggle star by email ID
     */
    public function toggleStarById($id)
    {
        $msg = EmailMessage::find($id);
        if (!$msg) {
            return response()->json(['success' => false, 'error' => 'Message not found'], 404);
        }
        $msg->is_starred = !$msg->is_starred;
        $msg->save();

        return response()->json(['success' => true, 'is_starred' => $msg->is_starred]);
    }

    /**
     * Delete email by ID
     */
    public function deleteById($id)
    {
        $msg = EmailMessage::find($id);
        if ($msg) {
            $msg->update(['folder' => 'trash']);
        }
        return response()->json(['success' => true, 'message' => 'Email moved to Trash']);
    }

    /**
     * Send Quick Reply to an existing email / thread
     */
    public function replyToMessage(Request $request, $id)
    {
        $parent = EmailMessage::find($id);
        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Message not found'], 404);
        }

        $bodyHtml = $request->input('body_html');
        if (empty($bodyHtml)) {
            return response()->json(['success' => false, 'message' => 'Reply content is required'], 422);
        }

        // Determine recipient & subject
        $to = $parent->from_email;
        $subject = 'Re: ' . preg_replace('/^(Re:\s*)+/i', '', $parent->subject ?? '(No Subject)');

        try {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'body_html' => $bodyHtml,
                'thread_id' => $parent->thread_id,
                'in_reply_to' => $parent->message_id,
                'account_id' => $parent->email_configuration_id,
            ];

            $this->emailService->sendEmail($data);

            return response()->json(['success' => true, 'message' => 'Reply delivered successfully!']);
        } catch (\Exception $e) {
            \Log::error('Email reply failed.', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Reply delivery failed. Check the account connection.'], 500);
        }
    }

    /**
     * Send Outbound Email
     */
    public function send(Request $request)
    {
        $request->merge(['to' => $request->input('to') ?: $request->input('to_email')]);
        $validated = $request->validate([
            'to' => ['required', 'string', 'max:2000', function ($attribute, $value, $fail) {
                foreach (array_filter(array_map('trim', explode(',', $value))) as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $fail("Invalid recipient: {$email}");
                }
            }],
            'cc' => 'nullable|string|max:2000',
            'bcc' => 'nullable|string|max:2000',
            'subject' => 'nullable|string|max:998',
            'body_html' => 'nullable|string|max:2000000',
            'body' => 'nullable|string|max:2000000',
            'account_id' => 'nullable|integer|exists:email_configurations,id',
            'draft_id' => 'nullable|integer|exists:email_messages,id',
            'attachments.*' => 'file|max:20480',
            'files.*' => 'file|max:20480',
        ]);
        $to = $validated['to'];

        try {
            $files = $request->file('attachments', $request->file('files', []));
            $data = [
                'to' => $to,
                'to_name' => $request->input('to_name'),
                'account_id' => $request->input('account_id'),
                'subject' => $request->input('subject') ?: '(No Subject)',
                'body_html' => $this->htmlSanitizer->sanitize($request->input('body_html') ?: $request->input('body', '')),
                'cc' => $request->input('cc'),
                'bcc' => $request->input('bcc'),
                'thread_id' => $request->input('thread_id'),
                'in_reply_to' => $request->input('in_reply_to'),
                'draft_id' => $request->input('draft_id'),
            ];

            $emailMsg = $this->emailService->sendEmail($data, $files);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.',
                'thread_id' => $emailMsg->thread_id,
                'message_id' => $emailMsg->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Send Email Controller Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Email delivery failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save / Update Draft
     */
    public function saveDraft(Request $request)
    {
        try {
            $files = $request->file('files', []);
            $data = [
                'to' => $request->input('to'),
                'account_id' => $request->input('account_id'),
                'subject' => $request->input('subject'),
                'body_html' => $request->input('body_html'),
                'cc' => $request->input('cc'),
                'bcc' => $request->input('bcc'),
                'thread_id' => $request->input('thread_id'),
                'draft_id' => $request->input('draft_id'),
            ];

            $draft = $this->emailService->saveDraft($data, $files);

            return response()->json([
                'success' => true,
                'message' => 'Draft saved',
                'draft_id' => $draft->id,
                'thread_id' => $draft->thread_id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Email draft save failed.', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Could not save the draft.',
            ], 500);
        }
    }

    /**
     * Toggle Star
     */
    public function toggleStar(Request $request)
    {
        $id = $request->input('id');
        $msg = EmailMessage::find($id);

        if (!$msg) {
            return response()->json(['success' => false, 'error' => 'Message not found'], 404);
        }

        $msg->is_starred = !$msg->is_starred;
        $msg->save();

        return response()->json([
            'success' => true,
            'is_starred' => $msg->is_starred,
        ]);
    }

    /**
     * Mark as Read / Unread
     */
    public function markAsRead(Request $request)
    {
        $threadId = $request->input('thread_id');
        $id = $request->input('id');
        $ids = $request->input('ids');
        $isRead = $request->boolean('is_read', true);

        if (!empty($ids) && is_array($ids)) {
            EmailMessage::whereIn('id', $ids)->update(['is_read' => $isRead]);
        } elseif ($threadId) {
            EmailMessage::where('thread_id', $threadId)->update(['is_read' => $isRead]);
        } elseif ($id) {
            EmailMessage::where('id', $id)->update(['is_read' => $isRead]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Move to Trash / Permanent Delete
     */
    public function deleteMessage(Request $request)
    {
        $id = $request->input('id');
        $ids = $request->input('ids');
        $threadId = $request->input('thread_id');
        $permanent = $request->input('permanent', false);

        if (!empty($ids) && is_array($ids)) {
            if ($permanent) {
                $msgs = EmailMessage::whereIn('id', $ids)->with('attachments')->get();
                foreach ($msgs as $m) {
                    foreach ($m->attachments as $att) {
                        Storage::disk('public')->delete($att->file_path);
                    }
                }
                EmailMessage::whereIn('id', $ids)->delete();
            } else {
                EmailMessage::whereIn('id', $ids)->update(['folder' => 'trash']);
            }
        } elseif ($threadId) {
            if ($permanent) {
                $msgs = EmailMessage::where('thread_id', $threadId)->get();
                foreach ($msgs as $m) {
                    foreach ($m->attachments as $att) {
                        Storage::disk('public')->delete($att->file_path);
                    }
                }
                EmailMessage::where('thread_id', $threadId)->delete();
            } else {
                EmailMessage::where('thread_id', $threadId)->update(['folder' => 'trash']);
            }
        } elseif ($id) {
            $msg = EmailMessage::find($id);
            if ($msg) {
                if ($permanent || $msg->folder === 'trash') {
                    foreach ($msg->attachments as $att) {
                        Storage::disk('public')->delete($att->file_path);
                    }
                    $msg->delete();
                } else {
                    $msg->update(['folder' => 'trash']);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Conversation moved to Trash']);
    }

    /**
     * Gmail Style Archive
     */
    public function archive(Request $request)
    {
        $ids = $request->input('ids', $request->input('id') ? [$request->input('id')] : []);
        if (!empty($ids)) {
            EmailMessage::whereIn('id', (array)$ids)->update(['folder' => 'archive']);
        }
        return response()->json(['success' => true, 'message' => 'Conversation archived']);
    }

    /**
     * Move selected messages to another folder (inbox, archive, spam, trash)
     */
    public function moveToFolder(Request $request)
    {
        $ids = $request->input('ids', $request->input('id') ? [$request->input('id')] : []);
        $folder = $request->input('folder', 'inbox');
        if (!empty($ids)) {
            EmailMessage::whereIn('id', (array)$ids)->update(['folder' => $folder]);
        }
        return response()->json(['success' => true, 'message' => 'Conversation moved to ' . ucfirst($folder)]);
    }

    /**
     * Undo last action (e.g. restore to previous folder)
     */
    public function undoAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $restoreFolder = $request->input('folder', 'inbox');
        if (!empty($ids)) {
            EmailMessage::whereIn('id', (array)$ids)->update(['folder' => $restoreFolder]);
        }
        return response()->json(['success' => true, 'message' => 'Action undone']);
    }

    /**
     * Bulk Assign / Remove Labels
     */
    public function bulkAssignLabels(Request $request)
    {
        $ids = $request->input('ids', []);
        $labelId = $request->input('label_id');
        $action = $request->input('action', 'add'); // 'add' or 'remove'

        if (!empty($ids) && $labelId) {
            $emails = EmailMessage::whereIn('id', $ids)->get();
            $handledCustomers = [];
            foreach ($emails as $email) {
                $targetEmail = $email->customer_email ?: $email->from_email;
                $cleanTargetEmail = EmailMessage::extractCleanEmail($targetEmail);
                $threadId = $email->thread_id ?: ('legacy_' . $email->id);

                if ($action === 'add') {
                    \App\Models\EmailThreadLabel::firstOrCreate([
                        'thread_id' => $threadId,
                        'label_id' => (int) $labelId,
                        'email' => $cleanTargetEmail,
                    ], [
                        'assigned_by' => auth()->id(),
                    ]);
                } else {
                    \App\Models\EmailThreadLabel::where('label_id', $labelId)
                        ->where(function($q) use ($threadId, $cleanTargetEmail) {
                            $q->where('thread_id', $threadId);
                            if ($cleanTargetEmail) {
                                $q->orWhere('email', $cleanTargetEmail);
                            }
                        })->delete();
                }

                // Cross-sync if we have customer email and haven't already processed it in this bulk operation
                if ($cleanTargetEmail && !in_array($cleanTargetEmail, $handledCustomers)) {
                    $handledCustomers[] = $cleanTargetEmail;
                    $currentLabels = \App\Models\EmailThreadLabel::where(function($q) use ($threadId, $cleanTargetEmail) {
                        $q->where('thread_id', $threadId)
                          ->orWhere('email', $cleanTargetEmail);
                    })->pluck('label_id')->unique()->all();

                    try {
                        app(\App\Services\LabelSyncService::class)->syncEmailToWhatsApp($cleanTargetEmail, $threadId, $currentLabels, auth()->id());
                    } catch (\Throwable $e) {
                        \Log::warning('Bulk label cross-sync failed: ' . $e->getMessage());
                    }
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Labels updated']);
    }

    /**
     * Clean snippet by stripping quoted email replies and email chain headers
     */
    protected function getCleanSnippet($msg, $limit = 120)
    {
        $plain = $msg->body_plain ?? '';
        $clean = preg_replace('/(On\s+[\s\S]*?wrote:[\s\S]*|-----Original Message-----[\s\S]*)/iu', '', $plain);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));
        if (empty($clean)) {
            $clean = trim(preg_replace('/\s+/', ' ', strip_tags($msg->body_plain ?: $msg->body_html)));
        }
        return \Illuminate\Support\Str::limit($clean, $limit);
    }

    /**
     * Format body HTML so that quoted chains starting with 'On ... wrote:' or '-----Original Message-----'
     * are cleanly isolated in a .gmail_quote container for collapsible Gmail-style trimmed content.
     */
    /**
     * Clean, decode and format email body HTML into authentic standards.
     * Decodes Quoted-Printable (=3D, =\r\n, =C2=A3), removes IMAP protocol artifacts,
     * restores broken HTML attributes, and cleanly isolates reply chains in .gmail_quote.
     */
    protected function formatIsolatedBodyHtml($html, $plain = null)
    {
        $str = $html ?: ($plain ? nl2br(e($plain)) : '');
        if (empty($str)) return '';

        // 1. Strip IMAP protocol line artifacts at start and end
        $str = preg_replace('/^\s*\*\s*\d+\s+FETCH\s*\([^\r\n]*\r?\n?/i', '', $str);
        $str = preg_replace('/\s*\*\s*\d+\s+FETCH\s*\([^\r\n]*$/i', '', $str);
        $str = preg_replace('/\s*TAG_F\d+\s+OK[^\r\n]*$/i', '', $str);
        $str = preg_replace('/\)\s*$/', '', $str);

        // 2. Decode Quoted-Printable if present
        if (strpos($str, '=3D') !== false || preg_match('/=[0-9A-Fa-f]{2}/', $str) || preg_match('/=\r?\n/', $str)) {
            $str = quoted_printable_decode($str);
        }

        // 3. Fix residual corrupted Quoted-Printable artifacts
        $str = str_replace(["=3D", "3D\"", "'3D\"", "3D%22"], ["=", "\"", "\"", ""], $str);
        $str = preg_replace('/%22(?=\s|>|"|\')/', '', $str);

        // 4. Fix broken attribute quotes and split words from raw fetch
        $str = preg_replace('/class=[\'"]*3D[\'"]*([^\'"\s>]+)[\'"]*/i', 'class="$1"', $str);
        $str = preg_replace('/class=[\'"]+([a-zA-Z0-9_-]+)=\'?\s+([a-zA-Z0-9_-]+)>/i', 'class="$1$2">', $str);
        $str = preg_replace('/class=["\']([a-zA-Z0-9_-]+)="?\s+([a-zA-Z0-9_-]+)>/i', 'class="$1$2">', $str);
        $str = preg_replace('/rol=[\'"]*e=3D"presentation"[\'"]*/i', 'role="presentation"', $str);
        $str = preg_replace('/rol=[\'"]*e="presentation"[\'"]*/i', 'role="presentation"', $str);
        $str = preg_replace('/styl=[\'"]*e=3D"([^"]*)"[\'"]*/i', 'style="$1"', $str);
        $str = preg_replace('/styl=[\'"]*e="([^"]*)"[\'"]*/i', 'style="$1"', $str);
        $str = preg_replace('/cellpadding=[\'"]*3D"([^\"]*)"[\'"]*/i', 'cellpadding="$1"', $str);
        $str = preg_replace('/cellspacing=[\'"]*3D"([^\"]*)"[\'"]*/i', 'cellspacing="$1"', $str);
        $str = preg_replace('/width=[\'"]*3D"([^\"]*)"[\'"]*/i', 'width="$1"', $str);
        $str = preg_replace('/width=["\']?120["\']?([0-9.]+%?)"?/i', 'width="$1"', $str);

        // Fix Word/Outlook invalid nested paragraphs and collapse huge empty gaps
        $str = preg_replace('/<p[^>]*>\s*(?:<span[^>]*>)?\s*<p[^>]*>(\s*|&nbsp;| )*<\/p>\s*(?:<\/span>)?\s*<\/p>/i', '<p class="MsoNormal" style="margin: 4px 0;">&nbsp;</p>', $str);
        $str = preg_replace('/<p><\/p>/i', '', $str);
        $str = preg_replace('/(<p[^>]*>(?:&nbsp;|\s| )*<\/p>\s*){2,}/i', '<p class="MsoNormal" style="margin: 4px 0;">&nbsp;</p>', $str);
        $str = preg_replace('/alt=[\'"]*3D"([^\"]*)\'(\s+in\s+need)?/i', 'alt="$1 In Need"', $str);
        $str = preg_replace('/alt=[\'"]+([^\'"]*)\'\s+in\s+need/i', 'alt="$1 In Need"', $str);
        $str = preg_replace('/src="3D%22([^%"]+)%22/i', 'src="$1"', $str);
        $str = preg_replace('/src="="https/i', 'src="https', $str);
        $str = preg_replace('/href="="https/i', 'href="https', $str);
        $str = preg_replace('/href="3D%22([^%"]+)%22/i', 'href="$1"', $str);
        $str = preg_replace('/href="=%22([^%"]+)%22"?/i', 'href="$1"', $str);
        $str = preg_replace('/href="=%22([^%"]+)"/i', 'href="$1"', $str);
        $str = preg_replace('/style=[\'"]*3D"display:\'(\s+block\s+margin:\s+auto)?>?/i', 'style="display: block; margin: 0 auto;">', $str);
        $str = preg_replace('/style=[\'"]+display:\'\s*block\s*margin:\s*auto>?/i', 'style="display: block; margin: 0 auto;">', $str);
        $str = preg_replace('/text-decor="ation:"\s*none/i', 'text-decoration: none', $str);
        $str = preg_replace('/style=[\'"]*3D"color:\'(\s*text-decoration:\s*none;?)?>?/i', 'style="color: #7860ff; text-decoration: none;">', $str);
        $str = preg_replace('/style=[\'"]+color:\'\s*text-decoration:\s*none;?>?/i', 'style="color: #7860ff; text-decoration: none;">', $str);
        $str = preg_replace('/style="margin-bottom:\s*5px;">(\s*5px;>)+/i', 'style="margin-bottom: 5px;">', $str);
        $str = preg_replace('/style="margin-bottom:\'?>?/i', 'style="margin-bottom: 5px;">', $str);
        $str = preg_replace('/https:\/\/www\.assignnmentinneed\.com\/ass=[\'"]*\s*ets/i', 'https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png', $str);
        $str = preg_replace('/assignment_logo\.png\s+alt=/i', 'assignment_logo.png" alt=', $str);
        $str = preg_replace('/<meta\s+charset=[\'"]*3D"utf-8"[\'"]*=?\s*>/i', '<meta charset="utf-8">', $str);
        $str = preg_replace('/<meta\s+charset=[\'"]+utf-8[\'"]+=?\s*>/i', '<meta charset="utf-8">', $str);
        $str = preg_replace('/<meta\s+charset="utf-8"=\s*>/i', '<meta charset="utf-8">', $str);
        $str = preg_replace('/<meta\s+charset=[\'"]*"utf-8"=[\'"]*>/i', '<meta charset="utf-8">', $str);
        $str = preg_replace('/<meta\s+name=[\'"]*3D"viewport"[\'"]*[^>]*>/i', '<meta name="viewport" content="width=device-width, initial-scale=1.0">', $str);
        $str = preg_replace('/<meta\s+name="viewport"\s+content=[^>]+>/i', '<meta name="viewport" content="width=device-width, initial-scale=1.0">', $str);
        $str = preg_replace('/\/ass=\s*ets/i', '/assets', $str);

        // 5. Fix CSS line-broken words
        $str = preg_replace('/background-=\s*color/i', 'background-color', $str);
        $str = preg_replace('/border-radiu=\s*s/i', 'border-radius', $str);
        $str = preg_replace('/table-layo=\s*ut/i', 'table-layout', $str);
        $str = preg_replace('/text-size-adjust:=\s*none/i', 'text-size-adjust: none', $str);

        // 6. Fix double quotes inside attributes e.g. class='"wrapper"' or cellpadding='"0"'
        $str = preg_replace('/([a-zA-Z0-9_-]+)=[\'"]+"([^\'"]+)"[\'"]+/i', '$1="$2"', $str);

        // 7. Decode escaped HTML tags like &lt;b&gt;, &lt;/b&gt;, &lt;/tr&gt;, &lt;/html&gt;
        $str = preg_replace_callback('/&lt;(\/?[a-zA-Z0-9_-]+(?:[\s\S]*?)?)&gt;/i', function($m) {
            $inner = $m[1];
            if (preg_match('/^\/?(html|body|head|table|tbody|thead|tr|td|th|p|div|span|b|strong|i|em|u|br|hr|img|a)(?:\s+[^>]*)?$/i', $inner)) {
                return '<' . $inner . '>';
            }
            return $m[0];
        }, $str);

        // 8. Remove duplicate consecutive table tags and stray html/body tags
        $str = preg_replace('/(<\/tr>\s*){2,}/i', '</tr>', $str);
        $str = preg_replace('/(<\/table>\s*){2,}/i', '</table>', $str);
        $str = preg_replace('/(<\/div>\s*){2,}/i', '</div>', $str);
        $str = preg_replace('/<\/?(html|body|head)[^>]*>/i', '', $str);

        // 9. Sanitize UTF-8 encoding
        if (function_exists('iconv')) {
            $str = iconv('UTF-8', 'UTF-8//IGNORE', $str) ?: $str;
        }
        $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

        // 8. Isolate reply chains in .gmail_quote container
        if (stripos($str, 'gmail_quote') === false) {
            $pattern = '/(?=(?:<div[^>]*>|<p[^>]*>|<br\s*\/?>|\n|^)\s*(?:On\s+[\s\S]*?wrote:|-----Original Message-----|From:\s+[\s\S]*?Sent:))/iu';
            $parts = preg_split($pattern, $str, 2);
            if (is_array($parts) && count($parts) === 2 && !empty(trim(strip_tags($parts[0])))) {
                $str = $parts[0] . '<div class="gmail_quote">' . $parts[1] . '</div>';
            }
        }

        return trim($str);
    }

    /**
     * Trigger Sync via AJAX / Auto-Polling
     */
    public function sync(Request $request)
    {
        $account = null;
        if ($request->filled('account_id')) {
            $account = EmailConfiguration::whereKey($request->integer('account_id'))
                ->where('is_active', true)
                ->first();
        }

        $accountId = $account?->id;
        $dispatchKey = 'email-sync-lock-'.($accountId ?: 'all');

        if (!Cache::add($dispatchKey, true, now()->addSeconds(6))) {
            return response()->json([
                'status' => 'busy',
                'message' => 'Sync already in progress.',
                'synced_count' => 0,
            ], 200);
        }

        try {
            if (session()->isStarted()) {
                session()->save();
            }
            // Direct synchronous IMAP socket sync — works on shared cPanel without requiring exec()
            $result = $this->emailService->syncImap($account);

            $clientUnread = EmailMessage::where('email_configuration_id', 2)
                ->where('direction', 'inbound')
                ->where('folder', '!=', 'trash')
                ->where('is_read', false)
                ->count();

            $writerUnread = EmailMessage::where('email_configuration_id', 1)
                ->where('direction', 'inbound')
                ->where('folder', '!=', 'trash')
                ->where('is_read', false)
                ->count();

            return response()->json([
                'status' => $result['status'] ?? 'success',
                'message' => $result['message'] ?? 'Incoming email sync completed.',
                'synced_count' => $result['synced_count'] ?? 0,
                'client_unread' => $clientUnread,
                'writer_unread' => $writerUnread,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Email sync error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'synced_count' => 0,
            ], 200);
        } finally {
            Cache::forget($dispatchKey);
        }
    }

    private function startEmailSyncProcess(?int $accountId): void
    {
        $php = PHP_BINARY;
        $artisan = base_path('artisan');
        $accountOption = $accountId ? ' --account='.(int) $accountId : '';

        if (PHP_OS_FAMILY === 'Windows') {
            $command = 'start /B "" "'.str_replace('"', '""', $php).'" "'
                .str_replace('"', '""', $artisan).'" email:sync'.$accountOption.' >NUL 2>&1';
            @pclose(@popen($command, 'r'));
            return;
        }

        $command = escapeshellarg($php).' '.escapeshellarg($artisan).' email:sync'
            .$accountOption.' > /dev/null 2>&1 &';
        @exec($command);
    }

    /**
     * Download Email Attachment
     */
    public function downloadAttachment($id)
    {
        $attachment = EmailAttachment::findOrFail($id);
        $path = Storage::disk('local')->path($attachment->file_path);
        if (!file_exists($path)) {
            $legacyPath = storage_path('app/public/'.$attachment->file_path);
            if (!file_exists($legacyPath)) abort(404, 'Attachment file not found');
            $path = $legacyPath;
        }

        return response()->download($path, $attachment->filename, [
            'Content-Type' => $attachment->mime_type ?: 'application/octet-stream',
        ]);
    }

    /**
     * Inbound Webhook Endpoint
     */
    public function webhook(Request $request)
    {
        $secret = (string) config('mail.inbound_webhook_secret');
        $provided = (string) ($request->header('X-Email-Webhook-Secret') ?: $request->input('webhook_secret'));
        if ($secret === '' || !hash_equals($secret, $provided)) {
            abort(401, 'Invalid inbound email webhook signature.');
        }
        $payload = $request->all();
        $payload['_attachments'] = $request->allFiles();
        $msg = $this->emailService->processInboundWebhook($payload);

        return response()->json([
            'status' => 'received',
            'message_id' => $msg ? $msg->id : null,
        ]);
    }

    /**
     * Seed realistic sample conversation emails for testing & preview
     */
    public function seedSampleData()
    {
        if (EmailMessage::count() > 0) {
            return response()->json(['message' => 'Emails already exist in database!']);
        }

        $thread1 = (string) Str::uuid();
        $thread2 = (string) Str::uuid();
        $thread3 = (string) Str::uuid();

        // Conversation 1: Assignment Inquiry
        EmailMessage::create([
            'thread_id' => $thread1,
            'message_id' => '<sample1@student.oxford.ac.uk>',
            'from_email' => 'sarah.j@oxford.ac.uk',
            'from_name' => 'Sarah Jenkins',
            'to_email' => 'support@ain-backend.com',
            'subject' => 'Help needed for MBA Dissertation Proposal (Finance)',
            'body_html' => '<p>Hello Support Team,</p><p>I need urgent assistance with my MBA Dissertation Proposal on "Financial Risk Modeling in Emerging Markets". The deadline is in 5 days. Could you please let me know the pricing and writer availability?</p><p>Thanks,<br>Sarah Jenkins<br>Oxford University</p>',
            'body_plain' => 'Hello Support Team, I need urgent assistance with my MBA Dissertation Proposal...',
            'folder' => 'inbox',
            'direction' => 'inbound',
            'status' => 'received',
            'is_read' => false,
            'is_starred' => true,
            'received_at' => now()->subHours(3),
            'created_at' => now()->subHours(3),
        ]);

        // Conversation 2: Reply Thread with Attachment
        $msg2_1 = EmailMessage::create([
            'thread_id' => $thread2,
            'message_id' => '<sample2_1@harvard.edu>',
            'from_email' => 'david.clark@harvard.edu',
            'from_name' => 'David Clark',
            'to_email' => 'support@ain-backend.com',
            'subject' => 'Python Machine Learning Project Code Review',
            'body_html' => '<p>Hi,</p><p>I am attaching my Jupyter Notebook and project guidelines. I need code debugging and explanatory documentation.</p>',
            'body_plain' => 'Hi, I am attaching my Jupyter Notebook and project guidelines...',
            'folder' => 'inbox',
            'direction' => 'inbound',
            'status' => 'received',
            'is_read' => true,
            'has_attachments' => true,
            'received_at' => now()->subHours(24),
            'created_at' => now()->subHours(24),
        ]);

        EmailMessage::create([
            'thread_id' => $thread2,
            'message_id' => '<sample2_2@ain-backend.com>',
            'in_reply_to' => '<sample2_1@harvard.edu>',
            'from_email' => 'support@ain-backend.com',
            'from_name' => 'Assignment In Need Support',
            'to_email' => 'david.clark@harvard.edu',
            'to_name' => 'David Clark',
            'subject' => 'Re: Python Machine Learning Project Code Review',
            'body_html' => '<p>Dear David,</p><p>Thank you for reaching out! Our expert Data Science writer has reviewed your project requirements. We can complete this within 48 hours.</p><p>Best regards,<br>AIN Support Team</p>',
            'body_plain' => 'Dear David, Thank you for reaching out! Our expert Data Science writer has reviewed...',
            'folder' => 'sent',
            'direction' => 'outbound',
            'status' => 'sent',
            'is_read' => true,
            'sent_at' => now()->subHours(22),
            'created_at' => now()->subHours(22),
        ]);

        // Conversation 3: Draft
        EmailMessage::create([
            'thread_id' => $thread3,
            'from_email' => 'support@ain-backend.com',
            'from_name' => 'Assignment In Need',
            'to_email' => 'prof.michael@cambridge.ac.uk',
            'subject' => 'Follow up on Academic Partnership Proposal',
            'body_html' => '<p>Dear Professor Michael,</p><p>I hope this email finds you well. Writing to follow up on our previous discussion regarding tutoring partnerships...</p>',
            'body_plain' => 'Dear Professor Michael, Writing to follow up...',
            'folder' => 'drafts',
            'direction' => 'outbound',
            'status' => 'draft',
            'is_read' => true,
            'is_draft' => true,
            'created_at' => now()->subMinutes(30),
        ]);

        return response()->json(['message' => 'Sample email conversations created successfully!']);
    }

    /**
     * Display Email Plugin Settings & Multi-Account Configurations.
     */
    public function settings(Request $request)
    {
        $configurations = EmailConfiguration::orderBy('sort_order')->orderBy('id')->get();

        return view('emails.settings', [
            'configurations' => $configurations,
        ]);
    }

    /**
     * Store new Email Configuration.
     */
    public function storeConfiguration(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email_address' => 'required|email|max:191',
            'from_name' => 'nullable|string|max:191',
            'driver' => 'required|string|in:smtp',
            'host' => 'nullable|string|max:191',
            'port' => 'nullable|integer|min:1|max:65535',
            'encryption' => 'nullable|string|in:tls,ssl,none',
            'username' => 'nullable|string|max:191',
            'password' => 'nullable|string',
            'incoming_protocol' => 'nullable|string|in:imap',
            'incoming_host' => 'nullable|string|max:191',
            'incoming_port' => 'nullable|integer|min:1|max:65535',
            'incoming_encryption' => 'nullable|string|in:ssl,none',
            'incoming_username' => 'nullable|string|max:191',
            'incoming_password' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if (blank($validated['host'] ?? null) || blank($validated['username'] ?? null) || blank($validated['password'] ?? null)) {
            return back()->withErrors(['host' => 'SMTP host, username and password are required.'])->withInput();
        }
        if (blank($validated['incoming_host'] ?? null) || blank($validated['incoming_username'] ?? null) || blank($validated['incoming_password'] ?? null)) {
            return back()->withErrors(['incoming_host' => 'IMAP host, username and password are required.'])->withInput();
        }

        if ($request->boolean('is_default')) {
            EmailConfiguration::where('is_default', true)->update(['is_default' => false]);
        }

        $config = EmailConfiguration::create([
            'name' => $validated['name'],
            'email_address' => $validated['email_address'],
            'from_name' => $validated['from_name'] ?? $validated['name'],
            'driver' => $validated['driver'],
            'host' => $validated['host'] ?? null,
            'port' => (int)($validated['port'] ?? 587),
            'encryption' => $validated['encryption'] ?? 'tls',
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'incoming_protocol' => $validated['incoming_protocol'] ?? 'imap',
            'incoming_host' => $validated['incoming_host'] ?? null,
            'incoming_port' => (int)($validated['incoming_port'] ?? 993),
            'incoming_encryption' => $validated['incoming_encryption'] ?? 'ssl',
            'incoming_username' => $validated['incoming_username'] ?? null,
            'incoming_password' => $validated['incoming_password'] ?? null,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'sort_order' => EmailConfiguration::count() + 1,
        ]);

        EmailConfiguration::syncEmailSubmenus();

        return redirect()->route('emails.settings')->with('success', "Email configuration '{$config->name}' created and added to Emails menu!");
    }

    /**
     * Clone an existing Email Configuration.
     */
    public function cloneConfiguration(Request $request, $id)
    {
        $source = EmailConfiguration::findOrFail($id);

        $copyName = $request->input('name') ?: ($source->name . ' (Copy)');
        $copyEmail = $request->input('email_address') ?: $source->email_address;

        $cloned = EmailConfiguration::create([
            'name' => $copyName,
            'email_address' => $copyEmail,
            'from_name' => $source->from_name,
            'driver' => $source->driver,
            'host' => $source->host,
            'port' => $source->port,
            'encryption' => $source->encryption,
            'username' => $source->username,
            'password' => $source->password,
            'incoming_protocol' => $source->incoming_protocol,
            'incoming_host' => $source->incoming_host,
            'incoming_port' => $source->incoming_port,
            'incoming_encryption' => $source->incoming_encryption,
            'incoming_username' => $source->incoming_username,
            'incoming_password' => $source->incoming_password,
            'settings' => $source->settings,
            'is_default' => false,
            'is_active' => true,
            'sort_order' => EmailConfiguration::count() + 1,
        ]);

        EmailConfiguration::syncEmailSubmenus();

        return redirect()->route('emails.settings')->with('success', "Configuration '{$source->name}' cloned as '{$cloned->name}' and added to Emails menu!");
    }

    /**
     * Update Email Configuration.
     */
    public function updateConfiguration(Request $request, $id)
    {
        $config = EmailConfiguration::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email_address' => 'required|email|max:191',
            'from_name' => 'nullable|string|max:191',
            'driver' => 'required|string|in:smtp',
            'host' => 'nullable|string|max:191',
            'port' => 'nullable|integer|min:1|max:65535',
            'encryption' => 'nullable|string|in:tls,ssl,none',
            'username' => 'nullable|string|max:191',
            'password' => 'nullable|string',
            'incoming_protocol' => 'nullable|string|in:imap',
            'incoming_host' => 'nullable|string|max:191',
            'incoming_port' => 'nullable|integer|min:1|max:65535',
            'incoming_encryption' => 'nullable|string|in:ssl,none',
            'incoming_username' => 'nullable|string|max:191',
            'incoming_password' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            EmailConfiguration::where('id', '!=', $config->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $updateData = [
            'name' => $validated['name'],
            'email_address' => $validated['email_address'],
            'from_name' => $validated['from_name'] ?? $validated['name'],
            'driver' => $validated['driver'],
            'host' => $validated['host'] ?? null,
            'port' => (int)($validated['port'] ?? 587),
            'encryption' => $validated['encryption'] ?? 'tls',
            'username' => $validated['username'] ?? null,
            'incoming_protocol' => $validated['incoming_protocol'] ?? 'imap',
            'incoming_host' => $validated['incoming_host'] ?? null,
            'incoming_port' => (int)($validated['incoming_port'] ?? 993),
            'incoming_encryption' => $validated['incoming_encryption'] ?? 'ssl',
            'incoming_username' => $validated['incoming_username'] ?? null,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }
        if (!empty($validated['incoming_password'])) {
            $updateData['incoming_password'] = $validated['incoming_password'];
        }

        $config->update($updateData);
        EmailConfiguration::syncEmailSubmenus();

        return redirect()->route('emails.settings')->with('success', "Email configuration '{$config->name}' updated successfully!");
    }

    /**
     * Delete Email Configuration.
     */
    public function deleteConfiguration($id)
    {
        $config = EmailConfiguration::findOrFail($id);
        $name = $config->name;

        // Remove submenu associated with this account
        $subRoute = "emails?account_id={$config->id}";
        DB::table('submenus')->where('routes', $subRoute)->delete();

        $config->delete();
        EmailConfiguration::syncEmailSubmenus();

        return redirect()->route('emails.settings')->with('success', "Configuration '{$name}' deleted!");
    }

    /**
     * Test SMTP / IMAP Connection.
     */
    public function testConnection(Request $request, $id)
    {
        $config = EmailConfiguration::findOrFail($id);

        try {
            $this->emailService->testConnections($config);
            return response()->json([
                'success' => true,
                'message' => "SMTP and IMAP authentication succeeded for '{$config->name}'.",
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Email connection test failed.', ['configuration_id' => $config->id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'SMTP or IMAP authentication failed. Verify the server, port, encryption and credentials.',
            ]);
        }
    }

    /**
     * Save / Update Labels for an Email Thread and cross-sync to WhatsApp.
     */
    public function saveThreadLabels(Request $request)
    {
        $validated = $request->validate([
            'thread_id' => 'nullable|string|max:100',
            'email' => 'nullable|string|max:191',
            'labels' => 'nullable|array',
        ]);

        $threadId = trim((string) ($validated['thread_id'] ?? ''));
        $rawEmail = trim((string) ($validated['email'] ?? ''));
        $customerEmail = EmailMessage::extractCleanEmail($rawEmail);

        // Find email message for context if needed
        $emailMsg = null;
        if ($threadId) {
            $emailMsg = EmailMessage::where('thread_id', $threadId)->first();
        }

        if (!$customerEmail && $emailMsg) {
            $customerEmail = $emailMsg->customer_email;
        }

        // Make sure customerEmail is NOT one of our configured system email accounts
        $configuredEmails = EmailConfiguration::pluck('email_address')
            ->map(fn($e) => strtolower(trim($e)))
            ->filter()
            ->all();

        if ($customerEmail && in_array(strtolower($customerEmail), $configuredEmails)) {
            if ($emailMsg) {
                $otherParty = ($emailMsg->direction === 'outbound' || $emailMsg->folder === 'sent' || $emailMsg->folder === 'drafts')
                    ? $emailMsg->to_email
                    : $emailMsg->from_email;
                $customerEmail = EmailMessage::extractCleanEmail($otherParty);
                if ($customerEmail && in_array(strtolower($customerEmail), $configuredEmails)) {
                    $customerEmail = null;
                }
            } else {
                $customerEmail = null;
            }
        }

        $labelIds = collect($request->input('labels', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        // Cross-channel sync: update email labels and mirror to WhatsApp
        $syncedPhones = [];
        try {
            $syncedPhones = app(\App\Services\LabelSyncService::class)->syncEmailToWhatsApp($customerEmail ?: '', $threadId ?: null, $labelIds, auth()->id());
        } catch (\Throwable $e) {
            \Log::warning('Failed to sync Email labels to WhatsApp: ' . $e->getMessage());
        }

        $activeLabels = \App\Models\WhatsappChatLabel::forEmail()->whereIn('id', $labelIds)->ordered()->get(['id', 'name', 'color']);

        return response()->json([
            'success' => true,
            'message' => 'Labels updated successfully.',
            'labels' => $activeLabels,
            'synced_phones' => $syncedPhones,
        ]);
    }

    /**
     * Search User & Lead contacts for Email Composer autocomplete suggestions.
     */
    public function suggestRecipients(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        if ($query === '' || strlen($query) < 1) {
            return response()->json(['users' => []]);
        }

        $users = \App\Models\User::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('mobile_no', 'like', "%{$query}%");
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->select('id', 'name', 'email', 'mobile_no')
            ->limit(10)
            ->get();

        $leadEmails = \App\Models\Leads::query()
            ->where(function ($q) use ($query) {
                $q->where('user_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('mobile', 'like', "%{$query}%");
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->select('id', 'user_name as name', 'email', 'mobile as mobile_no')
            ->limit(10)
            ->get();

        $isSuperAdmin = auth()->check() && (int) auth()->user()->role_id === 1;

        $combined = $users->concat($leadEmails)
            ->unique(fn ($item) => strtolower(trim($item->email)))
            ->take(10)
            ->map(function ($item) use ($isSuperAdmin) {
                $realEmail = $item->email;
                $item->display_email = $isSuperAdmin ? $realEmail : mask_email_for_display($realEmail);
                if (!$isSuperAdmin) {
                    $item->mobile_no = $item->mobile_no ? mask_mobile_only(null, $item->mobile_no) : '';
                }
                $item->email = $realEmail;
                return $item;
            })
            ->values();

        return response()->json([
            'users' => $combined,
        ]);
    }

    /**
     * Live search suggestions & instant preview for the Gmail search box.
     */
    public function suggestSearch(Request $request)
    {
        if (session()->isStarted()) {
            session()->save();
        }

        $query = trim((string) $request->input('q', ''));
        $accountId = $request->input('account_id') ?: session('active_email_account_id');
        $folder = $request->input('folder');

        $emailQuery = EmailMessage::where('folder', '!=', 'trash');

        if ($folder && $folder !== 'all' && $folder !== 'inbox') {
            if ($folder === 'sent') {
                $emailQuery->where(function ($q) {
                    $q->where('folder', 'sent')->orWhere('direction', 'outbound');
                });
            }
        }

        if ($request->boolean('has_attachment') || $request->input('has_attachment') === '1') {
            $emailQuery->where('has_attachments', true);
        }

        if ($request->boolean('from_me') || $request->input('from_me') === '1') {
            $emailQuery->where(function($q) {
                $q->where('direction', 'outbound')->orWhere('folder', 'sent');
            });
        }

        if ($request->input('date_range') === 'last_7_days') {
            $emailQuery->where(function ($q) {
                $q->where('received_at', '>=', now()->subDays(7))
                  ->orWhere('created_at', '>=', now()->subDays(7));
            });
        }

        if ($request->has('is_read')) {
            $isRead = $request->input('is_read');
            if ($isRead === 'unread' || $isRead === '0') {
                $emailQuery->where('is_read', false);
            } elseif ($isRead === 'read' || $isRead === '1') {
                $emailQuery->where('is_read', true);
            }
        }

        if ($query !== '') {
            $terms = collect(preg_split('/\s+/', $query))->filter()->values();

            $matchingContactEmails = User::query()
                ->where(function ($userQuery) use ($query, $terms) {
                    $userQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('mobile_no', 'like', "%{$query}%");
                    foreach ($terms as $t) {
                        $userQuery->orWhere('name', 'like', "%{$t}%")
                                  ->orWhere('email', 'like', "%{$t}%");
                    }
                })
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->all();

            $emailQuery->where(function ($deepQuery) use ($query, $terms, $matchingContactEmails) {
                $deepQuery->where('subject', 'like', "%{$query}%")
                    ->orWhere('from_name', 'like', "%{$query}%")
                    ->orWhere('from_email', 'like', "%{$query}%")
                    ->orWhere('to_name', 'like', "%{$query}%")
                    ->orWhere('to_email', 'like', "%{$query}%")
                    ->orWhere('cc', 'like', "%{$query}%")
                    ->orWhere('bcc', 'like', "%{$query}%");

                if (!empty($matchingContactEmails)) {
                    $deepQuery->orWhereIn('from_email', $matchingContactEmails)
                              ->orWhereIn('to_email', $matchingContactEmails);
                }

                if ($terms->count() > 1) {
                    $deepQuery->orWhere(function ($subQ) use ($terms) {
                        foreach ($terms as $term) {
                            $like = "%{$term}%";
                            $subQ->where(function ($fq) use ($like) {
                                $fq->where('subject', 'like', $like)
                                   ->orWhere('from_name', 'like', $like)
                                   ->orWhere('from_email', 'like', $like)
                                   ->orWhere('to_name', 'like', $like)
                                   ->orWhere('to_email', 'like', $like)
                                   ->orWhere('cc', 'like', $like)
                                   ->orWhere('bcc', 'like', $like);
                            });
                        }
                    });
                }
            });
        }

        // Strictly filter by currently active account
        if ($accountId) {
            $emailQuery->where('email_configuration_id', intval($accountId));
        }

        $messages = $emailQuery->orderByDesc('id')
            ->select([
                'id', 'thread_id', 'subject', 'from_name', 'from_email', 'to_name', 'to_email',
                'direction', 'has_attachments', 'received_at', 'created_at', 'is_read', 'email_configuration_id'
            ])
            ->take(30)
            ->get()
            ->unique('thread_id')
            ->take(6)
            ->values();

        // If subject/sender search yielded fewer than 6 and user typed query, fallback to body search
        if ($messages->count() < 6 && $query !== '') {
            $existingThreadIds = $messages->pluck('thread_id')->all();
            $fallbackQuery = EmailMessage::where('folder', '!=', 'trash')
                ->whereNotIn('thread_id', $existingThreadIds)
                ->where('body_plain', 'like', "%{$query}%");
            if ($accountId) {
                $fallbackQuery->where('email_configuration_id', intval($accountId));
            }
            $fallbackMessages = $fallbackQuery->orderByDesc('id')
                ->select([
                    'id', 'thread_id', 'subject', 'from_name', 'from_email', 'to_name', 'to_email',
                    'direction', 'has_attachments', 'received_at', 'created_at', 'is_read', 'email_configuration_id'
                ])
                ->take(10)
                ->get()
                ->unique('thread_id')
                ->take(6 - $messages->count())
                ->values();

            $messages = $messages->merge($fallbackMessages);
        }

        if ($messages->isEmpty()) {
            return response()->json([
                'success' => true,
                'query' => $query,
                'results' => []
            ]);
        }

        $results = $messages->map(function ($msg) {
            $date = $msg->received_at ?: $msg->created_at;
            $formattedDate = '';
            if ($date) {
                if ($date->isToday()) {
                    $formattedDate = $date->format('g:i A');
                } elseif ($date->isCurrentYear()) {
                    $formattedDate = $date->format('M j');
                } else {
                    $formattedDate = $date->format('M j, Y');
                }
            }

            // Participant display: like Gmail "Kriti Hinger, me"
            $fromPart = $msg->from_name ?: explode('@', (string) $msg->from_email)[0];
            $participants = $fromPart;
            if ($msg->direction === 'outbound') {
                $toPart = $msg->to_name ?: explode('@', (string) $msg->to_email)[0];
                $participants = "me, " . ($toPart ?: 'recipient');
            }

            return [
                'id' => $msg->id,
                'thread_id' => $msg->thread_id,
                'subject' => $msg->subject ?: '(no subject)',
                'participants' => $participants,
                'from_email' => $msg->from_email,
                'has_attachments' => (bool) $msg->has_attachments,
                'date_formatted' => $formattedDate,
                'is_read' => (bool) $msg->is_read,
            ];
        });

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
        ]);
    }
}
