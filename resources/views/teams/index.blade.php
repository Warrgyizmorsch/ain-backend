@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">
                    <i class="fas fa-users-cog text-primary fs-2 me-2"></i> Team Management
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Configure marketing teams, allocation weights, and assign staff members</small>
                </h1>
            </div>
            <div class="d-flex align-items-center py-1">
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light btn-active-light-primary fw-bold">
                    <i class="fas fa-arrow-left fs-8 me-1"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row g-6">
            <!-- Left Column: Team Selector & Member Assignment Form -->
            <div class="col-xl-4 col-lg-5" style="position: sticky; top: 80px; align-self: flex-start; z-index: 10;">
                <div class="card card-flush shadow-sm border-0 rounded-3 mb-5" id="teamFormCard">
                    <div class="card-header border-0 pt-4 pb-2 px-4">
                        <div class="card-title m-0">
                            <h3 class="fs-5 fw-bolder text-gray-900 m-0" id="formTitle">
                                <i class="fas fa-user-edit text-primary me-2"></i>Configure Team Members
                            </h3>
                        </div>
                        <div class="card-toolbar m-0">
                            <button type="button" class="btn btn-sm btn-icon btn-light-secondary w-25px h-25px" onclick="resetForm()" title="Reset Selection">
                                <i class="fas fa-redo-alt fs-9"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body pt-1 px-4 pb-4">
                        <form id="teamForm">
                            @csrf
                            <input type="hidden" name="team_id" id="team_id">
                            <input type="hidden" name="team_name" id="team_name">

                            <!-- Team Dropdown with + Button -->
                            <div class="mb-3">
                                <label class="form-label required fs-8 fw-bold text-gray-700 mb-1">
                                    Team Name
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm flex-grow-1">
                                        <span class="input-group-text bg-light border-gray-300">
                                            <i class="fas fa-layer-group text-muted fs-8"></i>
                                        </span>
                                        <select class="form-select form-select-sm border-gray-300" id="select_team_id" style="border-radius: 0 6px 6px 0;">
                                            <option value="">-- Choose Team --</option>
                                            @foreach($teams as $t)
                                                <option value="{{ $t->id }}" 
                                                        data-name="{{ $t->team_name }}"
                                                        data-priority="{{ $t->priority }}">
                                                    {{ $t->team_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-sm btn-icon btn-primary flex-shrink-0" 
                                            onclick="openCreateTeamModal()" 
                                            title="Create New Team"
                                            style="width: 32px; height: 32px; border-radius: 6px;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Priority (Order) -->
                            <div class="mb-3">
                                <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Priority (Assignment Order)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-gray-300">
                                        <i class="fas fa-sort-numeric-down text-muted fs-8"></i>
                                    </span>
                                    <input type="number" class="form-control form-control-sm border-gray-300" name="priority" id="priority" placeholder="1 = First, 2 = Second..." min="1">
                                </div>
                                <div class="form-text fs-9 text-muted mt-1">Lower number = higher priority for order assignment</div>
                            </div>

                            <!-- Select Members Box -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1.5">
                                    <label class="form-label fs-8 fw-bold text-gray-700 mb-0">Staff Members</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-light-primary fw-bolder fs-9" id="selectedMemberCount">0 Selected</span>
                                        <button type="button" class="btn btn-link btn-color-primary p-0 fs-9 fw-bold text-decoration-none" id="selectAllMembersBtn">All</button>
                                        <span class="text-muted fs-9">|</span>
                                        <button type="button" class="btn btn-link btn-color-danger p-0 fs-9 fw-bold text-decoration-none" id="clearAllMembersBtn">None</button>
                                    </div>
                                </div>
                                
                                <div class="position-relative mb-2">
                                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                                        <i class="fas fa-search fs-8"></i>
                                    </span>
                                    <input type="text" id="memberSearchInput" class="form-control form-control-sm ps-9 border-gray-300" placeholder="Search staff name...">
                                </div>

                                <div class="border border-gray-300 rounded p-1.5 bg-light-light" style="max-height: 280px; overflow-y: auto;">
                                    <div class="row g-1" id="memberListContainer">
                                        @forelse ($members as $member)
                                            @php
                                                $isLocked = !empty($member->team_id);
                                                $teamName = $member->team ? $member->team->team_name : null;
                                            @endphp
                                            <div class="col-12 member-item" data-name="{{ strtolower($member->name) }}">
                                                <label class="member-check-row {{ $isLocked ? 'member-locked' : 'cursor-pointer' }}" for="member_{{ $member->id }}">
                                                    <div class="member-left-box">
                                                        <input class="form-check-input member-checkbox flex-shrink-0" 
                                                               type="checkbox" 
                                                               value="{{ $member->id }}" 
                                                               id="member_{{ $member->id }}"
                                                               {{ $isLocked ? 'disabled' : '' }}>
                                                        <span class="member-name text-gray-800" title="{{ $member->name }}">{{ $member->name }}</span>
                                                    </div>
                                                    <div class="member-badge-box">
                                                        @if($isLocked)
                                                            <span class="badge badge-light-danger fs-9 fw-bold py-0.5 px-2" title="Assigned to {{ $teamName }}">
                                                                <i class="fas fa-lock text-danger me-1 fs-9"></i> Locked ({{ $teamName }})
                                                            </span>
                                                        @else
                                                            <span class="badge {{ $member->role_id == 9 ? 'badge-light-warning' : 'badge-light-primary' }} fs-9 fw-bold py-0.5 px-2">
                                                                {{ $member->role_id == 9 ? 'Subadmin' : 'Marketing' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center text-muted fs-8 py-4 empty-members-msg">
                                                No marketing members available
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2 pt-2 border-top border-gray-200">
                                <button type="button" class="btn btn-sm btn-light btn-active-light-primary px-3" onclick="resetForm()">Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold" id="submitBtn">
                                    <i class="fas fa-check-circle fs-8 me-1"></i> Save Team & Members
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Team Tabs & Members Table -->
            <div class="col-xl-8 col-lg-7">
                <div class="card card-flush shadow-sm border-0 rounded-3">
                    <div class="card-header border-0 pt-4 pb-2 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                        <!-- Left: Nav Pills Tabs -->
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <ul class="nav nav-pills nav-pills-custom gap-1 mb-0" id="teamTabsNav">
                                <li class="nav-item">
                                    <button class="nav-link team-nav-link active" 
                                            id="tab_all" 
                                            data-team-id="all" 
                                            onclick="switchTeamTab('all')">
                                        <i class="fas fa-users me-1 fs-8"></i>
                                        <span class="team-tab-name">All</span>
                                        <span class="team-tab-badge" id="allMembersCountBadge">{{ $members->count() }}</span>
                                    </button>
                                </li>
                                @foreach($teams as $t)
                                    <li class="nav-item">
                                        <button class="nav-link team-nav-link team-tab-btn" 
                                                id="tab_team_{{ $t->id }}" 
                                                data-team-id="{{ $t->id }}" 
                                                data-name="{{ $t->team_name }}"
                                                data-priority="{{ $t->priority }}"
                                                onclick="switchTeamTab({{ $t->id }})">
                                            <span class="team-tab-name">{{ $t->team_name }}</span>
                                            <span class="team-tab-badge team-tab-badge-{{ $t->id }}">
                                                {{ $t->members->count() }}
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Right Toolbar: Edit Active Team Button -->
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" 
                                    class="btn btn-sm d-none align-items-center gap-1" 
                                    id="activeTeamEditModalBtn" 
                                    onclick="openEditTeamModal()" 
                                    title="Edit Team Name">
                                <i class="fas fa-edit fs-7 me-1"></i>
                                <span>Edit <strong id="activeTabTeamNameText">Team</strong></span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Active Team Info Ribbon (when a specific team is selected) -->
                    <div id="activeTeamInfoBar" class="py-2 px-4 bg-light d-none align-items-center justify-content-between border-bottom text-muted fs-8">
                        <div class="d-flex align-items-center gap-3">
                            <span><i class="fas fa-layer-group text-primary me-1"></i> <strong class="text-gray-800" id="infoBarTeamName"></strong></span>
                            <span>&bull;</span>
                            <span>Priority: <strong class="text-gray-800" id="infoBarPriority">-</strong></span>
                            <span>&bull;</span>
                            <span>Staff: <strong class="text-primary" id="infoBarCount">0</strong></span>
                        </div>
                        <span class="text-muted fs-9"><i class="fas fa-info-circle me-1 text-muted"></i>Members assigned to this team</span>
                    </div>

                    <div class="card-body pt-3 px-4 pb-4">
                        <!-- Preloader -->
                        <div id="tablePreloader" class="text-center py-8" style="display: none;">
                            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="text-muted fs-8 fw-semibold mt-2">Loading team members...</div>
                        </div>

                        <!-- Members Table Container with proper borders -->
                        <div class="table-responsive rounded border border-gray-300" id="teamMembersTableContainer">
                            @include('teams.partials.members_table', ['members' => $members, 'activeTeam' => null, 'teamId' => 'all'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal: Quick Create Team -->
<div class="modal fade" id="createNewTeamModal" tabindex="-1" aria-labelledby="createNewTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3 px-5 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-gray-900 d-flex align-items-center gap-2" id="createNewTeamModalLabel">
                    <i class="fas fa-plus-circle text-primary fs-5"></i>
                    <span>Create New Team</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickNewTeamForm" onsubmit="event.preventDefault(); submitQuickNewTeam();">
                <div class="modal-body py-4 px-5">
                    <div class="mb-3">
                        <label class="form-label required fs-7 fw-bold text-gray-700">Team Name</label>
                        <input type="text" 
                               class="form-control form-control-solid" 
                               id="modal_new_team_name" 
                               name="team_name" 
                               placeholder="e.g. Team-Alpha, Team-Beta, Team-Gamma" 
                               required 
                               autocomplete="off"
                               style="border-radius: 8px;">
                        <div class="invalid-feedback" id="modal_new_team_name_error"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-7 fw-bold text-gray-700">Priority (Assignment Order)</label>
                        <input type="number" 
                               class="form-control form-control-solid" 
                               id="modal_new_priority" 
                               name="priority" 
                               placeholder="1 = First, 2 = Second..." 
                               min="1"
                               style="border-radius: 8px;">
                        <div class="form-text fs-9 text-muted">Lower number = assigned first</div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-5 bg-light border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4" id="btnSubmitQuickTeam">
                        <i class="fas fa-check-circle me-1"></i> Create Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Team (Name & Priority) -->
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header py-3 px-5 bg-light border-bottom">
                <h5 class="modal-title fw-bold text-gray-900 d-flex align-items-center gap-2" id="editTeamModalLabel">
                    <i class="fas fa-edit text-warning fs-5"></i>
                    <span>Edit Team: <span id="modal_edit_team_title_name" class="text-primary"></span></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeamModalForm" onsubmit="event.preventDefault(); submitEditTeamModal();">
                <input type="hidden" id="modal_edit_team_id" name="team_id">
                <div class="modal-body py-4 px-5">
                    <div class="mb-3">
                        <label class="form-label required fs-7 fw-bold text-gray-700">Team Name</label>
                        <input type="text" 
                               class="form-control form-control-solid" 
                               id="modal_edit_team_name" 
                               name="team_name" 
                               placeholder="e.g. Team-Alpha" 
                               required 
                               autocomplete="off"
                               style="border-radius: 8px;">
                        <div class="invalid-feedback" id="modal_edit_team_name_error"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-7 fw-bold text-gray-700">Priority (Assignment Order)</label>
                        <input type="number" 
                               class="form-control form-control-solid" 
                               id="modal_edit_priority" 
                               name="priority" 
                               placeholder="1 = First, 2 = Second..." 
                               min="1"
                               style="border-radius: 8px;">
                        <div class="form-text fs-9 text-muted">Lower number = assigned first</div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-5 bg-light border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4" id="btnSubmitEditTeam">
                        <i class="fas fa-check-circle me-1"></i> Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
#teamMembersTable {
    border: 1px solid #e4e6ef !important;
    border-collapse: collapse !important;
    width: 100% !important;
}
#teamMembersTable th, #teamMembersTable td {
    border: 1px solid #e4e6ef !important;
    vertical-align: middle !important;
    padding: 8px 12px !important;
}
#teamMembersTable thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.5px !important;
}
.member-check-row {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    transition: all 0.15s ease-in-out;
    margin-bottom: 3px;
    padding: 6px 10px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    min-height: 38px;
}
.member-check-row:not(.member-locked):hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
}
.member-left-box {
    display: flex;
    align-items: center;
    min-width: 0;
    flex: 1;
    gap: 8px;
}
.member-name {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    font-size: 12px;
    font-weight: 600;
}
.member-badge-box {
    flex-shrink: 0;
    white-space: nowrap;
}
.member-check-row input:checked ~ .member-name {
    color: #1d4ed8 !important;
}
.member-check-row:has(input:checked) {
    background-color: #eff6ff !important;
    border-color: #bfdbfe !important;
}
.member-locked {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    opacity: 0.72;
    cursor: not-allowed !important;
}
.member-locked * {
    cursor: not-allowed !important;
}
#teamTabsNav .team-nav-link {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 6px 14px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    border: 1px solid #e4e6ef !important;
    background-color: #f5f8fa !important;
    color: #5e6278 !important;
    cursor: pointer !important;
    transition: all 0.15s ease-in-out !important;
    text-decoration: none !important;
    line-height: 1.4 !important;
    box-shadow: none !important;
}
#teamTabsNav .team-nav-link:hover:not(.active) {
    background-color: #eef3f7 !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}
#teamTabsNav .team-nav-link .team-tab-badge {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 20px !important;
    height: 20px !important;
    padding: 0 6px !important;
    border-radius: 10px !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    background-color: #e4e6ef !important;
    color: #5e6278 !important;
    line-height: 1 !important;
    transition: all 0.15s ease-in-out !important;
}
#teamTabsNav .team-nav-link.active {
    background-color: #009ef7 !important;
    border-color: #009ef7 !important;
    color: #ffffff !important;
    box-shadow: 0 3px 8px rgba(0, 158, 247, 0.35) !important;
}
#teamTabsNav .team-nav-link.active .team-tab-name,
#teamTabsNav .team-nav-link.active i,
#teamTabsNav .team-nav-link.active * {
    color: #ffffff !important;
}
#teamTabsNav .team-nav-link.active .team-tab-badge {
    background-color: rgba(255, 255, 255, 0.28) !important;
    color: #ffffff !important;
}
#activeTeamEditModalBtn {
    background-color: #f1faff !important;
    border: 1px solid #b5e4fc !important;
    color: #009ef7 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    padding: 6px 14px !important;
    transition: all 0.2s ease-in-out !important;
    box-shadow: none !important;
}
#activeTeamEditModalBtn:hover {
    background-color: #009ef7 !important;
    border-color: #009ef7 !important;
    color: #ffffff !important;
    box-shadow: 0 3px 8px rgba(0, 158, 247, 0.3) !important;
}
#activeTeamEditModalBtn:hover * {
    color: #ffffff !important;
}
#activeTeamEditModalBtn i {
    color: #009ef7 !important;
}
#activeTeamEditModalBtn:hover i {
    color: #ffffff !important;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
    $membersInitialJson = $members->map(function ($m) {
        return [
            'id' => $m->id,
            'name' => $m->name,
            'role_id' => $m->role_id,
            'role_name' => $m->role_id == 9 ? 'Subadmin' : 'Marketing',
            'team_id' => $m->team_id,
            'team_name' => $m->team ? $m->team->team_name : null,
            'is_assigned_to_current' => false,
            'is_locked' => !empty($m->team_id),
        ];
    })->values();
