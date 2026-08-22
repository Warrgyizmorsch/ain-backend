<?php

namespace App\Services;

use App\Models\PluginSetting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioVoiceService
{
    private Client $httpClient;
    private ?PluginSetting $plugin;

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 20,
            'http_errors' => false,
        ]);
        $this->plugin = PluginSetting::where('plugin_key', 'twilio_call')->first();
    }

    /**
     * Check if Twilio Voice Call plugin is enabled and configured.
     */
    public function isConfigured(): bool
    {
        if (!$this->plugin || !$this->plugin->is_active) {
            return false;
        }

        $sid = $this->plugin->getSetting('account_sid');
        $token = $this->plugin->getSetting('auth_token');
        $from = $this->plugin->getSetting('twilio_number');

        return !empty($sid) && !empty($token) && !empty($from);
    }

    public function isWebRtcConfigured(): bool
    {
        if (!$this->plugin || !$this->plugin->is_active) {
            return false;
        }

        $accountSid = $this->plugin->getSetting('account_sid');
        $apiKey = $this->plugin->getSetting('api_key_sid');
        $apiSecret = $this->plugin->getSetting('api_secret');
        $appSid = $this->plugin->getSetting('twiml_app_sid');

        return !empty($accountSid) && !empty($apiKey) && !empty($apiSecret) && !empty($appSid);
    }

    public function getSettings(): array
    {
        return $this->plugin ? ($this->plugin->settings ?? []) : [];
    }

    /**
     * Format international phone number (ensure leading + and digits only).
     */
    public static function formatPhoneNumber(?string $countryCode, ?string $number): string
    {
        $countryCode = trim((string) $countryCode);
        $number = trim((string) $number);

        $countryCode = preg_replace('/[^\d+]/', '', $countryCode);
        $number = preg_replace('/\D/', '', $number);

        if (empty($number)) {
            return '';
        }

        if (str_starts_with($number, '+')) {
            return $number;
        }

        if (!empty($countryCode)) {
            $cc = ltrim($countryCode, '+');
            if (str_starts_with($number, $cc)) {
                return '+' . $number;
            }
            return '+' . $cc . $number;
        }

        return '+' . ltrim($number, '+');
    }

    /**
     * Generate Twilio Voice JWT Access Token for WebRTC Client SDK.
     */
    public function generateAccessToken(string $identity, int $ttl = 3600): array
    {
        $this->plugin = PluginSetting::where('plugin_key', 'twilio_call')->first();
        $accountSid = $this->plugin?->getSetting('account_sid');
        $apiKey = $this->plugin?->getSetting('api_key_sid');
        $apiSecret = $this->plugin?->getSetting('api_secret');
        $appSid = $this->plugin?->getSetting('twiml_app_sid');

        if (empty($accountSid) || empty($apiKey) || empty($apiSecret) || empty($appSid)) {
            return [
                'success' => false,
                'message' => 'WebRTC configuration is incomplete. Please ensure Account SID, API Key SID, API Secret, and TwiML App SID are saved.',
            ];
        }

        $now = time();
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
            'cty' => 'twilio-fpa;v=1',
        ];

        $payload = [
            'jti' => $apiKey . '-' . $now . '-' . mt_rand(100, 999),
            'iss' => $apiKey,
            'sub' => $accountSid,
            'nbf' => $now - 300, // 5 minutes in past to prevent any server clock skew
            'exp' => $now + 86400, // 24 hours validity
            'grants' => [
                'identity' => $identity,
                'voice' => [
                    'outgoing' => [
                        'application_sid' => $appSid,
                    ],
                    'incoming' => [
                        'allow' => true,
                    ],
                ],
            ],
        ];

        $encodedHeader = self::base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signature = hash_hmac('sha256', "{$encodedHeader}.{$encodedPayload}", $apiSecret, true);
        $encodedSignature = self::base64UrlEncode($signature);

        $jwt = "{$encodedHeader}.{$encodedPayload}.{$encodedSignature}";

        return [
            'success' => true,
            'token' => $jwt,
            'identity' => $identity,
            'twilio_number' => $this->plugin?->getSetting('twilio_number'),
        ];
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Handle incoming TwiML webhook from Twilio for outbound or inbound voice calls.
     */
    public function generateTwiMLResponse(Request $request): string
    {
        $this->plugin = PluginSetting::where('plugin_key', 'twilio_call')->first();
        $rawTo = trim((string) ($request->input('To') ?? $request->input('phoneNumber') ?? ''));
        $rawFrom = trim((string) $request->input('From', ''));
        $callerId = $this->plugin?->getSetting('twilio_number') ?: '+15054963739';

        $cleanTo = preg_replace('/[^\d\+]/', '', $rawTo);
        $formattedCallerId = self::formatPhoneNumber('', $callerId);
        $formattedTo = self::formatPhoneNumber('', $cleanTo);

        Log::info('Twilio TwiML Voice Webhook Triggered', [
            'rawTo' => $rawTo,
            'cleanTo' => $cleanTo,
            'formattedTo' => $formattedTo,
            'callerId' => $formattedCallerId,
        ]);

        // Case 1: Outbound call initiated from browser to an external phone number
        if (!empty($cleanTo) && !str_starts_with($cleanTo, 'client:') && $formattedTo !== $formattedCallerId && strlen($cleanTo) >= 7) {
            return '<?xml version="1.0" encoding="UTF-8"?>'
                . '<Response>'
                . '<Dial callerId="' . htmlspecialchars($formattedCallerId) . '" answerOnBridge="true">'
                . '<Number>' . htmlspecialchars($formattedTo) . '</Number>'
                . '</Dial>'
                . '</Response>';
        }

        // Case 2: Inbound call from a customer dialing our Twilio Number -> Ring active web softphones
        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<Response>'
            . '<Say voice="alice">Connecting to customer support, please hold.</Say>'
            . '<Dial timeout="30">'
            . '<Client>agent_12641</Client>'
            . '<Client>agent_1</Client>'
            . '<Client>agent_admin</Client>'
            . '</Dial>'
            . '</Response>';
    }

    /**
     * Make a Test Call to verify Twilio Credentials.
     */
    public function makeTestCall(string $toNumber, ?array $overrideSettings = null): array
    {
        $sid = $overrideSettings['account_sid'] ?? $this->plugin?->getSetting('account_sid');
        $token = $overrideSettings['auth_token'] ?? $this->plugin?->getSetting('auth_token');
        $from = $overrideSettings['twilio_number'] ?? $this->plugin?->getSetting('twilio_number');

        if (empty($sid) || empty($token) || empty($from)) {
            return [
                'success' => false,
                'message' => 'Twilio Account SID, Auth Token, or From Number is missing.',
            ];
        }

        $formattedTo = self::formatPhoneNumber('', $toNumber);
        $formattedFrom = self::formatPhoneNumber('', $from);

        $twiml = '<Response><Say voice="Polly.Aditi" language="hi-IN">Hello! This is a test call from your Laravel Twilio Calling Plugin. Your voice integration is working successfully.</Say><Pause length="1"/><Say voice="alice" language="en-US">Thank you for testing.</Say></Response>';

        return $this->dispatchTwilioCall($sid, $token, $formattedFrom, $formattedTo, $twiml);
    }

    /**
     * Trigger an Inbound Test Call directly to the Web Agent Softphone client.
     */
    public function triggerInboundTestCall(string $identity): array
    {
        $this->plugin = PluginSetting::where('plugin_key', 'twilio_call')->first();
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Twilio plugin is not configured.'];
        }

        $sid = $this->plugin->getSetting('account_sid');
        $token = $this->plugin->getSetting('auth_token');
        $from = $this->plugin->getSetting('twilio_number') ?: '+15054963739';

        $twiml = '<Response><Say voice="Polly.Aditi" language="hi-IN">Namaste! This is an incoming test call received on your web softphone.</Say><Pause length="1"/><Say voice="alice">Your inbound WebRTC routing is fully functional.</Say></Response>';

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Calls.json";

        try {
            $client = new \GuzzleHttp\Client(['timeout' => 15, 'http_errors' => false]);
            $response = $client->post($url, [
                'auth' => [$sid, $token],
                'form_params' => [
                    'To' => "client:{$identity}",
                    'From' => $from,
                    'Twiml' => $twiml,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return [
                    'success' => true,
                    'message' => 'Inbound test call triggered! Watch your screen for the incoming call popup.',
                    'call_sid' => $body['sid'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Failed to trigger inbound call from Twilio.',
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Initiate Bridge Call (Clicks-to-Call):
     * 1. Twilio calls the Agent first.
     * 2. When Agent picks up, Twilio bridges (dials) the Customer.
     */
    public function initiateBridgeCall(string $agentNumber, string $customerNumber, ?int $orderId = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Twilio Voice Call plugin is not active or credentials are not configured.',
            ];
        }

        $sid = $this->plugin->getSetting('account_sid');
        $token = $this->plugin->getSetting('auth_token');
        $from = $this->plugin->getSetting('twilio_number');

        $formattedAgent = self::formatPhoneNumber('', $agentNumber);
        $formattedCustomer = self::formatPhoneNumber('', $customerNumber);
        $formattedFrom = self::formatPhoneNumber('', $from);

        if (empty($formattedAgent)) {
            return ['success' => false, 'message' => 'Agent phone number is required.'];
        }

        if (empty($formattedCustomer)) {
            return ['success' => false, 'message' => 'Customer phone number is invalid or empty.'];
        }

        $orderText = $orderId ? " regarding Order Number {$orderId}" : '';
        $twiml = '<Response>'
            . '<Say voice="alice">Connecting your call' . htmlspecialchars($orderText) . '. Please wait while we dial the customer.</Say>'
            . '<Dial callerId="' . htmlspecialchars($formattedFrom) . '" timeout="30">'
            . '<Number>' . htmlspecialchars($formattedCustomer) . '</Number>'
            . '</Dial>'
            . '</Response>';

        return $this->dispatchTwilioCall($sid, $token, $formattedFrom, $formattedAgent, $twiml, [
            'order_id' => $orderId,
            'customer_phone' => $formattedCustomer,
            'agent_phone' => $formattedAgent,
        ]);
    }

    /**
     * Send Twilio Calls.json REST API request.
     */
    private function dispatchTwilioCall(string $sid, string $token, string $from, string $to, string $twiml, array $customMeta = []): array
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Calls.json";

        try {
            $response = $this->httpClient->post($url, [
                'auth' => [$sid, $token],
                'form_params' => [
                    'To' => $to,
                    'From' => $from,
                    'Twiml' => $twiml,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true);

            if ($statusCode >= 200 && $statusCode < 300) {
                Log::info('Twilio call initiated successfully', [
                    'call_sid' => $body['sid'] ?? null,
                    'to' => $to,
                    'from' => $from,
                    'meta' => $customMeta,
                ]);

                return [
                    'success' => true,
                    'message' => 'Call initiated successfully! Ringing agent...',
                    'call_sid' => $body['sid'] ?? null,
                    'status' => $body['status'] ?? 'queued',
                    'data' => $body,
                ];
            }

            $errorMessage = $body['message'] ?? 'Twilio API returned an error (' . $statusCode . ')';
            $errorCode = $body['code'] ?? $statusCode;

            Log::error('Twilio Call API failed', [
                'statusCode' => $statusCode,
                'response' => $body,
            ]);

            return [
                'success' => false,
                'message' => "Twilio Error [{$errorCode}]: {$errorMessage}",
                'code' => $errorCode,
                'details' => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('Twilio Voice Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to Twilio: ' . $e->getMessage(),
            ];
        }
    }
}
