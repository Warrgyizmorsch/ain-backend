@once
<!-- Twilio Voice WebRTC SDK (Local bundle with jsDelivr Fallback) -->
<script src="{{ asset('assets/plugins/twilio/twilio.min.js') }}"></script>
<script>
if (typeof Twilio === 'undefined' || !Twilio.Device) {
    document.write('<script src="https://cdn.jsdelivr.net/npm/@twilio/voice-sdk@2.11.0/dist/twilio.min.js"><\/script>');
}
</script>

<!-- Twilio Softphone Floating Widget Styles -->
<style>
.twilio-softphone-launcher {
    position: fixed;
    right: 25px;
    bottom: 25px;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: linear-gradient(135deg, #009ef7, #00539c);
    color: #fff;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 158, 247, 0.4);
    z-index: 1080;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.twilio-softphone-launcher:hover {
    transform: scale(1.08);
    box-shadow: 0 15px 35px rgba(0, 158, 247, 0.5);
}
.twilio-softphone-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #50cd89;
    border: 2px solid #fff;
}
.twilio-softphone-badge.offline {
    background: #f1416c;
}
.twilio-softphone-box {
    position: fixed;
    right: 25px;
    bottom: 90px;
    width: 320px;
    max-width: calc(100vw - 30px);
    background: #1e1e2d;
    color: #fff;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
    z-index: 1081;
    overflow: hidden;
    display: none;
    animation: twilioSlideUp 0.25s ease-out;
}
@keyframes twilioSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.twilio-softphone-header {
    padding: 12px 16px;
    background: #151521;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.twilio-dialpad-btn {
    width: 58px;
    height: 48px;
    border-radius: 10px;
    background: #2b2b40;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 18px;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
}
.twilio-dialpad-btn span.letters {
    font-size: 8px;
    color: #7e8299;
    letter-spacing: 1px;
    margin-top: -2px;
}
.twilio-dialpad-btn:hover {
    background: #363654;
    color: #009ef7;
}
.twilio-call-action-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
    transition: transform 0.15s ease;
}
.twilio-call-action-btn:hover {
    transform: scale(1.08);
}
.pulse-ringing {
    animation: pulseRing 1.4s infinite;
}
@keyframes pulseRing {
    0% { box-shadow: 0 0 0 0 rgba(80, 205, 137, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(80, 205, 137, 0); }
    100% { box-shadow: 0 0 0 0 rgba(80, 205, 137, 0); }
}
</style>

<!-- Floating Softphone Launcher Button -->
<button type="button" class="twilio-softphone-launcher" id="twilioSoftphoneLauncher" title="Open Twilio Dialer" onclick="twilioSoftphone.toggleWidget()">
    <i class="fa fa-phone fs-3 text-white"></i>
    <span class="twilio-softphone-badge offline" id="twilioDeviceStatusDot" title="Offline"></span>
</button>

<!-- Floating Softphone Dialer Widget Box -->
<div class="twilio-softphone-box" id="twilioSoftphoneBox">
    <!-- Header -->
    <div class="twilio-softphone-header">
        <div class="d-flex align-items-center gap-2">
            <i class="fa fa-phone text-primary"></i>
            <span class="fw-bold fs-7">Twilio Voice Dialer</span>
            <span id="twilioStatusText" class="badge badge-light-primary py-1 px-2 fs-9">
                <i class="fa fa-spinner fa-spin me-1"></i> Connecting...
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-icon text-muted p-0" onclick="twilioSoftphone.openPopout()" title="Popout Window (Stays active across all CRM pages)">
                <i class="fa fa-external-link-alt text-white fs-8"></i>
            </button>
            <button type="button" class="btn btn-sm btn-icon btn-active-color-primary text-muted p-0" onclick="twilioSoftphone.toggleWidget()">
                <i class="fa fa-times text-white"></i>
            </button>
        </div>
    </div>

    <!-- Body -->
    <div class="p-4">
        <!-- Active Call Screen (Visible during ongoing/ringing call) -->
        <div id="twilioActiveCallView" class="d-none text-center py-3">
            <div class="symbol symbol-60px symbol-circle bg-light-primary mb-3 mx-auto p-3 pulse-ringing" id="activeCallAvatar">
                <i class="fa fa-phone text-primary fs-1"></i>
            </div>
            <h5 class="text-white fw-bold mb-1" id="twilioActiveCallName">Dialing...</h5>
            <div class="text-muted fs-7 mb-2" id="twilioActiveCallNumber">+1...</div>
            <div class="badge badge-light-success fs-7 mb-4 px-3 py-1" id="twilioActiveCallTimer">00:00</div>

            <!-- In-Call Actions (Mute, Hold, End Call) -->
            <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                <button type="button" class="twilio-call-action-btn bg-dark text-white" id="twilioMuteBtn" title="Mute Mic" onclick="twilioSoftphone.toggleMute()">
                    <i class="fa fa-microphone"></i>
                </button>
                <button type="button" class="twilio-call-action-btn bg-danger" id="twilioHangupBtn" title="Hangup / End Call" onclick="twilioSoftphone.hangup()">
                    <i class="fa fa-phone-slash"></i>
                </button>
                <button type="button" class="twilio-call-action-btn bg-dark text-white" id="twilioHoldBtn" title="Hold Call" onclick="twilioSoftphone.toggleHold()">
                    <i class="fa fa-pause"></i>
                </button>
            </div>
        </div>

        <!-- Incoming Call Alert (Visible on Inbound Call) -->
        <div id="twilioIncomingCallView" class="d-none text-center py-3">
            <div class="symbol symbol-60px symbol-circle bg-light-success mb-3 mx-auto p-3 pulse-ringing">
                <i class="fa fa-phone-volume text-success fs-1"></i>
            </div>
            <h5 class="text-white fw-bold mb-1">Incoming Call</h5>
            <div class="text-white fs-6 fw-bold mb-4" id="twilioIncomingFromNumber">Unknown Caller</div>

            <div class="d-flex justify-content-center gap-4">
                <button type="button" class="btn btn-success btn-sm px-4" onclick="twilioSoftphone.acceptIncoming()">
                    <i class="fa fa-phone me-1"></i> Accept
                </button>
                <button type="button" class="btn btn-danger btn-sm px-4" onclick="twilioSoftphone.rejectIncoming()">
                    <i class="fa fa-phone-slash me-1"></i> Reject
                </button>
            </div>
        </div>

        <!-- Keypad / Dialpad Screen (Default view) -->
        <div id="twilioDialpadView">
            <div class="mb-3">
                <input type="text" id="twilioDialerInput" class="form-control form-control-solid bg-dark text-white text-center font-monospace fs-4 border-0" placeholder="+Country Code Number" autocomplete="off">
            </div>

            <!-- Dialpad Grid -->
            <div class="d-grid gap-2 mb-3" style="grid-template-columns: repeat(3, 1fr);">
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('1')">1 <span class="letters">&nbsp;</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('2')">2 <span class="letters">ABC</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('3')">3 <span class="letters">DEF</span></button>

                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('4')">4 <span class="letters">GHI</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('5')">5 <span class="letters">JKL</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('6')">6 <span class="letters">MNO</span></button>

                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('7')">7 <span class="letters">PQRS</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('8')">8 <span class="letters">TUV</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('9')">9 <span class="letters">WXYZ</span></button>

                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('*')">* <span class="letters">&nbsp;</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('0')">0 <span class="letters">+</span></button>
                <button type="button" class="twilio-dialpad-btn" onclick="twilioSoftphone.pressKey('#')"># <span class="letters">&nbsp;</span></button>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-sm btn-icon text-muted" onclick="twilioSoftphone.backspace()" title="Backspace">
                    <i class="fa fa-backspace text-white"></i>
                </button>
                <button type="button" class="btn btn-success w-75 fw-bold" onclick="twilioSoftphone.makeCall()">
                    <i class="fa fa-phone me-1"></i> Call
                </button>
            </div>

            <div class="text-center mt-3 pt-2 border-top border-secondary">
                <a href="javascript:void(0)" onclick="twilioSoftphone.openPopout()" class="text-primary fs-9 text-decoration-none">
                    <i class="fa fa-external-link-alt fs-9 me-1"></i> Open Standalone Window (Multi-Tab Safe)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Softphone JavaScript Controller -->
<script>
class TwilioSoftphoneController {
    constructor() {
        this.device = null;
        this.activeCall = null;
        this.incomingCall = null;
        this.callTimerInterval = null;
        this.callSeconds = 0;
        this.isMuted = false;
        this.isHeld = false;
        this.isReady = false;
        this.isInitializing = false;
    }

    async init() {
        if (this.isInitializing) return;
        if (this.isReady && this.device && this.device.state === 'registered') {
            this.setStatus('Ready (Online)', true);
            return true;
        }

        this.isInitializing = true;
        this.setStatus('Connecting...', false);

        try {
            // 1. Wait for Twilio Voice SDK script to be available
            let waitAttempts = 0;
            while ((typeof Twilio === 'undefined' || !Twilio.Device) && waitAttempts < 30) {
                await new Promise(r => setTimeout(r, 150));
                waitAttempts++;
            }

            if (typeof Twilio === 'undefined' || !Twilio.Device) {
                console.error('Twilio Voice JS SDK script failed to load.');
                this.setStatus('SDK Load Error', false);
                this.lastErrorMessage = 'Twilio Voice SDK script could not be loaded.';
                return false;
            }

            // 2. Fetch fresh JWT Token
            const res = await $.get("{{ route('plugins.twilio.token') }}");
            if (!res || !res.token) {
                this.setStatus('Not Configured', false);
                return false;
            }

            if (this.device) {
                try { this.device.destroy(); } catch(e){}
            }

            // 3. Initialize Twilio Device
            this.device = new Twilio.Device(res.token, {
                codecPreferences: ['opus', 'pcmu'],
                fakeLocalDTMF: true,
                enableRingingState: true
            });

            this.device.on('registered', () => {
                console.log('Twilio Device registered successfully for identity:', res.identity);
                this.isReady = true;
                this.setStatus('Ready (Online)', true);
            });

            this.device.on('unregistered', () => {
                console.log('Twilio Device unregistered');
                this.isReady = false;
                this.setStatus('Offline', false);
            });

            this.device.on('error', (err) => {
                console.error('Twilio Device Error:', err);
                this.isReady = false;
                this.lastErrorMessage = (err && (err.message || err.explanation)) ? (err.message || err.explanation) : 'Device registration error';
                this.setStatus('Error: ' + (err.code || 'Device Error'), false);
            });

            this.device.on('incoming', (call) => {
                this.handleIncoming(call);
            });

            this.device.on('tokenWillExpire', async () => {
                try {
                    const fresh = await $.get("{{ route('plugins.twilio.token') }}");
                    if (fresh && fresh.token) {
                        await this.device.updateToken(fresh.token);
                    }
                } catch(e) {}
            });

            await this.device.register();
            this.isReady = true;
            this.setStatus('Ready (Online)', true);
            return true;
        } catch (e) {
            console.error('Twilio WebRTC init failed:', e);
            this.isReady = false;
            this.lastErrorMessage = (e && e.responseJSON && e.responseJSON.message) ? e.responseJSON.message : ((e && e.message) ? e.message : 'Twilio settings not configured');
            this.setStatus((e && e.status === 422) ? 'Not Configured' : 'Offline', false);
            return false;
        } finally {
            this.isInitializing = false;
        }
    }

    setStatus(text, isOnline) {
        $('#twilioStatusText').text(text).removeClass('badge-light-success badge-light-danger').addClass(isOnline ? 'badge-light-success' : 'badge-light-danger');
        $('#twilioDeviceStatusDot').removeClass('offline').addClass(isOnline ? '' : 'offline');
    }

    toggleWidget() {
        const box = $('#twilioSoftphoneBox');
        box.toggle();
    }

    pressKey(num) {
        const input = $('#twilioDialerInput');
        input.val(input.val() + num);
        if (this.activeCall) {
            this.activeCall.sendDigits(num);
        }
    }

    backspace() {
        const input = $('#twilioDialerInput');
        input.val(input.val().slice(0, -1));
    }

    async makeCall(numberToDial, displayName = '') {
        const phone = (numberToDial || $('#twilioDialerInput').val()).trim();
        if (!phone) {
            Swal.fire('Error', 'Please enter a valid phone number with country code.', 'warning');
            return;
        }

        if (!this.device) {
            await this.init();
        }

        if (!this.device) {
            const errorDetails = this.lastErrorMessage || 'Please verify API Key SID, API Secret, and TwiML App SID in Settings > Plugins.';
            Swal.fire('Twilio Not Ready', errorDetails, 'warning');
            return;
        }

        $('#twilioSoftphoneBox').show();
        $('#twilioDialpadView').addClass('d-none');
        $('#twilioIncomingCallView').addClass('d-none');
        $('#twilioActiveCallView').removeClass('d-none');

        $('#twilioActiveCallName').text(displayName || 'Customer');
        $('#twilioActiveCallNumber').text(phone);
        $('#twilioActiveCallTimer').text('Calling...');

        try {
            const params = { To: phone };
            const call = await this.device.connect({ params });
            this.setupCallEvents(call);
        } catch (err) {
            console.error('Call Connect Failed:', err);
            this.endCallUI();
            Swal.fire('Call Failed', err.message || 'Could not connect call.', 'error');
        }
    }

    setupCallEvents(call) {
        this.activeCall = call;

        call.on('ringing', (hasEarlyMedia) => {
            $('#twilioActiveCallTimer').text('Ringing...');
        });

        call.on('accept', () => {
            $('#twilioActiveCallTimer').text('00:00');
            this.startTimer();
        });

        call.on('disconnect', () => {
            this.endCallUI();
        });

        call.on('error', (err) => {
            console.error('Active Call Error:', err);
            this.endCallUI();
            Swal.fire('Call Error', err.message || 'Call disconnected.', 'error');
        });
    }

    handleIncoming(call) {
        this.incomingCall = call;
        $('#twilioSoftphoneBox').show();
        $('#twilioDialpadView').addClass('d-none');
        $('#twilioActiveCallView').addClass('d-none');
        $('#twilioIncomingCallView').removeClass('d-none');

        const from = call.parameters?.From || 'Incoming Customer';
        $('#twilioIncomingFromNumber').text(from);

        call.on('disconnect', () => {
            this.endCallUI();
        });
        call.on('cancel', () => {
            this.endCallUI();
        });
    }

    acceptIncoming() {
        if (this.incomingCall) {
            $('#twilioIncomingCallView').addClass('d-none');
            $('#twilioActiveCallView').removeClass('d-none');
            $('#twilioActiveCallName').text('Customer');
            $('#twilioActiveCallNumber').text(this.incomingCall.parameters.From || '');
            this.incomingCall.accept();
            this.setupCallEvents(this.incomingCall);
            this.incomingCall = null;
        }
    }

    rejectIncoming() {
        if (this.incomingCall) {
            this.incomingCall.reject();
            this.incomingCall = null;
            this.endCallUI();
        }
    }

    toggleMute() {
        if (this.activeCall) {
            this.isMuted = !this.isMuted;
            this.activeCall.mute(this.isMuted);
            $('#twilioMuteBtn').toggleClass('bg-warning', this.isMuted);
        }
    }

    toggleHold() {
        if (this.activeCall) {
            this.isHeld = !this.isHeld;
            $('#twilioHoldBtn').toggleClass('bg-warning', this.isHeld);
        }
    }

    hangup() {
        if (this.activeCall) {
            this.activeCall.disconnect();
        }
        this.endCallUI();
    }

    startTimer() {
        this.callSeconds = 0;
        clearInterval(this.callTimerInterval);
        this.callTimerInterval = setInterval(() => {
            this.callSeconds++;
            const mins = String(Math.floor(this.callSeconds / 60)).padStart(2, '0');
            const secs = String(this.callSeconds % 60).padStart(2, '0');
            $('#twilioActiveCallTimer').text(`${mins}:${secs}`);
        }, 1000);
    }

    endCallUI() {
        clearInterval(this.callTimerInterval);
        this.activeCall = null;
        this.incomingCall = null;
        this.isMuted = false;
        this.isHeld = false;
        $('#twilioMuteBtn').removeClass('bg-warning');
        $('#twilioHoldBtn').removeClass('bg-warning');

        $('#twilioActiveCallView').addClass('d-none');
        $('#twilioIncomingCallView').addClass('d-none');
        $('#twilioDialpadView').removeClass('d-none');
    }

    openPopout() {
        const url = "{{ route('plugins.dialer.window') }}";
        window.open(url, 'AINSoftphonePopout', 'width=380,height=640,status=no,toolbar=no,menubar=no,location=no,resizable=yes');
    }
}

// Instantiate global softphone
window.twilioSoftphone = new TwilioSoftphoneController();

// Immediately trigger softphone init in background
window.twilioSoftphone.init();

// Seamless In-Page Navigation Engine (keeps call active without disconnecting)
async function seamlessNavigateTo(url) {
    try {
        if ($('#nprogress-bar').length === 0) {
            $('body').append('<div id="nprogress-bar" style="position:fixed;top:0;left:0;height:3px;background:#009ef7;width:0%;z-index:99999;transition:width 0.3s ease;box-shadow:0 0 10px #009ef7;"></div>');
        }
        $('#nprogress-bar').css('width', '40%').show();

        const response = await fetch(url);
        if (!response.ok) {
            window.location.href = url;
            return;
        }

        $('#nprogress-bar').css('width', '80%');
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

            // Execute scripts in the newly loaded page
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

            // Update active menu link in sidebar
            $('.menu-link').removeClass('active');
            $(`a[href="${url}"]`).addClass('active');

            // Trigger window load/resize/ready events
            $(document).trigger('ready');
            window.dispatchEvent(new Event('resize'));
        } else {
            window.location.href = url;
        }

        $('#nprogress-bar').css('width', '100%');
        setTimeout(() => $('#nprogress-bar').fadeOut(200).css('width', '0%'), 250);
    } catch (err) {
        console.error('Seamless navigation error, falling back:', err);
        window.location.href = url;
    }
}

