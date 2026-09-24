@extends('layouts.app')

@section('content')
@php
    $settings = optional($next2callPlugin)->settings ?? [];
    $isActive = (bool) (optional($next2callPlugin)->is_active ?? true);
    $userId   = $settings['user_id']            ?? '10101';
    $password = $settings['password']           ?? '';
    $sipDomain= $settings['sip_domain']         ?? 'ringfy.next2call.com';
    $apiBase  = $settings['api_base_url']       ?? 'https://ringfy.next2call.com';
    $clickPath= $settings['click_to_dial_path'] ?? '/softphone/Phone/click-to-dial.html';

    $dialerUrl = $dialerUrl ?? ("https://{$sipDomain}/softphone/Phone/index.html?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]));

    $ctcBaseUrl = $ctcBaseUrl ?? ("https://{$sipDomain}{$clickPath}?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]));
@endphp

<div class="container-fluid py-5">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-6 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">
                <i class="fa fa-headphones text-success me-2"></i>Next2Call Softphone
            </h1>
            <div class="text-muted fw-bold fs-7">
                Configure &amp; use your Next2Call Ringfy WebRTC softphone directly inside the CRM.
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#next2callTestCallModal">
                <i class="fa fa-phone-volume me-2"></i>Test Call Modal
            </button>
            <button type="button" class="btn btn-success" onclick="window.openRingfyDialer()">
                <i class="fa fa-phone me-2"></i>Open Softphone on Screen
            </button>
            <span class="badge fs-6 {{ $isActive ? 'badge-light-success' : 'badge-light-danger' }} px-4 py-2" id="n2cStatusBadge">
                <i class="fa fa-circle me-1 fs-9"></i>{{ $isActive ? 'Active' : 'Inactive' }}
            </span>
            <div class="form-check form-switch form-check-custom form-check-solid ms-2">
                <input class="form-check-input h-25px w-45px cursor-pointer" type="checkbox"
                       id="n2cMainToggle" {{ $isActive ? 'checked' : '' }}
                       onchange="toggleNext2CallStatus(this.checked)">
                <label class="form-check-label fw-bold ms-2" for="n2cMainToggle">
                    {{ $isActive ? 'Enabled' : 'Disabled' }}
                </label>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-5">
            <i class="fa fa-check-circle fs-3 text-success me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-5">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-6">

        {{-- Left: Settings Form --}}
        <div class="col-lg-7">
            <div class="card border shadow-sm">
                <div class="card-header border-bottom d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px symbol-circle bg-light-success p-2">
                        <i class="fa fa-cog text-success fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bolder text-dark mb-0">SIP / Softphone Configuration</h4>
                        <div class="text-muted fs-8">Next2Call Ringfy PBX credentials</div>
                    </div>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('plugins.next2call.save') }}" method="POST" id="n2cSettingsForm">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required">SIP Extension / User ID</label>
                                <input type="text" name="user_id" class="form-control form-control-solid"
                                       value="{{ old('user_id', $userId) }}" placeholder="e.g. 10101" required>
                                <div class="text-muted fs-8 mt-1">Your Next2Call extension number</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required">SIP Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-solid"
                                           id="n2cPasswordField" value="{{ old('password', $password) }}" placeholder="••••••••" required>
                                    <button type="button" class="btn btn-icon btn-light" onclick="togglePassVis()">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold required">SIP Server / Domain</label>
                                <input type="text" name="sip_domain" class="form-control form-control-solid"
                                       value="{{ old('sip_domain', $sipDomain) }}" placeholder="ringfy.next2call.com" required>
                                <div class="text-muted fs-8 mt-1">Ringfy PBX hostname</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">API Base URL</label>
                                <input type="text" name="api_base_url" class="form-control form-control-solid"
                                       value="{{ old('api_base_url', $apiBase) }}" placeholder="https://ringfy.next2call.com">
                                <div class="text-muted fs-8 mt-1">Leave blank to auto-derive from SIP domain</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Click-to-Dial Path</label>
                                <input type="text" name="click_to_dial_path" class="form-control form-control-solid"
                                       value="{{ old('click_to_dial_path', $clickPath) }}" placeholder="/softphone/Phone/click-to-dial.html">
                                <div class="text-muted fs-8 mt-1">Softphone page path on the PBX server</div>
                            </div>
                            <div class="col-12 d-flex align-items-center gap-3">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input h-20px w-35px" type="checkbox" name="is_active"
                                           id="n2cActiveCheck" value="1" {{ $isActive ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold ms-2" for="n2cActiveCheck">Plugin Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-6 pt-4 border-top">
                            <button type="submit" class="btn btn-success px-6">
                                <i class="fa fa-save me-2"></i>Save Settings
                            </button>
                            <button type="button" class="btn btn-light-primary px-6" onclick="window.openRingfyDialer()">
                                <i class="fa fa-phone me-2"></i>Test Softphone on Screen
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- In-Page Embedded Softphone Container --}}
            <div class="card border shadow-sm mt-6">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-desktop text-success fs-4"></i>
                        <div>
                            <h5 class="fw-bolder mb-0">Live Embedded Softphone</h5>
                            <div class="text-muted fs-8">Direct softphone preview inside this page</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light-success" id="btnToggleInlinePhone" onclick="toggleInlineSoftphone()">
                        <i class="fa fa-eye me-1" id="inlineIcon"></i> <span id="inlineText">Show Embedded Phone</span>
                    </button>
                </div>
                <div class="card-body p-0 d-none" id="inlineSoftphoneCard">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted fs-8"><i class="fa fa-info-circle me-1"></i>Microphone permission required for calls</span>
                        <a href="{{ $dialerUrl }}" target="_blank" class="btn btn-sm btn-link text-primary p-0">
                            <i class="fa fa-external-link-alt me-1"></i>Open in New Tab
                        </a>
                    </div>
                    <iframe
                        id="inlineSoftphoneFrame"
                        data-src="{{ $dialerUrl }}"
                        src=""
                        style="width: 100%; height: 560px; border: 0;"
                        allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Right: Webphone Interface & Quick Test --}}
        <div class="col-lg-5">
            {{-- Webphone Interface Card (from HTML doc) --}}
            <div class="card border shadow-sm mb-6">
                <div class="card-header border-bottom bg-light-success">
                    <h5 class="fw-bolder mb-0 text-success">
                        <i class="fa fa-phone-volume text-success me-2"></i>Webphone Interface
                    </h5>
                </div>
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-4">
                        <div>
                            <div class="fw-bolder text-dark fs-6">Full Softphone Dialer</div>
                            <div class="text-muted fs-8">Full-featured dialer with keypad &amp; call status</div>
                        </div>
                        <button type="button" class="btn btn-success btn-icon" onclick="window.openRingfyDialer()" title="Open Softphone">
                            <i class="fa fa-phone"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-success w-100" onclick="window.openRingfyDialer()">
                        <i class="fa fa-headset me-2"></i>Launch Softphone on Screen
                    </button>
                </div>
            </div>

            {{-- Manual Dial / Test Click-to-Dial Card --}}
            <div class="card border shadow-sm mb-6">
                <div class="card-header border-bottom">
                    <h5 class="fw-bolder mb-0"><i class="fa fa-keyboard text-success me-2"></i>Manual Dial / Test Call</h5>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted fs-7 mb-3">
                        Enter any customer or test number below. The Next2Call softphone popup will open directly on your screen and place the call.
                    </p>
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                        <input type="text" id="testPhoneInput" class="form-control form-control-solid"
                               placeholder="e.g. 9876543210 or 08800826129" value="{{ $currentUserPhone ?: '08800826129' }}">
                        <button type="button" class="btn btn-success" onclick="triggerTestCall()">
                            <i class="fa fa-phone me-1"></i>Call Now
                        </button>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-sm btn-light-warning flex-fill" onclick="window.simulateNext2CallInbound && window.simulateNext2CallInbound(document.getElementById('testPhoneInput').value.trim() || '08800826129')">
                            <i class="fa fa-bell me-1"></i>Ring Inbound (Incoming)
                        </button>
                        <button type="button" class="btn btn-sm btn-light-info flex-fill" data-bs-toggle="modal" data-bs-target="#next2callTestCallModal">
                            <i class="fa fa-phone-volume me-1"></i>Open Test Modal
                        </button>
                    </div>

                    {{-- Quick Contacts from official sample --}}
                    <div class="separator separator-dashed my-4"></div>
                    <div class="fs-7 fw-bold text-muted mb-2">Quick Test Numbers:</div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <div>
                                <div class="fw-bold text-dark fs-7">USA Test Call</div>
                                <div class="text-muted fs-8">447403511446</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-success btn-icon" onclick="window.dialNext2CallNumber('447403511446')">
                                <i class="fa fa-phone"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <div>
                                <div class="fw-bold text-dark fs-7">Domestic Test Call</div>
                                <div class="text-muted fs-8">08800826129</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-success btn-icon" onclick="window.dialNext2CallNumber('08800826129')">
                                <i class="fa fa-phone"></i>
                            </button>
                        </div>
                    </div>

                    <div id="testResult" class="mt-4 d-none">
                        <div class="alert alert-success p-3">
                            <div class="fw-bold mb-1">✅ Calling via Next2Call:</div>
                            <div id="testDialUrl" class="text-break fs-8 text-muted"></div>
                        </div>
                    </div>
                    <div id="testError" class="mt-4 d-none">
                        <div class="alert alert-danger p-3 fs-8" id="testErrorMsg"></div>
                    </div>
                </div>
            </div>

            {{-- Current Config Summary --}}
            <div class="card border shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="fw-bolder mb-0"><i class="fa fa-info-circle text-primary me-2"></i>Current Configuration</h5>
                </div>
                <div class="card-body p-5 fs-7">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Plugin Status</span>
                        <span class="badge {{ $isActive ? 'badge-light-success' : 'badge-light-danger' }}">{{ $isActive ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">SIP Extension</span>
                        <strong>{{ $userId }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">SIP Server</span>
                        <strong>{{ $sipDomain }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">API Base</span>
                        <strong class="text-truncate ms-3" style="max-width:160px">{{ $apiBase }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Click-to-Dial Path</span>
                        <strong class="text-truncate ms-3" style="max-width:160px">{{ $clickPath }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}
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
                            <button type="button" class="btn btn-xs btn-light-dark py-1 px-2 fs-8" onclick="setN2cTestNumber('{{ $userId ?: '10101' }}')">
                                <i class="fa fa-user-circle me-1"></i> Ext ({{ $userId ?: '10101' }})
                            </button>
                        </div>
                    </div>

                    <!-- Active Configuration Info -->
                    <div class="bg-light rounded p-3 fs-8 text-muted mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>SIP Extension:</span>
                            <strong class="text-dark">{{ $userId ?: '10101' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>SIP Domain:</span>
                            <strong class="text-dark">{{ $sipDomain }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge {{ $isActive ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                {{ $isActive ? 'Active' : 'Inactive' }}
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
function togglePassVis() {
    const f = document.getElementById('n2cPasswordField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') { f.type = 'text'; i.className = 'fa fa-eye-slash'; }
    else { f.type = 'password'; i.className = 'fa fa-eye'; }
}

function toggleNext2CallStatus(isChecked) {
    const label = document.querySelector('label[for="n2cMainToggle"]');
    const badge = document.getElementById('n2cStatusBadge');
    fetch('{{ route("plugins.toggle") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ plugin_key: 'next2call', is_active: isChecked })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            label.textContent = isChecked ? 'Enabled' : 'Disabled';
            badge.className = 'badge fs-6 px-4 py-2 ' + (isChecked ? 'badge-light-success' : 'badge-light-danger');
            badge.innerHTML = '<i class="fa fa-circle me-1 fs-9"></i>' + (isChecked ? 'Active' : 'Inactive');
            document.getElementById('n2cActiveCheck').checked = isChecked;
            Swal && Swal.fire({ icon: 'success', title: d.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
        }
    });
}

function triggerTestCall() {
    const phone = document.getElementById('testPhoneInput').value.trim();
    if (!phone) {
        alert('Please enter a phone number to test.');
        document.getElementById('testPhoneInput').focus();
        return;
    }

    // Immediately trigger the in-screen softphone popup
    if (typeof window.dialNext2CallNumber === 'function') {
        window.dialNext2CallNumber(phone);
    }

    document.getElementById('testResult').classList.add('d-none');
    document.getElementById('testError').classList.add('d-none');

    fetch('{{ route("plugins.next2call.test") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ test_phone_number: phone })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.getElementById('testDialUrl').textContent = d.dial_url;
            document.getElementById('testResult').classList.remove('d-none');
        } else {
            document.getElementById('testErrorMsg').textContent = d.message || 'Test failed.';
            document.getElementById('testError').classList.remove('d-none');
        }
    })
    .catch(() => {
        // Softphone is already opened on screen via client-side helper
    });
}

function toggleInlineSoftphone() {
    const card = document.getElementById('inlineSoftphoneCard');
    const iframe = document.getElementById('inlineSoftphoneFrame');
    const text = document.getElementById('inlineText');
    const icon = document.getElementById('inlineIcon');

    if (card.classList.contains('d-none')) {
        card.classList.remove('d-none');
        if (!iframe.src || iframe.src === 'about:blank') {
            iframe.src = iframe.dataset.src;
        }
        text.textContent = 'Hide Embedded Phone';
        icon.className = 'fa fa-eye-slash me-1';
    } else {
        card.classList.add('d-none');
        text.textContent = 'Show Embedded Phone';
        icon.className = 'fa fa-eye me-1';
    }
}

// Next2Call Modal Test Helpers
function setN2cTestNumber(val) {
    const input = document.getElementById('n2c_test_phone_number');
    if (input) input.value = val;
}

function testNext2CallInbound() {
    const testNum = (document.getElementById('n2c_test_phone_number')?.value || '').trim() || '08800826129';
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

    Swal && Swal.fire({
        icon: 'success',
        title: 'Incoming Call Dispatched!',
        html: `Next2Call Softphone is ringing on your screen from <strong>${testNum}</strong>.<br><small class="text-muted">Check the softphone widget popup at the bottom-right of your screen.</small>`,
        timer: 4500,
        showConfirmButton: true
    });
}

function testNext2CallOutbound() {
    const testNum = (document.getElementById('n2c_test_phone_number')?.value || '').trim();
    if (!testNum) {
        Swal && Swal.fire('Error', 'Please enter a test phone number.', 'warning');
        return;
    }
    $('#next2callTestCallModal').modal('hide');

    if (typeof window.dialNext2CallNumber === 'function') {
        window.dialNext2CallNumber(testNum);
    } else if (typeof window.openRingfyDialer === 'function') {
        window.openRingfyDialer(testNum);
    }

    Swal && Swal.fire({
        icon: 'info',
        title: 'Dialing via Softphone...',
        text: `Opening Next2Call softphone dialer for ${testNum}.`,
        timer: 2500,
        showConfirmButton: false
    });
}

$('#next2callTestCallForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#n2cStartTestApiBtn');
    const statusBox = $('#n2cTestStatusContainer');
    const testNumber = $('#n2c_test_phone_number').val().trim();

    if (!testNumber) {
        Swal && Swal.fire('Error', 'Please enter a test phone number.', 'warning');
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

@endsection
