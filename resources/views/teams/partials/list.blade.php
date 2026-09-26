<table class="table align-middle table-row-dashed fs-7 gy-3 gs-3 mb-0 w-100" id="teamsListTable">
    <thead>
        <tr class="text-start text-gray-600 fw-bolder fs-7 text-uppercase gs-0 bg-light-primary rounded">
            <th class="text-center ps-3" style="width: 40px;">#</th>
            <th style="min-width: 130px;">Team Name</th>
            <th class="text-center" style="width: 75px;">Priority</th>
            <th class="text-center" style="width: 90px;">Allocation</th>
            <th style="min-width: 180px;">Assigned Members</th>
            <th style="min-width: 110px; white-space: nowrap;">Created By</th>
            <th class="text-center pe-3" style="width: 90px; white-space: nowrap;">Action</th>
        </tr>
    </thead>
    <tbody class="fw-bold text-gray-700">
    @forelse($teams as $team)
        <tr class="border-bottom border-gray-200">
            <td class="text-center ps-3">
                <span class="badge badge-light-secondary text-gray-700 fw-bolder fs-8">{{ $loop->iteration }}</span>
            </td>
            <td style="white-space: nowrap;">
                <div class="d-flex align-items-center">
                    <span class="symbol symbol-30px me-2">
                        <span class="symbol-label bg-light-primary text-primary fw-bolder fs-6">
                            {{ strtoupper(substr($team->team_name, 0, 1)) }}
                        </span>
                    </span>
                    <div>
                        <span class="text-gray-900 fw-bolder fs-6">{{ $team->team_name }}</span>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <span class="badge badge-light-dark fw-bolder fs-7 px-2 py-1">
                    {{ $team->priority ?? '-' }}
                </span>
            </td>
            <td class="text-center">
                <span class="badge badge-light-success text-success fw-bolder fs-7 px-2.5 py-1">
                    {{ floatval($team->percentage ?? 0) }}%
                </span>
            </td>
            <td>
                <div class="d-flex flex-wrap gap-1 align-items-center py-1">
                    @forelse($team->members as $m)
                        <span class="badge badge-light-primary text-primary fw-semibold fs-8 py-1 px-2 d-inline-flex align-items-center" style="white-space: nowrap;">
                            <i class="fas fa-user-circle fs-9 me-1 opacity-75"></i>
                            {{ $m->name }}
                            <span class="badge badge-white text-muted fs-9 ms-1 py-0 px-1 border border-gray-200">
                                {{ $m->role_id == 9 ? 'Subadmin' : 'Mkt' }}
                            </span>
                        </span>
                    @empty
                        <span class="text-muted fs-8 fst-italic">No members assigned</span>
                    @endforelse
                </div>
            </td>
            <td style="white-space: nowrap;">
                <span class="text-gray-800 fs-7 fw-semibold">
                    <i class="fas fa-user-shield text-muted fs-9 me-1"></i>{{ $team->creator->name ?? 'Admin' }}
                </span>
            </td>
            <td class="text-center pe-3" style="white-space: nowrap;">
                <div class="d-inline-flex gap-1 justify-content-center align-items-center">
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-light-primary w-30px h-30px" 
                            onclick="editTeam({{ $team->id }})" 
                            title="Edit Team"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-edit fs-7"></i>
                    </button>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-light-danger w-30px h-30px" 
                            onclick="deleteTeam({{ $team->id }})" 
                            title="Delete Team"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-trash-alt fs-7"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-6 text-muted fs-7">
                <i class="fas fa-users-slash fs-2 text-muted mb-2 d-block"></i>
                No active teams found. Create your first team using the form on the left.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
