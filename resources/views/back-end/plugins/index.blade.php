@extends('layouts.app')

@section('content')
@php
    $twilioSettings = optional($twilioPlugin)->settings ?? [];
    $isTwilioActive = (bool) (optional($twilioPlugin)->is_active ?? false);
    $twilioSid = !empty($twilioSettings['account_sid']) ? $twilioSettings['account_sid'] : env('TWILIO_ACCOUNT_SID', env('TWILIO_SID', 'ACce3d9633593afbeda1054ac03f555ab3'));
    $twilioToken = !empty($twilioSettings['auth_token']) ? $twilioSettings['auth_token'] : env('TWILIO_AUTH_TOKEN', env('TWILIO_TOKEN', ''));
    $twilioNumber = !empty($twilioSettings['twilio_number']) ? $twilioSettings['twilio_number'] : env('TWILIO_NUMBER', env('TWILIO_PHONE_NUMBER', env('TWILIO_FROM', '+15054963739')));
    $apiKeySid = !empty($twilioSettings['api_key_sid']) ? $twilioSettings['api_key_sid'] : env('TWILIO_API_KEY_SID', env('TWILIO_API_KEY', 'SK68c36d375a7551364289a1b85a83e38b'));
    $apiSecret = !empty($twilioSettings['api_secret']) ? $twilioSettings['api_secret'] : env('TWILIO_API_SECRET', env('TWILIO_SECRET', 'rNXWstz1t72NSD4n60eT1uz2mZZLzfWe'));
    $twimlAppSid = !empty($twilioSettings['twiml_app_sid']) ? $twilioSettings['twiml_app_sid'] : env('TWILIO_TWIML_APP_SID', env('TWILIO_APP_SID', 'APde9388f580c06d9c737fbc995a3601a7'));
    $agentNumber = !empty($twilioSettings['default_agent_number']) ? $twilioSettings['default_agent_number'] : ($currentUserPhone ?? '');
    $callMode = !empty($twilioSettings['call_mode']) ? $twilioSettings['call_mode'] : 'webrtc';

    $n2cSettings = optional($next2callPlugin)->settings ?? [];
    $isN2cActive = (bool) (optional($next2callPlugin)->is_active ?? true);
    $n2cUserId = $n2cSettings['user_id'] ?? '10101';
    $n2cSipDomain = $n2cSettings['sip_domain'] ?? 'ringfy.next2call.com';
