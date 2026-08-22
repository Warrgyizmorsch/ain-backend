@extends('layouts.app')

@section('content')
@php
    $twilioSettings = $twilioPlugin->settings ?? [];
    $isTwilioActive = (bool) ($twilioPlugin->is_active ?? false);
    $twilioSid = $twilioSettings['account_sid'] ?? '';
    $twilioToken = $twilioSettings['auth_token'] ?? '';
    $twilioNumber = $twilioSettings['twilio_number'] ?? '';
    $apiKeySid = $twilioSettings['api_key_sid'] ?? '';
    $apiSecret = $twilioSettings['api_secret'] ?? '';
    $twimlAppSid = $twilioSettings['twiml_app_sid'] ?? '';
    $agentNumber = $twilioSettings['default_agent_number'] ?? $currentUserPhone;
    $callMode = $twilioSettings['call_mode'] ?? 'webrtc';
@endphp

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">
                <i class="fa fa-plug text-primary me-2"></i>Plugins & Integrations
            </h1>
            <div class="text-muted fw-bold fs-7">
                Manage third-party integrations, Twilio WebRTC browser calling & voice plugins.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#twilioSettingsModal">
                <i class="fa fa-phone me-1"></i> Configure Twilio Call
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-5">
            <i class="fa fa-check-circle fs-3 text-success me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Plugins Grid -->
    <div class="row g-6">
        <!-- Twilio Voice Call Plugin Card -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border shadow-sm plugin-card {{ $isTwilioActive ? 'border-success' : '' }}">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-primary p-3">
                                <i class="fa fa-phone text-primary fs-2"></i>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-35px cursor-pointer" type="checkbox" id="twilioPluginToggle" {{ $isTwilioActive ? 'checked' : '' }} onchange="togglePluginStatus('twilio_call', this.checked)">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h3 class="fw-bolder text-dark mb-0">Twilio Voice Call</h3>
                            <span id="twilioStatusBadge" class="badge {{ $isTwilioActive ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                {{ $isTwilioActive ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <p class="text-muted fs-7 mb-4">
                            Direct WebRTC browser calling & click-to-call directly from Orders page. Includes Live Softphone dialer with Hold, Mute & Inbound ringing.
                        </p>

                        <div class="bg-light rounded p-3 mb-4 fs-8 text-muted">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Twilio Number:</span>
                                <strong class="text-dark">{{ $twilioNumber ?: 'Not configured' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Call Mode:</span>
                                <span class="badge {{ $callMode === 'webrtc' ? 'badge-light-success' : 'badge-light-info' }} text-uppercase">{{ $callMode }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>TwiML App SID:</span>
                                <strong class="text-dark">{{ $twimlAppSid ? substr($twimlAppSid, 0, 10) . '...' : 'Not configured' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2 border-top">
                        <button type="button" class="btn btn-sm btn-light-primary flex-fill" data-bs-toggle="modal" data-bs-target="#twilioSettingsModal">
                            <i class="fa fa-cog me-1"></i> Settings
                        </button>
                        <button type="button" class="btn btn-sm btn-light-success flex-fill" data-bs-toggle="modal" data-bs-target="#twilioTestCallModal">
                            <i class="fa fa-phone-volume me-1"></i> Test Call
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Twilio SMS (Upcoming) -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border border-dashed shadow-sm plugin-card opacity-75">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-info p-3">
                                <i class="fa fa-comments text-info fs-2"></i>
                            </div>
                            <span class="badge badge-light-secondary fs-8">Coming Soon</span>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h3 class="fw-bolder text-dark mb-0">Twilio SMS / OTP</h3>
                        </div>

                        <p class="text-muted fs-7 mb-4">
                            Send instant SMS updates, payment links, and order status notifications directly to customer mobile numbers.
                        </p>
                    </div>

                    <div class="pt-2 border-top">
                        <button type="button" class="btn btn-sm btn-light w-100 disabled" disabled>
                            <i class="fa fa-lock me-1"></i> Available Soon
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Voice Agent (Upcoming) -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border border-dashed shadow-sm plugin-card opacity-75">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="symbol symbol-50px symbol-circle bg-light-warning p-3">
                                <i class="fa fa-robot text-warning fs-2"></i>
                            </div>
                            <span class="badge badge-light-secondary fs-8">Coming Soon</span>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h3 class="fw-bolder text-dark mb-0">AI Voice Assistant</h3>
                        </div>

                        <p class="text-muted fs-7 mb-4">
                            Automated AI voice calls for order delivery followups, payment reminders, and customer feedback collection.
                        </p>
                    </div>

                    <div class="pt-2 border-top">
                        <button type="button" class="btn btn-sm btn-light w-100 disabled" disabled>
                            <i class="fa fa-lock me-1"></i> Available Soon
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
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">API Key SID (SK...)</label>
                                            <input type="text" name="api_key_sid" class="form-control font-monospace" placeholder="e.g. SKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $apiKeySid }}">
                                            <div class="text-muted fs-8 mt-1">Found in Twilio Console &gt; API Keys &amp; Tokens</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">API Secret</label>
                                            <input type="password" name="api_secret" class="form-control font-monospace" placeholder="e.g. your_api_secret_key" value="{{ $apiSecret }}">
                                            <div class="text-muted fs-8 mt-1">Secret revealed when API Key was created.</div>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">TwiML App SID (AP...)</label>
                                            <input type="text" name="twiml_app_sid" class="form-control font-monospace" placeholder="e.g. APxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ $twimlAppSid }}">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="startTestCallBtn" class="btn btn-success">
                        <i class="fa fa-phone me-1"></i> <span id="testCallBtnText">Trigger Test Call</span>
                    </button>
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
            const badge = $('#twilioStatusBadge');
            if (isActive) {
                badge.removeClass('badge-light-danger').addClass('badge-light-success').text('Active');
            } else {
                badge.removeClass('badge-light-success').addClass('badge-light-danger').text('Inactive');
            }
            Swal.fire({
                icon: 'success',
                title: res.message,
                timer: 1200,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            $('#twilioPluginToggle').prop('checked', !isActive);
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