// Guard against accidental page reload during an active call
window.addEventListener('beforeunload', function(e) {
    if (window.twilioSoftphone && window.twilioSoftphone.activeCall) {
        e.preventDefault();
        e.returnValue = 'You are currently on a live voice call. Navigating away will disconnect the call.';
        return e.returnValue;
    }
});

// Protect active call: seamlessly navigate in-page when clicking any internal link in the CRM
$(document).on('click', 'a[href]', function(e) {
    const href = $(this).attr('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || $(this).attr('target') === '_blank') {
        return;
    }

    if (window.twilioSoftphone && window.twilioSoftphone.activeCall) {
        e.preventDefault();
        seamlessNavigateTo(href);
    }
});

// Handle browser back/forward buttons during active call
window.addEventListener('popstate', function(e) {
    if (window.twilioSoftphone && window.twilioSoftphone.activeCall) {
        seamlessNavigateTo(window.location.href);
    }
});

// Auto-initialize on page load & continuous heartbeat to keep device Always Online
$(document).ready(function() {
    window.twilioSoftphone.init();

    // Auto reconnect heartbeat every 10 seconds if device drops offline
    setInterval(function() {
        if (window.twilioSoftphone && !window.twilioSoftphone.isReady && !window.twilioSoftphone.activeCall) {
            window.twilioSoftphone.init();
        }
    }, 10000);
});
</script>
@endonce
