@php
    $teamsList = isset($teams) ? $teams : \App\Models\Team::where('is_delete', 0)->orderBy('priority', 'asc')->get();
    $authUser = auth()->user();
    $canManageTeams = !empty($authUser) && in_array((int) $authUser->role_id, [1, 9]);
@endphp

<!-- Change Team Modal -->
<div class="modal fade" id="changeTeamModal" tabindex="-1" aria-labelledby="changeTeamModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3 px-5 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-gray-800 d-flex align-items-center gap-2" id="changeTeamModalLabel">
                    <i class="fas fa-users text-primary fs-5"></i>
                    <span>Assign / Change Team</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4 px-5">
                <input type="hidden" id="modal_order_id">

                <div class="mb-3">
                    <label class="form-label fs-7 fw-bolder text-gray-700 mb-1 d-flex justify-content-between align-items-center">
                        <span>Select Team</span>
                        <span class="text-muted fs-8 fw-normal">Customer Sync Enabled</span>
                    </label>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-solid form-select-sm" id="modal_team_id" style="border-radius: 8px;">
                            <option value="">-- No Team (Unassigned) --</option>
                            @foreach($teamsList as $team)
                                <option value="{{ $team->id }}">
                                    {{ $team->team_name }}
                                </option>
                            @endforeach
                        </select>
                        @if($canManageTeams)
                            <button type="button" 
                                    class="btn btn-sm btn-icon btn-primary flex-shrink-0" 
                                    onclick="openQuickCreateTeamModal()" 
                                    title="Create New Team"
                                    style="border-radius: 8px; width: 34px; height: 34px;">
                                <i class="fas fa-plus"></i>
                            </button>
                        @endif
                    </div>
                    <div class="form-text text-muted fs-8 mt-2">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        Assigning or changing a team here will automatically synchronize and apply to all orders and future orders of this customer.
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2 px-5 bg-light border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary fw-bold px-4" id="btnUpdateTeamSubmit" onclick="updateTeam()">
                    <span class="indicator-label">Update Team</span>
                    <span class="indicator-progress d-none">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@if($canManageTeams)
