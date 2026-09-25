@once
@php
    $n2cPlugin = \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    $n2cIsActive = (bool) ($n2cPlugin?->is_active ?? true);
    if (!auth()->check() || !$n2cIsActive) {
        return;
    }
    $n2cSettings = $n2cPlugin?->settings ?? [];

    $userId = !empty($n2cSettings['user_id']) ? $n2cSettings['user_id'] : config('services.softphone.user_id', '10101');
    $password = !empty($n2cSettings['password']) ? $n2cSettings['password'] : config('services.softphone.password', 'T2d8d1r5P6x0T8O8iUq');
    $sipDomain = !empty($n2cSettings['sip_domain']) ? $n2cSettings['sip_domain'] : config('services.softphone.sip_domain', 'ringfy.next2call.com');
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

    $n2cCtcBaseUrl = "https://{$sipDomain}{$clickToDialPath}?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);
@endphp

<style>
    /* Next2Call Floating Launcher Button (Hidden per user request: only opens during incoming or outgoing calls) */
    .ringfy-softphone-launcher {
        display: none !important;
    }

    /* Next2Call Main Popup Widget */
    .ringfy-softphone-widget {
        display: flex;
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 380px;
        height: 590px;
        max-width: calc(100vw - 20px);
        max-height: calc(100vh - 35px);
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.3), 0 0 0 1px rgba(15, 23, 42, 0.08);
        z-index: 9999;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(20px) scale(0.96);
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease, height 0.25s ease, width 0.25s ease;
    }
    .ringfy-softphone-widget.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }

    /* Minimized state */
    .ringfy-softphone-widget.is-minimized {
        height: 52px !important;
        width: 320px !important;
        overflow: hidden;
    }
    .ringfy-softphone-widget.is-minimized .ringfy-softphone-content {
        display: none !important;
    }

    /* Maximized state */
    .ringfy-softphone-widget.is-maximized {
        width: 480px !important;
        height: calc(100vh - 50px) !important;
        max-height: 740px !important;
    }

    /* Header */
    .ringfy-softphone-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: move;
        user-select: none;
        flex-shrink: 0;
    }
    .ringfy-softphone-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 14px;
        color: #ffffff;
    }
    .ringfy-softphone-title i {
        font-size: 14px;
    }
    .ringfy-softphone-ext {
        background: rgba(255, 255, 255, 0.2);
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 6px;
        font-weight: 600;
    }
    .ringfy-softphone-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .ringfy-softphone-icon-btn {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 0;
        border-radius: 6px;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.15s;
    }
    .ringfy-softphone-icon-btn:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #ffffff;
    }

    /* Content Area */
    .ringfy-softphone-content {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        background: #f8fafc;
    }

    /* Top Quick Dial / Status bar */
    .ringfy-quick-bar {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .ringfy-quick-input {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }
    .ringfy-quick-input:focus {
        border-color: #10b981;
    }
    .ringfy-quick-call-btn {
        background: #10b981;
        border: none;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .ringfy-quick-call-btn:hover {
        background: #059669;
    }

    /* Active Call Info Banner */
    .ringfy-active-call-banner {
        display: none;
        background: #ecfdf5;
        border-bottom: 1px solid #a7f3d0;
        color: #065f46;
        padding: 6px 12px;
        font-size: 12px;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .ringfy-active-call-banner.is-active {
        display: flex;
    }
    .ringfy-pulse-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        animation: ringfyPulse 1.4s infinite;
        margin-right: 6px;
    }
    @keyframes ringfyPulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Iframe Wrapper */
    .ringfy-iframe-wrap {
        position: relative;
        flex: 1;
        width: 100%;
        min-height: 0;
        background: #ffffff;
    }
    .ringfy-softphone-frame {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
        background: #ffffff;
    }

    /* Loading overlay */
    .ringfy-frame-loader {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: #ffffff;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        color: #64748b;
        font-size: 13px;
        z-index: 10;
    }
    .ringfy-frame-loader.is-loading {
        display: flex;
    }

    @media (max-width: 575.98px) {
        .ringfy-softphone-widget {
            right: 10px;
            bottom: 10px;
            width: calc(100vw - 20px);
            height: 520px;
        }
        .ringfy-softphone-launcher {
            right: 16px;
            bottom: 16px;
            width: 52px;
            height: 52px;
            font-size: 20px;
        }
    }
</style>

<!-- Floating Launcher Button (Hidden per user request: only opens during incoming or outgoing calls) -->
<button type="button" id="ringfySoftphoneLauncher" class="ringfy-softphone-launcher" style="display: none !important;" title="Open Next2Call Softphone" aria-label="Open Softphone">
    <i class="fa fa-phone"></i>
    <span class="launcher-badge">{{ $userId }}</span>
</button>

<!-- Main Next2Call Softphone Popup Widget -->
<div id="ringfySoftphoneWidget" class="ringfy-softphone-widget" aria-live="polite"
     data-dialer-url="{{ $n2cDialerUrl }}"
     data-ctc-base="{{ $n2cCtcBaseUrl }}">
    
    <!-- Drag Header -->
    <div id="ringfySoftphoneHandle" class="ringfy-softphone-header">
        <div class="ringfy-softphone-title">
            <i class="fa fa-headphones"></i>
            <span>Next2Call Softphone</span>
            <span class="ringfy-softphone-ext">Ext: {{ $userId }}</span>
        </div>
        <div class="ringfy-softphone-actions">
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneKeypadBtn" title="Full Webphone Keypad">
                <i class="fa fa-th"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneExternal" title="Open in New Tab">
                <i class="fa fa-external-link-alt"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneMinimize" title="Minimize">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneMaximize" title="Maximize">
                <i class="fa fa-expand"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneClose" title="Close Dialer">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Softphone Body Content -->
    <div class="ringfy-softphone-content">
        <!-- Quick Dial Bar -->
        <div class="ringfy-quick-bar">
            <input type="tel" id="ringfyQuickDialInput" class="ringfy-quick-input" placeholder="Type number & press Enter...">
            <button type="button" id="ringfyQuickDialBtn" class="ringfy-quick-call-btn">
                <i class="fa fa-phone"></i> Call
            </button>
        </div>

        <!-- Active Call Status Bar -->
        <div id="ringfyActiveCallBanner" class="ringfy-active-call-banner">
            <div>
                <span class="ringfy-pulse-dot"></span>
                <span id="ringfyActiveCallText">Ready</span>
            </div>
            <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-bold" id="ringfyEndCallBtn">
                Reset
            </button>
        </div>

        <!-- Iframe Container -->
        <div class="ringfy-iframe-wrap">
            <div id="ringfyFrameLoader" class="ringfy-frame-loader">
                <div class="spinner-border text-success" role="status" style="width: 2rem; height: 2rem;"></div>
                <span>Loading Next2Call Softphone...</span>
            </div>
            <iframe
                id="ringfySoftphoneFrame"
                class="ringfy-softphone-frame"
                src=""
                allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>

<script>
    function initRingfySoftphoneWidget() {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const handle = document.getElementById('ringfySoftphoneHandle');
        const launcher = document.getElementById('ringfySoftphoneLauncher');
        const frame = document.getElementById('ringfySoftphoneFrame');
        const loader = document.getElementById('ringfyFrameLoader');
        const closeBtn = document.getElementById('ringfySoftphoneClose');
        const minimizeBtn = document.getElementById('ringfySoftphoneMinimize');
        const maximizeBtn = document.getElementById('ringfySoftphoneMaximize');
        const keypadBtn = document.getElementById('ringfySoftphoneKeypadBtn');
        const externalBtn = document.getElementById('ringfySoftphoneExternal');
        const quickInput = document.getElementById('ringfyQuickDialInput');
        const quickBtn = document.getElementById('ringfyQuickDialBtn');
        const activeBanner = document.getElementById('ringfyActiveCallBanner');
        const activeText = document.getElementById('ringfyActiveCallText');
        const endCallBtn = document.getElementById('ringfyEndCallBtn');

        const DIALER_URL = widget?.dataset?.dialerUrl || '';
        const CTC_BASE = widget?.dataset?.ctcBase || '';



        // Restore saved position
        const savedPos = localStorage.getItem('ringfy_softphone_pos');
        if (savedPos) {
            try {
                const pos = JSON.parse(savedPos);
                applyWidgetPosition(pos.left, pos.top);
            } catch (e) {
                localStorage.removeItem('ringfy_softphone_pos');
            }
        }

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

        function onDragStart(e) {
            if (e.target.closest('button') || widget.classList.contains('is-maximized')) return;
            isDragging = true;
            const rect = widget.getBoundingClientRect();
            const clientX = e.clientX || e.touches?.[0]?.clientX;
            const clientY = e.clientY || e.touches?.[0]?.clientY;
            startX = clientX - rect.left;
            startY = clientY - rect.top;
            document.body.style.userSelect = 'none';
        }

        function onDragMove(e) {
            if (!isDragging) return;
            const clientX = e.clientX || e.touches?.[0]?.clientX;
            const clientY = e.clientY || e.touches?.[0]?.clientY;
            applyWidgetPosition(clientX - startX, clientY - startY);
        }

        function onDragEnd() {
            if (!isDragging) return;
            isDragging = false;
            document.body.style.userSelect = '';
            const rect = widget.getBoundingClientRect();
            localStorage.setItem('ringfy_softphone_pos', JSON.stringify({ left: rect.left, top: rect.top }));
        }

        handle?.addEventListener('mousedown', onDragStart);
        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('mouseup', onDragEnd);

        handle?.addEventListener('touchstart', onDragStart, { passive: true });
        document.addEventListener('touchmove', onDragMove, { passive: true });
        document.addEventListener('touchend', onDragEnd);

        // Toggle Launcher
        launcher?.addEventListener('click', function () {
            if (widget.classList.contains('is-open')) {
                if (widget.classList.contains('is-minimized')) {
                    widget.classList.remove('is-minimized');
                } else {
                    closeSoftphoneWidget();
                }
            } else {
                openDialerInWidget();
            }
        });

        // Keypad button -> switch to full Webphone interface
        keypadBtn?.addEventListener('click', function () {
            openDialerInWidget();
        });

        // External new tab button
        externalBtn?.addEventListener('click', function () {
            const currentSrc = frame?.src || DIALER_URL;
            if (currentSrc) {
                window.open(currentSrc, '_blank');
            }
        });

        // Minimize
        minimizeBtn?.addEventListener('click', function () {
            widget.classList.toggle('is-minimized');
            minimizeBtn.innerHTML = widget.classList.contains('is-minimized')
                ? '<i class="fa fa-window-restore"></i>'
                : '<i class="fa fa-minus"></i>';
        });

        // Maximize
        maximizeBtn?.addEventListener('click', function () {
            widget.classList.toggle('is-maximized');
            maximizeBtn.innerHTML = widget.classList.contains('is-maximized')
                ? '<i class="fa fa-compress"></i>'
                : '<i class="fa fa-expand"></i>';
        });

        // Close
        closeBtn?.addEventListener('click', function () {
            closeSoftphoneWidget();
        });

        // Quick Call Form
        function handleQuickCall() {
            const raw = quickInput?.value?.trim();
            if (!raw) {
                quickInput?.focus();
                return;
            }
            dialNext2CallNumber(raw);
        }

        quickBtn?.addEventListener('click', handleQuickCall);
        quickInput?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleQuickCall();
            }
        });

        endCallBtn?.addEventListener('click', function () {
            openDialerInWidget();
        });

        // Iframe load listener
        frame?.addEventListener('load', function () {
            loader?.classList.remove('is-loading');
        });

        // Realistic Dual-Tone Multi-Frequency (DTMF / Ringback) Audio Tone Generator
        let ringAudioCtx = null;
        let ringInterval = null;

        function playRingTone() {
            stopRingTone();
            try {
                const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtxClass) return;
                ringAudioCtx = new AudioCtxClass();

                function ringBurst() {
                    if (!ringAudioCtx || ringAudioCtx.state === 'closed') return;
                    if (ringAudioCtx.state === 'suspended') {
                        ringAudioCtx.resume();
                    }
                    const now = ringAudioCtx.currentTime;
                    const osc1 = ringAudioCtx.createOscillator();
                    const osc2 = ringAudioCtx.createOscillator();
                    const gain = ringAudioCtx.createGain();

                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(440, now);
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(480, now);

                    gain.gain.setValueAtTime(0, now);
                    gain.gain.linearRampToValueAtTime(0.12, now + 0.05);
                    gain.gain.setValueAtTime(0.12, now + 1.6);
                    gain.gain.linearRampToValueAtTime(0, now + 1.8);

                    osc1.connect(gain);
                    osc2.connect(gain);
                    gain.connect(ringAudioCtx.destination);

                    osc1.start(now);
                    osc2.start(now);
                    osc1.stop(now + 1.8);
                    osc2.stop(now + 1.8);
                }

                ringBurst();
                ringInterval = setInterval(ringBurst, 3500);

                setTimeout(function () {
                    stopRingTone();
                }, 30000);
            } catch (err) {
                console.warn('[Softphone] Ring audio notice:', err);
            }
        }

        function stopRingTone() {
            if (ringInterval) {
                clearInterval(ringInterval);
                ringInterval = null;
            }
            if (ringAudioCtx) {
                try { ringAudioCtx.close(); } catch (e) {}
                ringAudioCtx = null;
            }
        }

        // Listen for postMessage from Next2Call Ringfy PBX
        window.addEventListener('message', function (event) {
            // Only accept explicit simulation event from our own origin
            if (event.origin === window.location.origin) {
                if (event.data && event.data.type === 'SIMULATE_NEXT2CALL_INBOUND') {
                    const caller = event.data.caller || '08800826129';
                    window.simulateNext2CallInbound && window.simulateNext2CallInbound(caller);
                }
                return;
            }

            // Otherwise, only accept messages from Next2Call PBX domain
            if (event.origin !== 'https://ringfy.next2call.com') return;

            console.log('[Next2Call postMessage]:', event.data);

            const isIncoming = event.data === 'INCOMING_CALL' ||
                               event.data?.type === 'INCOMING_CALL' ||
                               event.data?.event === 'incoming_call' ||
                               (typeof event.data === 'string' && event.data.trim() === 'INCOMING_CALL');

            const isHangup = event.data === 'CALL_HANGUP' ||
                             event.data?.type === 'CALL_HANGUP' ||
                             event.data?.type === 'CLOSE_PHONE_POPUP' ||
                             event.data?.type === 'hangup' ||
                             event.data?.event === 'hangup' ||
                             event.data?.type === 'CALL_DISCONNECTED' ||
                             event.data === 'CALL_DISCONNECTED';

            if (isIncoming) {
                // Incoming call received -> immediately open softphone popup on screen
                widget.classList.add('is-open');
                widget.classList.remove('is-minimized');
                activeBanner?.classList.add('is-active');
                const caller = event.data?.caller || event.data?.from || '';
                activeText.textContent = caller ? 'Incoming Call: ' + caller : 'Incoming Call...';
                playRingTone();
            } else if (isHangup) {
                console.log("Call disconnected");
                stopRingTone();
                activeBanner?.classList.remove('is-active');
                activeText.textContent = 'Call Disconnected';
                closeSoftphoneWidget();
            }
        });

        function closeSoftphoneWidget() {
            stopRingTone();
            widget.classList.remove('is-open');
            widget.classList.remove('is-minimized');
            activeBanner?.classList.remove('is-active');
            loader?.classList.remove('is-loading');
            if (frame) {
                frame.removeAttribute('src');
                frame.src = '';
            }
        }

        function openDialerInWidget() {
            widget.classList.add('is-open');
            widget.classList.remove('is-minimized');
            activeBanner?.classList.remove('is-active');
            loader?.classList.add('is-loading');

            if (frame && frame.src !== DIALER_URL) {
                frame.src = DIALER_URL;
            } else {
                loader?.classList.remove('is-loading');
            }
        }

        // Expose globally
        window.openRingfyDialer = function (number = '') {
            stopRingTone();
            if (number && quickInput) {
                quickInput.value = number;
            }
            openDialerInWidget();
        };

        window.simulateNext2CallInbound = function (callerNumber = '08800826129') {
            widget.classList.add('is-open');
            widget.classList.remove('is-minimized');
            activeBanner?.classList.add('is-active');
            activeText.textContent = 'Incoming Call: ' + callerNumber;
            if (quickInput) quickInput.value = callerNumber;
            playRingTone();
            openDialerInWidget();
        };

        window.closeRingfySoftphone = function () {
            stopRingTone();
            closeSoftphoneWidget();
        };

        window.dialNext2CallNumber = function (rawNumber) {
            let num = String(rawNumber || '').trim().replace(/[^0-9]/g, '');
            if (!num) return;

            // Normalize for Next2Call SIP Trunk
            // 10-digit Indian: add 0 -> 08800826129
            // 12-digit Indian (91...): -> 0 + 10 digits
            if (num.startsWith('91') && num.length === 12) {
                num = '0' + num.substring(2);
            } else if (num.length === 10) {
                num = '0' + num;
            } else if (num.startsWith('440')) {
                // UK Number: strip extra leading 0 after 44
                num = '44' + num.substring(3);
            }

            const targetUrl = CTC_BASE + '&d=' + encodeURIComponent(num);

            widget.classList.add('is-open');
            widget.classList.remove('is-minimized');
            activeText.textContent = 'Calling: ' + num;
            activeBanner?.classList.add('is-active');
            loader?.classList.add('is-loading');

            if (quickInput) quickInput.value = num;
            if (frame) frame.src = targetUrl;
        };

        window.dialNumber = function (mobile) {
            window.dialNext2CallNumber(mobile);
        };

        window.openRingfySoftphone = async function (orderId, countryCode = '', mobile = '') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const cleanCode = String(countryCode || '').trim();
            const cleanMobile = String(mobile || '').trim();

            console.log('[Next2Call Softphone] Direct Call Request:', { orderId, countryCode: cleanCode, mobile: cleanMobile });

            if (!cleanMobile) {
                Swal?.fire({
                    icon: 'warning',
                    title: 'Number Missing',
                    text: 'Mobile number is required to make a call.',
                });
                return;
            }

            // Immediately open widget so user sees responsiveness
            widget.classList.add('is-open');
            widget.classList.remove('is-minimized');
            loader?.classList.add('is-loading');
            activeText.textContent = 'Connecting: ' + (cleanCode ? '+' + cleanCode + ' ' : '') + cleanMobile;
            activeBanner?.classList.add('is-active');
            if (quickInput) quickInput.value = cleanMobile;

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
                console.log('[Next2Call Softphone] Server Response:', data);

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Call request failed.');
                }

                if (frame) {
                    frame.src = data.url;
                }
                activeText.textContent = 'Calling: ' + (data.target_number || cleanMobile);
            } catch (err) {
                console.warn('[Next2Call Softphone] Server endpoint fallback to direct SIP URL:', err);
                // Clean fallback to client-side built click-to-dial URL
                dialNext2CallNumber(cleanMobile);
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
