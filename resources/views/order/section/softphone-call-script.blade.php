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
    if (!str_contains($n2cDialerUrl, 'api-section')) {
        $n2cDialerUrl = str_replace('/softphone/Phone/', '/api-section/softphone/Phone/', $n2cDialerUrl);
    }

    $n2cRawCtc = $n2cSession['click_to_call_url'] ?? '';
    $n2cCtcBaseUrl = !empty($n2cRawCtc)
        ? str_replace('index.html', 'click-to-dial.html', $n2cRawCtc)
        : ("https://{$sipDomain}/api-section/softphone/Phone/click-to-dial.html?" . http_build_query([
            'profileName' => $userId,
            'SipDomain'   => $sipDomain,
            'SipUsername' => $userId,
            'SipPassword' => $password,
        ]) . '&d=');
    if (!str_contains($n2cCtcBaseUrl, 'api-section')) {
        $n2cCtcBaseUrl = str_replace('/softphone/Phone/', '/api-section/softphone/Phone/', $n2cCtcBaseUrl);
    }
@endphp

<style>
    /* Next2Call Softphone Floating Box (Default Next2Call Interface) */
    .n2c-softphone-box {
        position: fixed;
        right: 25px;
        bottom: 30px;
        width: 340px;
        max-width: calc(100vw - 30px);
        background: #1e1e2d;
        color: #ffffff;
        border-radius: 12px;
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

    /* Default Next2Call Phone Iframe Container */
    .n2c-iframe-container {
        position: relative;
        width: 100%;
        height: 560px;
        background: #151521;
        overflow: hidden;
    }
    .n2c-loading-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, #1e1e2d 0%, #151521 100%);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
        text-align: center;
        padding: 20px;
        transition: opacity 0.3s ease;
    }
    .n2c-loader-contact {
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
        margin-top: 14px;
    }
    .n2c-loader-number {
        font-size: 14px;
        font-weight: 600;
        color: #10b981;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        padding: 5px 14px;
        border-radius: 6px;
        margin: 10px 0 12px;
        letter-spacing: 0.5px;
    }
    .n2c-loader-status {
        font-size: 11px;
        color: #a1a5b7;
    }
    .n2c-softphone-iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
        background: #ffffff;
    }

    .n2c-logout-btn {
        background: #dc3545;
        color: #ffffff;
        border: 0;
        border-radius: 6px;
        padding: 4px 9px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }
    .n2c-logout-btn:hover {
        background: #bb2d3b;
        color: #ffffff;
    }
    .n2c-logout-btn:active {
        transform: scale(0.97);
    }
    .n2c-notice-bar {
        background: #2a2215;
        border-bottom: 1px solid #4a3b1a;
        color: #ffd666;
        padding: 5px 12px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    @media (max-width: 575.98px) {
        .n2c-softphone-box {
            right: 10px;
            bottom: 10px;
            width: calc(100vw - 20px);
        }
    }
</style>

<!-- Next2Call Softphone Floating Widget Box (Default Next2Call Interface) -->
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
            <button type="button" class="n2c-logout-btn" id="n2cLogoutBtn" title="Registration Failed ya Forbidden aane par yahan click karein: Session Logout karke fresh 12h login banayega">
                <i class="fa fa-sign-out-alt"></i> Logout / Reset
            </button>
            <button type="button" class="n2c-header-btn" id="n2cRefreshBtn" title="Re-Login / Refresh 12h Session">
                <i class="fa fa-sync-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cCloseBtn" title="Close Softphone">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Quick Help Notice Bar for Forbidden / Login issues -->
    <div id="n2cNoticeBar" class="n2c-notice-bar">
        <span><i class="fa fa-info-circle me-1"></i> Forbidden ya registration error aane par <strong>Logout / Reset</strong> karein.</span>
        <button type="button" id="n2cQuickResetLink" style="background:none;border:none;color:#ff7875;cursor:pointer;text-decoration:underline;font-size:11px;font-weight:700;padding:0;">Re-login</button>
    </div>

    <!-- Default Next2Call Phone Iframe Container -->
    <div id="n2cIframeContainer" class="n2c-iframe-container">
        <!-- Connecting & Masking Loader Overlay -->
        <div id="n2cLoadingOverlay" class="n2c-loading-overlay">
            <div class="spinner-border text-success" role="status" style="width: 2.4rem; height: 2.4rem;"></div>
            <div class="n2c-loader-contact" id="n2cLoaderContact">Calling Customer...</div>
            <div class="n2c-loader-number" id="n2cLoaderNumber">+91 ******2299</div>
            <div class="n2c-loader-status" id="n2cLoaderStatus">Connecting Next2Call 12h Webphone...</div>
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
        const overlay = document.getElementById('n2cLoadingOverlay');
        const loaderContact = document.getElementById('n2cLoaderContact');
        const loaderNumber = document.getElementById('n2cLoaderNumber');
        const loaderStatus = document.getElementById('n2cLoaderStatus');

        const closeBtn = document.getElementById('n2cCloseBtn');
        const refreshBtn = document.getElementById('n2cRefreshBtn');
        const logoutBtn = document.getElementById('n2cLogoutBtn');
        const quickResetLink = document.getElementById('n2cQuickResetLink');

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

        // Full Session Reset & Logout Flow:
        // Automatically clears stale session from DB, ensures IP whitelist, drops old Asterisk socket,
        // and generates a fresh 12-hour session and fresh dialer URLs cleanly.
        async function performNext2CallSessionReset() {
            const icon = logoutBtn?.querySelector('i');
            if (icon) icon.className = 'fa fa-spinner fa-spin';
            const refreshIcon = refreshBtn?.querySelector('i');
            if (refreshIcon) refreshIcon.classList.add('fa-spin');

            if (overlay) overlay.style.display = 'flex';
            if (loaderContact) loaderContact.textContent = 'Next2Call PBX';
            if (loaderNumber) loaderNumber.textContent = 'Logging out stale session...';
            if (loaderStatus) loaderStatus.textContent = 'Clearing DB session & re-registering fresh SIP connection...';

            // 1. Immediately drop previous WebRTC socket connection on iframe
            const currentFrame = document.getElementById('ringfySoftphoneFrame');
            if (currentFrame) {
                currentFrame.src = 'about:blank';
            }

            // 2. Discover client IP for firewall whitelisting
            let clientIp = '';
            try {
                const ipRes = await fetch('https://api.ipify.org?format=json');
                const ipData = await ipRes.json();
                clientIp = ipData.ip || '';
            } catch (e) {}

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            try {
                const res = await fetch('{{ route('softphone.reset-session') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ client_ip: clientIp })
                });
                const data = await res.json();
                if (data && data.success && data.dialer_url) {
                    const freshDialer = data.dialer_url.replace(/&amp;/g, '&');
                    if (widget) {
                        widget.dataset.dialerUrl = freshDialer;
                        DIALER_URL = freshDialer;
                        if (data.click_to_call_url) {
                            const newBase = data.click_to_call_url.includes('&d=') 
                                ? (data.click_to_call_url.split('&d=')[0] + '&d=')
                                : data.click_to_call_url;
                            widget.dataset.ctcBase = newBase;
                            CTC_BASE_URL = newBase;
                        }
                    }

                    // Delay mounting by 600ms so Asterisk drops old socket cleanly
                    setTimeout(() => {
                        const targetFrame = document.getElementById('ringfySoftphoneFrame');
                        if (targetFrame) {
                            targetFrame.src = freshDialer;
                        }
                        if (overlay) overlay.style.display = 'none';
                        if (icon) icon.className = 'fa fa-sign-out-alt';
                        if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Next2Call session reset successfully! Logged in with fresh 12h token.');
                        }
                    }, 600);
                    return;
                }
            } catch (err) {
                console.warn('[Next2Call] Reset error:', err);
            }

            setTimeout(() => {
                if (overlay) overlay.style.display = 'none';
                if (icon) icon.className = 'fa fa-sign-out-alt';
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            }, 800);
        }

        logoutBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            performNext2CallSessionReset();
        });

        quickResetLink?.addEventListener('click', function(e) {
            e.preventDefault();
            performNext2CallSessionReset();
        });

        refreshBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            performNext2CallSessionReset();
        });

        window.resetNext2CallSession = performNext2CallSessionReset;
        window.logoutNext2Call = performNext2CallSessionReset;

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
                if (cleanUrl && currentFrame.src !== cleanUrl) {
                    currentFrame.src = cleanUrl;
                }
                return;
            }

            container.innerHTML = `
                <div id="n2cLoadingOverlay" class="n2c-loading-overlay">
                    <div class="spinner-border text-success" role="status" style="width: 2.4rem; height: 2.4rem;"></div>
                    <div class="n2c-loader-contact" id="n2cLoaderContact">Calling Customer...</div>
                    <div class="n2c-loader-number" id="n2cLoaderNumber">+91 ******2299</div>
                    <div class="n2c-loader-status" id="n2cLoaderStatus">Connecting Next2Call 12h Webphone...</div>
                </div>
                <iframe
                    id="ringfySoftphoneFrame"
                    class="n2c-softphone-iframe"
                    src="${cleanUrl || ''}"
                    allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
                    allowfullscreen>
                </iframe>
            `;
            attachFrameLoadListener();
        }

        function attachFrameLoadListener() {
            const frame = document.getElementById('ringfySoftphoneFrame');
            if (frame) {
                frame.onload = function () {
                    const lOverlay = document.getElementById('n2cLoadingOverlay');
                    if (lOverlay) {
                        setTimeout(() => {
                            lOverlay.style.display = 'none';
                        }, 500);
                    }
                };
            }
        }
        attachFrameLoadListener();

        // Close Widget (Keep 12h session intact - do NOT destroy iframe!)
        function closeSoftphoneWidget() {
            isCallActive = false;
            callInitiatedAt = 0;

            if (widget) {
                widget.classList.remove('is-open');
                widget.style.display = 'none';
            }

            const lOverlay = document.getElementById('n2cLoadingOverlay');
            if (lOverlay) lOverlay.style.display = 'none';

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) headerTitleEl.textContent = 'Next2Call Softphone';
        }

        closeBtn?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeSoftphoneWidget();
        });

        // Close widget with Escape key if needed
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && widget && widget.classList.contains('is-open')) {
                closeSoftphoneWidget();
            }
        });

        // Listen for hangup events from Next2Call dialer
        window.addEventListener('message', function (event) {
            if (event.origin !== 'https://ringfy.next2call.com') return;

            let data = event.data;
            if (typeof data === 'string') {
                try { data = JSON.parse(data); } catch(e){}
            }

            if (data === 'CALL_HANGUP' || data?.type === 'CALL_HANGUP' || data?.type === 'CLOSE_PHONE_POPUP') {
                console.log('[Next2Call] Call disconnected / hangup received');
                isCallActive = false;
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

            // Masked display formatting for UI (e.g. +91 ******2299)
            let maskedDisplayNum = num;
            if (num.length >= 6) {
                const ccPart = num.length > 10 ? ('+' + num.slice(0, num.length - 10)) : '+91';
                maskedDisplayNum = ccPart + ' ******' + num.slice(-4);
            }

            // Open Widget when call arrives/starts
            if (widget) {
                const twilioBox = document.getElementById('twilioSoftphoneBox');
                if (twilioBox && $(twilioBox).is(':visible') && !widget.style.left) {
                    widget.style.right = '325px';
                } else if (!widget.style.left) {
                    widget.style.right = '25px';
                }
                widget.style.display = 'flex';
                widget.classList.add('is-open');
            }

            // Update header title with masked phone number
            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) {
                headerTitleEl.textContent = (contactName && contactName !== 'Customer')
                    ? (`Call: ${contactName} (${maskedDisplayNum})`)
                    : (`Next2Call (${maskedDisplayNum})`);
            }

            // Show instant loading overlay with customer info & masked number
            const lOverlay = document.getElementById('n2cLoadingOverlay');
            const lContact = document.getElementById('n2cLoaderContact');
            const lNumber = document.getElementById('n2cLoaderNumber');
            const lStatus = document.getElementById('n2cLoaderStatus');
            if (lContact) lContact.textContent = (contactName && contactName !== 'Customer') ? contactName : 'Customer';
            if (lNumber) lNumber.textContent = maskedDisplayNum;
            if (lStatus) lStatus.textContent = 'Connecting Next2Call 12h Webphone...';
            if (lOverlay) lOverlay.style.display = 'flex';

            // Calculate target direct dial URL immediately from base CTC
            let baseCtc = ((widget?.dataset?.ctcBase) || CTC_BASE_URL || '').replace(/&amp;/g, '&');
            if (!baseCtc.includes('api-section')) {
                baseCtc = baseCtc.replace('/softphone/Phone/', '/api-section/softphone/Phone/');
            }

            let targetCallUrl = '';
            if (baseCtc) {
                if (baseCtc.includes('&d=')) {
                    targetCallUrl = baseCtc.replace(/&d=[^&]*/, `&d=${encodeURIComponent(num)}`);
                } else {
                    targetCallUrl = `${baseCtc}&d=${encodeURIComponent(num)}`;
                }
            }

            // Mount and load iframe INSTANTLY without waiting for network AJAX
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
