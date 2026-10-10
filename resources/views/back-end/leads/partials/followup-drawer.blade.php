<!-- Lead Follow-up Side Toggle Drawer (Matching Success Tracking Style) -->
<div id="kt_drawer_lead_followup" 
     class="offcanvas offcanvas-end followup-history-drawer" 
     tabindex="-1"
     aria-labelledby="leadFollowupDrawerLabel">

    <!-- Header (Matching follow.blade.php) -->
    <div class="offcanvas-header d-flex align-items-center justify-content-between p-5 border-bottom bg-light">
        <div class="d-flex flex-column">
            <h5 class="offcanvas-title fw-bolder text-gray-900 fs-5 mb-1" id="leadFollowupDrawerLabel">
                <i class="fa fa-history text-primary me-2"></i>Follow-Up History
            </h5>
            <span class="text-muted fs-8">
                Order ID: <strong class="text-dark" id="followup_lead_order_badge">-</strong>
                <span id="followup_lead_client_wrap" style="display: none;"> • Client: <strong class="text-dark" id="followup_lead_name">-</strong></span>
            </span>
        </div>
        <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-close-followup-drawer" data-bs-dismiss="offcanvas" aria-label="Close" onclick="closeLeadFollowupDrawer()">
            <i class="fa fa-times fs-6"></i>
        </button>
    </div>

    <!-- Body / Follow-up History (Matching follow.blade.php) -->
    <div class="offcanvas-body p-5 overflow-auto flex-grow-1" id="followup_history_scroll" style="background-color: #f9fafb;">
        <!-- Loading Spinner -->
        <div id="followup_loader" class="text-center py-12">
            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-muted fs-8 mt-2.5 fw-semibold">Fetching follow-up records...</div>
        </div>

        <!-- Follow-up Timeline List -->
        <div id="followup_timeline_list" class="timeline-widget" style="display: none;"></div>

        <!-- Empty State (Matching follow.blade.php) -->
        <div id="followup_empty_state" class="text-center py-10" style="display: none;">
            <div class="symbol symbol-60px symbol-circle bg-light-primary mb-3 d-inline-flex align-items-center justify-content-center">
                <i class="fa fa-commenting-o text-primary fs-2"></i>
            </div>
            <h6 class="fw-bolder text-gray-800 fs-6 mb-1">No Follow-Up History</h6>
            <p class="text-muted fs-7 mb-0">No follow-up comments have been recorded for this lead yet.</p>
        </div>
    </div>

    <!-- Footer Form: Chat Note & Next Followup Date & Save Button -->
    <div class="offcanvas-footer p-4 border-top bg-white shadow-sm">
        <form id="lead_followup_form" onsubmit="submitLeadFollowup(event)">
            <input type="hidden" id="followup_target_lead_id" value="">

            <!-- Chat / Message Box -->
            <div class="mb-3">
                <label class="form-label fs-7 fw-bolder text-gray-800 mb-1.5 d-flex align-items-center justify-content-between">
                    <span><i class="fa fa-commenting-o text-primary me-1.5"></i> Follow-up Note / Discussion</span>
                    <span class="badge badge-light-primary fs-9 fw-semibold">Required</span>
                </label>
                <textarea id="followup_message_input" 
                          class="form-control form-control-solid fs-7 border border-gray-200" 
                          rows="3" 
                          style="resize: none; border-radius: 8px;"
                          placeholder="Write followup discussion, remarks, or client's response..." 
                          required></textarea>
            </div>

            <!-- Next Followup Date & Save Button Row -->
            <div class="d-flex flex-column gap-1">
                <label class="form-label fs-7 fw-bolder text-gray-800 mb-1">
                    <i class="fa fa-calendar-check-o text-primary me-1.5"></i> Next Follow-up Date <span class="text-danger">*</span>
                </label>
                
                <div class="d-flex align-items-center gap-2">
                    <div class="position-relative flex-grow-1">
                        <input type="date" 
                               id="followup_date_input" 
                               class="form-control form-control-solid fs-7 border border-gray-200 h-42px w-100" 
                               style="border-radius: 8px;"
                               min="{{ date('Y-m-d') }}" 
                               value="{{ date('Y-m-d') }}" 
                               required>
                    </div>
                    <button type="submit" id="btn_save_followup" class="btn btn-primary fw-bolder fs-7 h-42px px-4 d-inline-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="min-width: 135px; border-radius: 8px;">
                        <span class="indicator-label d-inline-flex align-items-center">
                            <i class="fa fa-paper-plane me-2"></i> Save Note
                        </span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm align-middle me-1"></span> Saving...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Backdrop (Matching follow.blade.php) -->