@endphp

<script>
window.allMembersData = {!! json_encode($membersInitialJson) !!};
let currentActiveTabId = 'all';

function updateSelectedCount() {
    const checkedCount = $('.member-checkbox:checked').length;
    $('#selectedMemberCount').text(checkedCount + ' Selected');
}

function renderMembersList(members, currentTeamId) {
    let html = '';
    if (members && members.length > 0) {
        members.forEach(function (m) {
            const isAssigned = currentTeamId && ((m.is_assigned_to_current) || String(m.team_id) === String(currentTeamId));
            const isLocked = !isAssigned && (m.is_locked || (m.team_id && String(m.team_id) !== String(currentTeamId)));
            const checkedAttr = isAssigned ? 'checked' : '';
            const disabledAttr = isLocked ? 'disabled' : '';
            const rowClass = isLocked ? 'member-locked' : 'cursor-pointer';

            let badgeHtml = '';
            if (isAssigned) {
                badgeHtml = `<span class="badge badge-light-success fs-9 fw-bold py-0.5 px-2"><i class="fas fa-check-circle text-success me-1"></i> Assigned</span>`;
            } else if (isLocked) {
                badgeHtml = `<span class="badge badge-light-danger fs-9 fw-bold py-0.5 px-2" title="Locked in ${m.team_name || 'other team'}"><i class="fas fa-lock text-danger me-1 fs-9"></i> Locked (${m.team_name || 'Other'})</span>`;
            } else {
                badgeHtml = `<span class="badge ${m.role_id == 9 ? 'badge-light-warning' : 'badge-light-primary'} fs-9 fw-bold py-0.5 px-2">${m.role_name || (m.role_id == 9 ? 'Subadmin' : 'Marketing')}</span>`;
            }

            html += `
                <div class="col-12 member-item" data-name="${(m.name || '').toLowerCase()}">
                    <label class="member-check-row ${rowClass}" for="member_${m.id}">
                        <div class="member-left-box">
                            <input class="form-check-input member-checkbox flex-shrink-0" 
                                   type="checkbox" 
                                   value="${m.id}" 
                                   id="member_${m.id}" 
                                   ${checkedAttr} 
                                   ${disabledAttr}>
                            <span class="member-name text-gray-800" title="${m.name}">${m.name}</span>
                        </div>
                        <div class="member-badge-box">
                            ${badgeHtml}
                        </div>
                    </label>
                </div>
            `;
        });
    } else {
        html = '<div class="col-12 text-center text-muted fs-8 py-4 empty-members-msg">No marketing members available</div>';
    }

    $('#memberListContainer').html(html);
    updateSelectedCount();
}

