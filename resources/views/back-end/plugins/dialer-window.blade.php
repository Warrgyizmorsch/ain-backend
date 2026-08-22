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
    <script src="{{ asset('assets/plugins/twilio/twilio.min.js') }}"></script>
    <script>
    if (typeof Twilio === 'undefined' || !Twilio.Device) {
        document.write('<script src="https://cdn.jsdelivr.net/npm/@twilio/voice-sdk@2.11.0/dist/twilio.min.js"><\/script>');
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
            <div id="callTimer" class="badge bg-success fs-6 px-3 py-2 mb-4">00:00</div>

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
    let seconds = 0;
    let isMuted = false;
    let isHeld = false;

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
                $('#incomingCallerNumber').text(call.parameters?.From || 'Incoming Caller');
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
        $('#callDisplayNumber').text(phone);
        $('#callTimer').text('Calling...');

        try {
            activeCall = await device.connect({ params: { To: phone } });
            activeCall.on('accept', () => startTimer());
            activeCall.on('disconnect', () => endCallUI());
            activeCall.on('error', (err) => {
                Swal.fire('Call Error', err.message || 'Call failed', 'error');
                endCallUI();
            });
        } catch (err) {
            Swal.fire('Call Failed', err.message || 'Could not connect', 'error');
            endCallUI();
        }
    }

    function acceptIncoming() {
        if (incomingCall) {
            incomingCall.accept();
            activeCall = incomingCall;
            incomingCall = null;
            $('#incomingView').addClass('d-none');
            $('#activeCallView').removeClass('d-none');
            $('#callDisplayName').text('Incoming Customer');
            $('#callDisplayNumber').text(activeCall.parameters?.From || '');
            startTimer();

            activeCall.on('disconnect', () => endCallUI());
        }
    }

    function rejectIncoming() {
        if (incomingCall) {
            incomingCall.reject();
            incomingCall = null;
            endCallUI();
        }
    }

    function toggleMute() {
        if (activeCall) {
            isMuted = !isMuted;
            activeCall.mute(isMuted);
            $('#muteBtn').toggleClass('btn-warning', isMuted).toggleClass('btn-dark', !isMuted);
        }
    }

    function toggleHold() {
        if (activeCall) {
            isHeld = !isHeld;
            $('#holdBtn').toggleClass('btn-warning', isHeld).toggleClass('btn-dark', !isHeld);
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
