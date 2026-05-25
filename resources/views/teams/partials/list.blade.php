<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Team</th>
            <th>Priority</th>
            <th>%</th>
            <th>Members</th>
            <th>Created By</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($teams as $team)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $team->team_name }}</td>
            <td>{{ $team->priority ?? '-' }}</td>
            <td>{{ $team->percentage ?? '-' }}%</td>
            <td>
                @foreach($team->members as $m)
                    <span class="badge bg-primary my-1">{{ $m->name }}</span>
                @endforeach
            </td>
            <td>{{ $team->creator->name ?? 'N/A' }}</td>
            <td>
                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="editTeam({{ $team->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" onclick="deleteTeam({{ $team->id }})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No teams available</td>
        </tr>
    @endforelse
    </tbody>
</table>
