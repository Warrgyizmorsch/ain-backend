@if(auth()->check())
<!-- Follow-up Real-Time Reminder Alert (Staff & Executive) -->
<div id="crm_followup_reminder_container" style="position: fixed; bottom: 24px; right: 24px; z-index: 100060; width: 380px; max-width: calc(100vw - 32px); display: none;">
    <div class="card shadow-lg border border-primary border-opacity-25 rounded-4 overflow-hidden" style="background: #ffffff; box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important;">
        <!-- Header -->
        <div class="card-header border-0 py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e1e2d 0%, #151521 100%);">
            <div class="d-flex align-items-center gap-2">
                <span class="symbol symbol-30px symbol-circle bg-danger bg-opacity-25 d-flex align-items-center justify-content-center text-danger">
                    <i class="fa fa-bell fa-shake text-danger fs-6"></i>
                </span>
                <div>
                    <div class="fw-bolder text-white fs-7">Follow-up Reminder!</div>
                    <div class="text-gray-400 fs-9" id="reminder_scheduled_badge">Scheduled Time</div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-icon btn-active-light-secondary text-gray-400 text-hover-white p-0" onclick="dismissActiveReminder()" title="Dismiss Reminder" style="width: 24px; height: 24px;">
                <i class="fa fa-times fs-7"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="card-body p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge badge-light-primary fw-bolder fs-8" id="reminder_order_id">UKS-0000</span>
                <span class="text-gray-700 fw-bold fs-8 text-truncate" id="reminder_client_name" style="max-width: 170px;">Client</span>
            </div>

            <div class="p-3 bg-light rounded-3 border border-gray-200 mb-3">
                <div class="text-gray-800 fs-7 lh-base text-break" id="reminder_message" style="max-height: 80px; overflow-y: auto;">
                    Followup note details...
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-primary fw-bold flex-grow-1 fs-8 py-2" id="reminder_btn_view" onclick="openRemindedLead()">
                    <i class="fa fa-external-link me-1"></i> Open Lead
                </button>
                <button type="button" class="btn btn-sm btn-light-warning fw-bold fs-8 py-2 px-3" onclick="snoozeActiveReminder(10)" title="Remind again in 10 minutes">
                    <i class="fa fa-clock-o me-1"></i> +10m
                </button>
                <button type="button" class="btn btn-sm btn-light-success fw-bold fs-8 py-2 px-3" onclick="markActiveReminderDone()" title="Mark this follow-up as done">
                    <i class="fa fa-check"></i> Done
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let currentReminder = null;
    let reminderCheckTimer = null;
    let audioCtx = null;

    // Pleasant notification chime using Web Audio API (Zero mp3 file dependencies)
    function playReminderChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            if (!audioCtx) audioCtx = new AudioContext();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            const now = audioCtx.currentTime;

            // Tone 1: High crisp bell note (D5 - 587.33 Hz)
            const osc1 = audioCtx.createOscillator();
            const gain1 = audioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, now);
            gain1.gain.setValueAtTime(0.25, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
            osc1.connect(gain1);
            gain1.connect(audioCtx.destination);
            osc1.start(now);
            osc1.stop(now + 0.5);

            // Tone 2: Harmonious upper note (A5 - 880 Hz)
            const osc2 = audioCtx.createOscillator();
            const gain2 = audioCtx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(880, now + 0.12);
            gain2.gain.setValueAtTime(0.3, now + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.7);
            osc2.connect(gain2);
            gain2.connect(audioCtx.destination);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.7);
        } catch (e) {
            // Audio policy silently handled
        }
    }

    // HTML5 Desktop Notification
    function showDesktopNotification(rem) {
        try {
            if (!("Notification" in window)) return;
            if (Notification.permission === "granted") {
                const n = new Notification("🔔 Follow-up Due: " + rem.order_id, {
                    body: (rem.client_name ? rem.client_name + " - " : "") + (rem.message || "Follow-up scheduled now."),
                    icon: "{{ asset('assets/media/avatars/assignment_logo.png') }}",
                });
                n.onclick = function() {
                    window.focus();
                    openRemindedLead();
                };
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission();
            }
        } catch (e) {}
    }

    function checkDueFollowupReminders() {
        $.ajax({
            url: "{{ route('lead.followups.due-reminders') }}",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.status && res.reminders && res.reminders.length > 0) {
                    const first = res.reminders[0];
                    if (!currentReminder || currentReminder.id !== first.id) {
                        currentReminder = first;
                        displayReminderCard(first);

                        const lastChimed = sessionStorage.getItem('last_chimed_reminder_' + first.id);
                        const nowTime = Date.now();
                        if (!lastChimed || (nowTime - parseInt(lastChimed, 10)) > 2 * 60 * 1000) {
                            sessionStorage.setItem('last_chimed_reminder_' + first.id, nowTime);
                            playReminderChime();
                            showDesktopNotification(first);
                        }
                    }
                } else {
                    if (currentReminder) {
                        $('#crm_followup_reminder_container').fadeOut(200);
                        currentReminder = null;
                    }
                }
            },
            error: function() {
                // Silently ignore background polling errors
            }
        });
    }

    function displayReminderCard(rem) {
        $('#reminder_order_id').text(rem.order_id);
        $('#reminder_client_name').text(rem.client_name || 'Client');
        $('#reminder_message').text(rem.message || 'No remarks provided.');
        const timeText = rem.followup_time !== 'All Day' ? (rem.followup_time + ' (Due Now)') : 'Today (Due Now)';
        $('#reminder_scheduled_badge').text(timeText);

        $('#crm_followup_reminder_container').fadeIn(300);
    }

    window.openRemindedLead = function() {
        if (!currentReminder) return;
        const leadId = currentReminder.lead_id;
        const orderId = currentReminder.order_id;
        const clientName = currentReminder.client_name;

        // If on the /lead page and drawer function exists
        if (typeof openLeadFollowupDrawer === 'function') {
            openLeadFollowupDrawer(leadId, orderId, clientName);
            $('#crm_followup_reminder_container').fadeOut(200);
        } else {
            // Navigate to lead page and open drawer
            window.location.href = "{{ url('/lead') }}?lead_id=" + leadId + "&open_followup=1";
        }
    };

    window.snoozeActiveReminder = function(minutes) {
        if (!currentReminder) return;
        const id = currentReminder.id;
        $('#crm_followup_reminder_container').fadeOut(200);

        $.ajax({
            url: "{{ url('/lead/followups') }}/" + id + "/snooze",
            type: 'POST',
            data: {
                minutes: minutes || 10,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                currentReminder = null;
                if (typeof Swal !== 'undefined' && Swal.fire) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Reminder snoozed for ' + (minutes || 10) + 'm',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    };

    window.dismissActiveReminder = function() {
        if (!currentReminder) return;
        const id = currentReminder.id;
        $('#crm_followup_reminder_container').fadeOut(200);

        $.ajax({
            url: "{{ url('/lead/followups') }}/" + id + "/dismiss",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                currentReminder = null;
            }
        });
    };

    window.markActiveReminderDone = function() {
        if (!currentReminder) return;
        const id = currentReminder.id;
        $('#crm_followup_reminder_container').fadeOut(200);

        $.ajax({
            url: "{{ url('/lead/followups') }}/" + id + "/done",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                currentReminder = null;
                if (typeof Swal !== 'undefined' && Swal.fire) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Follow-up marked as completed!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
                // Refresh if on lead page
                if (typeof refreshLeadTable === 'function') {
                    refreshLeadTable();
                }
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Initial check 5 seconds after page loads
        setTimeout(checkDueFollowupReminders, 5000);

        // Periodic check every 45 seconds
        reminderCheckTimer = setInterval(checkDueFollowupReminders, 45000);

        // Pre-request notification permission and unlock audio context on first interaction
        document.addEventListener('click', function reqPerm() {
            if ("Notification" in window && Notification.permission === "default") {
                Notification.requestPermission();
            }
            try {
                const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
                if (AudioCtxClass && !audioCtx) {
                    audioCtx = new AudioCtxClass();
                }
                if (audioCtx && audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
            } catch (e) {}
            document.removeEventListener('click', reqPerm);
        }, { once: true });
    });
})();
</script>
@endif
