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

    $n2cClientUrl = route('softphone.client', [
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);

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

    $n2cCtcBaseUrl = "https://{$sipDomain}{$clickToDialPath}?" . http_build_query([
        'profileName' => $userId,
        'SipDomain'   => $sipDomain,
        'SipUsername' => $userId,
        'SipPassword' => $password,
    ]);
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
    }
    .n2c-title-text {
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
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
        width: 100%;
        height: 560px;
        background: #ffffff;
        overflow: hidden;
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

<!-- Next2Call Softphone Floating Widget Box (Default Next2Call Interface) -->
<div id="ringfySoftphoneWidget" class="n2c-softphone-box" aria-live="polite"
     data-client-url="{{ $n2cClientUrl }}"
     data-dialer-url="{{ $n2cDialerUrl }}"
     data-ctc-base="{{ $n2cCtcBaseUrl }}"
     data-external-ctc="{{ $n2cExternalCtcUrl }}">
    
    <!-- Drag Header -->
    <div id="ringfySoftphoneHandle" class="n2c-softphone-header">
        <div class="n2c-title-wrap">
            <i class="fa fa-phone text-success fs-7"></i>
            <span class="n2c-title-text" id="n2cHeaderTitle">Next2Call Softphone</span>
        </div>
        <div class="n2c-header-actions">
            <button type="button" class="n2c-header-btn" id="n2cExternalBtn" title="Open in Standalone Popup">
                <i class="fa fa-external-link-alt"></i>
            </button>
            <button type="button" class="n2c-header-btn" id="n2cCloseBtn" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Default Next2Call Phone Iframe Container -->
    <div id="n2cIframeContainer" class="n2c-iframe-container">
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

        const closeBtn = document.getElementById('n2cCloseBtn');
        const externalBtn = document.getElementById('n2cExternalBtn');

        const DIALER_URL = widget?.dataset?.dialerUrl || '';
        const CLIENT_URL = widget?.dataset?.clientUrl || widget?.dataset?.ctcBase || '';

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

                    currentFrame.contentWindow.postMessage({ type: 'HANGUP', action: 'hangup' }, '*');
                    currentFrame.contentWindow.postMessage('HANGUP', '*');
                    currentFrame.contentWindow.postMessage({ type: 'CLOSE_PHONE_POPUP' }, '*');
                }
            } catch (e) {}

            if (iframeWrap) {
                iframeWrap.innerHTML = '';
            }
        }

        // Close Widget (when call is cut or user closes)
        function closeSoftphoneWidget() {
            isCallActive = false;
            callInitiatedAt = 0;

            if (widget) {
                widget.classList.remove('is-open');
                widget.style.display = 'none';
            }

            destroyAndResetIframe();

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

        // Popout External Button
        externalBtn?.addEventListener('click', function () {
            const currentFrame = document.getElementById('ringfySoftphoneFrame');
            const currentSrc = (currentFrame && currentFrame.src && currentFrame.src !== 'about:blank') ? currentFrame.src : (CLIENT_URL || DIALER_URL);
            if (currentSrc) {
                window.open(currentSrc, 'Next2CallSoftphone', 'width=380,height=600,menubar=no,toolbar=no,location=no');
            }
        });

        // Listen for postMessage from Softphone PBX
        window.addEventListener('message', function (event) {
            const originOk = event.origin.includes('next2call.com') ||
                             event.origin === window.location.origin ||
                             window.location.origin.includes('localhost') ||
                             window.location.origin.includes('127.0.0.1');
            if (!originOk) return;

            let data = event.data;
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {}
            }

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

            if (isHangup) {
                console.log('[Next2Call] Call cut / terminated, closing softphone widget...');
                closeSoftphoneWidget();
                return;
            } else if (isConnected) {
                isCallActive = true;
            } else if (isIncoming) {
                // Incoming call: Open widget!
                if (widget) {
                    widget.style.display = 'flex';
                    widget.classList.add('is-open');
                }
            }
        });

        // Global Direct Dial Function with Country Code
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

            const clientBase = widget?.dataset?.clientUrl || widget?.dataset?.ctcBase || '';
            const targetUrl = clientBase + (clientBase.includes('?') ? '&' : '?') + 'd=' + encodeURIComponent(num);

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

            const headerTitleEl = document.getElementById('n2cHeaderTitle');
            if (headerTitleEl) {
                headerTitleEl.textContent = (contactName && contactName !== 'Customer') ? ('Next2Call: ' + contactName) : 'Next2Call Softphone';
            }

            // Mount default Next2Call iframe
            mountIframe(targetUrl);

            // Auto-whitelist IP & log call on server in background
            try {
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
                }).catch(() => {});
            } catch (e) {}
        };

        window.openRingfyDialer = function (mobile = '') {
            if (mobile) {
                window.dialNext2CallNumber(mobile);
            } else {
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
                const clientBase = widget?.dataset?.clientUrl || widget?.dataset?.dialerUrl || '';
                mountIframe(clientBase);
            }
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRingfySoftphoneWidget);
    } else {
        initRingfySoftphoneWidget();
    }
</script>
@endonce
