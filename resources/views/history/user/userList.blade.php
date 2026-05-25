<div class="table-responsive table-product">
    <table class="table all-package theme-table dataTable no-footer" id="table_id">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>User</th>
                <th>Logged In?</th>
                <th>IP</th>
                <th>Device</th>
                <th>Last Login</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                @php
                    $session = $sessions[$user->id] ?? null;
                    $latestLogin = $user->loginHistories->last();
                    $roleMap = $userRole->pluck('role', 'id');
                @endphp
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td><span>{{ $user->name }}</span> <br/> <span style="padding: 2px 5px; border-radius: 10px; border: 1px solid red; color: red; font-weight: 600; font-size: 9px;">{{ $roleMap[$user->role_id] ?? 'Unknown' }}</span></td>

                    <td>
                        <span class="badge {{ $session ? 'bg-success' : 'bg-danger' }}">
                            {{ $session ? 'Yes' : 'No' }}
                        </span>
                    </td>

                    <td>{{ optional($latestLogin)->ip_address ?? '-' }}</td>

                    <td>{{ Str::limit(optional($latestLogin)->user_agent, 40) ?? '-' }}</td>

                    <td>{{ optional(optional($latestLogin)->created_at)->diffForHumans() ?? '-' }}</td>

                    <td>
                        <div class="action-btn">
                            <a href="{{ route('admin.users.history', $user->id) }}" class="badge bg-primary text-white" title="View History">
                            <li class="fa fa-history"></li>
                        </a>

                        @if ($session)
                            <form method="POST" action="{{ route('admin.users.logout', $user) }}">
                                @csrf
                                <button type="submit" class="badge bg-danger border-0" title="Logout User">
                                    <li class="fa fa-sign-out"></li>
                                </button>
                            </form>
                        @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No users are currently logged in.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="m-4">
    {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
</div>

<!-- Table styling -->
 <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 10px !important;
            min-width: 50px;
            border: 1px solid #dee2e6 !important;
        }
        table th {
            font-weight: bold !important;
        }
        .action-btn {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
    </style>