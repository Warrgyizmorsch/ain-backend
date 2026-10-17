<div class="modal fade" id="peerSuggestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header py-4 px-6" style="background: linear-gradient(135deg, #1e1e2d 0%, #2d2d44 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <span class="btn btn-icon btn-sm btn-light-info p-0" style="width: 32px; height: 32px; border-radius: 8px;">
                        <i class="fa fa-users text-info fs-5"></i>
                    </span>
                    <div>
                        <h4 class="modal-title fw-bolder text-white mb-0 fs-5">
                            Batchmate / Module Peer Finder
                        </h4>
                        <div class="text-muted fs-8 mt-0" style="color: #a1a5b7 !important;">
                            Find students who ordered the same module code in the 2–12 months window
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" style="color: #ffffff;">
                    <i class="fa fa-times fs-6"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-6" style="background-color: #f9f9fb; max-height: 75vh; overflow-y: auto;">
                {{-- Current Order & Customer Card (Person A) --}}
                <div class="card border border-gray-300 shadow-none mb-4" style="background: #ffffff; border-radius: 10px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                            <div>
                                <span class="badge badge-light-primary fw-bold fs-8 mb-1">
                                    Current Order (Person A)
                                </span>
                                <h5 class="fw-bolder text-gray-800 mb-1" id="psCurrentCustomerName">
                                    Loading...
                                </h5>
                                <div class="text-muted fs-7" id="psCurrentOrderTitle">
                                    --
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted fs-8">Order Code</div>
                                <span class="badge badge-light-dark fs-7 fw-bold" id="psCurrentOrderCode">--</span>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-3"></div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="fs-8 text-gray-700">
                                <span class="fw-bold">Searched Module Codes:</span>
                                <span id="psSearchedModulesBadges" class="d-inline-flex flex-wrap gap-1 ms-1">
                                    <span class="badge badge-light-secondary fs-8">Scanning...</span>
                                </span>
                            </div>
                            <div class="fs-8 text-muted">
                                Timeline: <span id="psTimelineText" class="fw-bold text-gray-700">Past 2 to 12 Months</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Loading Spinner State --}}
                <div id="psLoadingState" class="py-10 text-center">
                    <div class="spinner-border text-primary mb-3" style="width: 2.5rem; height: 2.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="fw-bold text-gray-800 fs-6">Scanning order history for matching batchmates...</div>
                    <div class="text-muted fs-7">Checking orders across shared module codes</div>
                </div>

                {{-- Empty / No Peers State --}}
                <div id="psEmptyState" class="py-8 text-center d-none">
                    <div class="mb-3">
                        <span class="btn btn-icon btn-light-warning p-0" style="width: 50px; height: 50px; border-radius: 50%;">
                            <i class="fa fa-info-circle text-warning fs-2"></i>
                        </span>
                    </div>
                    <h5 class="fw-bolder text-gray-800 mb-1" id="psEmptyTitle">No Batchmate Found</h5>
                    <div class="text-muted fs-7 mx-auto" style="max-width: 480px;" id="psEmptyMessage">
                        No other customer placed an order with the same module code in the 2–12 months window.
                    </div>
                </div>

                {{-- Peers Results Container --}}
                <div id="psResultsContainer" class="d-none">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-bolder text-gray-800 fs-6">
                            <span class="badge badge-light-success fs-7 fw-bold px-3 py-1 me-1" id="psPeersCountBadge">0 Peers Found</span>
                            Suggested Peers (Person B):
                        </div>
                        <div class="text-muted fs-8">1-Click to pitch new assignments</div>
                    </div>

                    <div id="psPeersList" class="d-flex flex-column gap-3">
                        {{-- Injected dynamically --}}
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer py-3 px-6 bg-white border-top justify-content-between">
                <div class="fs-8 text-muted">
                    <i class="fa fa-shield-alt text-success me-1"></i> Direct WhatsApp chat triggers target peer's registered account.
                </div>
                <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form for Opening Peer in CRM WhatsApp --}}
