@once
@php
    $n2cPlugin = \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    $n2cIsActive = (bool) ($n2cPlugin?->is_active ?? true);
    if (!auth()->check() || !$n2cIsActive) {
        return;
    }
    $n2cSettings = $n2cPlugin?->settings ?? [];

    $userId = !empty($n2cSettings['user_id']) ? $n2cSettings['user_id'] : '';
    $password = !empty($n2cSettings['password']) ? $n2cSettings['password'] : '';
    $sipDomain = !empty($n2cSettings['sip_domain']) ? $n2cSettings['sip_domain'] : 'ringfy.next2call.com';
    $clickToDialPath = !empty($n2cSettings['click_to_dial_path']) ? $n2cSettings['click_to_dial_path'] : '/softphone/Phone/click-to-dial.html';

    if (auth()->check()) {
        $authUser = auth()->user();
        if (!empty($authUser->sip) && !empty($authUser->sip_password)) {
            $userId = $authUser->sip;
            $password = $authUser->sip_password;
        } elseif (!empty($authUser->call_id) && !empty($authUser->sip_password)) {
            $userId = $authUser->call_id;
            $password = $authUser->sip_password;
        }
    }

    $n2cDialerUrl = "https://{$sipDomain}/softphone/Phone/index.html?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);

    $n2cExternalCtcUrl = "https://{$sipDomain}{$clickToDialPath}?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);

    $n2cCtcBaseUrl = route('softphone.client') . '?' . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);
@endphp

<style>
    /* Next2Call Twilio-Style Softphone Box */
    .n2c-softphone-box {
        position: fixed;
        right: 25px;
        bottom: 30px;
        width: 320px;
        max-width: calc(100vw - 30px);
        background: #1e1e2d;
        color: #ffffff;
        border-radius: 16px;
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
        padding: 12px 16px;
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
    }
    .n2c-title-text {
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
    }
    .n2c-status-badge {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 600;
        background: rgba(16, 185, 129, 0.18);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .n2c-header-actions {
        display: flex;
        align-items: center;
        gap: 4px;
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
        font-size: 11px;
        transition: background 0.15s, color 0.15s;
    }
    .n2c-header-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Active Calling Screen (Twilio Style) */
    .n2c-active-card {
        padding: 24px 18px 20px 18px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #1e1e2d;
    }
    .n2c-avatar-ring {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 14px;
        animation: n2cPulseRing 1.4s infinite;
    }
    @keyframes n2cPulseRing {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 16px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .n2c-customer-name {
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 4px 0;
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .n2c-masked-number {
        color: #10b981;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 1px;
        font-family: monospace, sans-serif;
        margin-bottom: 12px;
    }
    .n2c-timer-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 20px;
    }
    .n2c-timer-dot {
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: n2cPulseRing 1.2s infinite;
    }
    .n2c-notice-box {
        font-size: 11px;
        line-height: 1.45;
        padding: 8px 12px;
        margin: 0 14px 14px 14px;
        border-radius: 8px;
        text-align: center;
        width: calc(100% - 28px);
        box-sizing: border-box;
    }
    .n2c-notice-danger {
        background: rgba(241, 65, 108, 0.15);
        color: #ff859d;
        border: 1px solid rgba(241, 65, 108, 0.35);
    }

    /* Actions (Mute, Hangup, Keypad) */
    .n2c-action-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        width: 100%;
    }
    .n2c-call-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .n2c-call-btn:hover {
        transform: scale(1.08);
    }
    .n2c-btn-hangup {
        width: 54px;
        height: 54px;
        background: #f1416c;
        color: #ffffff;
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(241, 65, 108, 0.45);
    }
    .n2c-btn-hangup:hover {
        background: #d9214e;
    }
    .n2c-btn-mute {
        background: #2b2b40;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .n2c-btn-mute.is-muted {
        background: #f59e0b;
        color: #ffffff;
    }
    .n2c-btn-keypad {
        background: #2b2b40;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .n2c-btn-keypad:hover, .n2c-btn-mute:hover {
        background: #363654;
    }

    /* Background WebRTC Iframe Container */
    .n2c-iframe-container {
        position: relative;
        width: 100%;
        background: #151521;
        overflow: hidden;
    }
    /* Invisible mode: audio/WebRTC runs 100% active without throttling */
    .n2c-iframe-container.is-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        opacity: 0.001 !important;
        pointer-events: none !important;
        bottom: 0 !important;
        left: 0 !important;
    }
    /* Visible mode: shows full Next2Call interface when Keypad is toggled */
    .n2c-iframe-container.is-visible {
        height: 480px;
        display: block;
    }
    .n2c-softphone-iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
        background: #ffffff;
    }

    @media (max-width: 575.98px) {
        .n2c-softphone-box {
            right: 10px;
            bottom: 10px;
            width: calc(100vw - 20px);
        }
    }
