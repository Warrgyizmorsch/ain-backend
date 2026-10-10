{{-- Global Email Auto-Sync & Real-Time Receiver across all CRM Pages --}}
<script>
(function () {
    let isGlobalSyncing = false;
    let lastGlobalSyncTime = 0;

    // Synthesized gentle notification chime via Web Audio API
    function playGlobalEmailChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            if (ctx.state === 'suspended') {
                ctx.resume();
            }
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.18, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
            osc.start();
            osc.stop(ctx.currentTime + 0.4);
        } catch (e) {
            // Audio policy or unsupported
        }
    }

    function updateHeaderBadge(badgeId, count) {
        const badge = document.getElementById(badgeId);
        if (!badge) return;
        const n = parseInt(count, 10) || 0;
        badge.textContent = n;
        if (n > 0) {
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    function runGlobalEmailSync() {
        // Skip if user is currently inside the standalone Emails inbox view (it has its own sync loop)
        const path = window.location.pathname;
        if (path.includes('/emails') && !path.includes('/emails/settings')) {
            return;
        }

        if (isGlobalSyncing) return;

        const now = Date.now();
        // If document is hidden, throttle to 45s, otherwise 25s
        const throttleLimit = document.hidden ? 45000 : 25000;
        if (now - lastGlobalSyncTime < throttleLimit) return;

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
        if (!token) return;

        isGlobalSyncing = true;
        lastGlobalSyncTime = now;

        fetch('{{ route("emails.sync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Sync status ' + res.status);
            return res.json();
        })
        .then(function (data) {
            if (!data) return;

            // Update badge counters if returned
            if (typeof data.client_unread !== 'undefined') {
                updateHeaderBadge('clientEmailHeaderBadge', data.client_unread);
            }
            if (typeof data.writer_unread !== 'undefined') {
                updateHeaderBadge('writerEmailHeaderBadge', data.writer_unread);
            }

            // If new incoming emails were fetched from IMAP
            if (data.synced_count && data.synced_count > 0) {
                playGlobalEmailChime();

                if (window.toastr) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 7000
                    };
                    toastr.info(
                        '📩 ' + data.synced_count + ' new email(s) received into CRM.',
                        'New Email Received'
                    );
                }
            }
        })
        .catch(function () {
            // Silently ignore background network/sync hiccups
        })
        .finally(function () {
            isGlobalSyncing = false;
        });
    }

    // Run first sync 3.5 seconds after page load
    setTimeout(runGlobalEmailSync, 3500);

    // Keep checking every 35 seconds across any open page
    setInterval(runGlobalEmailSync, 35000);

    // Re-check when user switches back to this tab
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            runGlobalEmailSync();
        }
    });
})();
</script>