<div id="leadFollowupDrawerBackdrop" class="followup-drawer-backdrop" onclick="closeLeadFollowupDrawer()"></div>

<style>
/* Follow-up History Side Toggle Drawer (Exact Tracking Style from follow.blade.php) */
.followup-history-drawer {
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    width: 500px !important;
    max-width: 95vw !important;
    height: 100vh !important;
    background: #ffffff !important;
    box-shadow: -6px 0 35px rgba(0, 0, 0, 0.22) !important;
    z-index: 100050 !important;
    display: flex !important;
    flex-direction: column !important;
    transform: translateX(100%) !important;
    transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    visibility: hidden !important;
}
.followup-history-drawer.show {
    transform: translateX(0) !important;
    visibility: visible !important;
}
.followup-drawer-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0, 0, 0, 0.45) !important;
    backdrop-filter: blur(2px) !important;
    z-index: 100040 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transition: opacity 0.25s ease-in-out !important;
}
.followup-drawer-backdrop.show {
    opacity: 1 !important;
    visibility: visible !important;
}
.h-42px {
    height: 42px !important;
}
</style>

<script>
let currentFollowupLeadId = null;

function openLeadFollowupDrawer(leadId) {
    if (!leadId) return;
    currentFollowupLeadId = leadId;
    $('#followup_target_lead_id').val(leadId);
    $('#followup_message_input').val('');
    $('#followup_date_input').val('{{ date("Y-m-d") }}');

    // Show Drawer & Backdrop (tracking style)
    $('#kt_drawer_lead_followup').addClass('show');
    $('#leadFollowupDrawerBackdrop').addClass('show');
    $('body').css('overflow', 'hidden');

    loadLeadFollowupData(leadId);
}

function closeLeadFollowupDrawer() {
    $('#kt_drawer_lead_followup').removeClass('show');
    $('#leadFollowupDrawerBackdrop').removeClass('show');
    $('body').css('overflow', '');
}

$(document).on('click', '.btn-close-followup-drawer', function(e) {
    e.preventDefault();
    closeLeadFollowupDrawer();
});

$(document).keyup(function(e) {
    if (e.key === "Escape") {
        closeLeadFollowupDrawer();
    }
});

function loadLeadFollowupData(leadId) {
    $('#followup_loader').show();
    $('#followup_timeline_list').hide().empty();
    $('#followup_empty_state').hide();

    $.ajax({
        url: "{{ url('/lead/followups') }}/" + leadId,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $('#followup_loader').hide();
            if (res.status && res.lead) {
                const lead = res.lead;
                $('#followup_lead_order_badge').text(lead.order_id || ('LEAD-' + lead.id));
                const leadName = lead.name || '';
                if (leadName) {
                    $('#followup_lead_name').text(leadName);
                    $('#followup_lead_client_wrap').show();
                } else {
                    $('#followup_lead_client_wrap').hide();
                }

                const followups = res.followups || [];

                if (followups.length === 0) {
                    $('#followup_empty_state').show();
                } else {
                    $('#followup_empty_state').hide();
                    $('#followup_timeline_list').show();
                    followups.forEach(function(f) {
                        $('#followup_timeline_list').append(renderFollowupCard(f));
                    });
                }
            }
        },
        error: function(err) {
            $('#followup_loader').hide();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load followups. Please try again.'
            });
        }
    });
}

