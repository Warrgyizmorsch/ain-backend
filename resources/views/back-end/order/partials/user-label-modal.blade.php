{{-- ── CRM User Labels Management Modal (Real-time + AJAX Sync) ── --}}
<div class="modal fade" id="crmUserLabelModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content shadow-lg rounded-3 border-0">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-white" style="width: 32px; height: 32px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold fs-6 mb-0" id="crmUserLabelModalTitle">Assign Labels</h5>
                        <div class="text-white text-opacity-75 fs-9" id="crmUserLabelModalSubtitle">Manage customer tags</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="crmUserLabelForm">
                <input type="hidden" id="crmUserLabelUserId" name="user_id" value="">
                <input type="hidden" id="crmUserLabelPhone" name="phone" value="">
                <input type="hidden" id="crmUserLabelEmail" name="email" value="">

                <div class="modal-body p-4" style="max-height: 420px; overflow-y: auto;">
                    <div id="crmUserLabelError" class="alert alert-danger d-none py-2 px-3 fs-8 mb-3"></div>

                    <div class="d-flex justify-content-between align-items-center mb-2.5">
                        <span class="fs-8 fw-bolder text-gray-700 text-uppercase">Select Labels</span>
                        <span class="fs-9 text-muted" id="crmUserLabelSelectedCount">0 selected</span>
                    </div>

                    @php
                        $crmAllLabels = \App\Models\WhatsappChatLabel::ordered()->get();
                    @endphp

                    <div class="d-flex flex-column gap-2" id="crmUserLabelListContainer">
                        @forelse($crmAllLabels as $label)
                            <label class="d-flex align-items-center justify-content-between p-2.5 rounded border border-gray-200 cursor-pointer crm-label-option-row" style="transition: all 0.15s ease;" for="crm_lbl_chk_{{ $label->id }}">
                                <div class="d-flex align-items-center gap-2.5">
                                    <input class="form-check-input crm-user-label-chk m-0" type="checkbox" name="label_ids[]" value="{{ $label->id }}" id="crm_lbl_chk_{{ $label->id }}" data-id="{{ $label->id }}" data-name="{{ $label->name }}" data-color="{{ $label->color }}">
                                    <span class="badge" style="background: {{ $label->color }}1f; color: {{ $label->color }}; border: 1px solid {{ $label->color }}4d; font-size: 12px; padding: 4px 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: {{ $label->color }};"></span>
                                        {{ $label->name }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    @if($label->is_whatsapp)
                                        <span class="badge badge-light-success fs-9 py-0 px-1" title="WhatsApp Channel">WA</span>
                                    @endif
                                    @if($label->is_email)
                                        <span class="badge badge-light-primary fs-9 py-0 px-1" title="Email Channel">Email</span>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-4 text-muted fs-8">
                                No labels found. Please create labels in Master Settings.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="modal-footer py-2.5 px-4 bg-light d-flex justify-content-between align-items-center">
                    <span id="crmUserLabelSyncStatus" class="fs-9 text-muted"></span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light py-1.5 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary py-1.5 px-3" id="crmUserLabelSaveBtn">
                            <i class="fa fa-check me-1"></i>Save Labels
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    window.openUserLabelModal = function(userId, userName, userPhone, userEmail, assignedIds) {
        const modalEl = document.getElementById('crmUserLabelModal');
        if (!modalEl) return;

        document.getElementById('crmUserLabelUserId').value = userId || '';
        document.getElementById('crmUserLabelPhone').value = userPhone || '';
        document.getElementById('crmUserLabelEmail').value = userEmail || '';

        const titleEl = document.getElementById('crmUserLabelModalTitle');
        if (titleEl) titleEl.textContent = 'Labels — ' + (userName || userPhone || 'User #' + userId);

        const subtitleEl = document.getElementById('crmUserLabelModalSubtitle');
        if (subtitleEl) {
            const parts = [];
            if (userPhone) parts.push(userPhone);
            if (userEmail) parts.push(userEmail);
            subtitleEl.textContent = parts.join(' • ') || 'User #' + userId;
        }

        const errorEl = document.getElementById('crmUserLabelError');
        if (errorEl) errorEl.classList.add('d-none');

        // Pre-check assigned labels
        let idsArr = [];
        if (Array.isArray(assignedIds)) {
            idsArr = assignedIds.map(Number);
        } else if (typeof assignedIds === 'string') {
            try { idsArr = JSON.parse(assignedIds).map(Number); } catch(e) { idsArr = []; }
        }

        const idSet = new Set(idsArr);
        document.querySelectorAll('.crm-user-label-chk').forEach(chk => {
            chk.checked = idSet.has(Number(chk.value));
        });

        updateSelectedLabelCount();

        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        bsModal.show();
    };

    function updateSelectedLabelCount() {
        const count = document.querySelectorAll('.crm-user-label-chk:checked').length;
        const countEl = document.getElementById('crmUserLabelSelectedCount');
        if (countEl) countEl.textContent = count + ' selected';
    }

    document.querySelectorAll('.crm-user-label-chk').forEach(chk => {
        chk.addEventListener('change', updateSelectedLabelCount);
    });

    // Handle Form Submit
    const form = document.getElementById('crmUserLabelForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('crmUserLabelSaveBtn');
            const statusEl = document.getElementById('crmUserLabelSyncStatus');
            const errorEl = document.getElementById('crmUserLabelError');

            const userId = document.getElementById('crmUserLabelUserId').value;
            const phone = document.getElementById('crmUserLabelPhone').value;
            const email = document.getElementById('crmUserLabelEmail').value;

            const checkedBoxes = Array.from(document.querySelectorAll('.crm-user-label-chk:checked'));
            const labelIds = checkedBoxes.map(chk => Number(chk.value));
            const labelsData = checkedBoxes.map(chk => ({
                id: Number(chk.dataset.id || chk.value),
                name: chk.dataset.name,
                color: chk.dataset.color
            }));

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
            }
            if (statusEl) {
                statusEl.textContent = 'Saving labels...';
            }

            try {
                const res = await fetch('{{ route("whatsapp.chat.contact-labels.save") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        phone: phone,
                        email: email,
                        label_ids: labelIds
                    })
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Failed to save labels');
                }

                // Update UI badges across all tables in real-time
                updateUserLabelsBadgesInDOM(userId, phone, email, data.labels || labelsData, labelIds);

                const bsModal = bootstrap.Modal.getInstance(document.getElementById('crmUserLabelModal'));
                if (bsModal) bsModal.hide();

                if (typeof toastr !== 'undefined') {
                    toastr.success('Labels updated successfully!');
                } else if (window.Swal) {
                    Swal.fire({ icon: 'success', title: 'Labels Updated', timer: 1200, showConfirmButton: false });
                }
            } catch (err) {
                if (errorEl) {
                    errorEl.textContent = err.message || 'Error updating labels.';
                    errorEl.classList.remove('d-none');
                }
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-check me-1"></i>Save Labels';
                }
                if (statusEl) statusEl.textContent = '';
            }
        });
    }

    function updateUserLabelsBadgesInDOM(userId, phone, email, labels, labelIds) {
        const chipsHtml = (labels || []).map(lbl => `
            <span class="badge" style="background:${lbl.color}1f; color:${lbl.color}; border:1px solid ${lbl.color}4d; font-size: 10px; padding: 2px 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:${lbl.color};"></span>${escapeHtml(lbl.name)}
            </span>
        `).join('');

        // 1. By User ID
        if (userId) {
            document.querySelectorAll(`[data-user-labels-badges="${userId}"]`).forEach(container => {
                container.innerHTML = chipsHtml;
            });
            document.querySelectorAll(`[data-user-label-button="${userId}"]`).forEach(btn => {
                btn.dataset.labels = JSON.stringify(labelIds);
            });
        }

        // 2. By Phone if present
        if (phone) {
            const cleanP = String(phone).replace(/\D+/g, '');
            document.querySelectorAll(`[data-user-labels-badges-phone="${cleanP}"]`).forEach(container => {
                container.innerHTML = chipsHtml;
            });
        }

        // 3. WhatsApp contact sidebar item if open in background
        if (typeof window.updateContactItemLabels === 'function' && phone) {
            window.updateContactItemLabels(phone, labels, labelIds);
        }
    }

    function escapeHtml(str) {
        return String(str || '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[c]));
    }
})();
</script>