function resetForm() {
    $('#teamForm')[0].reset();
    $('#team_id').val('');
    $('#team_name').val('');
    $('#select_team_id').val('');
    $('#submitBtn').html('<i class="fas fa-check-circle fs-8 me-1"></i> Save Team & Members');
    $('#formTitle').html('<i class="fas fa-user-edit text-primary me-2"></i>Configure Team Members');
    $('#memberSearchInput').val('');

    if (window.allMembersData) {
        renderMembersList(window.allMembersData, null);
    }
}

// When user selects a team from the dropdown
$('#select_team_id').on('change', function() {
    const teamId = $(this).val();
    if (teamId) {
        if (currentActiveTabId != teamId) {
            switchTeamTab(teamId, true);
        }
    } else {
        switchTeamTab('all', true);
    }
});

function openCreateTeamModal() {
    $('#quickNewTeamForm')[0].reset();
    $('#modal_new_team_name').removeClass('is-invalid');
    $('#modal_new_team_name_error').text('');
    var modalEl = document.getElementById('createNewTeamModal');
    if (modalEl) {
        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function submitQuickNewTeam() {
    const teamName = $('#modal_new_team_name').val().trim();
    if (!teamName) {
        $('#modal_new_team_name').addClass('is-invalid');
        $('#modal_new_team_name_error').text('Please enter team name');
        return;
    }

    const priority = $('#modal_new_priority').val();

    const btn = $('#btnSubmitQuickTeam');
    btn.prop('disabled', true);

    $.ajax({
        url: "{{ route('teams.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            team_name: teamName,
            priority: priority
        },
        success: function(response) {
            var modalEl = document.getElementById('createNewTeamModal');
            if (modalEl) {
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            const newTeam = response.new_team;
            if (newTeam) {
                // Add to dropdown
                if (!$('#select_team_id option[value="' + newTeam.id + '"]').length) {
                    $('#select_team_id').append(
                        $('<option>', {
                            value: newTeam.id,
                            text: newTeam.team_name,
                            'data-name': newTeam.team_name,
                            'data-priority': newTeam.priority
                        })
                    );
                }

                // Add to tabs
                if (!$(`#tab_team_${newTeam.id}`).length) {
                    const newTabHtml = `
                        <li class="nav-item">
                            <button class="nav-link team-nav-link team-tab-btn" 
                                    id="tab_team_${newTeam.id}" 
                                    data-team-id="${newTeam.id}" 
                                    data-name="${newTeam.team_name}"
                                    data-priority="${newTeam.priority || ''}"
                                    onclick="switchTeamTab(${newTeam.id})">
                                <span class="team-tab-name">${newTeam.team_name}</span>
                                <span class="team-tab-badge team-tab-badge-${newTeam.id}">0</span>
                            </button>
                        </li>
                    `;
                    $('#teamTabsNav').append(newTabHtml);
                }

                if (response.members) {
                    window.allMembersData = response.members;
                }

                // Select team and switch tab
                $('#select_team_id').val(newTeam.id);
                switchTeamTab(newTeam.id);

                Swal.fire({
                    icon: 'success',
                    title: 'Team Created!',
                    text: `Team "${newTeam.team_name}" is created. You can select staff members and save.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
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
        }
    });
}

function switchTeamTab(teamId, syncForm = true) {
    currentActiveTabId = teamId;

    // Update active tab buttons
    $('#teamTabsNav .nav-link').removeClass('active');
    
    let teamName = '';
    let priority = '';

    if (teamId === 'all') {
        $('#tab_all').addClass('active');
        $('#activeTeamEditModalBtn').addClass('d-none').removeClass('d-inline-flex');
        $('#activeTeamInfoBar').addClass('d-none').removeClass('d-flex');
    } else {
        const tabBtn = $(`#tab_team_${teamId}`);
        tabBtn.addClass('active');
        teamName = tabBtn.data('name') || tabBtn.find('.team-tab-name').text().trim();
        priority = tabBtn.data('priority') || '-';

        $('#activeTabTeamNameText').text(teamName);
        $('#activeTeamEditModalBtn').removeClass('d-none').addClass('d-inline-flex');

        $('#infoBarTeamName').text(teamName);
        $('#infoBarPriority').text(priority);
        $('#activeTeamInfoBar').removeClass('d-none').addClass('d-flex');
    }

    // Instant Left Form Sync (0 ms - no server roundtrip)
    if (syncForm) {
        if (teamId === 'all') {
            resetForm();
        } else {
            $('#team_id').val(teamId);
            $('#team_name').val(teamName);
            $('#select_team_id').val(teamId);
            $('#priority').val(priority === '-' ? '' : priority);
            $('#formTitle').html('<i class="fas fa-edit text-warning me-2"></i>Configure: ' + teamName);
            $('#submitBtn').html('<i class="fas fa-save fs-8 me-1"></i> Save Team & Members');

            if (window.allMembersData) {
                renderMembersList(window.allMembersData, teamId);
            }
        }
    }

    // Instant Table Filter (0 ms - lightning fast)
    filterTableByTeam(teamId, teamName);
}

function filterTableByTeam(teamId, teamName) {
    let visibleCount = 0;
    const rows = $('#teamMembersTable tbody tr.member-table-row');

    if (!teamId || teamId === 'all') {
        rows.each(function() {
            visibleCount++;
            $(this).show();
            $(this).find('.row-index').text(visibleCount);
        });
        $('#emptyMembersRow').hide();
        $('#allMembersCountBadge').text(visibleCount);
    } else {
        rows.each(function() {
            const rowTeamId = $(this).attr('data-team-id');
            if (String(rowTeamId) === String(teamId)) {
                visibleCount++;
                $(this).show();
                $(this).find('.row-index').text(visibleCount);
            } else {
                $(this).hide();
            }
        });

        if (visibleCount === 0) {
            $('#emptyMembersText').html(`No members assigned to <strong>${teamName || 'this team'}</strong> yet.`);
            $('#emptyMembersRow').show();
        } else {
            $('#emptyMembersRow').hide();
        }

        $(`.team-tab-badge-${teamId}`).text(visibleCount);
        $('#infoBarCount').text(visibleCount);
    }
}

function updateTableRowsFromMembers(members) {
    if (!members) return;
    members.forEach(function(m) {
        const row = $(`#teamMembersTable tr[data-member-id="${m.id}"]`);
        if (row.length) {
            const mTeamId = m.team_id || '';
            row.attr('data-team-id', mTeamId);
            row.data('team-id', mTeamId);
            if (mTeamId && m.team_name) {
                row.find('.team-cell').html(`
                    <span class="badge badge-light-success text-success fw-bold fs-8 px-2.5 py-1 team-badge">
                        <i class="fas fa-layer-group fs-9 me-1"></i> <span class="team-badge-name">${m.team_name}</span>
                    </span>
                `);
                row.find('.action-cell').html(`
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-light-danger w-25px h-25px btn-unassign" 
                            onclick="unassignMemberFromTable(${m.id}, '${(m.name || '').replace(/'/g, "\\'")}')" 
                            title="Remove from Team">
                        <i class="fas fa-user-minus fs-9"></i>
                    </button>
                `);
            } else {
                row.find('.team-cell').html('<span class="badge badge-light-secondary text-muted fs-8 px-2 py-1 team-badge">Unassigned</span>');
                row.find('.action-cell').html('<span class="text-muted fs-9 no-action">-</span>');
            }
        }
    });
}

function updateAllTabBadges() {
    if (!window.allMembersData) return;
    
    $('#allMembersCountBadge').text(window.allMembersData.length);

    const counts = {};
    window.allMembersData.forEach(function(m) {
        if (m.team_id) {
            counts[m.team_id] = (counts[m.team_id] || 0) + 1;
        }
    });

    $('#teamTabsNav .team-tab-btn').each(function() {
        const tid = $(this).data('team-id');
        const count = counts[tid] || 0;
        $(this).find(`.team-tab-badge-${tid}`).text(count);
    });
}

// Open Modal to edit Team Name & Priority
function openEditTeamModal(teamId = null) {
    const targetId = teamId || currentActiveTabId;
    if (!targetId || targetId === 'all') {
        Swal.fire('Info', 'Please select a specific team tab to edit.', 'info');
        return;
    }

    const tabBtn = $(`#tab_team_${targetId}`);
    let teamName = '';
    let priority = '';

    if (tabBtn.length) {
        teamName = tabBtn.data('name') || tabBtn.find('.team-tab-name').text().trim();
        priority = tabBtn.data('priority') || '';
    } else {
        const opt = $(`#select_team_id option[value="${targetId}"]`);
        teamName = opt.data('name') || opt.text().trim();
        priority = opt.data('priority') || '';
    }

    $('#modal_edit_team_id').val(targetId);
    $('#modal_edit_team_title_name').text(teamName);
    $('#modal_edit_team_name').val(teamName);
    $('#modal_edit_priority').val(priority);
    $('#modal_edit_team_name_error').text('').hide();
    $('#modal_edit_team_name').removeClass('is-invalid');

    const modalEl = document.getElementById('editTeamModal');
    let modal = bootstrap.Modal.getInstance(modalEl);
    if (!modal) {
        modal = new bootstrap.Modal(modalEl);
    }
    modal.show();
}

// Submit Edit Team Modal via AJAX
function submitEditTeamModal() {
    const teamId = $('#modal_edit_team_id').val();
    const teamName = $('#modal_edit_team_name').val().trim();
    const priority = $('#modal_edit_priority').val();

    if (!teamName) {
        $('#modal_edit_team_name').addClass('is-invalid');
        $('#modal_edit_team_name_error').text('Team name is required.').show();
        return;
    }

    const btn = $('#btnSubmitEditTeam');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

    $.ajax({
        url: "{{ route('teams.update') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            team_id: teamId,
            team_name: teamName,
            priority: priority
        },
        success: function(response) {
            const modalEl = document.getElementById('editTeamModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            // Update tab button
            const tabBtn = $(`#tab_team_${teamId}`);
            if (tabBtn.length) {
                tabBtn.data('name', teamName);
                tabBtn.data('priority', priority);
                tabBtn.find('.team-tab-name').text(teamName);
            }

            // Update dropdown
            const opt = $(`#select_team_id option[value="${teamId}"]`);
            if (opt.length) {
                opt.text(teamName);
                opt.data('name', teamName);
                opt.data('priority', priority);
            }

            // Update active toolbar button & info ribbon
            if (currentActiveTabId == teamId) {
                $('#activeTabTeamNameText').text(teamName);
                $('#infoBarTeamName').text(teamName);
                $('#infoBarPriority').text(priority || '-');
            }

            // Update left form fields if this team is currently loaded
            if ($('#select_team_id').val() == teamId) {
                $('#team_name').val(teamName);
                $('#priority').val(priority);
                $('#formTitle').text('Edit: ' + teamName);
            }

            if (response.members) {
                window.allMembersData = response.members;
            }

            // Update team name badge inside table rows
            $(`#teamMembersTable tr[data-team-id="${teamId}"] .team-badge-name`).text(teamName);

            Swal.fire({
                icon: 'success',
                title: 'Team Updated!',
                text: `Team name updated to "${teamName}"`,
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            let msg = 'Failed to update team.';
            if (xhr.responseJSON) {
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
            btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Update Team');
        }
    });
}

function editTeamViaForm() {
    openEditTeamModal();
}

function unassignMemberFromTable(userId, userName) {
    Swal.fire({
        title: `Remove ${userName}?`,
        text: "This member will be unassigned from their current team.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, remove'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('teams.unassign.member') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: userId
                },
                success: function(response) {
                    if (response.members) {
                        window.allMembersData = response.members;
                        updateTableRowsFromMembers(response.members);
                        const currentTeamId = $('#team_id').val() || $('#select_team_id').val();
                        if (currentTeamId) {
                            renderMembersList(response.members, currentTeamId);
                        }
                    }
                    updateAllTabBadges();
                    filterTableByTeam(currentActiveTabId);

                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: response.message || 'Member unassigned.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Failed to unassign member.', 'error');
                }
            });
        }
    });
}

function editTeam(teamId, skipScroll = false) {
    if (!teamId) return;

    $.ajax({
        url: `/teams/${teamId}/edit`,
        method: 'GET',
        success: function (response) {
            $('#team_id').val(response.id);
            $('#team_name').val(response.team_name);
            $('#select_team_id').val(response.id);
            $('#priority').val(response.priority);

            if (response.members) {
                window.allMembersData = response.members;
                renderMembersList(response.members, response.id);
            }

            $('#formTitle').html('<i class="fas fa-edit text-warning me-2"></i>Configure: ' + response.team_name);
            $('#submitBtn').html('<i class="fas fa-save fs-8 me-1"></i> Save Team & Members');

            if (!skipScroll && $('#teamFormCard').length) {
                $('html, body').animate({
                    scrollTop: $('#teamFormCard').offset().top - 20
                }, 300);
            }
        },
        error: function (xhr) {
            let errorMessage = 'Failed to fetch team details.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        }
    });
}

// Checkbox selection count
$(document).on('change', '.member-checkbox', function() {
    updateSelectedCount();
});

// Select all only for UNLOCKED members
$('#selectAllMembersBtn').on('click', function() {
    $('#memberListContainer .member-item:visible .member-checkbox:not(:disabled)').prop('checked', true);
    updateSelectedCount();
});

// Clear all only for UNLOCKED members
$('#clearAllMembersBtn').on('click', function() {
    $('#memberListContainer .member-item:visible .member-checkbox:not(:disabled)').prop('checked', false);
    updateSelectedCount();
});

// Live filter
$(document).on('keyup', '#memberSearchInput', function() {
    var val = $(this).val().toLowerCase().trim();
    $('#memberListContainer .member-item').each(function() {
        var name = $(this).data('name') || $(this).text().toLowerCase();
        $(this).toggle(name.indexOf(val) > -1);
    });
});

// Submit teamForm
$('#teamForm').on('submit', function (e) {
    e.preventDefault();

    const teamId = $('#team_id').val() || $('#select_team_id').val();
    if (!teamId) {
        Swal.fire({
            icon: 'warning',
            title: 'Please Select a Team',
            text: 'Choose an existing team from the dropdown or click + to create a new team.'
        });
        return;
    }

    const selectedOption = $('#select_team_id option:selected');
    const teamName = selectedOption.data('name') || selectedOption.text().trim();

    const memberIds = $('.member-checkbox:checked:not(:disabled)').map(function () {
        return $(this).val();
    }).get();

    $('.member-checkbox:checked').each(function() {
        if (!memberIds.includes($(this).val())) {
            memberIds.push($(this).val());
        }
    });

    const formData = {
        _token: $('input[name="_token"]').val(),
        team_id: teamId,
        team_name: teamName,
        priority: $('#priority').val(),
        member_ids: memberIds
    };

    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true);

    $.ajax({
        url: '{{ route("teams.update") }}',
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.members) {
                window.allMembersData = response.members;
                renderMembersList(response.members, teamId);
                updateTableRowsFromMembers(response.members);
                updateAllTabBadges();
            }

            // Update tab button data
            const tabBtn = $(`#tab_team_${teamId}`);
            if (tabBtn.length) {
                tabBtn.data('name', teamName);
                tabBtn.data('priority', $('#priority').val());
                const count = tabBtn.find(`.team-tab-badge-${teamId}`).text();
                tabBtn.html(`<span class="team-tab-name">${teamName}</span> <span class="team-tab-badge team-tab-badge-${teamId}">${count}</span>`);
            }

            // Update dropdown option
            $(`#select_team_id option[value="${teamId}"]`)
                .text(teamName)
                .data('name', teamName)
                .data('priority', $('#priority').val());

            // Update info bar if active
            if (currentActiveTabId == teamId) {
                $('#activeTabTeamNameText').text(teamName);
                $('#infoBarTeamName').text(teamName);
                $('#infoBarPriority').text($('#priority').val() || '-');
            }

            // Instant re-filter active table
            filterTableByTeam(currentActiveTabId);

            Swal.fire({
                icon: 'success',
                title: 'Saved Successfully',
                text: `Team "${teamName}" members have been updated!`,
                timer: 1600,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            let errorMessage = 'Failed to submit the form. Please check your input.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            submitBtn.prop('disabled', false);
        }
    });
});

$(document).ready(function() {
    updateSelectedCount();
    // Default to 'all' tab
    switchTeamTab('all');
});
</script>
@endsection