function renderFollowupCard(f) {
    let badgeHtml = '<span class="badge badge-light-primary text-primary fs-8 fw-bold px-2 py-1"><i class="fa fa-clock-o me-1 fs-9"></i>Upcoming</span>';

    if (f.is_done) {
        badgeHtml = '<span class="badge badge-light-success text-success border border-success border-dashed fs-8 fw-bold px-2 py-1"><i class="fa fa-check-circle me-1 fs-9"></i>Done</span>';
    } else if (f.is_today) {
        badgeHtml = '<span class="badge badge-light-warning text-warning border border-warning border-dashed fs-8 fw-bold px-2 py-1"><i class="fa fa-clock-o me-1 fs-9"></i>Due Today</span>';
    } else if (f.is_overdue) {
        badgeHtml = '<span class="badge badge-light-danger text-danger border border-danger border-dashed fs-8 fw-bold px-2 py-1"><i class="fa fa-times-circle me-1 fs-9"></i>Overdue</span>';
    }

    let doneButtonHtml = '';
    if (!f.is_done) {
        doneButtonHtml = `
            <button type="button" 
                    class="btn btn-sm btn-light-success py-1 px-2.5 fs-8 fw-bold d-inline-flex align-items-center gap-1 shadow-none" 
                    style="border-radius: 6px;"
                    onclick="markFollowupAsDone(${f.id}, this)" 
                    title="Mark Follow-up Done">
                <i class="fa fa-check fs-9 text-success"></i> Done
            </button>
        `;
    }

    let completionInfo = '';
    if (f.is_done && f.done_by_name) {
        completionInfo = `
            <div class="mt-2.5 py-1.5 px-3 bg-light-success text-success rounded-2 fs-9 d-flex align-items-center">
                <i class="fa fa-check-circle me-1.5 fs-8"></i>
                <span>Completed by <strong>${escapeHtml(f.done_by_name)}</strong> on ${f.done_at_formatted || ''}</span>
            </div>
        `;
    }

    const initial = (f.user_name && f.user_name.trim().length > 0) ? f.user_name.trim().charAt(0).toUpperCase() : 'U';

    return `
        <div class="card mb-3 shadow-sm border border-gray-200 rounded-3 overflow-hidden bg-white" id="followup_item_${f.id}">
            <div class="p-4">
                <div class="d-flex align-items-start gap-3">
                    <!-- Left Avatar -->
                    <div class="symbol symbol-35px symbol-circle flex-shrink-0 mt-0.5">
                        <span class="symbol-label bg-primary text-white fw-bold fs-7">
                            ${initial}
                        </span>
                    </div>

                    <!-- Right Column: Name, Date, Text, and Next Followup all align here -->
                    <div class="flex-grow-1 min-w-0">
                        <!-- Top Row: Name & Action Badges -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0.5">
                            <div class="d-flex align-items-center gap-1.5">
                                <span class="fs-7 fw-bolder text-gray-900">${escapeHtml(f.user_name)}</span>
                                <span class="badge badge-light-secondary fs-9 py-0.5 px-2 fw-semibold">Staff</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                ${badgeHtml}
                                ${doneButtonHtml}
                            </div>
                        </div>

                        <!-- Date Row -->
                        <div class="text-muted fs-8 mb-2">
                            ${f.created_at_formatted}
                        </div>

                        <!-- Message Text (Starts here under the date) -->
                        <div class="text-dark fs-7 lh-base mb-3" style="white-space: pre-wrap; word-break: break-word;">${escapeHtml(f.message)}</div>

                        <!-- Next Follow-up Date Pill -->
                        <div class="pt-2 border-top border-gray-100 d-flex align-items-center flex-wrap gap-2">
                            <span class="fs-8 text-muted d-flex align-items-center gap-1.5 bg-light py-1 px-2.5 rounded border border-gray-200">
                                <i class="fa fa-calendar-check-o text-primary fs-8"></i>
                                <span>Next Follow-up:</span>
                                <strong class="text-gray-900 ms-1">${f.followup_date_formatted}</strong>
                            </span>
                        </div>

                        ${completionInfo}
                    </div>
                </div>
            </div>
        </div>
    `;
}