</style>

<!-- Twilio-Style Next2Call Softphone Floating Widget Box -->
<div id="ringfySoftphoneWidget" class="n2c-softphone-box" aria-live="polite"
     data-dialer-url="{{ $n2cDialerUrl }}"
     data-ctc-base="{{ $n2cCtcBaseUrl }}"
     data-external-ctc="{{ $n2cExternalCtcUrl }}">
    
    <!-- Drag Header -->
    <div id="ringfySoftphoneHandle" class="n2c-softphone-header">
        <div class="n2c-title-wrap">
            <i class="fa fa-phone text-success fs-7"></i>
            <span class="n2c-title-text">Next2Call Voice</span>
            <span class="n2c-status-badge" id="n2cStatusBadge">
                <i class="fa fa-circle text-success me-1" style="font-size: 6px;"></i> Ready
            </span>
        </div>
        <div class="n2c-header-actions">
            <button type="button" class="n2c-header-btn" id="n2cToggleKeypadBtn" title="Toggle Next2Call Keypad">
                <i class="fa fa-th"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cExternalBtn" title="Open in New Tab">
                <i class="fa fa-external-link-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cCloseBtn" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Active Calling Card (Twilio Sleek Style) -->
    <div id="n2cCallingCard" class="n2c-active-card">
        <div class="n2c-avatar-ring" id="n2cAvatarRing">
            <i class="fa fa-phone" id="n2cAvatarIcon"></i>
        </div>
        <h4 class="n2c-customer-name" id="n2cCustomerName">Customer</h4>
        <div class="n2c-masked-number" id="n2cMaskedNumber">+91 **********</div>

        <div class="n2c-timer-badge">
            <span class="n2c-timer-dot"></span>
            <span id="n2cCallTimerText">00:00</span>
        </div>

        <div id="n2cCallNotice" class="n2c-notice-box" style="display: none;"></div>

        <!-- In-Call Actions (Mute, Hangup, Keypad) -->
        <div class="n2c-action-bar">
            <button type="button" class="n2c-call-btn n2c-btn-mute" id="n2cMuteBtn" title="Mute Microphone">
                <i class="fa fa-microphone"></i>
            </button>
            <button type="button" class="n2c-call-btn n2c-btn-hangup" id="n2cHangupBtn" title="Hangup / End Call">
                <i class="fa fa-phone-slash"></i>
            </button>
            <button type="button" class="n2c-call-btn n2c-btn-keypad" id="n2cKeypadActionBtn" title="Show Keypad">
                <i class="fa fa-th"></i>
            </button>
        </div>
    </div>

    <!-- Background WebRTC Iframe Container (Hidden during calling card, expandable on keypad toggle) -->
    <div id="n2cIframeContainer" class="n2c-iframe-container is-hidden">
        <iframe
            id="ringfySoftphoneFrame"
            class="n2c-softphone-iframe"
            src=""
            allow="microphone; camera; display-capture; autoplay; fullscreen"
            allowfullscreen>
        </iframe>
    </div>
</div>

