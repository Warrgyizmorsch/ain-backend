@once
@php
    $n2cPlugin = \Illuminate\Support\Facades\Cache::remember('plugin_next2call_setting', 300, function() {
        return \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    });
    $n2cIsActive = (bool) ($n2cPlugin?->is_active ?? false);
    if (!auth()->check() || !$n2cIsActive) {
        return;
    }
    $creds = \App\Http\Controllers\PluginController::resolveNext2CallCredentials();
    $userId = $creds['user_id'];
    $password = $creds['password'];
    $sipDomain = $creds['sip_domain'];
    $clickToDialPath = $creds['click_to_dial_path'];

    $isSuperAdmin = auth()->check() && ((int) auth()->user()->role_id === 1);

    $n2cDialerUrl = "https://{$sipDomain}/api-section/softphone/Phone/index.html?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);

    $n2cCtcBaseUrl = "https://{$sipDomain}/api-section/softphone/Phone/click-to-dial.html?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]) . '&d=';
@endphp

<style>
    /* Next2Call Softphone Floating Box */
    .n2c-softphone-box {
        position: fixed;
        right: 25px;
        bottom: 30px;
        width: 320px;
        max-width: calc(100vw - 30px);
        background: #1e1e2d;
        color: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.12);
        z-index: 99999;
        overflow: hidden;
        display: none;
        flex-direction: column;
        animation: n2cSlideUp 0.25s ease-out;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    @keyframes n2cSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .n2c-softphone-box.is-open {
        display: flex;
    }

    /* Header */
    .n2c-softphone-header {
        padding: 10px 14px;
        background: #151521;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: grab;
        user-select: none;
    }
    .n2c-softphone-header:active {
        cursor: grabbing;
    }
    .n2c-title-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow: hidden;
    }
    .n2c-title-text {
        font-weight: 700;
        font-size: 12px;
        color: #ffffff;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .n2c-header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }
    .n2c-header-btn {
        background: rgba(255, 255, 255, 0.08);
        color: #a1a5b7;
        border: 0;
        border-radius: 6px;
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.15s, color 0.15s;
    }
    .n2c-header-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Active Call Screen (Default on Click-to-Call) */
    .n2c-call-screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 28px 18px 24px;
        background: radial-gradient(circle at center, #1e1e2d 0%, #151521 100%);
        min-height: 380px;
        text-align: center;
    }
    .n2c-avatar-ring {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.12);
        border: 2px solid #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        font-size: 28px;
        margin-bottom: 16px;
        animation: n2cPulseRing 1.5s infinite;
        transition: all 0.3s ease;
    }
    .n2c-avatar-ring.is-connected {
        background: rgba(16, 185, 129, 0.12);
        border-color: #10b981;
        color: #10b981;
        animation: n2cPulseRingGreen 1.5s infinite;
    }
    @keyframes n2cPulseRing {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
        70% { box-shadow: 0 0 0 16px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes n2cPulseRingGreen {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
        70% { box-shadow: 0 0 0 16px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .n2c-caller-name {
        font-size: 17px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 6px;
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .n2c-caller-number {
        font-size: 13px;
        font-weight: 600;
        color: #10b981;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        padding: 4px 14px;
        border-radius: 20px;
        margin-bottom: 12px;
        display: inline-block;
        letter-spacing: 0.5px;
    }
    .n2c-call-status {
        font-size: 12px;
        color: #a1a5b7;
        margin-bottom: 8px;
    }
    .n2c-call-timer {
        font-size: 22px;
        font-weight: 700;
        font-family: monospace;
        color: #ffffff;
        margin-bottom: 22px;
        letter-spacing: 1px;
    }
    .n2c-action-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        width: 100%;
    }
    .n2c-call-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .n2c-call-btn:hover {
        transform: scale(1.08);
    }
    .n2c-btn-hangup {
        background: #f1416c;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(241, 65, 108, 0.4);
    }
    .n2c-btn-hangup:hover {
        background: #d9214e;
    }
    .n2c-btn-keypad {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .n2c-btn-keypad:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    .n2c-btn-mute {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .n2c-btn-mute:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    .n2c-btn-mute.is-muted {
        background: #ffc700;
        color: #1e1e2d;
    }

    /* Container for Iframe and Call Screen */
    .n2c-iframe-container {
        position: relative;
        width: 100%;
        background: #151521;
        overflow: hidden;
    }
    .n2c-softphone-iframe {
        width: 100%;
        height: 560px;
        border: 0;
        display: block;
        background: #ffffff;
    }

    /* When NOT showing keypad: active call screen covers iframe, iframe remains active in background */
    .n2c-softphone-box:not(.show-keypad) .n2c-call-screen {
        display: flex;
    }
    .n2c-softphone-box:not(.show-keypad) .n2c-softphone-iframe {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0.01;
        pointer-events: none;
        top: -9999px;
    }
    .n2c-softphone-box:not(.show-keypad) .n2c-keypad-return-bar {
        display: none;
    }

    /* When SHOWING keypad: active call screen hides, iframe is full size */
    .n2c-softphone-box.show-keypad .n2c-call-screen {
        display: none;
    }
    .n2c-softphone-box.show-keypad .n2c-softphone-iframe {
        position: static;
        width: 100%;
        height: 560px;
        opacity: 1;
        pointer-events: auto;
    }
    .n2c-softphone-box.show-keypad .n2c-keypad-return-bar {
        display: flex;
    }

    .n2c-keypad-return-bar {
        background: #151521;
        padding: 6px 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        align-items: center;
        justify-content: space-between;
    }
    .n2c-keypad-return-btn {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: none;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
    }
    .n2c-keypad-return-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    @media (max-width: 575.98px) {
        .n2c-softphone-box {
            right: 10px;
            bottom: 10px;
            width: calc(100vw - 20px);
        }
    }
</style>

<!-- Next2Call Softphone Floating Widget Box -->
<div id="ringfySoftphoneWidget" class="n2c-softphone-box" aria-live="polite"
     data-dialer-url="{!! $n2cDialerUrl !!}"
     data-ctc-base="{!! $n2cCtcBaseUrl !!}">
    
    <!-- Drag Header -->
    <div id="ringfySoftphoneHandle" class="n2c-softphone-header">
        <div class="n2c-title-wrap">
            <i class="fa fa-phone text-success fs-7"></i>
            <span class="n2c-title-text" id="n2cHeaderTitle">Next2Call Softphone</span>
        </div>
        <div class="n2c-header-actions">
            <button type="button" class="n2c-header-btn" id="n2cRefreshBtn" title="Re-Login / Refresh 12h Session">
                <i class="fa fa-sync-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cCloseBtn" title="Close Softphone">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Return Bar (Only visible when viewing Keypad) -->
    <div class="n2c-keypad-return-bar" id="n2cKeypadReturnBar">
        <span class="text-muted" style="font-size: 11px;">Active Call Keypad</span>
        <button type="button" class="n2c-keypad-return-btn" id="n2cReturnToCallBtn">
            <i class="fa fa-arrow-left me-1"></i> Back to Call
        </button>
    </div>

    <!-- Default Next2Call Phone Iframe Container -->
    <div id="n2cIframeContainer" class="n2c-iframe-container">
        <!-- Active Calling Screen (Shown by default on Click-to-Call) -->
        <div id="n2cActiveCallView" class="n2c-call-screen">
            <div class="n2c-avatar-ring" id="n2cAvatarRing">
                <i class="fa fa-phone"></i>
            </div>
            <div class="n2c-caller-name" id="n2cCustomerName">Customer</div>
            <div class="n2c-caller-number" id="n2cMaskedNumber">+91 ******2299</div>
            <div class="n2c-call-status" id="n2cStatusBadge" style="cursor: pointer;" title="Click when answered to connect">
                <i class="fa fa-spinner fa-spin me-1 text-warning"></i> Ringing...
            </div>
            <div class="n2c-call-timer" id="n2cCallTimerText">Ringing...</div>

            <!-- Connect Trigger (Manual/Visual confirmation fallback) -->
            <div id="n2cConnectTriggerWrap" style="margin-top: -10px; margin-bottom: 20px;">
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm" id="n2cMarkConnectedBtn" style="font-size: 11px; font-weight: 600;">
                    <i class="fa fa-phone-volume me-1"></i> Click when Answered
                </button>
            </div>

            <!-- In-Call Actions (Mute, Hangup, Keypad) -->
            <div class="n2c-action-bar">
                <button type="button" class="n2c-call-btn n2c-btn-mute" id="n2cMuteBtn" title="Mute Microphone">
                    <i class="fa fa-microphone" id="n2cMuteIcon"></i>
                </button>
                <button type="button" class="n2c-call-btn n2c-btn-hangup" id="n2cHangupBtn" title="End Call">
                    <i class="fa fa-phone" style="transform: rotate(135deg);"></i>
                </button>
                <button type="button" class="n2c-call-btn n2c-btn-keypad" id="n2cKeypadToggleBtn" title="Open Dialpad / Keypad">
                    <i class="fa fa-th"></i>
                </button>
            </div>
        </div>

        <iframe
            id="ringfySoftphoneFrame"
            class="n2c-softphone-iframe"
            src=""
            allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
            allowfullscreen>
        </iframe>
    </div>
</div>

<script>
    function initRingfySoftphoneWidget() {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const handle = document.getElementById('ringfySoftphoneHandle');
        const iframeWrap = document.getElementById('n2cIframeContainer');

        const customerNameEl = document.getElementById('n2cCustomerName');
        const maskedNumberEl = document.getElementById('n2cMaskedNumber');
        const statusBadge = document.getElementById('n2cStatusBadge');
        const timerTextEl = document.getElementById('n2cCallTimerText');
        const avatarRing = document.getElementById('n2cAvatarRing');

        const closeBtn = document.getElementById('n2cCloseBtn');
        const refreshBtn = document.getElementById('n2cRefreshBtn');
        const hangupBtn = document.getElementById('n2cHangupBtn');
        const muteBtn = document.getElementById('n2cMuteBtn');
        const keypadToggleBtn = document.getElementById('n2cKeypadToggleBtn');
        const returnToCallBtn = document.getElementById('n2cReturnToCallBtn');
        const markConnectedBtn = document.getElementById('n2cMarkConnectedBtn');
        const connectTriggerWrap = document.getElementById('n2cConnectTriggerWrap');

        let callTimerInterval = null;
        let callSeconds = 0;
        let isCallConnected = false;
        let isMuted = false;

        function connectCallUI() {
            if (isCallConnected || !isCallActive) return;
            isCallConnected = true;
            if (statusBadge) {
                statusBadge.innerHTML = '<i class="fa fa-circle text-success me-1" style="font-size: 8px;"></i> Connected';
            }
            if (avatarRing) {
                avatarRing.classList.add('is-connected');
            }
            if (connectTriggerWrap) {
                connectTriggerWrap.style.display = 'none';
            }
            startCallTimer();
        }

        markConnectedBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            connectCallUI();
        });

        statusBadge?.addEventListener('click', function (e) {
            if (!isCallConnected && isCallActive) {
                connectCallUI();
            }
        });

        function startCallTimer() {
            stopCallTimer();
            callSeconds = 0;
            updateCallTimer();
            callTimerInterval = setInterval(() => {
                callSeconds++;
                updateCallTimer();
            }, 1000);
        }

        function stopCallTimer() {
            if (callTimerInterval) {
                clearInterval(callTimerInterval);
                callTimerInterval = null;
            }
            callSeconds = 0;
        }

        function updateCallTimer() {
            const m = Math.floor(callSeconds / 60).toString().padStart(2, '0');
            const s = (callSeconds % 60).toString().padStart(2, '0');
            if (timerTextEl) timerTextEl.textContent = `${m}:${s}`;
        }

        function ensureNext2CallIpAllowed(callback) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch('https://api.ipify.org?format=json')
                .then(r => r.json())
                .then(data => {
                    return fetch('{{ route('softphone.whitelist-ip') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ client_ip: data.ip })
                    });
                })
                .catch(() => {
                    return fetch('{{ route('softphone.whitelist-ip') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                })
                .finally(() => {
                    if (typeof callback === 'function') callback();
                });
        }

        // Re-Login & Refresh Session
        refreshBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon) icon.classList.add('fa-spin');
            if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Refreshing 12h session...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch('{{ route('softphone.call-url') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ force_refresh: true }),
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.success && res.dialer_url) {
                    const freshDialer = res.dialer_url.replace(/&amp;/g, '&');
                    if (widget) {
                        widget.dataset.dialerUrl = freshDialer;
                        DIALER_URL = freshDialer;
                        if (res.url) {
                            const newBase = res.url.split('&d=')[0] + '&d=';
                            widget.dataset.ctcBase = newBase;
                            CTC_BASE_URL = newBase;
                        }
                    }
                    const currentFrame = document.getElementById('ringfySoftphoneFrame');
                    if (currentFrame) {
                        currentFrame.src = freshDialer;
                    }
                }
            })
            .catch(err => {
                console.warn('[Next2Call] Session refresh error:', err);
            })
            .finally(() => {
                ensureNext2CallIpAllowed(function () {
                    setTimeout(() => {
                        if (icon) icon.classList.remove('fa-spin');
                        if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-circle text-success me-1" style="font-size: 8px;"></i> Session Ready';
                    }, 1200);
                });
            });
        });

        // Automatically ensure agent IP is whitelisted on Next2Call when page loads
        ensureNext2CallIpAllowed();

        let DIALER_URL = (widget?.dataset?.dialerUrl || '').replace(/&amp;/g, '&');
        let CTC_BASE_URL = (widget?.dataset?.ctcBase || '').replace(/&amp;/g, '&');

        let currentFullNumber = '';
        let callInitiatedAt = 0;
        let isCallActive = false;

        // Draggable Widget Logic
        let isDragging = false;
        let startX = 0, startY = 0;

        function applyWidgetPosition(left, top) {
            if (!widget) return;
            const margin = 10;
            const maxLeft = window.innerWidth - widget.offsetWidth - margin;
            const maxTop = window.innerHeight - widget.offsetHeight - margin;
            const safeLeft = Math.max(margin, Math.min(left, maxLeft));
            const safeTop = Math.max(margin, Math.min(top, maxTop));
            widget.style.left = safeLeft + 'px';
            widget.style.top = safeTop + 'px';
            widget.style.right = 'auto';
            widget.style.bottom = 'auto';
        }

        handle?.addEventListener('mousedown', function (e) {
            if (e.target.closest('button')) return;
            isDragging = true;
            const rect = widget.getBoundingClientRect();
            startX = e.clientX - rect.left;
            startY = e.clientY - rect.top;
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', function (e) {
            if (!isDragging) return;
            applyWidgetPosition(e.clientX - startX, e.clientY - startY);
        });

        document.addEventListener('mouseup', function () {
            if (!isDragging) return;
            isDragging = false;
            document.body.style.userSelect = '';
        });

        function mountIframe(url) {
            const container = document.getElementById('n2cIframeContainer') || iframeWrap;
            if (!container) return;
            const cleanUrl = url ? url.replace(/&amp;/g, '&') : '';
            const currentFrame = document.getElementById('ringfySoftphoneFrame');

            if (currentFrame) {
                currentFrame.src = cleanUrl;
                return;
            }

            const frame = document.createElement('iframe');
            frame.id = 'ringfySoftphoneFrame';
            frame.className = 'n2c-softphone-iframe';
            frame.src = cleanUrl;
            frame.allow = 'microphone; camera; speaker-selection; display-capture; autoplay; fullscreen';
            frame.setAttribute('allowfullscreen', 'true');
            container.appendChild(frame);
        }

        // Close Widget
        function closeSoftphoneWidget() {
            stopCallTimer();
            isCallActive = false;
            isCallConnected = false;
            callInitiatedAt = 0;

            if (widget) {
                widget.classList.remove('is-open');
                widget.classList.remove('show-keypad');
                widget.style.display = 'none';
            }

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) headerTitleEl.textContent = 'Next2Call Softphone';

            if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-circle text-muted me-1" style="font-size: 8px;"></i> Ready';
            if (timerTextEl) timerTextEl.textContent = '00:00';
            if (avatarRing) avatarRing.classList.remove('is-connected');
            if (connectTriggerWrap) connectTriggerWrap.style.display = 'block';
        }

        // Hangup call: terminate SIP audio & reset frame
        function hangupNext2Call() {
            if (statusBadge) statusBadge.innerHTML = '<span class="text-danger fw-bold">Call Ended</span>';
            stopCallTimer();
            isCallActive = false;
            isCallConnected = false;
            if (avatarRing) avatarRing.classList.remove('is-connected');

            // Reset frame to idle dialer to instantly drop the WebRTC call
            const currentFrame = document.getElementById('ringfySoftphoneFrame');
            if (currentFrame && DIALER_URL) {
                currentFrame.src = DIALER_URL;
            }

            setTimeout(() => {
                closeSoftphoneWidget();
            }, 600);
        }

        hangupBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            hangupNext2Call();
        });

        closeBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            hangupNext2Call();
        });

        // Keypad toggle handlers
        keypadToggleBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            widget?.classList.add('show-keypad');
        });

        returnToCallBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            widget?.classList.remove('show-keypad');
        });

        // Mute Microphone toggle
        muteBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            isMuted = !isMuted;
            this.classList.toggle('is-muted', isMuted);
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isMuted ? 'fa fa-microphone-slash' : 'fa fa-microphone';
            }
            if (typeof toastr !== 'undefined') {
                toastr.info(isMuted ? 'Microphone Muted' : 'Microphone Unmuted');
            }
        });

        // Close widget with Escape key if needed
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && widget && widget.classList.contains('is-open')) {
                closeSoftphoneWidget();
            }
        });

        // Listen for events from Next2Call dialer
        window.addEventListener('message', function (event) {
            if (event.origin !== 'https://ringfy.next2call.com') return;

            let data = event.data;
            if (typeof data === 'string') {
                try { data = JSON.parse(data); } catch(e){}
            }

            const msgType = data?.type || data;

            // Connected / Answered events
            if (
                msgType === 'CALL_CONNECTED' ||
                msgType === 'CALL_ACCEPTED' ||
                msgType === 'CONNECTED' ||
                msgType === 'ANSWERED' ||
                msgType === 'accepted'
            ) {
                console.log('[Next2Call] Call connected event received:', msgType);
                connectCallUI();
                return;
            }

            // Hangup events
            if (msgType === 'CALL_HANGUP' || msgType === 'CLOSE_PHONE_POPUP' || msgType === 'CALL_DISCONNECTED') {
                console.log('[Next2Call] Call disconnected / hangup received');
                isCallActive = false;
                isCallConnected = false;
                stopCallTimer();
                if (statusBadge) statusBadge.innerHTML = '<span class="text-danger">Call Ended</span>';
                if (avatarRing) avatarRing.classList.remove('is-connected');
                setTimeout(() => {
                    closeSoftphoneWidget();
                }, 800);
            }
        });

        // Global Direct Dial Function with Country Code & Masking
        window.dialNext2CallNumber = function (rawNumber, countryCode = '', contactName = 'Customer') {
            let inputStr = String(rawNumber || '').trim();
            // Resolve masked number if contains asterisks
            if (inputStr.includes('*') && typeof window.resolveMaskedToken === 'function') {
                const resolved = window.resolveMaskedToken(inputStr);
                if (resolved) {
                    inputStr = resolved;
                }
            }

            let num = inputStr.replace(/[^0-9]/g, '');
            let cc = String(countryCode || '').trim().replace(/[^0-9]/g, '');
            if (!num) return;

            // Strip leading zero if 11 digits (e.g. 09610092299 -> 9610092299)
            if (num.startsWith('0') && num.length === 11) {
                num = num.substring(1);
            }

            // Always format with Country Code (e.g. 919610092299)
            if (cc) {
                if (!num.startsWith(cc)) {
                    num = cc + num;
                }
            } else if (num.length === 10) {
                num = '91' + num;
            } else if (num.startsWith('440')) {
                num = '44' + num.substring(3);
            }

            currentFullNumber = num;
            callInitiatedAt = Date.now();
            isCallActive = true;
            isCallConnected = false;

            // Reset UI state to Ringing
            stopCallTimer();
            if (avatarRing) avatarRing.classList.remove('is-connected');
            if (connectTriggerWrap) connectTriggerWrap.style.display = 'block';
            if (timerTextEl) timerTextEl.textContent = 'Ringing...';

            // Masked display formatting for UI (e.g. +91 ******2299)
            let maskedDisplayNum = num;
            if (num.length >= 6) {
                const ccPart = num.length > 10 ? ('+' + num.slice(0, num.length - 10)) : '+91';
                maskedDisplayNum = ccPart + ' ******' + num.slice(-4);
            }

            // Open Widget in Active Calling Mode (NOT raw dialer keypad!)
            if (widget) {
                const twilioBox = document.getElementById('twilioSoftphoneBox');
                if (twilioBox && $(twilioBox).is(':visible') && !widget.style.left) {
                    widget.style.right = '325px';
                } else if (!widget.style.left) {
                    widget.style.right = '25px';
                }
                widget.classList.remove('show-keypad');
                widget.style.display = 'flex';
                widget.classList.add('is-open');
            }

            // Set customer name, masked phone number & status
            const cName = (contactName && contactName !== 'Customer') ? contactName : 'Customer';
            if (customerNameEl) customerNameEl.textContent = cName;
            if (maskedNumberEl) maskedNumberEl.textContent = maskedDisplayNum;
            if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-spinner fa-spin me-1 text-warning"></i> Ringing...';

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) {
                headerTitleEl.textContent = `Call: ${cName} (${maskedDisplayNum})`;
            }

            // NOTE: Timer will only start when the call is actually connected/answered via connectCallUI()

            // Calculate target direct dial URL immediately from base CTC
            let baseCtc = ((widget?.dataset?.ctcBase) || CTC_BASE_URL || '').replace(/&amp;/g, '&');
            if (!baseCtc.includes('api-section')) {
                baseCtc = baseCtc.replace('/softphone/Phone/', '/api-section/softphone/Phone/');
            }
            if (baseCtc.includes('index.html')) {
                baseCtc = baseCtc.replace('index.html', 'click-to-dial.html');
            }

            let targetCallUrl = '';
            if (baseCtc) {
                if (baseCtc.includes('&d=')) {
                    targetCallUrl = baseCtc.replace(/&d=[^&]*/, `&d=${encodeURIComponent(num)}`);
                } else {
                    targetCallUrl = `${baseCtc}&d=${encodeURIComponent(num)}`;
                }
            }

            // Mount and load iframe with target auto-dial URL
            if (targetCallUrl) {
                mountIframe(targetCallUrl);
            }

            // Background dynamic check to keep session synchronized (non-blocking)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch('{{ route('softphone.call-url') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    country_code: cc,
                    mobile: num,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res && res.success && res.url) {
                    const freshUrl = res.url.replace(/&amp;/g, '&');
                    if (widget && res.url.includes('&d=')) {
                        const newBase = res.url.split('&d=')[0] + '&d=';
                        widget.dataset.ctcBase = newBase;
                        CTC_BASE_URL = newBase;
                    }
                    if (!targetCallUrl) {
                        mountIframe(freshUrl);
                    }
                }
            })
            .catch(err => {
                console.warn('[Next2Call] background call-url sync error:', err);
            });
        };

        window.openRingfyDialer = function (mobile = '') {
            if (mobile) {
                window.dialNext2CallNumber(mobile);
                return;
            }

            const currentWidget = document.getElementById('ringfySoftphoneWidget') || widget;
            if (currentWidget) {
                const twilioBox = document.getElementById('twilioSoftphoneBox');
                if (twilioBox && $(twilioBox).is(':visible') && !currentWidget.style.left) {
                    currentWidget.style.right = '325px';
                } else if (!currentWidget.style.left) {
                    currentWidget.style.right = '25px';
                }
                currentWidget.classList.add('show-keypad');
                currentWidget.style.display = 'flex';
                currentWidget.classList.add('is-open');
            }

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) headerTitleEl.textContent = 'Next2Call Softphone';

            let activeDialerUrl = ((currentWidget?.dataset?.dialerUrl) || DIALER_URL).replace(/&amp;/g, '&');
            if (!activeDialerUrl.includes('api-section')) {
                activeDialerUrl = activeDialerUrl.replace('/softphone/Phone/', '/api-section/softphone/Phone/');
            }
            mountIframe(activeDialerUrl);
        };

        window.dialNumber = function (mobile, countryCode = '', contactName = 'Customer') {
            window.dialNext2CallNumber(mobile, countryCode, contactName);
        };

        window.openRingfySoftphone = async function (orderId, countryCode = '', mobile = '', contactName = 'Customer') {
            const cleanCode = String(countryCode || '').trim();
            let cleanMobile = String(mobile || '').trim();

            if (!cleanMobile && orderId) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                    const res = await fetch('{{ route('softphone.call-url') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ order_id: orderId }),
                    });
                    const data = await res.json();
                    if (data && data.success && data.target_number) {
                        dialNext2CallNumber(data.target_number, '', data.customer_name || contactName);
                        return;
                    }
                } catch(e) {}
            }

            if (!cleanMobile) {
                Swal?.fire({
                    icon: 'warning',
                    title: 'Number Missing',
                    text: 'Mobile number is required to make a call.',
                });
                return;
            }

            dialNext2CallNumber(cleanMobile, cleanCode, contactName);
        };

        // =========================================================================
        // Seamless In-Call Navigation Engine:
        // Keeps WebRTC voice call 100% active and connected when agent clicks other
        // CRM pages, orders, menus, or customer history during a live call!
        // =========================================================================
        async function n2cSeamlessNavigateTo(url) {
            try {
                if ($('#n2c-nav-progress').length === 0) {
                    $('body').append('<div id="n2c-nav-progress" style="position:fixed;top:0;left:0;height:3px;background:#10b981;width:0%;z-index:999999;transition:width 0.3s ease;box-shadow:0 0 10px #10b981;"></div>');
                }
                $('#n2c-nav-progress').css('width', '40%').show();

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    window.location.href = url;
                    return;
                }

                $('#n2c-nav-progress').css('width', '80%');
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTitle = doc.querySelector('title')?.innerText || document.title;
                const newMain = doc.querySelector('main.content') || doc.querySelector('#kt_content') || doc.querySelector('#kt_wrapper');
                const currentMain = document.querySelector('main.content') || doc.querySelector('#kt_content') || doc.querySelector('#kt_wrapper');

                if (newMain && currentMain) {
                    currentMain.innerHTML = newMain.innerHTML;
                    document.title = newTitle;
                    window.history.pushState({ path: url }, newTitle, url);

                    // Re-run inline/embedded scripts in the new content
                    const scripts = newMain.querySelectorAll('script');
                    scripts.forEach(s => {
                        const newScript = document.createElement('script');
                        if (s.src) {
                            newScript.src = s.src;
                        } else {
                            newScript.textContent = s.textContent;
                        }
                        document.body.appendChild(newScript);
                    });

                    // Update active menu link
                    $('.menu-link').removeClass('active');
                    $(`a[href="${url}"]`).addClass('active');

                    // Fire ready/resize events
                    $(document).trigger('ready');
                    window.dispatchEvent(new Event('resize'));
                } else {
                    window.location.href = url;
                }

                $('#n2c-nav-progress').css('width', '100%');
                setTimeout(() => $('#n2c-nav-progress').fadeOut(200).css('width', '0%'), 250);
            } catch (err) {
                console.error('[Next2Call] Seamless navigation error, falling back:', err);
                window.location.href = url;
            }
        }

        // 1. Intercept internal CRM link clicks while softphone widget is open with live call
        $(document).on('click', 'a[href]', function(e) {
            const href = $(this).attr('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || $(this).attr('target') === '_blank' || $(this).attr('download')) {
                return;
            }

            // Only intercept if within same origin (internal CRM navigation)
            if (href.startsWith('http://') || href.startsWith('https://')) {
                if (!href.startsWith(window.location.origin)) {
                    return;
                }
            }

            // If a live call is active or softphone popup is open: do NOT reload page, navigate seamlessly!
            if (widget && widget.classList.contains('is-open')) {
                e.preventDefault();
                n2cSeamlessNavigateTo(href);
            }
        });

        // 2. Handle browser Back/Forward buttons during active call
        window.addEventListener('popstate', function(e) {
            if (widget && widget.classList.contains('is-open')) {
                n2cSeamlessNavigateTo(window.location.href);
            }
        });

        // 3. Beforeunload guard: warn agent if they accidentally hit refresh or close tab during call
        window.addEventListener('beforeunload', function(e) {
            if (widget && widget.classList.contains('is-open') && isCallActive) {
                e.preventDefault();
                e.returnValue = 'Live Next2Call call chal rahi hai. Page reload karne se call disconnect ho jayegi.';
                return e.returnValue;
            }
        });
    }

    window.initRingfySoftphoneWidget = initRingfySoftphoneWidget;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRingfySoftphoneWidget);
    } else {
        initRingfySoftphoneWidget();
    }
</script>
@endonce
