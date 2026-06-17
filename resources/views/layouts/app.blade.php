<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIN → CRM Portal</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Base Href (Optional) --}}
    <base href="{{ url('/') }}/">

    {{-- Styles --}}
    @include('layouts.css')

    {{-- Page-specific head content --}}
    @stack('head')
</head>

<body 
    id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px; --kt-toolbar-height-tablet-and-mobile:55px"
>
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            
            {{-- Sidebar --}}
            @include('layouts.aside')

            {{-- Main Wrapper --}}
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                
                {{-- Header --}}
                @include('layouts.header')

                {{-- Flash Messages --}}
                @include('layouts.flash')

                {{-- Page Content --}}
                <main class="content flex-column-fluid">
                    @yield('content')
                </main>

            </div>
        </div>
    </div>
    <!-- export-preloader start -->
    <div id="export-preloader" style="display:none; position:fixed; top:15px; right:20px; z-index:9999;">
        <span style="
            display:inline-block;
            width:10px;
            height:10px;
            background-color:red;
            border-radius:50%;
            animation: blink 1s infinite;
        "></span>
    </div>

    <style>
    @keyframes blink {
        0%   { opacity: 1; }
        50%  { opacity: 0; }
        100% { opacity: 1; }
    }
    </style>
    <!-- export-preloader end -->

    {{-- Scripts --}}
    @include('layouts.js')

    @if(auth()->check() && in_array(auth()->user()->role_id, [4, 9]))
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const channel = new BroadcastChannel('revoke_payment_alert_channel');

        let activeExpiredModalPaymentId = null;
        let warningShown = {};
        let snoozeTimers = {};

        function formatTime(seconds) {
            if (seconds <= 0) return '00:00:00';

            const d = Math.floor(seconds / 86400);
            seconds %= 86400;

            const h = Math.floor(seconds / 3600);
            seconds %= 3600;

            const m = Math.floor(seconds / 60);
            const s = seconds % 60;

            return `${d}d ${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }

        function updateCountdownBadges(alerts) {
            alerts.forEach(item => {
                const badge = document.querySelector(`.revoke-countdown-badge[data-payment-id="${item.payment_id}"]`);

                if (!badge) return;

                badge.innerText = formatTime(item.seconds_left);

                if (item.seconds_left <= 0) {
                    badge.className = 'badge badge-light-danger revoke-countdown-badge';
                } else if (item.seconds_left <= 1800) {
                    badge.className = 'badge badge-light-warning revoke-countdown-badge';
                } else {
                    badge.className = 'badge badge-light-success revoke-countdown-badge';
                }
            });
        }

        function requestExtension(paymentId, comment) {
            return fetch(`/revoke-payments/request-extension/${paymentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ comment: comment })
            }).then(res => res.json());
        }

        function showWarningPopup(item, minutes) {
            const key = `payment_${item.payment_id}_${minutes}`;

            if (warningShown[key]) return;
            warningShown[key] = true;

            channel.postMessage({
                type: 'warning',
                paymentId: item.payment_id,
                minutes: minutes
            });

            Swal.fire({
                title: 'Revoke Payment Deadline Alert',
                html: `
                    <div class="text-start">
                        <p class="fw-bold text-danger">
                            The revoke payment deadline is approaching. Only ${minutes} minutes remaining!
                        </p>
                        <p><b>Order ID:</b> ${item.order_id ?? 'N/A'}</p>
                        <p><b>Amount:</b> £${item.amount}</p>
                    </div>
                `,
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true,
                confirmButtonText: 'Request Extension',
                cancelButtonText: 'Dismiss',
                confirmButtonColor: '#009ef7',
                cancelButtonColor: '#6c757d'
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Request Deadline Extension',
                        text: 'Enter a comment/reason for the extension request:',
                        input: 'textarea',
                        inputPlaceholder: 'Enter reason here...',
                        showCancelButton: true,
                        confirmButtonText: 'Send Request',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#009ef7',
                        preConfirm: (value) => {
                            if (!value || value.trim() === "") {
                                Swal.showValidationMessage('Comment is required');
                                return false;
                            }
                            return value;
                        }
                    }).then(commentResult => {
                        if (commentResult.isConfirmed) {
                            requestExtension(item.payment_id, commentResult.value).then((res) => {
                                if (res && res.success) {
                                    Swal.fire('Request Sent', res.message || 'Extension request sent to Admin.', 'success');
                                    channel.postMessage({ type: 'refresh' });
                                } else {
                                    Swal.fire('Error', (res && res.error) || 'Failed to send request.', 'error');
                                }
                            });
                        }
                    });
                }
            });
        }

        function showExpiredModal(item) {
            if (item.extension_status === 'pending') return;
            if (activeExpiredModalPaymentId === item.payment_id) return;

            const snoozeUntil = localStorage.getItem(`revoke_snooze_${item.payment_id}`);

            if (snoozeUntil && Date.now() < parseInt(snoozeUntil)) {
                return;
            }

            activeExpiredModalPaymentId = item.payment_id;

            channel.postMessage({
                type: 'expired',
                paymentId: item.payment_id
            });

            Swal.fire({
                title: 'Revoke Payment Deadline Expired',
                html: `
                    <div class="text-start">
                        <p class="fw-bold text-danger">
                            This revoke payment deadline has expired. You must take action.
                        </p>
                        <p><b>Order ID:</b> ${item.order_id ?? 'N/A'}</p>
                        <p><b>Amount:</b> £${item.amount}</p>
                    </div>
                `,
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Update Payment',
                denyButtonText: 'Request Admin Extension',
                cancelButtonText: 'Snooze',
                confirmButtonColor: '#50cd89',
                denyButtonColor: '#009ef7',
                cancelButtonColor: '#ffc700'
            }).then(result => {
                activeExpiredModalPaymentId = null;

                if (result.isConfirmed) {
                    window.location.href = `/orders/payment/${item.order_numeric_id}/${item.payment_id}`;
                }

                if (result.isDenied) {
                    if (item.extension_status === 'rejected') {
                        Swal.fire('Rejected', 'Admin has rejected the extension request. Please update payment or snooze.', 'error');
                        showExpiredModal(item);
                        return;
                    }

                    Swal.fire({
                        title: 'Request Deadline Extension',
                        text: 'Enter a comment/reason for the extension request:',
                        input: 'textarea',
                        inputPlaceholder: 'Enter reason here...',
                        showCancelButton: true,
                        confirmButtonText: 'Send Request',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#009ef7',
                        preConfirm: (value) => {
                            if (!value || value.trim() === "") {
                                Swal.showValidationMessage('Comment is required');
                                return false;
                            }
                            return value;
                        }
                    }).then(commentResult => {
                        if (commentResult.isConfirmed) {
                            requestExtension(item.payment_id, commentResult.value).then((res) => {
                                if (res && res.success) {
                                    Swal.fire('Request Sent', res.message || 'Extension request sent to Admin.', 'success');
                                    channel.postMessage({ type: 'refresh' });
                                } else {
                                    Swal.fire('Error', (res && res.error) || 'Failed to send request.', 'error');
                                    showExpiredModal(item);
                                }
                            }).catch(err => {
                                Swal.fire('Error', 'An error occurred while sending the request.', 'error');
                                showExpiredModal(item);
                            });
                        } else {
                            showExpiredModal(item);
                        }
                    });
                }

                if (result.dismiss === Swal.DismissReason.cancel) {
                    const snoozeTime = Date.now() + (5 * 60 * 1000);
                    localStorage.setItem(`revoke_snooze_${item.payment_id}`, snoozeTime);

                    setTimeout(() => {
                        showExpiredModal(item);
                    }, 5 * 60 * 1000);
                }
            });
        }

        function checkRevokeAlerts() {
            fetch(`{{ route('revoke.alerts.active') }}`)
                .then(res => res.ok ? res.json() : [])
                .then(alerts => {
                    if (!Array.isArray(alerts)) return;

                    updateCountdownBadges(alerts);

                    if (document.hidden) return;

                    alerts.forEach(item => {
                        if (item.seconds_left <= 0) {
                            showExpiredModal(item);
                            return;
                        }

                        if (item.seconds_left <= 1800 && item.seconds_left > 1740) {
                            showWarningPopup(item, 30);
                        }

                        if (item.seconds_left <= 600 && item.seconds_left > 540) {
                            showWarningPopup(item, 10);
                        }
                    });
                })
                .catch(() => {});
        }

        channel.onmessage = function (event) {
            const data = event.data;

            if (data.type === 'refresh') {
                checkRevokeAlerts();
            }
        };

        setInterval(checkRevokeAlerts, 15000);
        checkRevokeAlerts();
    });
    </script>
    @endif

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        function formatCountdown(seconds) {
            if (seconds <= 0) {
                return "00:00:00";
            }

            let days = Math.floor(seconds / 86400);
            seconds = seconds % 86400;

            let hours = Math.floor(seconds / 3600);
            seconds = seconds % 3600;

            let minutes = Math.floor(seconds / 60);
            let secs = seconds % 60;

            return days + "d " +
                String(hours).padStart(2, "0") + ":" +
                String(minutes).padStart(2, "0") + ":" +
                String(secs).padStart(2, "0");
        }

        function updateRevokeCountdowns() {
            document.querySelectorAll(".revoke-countdown-badge").forEach(function (badge) {
                let deadline = badge.getAttribute("data-deadline");

                if (!deadline) {
                    badge.innerText = "Deadline not set";
                    return;
                }

                let deadlineTime = new Date(deadline).getTime();
                let now = new Date().getTime();

                let diffSeconds = Math.floor((deadlineTime - now) / 1000);

                badge.innerText = formatCountdown(diffSeconds);

                badge.classList.remove(
                    "badge-light-success",
                    "badge-light-warning",
                    "badge-light-danger"
                );

                if (diffSeconds <= 0) {
                    badge.classList.add("badge-light-danger");
                } else if (diffSeconds <= 1800) {
                    badge.classList.add("badge-light-warning");
                } else {
                    badge.classList.add("badge-light-success");
                }
            });
        }

        updateRevokeCountdowns();
        setInterval(updateRevokeCountdowns, 1000);
    });
    </script>

    @if(auth()->check() && auth()->user()->role_id == 1)
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const channel = new BroadcastChannel('revoke_payment_alert_channel');
        let currentRequestId = null;

        function approveRequest(requestId) {
            return fetch(`/admin/revoke-extension-approve/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(res => res.json());
        }

        function rejectRequest(requestId) {
            return fetch(`/admin/revoke-extension-reject/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(res => res.json());
        }

        function checkAdminRequests() {
            if (document.hidden) return;
            fetch(`{{ route('admin.revoke.extension.requests') }}`)
                .then(res => res.ok ? res.json() : [])
                .then(requests => {
                    if (!Array.isArray(requests) || !requests.length) return;
                    const req = requests[0];
                    if (currentRequestId === req.id) return;
                    currentRequestId = req.id;

                    Swal.fire({
                        title: 'Revoke Extension Request',
                        html: `
                            <div class="text-start">
                                <p><b>Order ID:</b> ${req.order_id ?? 'N/A'}</p>
                                <p><b>Amount:</b> £${req.paid_amount}</p>
                                <p><b>Requested By:</b> ${req.requested_by ?? 'N/A'}</p>
                                <p><b>Reason / Comment:</b> <br><span class="text-muted">${req.admin_note ?? 'No reason provided.'}</span></p>
                            </div>
                        `,
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCancelButton: true,
                        confirmButtonText: 'Approve',
                        cancelButtonText: 'Reject',
                        confirmButtonColor: '#50cd89',
                        cancelButtonColor: '#f1416c'
                    }).then(result => {
                        if (result.isConfirmed) {
                            approveRequest(req.id).then((response) => {

                                console.log('Approve Response:', response);

                                if (!response.success) {
                                    Swal.fire(
                                        'Error',
                                        response.message || 'Approval failed.',
                                        'error'
                                    );

                                    currentRequestId = null;
                                    return;
                                }

                                channel.postMessage({ type: 'refresh' });
                                currentRequestId = null;
                                Swal.fire(
                                    'Approved',
                                    response.message || 'Deadline extended by 3 days.',
                                    'success'
                                ).then(() => {
                                    checkAdminRequests();
                                });
                            });

                            return;
                        }

                        if (result.dismiss === Swal.DismissReason.cancel) {
                            rejectRequest(req.id).then((response) => {

                                console.log('Reject Response:', response);

                                if (!response.success) {
                                    Swal.fire(
                                        'Error',
                                        response.message || 'Reject failed.',
                                        'error'
                                    );

                                    currentRequestId = null;
                                    return;
                                }

                                channel.postMessage({ type: 'refresh' });

                                currentRequestId = null;

                                Swal.fire(
                                    'Rejected',
                                    response.message || 'Extension request rejected.',
                                    'error'
                                ).then(() => {
                                    checkAdminRequests();
                                });
                            });

                            return;
                        }

                        currentRequestId = null;
                    });
                })
                .catch(() => {});
        }

        channel.onmessage = function (event) {
            const data = event.data;
            if (data.type === 'refresh') {
                checkAdminRequests();
            }
        };

        setInterval(checkAdminRequests, 15000);
        setTimeout(checkAdminRequests, 3000);
    });
    </script>
    @endif

    {{-- Page-specific scripts --}}
    @stack('scripts')
    <!-- <script>
        // Run on every page
        document.addEventListener("DOMContentLoaded", function () {
            const checkExportStatus = () => {
                const exportStatus = localStorage.getItem("exportStatus");

                if (exportStatus === "pending") {
                    fetch("/lead/export/status")
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === "ready") {
                                localStorage.removeItem("exportStatus"); // clear flag

                                Swal.fire({
                                    title: 'Export Ready!',
                                    text: 'Do you want to download the file?',
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, download it',
                                    cancelButtonText: 'No',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = data.url;
                                    }
                                });
                            }
                        });
                }
            };

            // Check every 5 seconds
            setInterval(checkExportStatus, 5000);
        });
    </script> -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const preloader = document.getElementById('export-preloader');

            const checkExportStatus = (type) => {
                const statusKey = `${type}ExportStatus`;

                if (localStorage.getItem(statusKey) === "pending") {
                    preloader.style.display = 'block'; // show dot
                    fetch(`/${type}/export/status`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === "ready") {
                                localStorage.removeItem(statusKey); // clear the flag
                                preloader.style.display = 'none'; // hide dot

                                Swal.fire({
                                    title: `${type.charAt(0).toUpperCase() + type.slice(1)} Export Ready!`,
                                    text: 'Do you want to download the file?',
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, download it',
                                    cancelButtonText: 'No',
                                }).then((result) => {
                                    if (result.isConfirmed && data.url) {
                                        window.location.href = data.url;
                                    }
                                });
                            }
                        });
                }
            };

            setInterval(() => {
                checkExportStatus('lead');
                checkExportStatus('order');
            }, 5000);
        });
    </script>

    <script>
        window.currentAlertOrder = null;
        const canShowDeadlinePopup = {{ in_array(auth()->user()->role_id, [1, 4, 9]) ? 'true' : 'false' }};

        const CHECK_INTERVAL = 60000; // 🔥 increased to 1 min (less load)
        const SNOOZE_MINUTES = 5;

        // -------------------------------
        // 📦 LOAD STATUS LIST (CACHED)
        // -------------------------------
        window.statusList = [];

        function loadStatuses() {

            const cached = localStorage.getItem('status_list');

            if (cached) {
                window.statusList = JSON.parse(cached);
                return Promise.resolve();
            }

            return $.get('/orders/statuses/list')
                .done(function (data) {
                    window.statusList = data || [];
                    localStorage.setItem('status_list', JSON.stringify(data));
                })
                .fail(function () {
                    console.warn('Failed to load statuses');
                });
        }

        // -------------------------------
        // 🔑 STORAGE HELPERS
        // -------------------------------
        function getSnoozeKey(orderId) {
            return 'snooze_order_' + orderId;
        }

        function getForceKey(orderId) {
            return 'force_status_' + orderId;
        }

        function isSnoozed(orderId) {
            const snoozeUntil = localStorage.getItem(getSnoozeKey(orderId));
            if (!snoozeUntil) return false;

            const snoozeTime = parseInt(snoozeUntil);

            if (Date.now() >= snoozeTime) {
                localStorage.removeItem(getSnoozeKey(orderId));
                return false;
            }

            return true;
        }

        function setSnooze(orderId) {
            const time = Date.now() + (SNOOZE_MINUTES * 60 * 1000);
            localStorage.setItem(getSnoozeKey(orderId), time);
        }

        function setForceStatus(orderId) {
            localStorage.setItem(getForceKey(orderId), 'true');
        }

        function clearForceStatus(orderId) {
            localStorage.removeItem(getForceKey(orderId));
        }

        function hasForceStatus(orderId) {
            return localStorage.getItem(getForceKey(orderId)) === 'true';
        }

        // -------------------------------
        // 🧠 SINGLE TAB CONTROL
        // -------------------------------
        function isPrimaryTab() {
            const now = Date.now();
            const key = 'urgent_orders_last_check';
            const last = localStorage.getItem(key);

            if (!last || (now - last) > (CHECK_INTERVAL + 5000)) {
                localStorage.setItem(key, now);
                return true;
            }

            return false;
        }

        // -------------------------------
        // 🚀 MAIN CHECK
        // -------------------------------
        function checkUrgentOrders() {

            if (!canShowDeadlinePopup) return;
            if (document.hidden) return;
            if (window.currentAlertOrder !== null) return;
            if (!isPrimaryTab()) return;

            $.get('/orders/urgent-orders')
                .done(function (orders) {

                    if (!orders || !orders.length) return;

                    for (let order of orders) {

                        if (hasForceStatus(order.id)) {
                            forceStatusUpdate(order.id);
                            return;
                        }

                        if (isSnoozed(order.id)) continue;

                        showUrgentModal(order);
                        return;
                    }
                })
                .fail(function () {
                    console.warn('Urgent order check failed');
                });
        }

        // -------------------------------
        // 🔔 ALERT MODAL
        // -------------------------------
        function showUrgentModal(order) {

            window.currentAlertOrder = order.id;

            Swal.fire({
                title: '⏰ Deadline Alert!',
                html: `<b>Order ID:</b> ${order.order_id}<br><br>Less than 30 minutes left!`,
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true,
                confirmButtonText: 'Done',
                cancelButtonText: 'Snooze (5 min)'
            }).then((result) => {

                window.currentAlertOrder = null;

                if (result.isConfirmed) {
                    setForceStatus(order.id);
                    forceStatusUpdate(order.id);
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    setSnooze(order.id);
                }
            });
        }

        // -------------------------------
        // 🔒 FORCE STATUS UPDATE
        // -------------------------------
        function forceStatusUpdate(orderId) {

            if (!canShowDeadlinePopup) return;
            window.currentAlertOrder = orderId;

            if (!window.statusList.length) {
                loadStatuses().then(() => {
                    forceStatusUpdate(orderId);
                });
                return;
            }

            let statusOptions = {};

            window.statusList.forEach(item => {
                statusOptions[item.status] = item.status;
            });

            Swal.fire({
                title: '⚠️ Update Status (Mandatory)',
                text: 'You must update the status before continuing',
                icon: 'info',
                input: 'select',
                inputOptions: statusOptions,
                inputPlaceholder: 'Select status',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: false,
                confirmButtonText: 'Update Status',

                preConfirm: (selectedStatus) => {

                    if (!selectedStatus) {
                        Swal.showValidationMessage('Status is required');
                        return false;
                    }

                    return $.ajax({
                        type: 'POST',
                        url: 'update_status',
                        data: {
                            orderId: orderId,
                            status: selectedStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then(response => {

                        if (response.warning) {
                            throw new Error(response.warning);
                        }

                        return response;

                    }).catch(error => {
                        Swal.showValidationMessage(error.message || 'Update failed');
                    });
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    clearForceStatus(orderId);
                    localStorage.removeItem(getSnoozeKey(orderId));

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Status updated successfully'
                    }).then(() => {
                        location.reload();
                    });

                } else {
                    forceStatusUpdate(orderId);
                }
            });
        }

        // -------------------------------
        // 🧠 INIT
        // -------------------------------
        document.addEventListener('DOMContentLoaded', async () => {

            await loadStatuses();

            // for (let key in localStorage) {
            //     if (key.startsWith('force_status_')) {
            //         const orderId = key.replace('force_status_', '');
            //         forceStatusUpdate(orderId);
            //         return;
            //     }
            // }
            if (canShowDeadlinePopup) {
                for (let key in localStorage) {
                    if (key.startsWith('force_status_')) {
                        const orderId = key.replace('force_status_', '');
                        forceStatusUpdate(orderId);
                        return;
                    }
                }

                checkUrgentOrders();
            }
        });

        // -------------------------------
        // 🔁 POLLING
        // -------------------------------
        setInterval(checkUrgentOrders, CHECK_INTERVAL);
    </script>


</body>
</html>
