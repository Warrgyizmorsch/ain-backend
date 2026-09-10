<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIN Voice Softphone</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@twilio/voice-sdk@2.11.0/dist/twilio.min.js"></script>
    <script>
    if (typeof Twilio === 'undefined' || !Twilio.Device) {
        const fallbackScript = document.createElement('script');
        fallbackScript.src = "{{ asset('assets/plugins/twilio/twilio.min.js') }}";
        document.head.appendChild(fallbackScript);
    }
    </script>
    <style>
        body {
            background-color: #12121e;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            user-select: none;
            overflow-x: hidden;
        }
        .popout-container {
            max-width: 360px;
            margin: 0 auto;
            min-height: 100vh;
            display: flex;
            flex-column: column;
            justify-content: space-between;
            padding: 20px 16px;
        }
        .popout-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .dialer-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
            border-radius: 12px;
            padding: 12px;
            margin: 15px 0;
            width: 100%;
        }
        .dialer-input:focus {
            background: rgba(255,255,255,0.09);
            border-color: #009ef7;
            color: #fff;
            box-shadow: none;
        }
        .dialpad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }
        .dial-btn {
            background: #252538;
            border: 1px solid rgba(255,255,255,0.06);
            color: #fff;
            border-radius: 12px;
            height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .dial-btn:hover {
            background: #32324d;
            border-color: #009ef7;
            transform: translateY(-2px);
        }
        .dial-btn .sub {
            font-size: 9px;
            color: #8b8b9e;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: -2px;
        }
        .call-btn {
            background: #50cd89;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(80, 205, 137, 0.4);
            transition: all 0.2s ease;
        }
        .call-btn:hover {
            background: #47be7d;
            transform: scale(1.02);
        }
        .hangup-btn {
            background: #f1416c;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(241, 65, 108, 0.4);
        }
        .avatar-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #009ef7, #00539c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 15px auto;
        }
        .pulse-ring {
            animation: ringPulse 1.5s infinite;
        }
        @keyframes ringPulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 158, 247, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(0, 158, 247, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 158, 247, 0); }
        }
    </style>