<form id="psHiddenWaForm" method="POST" action="{{ route('whatsapp.chat.open-order') }}" target="_blank" class="d-none">
    @csrf
    <input type="hidden" name="order_ref" id="psHiddenWaOrderRef" value="">
</form>

<style>
.ps-module-pill {
    cursor: pointer;
    user-select: none;
    transition: all 0.15s ease-in-out;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
}
.ps-module-pill:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
}
.ps-module-pill.active {
    font-weight: 700;
}
</style>

<script>
window.psCurrentAllPeers = [];
window.psCurrentOrderId = null;
window.psActiveModuleFilter = 'ALL';

function escapePsHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

window.openPeerSuggestionModal = function(orderId) {
    const modalEl = document.getElementById('peerSuggestionModal');
    if (!modalEl) {
        alert('Peer Suggestion Modal element not found in DOM.');
        return;
    }

    const modal = (typeof bootstrap !== 'undefined' && bootstrap.Modal)
        ? bootstrap.Modal.getOrCreateInstance(modalEl)
        : $(modalEl);

    if (typeof modal.show === 'function') {
        modal.show();
    } else {
        $(modalEl).modal('show');
    }

    window.psCurrentOrderId = orderId;
    window.psCurrentAllPeers = [];
    window.psActiveModuleFilter = 'ALL';

    // Reset UI to loading state
    $('#psCurrentCustomerName').text('Loading customer...');
    $('#psCurrentOrderTitle').text('--');
    $('#psCurrentOrderCode').text('--');
    $('#psTimelineText').text('Past 2 to 12 Months');
    $('#psSearchedModulesBadges').html('<span class="badge badge-light-secondary fs-8">Scanning...</span>');

    $('#psLoadingState').removeClass('d-none');
    $('#psEmptyState').addClass('d-none');
    $('#psResultsContainer').addClass('d-none');
    $('#psPeersList').empty();

    // Fetch peer suggestions asynchronously
    const url = "{{ route('orders.peer.suggestions', ':id') }}".replace(':id', orderId);

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            $('#psLoadingState').addClass('d-none');

            if (!res || !res.success) {
                $('#psEmptyTitle').text('Could not fetch peers');
                $('#psEmptyMessage').text(res && res.message ? res.message : 'An error occurred while finding peers.');
                $('#psEmptyState').removeClass('d-none');
                return;
            }

            // Populate current customer (Person A) details
            if (res.current_customer) {
                $('#psCurrentCustomerName').html(
                    escapePsHtml(res.current_customer.name || 'Customer') +
                    (res.current_customer.user_id ? ' <span class="badge badge-light-dark fs-8 fw-bold">ID: ' + escapePsHtml(res.current_customer.user_id) + '</span>' : '')
                );
                $('#psCurrentOrderTitle').text(res.current_customer.title || 'N/A');
                $('#psCurrentOrderCode').text(res.current_customer.order_code || '--');
                if (res.current_customer.window_start && res.current_customer.window_end) {
                    $('#psTimelineText').text(res.current_customer.window_start + ' to ' + res.current_customer.window_end);
                } else {
                    $('#psTimelineText').text('Past 2 to 12 Months');
                }
            }

            window.psCurrentAllPeers = res.peers || [];

            // Calculate peer count by module code across all modules each peer took
            const peerCountByModule = {};
            window.psCurrentAllPeers.forEach(function(p) {
                const modules = (Array.isArray(p.matched_modules) && p.matched_modules.length > 0)
                    ? p.matched_modules
                    : [p.matched_module_code];
                modules.forEach(function(rawM) {
                    const m = (rawM || '').trim().toUpperCase();
                    if (m) {
                        peerCountByModule[m] = (peerCountByModule[m] || 0) + 1;
                    }
                });
            });

            // Render searched module filter badges
            if (res.searched_modules && res.searched_modules.length > 0) {
                const totalPeers = window.psCurrentAllPeers.length;
                let moduleHtml = `
                    <span class="badge ps-module-pill active fs-8 fw-bold" 
                          data-module="ALL" 
                          onclick="filterPeersByModule('ALL', this)" 
                          style="background-color: #1e1e2d; color: #ffffff; border: 1px solid #1e1e2d; box-shadow: 0 2px 8px rgba(30, 30, 45, 0.35);" 
                          title="Click to view all peers">
                        All (${totalPeers})
                    </span>
                `;

                res.searched_modules.forEach(function(code) {
                    const codeUpper = (code || '').trim().toUpperCase();
                    const count = peerCountByModule[codeUpper] || 0;
                    if (count === 0) return; // Strictly omit any module with 0 matching peers

                    moduleHtml += `
                        <span class="badge ps-module-pill fs-8 fw-bold" 
                              data-module="${escapePsHtml(code)}" 
                              data-count="${count}"
                              onclick="filterPeersByModule('${escapePsHtml(code)}', this)" 
                              style="background-color: #ffeef3; color: #f1416c; border: 1px solid #f1416c;" 
                              title="Tap to filter peers by ${escapePsHtml(code)} (${count} matches)">
                            ${escapePsHtml(code)}
                            <span class="badge badge-circle text-white ms-1" style="background-color: #f1416c; font-size: 9px; width: 16px; height: 16px; min-width: 16px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">${count}</span>
                        </span>
                    `;
                });
                $('#psSearchedModulesBadges').html(moduleHtml);
            } else {
                $('#psSearchedModulesBadges').html('<span class="badge badge-light-warning fs-8">None</span>');
            }

            // Check if peers exist
            if (!res.has_peers || window.psCurrentAllPeers.length === 0) {
                $('#psEmptyTitle').text('No Batchmates Found');
                $('#psEmptyMessage').text(res.message || 'No matching peers found who ordered past module codes within the 2–12 months window.');
                $('#psEmptyState').removeClass('d-none');
                return;
            }

            // Render matched peers
            renderPeersCards(window.psCurrentAllPeers, 'ALL');
        },
        error: function(xhr) {
            $('#psLoadingState').addClass('d-none');
            $('#psEmptyTitle').text('Connection Error');
            $('#psEmptyMessage').text('Could not communicate with server. Please try again.');
            $('#psEmptyState').removeClass('d-none');
        }
    });
};

