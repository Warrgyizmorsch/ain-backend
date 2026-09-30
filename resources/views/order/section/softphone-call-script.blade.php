@once
@php
    $n2cPlugin = \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    $n2cIsActive = (bool) ($n2cPlugin?->is_active ?? true);
    if (!auth()->check() || !$n2cIsActive) {
        return;
    }
    $creds = \App\Http\Controllers\PluginController::resolveNext2CallCredentials();
    $userId = $creds['user_id'];
    $password = $creds['password'];
    $sipDomain = $creds['sip_domain'];
    $clickToDialPath = $creds['click_to_dial_path'];

    $isSuperAdmin = auth()->check() && ((int) auth()->user()->role_id === 1);

    $n2cSession = \App\Http\Controllers\PluginController::getNext2CallSession($userId, $password);
    $n2cDialerUrl = $n2cSession['webphone_url'] ?? ("https://{$sipDomain}/api-section/softphone/Phone/index.html?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]));

    $n2cRawCtc = $n2cSession['click_to_call_url'] ?? '';
    $n2cCtcBaseUrl = !empty($n2cRawCtc)
        ? str_replace('index.html', 'click-to-dial.html', $n2cRawCtc)
        : ("https://{$sipDomain}/softphone/Phone/click-to-dial.html?" . http_build_query([
            'profileName' => $userId,
            'SipDomain'   => $sipDomain,
            'SipUsername' => $userId,
            'SipPassword' => $password,
        ]) . '&d=');
@endphp

<style>
    /* Next2Call Softphone Floating Box */
    .n2c-softphone-box {
        position: fixed;
        right: 25px;
        bottom: 30px;
        width: 350px;
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
        gap: 6px;
    }
    .n2c-header-btn {
        background: rgba(255, 255, 255, 0.08);
        color: #a1a5b7;
        border: 0;
        border-radius: 6px;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        transition: background 0.15s, color 0.15s;
    }
    .n2c-header-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Active Calling Screen (Masked Number Card) */
    .n2c-active-card {
        padding: 26px 20px 22px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #1e1e2d;
    }
    .n2c-avatar-ring {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 14px;
        animation: n2cPulseRing 1.5s infinite;
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
        font-size: 15px;
        font-weight: 700;
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
        margin-bottom: 12px;
    }
    .n2c-timer-dot {
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: n2cPulseRing 1.2s infinite;
    }
    .n2c-call-status-text {
        font-size: 12px;
        color: #a1a5b7;
        margin-bottom: 20px;
        font-weight: 500;
    }

    /* Actions (Hangup, Keypad) */
    .n2c-action-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
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
        cursor: pointer;
        font-size: 16px;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .n2c-call-btn:hover {
        transform: scale(1.08);
    }
    .n2c-btn-hangup {
        width: 56px;
        height: 56px;
        background: #f1416c;
        color: #ffffff;
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(241, 65, 108, 0.45);
    }
    .n2c-btn-hangup:hover {
        background: #d9214e;
    }
    .n2c-btn-keypad {
        background: #2b2b40;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .n2c-btn-keypad:hover {
        background: #363650;
    }

    /* Background WebRTC Iframe Container:
       Kept active in DOM with 0 height & opacity when masked card is active.
       NEVER uses display:none so WebRTC audio & click-to-dial stream smoothly!
    */
    .n2c-iframe-container {
        width: 100%;
        height: 520px;
        background: #ffffff;
        overflow: hidden;
        transition: height 0.25s ease, opacity 0.25s ease;
    }
    .n2c-iframe-container.is-hidden {
        height: 0px !important;
        opacity: 0 !important;
        pointer-events: none !important;
        border: 0 !important;
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

<!-- Next2Call Softphone Floating Widget Box -->
<div id="ringfySoftphoneWidget" class="n2c-softphone-box" aria-live="polite"
     data-dialer-url="{!! $n2cDialerUrl !!}"
     data-ctc-base="{!! $n2cCtcBaseUrl !!}">
    
    <!-- Drag Header -->
    <div id="ringfySoftphoneHandle" class="n2c-softphone-header">
        <div class="n2c-title-wrap">
            <i class="fa fa-phone text-success fs-7"></i>
            <span class="n2c-title-text" id="n2cHeaderTitle">Next2Call Softphone</span>
            <span id="n2cStatusBadge" class="n2c-status-badge">Ready</span>
        </div>
        <div class="n2c-header-actions">
            <button type="button" class="n2c-header-btn" id="n2cToggleKeypadBtn" title="Toggle Keypad / Dialer">
                <i class="fa fa-th"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cRefreshBtn" title="Reconnect / Whitelist IP">
                <i class="fa fa-sync-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cExternalBtn" title="Open in Standalone Popup">
                <i class="fa fa-external-link-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cCloseBtn" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Active Calling Screen (Masked Number Card) -->
    <div id="n2cCallingCard" class="n2c-active-card">
        <div class="n2c-avatar-ring" id="n2cAvatarRing">
            <i class="fa fa-phone" id="n2cAvatarIcon"></i>
        </div>
        <h4 class="n2c-customer-name" id="n2cCustomerName">Customer</h4>
        <div class="n2c-masked-number" id="n2cMaskedNumber">+91 **********</div>

        <div class="n2c-timer-badge">
            <span class="n2c-timer-dot" id="n2cTimerDot"></span>
            <span id="n2cCallTimerText">00:00</span>
        </div>

        <div id="n2cCallStatus" class="n2c-call-status-text">Calling...</div>

        <!-- In-Call Actions (Hangup, Keypad) -->
        <div class="n2c-action-bar">
            <button type="button" class="n2c-call-btn n2c-btn-hangup" id="n2cHangupBtn" title="End Call">
                <i class="fa fa-phone-slash"></i>
            </button>
            <button type="button" class="n2c-call-btn n2c-btn-keypad" id="n2cKeypadActionBtn" title="Toggle Keypad / DTMF">
                <i class="fa fa-th"></i>
            </button>
        </div>
    </div>

    <!-- Background WebRTC Iframe Container:
         Kept active in DOM (height: 0, opacity: 0) during masked call so WebRTC audio & click-to-dial stream smoothly!
         Expands to 520px when agent clicks Keypad button! -->
    <div id="n2cIframeContainer" class="n2c-iframe-container is-hidden">
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
        const callingCard = document.getElementById('n2cCallingCard');

        const statusBadge = document.getElementById('n2cStatusBadge');
        const customerNameEl = document.getElementById('n2cCustomerName');
        const maskedNumberEl = document.getElementById('n2cMaskedNumber');
        const timerTextEl = document.getElementById('n2cCallTimerText');
        const callStatusEl = document.getElementById('n2cCallStatus');

        const closeBtn = document.getElementById('n2cCloseBtn');
        const hangupBtn = document.getElementById('n2cHangupBtn');
        const toggleKeypadBtn = document.getElementById('n2cToggleKeypadBtn');
        const keypadActionBtn = document.getElementById('n2cKeypadActionBtn');
        const externalBtn = document.getElementById('n2cExternalBtn');
        const refreshBtn = document.getElementById('n2cRefreshBtn');

        let timerInterval = null;
        let callSeconds = 0;

        function startCallTimer() {
            stopCallTimer();
            callSeconds = 0;
            if (timerTextEl) timerTextEl.textContent = '00:00';
            timerInterval = setInterval(function () {
                callSeconds++;
                const mins = String(Math.floor(callSeconds / 60)).padStart(2, '0');
                const secs = String(callSeconds % 60).padStart(2, '0');
                if (timerTextEl) timerTextEl.textContent = `${mins}:${secs}`;
                if (callSeconds > 2 && callStatusEl && callStatusEl.textContent === 'Calling...') {
                    callStatusEl.textContent = 'Call in progress';
                    if (statusBadge) statusBadge.textContent = 'In Call';
                }
            }, 1000);
        }

        function stopCallTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        // Mask Number Helper: Shows +CC ******XXXX (Super Admin sees full number)
        function maskNumber(num) {
            if (!num) return '******';
            const str = String(num).replace(/[^0-9]/g, '');
            if (!str) return '******';

            const isSuperAdmin = {{ Auth::check() && ((int) Auth::user()->role_id === 1) ? 'true' : 'false' }};
            if (isSuperAdmin) {
                return (str.startsWith('91') && str.length > 10) ? ('+' + str) : ('+91 ' + str);
            }

            let prefix = '+91 ';
            let digits = str;
            if (str.startsWith('91') && str.length >= 12) {
                prefix = '+91 ';
                digits = str.slice(2);
            } else if (str.startsWith('44') && str.length >= 11) {
                prefix = '+44 ';
                digits = str.slice(2);
            } else if (str.startsWith('1') && str.length === 11) {
                prefix = '+1 ';
                digits = str.slice(1);
            }

            if (digits.length <= 4) {
                return prefix + '******' + digits;
            }
            return prefix + '******' + digits.slice(-4);
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

        refreshBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon) icon.classList.add('fa-spin');
            ensureNext2CallIpAllowed(function () {
                const currentFrame = document.getElementById('ringfySoftphoneFrame');
                if (currentFrame && currentFrame.src) {
                    const originalSrc = currentFrame.src;
                    currentFrame.src = 'about:blank';
                    setTimeout(() => {
                        currentFrame.src = originalSrc;
                        if (icon) icon.classList.remove('fa-spin');
                    }, 400);
                } else {
                    if (icon) icon.classList.remove('fa-spin');
                }
            });
        });

        // Toggle Keypad handler (switches between Masked Card and Next2Call Dialpad)
        function toggleKeypad() {
            if (!iframeWrap) return;
            const isHidden = iframeWrap.classList.contains('is-hidden');
            if (isHidden) {
                iframeWrap.classList.remove('is-hidden');
                if (callingCard) callingCard.style.display = 'none';
            } else {
                iframeWrap.classList.add('is-hidden');
                if (callingCard) callingCard.style.display = 'flex';
            }
        }

        toggleKeypadBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            toggleKeypad();
        });

        keypadActionBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            toggleKeypad();
        });

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
                currentWidget.style.display = 'flex';
                currentWidget.classList.add('is-open');
            }

            // Show Keypad iframe directly
            if (callingCard) callingCard.style.display = 'none';
            if (iframeWrap) iframeWrap.classList.remove('is-hidden');

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) headerTitleEl.textContent = 'Next2Call Dialer';

            const activeDialerUrl = ((currentWidget?.dataset?.dialerUrl) || DIALER_URL).replace(/&amp;/g, '&');
            mountIframe(activeDialerUrl);

            // Ensure agent IP is fresh on Next2Call PBX
            ensureNext2CallIpAllowed();
        };

        window.dialNumber = function (mobile, countryCode = '', contactName = 'Customer') {
            window.dialNext2CallNumber(mobile, countryCode, contactName);
        };

        window.openRingfySoftphone = async function (orderId, countryCode = '', mobile = '', contactName = 'Customer') {
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
                const currentMain = document.querySelector('main.content') || document.querySelector('#kt_content') || document.querySelector('#kt_wrapper');

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