function submitLeadFollowup(e) {
    e.preventDefault();
    const leadId = $('#followup_target_lead_id').val();
    const message = $('#followup_message_input').val().trim();
    const followupDate = $('#followup_date_input').val();

    if (!leadId) {
        Swal.fire({ icon: 'warning', title: 'Warning', text: 'No lead selected.' });
        return;
    }
    if (!message) {
        Swal.fire({ icon: 'warning', title: 'Warning', text: 'Please enter a followup message.' });
        return;
    }
    if (!followupDate) {
        Swal.fire({ icon: 'warning', title: 'Warning', text: 'Please select a followup date.' });
        return;
    }

    const $btn = $('#btn_save_followup');
    $btn.prop('disabled', true);
    $btn.find('.indicator-label').addClass('d-none');
    $btn.find('.indicator-progress').removeClass('d-none');

    $.ajax({
        url: "{{ url('/lead/followups') }}/" + leadId,
        type: 'POST',
        data: {
            message: message,
            followup_date: followupDate,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $btn.prop('disabled', false);
            $btn.find('.indicator-label').removeClass('d-none');
            $btn.find('.indicator-progress').addClass('d-none');

            if (res.status && res.followup) {
                $('#followup_message_input').val('');
                $('#followup_empty_state').hide();
                $('#followup_timeline_list').show().prepend(renderFollowupCard(res.followup));

                // Update lead row badge on current page if present
                const $rowBadge = $('#lead_followup_badge_' + leadId);
                if ($rowBadge.length) {
                    $rowBadge.text(res.followup.followup_date_formatted).removeClass('d-none');
                }

                // Toast notification
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Followup note saved!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false);
            $btn.find('.indicator-label').removeClass('d-none');
            $btn.find('.indicator-progress').addClass('d-none');

            let errorMsg = 'Failed to save followup.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errs = Object.values(xhr.responseJSON.errors).flat();
                errorMsg = errs.join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire({ icon: 'error', title: 'Error', html: errorMsg });
        }
    });
}

function markFollowupAsDone(followupId, btnEl) {
    if (!followupId) return;

    Swal.fire({
        title: 'Mark as Done?',
        text: 'Are you sure you want to mark this followup as completed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#50cd89',
        cancelButtonColor: '#7e8299',
        confirmButtonText: 'Yes, Done!'
    }).then((result) => {
        if (result.isConfirmed) {
            const $btn = $(btnEl);
            $btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('/lead/followups') }}/" + followupId + "/done",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status) {
                        const $card = $('#followup_item_' + followupId);
                        $btn.remove();
                        $card.find('.badge').first().replaceWith('<span class="badge badge-light-success text-success border border-success border-dashed fs-8 fw-bold px-2 py-1"><i class="fa fa-check-circle me-1 fs-9"></i>Done</span>');

                        if (res.done_by_name) {
                            $card.find('.flex-grow-1').append(`
                                <div class="mt-2.5 py-1.5 px-3 bg-light-success text-success rounded-2 fs-9 d-flex align-items-center">
                                    <i class="fa fa-check-circle me-1.5 fs-8"></i>
                                    <span>Completed by <strong>${escapeHtml(res.done_by_name)}</strong> on ${res.done_at_formatted || 'just now'}</span>
                                </div>
                            `);
                        }

                        // Also update lead row badge on current page if present
                        if (currentFollowupLeadId) {
                            const $rowBadge = $('#lead_followup_badge_' + currentFollowupLeadId);
                            if ($rowBadge.length) {
                                if (res.next_pending_date) {
                                    $rowBadge.text(res.next_pending_date).removeClass('d-none');
                                } else {
                                    $rowBadge.addClass('d-none');
                                }
                            }
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Followup completed!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update followup status.' });
                }
            });
        }
    });
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
