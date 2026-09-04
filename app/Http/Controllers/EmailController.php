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
        $search = $request->get('search');
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
                $query->where(function ($q) {
                    $q->where('folder', 'inbox')
                      ->orWhere('direction', 'inbound');
                })->where('is_draft', false)->where('folder', '!=', 'trash');
                break;
        }

        // Filter by specific configured account if selected
        $selectedAccount = null;
        if ($accountId) {
            $selectedAccount = EmailConfiguration::find($accountId);
            if ($selectedAccount && !empty($selectedAccount->email_address)) {
                $query->where('email_configuration_id', $selectedAccount->id);
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%")
                  ->orWhere('to_email', 'like', "%{$search}%")
                  ->orWhere('body_plain', 'like', "%{$search}%");
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

        // Fetch latest message per thread to display in list (lightweight 20 per page)
        $threadsQuery = clone $query;
        $latestMessageIds = $threadsQuery->selectRaw('MAX(id) as id')
            ->groupBy('thread_id')
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        $threads = EmailMessage::select([
                'id', 'thread_id', 'from_email', 'from_name', 'to_email', 'to_name',
                'subject', 'body_plain', 'folder', 'direction', 'status',
                'is_read', 'is_starred', 'is_draft', 'has_attachments', 'received_at', 'created_at'
            ])
            ->whereIn('id', empty($latestMessageIds) ? [0] : $latestMessageIds)
            ->orderByDesc('id')
            ->paginate(20);

        if ($request->ajax() && $request->boolean('partial')) {
            return response(
                view('emails._rows', ['emails' => $threads, 'isAppend' => false])->render()
            )->withHeaders([
                'Cache-Control' => 'no-store, private',
                'X-Email-Partial' => 'rows',
            ]);
        }

        if ($request->ajax() && ($request->get('scroll') == '1' || $request->has('page'))) {
            return response()->json([
                'success' => true,
                'html' => view('emails._rows', ['emails' => $threads, 'isAppend' => true])->render(),
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

        // Keep the small first page of every folder in the browser so folder
        // switching is instant even on a slow local PHP development server.
        $cacheSource = EmailMessage::select([
                'id', 'thread_id', 'from_email', 'from_name', 'to_email', 'to_name',
                'subject', 'body_plain', 'folder', 'direction', 'status',
                'is_read', 'is_starred', 'is_draft', 'has_attachments', 'received_at', 'created_at'
            ])
            ->when($selectedAccount, fn ($q) => $q->where('email_configuration_id', $selectedAccount->id))
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $folderHtmlCache = [];
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
            ])->render();
        }

        $allLabels = \App\Models\WhatsappChatLabel::orderBy('name')->get();
        $threadIds = $threads->pluck('thread_id')->filter()->unique()->all();
        $threadLabelsMap = \App\Models\EmailThreadLabel::with('label')
            ->whereIn('thread_id', $threadIds)
            ->get()
            ->groupBy('thread_id');

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
            'threadLabelsMap'
        ));
    }

    /** Lightweight real-time change & new email detector. */
    public function updates(Request $request)
    {
        $query = EmailMessage::query();
        if ($request->filled('account_id')) {
            $query->where('email_configuration_id', $request->integer('account_id'));
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
            ->when($request->filled('account_id'), fn ($q) => $q->where('email_configuration_id', $request->integer('account_id')))
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

        // Fetch labels attached to this thread
        $customerEmail = $email->customer_email;
        $threadLabelIds = \App\Models\EmailThreadLabel::where('thread_id', $email->thread_id)
            ->when(empty($email->thread_id) && !empty($customerEmail), function($q) use ($customerEmail) {
                $q->orWhere('email', $customerEmail);
            })
            ->pluck('label_id')
            ->unique()
            ->all();

        $threadLabels = \App\Models\WhatsappChatLabel::whereIn('id', $threadLabelIds)
            ->get(['id', 'name', 'color']);

        $allLabels = \App\Models\WhatsappChatLabel::orderBy('name')->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'email' => [
                    'id' => $email->id,
                    'thread_id' => $email->thread_id,
                    'message_id' => $email->message_id,
                    'from_name' => $email->from_name ?: $email->from_email,
                    'from_email' => $email->from_email,
                    'to_name' => $email->to_name,
                    'to_email' => $email->to_email,
                    'customer_email' => $customerEmail,
                    'cc' => $email->cc,
                    'bcc' => $email->bcc,
                    'subject' => $email->subject,
                    'body_html' => $email->body_html ?: nl2br(e($email->body_plain)),
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
                'messages' => $threadMessages->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'from_name' => $msg->from_name ?: $msg->from_email,
                        'from_email' => $msg->from_email,
                        'to_name' => $msg->to_name,
                        'to_email' => $msg->to_email,
                        'subject' => $msg->subject,
                        'body_html' => $msg->body_html ?: nl2br(e($msg->body_plain)),
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
            'threadLabelIds'
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
                'error' => 'Email delivery failed. Check the selected account connection and application logs.',
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
        $isRead = $request->input('is_read', true);

        if ($threadId) {
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
        $threadId = $request->input('thread_id');
        $permanent = $request->input('permanent', false);

        if ($threadId) {
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

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    /**
     * Trigger Manual Sync via AJAX
     */
    public function sync(Request $request)
    {
        $account = null;
        if ($request->filled('account_id')) {
            $account = EmailConfiguration::whereKey($request->integer('account_id'))
                ->where('is_active', true)
                ->firstOrFail();
        }
        $accountId = $account?->id;
        $dispatchKey = 'email-sync-dispatch-'.($accountId ?: 'all');

        if (Cache::add($dispatchKey, true, now()->addSeconds(8))) {
            $this->startEmailSyncProcess($accountId);
        }

        return response()->json([
            'status' => 'accepted',
            'message' => 'Incoming email sync is running in the background.',
        ], 202);
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

        $activeLabels = \App\Models\WhatsappChatLabel::whereIn('id', $labelIds)->get(['id', 'name', 'color']);

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

        $combined = $users->concat($leadEmails)
            ->unique(fn ($item) => strtolower(trim($item->email)))
            ->take(10)
            ->values();

        return response()->json([
            'users' => $combined,
        ]);
    }
}