</head>
<body>
    <div class="popout-container">
        <!-- Header -->
        <div class="popout-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa fa-phone-volume text-primary fs-5"></i>
                <span class="fw-bold fs-6">AIN Softphone</span>
            </div>
            <span id="deviceStatusBadge" class="badge bg-secondary fs-8">Connecting...</span>
        </div>

        <!-- DIALPAD VIEW -->
        <div id="dialerView">
            <input type="text" id="phoneInput" class="form-control dialer-input" placeholder="+Country Code Number">

            <div class="dialpad-grid">
                <button type="button" class="dial-btn" onclick="press('1')">1 <span class="sub">&nbsp;</span></button>
                <button type="button" class="dial-btn" onclick="press('2')">2 <span class="sub">ABC</span></button>
                <button type="button" class="dial-btn" onclick="press('3')">3 <span class="sub">DEF</span></button>
                <button type="button" class="dial-btn" onclick="press('4')">4 <span class="sub">GHI</span></button>
                <button type="button" class="dial-btn" onclick="press('5')">5 <span class="sub">JKL</span></button>
                <button type="button" class="dial-btn" onclick="press('6')">6 <span class="sub">MNO</span></button>
                <button type="button" class="dial-btn" onclick="press('7')">7 <span class="sub">PQRS</span></button>
                <button type="button" class="dial-btn" onclick="press('8')">8 <span class="sub">TUV</span></button>
                <button type="button" class="dial-btn" onclick="press('9')">9 <span class="sub">WXYZ</span></button>
                <button type="button" class="dial-btn" onclick="press('*')">* <span class="sub">&nbsp;</span></button>
                <button type="button" class="dial-btn" onclick="press('0')">0 <span class="sub">+</span></button>
                <button type="button" class="dial-btn" onclick="press('#')"># <span class="sub">&nbsp;</span></button>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary px-3" onclick="backspace()">
                    <i class="fa fa-backspace"></i>
                </button>
                <button type="button" class="call-btn flex-fill" onclick="makeCall()">
                    <i class="fa fa-phone"></i> Call Now
                </button>
            </div>
        </div>

        <!-- ACTIVE CALL VIEW -->
        <div id="activeCallView" class="d-none text-center py-4">
            <div class="avatar-circle pulse-ring">
                <i class="fa fa-user"></i>
            </div>
            <h4 id="callDisplayName" class="fw-bolder mb-1">Customer</h4>
            <div id="callDisplayNumber" class="text-muted fs-6 mb-2">--</div>
            <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                <div id="callTimer" class="badge bg-success fs-6 px-3 py-2">00:00</div>
                <span id="dialerHoldBadge" class="badge bg-warning text-dark fs-8 px-2 py-1 d-none"><i class="fa fa-pause me-1"></i> HOLD</span>
                <span id="dialerMuteBadge" class="badge bg-danger text-white fs-8 px-2 py-1 d-none"><i class="fa fa-microphone-slash me-1"></i> MUTED</span>
            </div>

            <div class="d-flex justify-content-center gap-3 mb-4">
                <button type="button" id="muteBtn" class="btn btn-dark p-3 rounded-circle" onclick="toggleMute()" title="Mute">
                    <i class="fa fa-microphone-slash fs-5"></i>
                </button>
                <button type="button" id="holdBtn" class="btn btn-dark p-3 rounded-circle" onclick="toggleHold()" title="Hold">
                    <i class="fa fa-pause fs-5"></i>
                </button>
            </div>

            <button type="button" class="hangup-btn" onclick="hangup()">
                <i class="fa fa-phone-slash"></i> End Call
            </button>
        </div>

        <!-- INCOMING CALL VIEW -->
        <div id="incomingView" class="d-none text-center py-4">
            <div class="avatar-circle pulse-ring bg-warning">
                <i class="fa fa-phone-volume text-dark"></i>
            </div>
            <h4 class="fw-bolder text-warning mb-1">Incoming Call</h4>
            <div id="incomingCallerNumber" class="text-white fs-5 fw-bold mb-4">+...</div>

            <div class="d-flex gap-3">
                <button type="button" class="btn btn-danger flex-fill py-3" onclick="rejectIncoming()">
                    <i class="fa fa-phone-slash me-1"></i> Reject
                </button>
                <button type="button" class="btn btn-success flex-fill py-3" onclick="acceptIncoming()">
                    <i class="fa fa-phone me-1"></i> Accept
                </button>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center text-muted fs-8 pt-3 border-top border-secondary">
            Keep this window open to receive & make calls seamlessly across CRM.
        </div>
    </div>

    <script>
    let device = null;
    let activeCall = null;
    let incomingCall = null;
    let timerInterval = null;
    let isMuted = false;
    let isHeld = false;
    let seconds = 0;

    const isSuperAdmin = {{ Auth::check() && (int) Auth::user()->role_id === 1 ? 'true' : 'false' }};

    function crmMaskPhone(phone) {
        if (!phone || typeof phone !== 'string') return phone || '';
        const str = phone.trim();
        if (!str || str.length < 5) return str;
        if (isSuperAdmin) return str;

        let prefix = '';
        let digits = str;
        if (str.startsWith('+')) {
            const ccMatch = str.match(/^(\+\d{1,3})/);
            if (ccMatch) {
                prefix = ccMatch[1] + ' ';
                digits = str.slice(ccMatch[1].length);
            }
        }
        if (digits.length <= 4) return str;
        const visible_start = digits.slice(0, 2);
        const visible_end   = digits.slice(-2);
        const masked_mid    = '*'.repeat(Math.max(4, digits.length - 4));
        return prefix + visible_start + masked_mid + visible_end;
    }

    function formatTwilioCallError(err, phoneNumber) {
        if (!err) return 'Call disconnected unexpectedly.';
        const code = err.code || err.statusCode || '';
        const msg = String(err.message || '');

        if (code === 31005 || msg.includes('31005') || msg.includes('HANGUP')) {
            return `The destination phone number <strong>${phoneNumber || 'dialed'}</strong> is invalid, disconnected, or rejected by the telecom carrier network.<br><br><span class="badge bg-danger fs-8">Twilio Error 31005 / 13224 (Invalid Phone Number)</span><br><br><small class="text-muted">Please verify that the customer's phone number is active and reachable.</small>`;
        }
        if (code === 21211 || code === 13224) {
            return `The phone number format is invalid or does not exist on the telecom network.`;
        }
        if (code === 21408) {
            return `Calls to this country code are restricted in your Twilio Voice Geographic Permissions.`;
        }
        if (code === 31000 || code === 31002) {
            return `Unable to connect to the Twilio voice gateway.<br><br><small class="text-muted">Please check your internet connection or try dialing again.</small>`;
        }
        if (code === 31008) {
            return `Call was cancelled before it could be connected.`;
        }
        return msg || 'Call could not be completed.';
    }

    async function initDevice() {
        try {
            let attempts = 0;
            while ((typeof Twilio === 'undefined' || !Twilio.Device) && attempts < 25) {
                await new Promise(r => setTimeout(r, 200));
                attempts++;
            }

            const res = await $.get("{{ route('plugins.twilio.token') }}");
            if (!res || !res.token) {
                $('#deviceStatusBadge').text('Not Configured').removeClass('bg-success').addClass('bg-danger');
                return;
            }

            device = new Twilio.Device(res.token, {
                codecPreferences: ['opus', 'pcmu'],
                fakeLocalDTMF: true,
                enableRingingState: true
            });

            device.on('registered', () => {
                $('#deviceStatusBadge').text('Online').removeClass('bg-secondary bg-danger').addClass('bg-success');
            });

            device.on('unregistered', () => {
                $('#deviceStatusBadge').text('Offline').removeClass('bg-success').addClass('bg-danger');
            });

            device.on('error', (err) => {
                console.error('Twilio Error:', err);
                $('#deviceStatusBadge').text('Error').removeClass('bg-success').addClass('bg-danger');
            });

            device.on('incoming', (call) => {
                incomingCall = call;
                $('#incomingCallerNumber').text(crmMaskPhone(call.parameters?.From || 'Incoming Caller'));
                $('#dialerView').addClass('d-none');
                $('#activeCallView').addClass('d-none');
                $('#incomingView').removeClass('d-none');

                call.on('disconnect', () => endCallUI());
                call.on('cancel', () => endCallUI());
            });

            await device.register();
        } catch (e) {
            console.error('Init failed:', e);
            $('#deviceStatusBadge').text('Offline').removeClass('bg-success').addClass('bg-danger');
        }
    }

    function press(num) {
        const inp = $('#phoneInput');
        inp.val(inp.val() + num);
        if (activeCall) activeCall.sendDigits(num);
    }

    function backspace() {
        const inp = $('#phoneInput');
        inp.val(inp.val().slice(0, -1));
    }

    let currentCallData = null;
    let incomingCallData = null;

    function postCallLog(data) {
        $.ajax({
            url: "{{ route('plugins.twilio.log.call') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            dataType: 'json',
            success: function(res) {
                console.log('Dialer window call log synced:', res);
            },
            error: function(err) {
                console.warn('Dialer window call log sync error:', err);
            }
        });
    }

    async function makeCall() {
        const phone = $('#phoneInput').val().trim();
        if (!phone) {
            Swal.fire('Error', 'Please enter a valid phone number with country code.', 'warning');
            return;
        }
        if (!device) {
            Swal.fire('Error', 'Device is not ready. Reconnecting...', 'warning');
            await initDevice();
            return;
        }

        $('#dialerView').addClass('d-none');
        $('#incomingView').addClass('d-none');
        $('#activeCallView').removeClass('d-none');
        $('#callDisplayName').text('Customer');
        $('#callDisplayNumber').text(crmMaskPhone(phone));
        $('#callTimer').text('Calling...');

        currentCallData = {
            direction: 'outbound',
            to_number: phone,
            customer_name: 'Customer',
            started_at: new Date().toISOString(),
            call_sid: null
        };

        try {
            activeCall = await device.connect({ params: { To: phone } });

            activeCall.on('accept', () => {
                startTimer();
                if (currentCallData) {
                    currentCallData.call_sid = activeCall.parameters?.CallSid || null;
                    postCallLog({
                        call_sid: currentCallData.call_sid,
                        direction: 'outbound',
                        to_number: currentCallData.to_number,
                        customer_name: currentCallData.customer_name,
                        status: 'in-progress',
                        started_at: currentCallData.started_at
                    });
                }
            });

            activeCall.on('disconnect', () => {
                const dur = seconds || 0;
                const finalStatus = dur > 0 ? 'completed' : 'no-answer';
                postCallLog({
                    call_sid: currentCallData?.call_sid || activeCall.parameters?.CallSid || null,
                    direction: 'outbound',
                    to_number: currentCallData?.to_number || phone,
                    customer_name: currentCallData?.customer_name || 'Customer',
                    status: finalStatus,
                    duration: dur,
                    started_at: currentCallData?.started_at,
                    ended_at: new Date().toISOString()
                });
                endCallUI();
            });

            activeCall.on('error', (err) => {
                postCallLog({
                    call_sid: currentCallData?.call_sid || null,
                    direction: 'outbound',
                    to_number: currentCallData?.to_number || phone,
                    customer_name: currentCallData?.customer_name || 'Customer',
                    status: 'failed',
                    duration: seconds || 0,
                    started_at: currentCallData?.started_at,
                    ended_at: new Date().toISOString()
                });
                Swal.fire({
                    title: 'Call Unsuccessful',
                    html: formatTwilioCallError(err, currentCallData?.to_number || phone),
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                endCallUI();
            });
        } catch (err) {
            postCallLog({
                direction: 'outbound',
                to_number: phone,
                customer_name: 'Customer',
                status: 'failed',
                duration: 0,
                started_at: currentCallData?.started_at,
                ended_at: new Date().toISOString()
            });
            Swal.fire({
                title: 'Call Unsuccessful',
                html: formatTwilioCallError(err, phone),
                icon: 'error',
                confirmButtonText: 'OK'
            });
            endCallUI();
        }
    }

    function acceptIncoming() {
        if (incomingCall) {
            incomingCallData.answered = true;
            currentCallData = { ...incomingCallData };
            incomingCall.accept();
            activeCall = incomingCall;
            incomingCall = null;

            $('#incomingView').addClass('d-none');
            $('#activeCallView').removeClass('d-none');
            $('#callDisplayName').text('Incoming Customer');
            $('#callDisplayNumber').text(crmMaskPhone(activeCall.parameters?.From || ''));
            startTimer();

            postCallLog({
                call_sid: currentCallData.call_sid,
                direction: 'inbound',
                from_number: currentCallData.from_number,
                customer_name: 'Incoming Customer',
                status: 'in-progress',
                started_at: currentCallData.started_at
            });

            activeCall.on('disconnect', () => {
                const dur = seconds || 0;
                postCallLog({
                    call_sid: currentCallData?.call_sid || activeCall.parameters?.CallSid || null,
                    direction: 'inbound',
                    from_number: currentCallData?.from_number || '',
                    customer_name: 'Incoming Customer',
                    status: 'completed',
                    duration: dur,
                    started_at: currentCallData?.started_at,
                    ended_at: new Date().toISOString()
                });
                endCallUI();
            });
        }
    }

    function rejectIncoming() {
        if (incomingCall) {
            postCallLog({
                call_sid: incomingCallData?.call_sid || incomingCall.parameters?.CallSid,
                direction: 'inbound',
                from_number: incomingCallData?.from_number || incomingCall.parameters?.From,
                customer_name: 'Incoming Caller',
                status: 'missed',
                duration: 0,
                started_at: incomingCallData?.started_at,
                ended_at: new Date().toISOString()
            });
            incomingCall.reject();
            incomingCall = null;
            endCallUI();
        }
    }

    function toggleMute() {
        if (activeCall) {
            isMuted = !isMuted;
            const muteMic = isMuted || isHeld;
            if (typeof activeCall.mute === 'function') {
                activeCall.mute(muteMic);
            }
            $('#muteBtn').toggleClass('btn-warning text-dark', isMuted).toggleClass('btn-dark', !isMuted);
            $('#dialerMuteBadge').toggleClass('d-none', !isMuted);
        }
    }

    function toggleHold() {
        if (activeCall) {
            isHeld = !isHeld;
            const muteMic = isHeld || isMuted;
            if (typeof activeCall.mute === 'function') {
                activeCall.mute(muteMic);
            }
            try {
                if (activeCall.getRemoteStream && activeCall.getRemoteStream()) {
                    activeCall.getRemoteStream().getAudioTracks().forEach(track => {
                        track.enabled = !isHeld;
                    });
                }
            } catch(e) {
                console.warn('Hold remote track toggle error:', e);
            }
            $('#holdBtn').toggleClass('btn-warning text-dark', isHeld).toggleClass('btn-dark', !isHeld);
            $('#dialerHoldBadge').toggleClass('d-none', !isHeld);
        }
    }

    function hangup() {
        if (activeCall) activeCall.disconnect();
        endCallUI();
    }

    function startTimer() {
        seconds = 0;
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            seconds++;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            $('#callTimer').text(`${m}:${s}`);
        }, 1000);
    }

    function endCallUI() {
        clearInterval(timerInterval);
        activeCall = null;
        incomingCall = null;
        isMuted = false;
        isHeld = false;
        $('#muteBtn').removeClass('btn-warning text-dark').addClass('btn-dark');
        $('#holdBtn').removeClass('btn-warning text-dark').addClass('btn-dark');
        $('#dialerMuteBadge').addClass('d-none');
        $('#dialerHoldBadge').addClass('d-none');

        $('#activeCallView').addClass('d-none');
        $('#incomingView').addClass('d-none');
        $('#dialerView').removeClass('d-none');
    }

    window.addEventListener('beforeunload', (e) => {
        if (activeCall) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    $(document).ready(() => initDevice());
    </script>
</body>
</html>
