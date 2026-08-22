<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PluginSetting;
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

        return view('back-end.plugins.index', [
            'twilioPlugin' => $twilioPlugin,
            'currentUserPhone' => $currentUserPhone,
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
            'is_active' => ['required', 'boolean'],
        ]);

        $plugin = PluginSetting::where('plugin_key', $validated['plugin_key'])->first();
        if (!$plugin) {
            return response()->json(['success' => false, 'message' => 'Plugin not found.'], 404);
        }

        $plugin->is_active = $validated['is_active'];
        $plugin->updated_by = Auth::id();
        $plugin->save();

        return response()->json([
            'success' => true,
            'message' => "Plugin status updated to " . ($plugin->is_active ? 'Active' : 'Inactive'),
            'is_active' => $plugin->is_active,
        ]);
    }
}
