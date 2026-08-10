@once
<style>
    .ringfy-softphone-widget {
        position: fixed;
        right: 22px;
        bottom: 22px;
        width: 330px;
        max-width: calc(100vw - 24px);
        background: #111827;
        border: 1px solid #263241;
        border-radius: 28px;
        box-shadow: 0 22px 55px rgba(15, 23, 42, 0.34);
        z-index: 1085;
        overflow: hidden;
        display: none;
        padding: 10px;
    }

    .ringfy-softphone-launcher {
        position: fixed;
        right: 22px;
        bottom: 22px;
        width: 58px;
        height: 58px;
        border: 0;
        border-radius: 50%;
        background: #16a34a;
        color: #fff;
        box-shadow: 0 14px 35px rgba(22, 163, 74, 0.34);
        z-index: 1084;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .ringfy-softphone-widget.is-open + .ringfy-softphone-launcher {
        display: none;
    }

    .ringfy-softphone-widget.is-open {
        display: block;
    }

    .ringfy-softphone-widget.is-maximized,
    .ringfy-softphone-widget.has-frame {
        width: 430px;
    }

    .ringfy-softphone-widget.is-minimized .ringfy-softphone-body {
        display: none;
    }

    .ringfy-softphone-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px 12px;
        cursor: move;
        background: #111827;
        color: #fff;
        user-select: none;
    }

    .ringfy-softphone-title {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        font-weight: 700;
    }

    .ringfy-softphone-title span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ringfy-softphone-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .ringfy-softphone-icon-btn {
        width: 28px;
        height: 28px;
        border: 0;
        border-radius: 50%;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ringfy-softphone-icon-btn:hover {
        background: rgba(255, 255, 255, 0.28);
    }

    .ringfy-softphone-body {
        padding: 18px;
        background: #f8fafc;
        border-radius: 22px;
        border: 1px solid #e5e7eb;
    }

    .ringfy-softphone-number {
        font-size: 21px;
        color: #111827;
        margin: 8px 0 14px;
        text-align: center;
        word-break: break-word;
        font-weight: 700;
        letter-spacing: 0;
    }

    .ringfy-softphone-status {
        color: #16a34a;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
    }

    .ringfy-softphone-avatar {
        width: 58px;
        height: 58px;
        margin: 2px auto 10px;
        border-radius: 50%;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .ringfy-softphone-note {
        color: #64748b;
        font-size: 13px;
        line-height: 1.45;
        margin: 12px 0 16px;
    }

    .ringfy-softphone-frame-wrap {
        display: none;
        margin-top: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        min-height: 520px;
    }

    .ringfy-softphone-widget.has-frame .ringfy-softphone-frame-wrap {
        display: block;
    }

    .ringfy-softphone-widget.has-frame .ringfy-softphone-avatar,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-status,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-number,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-manual-form,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-dialpad,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-note,
    .ringfy-softphone-widget.has-frame .ringfy-softphone-buttons {
        display: none;
    }

    .ringfy-softphone-frame {
        width: 100%;
        height: 520px;
        border: 0;
        display: block;
        background: #fff;
    }

    .ringfy-softphone-buttons {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .ringfy-softphone-buttons .btn {
        border-radius: 999px;
    }

    .ringfy-softphone-dialpad {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin: 14px 0;
    }

    .ringfy-softphone-key {
        min-height: 36px;
        border-radius: 10px;
        background: #eef2f7;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .ringfy-softphone-manual-form {
        display: grid;
        grid-template-columns: 82px 1fr 42px;
        gap: 8px;
        margin: 14px 0;
    }

    .ringfy-softphone-manual-form .form-control,
    .ringfy-softphone-manual-form .btn {
        min-height: 40px;
        border-radius: 12px;
    }

    .ringfy-softphone-grip {
        font-size: 12px;
        opacity: 0.55;
    }

    @media (max-width: 575.98px) {
        .ringfy-softphone-widget {
            right: 12px;
            bottom: 12px;
            width: calc(100vw - 24px);
        }

        .ringfy-softphone-launcher {
            right: 14px;
            bottom: 14px;
        }

        .ringfy-softphone-widget.is-maximized {
            width: calc(100vw - 24px);
        }
    }
</style>

<div id="ringfySoftphoneWidget" class="ringfy-softphone-widget" aria-live="polite">
    <div id="ringfySoftphoneHandle" class="ringfy-softphone-header">
        <div class="ringfy-softphone-title">
            <i class="fa fa-phone"></i>
            <span>Ringfy Softphone</span>
            <span class="ringfy-softphone-grip">drag</span>
        </div>
        <div class="ringfy-softphone-actions">
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneExternal" title="Open in New Tab">
                <i class="fa fa-external-link"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneMinimize" title="Minimize">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneMaximize" title="Maximize">
                <i class="fa fa-expand"></i>
            </button>
            <button type="button" class="ringfy-softphone-icon-btn" id="ringfySoftphoneClose" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="ringfy-softphone-body">
        <div class="ringfy-softphone-avatar">
            <i class="fa fa-phone"></i>
        </div>
        <div class="ringfy-softphone-status">Ready to call</div>
        <div class="ringfy-softphone-number" id="ringfySoftphoneNumber"></div>
        <form id="ringfySoftphoneManualForm" class="ringfy-softphone-manual-form">
            <input type="text" id="ringfySoftphoneCountryCode" class="form-control" placeholder="+91" inputmode="tel" autocomplete="tel-country-code">
            <input type="text" id="ringfySoftphoneMobile" class="form-control" placeholder="Mobile number" inputmode="tel" autocomplete="tel-national">
            <button type="submit" class="btn btn-success btn-icon" title="Start call">
                <i class="fa fa-phone"></i>
            </button>
        </form>
        <div class="ringfy-softphone-dialpad" aria-hidden="true">
            <span class="ringfy-softphone-key">1</span>
            <span class="ringfy-softphone-key">2</span>
            <span class="ringfy-softphone-key">3</span>
            <span class="ringfy-softphone-key">4</span>
            <span class="ringfy-softphone-key">5</span>
            <span class="ringfy-softphone-key">6</span>
            <span class="ringfy-softphone-key">7</span>
            <span class="ringfy-softphone-key">8</span>
            <span class="ringfy-softphone-key">9</span>
            <span class="ringfy-softphone-key">*</span>
            <span class="ringfy-softphone-key">0</span>
            <span class="ringfy-softphone-key">#</span>
        </div>
        <p class="ringfy-softphone-note text-center">
            Ringfy webphone panel. If server is slow, open in new tab below.
        </p>
        <div class="ringfy-softphone-frame-wrap">
            <iframe
                id="ringfySoftphoneFrame"
                class="ringfy-softphone-frame"
                title="Ringfy Softphone"
                allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
                allowfullscreen>
            </iframe>
        </div>
        <div class="ringfy-softphone-buttons text-center mt-2">
            <button type="button" id="ringfySoftphoneOpenTab" class="btn btn-sm btn-light-primary w-100">
                <i class="fa fa-external-link me-1"></i> Open Dialer in New Tab
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const handle = document.getElementById('ringfySoftphoneHandle');
        const launcherButton = document.getElementById('ringfySoftphoneLauncher');
        const manualForm = document.getElementById('ringfySoftphoneManualForm');
        const countryCodeInput = document.getElementById('ringfySoftphoneCountryCode');
        const mobileInput = document.getElementById('ringfySoftphoneMobile');
        const popupButton = document.getElementById('ringfySoftphonePopup');
        const softphoneFrame = document.getElementById('ringfySoftphoneFrame');
        const minimizeButton = document.getElementById('ringfySoftphoneMinimize');
        const maximizeButton = document.getElementById('ringfySoftphoneMaximize');
        const closeButton = document.getElementById('ringfySoftphoneClose');
        const savedPosition = localStorage.getItem('ringfySoftphonePosition');
        const savedCountryCode = localStorage.getItem('ringfySoftphoneCountryCode');

        if (savedCountryCode && countryCodeInput) {
            countryCodeInput.value = savedCountryCode;
        }

        if (savedPosition) {
            try {
                const position = JSON.parse(savedPosition);
                setRingfyWidgetPosition(position.left, position.top);
            } catch (error) {
                localStorage.removeItem('ringfySoftphonePosition');
            }
        }

        let dragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        function startDrag(clientX, clientY, target) {
            if (target.closest('button')) {
                return;
            }

            dragging = true;
            const rect = widget.getBoundingClientRect();
            dragOffsetX = clientX - rect.left;
            dragOffsetY = clientY - rect.top;
            document.body.style.userSelect = 'none';
        }

        handle?.addEventListener('mousedown', function (event) {
            startDrag(event.clientX, event.clientY, event.target);
        });

        handle?.addEventListener('touchstart', function (event) {
            const touch = event.touches[0];
            startDrag(touch.clientX, touch.clientY, event.target);
        }, { passive: true });

        document.addEventListener('mousemove', function (event) {
            if (!dragging) {
                return;
            }

            setRingfyWidgetPosition(event.clientX - dragOffsetX, event.clientY - dragOffsetY);
        });

        document.addEventListener('touchmove', function (event) {
            if (!dragging) {
                return;
            }

            const touch = event.touches[0];
            setRingfyWidgetPosition(touch.clientX - dragOffsetX, touch.clientY - dragOffsetY);
        }, { passive: true });

        document.addEventListener('mouseup', function () {
            finishDrag();
        });

        document.addEventListener('touchend', function () {
            finishDrag();
        });

        function finishDrag() {
            if (!dragging) {
                return;
            }

            dragging = false;
            document.body.style.userSelect = '';
            const rect = widget.getBoundingClientRect();
            localStorage.setItem('ringfySoftphonePosition', JSON.stringify({
                left: rect.left,
                top: rect.top,
            }));
        }

        const externalBtn = document.getElementById('ringfySoftphoneExternal');
        const openTabBtn = document.getElementById('ringfySoftphoneOpenTab');

        function openSoftphoneInNewTab() {
            const url = widget?.dataset.softphoneUrl;
            if (url) {
                window.open(url, '_blank');
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No Active Call',
                    text: 'Please initiate a call or enter a number first.',
                });
            }
        }

        externalBtn?.addEventListener('click', openSoftphoneInNewTab);
        openTabBtn?.addEventListener('click', openSoftphoneInNewTab);

        popupButton?.addEventListener('click', function () {
            const url = widget?.dataset.softphoneUrl;
            if (url && softphoneFrame) {
                softphoneFrame.src = url;
                widget.classList.add('has-frame');
                keepRingfyWidgetInViewport();
            }
        });

        launcherButton?.addEventListener('click', function () {
            openRingfyDialer();
        });

        manualForm?.addEventListener('submit', function (event) {
            event.preventDefault();

            const countryCode = countryCodeInput?.value || '';
            const mobile = mobileInput?.value || '';

            localStorage.setItem('ringfySoftphoneCountryCode', countryCode.trim());
            openRingfySoftphone(null, countryCode, mobile);
        });

        minimizeButton?.addEventListener('click', function () {
            widget.classList.toggle('is-minimized');
            minimizeButton.innerHTML = widget.classList.contains('is-minimized')
                ? '<i class="fa fa-window-restore"></i>'
                : '<i class="fa fa-minus"></i>';
        });

        maximizeButton?.addEventListener('click', function () {
            widget.classList.toggle('is-maximized');
            maximizeButton.innerHTML = widget.classList.contains('is-maximized')
                ? '<i class="fa fa-compress"></i>'
                : '<i class="fa fa-expand"></i>';
            keepRingfyWidgetInViewport();
        });

        closeButton?.addEventListener('click', function () {
            widget.classList.remove('is-open');
            widget.classList.remove('has-frame');
            widget.dataset.softphoneUrl = '';
            if (softphoneFrame) {
                softphoneFrame.removeAttribute('src');
            }
        });

        // Listen for hangup events from dialer to close the softphone widget automatically
        window.addEventListener("message", (event) => {
            if (event.origin !== "https://ringfy.next2call.com") return;
            
            console.log("Received postMessage from Next2Call:", event.data);

            const isHangup = event.data === "CALL_HANGUP" ||
                             event.data?.type === "CALL_HANGUP" ||
                             event.data?.event === "hangup" ||
                             event.data?.type === "hangup" ||
                             event.data?.type === "CALL_DISCONNECTED" ||
                             event.data === "CALL_DISCONNECTED";

            if (isHangup) {
                console.log("Call disconnected, closing softphone widget...");
                widget.classList.remove('is-open');
                widget.classList.remove('has-frame');
                widget.dataset.softphoneUrl = '';
                if (softphoneFrame) {
                    softphoneFrame.removeAttribute('src');
                }
            }
        });
    });

    function setRingfyWidgetPosition(left, top) {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const margin = 8;
        const maxLeft = window.innerWidth - widget.offsetWidth - margin;
        const maxTop = window.innerHeight - widget.offsetHeight - margin;
        const safeLeft = Math.max(margin, Math.min(left, maxLeft));
        const safeTop = Math.max(margin, Math.min(top, maxTop));

        widget.style.left = safeLeft + 'px';
        widget.style.top = safeTop + 'px';
        widget.style.right = 'auto';
        widget.style.bottom = 'auto';
    }

    function keepRingfyWidgetInViewport() {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const rect = widget.getBoundingClientRect();
        setRingfyWidgetPosition(rect.left, rect.top);
    }

    function showRingfySoftphone(url, targetNumber) {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const numberElement = document.getElementById('ringfySoftphoneNumber');
        const softphoneFrame = document.getElementById('ringfySoftphoneFrame');
        const countryCodeInput = document.getElementById('ringfySoftphoneCountryCode');
        const mobileInput = document.getElementById('ringfySoftphoneMobile');

        widget.dataset.softphoneUrl = url;
        numberElement.textContent = targetNumber || '';
        if (countryCodeInput?.value) {
            localStorage.setItem('ringfySoftphoneCountryCode', countryCodeInput.value.trim());
        }
        if (mobileInput && targetNumber && !mobileInput.value) {
            mobileInput.value = targetNumber;
        }

        if (softphoneFrame) {
            softphoneFrame.src = url;
        }

        widget.classList.add('is-open');
        widget.classList.add('has-frame');
        widget.classList.remove('is-minimized');
        document.getElementById('ringfySoftphoneMinimize').innerHTML = '<i class="fa fa-minus"></i>';
        keepRingfyWidgetInViewport();
    }

    function openRingfyDialer(countryCode = '', mobile = '') {
        const widget = document.getElementById('ringfySoftphoneWidget');
        const countryCodeInput = document.getElementById('ringfySoftphoneCountryCode');
        const mobileInput = document.getElementById('ringfySoftphoneMobile');

        if (countryCode && countryCodeInput) {
            countryCodeInput.value = countryCode;
        }

        if (mobile && mobileInput) {
            mobileInput.value = mobile;
        }

        widget.classList.add('is-open');
        widget.classList.remove('has-frame');
        widget.classList.remove('is-minimized');
        document.getElementById('ringfySoftphoneMinimize').innerHTML = '<i class="fa fa-minus"></i>';
        keepRingfyWidgetInViewport();
        mobileInput?.focus();
    }

    async function openRingfySoftphone(orderId, countryCode = '', mobile = '') {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const cleanCountryCode = String(countryCode || document.getElementById('country_primary')?.value || document.getElementById('ringfySoftphoneCountryCode')?.value || '').trim();
        const cleanMobile = String(mobile || document.getElementById('primary')?.value || document.getElementById('ringfySoftphoneMobile')?.value || '').trim();

        if (!cleanCountryCode || !cleanMobile) {
            Swal.fire({
                icon: 'warning',
                title: 'Number missing',
                text: 'Country code or mobile number is missing.',
            });
            return;
        }

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
                    country_code: cleanCountryCode,
                    mobile: cleanMobile,
                }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Softphone call failed.');
            }

            showRingfySoftphone(data.url, data.target_number);
            navigator.clipboard?.writeText(data.target_number).catch(() => {});

            Swal.fire({
                icon: 'success',
                title: 'Softphone ready',
                html: 'Customer number: <strong>' + data.target_number + '</strong><br>Number copied for dialing.',
                timer: 1800,
                showConfirmButton: false,
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Call failed',
                text: error.message || 'Unable to open softphone.',
            });
        }
    }
</script>
@endonce