<!-- Quick Create Team Modal -->
<div class="modal fade" id="quickCreateTeamModal" tabindex="-1" aria-labelledby="quickCreateTeamModalLabel" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3 px-5 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-gray-800 d-flex align-items-center gap-2" id="quickCreateTeamModalLabel">
                    <i class="fas fa-plus-circle text-success fs-5"></i>
                    <span>Create New Team</span>
                </h5>
                <button type="button" class="btn-close" onclick="closeQuickCreateTeamModal()"></button>
            </div>

            <form id="quickCreateTeamForm" onsubmit="event.preventDefault(); saveQuickTeam();">
                <div class="modal-body py-4 px-5">
                    <div class="mb-3">
                        <label class="form-label fs-7 fw-bolder text-gray-700 required">Team Name</label>
                        <input type="text" 
                               id="quick_team_name" 
                               name="team_name" 
                               class="form-control form-control-solid" 
                               placeholder="e.g. Gamma, Apex, Titan..." 
                               required 
                               autocomplete="off"
                               style="border-radius: 8px;">
                        <div class="invalid-feedback" id="quick_team_name_error"></div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-7 fw-bolder text-gray-700">Priority (Order)</label>
                            <input type="number" 
                                   id="quick_priority" 
                                   name="priority" 
                                   class="form-control form-control-solid" 
                                   placeholder="e.g. 3" 
                                   min="1"
                                   style="border-radius: 8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-7 fw-bolder text-gray-700">Percentage (%)</label>
                            <input type="number" 
                                   id="quick_percentage" 
                                   name="percentage" 
                                   class="form-control form-control-solid" 
                                   placeholder="0 - 100" 
                                   min="0" 
                                   max="100" 
                                   step="0.01"
                                   style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fs-7 fw-bolder text-gray-700 d-flex justify-content-between align-items-center mb-1">
                            <span>Assign Marketing Members (Optional)</span>
                            <span class="badge badge-light-primary fs-9" id="quick_unassigned_count">Loading...</span>
                        </label>
                        <div id="quick_team_members_container" class="border rounded-2 p-2 bg-light" style="max-height: 150px; overflow-y: auto;">
                            <div class="text-center py-2 text-muted fs-8">
                                <span class="spinner-border spinner-border-sm align-middle me-1"></span> Loading members...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2 px-5 bg-light border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-light" onclick="closeQuickCreateTeamModal()">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold px-4" id="btnSaveQuickTeam">
                        <span class="indicator-label"><i class="fas fa-check me-1"></i> Create Team</span>
                        <span class="indicator-progress d-none">
                            Creating... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // Global color palettes for dynamically added team tabs
    window.teamPaletteList = [
        { bg: '#eff6ff', border: '#bfdbfe', color: '#1d4ed8', badge: '#3b82f6' },
        { bg: '#f8fafc', border: '#cbd5e1', color: '#1e293b', badge: '#1e293b' },
        { bg: '#f0fdf4', border: '#bbf7d0', color: '#15803d', badge: '#16a34a' },
        { bg: '#faf5ff', border: '#e9d5ff', color: '#7e22ce', badge: '#9333ea' },
        { bg: '#fff7ed', border: '#fed7aa', color: '#c2410c', badge: '#ea580c' },
        { bg: '#fdf2f8', border: '#fbcfe8', color: '#be185d', badge: '#db2777' },
        { bg: '#ecfeff', border: '#a5f3fc', color: '#0e7490', badge: '#06b6d4' },
        { bg: '#fefce8', border: '#fef08a', color: '#a16207', badge: '#eab308' },
    ];

    function openTeamModal(orderId, teamId) {
        $('#modal_order_id').val(orderId);
        $('#modal_team_id').val(teamId || '');
        var modalEl = document.getElementById('changeTeamModal');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function updateTeam() {
        let orderId = $('#modal_order_id').val();
        let teamId  = $('#modal_team_id').val();

        if (!orderId) {
            Swal.fire('Error', 'Order ID not found', 'error');
            return;
        }

        const btn = $('#btnUpdateTeamSubmit');
        btn.prop('disabled', true);
        btn.find('.indicator-label').addClass('d-none');
        btn.find('.indicator-progress').removeClass('d-none');

        $.ajax({
            url: "{{ route('orders.change.team') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                team_id: teamId
            },
            success: function(response) {
                var modalEl = document.getElementById('changeTeamModal');
                if (modalEl) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                Swal.fire({
                    icon: 'success',
                    title: response.message || 'Team updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });

                // Real-time update badge on this order row if present
                const badgeContainer = $('.order-team-badge-container-' + orderId);
                if (badgeContainer.length) {
                    if (response.team_id && response.team_name && response.team_name !== 'No Team') {
                        badgeContainer.html(`
                            <span class="badge badge-light-primary fs-7 fw-bold cursor-pointer"
                                  data-bs-toggle="modal"
                                  data-bs-target="#changeTeamModal"
                                  onclick="openTeamModal('${orderId}', '${response.team_id}')"
                                  title="Click to change team">
                                <i class="fas fa-users fs-9 me-1"></i>${response.team_name}
                            </span>
                        `);
                    } else {
                        badgeContainer.html(`
                            <span class="badge badge-light-secondary text-muted fs-8 fw-bold cursor-pointer"
                                  data-bs-toggle="modal"
                                  data-bs-target="#changeTeamModal"
                                  onclick="openTeamModal('${orderId}', '')"
                                  title="Click to assign team">
                                <i class="fas fa-plus fs-9 me-1"></i> Assign Team
                            </span>
                        `);
                    }
                }
            },
            error: function(xhr) {
                let msg = 'Failed to update team';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.find('.indicator-label').removeClass('d-none');
                btn.find('.indicator-progress').addClass('d-none');
            }
        });
    }

    function openQuickCreateTeamModal() {
        $('#quickCreateTeamForm')[0].reset();
        $('#quick_team_name').removeClass('is-invalid');
        $('#quick_team_name_error').text('');

        // Fetch unassigned marketing members
        $('#quick_unassigned_count').text('Loading...');
        $('#quick_team_members_container').html(`
            <div class="text-center py-2 text-muted fs-8">
                <span class="spinner-border spinner-border-sm align-middle me-1"></span> Loading members...
            </div>
        `);

        $.ajax({
            url: "{{ route('teams.unassigned.members') }}",
            type: "GET",
            dataType: "json",
            success: function(res) {
                let members = res.members || [];
                $('#quick_unassigned_count').text(members.length + ' Available');
                if (members.length === 0) {
                    $('#quick_team_members_container').html(`
                        <div class="text-center py-2 text-muted fs-8">
                            No unassigned members available. You can assign members later.
                        </div>
                    `);
                } else {
                    let html = '<div class="row g-1">';
                    members.forEach(function(m) {
                        html += `
                            <div class="col-12 col-sm-6">
                                <div class="form-check form-check-custom form-check-solid form-check-sm py-1">
                                    <input class="form-check-input" type="checkbox" name="member_ids[]" value="${m.id}" id="qmem_${m.id}">
                                    <label class="form-check-label fs-8 text-dark fw-bold" for="qmem_${m.id}" style="cursor: pointer;">
                                        ${m.name}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#quick_team_members_container').html(html);
                }
            },
            error: function() {
                $('#quick_unassigned_count').text('0');
                $('#quick_team_members_container').html(`
                    <div class="text-center py-2 text-muted fs-8">
                        Members can be assigned later from the Teams menu.
                    </div>
                `);
            }
        });

        var modalEl = document.getElementById('quickCreateTeamModal');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function closeQuickCreateTeamModal() {
        var modalEl = document.getElementById('quickCreateTeamModal');
        if (modalEl) {
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function saveQuickTeam() {
        const teamName = $('#quick_team_name').val().trim();
        if (!teamName) {
            $('#quick_team_name').addClass('is-invalid');
            $('#quick_team_name_error').text('Please enter a team name');
            return;
        }

        const priority = $('#quick_priority').val();
        const percentage = $('#quick_percentage').val();
        const memberIds = [];
        $('input[name="member_ids[]"]:checked').each(function() {
            memberIds.push($(this).val());
        });

        const btn = $('#btnSaveQuickTeam');
        btn.prop('disabled', true);
        btn.find('.indicator-label').addClass('d-none');
        btn.find('.indicator-progress').removeClass('d-none');

        $.ajax({
            url: "{{ route('teams.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                team_name: teamName,
                priority: priority,
                percentage: percentage,
                member_ids: memberIds
            },
            success: function(response) {
                closeQuickCreateTeamModal();

                const newTeam = response.new_team;
                if (!newTeam) {
                    Swal.fire('Success', 'Team created successfully!', 'success');
                    return;
                }

                // 1. Append to #modal_team_id select & select it!
                if ($('#modal_team_id').length) {
                    $('#modal_team_id').append(
                        $('<option>', {
                            value: newTeam.id,
                            text: newTeam.team_name,
                            selected: true
                        })
                    );
                    $('#modal_team_id').val(newTeam.id);
                }

                // 2. Dynamically add the new team Tab/Button to the top filter bar in real-time!
                const container = $('#dynamicTeamBtnsContainer');
                if (container.length) {
                    const existingCount = container.find('.dynamic-team-btn').length;
                    const palette = window.teamPaletteList[existingCount % window.teamPaletteList.length];
                    const btnId = 'teamBtn_' + newTeam.id;

                    const newBtnHtml = `
                        <a href="javascript:void(0)" 
                           id="${btnId}" 
                           data-team-id="${newTeam.id}" 
                           class="team-quick-btn dynamic-team-btn"
                           style="background-color: ${palette.bg}; border: 1px solid ${palette.border}; color: ${palette.color};">
                            <span>${newTeam.team_name}</span>
                            <span class="team-badge" style="background: ${palette.badge};">0</span>
                        </a>
                    `;

                    const addBtn = $('#addTeamQuickBtn');
                    if (addBtn.length) {
                        $(newBtnHtml).insertBefore(addBtn);
                    } else {
                        container.append(newBtnHtml);
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Team Created!',
                    text: `Team "${newTeam.team_name}" is ready and added to the filter tabs in real-time.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                let msg = 'Failed to create team';
                if (xhr.status === 422 && xhr.responseJSON) {
                    if (xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        const firstKey = Object.keys(xhr.responseJSON.errors)[0];
                        msg = xhr.responseJSON.errors[firstKey][0];
                    }
                }
                Swal.fire('Error', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
                btn.find('.indicator-label').removeClass('d-none');
                btn.find('.indicator-progress').addClass('d-none');
            }
        });
    }
</script>
