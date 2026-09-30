<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PluginSetting;
use App\Models\TwilioCallLog;
use App\Models\Next2CallLog;
use App\Services\TwilioVoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PluginController extends Controller
{
    protected ?TwilioVoiceService $twilioService = null;

    /**
     * Lazy-load TwilioVoiceService only when actually needed.
     * This prevents errors when Twilio is not configured.
     */
    protected function twilio(): TwilioVoiceService
    {
        if ($this->twilioService === null) {
            $this->twilioService = app(TwilioVoiceService::class);
        }
        return $this->twilioService;
    }

    /**
     * Display the plugins directory / settings page.
     */
    public function index(): View
    {
        $currentUser = Auth::user();
        $currentUserPhone = $currentUser ? ($currentUser->mobile ?? $currentUser->mobile_no ?? '') : '';
        $emailAccountsCount = \App\Models\EmailConfiguration::count();
        $activeEmailAccounts = \App\Models\EmailConfiguration::where('is_active', true)->count();

        $twilioPlugin = PluginSetting::firstOrCreate(
            ['plugin_key' => 'twilio_call'],
            [
                'name' => 'Twilio Voice Call',
                'category' => 'communication',
                'description' => 'Bridge voice calls between agents and customers directly from the Orders page using Twilio Voice API & WebRTC Dialer.',
                'is_active' => true,
                'settings' => [
                    'account_sid' => env('TWILIO_ACCOUNT_SID', env('TWILIO_SID', '')),
                    'auth_token' => env('TWILIO_AUTH_TOKEN', env('TWILIO_TOKEN', '')),
                    'twilio_number' => env('TWILIO_NUMBER', env('TWILIO_PHONE_NUMBER', env('TWILIO_FROM', ''))),
                    'api_key_sid' => env('TWILIO_API_KEY_SID', env('TWILIO_API_KEY', '')),
                    'api_secret' => env('TWILIO_API_SECRET', env('TWILIO_SECRET', '')),
                    'twiml_app_sid' => env('TWILIO_TWIML_APP_SID', env('TWILIO_APP_SID', '')),
                    'default_agent_number' => env('TWILIO_AGENT_NUMBER', ''),
                    'call_mode' => 'webrtc',
                    'record_calls' => false,
                ],
            ]
        );

        // If existing settings have empty keys, fallback to .env
        $twCurrent = $twilioPlugin->settings ?? [];
        $twChanged = false;
        if (empty($twCurrent['account_sid']) && (env('TWILIO_ACCOUNT_SID') || env('TWILIO_SID'))) {
            $twCurrent['account_sid'] = env('TWILIO_ACCOUNT_SID', env('TWILIO_SID'));
            $twChanged = true;
        }
        if (empty($twCurrent['auth_token']) && (env('TWILIO_AUTH_TOKEN') || env('TWILIO_TOKEN'))) {
            $twCurrent['auth_token'] = env('TWILIO_AUTH_TOKEN', env('TWILIO_TOKEN'));
            $twChanged = true;
        }
        if (empty($twCurrent['twilio_number']) && (env('TWILIO_NUMBER') || env('TWILIO_PHONE_NUMBER') || env('TWILIO_FROM'))) {
            $twCurrent['twilio_number'] = env('TWILIO_NUMBER', env('TWILIO_PHONE_NUMBER', env('TWILIO_FROM')));
            $twChanged = true;
        }
        if (empty($twCurrent['api_key_sid']) && (env('TWILIO_API_KEY_SID') || env('TWILIO_API_KEY'))) {
            $twCurrent['api_key_sid'] = env('TWILIO_API_KEY_SID', env('TWILIO_API_KEY'));
            $twChanged = true;
        }
        if (empty($twCurrent['api_secret']) && (env('TWILIO_API_SECRET') || env('TWILIO_SECRET'))) {
            $twCurrent['api_secret'] = env('TWILIO_API_SECRET', env('TWILIO_SECRET'));
            $twChanged = true;
        }
        if (empty($twCurrent['twiml_app_sid']) && (env('TWILIO_TWIML_APP_SID') || env('TWILIO_APP_SID'))) {
            $twCurrent['twiml_app_sid'] = env('TWILIO_TWIML_APP_SID', env('TWILIO_APP_SID'));
            $twChanged = true;
        }
        if ($twChanged) {
            $twilioPlugin->settings = $twCurrent;
            $twilioPlugin->save();
        }

        $next2callPlugin = PluginSetting::firstOrCreate(
            ['plugin_key' => 'next2call'],
            [
                'name' => 'Next2Call Softphone',
                'category' => 'communication',
                'description' => 'Direct in-browser WebRTC softphone calling & click-to-dial powered by Next2Call Ringfy PBX.',
                'is_active' => true,
                'settings' => [
                    'user_id' => '10101',
                    'password' => 'T2d8d1r5P6x0T8O8iUq',
                    'sip_domain' => 'ringfy.next2call.com',
                    'api_base_url' => 'https://ringfy.next2call.com',
                    'click_to_dial_path' => '/softphone/Phone/click-to-dial.html',
                ],
            ]
        );

        // Ensure default settings exist for testing if empty
        $n2cCurrent = $next2callPlugin->settings ?? [];
        $n2cChanged = false;
        if (empty($n2cCurrent['user_id'])) {
            $n2cCurrent['user_id'] = '10101';
            $n2cChanged = true;
        }
        if (empty($n2cCurrent['password'])) {
            $n2cCurrent['password'] = 'T2d8d1r5P6x0T8O8iUq';
            $n2cChanged = true;
        }
        if (empty($n2cCurrent['sip_domain'])) {
            $n2cCurrent['sip_domain'] = 'ringfy.next2call.com';
            $n2cChanged = true;
        }
        if (empty($n2cCurrent['api_base_url'])) {
            $n2cCurrent['api_base_url'] = 'https://ringfy.next2call.com';
            $n2cChanged = true;
        }
        if (empty($n2cCurrent['click_to_dial_path']) || str_contains($n2cCurrent['click_to_dial_path'], 'index.html')) {
            $n2cCurrent['click_to_dial_path'] = '/softphone/Phone/click-to-dial.html';
            $n2cChanged = true;
        }
        if ($n2cChanged) {
            $next2callPlugin->settings = $n2cCurrent;
            $next2callPlugin->save();
        }

        return view('back-end.plugins.index', [
            'twilioPlugin' => $twilioPlugin,
            'next2callPlugin' => $next2callPlugin,
            'currentUserPhone' => $currentUserPhone,
            'emailAccountsCount' => $emailAccountsCount,
            'activeEmailAccounts' => $activeEmailAccounts,
        ]);
    }

    /**
     * Dedicated Next2Call Softphone page (separate menu entry).
     */
    public function next2callPage(): View
    {
        $next2callPlugin = PluginSetting::firstOrCreate(
            ['plugin_key' => 'next2call'],
            [
                'name' => 'Next2Call Softphone',
                'category' => 'communication',
                'description' => 'Direct in-browser WebRTC softphone calling & click-to-dial powered by Next2Call Ringfy PBX.',
                'is_active' => true,
                'settings' => [
                    'user_id' => '10101',
                    'password' => 'T2d8d1r5P6x0T8O8iUq',
                    'sip_domain' => 'ringfy.next2call.com',
                    'api_base_url' => 'https://ringfy.next2call.com',
                    'click_to_dial_path' => '/softphone/Phone/click-to-dial.html',
                ],
            ]
        );

        $currentUser = Auth::user();
        $currentUserPhone = $currentUser ? ($currentUser->mobile ?? $currentUser->mobile_no ?? '') : '';

        $creds = self::resolveNext2CallCredentials();
        $session = self::getNext2CallSession($creds['user_id'], $creds['password']);

        $userId   = $session['user_id'] ?? $creds['user_id'];
        $password = $creds['password'];
        $sipDomain = $creds['sip_domain'];
        $clickPath = $creds['click_to_dial_path'];

        $dialerUrl = $session['webphone_url'] ?? ("https://{$sipDomain}/api-section/softphone/Phone/index.html?" . http_build_query([
            'profileName' => $userId,
            'SipDomain'   => $sipDomain,
            'SipUsername' => $userId,
            'SipPassword' => $password,
        ]));

        $ctcBaseUrl = $session['click_to_call_url'] ?? ("https://{$sipDomain}{$clickPath}?" . http_build_query([
            'profileName' => $userId,
            'SipDomain'   => $sipDomain,
            'SipUsername' => $userId,
            'SipPassword' => $password,
        ]) . '&d=');

        return view('back-end.plugins.next2call', [
            'next2callPlugin' => $next2callPlugin,
            'currentUserPhone' => $currentUserPhone,
            'dialerUrl' => $dialerUrl,
            'ctcBaseUrl' => $ctcBaseUrl,
        ]);
    }

    /**
     * Save Twilio Call Plugin settings.
     */
    public function saveTwilio(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'account_sid' => ['nullable', 'string', 'max:255'],
            'auth_token' => ['nullable', 'string', 'max:255'],
            'twilio_number' => ['nullable', 'string', 'max:50'],
            'api_key_sid' => ['nullable', 'string', 'max:255'],
            'api_secret' => ['nullable', 'string', 'max:255'],
            'twiml_app_sid' => ['nullable', 'string', 'max:255'],
            'default_agent_number' => ['nullable', 'string', 'max:50'],
            'call_mode' => ['nullable', 'string', 'in:webrtc,bridge,direct'],
            'record_calls' => ['nullable', 'boolean'],
            'is_active' => ['nullable'],
        ]);

        $isActive = $request->boolean('is_active');

        $plugin = PluginSetting::firstOrNew(['plugin_key' => 'twilio_call']);
        $plugin->name = 'Twilio Voice Call';
        $plugin->category = 'communication';
        $plugin->description = 'Bridge voice calls between agents and customers directly from the Orders page using Twilio Voice API & WebRTC Dialer.';
        $plugin->is_active = $isActive;
        $plugin->settings = [
            'account_sid' => trim($validated['account_sid'] ?? ''),
            'auth_token' => trim($validated['auth_token'] ?? ''),
            'twilio_number' => trim($validated['twilio_number'] ?? ''),
            'api_key_sid' => trim($validated['api_key_sid'] ?? ''),
            'api_secret' => trim($validated['api_secret'] ?? ''),
            'twiml_app_sid' => trim($validated['twiml_app_sid'] ?? ''),
            'default_agent_number' => trim($validated['default_agent_number'] ?? ''),
            'call_mode' => $validated['call_mode'] ?? 'webrtc',
            'record_calls' => (bool) ($validated['record_calls'] ?? false),
        ];
        $plugin->updated_by = Auth::id();
        if (!$plugin->exists) {
            $plugin->created_by = Auth::id();
        }
        $plugin->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Twilio Voice Calling plugin settings saved successfully.',
                'plugin' => $plugin,
            ]);
        }

        return back()->with('success', 'Twilio Voice Calling plugin settings saved successfully.');
    }

    /**
     * Save Next2Call Softphone Plugin settings.
     */
    public function saveNext2call(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
            'sip_domain' => ['required', 'string', 'max:255'],
            'api_base_url' => ['nullable', 'string', 'max:255'],
            'click_to_dial_path' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable'],
        ]);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : false;

        $plugin = PluginSetting::firstOrNew(['plugin_key' => 'next2call']);
        $plugin->name = 'Next2Call Softphone';
        $plugin->category = 'communication';
        $plugin->description = 'Direct in-browser WebRTC softphone calling & click-to-dial powered by Next2Call Ringfy PBX.';
        $plugin->is_active = $isActive;
        $plugin->settings = [
            'user_id' => trim($validated['user_id']),
            'password' => trim($validated['password']),
            'sip_domain' => trim($validated['sip_domain']),
            'api_base_url' => trim($validated['api_base_url'] ?: 'https://' . trim($validated['sip_domain'])),
            'click_to_dial_path' => trim($validated['click_to_dial_path'] ?: '/api-section/softphone/Phone/index.html'),
        ];
        $plugin->updated_by = Auth::id();
        if (!$plugin->exists) {
            $plugin->created_by = Auth::id();
        }
        $plugin->save();

        // Clear session cache and generate fresh 12h token
        Cache::forget("next2call_session_" . trim($validated['user_id']));
        self::getNext2CallSession(trim($validated['user_id']), trim($validated['password']), true);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Next2Call Softphone settings saved successfully.',
                'plugin' => $plugin,
            ]);
        }

        return back()->with('success', 'Next2Call Softphone settings saved successfully.');
    }

    /**
     * Resolve effective Next2Call credentials.
     * Prioritizes per-user SIP ID (users.sip or users.call_id) and optional per-user SIP password.
     * Falls back to admin default Next2Call settings for testing if user has no SIP configured.
     */
    public static function resolveNext2CallCredentials(?string $userId = null, ?string $password = null, ?\App\Models\User $user = null): array
    {
        $plugin = PluginSetting::where('plugin_key', 'next2call')->first();
        $settings = $plugin?->settings ?? [];

        $defaultUserId = !empty($settings['user_id']) ? (string) $settings['user_id'] : '10101';
        $defaultPassword = !empty($settings['password']) ? (string) $settings['password'] : 'T2d8d1r5P6x0T8O8iUq';
        $sipDomain = !empty($settings['sip_domain']) ? (string) $settings['sip_domain'] : 'ringfy.next2call.com';
        $apiBaseUrl = rtrim(!empty($settings['api_base_url']) ? (string) $settings['api_base_url'] : 'https://ringfy.next2call.com', '/');
        $clickToDialPath = (!empty($settings['click_to_dial_path']) && !str_contains($settings['click_to_dial_path'], 'index.html'))
            ? (string) $settings['click_to_dial_path']
            : '/api-section/softphone/Phone/click-to-dial.html';
        if (!str_contains($clickToDialPath, 'api-section')) {
            $clickToDialPath = '/api-section' . (str_starts_with($clickToDialPath, '/') ? $clickToDialPath : '/' . $clickToDialPath);
        }

        $targetUser = $user ?: (Auth::check() ? Auth::user() : null);

        // Per-user SIP ID:
        if (empty($userId) && $targetUser) {
            if (!empty($targetUser->sip)) {
                $userId = (string) $targetUser->sip;
            } elseif (!empty($targetUser->call_id)) {
                $userId = (string) $targetUser->call_id;
            }
        }

        // Per-user SIP Password (if custom password set on user profile):
        if (empty($password) && $targetUser && !empty($targetUser->sip_password)) {
            $password = (string) $targetUser->sip_password;
        }

        // Fallbacks to admin defaults for testing / shared PBX:
        if (empty($userId)) {
            $userId = $defaultUserId;
        }
        if (empty($password)) {
            $password = $defaultPassword;
        }

        return [
            'user_id'            => trim($userId),
            'password'           => trim($password),
            'sip_domain'         => $sipDomain,
            'api_base_url'       => $apiBaseUrl,
            'click_to_dial_path' => $clickToDialPath,
            'is_active'          => (bool) ($plugin?->is_active ?? true),
        ];
    }

    /**
     * Get or refresh active Next2Call session with 12-hour caching & JWT expiration checking.
     * When token is expired or older than 12 hours, automatically re-authenticates and generates a fresh token.
     */
    public static function getNext2CallSession(?string $userId = null, ?string $password = null, bool $forceRefresh = false): ?array
    {
        $creds = self::resolveNext2CallCredentials($userId, $password);
        $userId = $creds['user_id'];
        $password = $creds['password'];
        $apiBaseUrl = $creds['api_base_url'];

        if (empty($userId) || empty($password)) {
            return null;
        }

        // 1. Check in DB: Check when last session was generated for this SIP user
        if (!$forceRefresh) {
            $dbSession = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('next2call_sessions')) {
                $dbSession = \App\Models\Next2CallSession::where('user_id', $userId)->latest('id')->first();
            }

            // Fallback check in plugin_settings table in DB
            if (!$dbSession) {
                $plugin = PluginSetting::where('plugin_key', 'next2call')->first();
                $saved = data_get($plugin?->settings, "sip_sessions.{$userId}");
                if ($saved && !empty($saved['token'])) {
                    $dbSession = (object) $saved;
                }
            }

            if ($dbSession && !empty($dbSession->token)) {
                $generatedAt = !empty($dbSession->generated_at)
                    ? ($dbSession->generated_at instanceof \Carbon\Carbon ? $dbSession->generated_at : \Carbon\Carbon::parse($dbSession->generated_at))
                    : null;
                $expiresAt = !empty($dbSession->expires_at)
                    ? ($dbSession->expires_at instanceof \Carbon\Carbon ? $dbSession->expires_at : \Carbon\Carbon::parse($dbSession->expires_at))
                    : null;

                // Check elapsed time since generated_at (12 hours limit)
                $hoursElapsed = $generatedAt ? $generatedAt->diffInHours(now(), false) : 999;
                $hasExpired = ($hoursElapsed >= 12) || ($expiresAt && now()->addSeconds(120)->greaterThanOrEqualTo($expiresAt));

                if (!$hasExpired) {
                    Log::info("[Next2Call DB] Reusing valid 12h session for SIP {$userId} from DB (generated {$hoursElapsed}h ago at " . ($generatedAt ? $generatedAt->toDateTimeString() : 'N/A') . ")");

                    return [
                        'token'             => $dbSession->token,
                        'token_type'        => 'Bearer',
                        'expires_in'        => '12h',
                        'expires_at'        => $expiresAt ? $expiresAt->timestamp : (time() + (12 * 3600)),
                        'agent_status'      => $dbSession->agent_status ?? 1,
                        'webphone_url'      => $dbSession->webphone_url ?? '',
                        'click_to_call_url' => $dbSession->click_to_call_url ?? '',
                        'user_id'           => $userId,
                        'generated_at'      => $generatedAt ? $generatedAt->toDateTimeString() : now()->toDateTimeString(),
                        'from_db'           => true,
                    ];
                }

                Log::info("[Next2Call DB] Session for SIP {$userId} in DB has expired (generated: " . ($generatedAt ? $generatedAt->toDateTimeString() : 'N/A') . ", elapsed: {$hoursElapsed}h). Generating new token...");
            }
        }

        // 2. If >= 12h or not found in DB: Call Next2Call Webphone Login API to generate new token and iframe URLs
        try {
            $response = Http::timeout(10)->post("{$apiBaseUrl}/mobileapi/api/webphone_login", [
                'user_id' => $userId,
                'password' => $password,
            ]);

            $data = $response->json();

            if ($response->successful() && !empty($data['token'])) {
                $token = $data['token'];
                $now = now();
                $expiresAt = now()->addHours(12);

                // Decode JWT to extract exact exp timestamp if available
                $parts = explode('.', $token);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
                    if (!empty($payload['exp'])) {
                        $expiresAt = \Carbon\Carbon::createFromTimestamp((int) $payload['exp']);
                    }
                }

                $rawCtc = (string) ($data['click_to_call_url'] ?? '');
                $autoDialCtc = !empty($rawCtc)
                    ? str_replace('index.html', 'click-to-dial.html', $rawCtc)
                    : ("https://{$creds['sip_domain']}/api-section/softphone/Phone/click-to-dial.html?" . http_build_query([
                        'profileName' => $userId,
                        'SipDomain'   => $creds['sip_domain'],
                        'SipUsername' => $userId,
                        'SipPassword' => $password,
                    ]) . '&d=');
                if (!str_contains($autoDialCtc, 'api-section')) {
                    $autoDialCtc = str_replace('/softphone/Phone/', '/api-section/softphone/Phone/', $autoDialCtc);
                }
                if (!str_contains($autoDialCtc, '&d=')) {
                    $autoDialCtc .= '&d=';
                }

                $webphoneUrl = $data['webphone_url'] ?? ("https://{$creds['sip_domain']}/api-section/softphone/Phone/index.html?" . http_build_query([
                    'profileName' => $userId,
                    'SipDomain'   => $creds['sip_domain'],
                    'SipUsername' => $userId,
                    'SipPassword' => $password,
                ]));

                // 3. Save / Submit to DB: Record new session in next2call_sessions table
                if (\Illuminate\Support\Facades\Schema::hasTable('next2call_sessions')) {
                    \App\Models\Next2CallSession::updateOrCreate(
                        ['user_id' => $userId],
                        [
                            'token'             => $token,
                            'webphone_url'      => $webphoneUrl,
                            'click_to_call_url' => $autoDialCtc,
                            'generated_at'      => $now,
                            'expires_at'        => $expiresAt,
                            'agent_status'      => $data['agent_status'] ?? 1,
                        ]
                    );
                }

                // Also update plugin_settings table in DB for persistent backup
                $plugin = PluginSetting::where('plugin_key', 'next2call')->first();
                if ($plugin) {
                    $settings = $plugin->settings ?? [];
                    $settings['sip_sessions'][$userId] = [
                        'token'             => $token,
                        'webphone_url'      => $webphoneUrl,
                        'click_to_call_url' => $autoDialCtc,
                        'generated_at'      => $now->toDateTimeString(),
                        'expires_at'        => $expiresAt->toDateTimeString(),
                        'agent_status'      => $data['agent_status'] ?? 1,
                    ];
                    $plugin->settings = $settings;
                    $plugin->save();
                }

                $session = [
                    'token'             => $token,
                    'token_type'        => $data['token_type'] ?? 'Bearer',
                    'expires_in'        => $data['expires_in'] ?? '12h',
                    'expires_at'        => $expiresAt->timestamp,
                    'agent_status'      => $data['agent_status'] ?? 1,
                    'webphone_url'      => $webphoneUrl,
                    'click_to_call_url' => $autoDialCtc,
                    'user'              => $data['user'] ?? null,
                    'user_id'           => $userId,
                    'api_base_url'      => $apiBaseUrl,
                    'generated_at'      => $now->toDateTimeString(),
                    'from_db'           => false,
                ];

                Log::info("[Next2Call DB] Generated NEW 12h session and saved to DB for SIP {$userId}", [
                    'generated_at' => $now->toDateTimeString(),
                    'expires_at'   => $expiresAt->toDateTimeString(),
                ]);

                return $session;
            }

            Log::warning('[Next2Call] Webphone login failed', [
                'user_id'  => $userId,
                'status'   => $response->status(),
                'response' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('[Next2Call] Webphone login exception: ' . $e->getMessage(), ['user_id' => $userId]);
        }

        return null;
    }

    /**
     * Sync call logs from Next2Call Agent Call Report API into local database table next2call_call_logs
     */
    public static function syncNext2CallLogs(?string $userId = null, int $limit = 100): int
    {
        $session = self::getNext2CallSession($userId);
        if (!$session || empty($session['token'])) {
            return 0;
        }

        $token = $session['token'];
        $apiBaseUrl = $session['api_base_url'] ?? 'https://ringfy.next2call.com';
        $agentUserId = $session['user_id'] ?? $userId;

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->get("{$apiBaseUrl}/mobileapi/api/agent-call-report", [
                    'limit' => $limit,
                ]);

            if ($response->status() === 401) {
                // Token expired - re-authenticate and retry once (per Agent API Guide Section 6)
                $session = self::getNext2CallSession($userId, null, true);
                if ($session && !empty($session['token'])) {
                    $token = $session['token'];
                    $response = Http::withToken($token)
                        ->timeout(15)
                        ->get("{$apiBaseUrl}/mobileapi/api/agent-call-report", [
                            'limit' => $limit,
                        ]);
                }
            }

            if (!$response->successful()) {
                Log::warning('Next2Call sync failed HTTP: ' . $response->status());
                return 0;
            }

            $data = $response->json();
            $records = $data['data'] ?? [];
            if (!is_array($records) || empty($records)) {
                return 0;
            }

            $syncedCount = 0;
            foreach ($records as $item) {
                if (empty($item['id'])) continue;

                $next2callId = (string) $item['id'];
                $log = Next2CallLog::firstOrNew(['next2call_id' => $next2callId]);

                $log->uniqueid = (string) ($item['uniqueid'] ?? $log->uniqueid);
                $log->did = (string) ($item['did'] ?? $log->did);
                $log->direction = strtolower($item['direction'] ?? ($log->direction ?: 'outbound'));
                $log->status = strtoupper($item['status'] ?? ($log->status ?: 'ANSWER'));
                $log->call_from = (string) ($item['call_from'] ?? $log->call_from);
                $log->call_to = (string) ($item['call_to'] ?? $log->call_to);
                $log->hangup = (string) ($item['hangup'] ?? $log->hangup);
                $log->campaign_id = (string) ($item['campaign_id'] ?? $log->campaign_id);
                $log->agent_id = (string) ($agentUserId ?: $log->agent_id);

                if (!empty($item['record_url'])) {
                    $log->record_url = $item['record_url'];
                }

                // Duration handling
                if (isset($item['dur'])) {
                    $log->duration = (int) $item['dur'];
                } elseif (!empty($item['duration'])) {
                    $parts = explode(':', (string) $item['duration']);
                    if (count($parts) === 3) {
                        $log->duration = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
                    }
                }
                $log->duration_formatted = $item['duration_formatted'] ?? ($item['duration'] ?? gmdate('H:i:s', $log->duration));

                // Date parsing (format '2026-09-28-16:57:49' or '2026-09-28 16:57:49')
                if (!empty($item['start_time'])) {
                    $cleanStart = preg_replace('/^(\d{4}-\d{2}-\d{2})-(\d{2}:\d{2}:\d{2})$/', '$1 $2', $item['start_time']);
                    try {
                        $log->started_at = \Carbon\Carbon::parse($cleanStart);
                        if (!$log->created_at) {
                            $log->created_at = $log->started_at;
                        }
                    } catch (\Throwable $e) {}
                }
                if (!empty($item['end_time'])) {
                    $cleanEnd = preg_replace('/^(\d{4}-\d{2}-\d{2})-(\d{2}:\d{2}:\d{2})$/', '$1 $2', $item['end_time']);
                    try {
                        $log->ended_at = \Carbon\Carbon::parse($cleanEnd);
                    } catch (\Throwable $e) {}
                }

                // Resolve customer name if not set
                if (empty($log->customer_name)) {
                    $candidatePhone = ($log->call_to == $agentUserId) ? $log->call_from : $log->call_to;
                    $digits = preg_replace('/\D/', '', (string) $candidatePhone);
                    if (strlen($digits) >= 7) {
                        $last10 = substr($digits, -10);
                        try {
                            $customer = \App\Models\User::whereRaw("REPLACE(REPLACE(mobile_no, ' ', ''), '-', '') LIKE ?", ['%' . $last10])
                                ->orWhereRaw("REPLACE(REPLACE(mobile_no2, ' ', ''), '-', '') LIKE ?", ['%' . $last10])
                                ->first();
                            if ($customer) {
                                $log->customer_name = $customer->name;
                            }
                        } catch (\Throwable $ex) {}
                    }
                }

                if (Auth::check() && empty($log->agent_user_id)) {
                    $log->agent_user_id = Auth::id();
                }

                $log->save();
                $syncedCount++;
            }

            return $syncedCount;
        } catch (\Throwable $e) {
            Log::error('Next2Call syncNext2CallLogs error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * AJAX endpoint to trigger manual Next2Call call history sync
     */
    public function next2callSyncNow(Request $request): JsonResponse
    {
        $count = self::syncNext2CallLogs(null, (int) $request->input('limit', 100));
        return response()->json([
            'success' => true,
            'message' => "Synced {$count} calls from Next2Call PBX successfully.",
            'count' => $count,
        ]);
    }

    /**
     * Download and save Next2Call audio recording locally to CRM server
     */
    public function next2callSaveRecording(Request $request): mixed
    {
        $id = $request->input('id');
        $recordUrl = $request->input('record_url');

        $log = null;
        if (!empty($id)) {
            $log = Next2CallLog::where('id', $id)->orWhere('next2call_id', $id)->first();
        }

        if (!$log && !empty($recordUrl)) {
            $log = Next2CallLog::where('record_url', $recordUrl)->first();
        }

        $urlToDownload = $log ? $log->record_url : $recordUrl;
        if (empty($urlToDownload)) {
            return response()->json([
                'success' => false,
                'message' => 'Recording URL not found.',
            ], 404);
        }

        $storageDir = public_path('storage/next2call_recordings');
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }

        // Determine filename
        $parsedPath = parse_url($urlToDownload, PHP_URL_PATH);
        $originalFilename = $parsedPath ? basename($parsedPath) : ('rec_' . time() . '.wav');
        if (!str_ends_with(strtolower($originalFilename), '.wav')) {
            $originalFilename .= '.wav';
        }

        $cleanBase = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $originalFilename);
        $safeName = $log ? ("next2call_" . ($log->id ?: $log->next2call_id) . "_" . $cleanBase) : $cleanBase;
        $localRelativePath = 'storage/next2call_recordings/' . $safeName;
        $fullPath = public_path($localRelativePath);

        // If file already downloaded and exists
        if (file_exists($fullPath) && filesize($fullPath) > 0) {
            if ($log && empty($log->local_record_path)) {
                $log->local_record_path = $localRelativePath;
                $log->save();
            }

            if ($request->boolean('download')) {
                return response()->download($fullPath, $safeName, ['Content-Type' => 'audio/wav']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Recording already saved locally on server.',
                'already_exists' => true,
                'local_url' => asset($localRelativePath),
                'file_name' => $safeName,
                'size' => filesize($fullPath),
            ]);
        }

        try {
            $downloadRes = Http::timeout(45)->get($urlToDownload);
            if (!$downloadRes->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to download recording from Next2Call server (HTTP ' . $downloadRes->status() . ').',
                ], 502);
            }

            $body = $downloadRes->body();
            if (empty($body)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Received empty recording file from Next2Call.',
                ], 502);
            }

            file_put_contents($fullPath, $body);

            if ($log) {
                $log->local_record_path = $localRelativePath;
                $log->save();
            }

            if ($request->boolean('download')) {
                return response()->download($fullPath, $safeName, ['Content-Type' => 'audio/wav']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Recording downloaded and saved locally in CRM server successfully.',
                'local_url' => asset($localRelativePath),
                'file_name' => $safeName,
                'size' => strlen($body),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving recording: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webphone Login API - Authenticate agent with Next2Call (Ringfy) Agent API
     * Uses 12-hour session caching.
     */
    public function next2callLogin(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $password = $request->input('password');

        $session = self::getNext2CallSession($userId, $password, true);

        if ($session && !empty($session['token'])) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $session['token'],
                'token_type' => $session['token_type'],
                'expires_in' => $session['expires_in'],
                'agent_status' => $session['agent_status'],
                'webphone_url' => $session['webphone_url'],
                'click_to_call_url' => $session['click_to_call_url'],
                'user' => $session['user'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Next2Call authentication failed. Please verify credentials.',
        ], 401);
    }

    /**
     * Agent Call Report API - Fetch call history & recording URLs (.wav)
     */
    public function next2callCallReport(Request $request): JsonResponse
    {
        $session = self::getNext2CallSession();
        if (!$session || empty($session['token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to obtain Next2Call API access token. Please verify credentials.',
            ], 401);
        }

        $token = $session['token'];
        $apiBaseUrl = $session['api_base_url'] ?? 'https://ringfy.next2call.com';

        try {
            $params = [
                'limit' => (int) $request->input('limit', 50),
            ];
            if ($request->filled('status') && $request->input('status') !== 'all') {
                $params['status'] = $request->input('status');
            }
            if ($request->filled('direction') && $request->input('direction') !== 'all') {
                $params['direction'] = $request->input('direction');
            }
            if ($request->filled('before_id')) {
                $params['before_id'] = $request->input('before_id');
            }

            $response = Http::withToken($token)
                ->timeout(12)
                ->get("{$apiBaseUrl}/mobileapi/api/agent-call-report", $params);

            if ($response->status() === 401) {
                $session = self::getNext2CallSession(null, null, true);
                if ($session && !empty($session['token'])) {
                    $token = $session['token'];
                    $response = Http::withToken($token)
                        ->timeout(12)
                        ->get("{$apiBaseUrl}/mobileapi/api/agent-call-report", $params);
                }
            }

            $data = $response->json();

            // Mask phone numbers for non-admins if role_id != 1
            if (Auth::check() && (int) Auth::user()->role_id !== 1 && !empty($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as &$record) {
                    if (!empty($record['call_from'])) {
                        $record['call_from_raw'] = $record['call_from'];
                        $record['call_from'] = mask_raw_phone($record['call_from']);
                    }
                }
            }

            return response()->json($data, $response->status());

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Next2Call call report: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Next2Call configuration & generate a test click-to-dial URL.
     */
    public function testNext2call(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'test_phone_number' => ['required', 'string', 'min:6'],
        ]);

        $creds = self::resolveNext2CallCredentials();
        $sipDomain = $creds['sip_domain'];
        $path = $creds['click_to_dial_path'];

        $number = preg_replace('/[^0-9]/', '', $validated['test_phone_number']);
        if (str_starts_with($number, '0') && strlen($number) === 11) {
            $number = substr($number, 1);
        } elseif (strlen($number) === 10) {
            $number = '91' . $number;
        }

        $session = self::getNext2CallSession($creds['user_id'], $creds['password']);
        $loginSuccess = ($session && !empty($session['token']));

        if ($loginSuccess && !empty($session['click_to_call_url'])) {
            $webphoneUrl = $session['webphone_url'];
            $baseCtc = $session['click_to_call_url'];
            $clickToCallUrl = str_ends_with($baseCtc, '=') ? ($baseCtc . $number) : ($baseCtc . '&d=' . $number);
            $userId = $session['user_id'];
            $agentStatus = $session['agent_status'] ?? 1;
        } else {
            $userId = $creds['user_id'];
            $password = $creds['password'];
            $agentStatus = 1;
            $clickToCallUrl = "https://{$sipDomain}{$path}?" . http_build_query([
                'profileName' => $userId,
                'SipDomain'   => $sipDomain,
                'SipUsername' => $userId,
                'SipPassword' => $password,
                'd'           => $number,
            ]);

            $dialerQuery = http_build_query([
                'profileName' => $userId,
                'SipDomain'   => $sipDomain,
                'SipUsername' => $userId,
                'SipPassword' => $password,
            ]);
            $webphoneUrl = "https://{$sipDomain}/api-section/softphone/Phone/index.html?" . $dialerQuery;
        }

        return response()->json([
            'success' => true,
            'message' => $loginSuccess 
                ? 'Ringfy Agent API login verified & click-to-dial URL generated.' 
                : 'Next2Call click-to-dial test URL generated successfully.',
            'api_verified' => $loginSuccess,
            'agent_status' => $agentStatus,
            'dial_url' => $clickToCallUrl,
            'click_to_call_url' => $clickToCallUrl,
            'dialer_url' => $webphoneUrl,
            'webphone_url' => $webphoneUrl,
            'user_id' => $userId,
            'sip_domain' => $sipDomain,
            'target_number' => $number,
        ]);
    }

    /**
     * Return WebRTC Access Token for client browser softphone.
     */
    public function getWebRtcToken(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? 1;
        $identity = 'agent_' . $userId;

        $tokenData = $this->twilio()->generateAccessToken($identity);

        return response()->json($tokenData, $tokenData['success'] ? 200 : 422);
    }

    /**
     * Standalone Popout Dialer Window (persists across CRM navigation).
     */
    public function dialerWindow(): View
    {
        return view('back-end.plugins.dialer-window');
    }

    /**
     * TwiML Voice Webhook endpoint hit by Twilio when browser client dials or an inbound call arrives.
     */
    public function handleTwilioVoiceWebhook(Request $request): Response
    {
        $twiml = $this->twilio()->generateTwiMLResponse($request);

        return response($twiml, 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Send a test call to verify Twilio credentials.
     */
    public function testCall(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'test_phone_number' => ['required', 'string', 'min:6'],
            'account_sid' => ['nullable', 'string'],
            'auth_token' => ['nullable', 'string'],
            'twilio_number' => ['nullable', 'string'],
        ]);

        $override = array_filter([
            'account_sid' => $validated['account_sid'] ?? null,
            'auth_token' => $validated['auth_token'] ?? null,
            'twilio_number' => $validated['twilio_number'] ?? null,
        ]);

        $result = $this->twilio()->makeTestCall($validated['test_phone_number'], $override);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Trigger an Inbound Test Call to test WebRTC ringing on browser softphone.
     */
    public function testInboundCall(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? 1;
        $identity = 'agent_' . $userId;

        $result = $this->twilio()->triggerInboundTestCall($identity);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Auto-generate and save a verified Twilio API Key & Secret with 1-click.
     */
    public function autoFixKeys(): JsonResponse
    {
        $result = $this->twilio()->autoGenerateAndSaveApiKey();

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Initiate a Click-to-Call from the Orders page.
     */
    public function initiateOrderCall(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer'],
            'agent_phone' => ['nullable', 'string'],
        ]);

        $order = Order::with('user')->find($validated['order_id']);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $customerCountryCode = optional($order->user)->countrycode ?? '';
        $customerMobile = optional($order->user)->mobile_no ?? optional($order->user)->mobile ?? '';

        $customerPhone = TwilioVoiceService::formatPhoneNumber($customerCountryCode, $customerMobile);
        if (empty($customerPhone)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid customer phone number found for this order.',
            ], 422);
        }

        $settings = $this->twilio()->getSettings();
        $callMode = $settings['call_mode'] ?? 'webrtc';

        // If in WebRTC mode, return token and customer phone to initiate call right inside browser
        if ($callMode === 'webrtc') {
            $userId = Auth::id() ?? 1;
            $identity = 'agent_' . $userId;
            $tokenData = $this->twilio()->generateAccessToken($identity);

            if (!$tokenData['success']) {
                return response()->json($tokenData, 422);
            }

            return response()->json([
                'success' => true,
                'mode' => 'webrtc',
                'customer_phone' => $customerPhone,
                'customer_name' => optional($order->user)->name ?? 'Customer',
                'order_id' => $order->id,
                'token' => $tokenData['token'],
                'message' => 'Connecting call directly through browser dialer...',
            ]);
        }

        // Bridge Call Mode (fallback)
        $agentPhone = $validated['agent_phone'] ?? null;
        if (empty($agentPhone)) {
            $currentUser = Auth::user();
            $agentPhone = $currentUser ? ($currentUser->mobile ?? $currentUser->mobile_no ?? '') : '';
        }
        if (empty($agentPhone)) {
            $agentPhone = $settings['default_agent_number'] ?? '';
        }

        if (empty($agentPhone)) {
            return response()->json([
                'success' => false,
                'require_agent_phone' => true,
                'customer_phone' => $customerPhone,
                'order_id' => $order->id,
                'message' => 'Agent phone number is missing. Please provide your phone number to receive the call.',
            ], 422);
        }

        $result = $this->twilio()->initiateBridgeCall($agentPhone, $customerPhone, $order->id);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Toggle active state of any plugin.
     */
    public function toggleStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plugin_key' => ['required', 'string'],
            'is_active'  => ['required', 'boolean'],
        ]);

        $plugin = PluginSetting::where('plugin_key', $validated['plugin_key'])->first();
        if (!$plugin) {
            return response()->json(['success' => false, 'message' => 'Plugin not found.'], 404);
        }

        $plugin->is_active = $validated['is_active'];
        $plugin->updated_by = Auth::id();
        $plugin->save();

        return response()->json([
            'success'   => true,
            'message'   => "Plugin status updated to " . ($plugin->is_active ? 'Active' : 'Inactive'),
            'is_active' => $plugin->is_active,
        ]);
    }

    /**
     * Log a call event from the browser softphone (JS posts here on call start/end).
     */
    public function logCall(Request $request): JsonResponse
    {
        $data = $request->validate([
            'call_sid'      => ['nullable', 'string', 'max:64'],
            'direction'     => ['nullable', 'string', 'in:outbound,inbound'],
            'status'        => ['nullable', 'string', 'max:30'],
            'from_number'   => ['nullable', 'string', 'max:50'],
            'to_number'     => ['nullable', 'string', 'max:50'],
            'customer_name' => ['nullable', 'string', 'max:200'],
            'duration'      => ['nullable', 'integer', 'min:0'],
            'started_at'    => ['nullable', 'string'],
            'ended_at'      => ['nullable', 'string'],
            'recording_url' => ['nullable', 'string'],
            'notes'         => ['nullable', 'string'],
        ]);

        $user = Auth::user();

        // Upsert by call_sid if provided, else always insert
        if (!empty($data['call_sid'])) {
            $log = TwilioCallLog::firstOrNew(['call_sid' => $data['call_sid']]);
        } else {
            $log = new TwilioCallLog();
        }

        $log->fill([
            'call_sid'       => $data['call_sid'] ?? $log->call_sid,
            'direction'      => $data['direction'] ?? $log->direction ?? 'outbound',
            'status'         => $data['status'] ?? $log->status ?? 'initiated',
            'from_number'    => $data['from_number'] ?? $log->from_number,
            'to_number'      => $data['to_number'] ?? $log->to_number,
            'customer_name'  => $data['customer_name'] ?? $log->customer_name,
            'duration'       => max((int) ($data['duration'] ?? 0), (int) ($log->duration ?? 0)),
            'agent_user_id'  => $log->agent_user_id ?: $user?->id,
            'agent_identity' => $log->agent_identity ?: ($user ? 'agent_' . $user->id : null),
            'started_at'     => !empty($data['started_at']) ? \Carbon\Carbon::parse($data['started_at']) : ($log->started_at ?: now()),
            'ended_at'       => !empty($data['ended_at'])   ? \Carbon\Carbon::parse($data['ended_at'])   : $log->ended_at,
            'recording_url'  => $data['recording_url'] ?? $log->recording_url,
            'notes'          => $data['notes'] ?? $log->notes,
        ]);
        $log->save();

        return response()->json(['success' => true, 'id' => $log->id]);
    }

    /**
     * Twilio Status & Recording Callback webhook — receives call updates directly from Twilio.
     */
    public function statusCallback(Request $request): Response
    {
        try {
            $callSid   = $request->input('CallSid') ?: $request->input('DialCallSid');
            $rawStatus = strtolower((string) ($request->input('CallStatus') ?: $request->input('DialCallStatus') ?: 'completed'));
            $duration  = (int) ($request->input('CallDuration') ?: $request->input('DialCallDuration') ?: 0);
            $from      = $request->input('From', '');
            $to        = $request->input('To', '');
            $dirInput  = strtolower((string) $request->input('Direction', ''));
            $direction = str_contains($dirInput, 'inbound') ? 'inbound' : 'outbound';

            // Map status
            $status = match($rawStatus) {
                'in-progress', 'in_progress' => 'in-progress',
                'completed'                  => 'completed',
                'busy'                       => ($direction === 'inbound') ? 'missed' : 'no-answer',
                'no-answer', 'no_answer'     => ($direction === 'inbound') ? 'missed' : 'no-answer',
                'canceled', 'cancelled'      => ($direction === 'inbound') ? 'missed' : 'cancelled',
                'failed'                     => 'failed',
                'ringing'                    => 'ringing',
                default                      => $rawStatus,
            };

            // If inbound call completed with 0 duration or dial was not answered, it is a missed call
            if ($direction === 'inbound') {
                if ($rawStatus === 'completed' && $duration > 0) {
                    $status = 'completed';
                } elseif ($duration === 0 || in_array($rawStatus, ['busy', 'no-answer', 'no_answer', 'canceled', 'cancelled', 'failed'])) {
                    $status = 'missed';
                }
            }

            if ($callSid) {
                $log = TwilioCallLog::firstOrNew(['call_sid' => $callSid]);
                $log->status    = $status;
                $log->duration  = max((int) ($log->duration ?? 0), $duration);
                if (empty($log->from_number) && !empty($from)) $log->from_number = $from;
                if (empty($log->to_number) && !empty($to))     $log->to_number   = $to;
                if (empty($log->direction))                     $log->direction   = $direction;

                // Auto-resolve customer name if not already set
                if (empty($log->customer_name)) {
                    $callerPhone = $log->from_number ?: $from;
                    $digits = preg_replace('/\D/', '', $callerPhone);
                    if (strlen($digits) >= 7) {
                        try {
                            $customer = \App\Models\User::whereRaw("REPLACE(REPLACE(mobile_no, ' ', ''), '-', '') LIKE ?", ['%' . substr($digits, -10)])->first();
                            if ($customer) {
                                $log->customer_name = $customer->name;
                            }
                        } catch (\Throwable $ex) {
                            Log::warning('Twilio status-callback customer lookup skipped: ' . $ex->getMessage());
                        }
                    }
                }

                if ($request->filled('RecordingUrl')) {
                    $recordingUrl = $request->input('RecordingUrl');
                    if (!str_ends_with($recordingUrl, '.mp3')) {
                        $recordingUrl .= '.mp3';
                    }
                    $log->recording_url = $recordingUrl;
                }
                if ($request->filled('RecordingSid')) {
                    $log->recording_sid = $request->input('RecordingSid');
                }

                if (in_array($status, ['completed', 'failed', 'no-answer', 'missed', 'cancelled'])) {
                    $log->ended_at = now();
                }
                $log->save();
            }

            return response('', 204);
        } catch (\Throwable $e) {
            Log::error('Twilio status-callback error: ' . $e->getMessage());
            return response('', 200);
        }
    }

    /**
     * Call History page — list all call logs (Next2Call Softphone & Twilio Voice Call).
     */
    public function callHistory(Request $request): View
    {
        $isSuperAdmin = Auth::check() && (int) Auth::user()->role_id === 1;
        $currentUserId = Auth::id();

        // 2 Primary Provider Tabs: 'next2call' or 'twilio' (defaults to next2call)
        $provider = $request->input('provider', 'next2call');

        // ==========================================
        // Next2Call Softphone Provider Tab
        // ==========================================
        if ($provider === 'next2call') {
            // Auto-sync if not recently synced or explicit sync param
            $cacheSyncKey = 'n2c_synced_' . ($currentUserId ?? 'guest');
            if ($request->boolean('sync') || !Cache::has($cacheSyncKey)) {
                self::syncNext2CallLogs();
                Cache::put($cacheSyncKey, true, now()->addMinutes(2));
            }

            $baseQuery = Next2CallLog::query();

            // Summary counts for tabs and statistics
            $totalCalls     = (clone $baseQuery)->count();
            $completedCalls = (clone $baseQuery)->where('status', 'ANSWER')->count();
            $missedCalls    = (clone $baseQuery)->whereIn('status', ['NOANSWER', 'CANCEL', 'CONGESTION'])->count();
            $inboundCalls   = (clone $baseQuery)->where('direction', 'inbound')->count();
            $outboundCalls  = (clone $baseQuery)->where('direction', 'outbound')->count();

            $query = (clone $baseQuery)->with('agent')->orderByDesc('started_at')->orderByDesc('id');

            // Quick Tab Filter
            $tab = $request->input('tab', 'all');
            if ($tab === 'missed') {
                $query->whereIn('status', ['NOANSWER', 'CANCEL', 'CONGESTION']);
            } elseif ($tab === 'inbound') {
                $query->where('direction', 'inbound');
            } elseif ($tab === 'outbound') {
                $query->where('direction', 'outbound');
            } elseif ($tab === 'completed' || $tab === 'answered') {
                $query->where('status', 'ANSWER');
            }

            // Granular filters
            if ($request->filled('status') && $tab === 'all') {
                $query->where('status', strtoupper($request->input('status')));
            }
            if ($request->filled('direction') && $tab === 'all') {
                $query->where('direction', strtolower($request->input('direction')));
            }
            if ($request->filled('date_from')) {
                $query->whereDate('started_at', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->whereDate('started_at', '<=', $request->input('date_to'));
            }

            // Search by contact name, number, DID, or ID
            $searchVal = trim((string) $request->input('search'));
            $uid = $request->input('uid');

            if (!empty($uid) || !empty($searchVal)) {
                $matchedUserPhones = [];

                if (!empty($uid)) {
                    $selectedUser = \App\Models\User::find($uid);
                    if ($selectedUser) {
                        if (!empty($selectedUser->mobile_no)) {
                            $matchedUserPhones[] = preg_replace('/\D+/', '', $selectedUser->mobile_no);
                        }
                        if (!empty($selectedUser->mobile_no2)) {
                            $matchedUserPhones[] = preg_replace('/\D+/', '', $selectedUser->mobile_no2);
                        }
                    }
                }

                if (!empty($searchVal)) {
                    $userIds = function_exists('find_user_ids_by_search_term') ? find_user_ids_by_search_term($searchVal) : [];
                    if (!empty($userIds)) {
                        $uPhones = \App\Models\User::whereIn('id', $userIds)->get(['mobile_no', 'mobile_no2']);
                        foreach ($uPhones as $up) {
                            if (!empty($up->mobile_no)) {
                                $matchedUserPhones[] = preg_replace('/\D+/', '', $up->mobile_no);
                            }
                            if (!empty($up->mobile_no2)) {
                                $matchedUserPhones[] = preg_replace('/\D+/', '', $up->mobile_no2);
                            }
                        }
                    }
                }

                $matchedUserPhones = array_unique(array_filter($matchedUserPhones));

                $query->where(function ($q) use ($searchVal, $matchedUserPhones) {
                    if (!empty($searchVal)) {
                        $hasAsterisk = strpos($searchVal, '*') !== false;
                        if ($hasAsterisk) {
                            $pattern = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $searchVal));
                            $cleanPattern = ltrim($pattern, '0');
                            if (!empty($cleanPattern)) {
                                $q->where('call_from', 'like', "%{$cleanPattern}%")
                                  ->orWhere('call_to', 'like', "%{$cleanPattern}%")
                                  ->orWhere('did', 'like', "%{$cleanPattern}%");
                            }
                        } else {
                            $cleanDigits = preg_replace('/\D+/', '', $searchVal);
                            if (strlen($cleanDigits) >= 4) {
                                $last10 = substr($cleanDigits, -10);
                                $q->where('call_from', 'like', "%{$cleanDigits}%")
                                  ->orWhere('call_to', 'like', "%{$cleanDigits}%")
                                  ->orWhere('did', 'like', "%{$cleanDigits}%")
                                  ->orWhere('call_from', 'like', "%{$last10}%")
                                  ->orWhere('call_to', 'like', "%{$last10}%");
                            }
                        }

                        $s = '%' . $searchVal . '%';
                        $q->orWhere('customer_name', 'like', $s)
                          ->orWhere('did', 'like', $s)
                          ->orWhere('next2call_id', 'like', $s);
                    }

                    if (!empty($matchedUserPhones)) {
                        foreach ($matchedUserPhones as $phoneDigits) {
                            if (strlen($phoneDigits) >= 4) {
                                $last10 = substr($phoneDigits, -10);
                                $q->orWhere('call_from', 'like', "%{$phoneDigits}%")
                                  ->orWhere('call_to', 'like', "%{$phoneDigits}%")
                                  ->orWhere('call_from', 'like', "%{$last10}%")
                                  ->orWhere('call_to', 'like', "%{$last10}%");
                            }
                        }
                    }
                });
            }

            $logs = $query->paginate(30)->withQueryString();

            return view('back-end.plugins.call-history', compact(
                'provider',
                'logs',
                'totalCalls',
                'completedCalls',
                'missedCalls',
                'inboundCalls',
                'outboundCalls',
                'tab',
                'isSuperAdmin'
            ));
        }

        // ==========================================
        // Twilio Voice Call Provider Tab
        // ==========================================
        $baseQuery = TwilioCallLog::query();
        if (!$isSuperAdmin) {
            $baseQuery->where(function ($q) use ($currentUserId) {
                $q->where('agent_user_id', $currentUserId)
                  ->orWhere(function ($sub) {
                      $sub->where('direction', 'inbound');
                  });
            });
        }

        // Summary counts for tabs and statistics
        $totalCalls     = (clone $baseQuery)->count();
        $completedCalls = (clone $baseQuery)->where('status', 'completed')->count();
        $missedCalls    = (clone $baseQuery)->whereIn('status', ['missed', 'no-answer'])->count();
        $inboundCalls   = (clone $baseQuery)->where('direction', 'inbound')->count();
        $outboundCalls  = (clone $baseQuery)->where('direction', 'outbound')->count();

        $query = (clone $baseQuery)->with('agent')->orderByDesc('created_at');

        // Quick Tab Filter
        $tab = $request->input('tab', 'all');
        if ($tab === 'missed') {
            $query->whereIn('status', ['missed', 'no-answer']);
        } elseif ($tab === 'inbound') {
            $query->where('direction', 'inbound');
        } elseif ($tab === 'outbound') {
            $query->where('direction', 'outbound');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        // Additional granular filters
        if ($request->filled('status') && $tab === 'all') {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('direction') && $tab === 'all') {
            $query->where('direction', $request->input('direction'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        // Comprehensive search: by direct term, masked phone pattern, Twilio SID, or User (lead/order)
        $searchVal = trim((string) $request->input('search'));
        $uid = $request->input('uid');

        if (!empty($uid) || !empty($searchVal)) {
            $matchedUserPhones = [];

            if (!empty($uid)) {
                $selectedUser = \App\Models\User::find($uid);
                if ($selectedUser) {
                    if (!empty($selectedUser->mobile_no)) {
                        $matchedUserPhones[] = preg_replace('/\D+/', '', $selectedUser->mobile_no);
                    }
                    if (!empty($selectedUser->mobile_no2)) {
                        $matchedUserPhones[] = preg_replace('/\D+/', '', $selectedUser->mobile_no2);
                    }
                }
            }

            if (!empty($searchVal)) {
                $userIds = function_exists('find_user_ids_by_search_term') ? find_user_ids_by_search_term($searchVal) : [];
                if (!empty($userIds)) {
                    $uPhones = \App\Models\User::whereIn('id', $userIds)->get(['mobile_no', 'mobile_no2']);
                    foreach ($uPhones as $up) {
                        if (!empty($up->mobile_no)) {
                            $matchedUserPhones[] = preg_replace('/\D+/', '', $up->mobile_no);
                        }
                        if (!empty($up->mobile_no2)) {
                            $matchedUserPhones[] = preg_replace('/\D+/', '', $up->mobile_no2);
                        }
                    }
                }
            }

            $matchedUserPhones = array_unique(array_filter($matchedUserPhones));

            $query->where(function ($q) use ($searchVal, $matchedUserPhones) {
                if (!empty($searchVal)) {
                    $hasAsterisk = strpos($searchVal, '*') !== false;
                    if ($hasAsterisk) {
                        $pattern = preg_replace('/\*+/', '%', preg_replace('/[^0-9*]/', '', $searchVal));
                        $cleanPattern = ltrim($pattern, '0');
                        if (!empty($cleanPattern)) {
                            $q->where('from_number', 'like', "%{$cleanPattern}%")
                              ->orWhere('to_number', 'like', "%{$cleanPattern}%");
                        }
                    } else {
                        $cleanDigits = preg_replace('/\D+/', '', $searchVal);
                        if (strlen($cleanDigits) >= 4) {
                            $last10 = substr($cleanDigits, -10);
                            $q->where('from_number', 'like', "%{$cleanDigits}%")
                              ->orWhere('to_number', 'like', "%{$cleanDigits}%")
                              ->orWhere('from_number', 'like', "%{$last10}%")
                              ->orWhere('to_number', 'like', "%{$last10}%");
                        }
                    }

                    $s = '%' . $searchVal . '%';
                    $q->orWhere('customer_name', 'like', $s)
                      ->orWhere('call_sid', 'like', $s);
                }

                if (!empty($matchedUserPhones)) {
                    foreach ($matchedUserPhones as $phoneDigits) {
                        if (strlen($phoneDigits) >= 4) {
                            $last10 = substr($phoneDigits, -10);
                            $q->orWhere('from_number', 'like', "%{$phoneDigits}%")
                              ->orWhere('to_number', 'like', "%{$phoneDigits}%")
                              ->orWhere('from_number', 'like', "%{$last10}%")
                              ->orWhere('to_number', 'like', "%{$last10}%");
                        }
                    }
                }
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('back-end.plugins.call-history', compact(
            'provider',
            'logs',
            'totalCalls',
            'completedCalls',
            'missedCalls',
            'inboundCalls',
            'outboundCalls',
            'tab',
            'isSuperAdmin'
        ));
    }
}