<script>
    function initRingfySoftphoneWidget() {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const handle = document.getElementById('ringfySoftphoneHandle');
        const frame = document.getElementById('ringfySoftphoneFrame');
        const iframeWrap = document.getElementById('n2cIframeContainer');
        const callingCard = document.getElementById('n2cCallingCard');

        const statusBadge = document.getElementById('n2cStatusBadge');
        const customerNameEl = document.getElementById('n2cCustomerName');
        const maskedNumberEl = document.getElementById('n2cMaskedNumber');
        const timerTextEl = document.getElementById('n2cCallTimerText');
        const avatarRing = document.getElementById('n2cAvatarRing');

        const closeBtn = document.getElementById('n2cCloseBtn');
        const hangupBtn = document.getElementById('n2cHangupBtn');
        const toggleKeypadBtn = document.getElementById('n2cToggleKeypadBtn');
        const keypadActionBtn = document.getElementById('n2cKeypadActionBtn');
        const externalBtn = document.getElementById('n2cExternalBtn');
        const muteBtn = document.getElementById('n2cMuteBtn');

        const DIALER_URL = widget?.dataset?.dialerUrl || '';
        const CTC_BASE = widget?.dataset?.ctcBase || '';

        let timerInterval = null;
        let callSeconds = 0;
        let isMuted = false;
        let currentFullNumber = '';
        let callInitiatedAt = 0;
        let isCallActive = false;
        let activeCallTimerSafety = null;

        const noticeBox = document.getElementById('n2cCallNotice');

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

        // Number Masking Helper: Shows first few digits with country code, masks remaining with *****
        function maskNumber(raw) {
            if (!raw) return '*****';
            const digits = String(raw).trim().replace(/\D/g, '');
            if (digits.startsWith('91') && digits.length >= 12) {
                // e.g. 919610092299 -> +91 96100*****
                return '+91 ' + digits.substring(2, 7) + '*****';
            } else if (digits.length === 10) {
                // e.g. 9610092299 -> +91 96100*****
                return '+91 ' + digits.substring(0, 5) + '*****';
            } else if (digits.startsWith('44') && digits.length >= 10) {
                // e.g. UK +44 7123*****
                return '+44 ' + digits.substring(2, 6) + '*****';
            } else if (digits.length > 6) {
                return '+' + digits.substring(0, digits.length - 5) + '*****';
            }
            return digits.substring(0, 3) + '*****';
        }

        // Call Duration Timer
        function startCallTimer() {
            stopCallTimer();
            callSeconds = 0;
            if (timerTextEl) timerTextEl.textContent = '00:00';
            timerInterval = setInterval(function () {
                callSeconds++;
                const mins = String(Math.floor(callSeconds / 60)).padStart(2, '0');
                const secs = String(callSeconds % 60).padStart(2, '0');
                if (timerTextEl) timerTextEl.textContent = `${mins}:${secs}`;
            }, 1000);
        }

        function stopCallTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        function mountIframe(url) {
            if (!iframeWrap) return;
            iframeWrap.innerHTML = `
                <iframe
                    id="ringfySoftphoneFrame"
                    class="n2c-softphone-iframe"
                    src="${url}"
                    allow="microphone; camera; display-capture; autoplay; fullscreen"
                    allowfullscreen>
                </iframe>
            `;
        }

        function destroyAndResetIframe() {
            if (!iframeWrap) return;
            try {
                const currentFrame = document.getElementById('ringfySoftphoneFrame');
                if (currentFrame && currentFrame.contentWindow) {
                    // 1. Directly invoke Asterisk SIP cancel/bye inside iframe (works for local embed)
                    try {
                        if (typeof currentFrame.contentWindow.cancelSession === 'function') {
                            currentFrame.contentWindow.cancelSession(1);
                        }
                        if (typeof currentFrame.contentWindow.endSession === 'function') {
                            currentFrame.contentWindow.endSession(1);
                        }
                        if (typeof currentFrame.contentWindow.teardownSession === 'function' && typeof currentFrame.contentWindow.FindLineByNumber === 'function') {
                            var line = currentFrame.contentWindow.FindLineByNumber(1);
                            if (line) currentFrame.contentWindow.teardownSession(line);
                        }
                    } catch (e) {}

                    // 2. Post hangup signals to iframe
                    currentFrame.contentWindow.postMessage({ type: 'HANGUP', action: 'hangup' }, '*');
                    currentFrame.contentWindow.postMessage('HANGUP', '*');
                    currentFrame.contentWindow.postMessage({ type: 'CLOSE_PHONE_POPUP' }, '*');
                }
            } catch (e) {}

            // 3. Allow 300ms for SIP BYE / CANCEL packets to reach Asterisk PBX before destroying DOM element
            setTimeout(function () {
                if (iframeWrap && (!widget || !widget.classList.contains('is-open'))) {
                    iframeWrap.innerHTML = '';
                }
            }, 350);
        }

        // Close & Reset Widget (Instant Disconnect)
        function closeSoftphoneWidget() {
            stopCallTimer();
            if (activeCallTimerSafety) {
                clearTimeout(activeCallTimerSafety);
                activeCallTimerSafety = null;
            }
            isCallActive = false;
            callInitiatedAt = 0;

            if (widget) {
                widget.classList.remove('is-open');
                widget.style.display = 'none';
            }

            // Reset view state
            if (callingCard) callingCard.style.display = 'flex';
            if (iframeWrap) {
                iframeWrap.classList.remove('is-visible');
                iframeWrap.classList.add('is-hidden');
            }

            // Immediately signal hangup and cleanly reset iframe
            destroyAndResetIframe();

            if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-circle text-muted me-1" style="font-size: 6px;"></i> Ready';
            if (avatarRing) {
                avatarRing.style.animation = 'none';
                avatarRing.style.background = '';
                avatarRing.style.borderColor = '';
                avatarRing.style.color = '';
            }
            if (noticeBox) {
                noticeBox.style.display = 'none';
                noticeBox.innerHTML = '';
            }
        }

        // Hangup Button Click (Instant Call Disconnect by Agent)
        hangupBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (statusBadge) statusBadge.innerHTML = '<span class="text-danger fw-bold"><i class="fa fa-phone-slash me-1"></i> Disconnecting...</span>';
            // Trigger call termination immediately
            destroyAndResetIframe();
            setTimeout(function () {
                closeSoftphoneWidget();
            }, 250);
        });

        closeBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeSoftphoneWidget();
        });

        // Toggle Keypad View
        function toggleKeypad() {
            if (!iframeWrap) return;
            const isVisible = iframeWrap.classList.contains('is-visible');
            if (isVisible) {
                iframeWrap.classList.remove('is-visible');
                iframeWrap.classList.add('is-hidden');
                if (callingCard) callingCard.style.display = 'flex';
            } else {
                iframeWrap.classList.remove('is-hidden');
                iframeWrap.classList.add('is-visible');
                // Ensure frame exists with dialer if empty
                const currentFrame = document.getElementById('ringfySoftphoneFrame');
                if (!currentFrame || !currentFrame.src || currentFrame.src === 'about:blank') {
                    mountIframe(DIALER_URL);
                }
            }
        }

        toggleKeypadBtn?.addEventListener('click', toggleKeypad);
        keypadActionBtn?.addEventListener('click', toggleKeypad);

        // Popout External Button
        externalBtn?.addEventListener('click', function () {
            const currentFrame = document.getElementById('ringfySoftphoneFrame');
            const currentSrc = (currentFrame && currentFrame.src && currentFrame.src !== 'about:blank') ? currentFrame.src : DIALER_URL;
            if (currentSrc) {
                window.open(currentSrc, 'Next2CallSoftphone', 'width=380,height=600,menubar=no,toolbar=no,location=no');
            }
        });

        // Mute Toggle Button
        muteBtn?.addEventListener('click', function () {
            isMuted = !isMuted;
            muteBtn.classList.toggle('is-muted', isMuted);
            muteBtn.innerHTML = isMuted ? '<i class="fa fa-microphone-slash"></i>' : '<i class="fa fa-microphone"></i>';
            if (statusBadge) statusBadge.textContent = isMuted ? 'Muted' : 'Connected';
        });

        // Listen for postMessage from Softphone PBX (both local embed & next2call domains)
        window.addEventListener('message', function (event) {
            const originOk = event.origin.includes('next2call.com') ||
                             event.origin === window.location.origin ||
                             window.location.origin.includes('localhost') ||
                             window.location.origin.includes('127.0.0.1');
            if (!originOk) return;

            console.log('[Softphone postMessage]:', event.data);

            let data = event.data;
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {}
            }

            const isRegistrationFailed = data === 'SIP_REGISTRATION_FAILED' ||
                                         data?.type === 'SIP_REGISTRATION_FAILED' ||
                                         data?.type === 'REGISTRATION_FAILED';

            const isRegistered = data === 'SIP_REGISTERED' ||
                                 data?.type === 'SIP_REGISTERED';

            const isHangup = data === 'CALL_TERMINATED' ||
                             data?.type === 'CALL_TERMINATED' ||
                             data === 'CLOSE_PHONE_POPUP' ||
                             data?.type === 'CLOSE_PHONE_POPUP' ||
                             data === 'CALL_HANGUP' ||
                             data?.type === 'CALL_HANGUP' ||
                             data?.type === 'CALL_DISCONNECTED' ||
                             data === 'CALL_DISCONNECTED';

            const isConnected = data === 'CALL_ACCEPTED' ||
                                data?.type === 'CALL_ACCEPTED' ||
                                data?.type === 'CALL_CONNECTED' ||
                                data?.type === 'CONNECTED';

            const isIncoming = data === 'INCOMING_CALL' ||
                               data?.type === 'INCOMING_CALL' ||
                               data?.event === 'incoming_call';

            if (isRegistrationFailed) {
                console.warn('[Softphone] SIP Registration failed (403 Forbidden).');
                stopCallTimer();
                if (statusBadge) {
                    statusBadge.innerHTML = '<span class="text-danger fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> Registration Failed (403)</span>';
                }
                if (timerTextEl) {
                    timerTextEl.innerHTML = '<span class="text-danger">Forbidden (403)</span>';
                }
                if (avatarRing) {
                    avatarRing.style.animation = 'none';
                    avatarRing.style.background = 'rgba(241, 65, 108, 0.15)';
                    avatarRing.style.borderColor = '#f1416c';
                    avatarRing.style.color = '#f1416c';
                }
                if (noticeBox) {
                    noticeBox.className = 'n2c-notice-box n2c-notice-danger';
                    noticeBox.innerHTML = `
                        <strong>Next2Call PBX: 403 Forbidden</strong><br>
                        <span style="font-size: 11px; color: #ffccd5; display: block; margin: 4px 0 8px 0; text-align: left; line-height: 1.4;">
                            • IP <b>103.216.80.217</b> allow karein: <a href="http://ipallow.next2call.com/" target="_blank" style="color: #60a5fa; text-decoration: underline;">ipallow.next2call.com</a> (Key: <code>CLT-5009FAA4F58D</code>)<br>
                            • Dusre tab me open Next2Call / 10101 dialer ko band karein.<br>
                            • UAT Domain <b>uat-ain.londonstreetstore.com</b> Next2Call PBX pe whitelist karwayein.
                        </span>
                        <button type="button" class="btn btn-sm btn-light py-1 px-3 fs-8 text-dark fw-bold mt-1" id="n2cDirectPopupBtn" style="border-radius: 20px;">
                            <i class="fa fa-external-link-alt text-primary me-1"></i> Open Directly in Popup
                        </button>
                    `;
                    noticeBox.style.display = 'block';

                    document.getElementById('n2cDirectPopupBtn')?.addEventListener('click', function () {
                        const directUrl = (widget?.dataset?.externalCtc || widget?.dataset?.ctcBase || '') + '&d=' + encodeURIComponent(currentFullNumber);
                        window.open(directUrl, 'Next2CallSoftphone', 'width=380,height=620,menubar=no,toolbar=no,location=no');
                    });
                }
                return;
            }

            if (isRegistered) {
                if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-phone text-success me-1"></i> Ringing...';
                return;
            }

            if (isHangup) {
                console.log('[Softphone] Remote hangup / PBX terminate received, closing widget smoothly...');
                stopCallTimer();
                if (statusBadge) statusBadge.innerHTML = '<span class="text-muted"><i class="fa fa-phone-slash me-1"></i> Call Ended</span>';
                if (avatarRing) {
                    avatarRing.style.animation = 'none';
                    avatarRing.style.borderColor = '#6c757d';
                }
                setTimeout(function () {
                    closeSoftphoneWidget();
                }, 500);
            } else if (isConnected) {
                isCallActive = true;
                if (statusBadge) statusBadge.textContent = 'Connected';
                startCallTimer();
            } else if (isIncoming) {
                const caller = data?.caller || data?.from || 'Incoming';
                if (widget) {
                    widget.style.display = 'flex';
                    widget.classList.add('is-open');
                }
                if (customerNameEl) customerNameEl.textContent = 'Incoming Call';
                if (maskedNumberEl) maskedNumberEl.textContent = maskNumber(caller);
                if (statusBadge) statusBadge.textContent = 'Ringing...';
                if (avatarRing) avatarRing.style.animation = 'n2cPulseRing 1.4s infinite';
                startCallTimer();
            }
        });

        // Global Direct Dial Function with Country Code & Number Masking
        window.dialNext2CallNumber = function (rawNumber, countryCode = '', contactName = 'Customer') {
            let num = String(rawNumber || '').trim().replace(/[^0-9]/g, '');
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
            isCallActive = false;

            if (activeCallTimerSafety) {
                clearTimeout(activeCallTimerSafety);
            }
            activeCallTimerSafety = setTimeout(function () {
                if (widget && widget.classList.contains('is-open')) {
                    isCallActive = true;
                }
            }, 4500);

            const targetUrl = CTC_BASE + '&d=' + encodeURIComponent(num);

            // Open Widget (Only during call!)
            if (widget) {
                widget.style.display = 'flex';
                widget.classList.add('is-open');
            }

            // Ensure Calling Card is visible, iframe stays running in background
            if (callingCard) callingCard.style.display = 'flex';
            if (iframeWrap) {
                iframeWrap.classList.remove('is-visible');
                iframeWrap.classList.add('is-hidden');
            }

            // Update UI with Contact Name & Masked Number
            if (customerNameEl) customerNameEl.textContent = contactName || 'Customer';
            if (maskedNumberEl) maskedNumberEl.textContent = maskNumber(num);
            if (statusBadge) statusBadge.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Calling...';
            if (avatarRing) {
                avatarRing.style.background = '';
                avatarRing.style.borderColor = '';
                avatarRing.style.color = '';
                avatarRing.style.animation = 'n2cPulseRing 1.4s infinite';
            }
            if (noticeBox) {
                noticeBox.style.display = 'none';
                noticeBox.innerHTML = '';
            }

            // Start timer immediately
            startCallTimer();

            // Mount and load iframe with direct click-to-dial URL
            mountIframe(targetUrl);
        };

        window.dialNumber = function (mobile, countryCode = '', contactName = 'Customer') {
            window.dialNext2CallNumber(mobile, countryCode, contactName);
        };

        window.openRingfySoftphone = async function (orderId, countryCode = '', mobile = '', contactName = 'Customer') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const cleanCode = String(countryCode || '').trim();
            const cleanMobile = String(mobile || '').trim();

            if (!cleanMobile) {
                Swal?.fire({
                    icon: 'warning',
                    title: 'Number Missing',
                    text: 'Mobile number is required to make a call.',
                });
                return;
            }

            // Fallback directly to client-side built click-to-dial with masking
            dialNext2CallNumber(cleanMobile, cleanCode, contactName);

            try {
                const response = await fetch('{{ route('softphone.call-url') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        order_id: orderId || null,
                        country_code: cleanCode,
                        mobile: cleanMobile,
                    }),
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    if (data.customer_name && customerNameEl && (!contactName || contactName === 'Customer')) {
                        customerNameEl.textContent = data.customer_name;
                    }
                    if (data.target_number && maskedNumberEl) {
                        maskedNumberEl.textContent = maskNumber(data.target_number);
                    }
                    if (data.url) {
                        const currentFrame = document.getElementById('ringfySoftphoneFrame');
                        if (currentFrame && currentFrame.src !== data.url) {
                            currentFrame.src = data.url;
                        }
                    }
                }
            } catch (err) {
                console.warn('[Next2Call Softphone] Server endpoint fallback:', err);
            }
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRingfySoftphoneWidget);
    } else {
        initRingfySoftphoneWidget();
    }
</script>
@endonce