@endphp

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">
                <i class="fa fa-phone-volume text-primary me-2"></i>Call Plugins
            </h1>
            <div class="text-muted fw-bold fs-7">
                Manage your calling integrations — Twilio Voice Calling &amp; Next2Call Softphone.
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-5">
            <i class="fa fa-check-circle fs-3 text-success me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Call Plugins Grid (Twilio & Next2Call) -->
    <div class="row g-6">
        <!-- 1. Twilio Voice Call Plugin Card -->
        <div class="col-lg-6">
            <div class="card h-100 border shadow-sm plugin-card {{ $isTwilioActive ? 'border-primary' : 'border-secondary' }}" style="overflow: hidden; border-radius: 12px;">
                <!-- Card Header Flush with Top -->
                <div class="card-header border-bottom py-4 px-6 d-flex align-items-center justify-content-between {{ $isTwilioActive ? 'bg-light-primary' : 'bg-light' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle {{ $isTwilioActive ? 'bg-primary' : 'bg-secondary' }} d-flex align-items-center justify-content-center shadow-sm">
                            <i class="fa fa-phone text-white fs-3"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bolder text-dark mb-0 fs-5">Twilio Voice Call</h3>
                                <span id="twilioStatusBadge" class="badge {{ $isTwilioActive ? 'badge-light-success text-success' : 'badge-light-danger text-danger' }} fs-8 fw-bold">
                                    <i class="fa fa-circle me-1 fs-9 {{ $isTwilioActive ? 'text-success' : 'text-danger' }}"></i>{{ $isTwilioActive ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="text-muted fs-8 mt-1">Direct WebRTC browser calling &amp; click-to-dial</div>
                        </div>
                    </div>
                    <div class="card-toolbar d-flex align-items-center gap-2">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input h-25px w-45px cursor-pointer" type="checkbox" id="twilioPluginToggle" {{ $isTwilioActive ? 'checked' : '' }} onchange="togglePluginStatus('twilio_call', this.checked)" title="Enable / Disable Twilio">
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-6 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-muted fs-7 mb-4">
                            Direct WebRTC browser calling &amp; click-to-call directly from Orders page. Includes Live Softphone dialer with Hold, Mute &amp; Inbound ringing.
                        </p>

                        <!-- Live Configuration Details -->
                        <div class="bg-light rounded p-4 mb-4 border fs-7">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted fw-semibold"><i class="fa fa-phone me-2 text-primary"></i>Twilio Number:</span>
                                <strong class="text-dark font-monospace fs-7">{{ $twilioNumber ?: 'Not configured' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted fw-semibold"><i class="fa fa-sliders-h me-2 text-primary"></i>Call Mode:</span>
                                <span class="badge {{ $callMode === 'webrtc' ? 'badge-light-success text-success' : 'badge-light-primary text-primary' }} text-uppercase fw-bolder">{{ $callMode }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted fw-semibold"><i class="fa fa-layer-group me-2 text-primary"></i>TwiML App SID:</span>
                                <strong class="text-dark font-monospace fs-8">{{ $twimlAppSid ? substr($twimlAppSid, 0, 16) . '...' : 'Not configured' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Toolbar -->
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-sm btn-light-primary flex-fill" data-bs-toggle="modal" data-bs-target="#twilioSettingsModal">
                            <i class="fa fa-cog me-1"></i> Settings
                        </button>
                        <button type="button" class="btn btn-sm btn-light-info flex-fill" onclick="window.twilioSoftphone && window.twilioSoftphone.toggleWidget()">
                            <i class="fa fa-phone me-1"></i> Open Dialer
                        </button>
                        <button type="button" class="btn btn-sm btn-light-success flex-fill" data-bs-toggle="modal" data-bs-target="#twilioTestCallModal">
                            <i class="fa fa-phone-volume me-1"></i> Test Call
                        </button>
                        <a href="{{ route('plugins.twilio.call.history') }}" class="btn btn-sm btn-light-warning flex-fill">
                            <i class="fa fa-history me-1"></i> Call History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Next2Call Softphone Plugin Card -->
        <div class="col-lg-6">
            <div class="card h-100 border shadow-sm plugin-card {{ $isN2cActive ? 'border-success' : 'border-secondary' }}" style="overflow: hidden; border-radius: 12px;">
                <!-- Card Header Flush with Top -->
                <div class="card-header border-bottom py-4 px-6 d-flex align-items-center justify-content-between {{ $isN2cActive ? 'bg-light-success' : 'bg-light' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle {{ $isN2cActive ? 'bg-success' : 'bg-secondary' }} d-flex align-items-center justify-content-center shadow-sm">
                            <i class="fa fa-headphones text-white fs-3"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bolder text-dark mb-0 fs-5">Next2Call Softphone</h3>
                                <span id="next2callStatusBadge" class="badge {{ $isN2cActive ? 'badge-light-success text-success' : 'badge-light-danger text-danger' }} fs-8 fw-bold">
                                    <i class="fa fa-circle me-1 fs-9 {{ $isN2cActive ? 'text-success' : 'text-danger' }}"></i>{{ $isN2cActive ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="text-muted fs-8 mt-1">In-browser WebRTC softphone &amp; click-to-dial powered by Ringfy</div>
                        </div>
                    </div>
                    <div class="card-toolbar d-flex align-items-center gap-2">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input h-25px w-45px cursor-pointer" type="checkbox" id="next2callPluginToggle" {{ $isN2cActive ? 'checked' : '' }} onchange="togglePluginStatus('next2call', this.checked)" title="Enable / Disable Next2Call">
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-6 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-muted fs-7 mb-4">
                            In-browser WebRTC softphone calling &amp; click-to-dial powered by Next2Call Ringfy PBX. Full keypad dialer popup, quick call &amp; extension support.
                        </p>

                        <!-- Live Configuration Details -->
                        <div class="bg-light rounded p-4 mb-4 border fs-7">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted fw-semibold"><i class="fa fa-user-circle me-2 text-success"></i>SIP Extension:</span>
                                <strong class="text-dark font-monospace fs-7">{{ $n2cUserId ?: '10101' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted fw-semibold"><i class="fa fa-server me-2 text-success"></i>SIP Server:</span>
                                <strong class="text-dark font-monospace fs-7">{{ $n2cSipDomain }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted fw-semibold"><i class="fa fa-headset me-2 text-success"></i>Dialer Mode:</span>
                                <span class="badge badge-light-success text-success text-uppercase fw-bolder">Ringfy WebRTC</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Toolbar -->
                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                        <a href="{{ route('plugins.next2call.page') }}" class="btn btn-sm btn-light-success flex-fill">
                            <i class="fa fa-cog me-1"></i> Settings
                        </a>
                        <button type="button" class="btn btn-sm btn-light-primary flex-fill" onclick="window.openRingfyDialer && window.openRingfyDialer()">
                            <i class="fa fa-phone me-1"></i> Open Softphone
                        </button>
                        <button type="button" class="btn btn-sm btn-light-info flex-fill" data-bs-toggle="modal" data-bs-target="#next2callTestCallModal">
                            <i class="fa fa-phone-volume me-1"></i> Test Call
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Twilio Settings -->
<div class="modal fade" id="twilioSettingsModal" tabindex="-1" aria-labelledby="twilioSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="twilioSettingsModalLabel">
                    <i class="fa fa-phone text-primary me-2"></i> Twilio Voice & WebRTC Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="twilioSettingsForm" method="POST" action="{{ route('plugins.twilio.save') }}">
                @csrf
                <div class="modal-body p-6">
                    <div class="alert alert-light-primary d-flex align-items-center mb-5">
                        <i class="fa fa-info-circle fs-3 text-primary me-3"></i>
                        <div class="fs-7">
                            Configure your Twilio Voice credentials from your <a href="https://console.twilio.com/" target="_blank" class="fw-bold text-primary">Twilio Console</a> for browser WebRTC & Bridge calling.
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Account SID</label>
                            <input type="text" name="account_sid" class="form-control font-monospace" placeholder="e.g. ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $twilioSid }}" required>
                            <div class="text-muted fs-8 mt-1">Twilio Console &gt; Account Info</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Auth Token</label>
                            <input type="password" name="auth_token" class="form-control font-monospace" placeholder="e.g. xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $twilioToken }}" required>
                            <div class="text-muted fs-8 mt-1">Twilio Console &gt; Auth Token</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Twilio Caller Phone Number (From)</label>
                            <input type="text" name="twilio_number" class="form-control font-monospace" placeholder="e.g. +12055550199" value="{{ $twilioNumber }}" required>
                            <div class="text-muted fs-8 mt-1">Your Twilio purchased/trial virtual number.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Default Agent Phone Number (For Bridge Mode)</label>
                            <input type="text" name="default_agent_number" class="form-control font-monospace" placeholder="e.g. +919876543210" value="{{ $agentNumber }}">
                            <div class="text-muted fs-8 mt-1">Twilio will call this phone first in Mobile Bridge mode.</div>
                        </div>

                        <!-- WebRTC Credentials Toggle Header -->
                        <div class="col-12 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center bg-light rounded p-4 border">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle bg-light-primary p-2">
                                        <i class="fa fa-globe text-primary fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bolder text-dark mb-0">WebRTC Browser Softphone Mode (API Key & TwiML App)</h6>
                                        <div class="text-muted fs-8">Direct browser calling with mic, dialpad, hold, mute, and live call disconnect.</div>
                                    </div>
                                </div>
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-25px w-45px cursor-pointer" type="checkbox" id="webrtc_enable_toggle" {{ $callMode === 'webrtc' ? 'checked' : '' }} onchange="toggleWebRtcFields(this.checked)">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Call Mode input synced with toggle -->
                        <input type="hidden" name="call_mode" id="call_mode_input" value="{{ $callMode }}">

                        <!-- WebRTC Credentials Container (Appears only when ON) -->
                        <div class="col-12 {{ $callMode === 'webrtc' ? '' : 'd-none' }}" id="webrtc_fields_container">
                            <div class="card border border-primary border-dashed bg-light-primary mb-0">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold text-dark fs-7">API Key Credentials</span>
                                        <button type="button" class="btn btn-xs btn-primary py-1 px-3 fs-8" id="autoFixKeysBtn" onclick="autoFixTwilioKeys()">
                                            <i class="fa fa-magic me-1"></i> Auto-Generate & Save Key from Twilio
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">API Key SID (SK...)</label>
                                            <input type="text" name="api_key_sid" id="setting_api_key_sid" class="form-control font-monospace" placeholder="e.g. SKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $apiKeySid }}">
                                            <div class="text-muted fs-8 mt-1">Found in Twilio Console &gt; API Keys &amp; Tokens</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">API Secret</label>
                                            <input type="password" name="api_secret" id="setting_api_secret" class="form-control font-monospace" placeholder="e.g. your_api_secret_key" value="{{ $apiSecret }}">
                                            <div class="text-muted fs-8 mt-1">Secret revealed when API Key was created.</div>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">TwiML App SID (AP...)</label>
                                            <input type="text" name="twiml_app_sid" id="setting_twiml_app_sid" class="form-control font-monospace" placeholder="e.g. APxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $twimlAppSid }}">
                                            <div class="text-muted fs-8 mt-1">Found in Twilio Console &gt; Voice &gt; TwiML Apps (Application SID)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-3 border-top">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px" type="checkbox" name="is_active" value="1" id="is_active_checkbox" {{ $isTwilioActive ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold ms-3" for="is_active_checkbox">
                                    Enable Twilio Voice Calling in Orders Page
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveTwilioBtn" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Twilio Test Call -->
<div class="modal fade" id="twilioTestCallModal" tabindex="-1" aria-labelledby="twilioTestCallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="twilioTestCallModalLabel">
                    <i class="fa fa-phone-volume text-success me-2"></i> Test Twilio Voice Calling
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="twilioTestCallForm">
                @csrf
                <div class="modal-body p-6">
                    <p class="text-muted fs-7 mb-4">
                        Twilio will make an outbound test call to the number below and speak a confirmation message to verify that your credentials and number are active.
                    </p>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">Test Phone Number (With Country Code)</label>
                        <input type="text" id="test_phone_number" name="test_phone_number" class="form-control font-monospace" placeholder="e.g. +919876543210" value="{{ $agentNumber }}" required>
                        <div class="text-muted fs-8 mt-1">
                            <span class="badge badge-light-warning">Free Trial Note</span> If your Twilio account is a Free Trial, this number MUST be in your <strong>Twilio Verified Caller IDs</strong>.
                        </div>
                    </div>

                    <div id="testCallStatusContainer" class="d-none mt-3"></div>
                </div>
                <div class="modal-footer d-flex flex-wrap justify-content-between gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-warning" id="testInboundBtn" onclick="testInboundRinging()">
                            <i class="fa fa-bell me-1"></i> Ring Inbound (Test Callback)
                        </button>
                        <button type="button" class="btn btn-info" onclick="testViaSoftphone()">
                            <i class="fa fa-laptop me-1"></i> Call from Softphone
                        </button>
                        <button type="submit" id="startTestCallBtn" class="btn btn-success">
                            <i class="fa fa-phone me-1"></i> <span id="testCallBtnText">Outbound API Call</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Next2Call Test Call (Incoming & Outgoing Testing) -->
<div class="modal fade" id="next2callTestCallModal" tabindex="-1" aria-labelledby="next2callTestCallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="next2callTestCallModalLabel">
                    <i class="fa fa-headphones text-success me-2"></i> Test Next2Call Softphone Calling
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="next2callTestCallForm">
                @csrf
                <div class="modal-body p-6">
                    <p class="text-muted fs-7 mb-4">
                        Test both <strong>Incoming</strong> (simulated inbound ringing popup with caller ID) and <strong>Outgoing</strong> (in-browser WebRTC softphone click-to-dial) using Next2Call Ringfy PBX.
                    </p>

                    <div class="mb-4">
                        <label class="form-label fw-bold required">Test Phone / Caller Number</label>
                        <input type="text" id="n2c_test_phone_number" name="test_phone_number" class="form-control font-monospace" placeholder="e.g. 08800826129" value="{{ $currentUserPhone ?: '08800826129' }}" required>
                        <div class="text-muted fs-8 mt-1">
                            Used as simulated caller number for <strong>Incoming Call</strong>, and destination number for <strong>Outgoing Call</strong>.
                        </div>
                    </div>

                    <!-- Quick Preset Badges -->
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-8 text-muted mb-2">QUICK TEST PRESETS:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-xs btn-light-success py-1 px-2 fs-8" onclick="setN2cTestNumber('08800826129')">
                                <i class="fa fa-phone me-1"></i> Domestic (08800826129)
                            </button>
                            <button type="button" class="btn btn-xs btn-light-primary py-1 px-2 fs-8" onclick="setN2cTestNumber('+918800826129')">
                                <i class="fa fa-mobile-alt me-1"></i> Mobile (+918800826129)
                            </button>
                            <button type="button" class="btn btn-xs btn-light-warning py-1 px-2 fs-8" onclick="setN2cTestNumber('447403511446')">
                                <i class="fa fa-globe me-1"></i> USA/UK (447403511446)
                            </button>
                            <button type="button" class="btn btn-xs btn-light-dark py-1 px-2 fs-8" onclick="setN2cTestNumber('{{ $n2cUserId ?: '10101' }}')">
                                <i class="fa fa-user-circle me-1"></i> Ext ({{ $n2cUserId ?: '10101' }})
                            </button>
                        </div>
                    </div>

                    <!-- Active Configuration Info -->
                    <div class="bg-light rounded p-3 fs-8 text-muted mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>SIP Extension:</span>
                            <strong class="text-dark">{{ $n2cUserId ?: '10101' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>SIP Domain:</span>
                            <strong class="text-dark">{{ $n2cSipDomain }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge {{ $isN2cActive ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                {{ $isN2cActive ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div id="n2cTestStatusContainer" class="d-none mt-3"></div>
                </div>
                <div class="modal-footer d-flex flex-wrap justify-content-between gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-warning" id="n2cTestInboundBtn" onclick="testNext2CallInbound()">
                            <i class="fa fa-bell me-1"></i> Ring Inbound (Incoming Test)
                        </button>
                        <button type="button" class="btn btn-info" id="n2cTestOutboundBtn" onclick="testNext2CallOutbound()">
                            <i class="fa fa-laptop me-1"></i> Outbound Call (Softphone)
                        </button>
                        <button type="submit" id="n2cStartTestApiBtn" class="btn btn-success">
                            <i class="fa fa-link me-1"></i> <span id="n2cTestBtnText">Test PBX URL</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
function toggleWebRtcFields(isEnabled) {
    if (isEnabled) {
        $('#webrtc_fields_container').removeClass('d-none');
        $('#call_mode_input').val('webrtc');
    } else {
        $('#webrtc_fields_container').addClass('d-none');
        $('#call_mode_input').val('bridge');
    }
}

async function testViaSoftphone() {
    let testNum = $('#test_phone_number').val().trim();
    if (!testNum) {
        Swal.fire('Error', 'Please enter a test phone number with country code.', 'warning');
        return;
    }
    $('#twilioTestCallModal').modal('hide');

    // Wait if softphone is still initializing
    let attempts = 0;
    while ((!window.twilioSoftphone || !window.twilioSoftphone.isReady) && attempts < 30) {
        await new Promise(r => setTimeout(r, 150));
        attempts++;
    }

    if (window.twilioSoftphone) {
        window.twilioSoftphone.makeCall(testNum, 'Test Outbound Call');
    } else {
        Swal.fire('Connecting', 'Connecting softphone service, please click again in a moment.', 'info');
    }
}

function autoFixTwilioKeys() {
    const btn = $('#autoFixKeysBtn');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Generating...');

    $.ajax({
        url: "{{ route('plugins.twilio.autofix') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-check text-success me-1"></i> Key Synced!');
            if (res.api_key_sid) $('#setting_api_key_sid').val(res.api_key_sid);
            if (res.api_secret) $('#setting_api_secret').val(res.api_secret);
            Swal.fire('Twilio API Key Synced!', 'A fresh verified API Key & Secret were created and saved automatically!', 'success');
            if (window.twilioSoftphone) {
                window.twilioSoftphone.init();
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fa fa-magic me-1"></i> Auto-Generate & Save');
            Swal.fire('Auto-Sync Failed', xhr.responseJSON?.message || 'Could not auto-generate key. Please verify Account SID & Auth Token.', 'error');
        }
    });
}

function testInboundRinging() {
    const btn = $('#testInboundBtn');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Triggering Call...');
    
    $.ajax({
        url: "{{ route('plugins.twilio.test.inbound') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-bell me-1"></i> Ring Inbound (Test Callback)');
            $('#twilioTestCallModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Inbound Call Dispatched!',
                text: 'Twilio is placing an incoming call to your softphone right now! Watch your screen for the ringing popup.',
                timer: 3000,
                showConfirmButton: true
            });
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fa fa-bell me-1"></i> Ring Inbound (Test Callback)');
            Swal.fire('Inbound Test Failed', xhr.responseJSON?.message || 'Could not trigger inbound test call.', 'error');
        }
    });
}

function togglePluginStatus(pluginKey, isActive) {
    $.ajax({
        url: "{{ route('plugins.toggle') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            plugin_key: pluginKey,
            is_active: isActive ? 1 : 0
        },
        success: function(res) {
            const badgeId = pluginKey === 'twilio_call' ? '#twilioStatusBadge' : '#' + pluginKey + 'StatusBadge';
            const badge = $(badgeId);
            if (badge.length) {
                if (isActive) {
                    badge.removeClass('badge-light-danger text-danger').addClass('badge-light-success text-success').html('<i class="fa fa-circle me-1 fs-9 text-success"></i>Active');
                } else {
                    badge.removeClass('badge-light-success text-success').addClass('badge-light-danger text-danger').html('<i class="fa fa-circle me-1 fs-9 text-danger"></i>Inactive');
                }
            }
            Swal.fire({
                icon: 'success',
                title: res.message,
                timer: 1200,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            const toggleId = pluginKey === 'twilio_call' ? '#twilioPluginToggle' : '#' + pluginKey + 'PluginToggle';
            $(toggleId).prop('checked', !isActive);
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update status', 'error');
        }
    });
}

// Handle Twilio Settings Form Submit
$('#twilioSettingsForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#saveTwilioBtn');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Configuration');
            $('#twilioSettingsModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Configuration');
            let errorMsg = 'Failed to save settings.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Error', errorMsg, 'error');
        }
    });
});

// Handle Test Call
$('#twilioTestCallForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#startTestCallBtn');
    const statusBox = $('#testCallStatusContainer');
    const testNumber = $('#test_phone_number').val().trim();

    if (!testNumber) {
        Swal.fire('Error', 'Please enter a test phone number with country code.', 'warning');
        return;
    }

    btn.prop('disabled', true);
    $('#testCallBtnText').text('Connecting Twilio...');
    statusBox.removeClass('d-none').html(`
        <div class="alert alert-light-info d-flex align-items-center">
            <i class="fa fa-spinner fa-spin fs-4 text-info me-3"></i>
            <div>Dialing <strong>${testNumber}</strong> via Twilio Voice API... Please listen to your phone.</div>
        </div>
    `);

    $.ajax({
        url: "{{ route('plugins.twilio.test') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            test_phone_number: testNumber
        },
        success: function(res) {
            btn.prop('disabled', false);
            $('#testCallBtnText').text('Trigger Test Call');
            statusBox.html(`
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fa fa-check-circle fs-3 text-success me-3"></i>
                    <div>
                        <strong>Call Initiated Successfully!</strong><br>
                        Status: <span class="badge badge-success">${res.status || 'queued'}</span> | Call SID: <code class="text-dark">${res.call_sid || 'N/A'}</code>
                    </div>
                </div>
            `);
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            $('#testCallBtnText').text('Trigger Test Call');
            let errMsg = xhr.responseJSON?.message || 'Call failed to initiate.';
            statusBox.html(`
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle fs-3 text-danger me-3"></i>
                    <div><strong>Call Failed:</strong> ${errMsg}</div>
                </div>
            `);
        }
    });
});

// Next2Call Test Helpers
function setN2cTestNumber(val) {
    $('#n2c_test_phone_number').val(val);
}

// 1. Incoming Call Test (Inbound Ringing simulation)
function testNext2CallInbound() {
    const testNum = $('#n2c_test_phone_number').val().trim() || '08800826129';
    $('#next2callTestCallModal').modal('hide');

    if (typeof window.simulateNext2CallInbound === 'function') {
        window.simulateNext2CallInbound(testNum);
    } else {
        window.postMessage({
            type: 'INCOMING_CALL',
            caller: testNum,
            event: 'incoming'
        }, window.location.origin);
    }

    Swal.fire({
        icon: 'success',
        title: 'Incoming Call Dispatched!',
        html: `Next2Call Softphone is ringing on your screen from <strong>${testNum}</strong>.<br><small class="text-muted">Check the softphone widget popup at the bottom-right of your screen.</small>`,
        timer: 4500,
        showConfirmButton: true
    });
}

// 2. Outgoing Call Test (Softphone Click-to-Dial)
function testNext2CallOutbound() {
    const testNum = $('#n2c_test_phone_number').val().trim();
    if (!testNum) {
        Swal.fire('Error', 'Please enter a test phone number.', 'warning');
        return;
    }
    $('#next2callTestCallModal').modal('hide');

    if (typeof window.dialNext2CallNumber === 'function') {
        window.dialNext2CallNumber(testNum);
    } else if (typeof window.openRingfyDialer === 'function') {
        window.openRingfyDialer(testNum);
    }

    Swal.fire({
        icon: 'info',
        title: 'Dialing via Softphone...',
        text: `Opening Next2Call softphone dialer for ${testNum}.`,
        timer: 2500,
        showConfirmButton: false
    });
}

// 3. API / Click-to-Dial PBX Test Submit
$('#next2callTestCallForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#n2cStartTestApiBtn');
    const statusBox = $('#n2cTestStatusContainer');
    const testNumber = $('#n2c_test_phone_number').val().trim();

    if (!testNumber) {
        Swal.fire('Error', 'Please enter a test phone number.', 'warning');
        return;
    }

    btn.prop('disabled', true);
    $('#n2cTestBtnText').text('Generating PBX URL...');
    statusBox.removeClass('d-none').html(`
        <div class="alert alert-light-info d-flex align-items-center">
            <i class="fa fa-spinner fa-spin fs-4 text-info me-3"></i>
            <div>Validating Next2Call PBX click-to-dial URL for <strong>${testNumber}</strong>...</div>
        </div>
    `);

    $.ajax({
        url: "{{ route('plugins.next2call.test') }}",
        type: "POST",
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        contentType: 'application/json',
        data: JSON.stringify({
            test_phone_number: testNumber
        }),
        success: function(res) {
            btn.prop('disabled', false);
            $('#n2cTestBtnText').text('Test PBX URL');
            if (res.success) {
                statusBox.html(`
                    <div class="alert alert-success d-flex flex-column gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-check-circle fs-3 text-success me-3"></i>
                            <div>
                                <strong>PBX Click-to-Dial Generated Successfully!</strong><br>
                                <span class="badge badge-success">Target: ${res.target_number || testNumber}</span> | Extension: <code>${res.user_id || '10101'}</code>
                            </div>
                        </div>
                        <div class="text-break fs-9 bg-white p-2 rounded border font-monospace text-muted mt-1">
                            ${res.dial_url}
                        </div>
                    </div>
                `);
            } else {
                statusBox.html(`
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fs-3 text-danger me-3"></i>
                        <div><strong>Error:</strong> ${res.message || 'Failed to generate test URL.'}</div>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            $('#n2cTestBtnText').text('Test PBX URL');
            let errMsg = xhr.responseJSON?.message || 'Failed to generate Next2Call test URL.';
            statusBox.html(`
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle fs-3 text-danger me-3"></i>
                    <div><strong>Test Failed:</strong> ${errMsg}</div>
                </div>
            `);
        }
    });
});

</script>
@endpush

<style>
.plugin-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
}
.plugin-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
}
</style>
@endsection