window.filterPeersByModule = function(moduleCode, el) {
    if (!window.psCurrentAllPeers) return;

    // Toggle behavior: if clicking already active filter, revert to ALL
    if (moduleCode !== 'ALL' && window.psActiveModuleFilter === moduleCode) {
        moduleCode = 'ALL';
    }
    window.psActiveModuleFilter = moduleCode;

    // Update pill active styling in Searched Module Codes bar
    $('#psSearchedModulesBadges .ps-module-pill').each(function() {
        const pillModule = $(this).attr('data-module');
        const pillCount = parseInt($(this).attr('data-count') || '0', 10);

        if (pillModule === moduleCode) {
            if (pillModule === 'ALL') {
                $(this).addClass('active').css({
                    'background-color': '#1e1e2d',
                    'color': '#ffffff',
                    'border': '1px solid #1e1e2d',
                    'box-shadow': '0 2px 8px rgba(30, 30, 45, 0.35)',
                    'transform': 'translateY(-1px)'
                });
            } else {
                $(this).addClass('active').css({
                    'background-color': '#f1416c',
                    'color': '#ffffff',
                    'border': '1px solid #f1416c',
                    'box-shadow': '0 2px 8px rgba(241, 65, 108, 0.45)',
                    'transform': 'translateY(-1px)'
                });
                $(this).find('.badge-circle').css({
                    'background-color': '#ffffff',
                    'color': '#f1416c'
                });
            }
        } else {
            $(this).removeClass('active').css({
                'box-shadow': 'none',
                'transform': 'none'
            });
            if (pillModule === 'ALL') {
                $(this).css({
                    'background-color': '#f5f8fa',
                    'color': '#3f4254',
                    'border': '1px solid #7e8299'
                });
            } else if (pillCount > 0) {
                $(this).css({
                    'background-color': '#ffeef3',
                    'color': '#f1416c',
                    'border': '1px solid #f1416c'
                });
                $(this).find('.badge-circle').css({
                    'background-color': '#f1416c',
                    'color': '#ffffff'
                });
            } else {
                $(this).css({
                    'background-color': '#f5f8fa',
                    'color': '#7e8299',
                    'border': '1px dashed #d1d5db'
                });
            }
        }
    });

    // Filter peers list
    let filteredPeers = window.psCurrentAllPeers;
    if (moduleCode !== 'ALL') {
        const targetUpper = moduleCode.trim().toUpperCase();
        filteredPeers = window.psCurrentAllPeers.filter(function(p) {
            if (Array.isArray(p.matched_modules) && p.matched_modules.length > 0) {
                return p.matched_modules.some(function(m) {
                    return (m || '').trim().toUpperCase() === targetUpper;
                });
            }
            return (p.matched_module_code || '').trim().toUpperCase() === targetUpper;
        });
    }

    renderPeersCards(filteredPeers, moduleCode);
};

