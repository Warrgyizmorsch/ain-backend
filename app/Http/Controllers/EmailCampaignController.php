<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmailCampaignJob;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\EmailConfiguration;
use App\Models\EmailTemplate;
use App\Models\Leads;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmailCampaignController extends Controller
{
    public function __construct()
    {
        // Enforce feature flag check on all admin methods
        $this->middleware(function ($request, $next) {
            if (!is_email_campaign_enabled()) {
                abort(404, 'Email Campaigns module is currently disabled.');
            }
            return $next($request);
        })->except(['trackOpen', 'trackClick']);
    }

    /**
     * Campaign Dashboard / List Page
     */
    public function index(Request $request): View
    {
        $query = EmailCampaign::with(['emailConfiguration', 'creator'])->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->paginate(15)->withQueryString();

        // Metrics Overview
        $totalCampaigns = EmailCampaign::count();
        $totalSent      = EmailCampaign::sum('sent_count');
        $totalDelivered = EmailCampaign::sum('delivered_count');
        $totalOpens     = EmailCampaign::sum('opened_count');
        $totalClicks    = EmailCampaign::sum('clicked_count');

        $avgOpenRate  = ($totalSent > 0) ? round(($totalOpens / $totalSent) * 100, 1) : 0;
        $avgClickRate = ($totalSent > 0) ? round(($totalClicks / $totalSent) * 100, 1) : 0;

        return view('emails.campaigns.index', compact(
            'campaigns',
            'totalCampaigns',
            'totalSent',
            'totalDelivered',
            'avgOpenRate',
            'avgClickRate'
        ));
    }

    /**
     * Create Campaign Wizard View
     */
    public function create(): View
    {
        $emailConfigurations = EmailConfiguration::where('is_active', true)->get();
        $templates = EmailTemplate::orderBy('name')->get();

        // Distinct options for audience filters
        $leadStatuses = Leads::select('status')->distinct()->whereNotNull('status')->pluck('status')->filter()->values();
        $leadSources  = Leads::select('lead_source')->distinct()->whereNotNull('lead_source')->pluck('lead_source')->filter()->values();

        return view('emails.campaigns.create', compact(
            'emailConfigurations',
            'templates',
            'leadStatuses',
            'leadSources'
        ));
    }

    /**
     * AJAX endpoint to calculate dynamic audience count based on user filters
     */
    public function audienceCount(Request $request): JsonResponse
    {
        $audienceType = $request->input('audience_type', 'leads');
        $filters = $request->input('filters', []);

        $count = $this->queryAudienceCount($audienceType, $filters);

        return response()->json(['success' => true, 'count' => $count]);
    }

    /**
     * Upload an inline image for email campaign body (Quill editor)
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'image' => ['required', 'file', 'max:15360'], // 15MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'Invalid image file.',
            ], 422);
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            // Validate MIME type starts with image/
            $mime = strtolower((string) ($file->getMimeType() ?: $file->getClientMimeType()));
            if (!str_starts_with($mime, 'image/')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded file must be a valid image (JPEG, PNG, GIF, WebP, SVG).',
                ], 422);
            }

            $rawExt = $file->getClientOriginalExtension() ?: ($file->guessExtension() ?: 'png');
            $extension = strtolower($rawExt);
            $filename = 'campaign_' . time() . '_' . Str::random(10) . '.' . $extension;

            $publicDest = public_path('uploads/campaigns');
            $baseDest   = base_path('uploads/campaigns');

            if (!file_exists($publicDest)) {
                @mkdir($publicDest, 0777, true);
            }
            if (!file_exists($baseDest)) {
                @mkdir($baseDest, 0777, true);
            }

            // Move uploaded file to public destination
            $file->move($publicDest, $filename);
            @chmod($publicDest . '/' . $filename, 0644);

            // Also mirror to base project root uploads so root-level web servers (cPanel/Apache) can serve statically
            if ($baseDest !== $publicDest) {
                @copy($publicDest . '/' . $filename, $baseDest . '/' . $filename);
                @chmod($baseDest . '/' . $filename, 0644);
            }

            $isSecure = $request->isSecure()
                || strtolower((string) $request->header('x-forwarded-proto')) === 'https'
                || strtolower((string) $request->server('HTTP_X_FORWARDED_PROTO')) === 'https'
                || strtolower((string) $request->server('HTTPS')) === 'on'
                || str_starts_with((string) config('app.url'), 'https://');

            $url = url('uploads/campaigns/' . $filename, [], $isSecure);

            return response()->json([
                'success' => true,
                'url'     => $url,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No image uploaded or upload was corrupted.'], 400);
    }

    /**
     * Fallback route to serve campaign images directly via PHP stream across all server architectures
     */
    public function serveImage(string $filename)
    {
        $filename = basename($filename);

        $paths = [
            public_path('uploads/campaigns/' . $filename),
            base_path('uploads/campaigns/' . $filename),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $mimeType = mime_content_type($path) ?: 'image/png';
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        abort(404, 'Campaign image not found.');
    }

    /**
     * Store and launch or schedule campaign
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                   => ['required', 'string', 'max:255'],
            'email_configuration_id' => ['required', 'integer', 'exists:email_configurations,id'],
            'subject'                => ['required', 'string', 'max:300'],
            'body_html'              => ['required', 'string'],
            'audience_type'          => ['required', 'string', 'in:all_users,leads,orders,writers,csv'],
            'send_action'            => ['required', 'string', 'in:now,schedule,draft'],
            'scheduled_at'           => ['nullable', 'date'],
            'throttle_rate'          => ['nullable', 'integer', 'min:5', 'max:300'],
            'csv_file'               => ['nullable', 'file', 'mimes:csv,txt'],
        ]);

        $status = match ($validated['send_action']) {
            'now'      => 'processing',
            'schedule' => 'scheduled',
            default    => 'draft',
        };

        $scheduledAt = null;
        if ($validated['send_action'] === 'schedule' && !empty($validated['scheduled_at'])) {
            $scheduledAt = \Carbon\Carbon::parse($validated['scheduled_at']);
        }

        $campaign = EmailCampaign::create([
            'name'                   => $validated['name'],
            'email_configuration_id' => $validated['email_configuration_id'],
            'subject'                => $validated['subject'],
            'body_html'              => $validated['body_html'],
            'status'                 => $status,
            'audience_type'          => $validated['audience_type'],
            'audience_filters'       => $request->input('filters', []),
            'scheduled_at'           => $scheduledAt,
            'throttle_rate'          => $validated['throttle_rate'] ?: 30,
            'created_by'             => Auth::id(),
        ]);

        // Populate Recipients
        $this->populateRecipients($campaign, $request);

        // If Send Now, dispatch job immediately
        if ($campaign->status === 'processing') {
            $campaign->started_at = now();
            $campaign->save();
            ProcessEmailCampaignJob::dispatch($campaign->id);
        }

        return redirect()->route('emails.campaigns.show', $campaign->id)
            ->with('success', 'Email Campaign created successfully.');
    }

    /**
     * Campaign Report & Analytics Page
     */
    public function show(int $id, Request $request): View
    {
        $campaign = EmailCampaign::with(['emailConfiguration', 'creator'])->findOrFail($id);

        $recipientsQuery = EmailCampaignRecipient::where('campaign_id', $campaign->id);

        if ($request->filled('status')) {
            $recipientsQuery->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $recipientsQuery->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $recipients = $recipientsQuery->orderByDesc('id')->paginate(30)->withQueryString();

        return view('emails.campaigns.show', compact('campaign', 'recipients'));
    }

    /**
     * Send Now (Trigger paused or draft campaign)
     */
    public function sendNow(int $id): RedirectResponse
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->status = 'processing';
        if (empty($campaign->started_at)) {
            $campaign->started_at = now();
        }
        $campaign->save();

        ProcessEmailCampaignJob::dispatch($campaign->id);

        return back()->with('success', 'Campaign dispatch started.');
    }

    /**
     * Pause execution
     */
    public function pause(int $id): RedirectResponse
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->status = 'paused';
        $campaign->save();

        return back()->with('success', 'Campaign has been paused.');
    }

    /**
     * Resume execution
     */
    public function resume(int $id): RedirectResponse
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->status = 'processing';
        $campaign->save();

        ProcessEmailCampaignJob::dispatch($campaign->id);

        return back()->with('success', 'Campaign resumed.');
    }

    /**
     * Delete Campaign
     */
    public function destroy(int $id): RedirectResponse
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('emails.campaigns.index')->with('success', 'Campaign deleted.');
    }

    /**
     * 1x1 Transparent GIF Open Tracking Pixel
     */
    public function trackOpen(string $token): Response
    {
        try {
            $recipient = EmailCampaignRecipient::where('tracking_token', $token)->first();
            if ($recipient && empty($recipient->opened_at)) {
                $recipient->opened_at = now();
                $recipient->save();

                EmailCampaign::where('id', $recipient->campaign_id)->increment('opened_count');
            }
        } catch (\Throwable $e) {}

        // 1x1 transparent GIF
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($gif, 200, [
            'Content-Type'  => 'image/gif',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    /**
     * Click Tracking Redirect
     */
    public function trackClick(string $token, Request $request): RedirectResponse
    {
        $destination = $request->input('url', '/');
        $decodedUrl  = urldecode($destination);

        try {
            $recipient = EmailCampaignRecipient::where('tracking_token', $token)->first();
            if ($recipient && empty($recipient->clicked_at)) {
                $recipient->clicked_at = now();
                $recipient->save();

                EmailCampaign::where('id', $recipient->campaign_id)->increment('clicked_count');
            }
        } catch (\Throwable $e) {}

        return redirect()->away($decodedUrl);
    }

    // =========================================================================
    // Private Helpers
    // =========================================================================

    private function queryAudienceCount(string $type, array $filters): int
    {
        return match ($type) {
            'all_users' => $this->buildAllUsersQuery($filters)->count(),
            'leads'     => $this->buildLeadsQuery($filters)->count(),
            'orders'    => $this->buildOrdersQuery($filters)->count(),
            'writers'   => $this->buildWritersQuery($filters)->count(),
            default     => 0,
        };
    }

    private function buildAllUsersQuery(array $filters)
    {
        $query = User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('email', 'not like', '%user%@gmail.com');

        $scope = $filters['user_scope'] ?? 'clients';
        if ($scope === 'clients') {
            $query->where('role_id', 2);
        } elseif (!empty($filters['role_id'])) {
            $query->where('role_id', (int) $filters['role_id']);
        }
        // If scope === 'all', targets all registered user roles

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    private function buildLeadsQuery(array $filters)
    {
        $query = Leads::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('email', 'not like', '%user%@gmail.com');

        if (!empty($filters['statuses']) && is_array($filters['statuses'])) {
            $query->whereIn('status', $filters['statuses']);
        }
        if (!empty($filters['source'])) {
            $query->where('lead_source', $filters['source']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    private function buildOrdersQuery(array $filters)
    {
        $query = Order::query()
            ->join('users', 'orders.uid', '=', 'users.id')
            ->whereNotNull('users.email')
            ->where('users.email', '!=', '')
            ->where('users.email', 'not like', '%user%@gmail.com');

        if (!empty($filters['order_status'])) {
            $query->where('orders.projectstatus', $filters['order_status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('orders.created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('orders.created_at', '<=', $filters['date_to']);
        }

        return $query->select('orders.id as order_id', 'orders.uid as user_id', 'users.email', 'users.name')->distinct();
    }

    private function buildWritersQuery(array $filters)
    {
        $query = User::whereIn('role_id', [6, 7, 8])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('email', 'not like', '%user%@gmail.com');

        if (!empty($filters['writer_status'])) {
            $query->where('status', $filters['writer_status']);
        }

        return $query;
    }

    private function populateRecipients(EmailCampaign $campaign, Request $request): void
    {
        $type = $campaign->audience_type;
        $filters = $campaign->audience_filters ?? [];
        $recipientData = [];
        $now = now();

        if ($type === 'csv' && $request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $header = fgetcsv($handle);
                $emailIdx = 0;
                $nameIdx = 1;
                foreach ($header as $i => $col) {
                    if (str_contains(strtolower($col), 'email')) $emailIdx = $i;
                    if (str_contains(strtolower($col), 'name')) $nameIdx = $i;
                }

                while (($row = fgetcsv($handle)) !== false) {
                    $email = trim($row[$emailIdx] ?? '');
                    $name  = trim($row[$nameIdx] ?? '');
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $recipientData[] = [
                            'campaign_id'     => $campaign->id,
                            'recipient_email' => $email,
                            'recipient_name'  => $name ?: 'Customer',
                            'status'          => 'pending',
                            'tracking_token'  => Str::random(40),
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ];
                    }
                }
                fclose($handle);
            }
        } elseif ($type === 'all_users') {
            $this->buildAllUsersQuery($filters)
                ->select(['id', 'email', 'name'])
                ->chunk(1000, function ($users) use ($campaign, $now, &$recipientData) {
                    foreach ($users as $user) {
                        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                            $recipientData[] = [
                                'campaign_id'     => $campaign->id,
                                'recipient_email' => $user->email,
                                'recipient_name'  => $user->name ?: 'Customer',
                                'user_id'         => $user->id,
                                'status'          => 'pending',
                                'tracking_token'  => Str::random(40),
                                'created_at'      => $now,
                                'updated_at'      => $now,
                            ];
                        }
                    }
                });
        } elseif ($type === 'leads') {
            $leads = $this->buildLeadsQuery($filters)->get(['id', 'email', 'user_name']);
            foreach ($leads as $lead) {
                if (filter_var($lead->email, FILTER_VALIDATE_EMAIL)) {
                    $recipientData[] = [
                        'campaign_id'     => $campaign->id,
                        'recipient_email' => $lead->email,
                        'recipient_name'  => $lead->user_name ?: 'Customer',
                        'lead_id'         => $lead->id,
                        'status'          => 'pending',
                        'tracking_token'  => Str::random(40),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }
        } elseif ($type === 'orders') {
            $orders = $this->buildOrdersQuery($filters)->get();
            foreach ($orders as $ord) {
                if (filter_var($ord->email, FILTER_VALIDATE_EMAIL)) {
                    $recipientData[] = [
                        'campaign_id'     => $campaign->id,
                        'recipient_email' => $ord->email,
                        'recipient_name'  => $ord->name ?: 'Customer',
                        'user_id'         => $ord->user_id,
                        'status'          => 'pending',
                        'tracking_token'  => Str::random(40),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }
        } elseif ($type === 'writers') {
            $writers = $this->buildWritersQuery($filters)->get(['id', 'email', 'name']);
            foreach ($writers as $writer) {
                if (filter_var($writer->email, FILTER_VALIDATE_EMAIL)) {
                    $recipientData[] = [
                        'campaign_id'     => $campaign->id,
                        'recipient_email' => $writer->email,
                        'recipient_name'  => $writer->name ?: 'Writer',
                        'user_id'         => $writer->id,
                        'status'          => 'pending',
                        'tracking_token'  => Str::random(40),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }
        }

        // De-duplicate by email for this campaign
        $uniqueRecipients = collect($recipientData)->unique('recipient_email')->values()->all();

        // Chunk insert
        foreach (array_chunk($uniqueRecipients, 500) as $chunk) {
            EmailCampaignRecipient::insert($chunk);
        }

        $campaign->total_recipients = count($uniqueRecipients);
        $campaign->save();
    }
}
