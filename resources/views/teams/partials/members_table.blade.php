<table class="table table-bordered table-striped align-middle fs-7 gy-2 gs-3 mb-0" id="teamMembersTable">
    <thead>
        <tr class="bg-light text-gray-700 fw-bolder fs-7 text-uppercase gs-0">
            <th class="text-center" style="width: 45px;">#</th>
            <th style="min-width: 150px;">Member Name</th>
            <th class="text-center" style="width: 100px;">Role</th>
            <th style="min-width: 160px;">Contact / Email</th>
            <th class="text-center" style="width: 130px;">Assigned Team</th>
            <th class="text-center" style="width: 80px;">Status</th>
            <th class="text-center" style="width: 75px;">Action</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-700">
        @forelse($members as $m)
            <tr class="member-table-row" data-member-id="{{ $m->id }}" data-team-id="{{ $m->team_id ?? '' }}">
                <td class="text-center fw-bold text-gray-500 row-index">{{ $loop->iteration }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-30px symbol-circle me-2 bg-light-primary text-primary fw-bolder fs-7 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 50%;">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-900 fw-bold fs-7">{{ $m->name }}</span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge {{ $m->role_id == 9 ? 'badge-light-warning text-warning' : 'badge-light-primary text-primary' }} fw-bold fs-8 px-2 py-1">
                        {{ $m->role_id == 9 ? 'Subadmin' : 'Marketing' }}
                    </span>
                </td>
                <td>
                    <div class="text-gray-800 fs-8">{{ $m->email ?? '-' }}</div>
                    @if(!empty($m->mobile_no))
                        <div class="text-muted fs-9">{{ $m->countrycode ? '+'.$m->countrycode.' ' : '' }}{{ $m->mobile_no }}</div>
                    @endif
                </td>
                <td class="text-center team-cell">
                    @if($m->team)
                        <span class="badge badge-light-success text-success fw-bold fs-8 px-2.5 py-1 team-badge">
                            <i class="fas fa-layer-group fs-9 me-1"></i> <span class="team-badge-name">{{ $m->team->team_name }}</span>
                        </span>
                    @else
                        <span class="badge badge-light-secondary text-muted fs-8 px-2 py-1 team-badge">Unassigned</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge badge-light-success fw-bold fs-9">Active</span>
                </td>
                <td class="text-center action-cell">
                    @if($m->team_id)
                        <button type="button" 
                                class="btn btn-icon btn-sm btn-light-danger w-25px h-25px btn-unassign" 
                                onclick="unassignMemberFromTable({{ $m->id }}, '{{ addslashes($m->name) }}')" 
                                title="Remove from Team">
                            <i class="fas fa-user-minus fs-9"></i>
                        </button>
                    @else
                        <span class="text-muted fs-9 no-action">-</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr id="initialEmptyRow">
                <td colspan="7" class="text-center py-6 text-muted fs-7">
                    <i class="fas fa-user-friends fs-2 text-muted mb-2 d-block opacity-50"></i>
                    No staff members found.
                </td>
            </tr>
        @endforelse
        <tr id="emptyMembersRow" style="display: none;">
            <td colspan="7" class="text-center py-6 text-muted fs-7">
                <i class="fas fa-user-friends fs-2 text-muted mb-2 d-block opacity-50"></i>
                <span id="emptyMembersText">No members assigned to this team yet.</span>
            </td>
        </tr>
    </tbody>
</table>
