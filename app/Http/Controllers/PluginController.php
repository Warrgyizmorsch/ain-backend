<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PluginSetting;
use App\Models\TwilioCallLog;
use App\Services\TwilioVoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PluginController extends Controller
{
    protected TwilioVoiceService $twilioService;

    public function __construct(TwilioVoiceService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Display the plugins directory / settings page.
     */
    public function index(): View
    {
        $twilioPlugin = PluginSetting::firstOrCreate(
            ['plugin_key' => 'twilio_call'],
            [
                'name' => 'Twilio Voice Call',
                'category' => 'communication',
                'description' => 'Bridge voice calls between agents and customers directly from the Orders page using Twilio Voice API & WebRTC Dialer.',
                'is_active' => false,
                'settings' => [
                    'account_sid' => '',
                    'auth_token' => '',
                    'twilio_number' => '',
                    'api_key_sid' => '',
                    'api_secret' => '',
                    'twiml_app_sid' => '',
                    'default_agent_number' => '',
                    'call_mode' => 'webrtc', // 'webrtc' or 'bridge'
                    'record_calls' => false,
                ],
            ]
        );

        $currentUser = Auth::user();
        $currentUserPhone = $currentUser ? ($currentUser->mobile ?? $currentUser->mobile_no ?? '') : '';
        $emailAccountsCount = \App\Models\EmailConfiguration::count();
        $activeEmailAccounts = \App\Models\EmailConfiguration::where('is_active', true)->count();

        return view('back-end.plugins.index', [
            'twilioPlugin' => $twilioPlugin,
            'currentUserPhone' => $currentUserPhone,
            'emailAccountsCount' => $emailAccountsCount,
            'activeEmailAccounts' => $activeEmailAccounts,
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
     * Return WebRTC Access Token for client browser softphone.
     */
    public function getWebRtcToken(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? 1;
        $identity = 'agent_' . $userId;

        $tokenData = $this->twilioService->generateAccessToken($identity);

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
        $twiml = $this->twilioService->generateTwiMLResponse($request);

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

        $result = $this->twilioService->makeTestCall($validated['test_phone_number'], $override);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Trigger an Inbound Test Call to test WebRTC ringing on browser softphone.
     */
    public function testInboundCall(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? 1;
        $identity = 'agent_' . $userId;

        $result = $this->twilioService->triggerInboundTestCall($identity);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Auto-generate and save a verified Twilio API Key & Secret with 1-click.
     */
    public function autoFixKeys(): JsonResponse
    {
        $result = $this->twilioService->autoGenerateAndSaveApiKey();

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

        $settings = $this->twilioService->getSettings();
        $callMode = $settings['call_mode'] ?? 'webrtc';

        // If in WebRTC mode, return token and customer phone to initiate call right inside browser
        if ($callMode === 'webrtc') {
            $userId = Auth::id() ?? 1;
            $identity = 'agent_' . $userId;
            $tokenData = $this->twilioService->generateAccessToken($identity);

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

        $result = $this->twilioService->initiateBridgeCall($agentPhone, $customerPhone, $order->id);

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

        if ($callSid) {
            $log = TwilioCallLog::firstOrNew(['call_sid' => $callSid]);
            $log->status    = $status;
            $log->duration  = max((int) ($log->duration ?? 0), $duration);
            if (empty($log->from_number) && !empty($from)) $log->from_number = $from;
            if (empty($log->to_number) && !empty($to))     $log->to_number   = $to;
            if (empty($log->direction))                     $log->direction   = $direction;

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
    }

    /**
     * Call History page — list all call logs.
     */
    public function callHistory(Request $request): View
    {
        $query = TwilioCallLog::with('agent')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->input('direction'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->filled('search')) {
            $s = '%' . $request->input('search') . '%';
            $query->where(function($q) use ($s) {
                $q->where('customer_name', 'like', $s)
                  ->orWhere('from_number', 'like', $s)
                  ->orWhere('to_number', 'like', $s)
                  ->orWhere('call_sid', 'like', $s);
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('back-end.plugins.call-history', compact('logs'));
    }
}