window.renderPeersCards = function(peersList, activeFilter) {
    activeFilter = activeFilter || window.psActiveModuleFilter || 'ALL';
    const totalPeers = (window.psCurrentAllPeers || []).length;
    const orderId = window.psCurrentOrderId;

    // Update count badge
    if (activeFilter === 'ALL') {
        $('#psPeersCountBadge').text(peersList.length + (peersList.length === 1 ? ' Peer Found' : ' Peers Found'));
    } else {
        $('#psPeersCountBadge').html(`${peersList.length} Peer${peersList.length === 1 ? '' : 's'} <span class="badge badge-light-danger ms-1" style="font-size:11px;">${escapePsHtml(activeFilter)}</span>`);
    }

    if (!peersList || peersList.length === 0) {
        const emptyHtml = `
            <div class="card border border-dashed border-gray-300 p-6 text-center my-3 bg-white" style="border-radius: 10px;">
                <div class="mb-2">
                    <span class="btn btn-icon btn-light-warning p-0" style="width: 40px; height: 40px; border-radius: 50%;">
                        <i class="fa fa-filter text-warning fs-4"></i>
                    </span>
                </div>
                <h6 class="fw-bolder text-gray-800 mb-1">No Peers for Module ${escapePsHtml(activeFilter)}</h6>
                <div class="text-muted fs-7 mb-3">
                    No customers found who ordered module <strong>${escapePsHtml(activeFilter)}</strong> in the 2–12 months window.
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" onclick="filterPeersByModule('ALL')">
                        <i class="fa fa-undo me-1"></i> Show All Peers (${totalPeers})
                    </button>
                </div>
            </div>
        `;
        $('#psPeersList').html(emptyHtml);
        $('#psResultsContainer').removeClass('d-none');
        return;
    }

    let peersHtml = '';
    peersList.forEach(function(peer, index) {
        const targetWindowBadge = peer.in_target_window
            ? '<span class="badge badge-light-success fs-8 fw-bold"><i class="fa fa-bullseye text-success me-1"></i>2–12 Months Match</span>'
            : '<span class="badge badge-light-primary fs-8 fw-bold"><i class="fa fa-history text-primary me-1"></i>Past Student</span>';

        const displayPhone = peer.peer_phone
            ? (peer.peer_cc ? '+' + peer.peer_cc + ' ' : '') + peer.peer_phone
            : 'Not Available';

        const displayEmail = peer.peer_email || 'Not Available';

        const activeModule = (activeFilter !== 'ALL') ? activeFilter : null;
        const matchedOrdersMap = peer.matched_orders_by_module || {};
        const currentOrderInfo = (activeModule && matchedOrdersMap[activeModule])
            ? matchedOrdersMap[activeModule]
            : {
                order_id: peer.matched_order_id,
                order_code: peer.matched_order_code,
                order_date: peer.matched_order_date,
                order_status: peer.matched_order_status,
                time_ago: peer.time_ago,
            };

        // Render module badge(s)
        let moduleBadgesHtml = '';
        if (activeModule) {
            moduleBadgesHtml = `
                <span class="badge badge-light-danger fs-7 fw-bold cursor-pointer" 
                      onclick="filterPeersByModule('${escapePsHtml(activeModule)}')" 
                      title="Filtered by ${escapePsHtml(activeModule)}">
                    ${escapePsHtml(activeModule)}
                </span>
                <span class="text-muted fs-8 ms-1">(${escapePsHtml(currentOrderInfo.time_ago || currentOrderInfo.order_date || '')})</span>
            `;
        } else {
            const allModules = (Array.isArray(peer.matched_modules) && peer.matched_modules.length > 0)
                ? peer.matched_modules
                : [peer.matched_module_code || 'N/A'];
            moduleBadgesHtml = allModules.map(function(m) {
                return `
                    <span class="badge badge-light-danger fs-8 fw-bold me-1 mb-1 cursor-pointer" 
                          onclick="filterPeersByModule('${escapePsHtml(m)}')" 
                          title="Click to filter by ${escapePsHtml(m)}">
                        ${escapePsHtml(m)}
                    </span>
                `;
            }).join('');
        }

        const copyModules = (Array.isArray(peer.matched_modules) && peer.matched_modules.length > 0) 
            ? peer.matched_modules.join(', ') 
            : (peer.matched_module_code || '');

        peersHtml += `
        <div class="card border border-gray-300 shadow-sm" style="background:#ffffff; border-radius:10px; transition: transform 0.15s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="btn btn-icon btn-sm btn-light-success p-0 fw-bolder" style="width:24px;height:24px;border-radius:6px;font-size:11px;">
                                ${index + 1}
                            </span>
                            <h5 class="fw-bolder text-gray-900 mb-0 fs-6">
                                ${escapePsHtml(peer.peer_name || 'Customer')}
                            </h5>
                            ${peer.peer_user_id ? `<span class="badge badge-light-dark fs-8 fw-bold">ID: ${escapePsHtml(peer.peer_user_id)}</span>` : ''}
                        </div>
                    </div>
                    <div>
                        ${targetWindowBadge}
                    </div>
                </div>

                {{-- Shared Module & Matched Order Details Box with Right-Side Highlighted Contact Checkbox --}}
                <div class="p-3 my-2" style="background:#f4f6fa; border-radius:8px; border: 1px solid #e2e8f0;">
                    <div class="row align-items-center g-2">
                        <div class="col-8 col-sm-9">
                            <div class="row g-2 fs-7">
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block">Shared Module${!activeModule && (peer.matched_modules || []).length > 1 ? 's' : ''}:</span>
                                    <div class="d-flex flex-wrap align-items-center mt-0.5">
                                        ${moduleBadgesHtml}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted fs-8 d-block">Matched Order:</span>
                                    <span class="fw-bold text-gray-800">#${escapePsHtml(currentOrderInfo.order_code || peer.matched_order_code || '')}</span>
                                    <span class="badge badge-light-info fs-8 ms-1">${escapePsHtml(currentOrderInfo.order_status || peer.matched_order_status || 'Delivered')}</span>
                                    <span class="text-muted fs-8 d-block">${escapePsHtml(currentOrderInfo.order_date || peer.matched_order_date || '')}</span>
                                </div>
                                <div class="col-sm-12">
                                    <span class="text-muted fs-8 d-block">Contact:</span>
                                    <span class="fw-bold text-gray-800">${escapePsHtml(displayPhone)}</span>
                                    ${peer.peer_phone ? `
                                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary p-0 flex-shrink-0 ms-1" style="width:16px;height:16px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('${escapePsHtml(displayPhone)}', 'Mobile copied!');">
                                            <i class="fa fa-clone fs-8 text-muted"></i>
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>

                        {{-- Big & Highlighted Contacted Checkbox on Right Side --}}
                        <div class="col-4 col-sm-3 text-center border-start border-gray-300 ps-2 ps-sm-3">
                            <div class="p-2 rounded-2 ${peer.is_contacted ? 'bg-light-success border border-success' : 'bg-white border border-gray-300'}" 
                                 id="peerContactBox_${peer.peer_identifier}" 
                                 style="transition: all 0.2s ease;">
                                 <label class="d-flex flex-column align-items-center justify-content-center mb-0 cursor-pointer" style="cursor: pointer;">
                                    <input type="checkbox" 
                                           class="form-check-input mb-1 cursor-pointer" 
                                           id="peerContactCb_${peer.peer_identifier}"
                                           style="width: 26px; height: 26px; border: 2.5px solid ${peer.is_contacted ? '#50cd89' : '#009ef7'}; cursor: pointer; accent-color: #50cd89;"
                                           ${peer.is_contacted ? 'checked' : ''}
                                           onchange="togglePeerContactedStatus('${peer.peer_identifier}', '${peer.peer_user_id || ''}', '${peer.peer_phone || ''}', ${orderId}, this)">
                                    <span class="fs-8 fw-bolder text-gray-800 text-nowrap" id="peerContactLabel_${peer.peer_identifier}">
                                        ${peer.is_contacted ? '<span class="text-success"><i class="fa fa-check text-success me-1"></i>Contacted</span>' : 'Contacted?'}
                                    </span>
                                    <span class="fs-9 text-muted d-block mt-0.5" id="peerContactDate_${peer.peer_identifier}" style="font-size: 10px !important;">
                                        ${peer.is_contacted && peer.contacted_date_formatted ? escapePsHtml(peer.contacted_date_formatted) : '2 Mo. Cycle'}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 pt-2 border-top border-gray-200">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- 1. Open CRM WhatsApp Chat with Person B --}}
                        <button type="button" 
                                class="btn btn-sm btn-success fw-bold d-inline-flex align-items-center gap-1 shadow-sm px-3 py-1.5"
                                title="Open chat with ${escapePsHtml(peer.peer_name || 'Customer')} in CRM WhatsApp"
                                onclick="openPeerInCrmWhatsapp(${currentOrderInfo.order_id || peer.whatsapp_order_ref});">
                            <i class="fab fa-whatsapp fs-6"></i>
                            <span>CRM WhatsApp</span>
                        </button>

                        {{-- 2. Call Twilio --}}
                        ${peer.peer_full_phone ? `
                            <button type="button"
                                    class="btn btn-sm btn-light-primary fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1.5"
                                    title="Call ${escapePsHtml(peer.peer_name || 'Customer')} via Twilio"
                                    onclick="event.preventDefault(); initiateTwilioCall('${peer.peer_full_phone}', '${(peer.peer_name || 'Customer').replace(/'/g, "\\'")}');">
                                <i class="fa fa-phone text-primary fs-8"></i>
                                <span>Twilio</span>
                            </button>
                        ` : ''}

                        {{-- 3. Call Next2Call --}}
                        ${peer.peer_full_phone ? `
                            <button type="button"
                                    class="btn btn-sm btn-light-danger fw-bold d-inline-flex align-items-center gap-1 px-2.5 py-1.5 position-relative"
                                    title="Call ${escapePsHtml(peer.peer_name || 'Customer')} via Next2Call"
                                    onclick="event.preventDefault(); initiateNext2Call('${peer.peer_full_phone}', '${(peer.peer_name || 'Customer').replace(/'/g, "\\'")}');">
                                <i class="fa fa-phone text-danger fs-8"></i>
                                <span>N2C</span>
                            </button>
                        ` : ''}
                    </div>

                    {{-- Copy summary button --}}
                    <button type="button" 
                            class="btn btn-sm btn-light-secondary text-muted fw-bold px-2 py-1.5 fs-8"
                            title="Copy customer details"
                            onclick="crmCopyToClipboard('${(peer.peer_name || '').replace(/'/g, "\\'")} | ${displayPhone} | ${displayEmail} | Modules: ${copyModules}', 'Peer details copied!');">
                        <i class="fa fa-copy fs-8 me-1"></i>Copy Info
                    </button>
                </div>
            </div>
        </div>
        `;
    });

    $('#psPeersList').html(peersHtml);
    $('#psResultsContainer').removeClass('d-none');
};

window.openPeerInCrmWhatsapp = function(orderRefId) {
    if (!orderRefId) {
        alert('Invalid order reference for WhatsApp chat.');
        return;
    }
    const form = document.getElementById('psHiddenWaForm');
    const input = document.getElementById('psHiddenWaOrderRef');
    if (form && input) {
        input.value = orderRefId;
        form.submit();
    }
};

window.togglePeerContactedStatus = function(peerIdentifier, peerUserId, peerPhone, orderId, checkboxEl) {
    const isChecked = checkboxEl.checked;
    const box = document.getElementById('peerContactBox_' + peerIdentifier);
    const label = document.getElementById('peerContactLabel_' + peerIdentifier);
    const dateSpan = document.getElementById('peerContactDate_' + peerIdentifier);

    // Keep in-memory peer object synchronized across module filters
    if (window.psCurrentAllPeers) {
        const foundPeer = window.psCurrentAllPeers.find(function(p) { return p.peer_identifier === peerIdentifier; });
        if (foundPeer) {
            foundPeer.is_contacted = isChecked;
            foundPeer.contacted_date_formatted = isChecked ? 'Today' : null;
        }
    }

    // Optimistic UI updates
    if (isChecked) {
        if (box) {
            box.classList.remove('bg-white', 'border-gray-300');
            box.classList.add('bg-light-success', 'border-success');
        }
        if (label) label.innerHTML = '<span class="text-success"><i class="fa fa-check text-success me-1"></i>Contacted</span>';
        if (dateSpan) dateSpan.textContent = 'Today';
        checkboxEl.style.borderColor = '#50cd89';
    } else {
        if (box) {
            box.classList.remove('bg-light-success', 'border-success');
            box.classList.add('bg-white', 'border-gray-300');
        }
        if (label) label.textContent = 'Contacted?';
        if (dateSpan) dateSpan.textContent = '2 Mo. Cycle';
        checkboxEl.style.borderColor = '#009ef7';
    }

    $.ajax({
        url: "{{ route('orders.peer.toggle-contact') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            peer_identifier: peerIdentifier,
            peer_user_id: peerUserId || null,
            peer_phone: peerPhone || null,
            order_id: orderId || null,
            is_contacted: isChecked ? 1 : 0
        },
        success: function(res) {
            if (res && res.success) {
                if (res.is_contacted && res.contacted_date_formatted && dateSpan) {
                    dateSpan.textContent = res.contacted_date_formatted;
                }
                if (window.psCurrentAllPeers) {
                    const foundPeer = window.psCurrentAllPeers.find(function(p) { return p.peer_identifier === peerIdentifier; });
                    if (foundPeer && res.is_contacted && res.contacted_date_formatted) {
                        foundPeer.contacted_date_formatted = res.contacted_date_formatted;
                    }
                }
            } else {
                checkboxEl.checked = !isChecked;
                alert(res && res.message ? res.message : 'Could not update contacted status.');
            }
        },
        error: function(xhr) {
            checkboxEl.checked = !isChecked;
            alert('Failed to save contact status. Please check connection.');
        }
    });
};
</script>
