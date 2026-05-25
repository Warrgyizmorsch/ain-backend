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
