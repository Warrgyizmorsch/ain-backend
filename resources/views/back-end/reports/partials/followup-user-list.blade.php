<div class="p-3 bg-light rounded">
    <h5 class="fw-bold mb-3">{{ $title }}</h5>

    <div class="table-responsive" style="max-height: 420px; overflow-y: auto; overflow-x: auto;">
        <table class="table table-sm table-bordered align-middle mb-0">
            <thead style="position: sticky; top: 0; z-index: 5;">
                <tr class="fw-bold bg-white">
                    <th>#</th>
                    <th>User</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Order ID</th>
                    <th>Follow Status</th>
                    <th>Comment</th>
                    <th>Follow Up By</th>
                    <th>Follow Up Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td class="fw-bold text-gray-800">
                            {{ $user->name ?? 'N/A' }}
                        </td>

                        <td>
                            <span class="badge badge-light-danger">
                                +{{ $user->countrycode }} {{ $user->mobile_no }}
                            </span>
                        </td>

                        <td>{{ $user->email ?? 'N/A' }}</td>

                        <td>
                            <span class="badge badge-light-primary">
                                {{ $user->order_id ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            @if(!empty($user->follow_status))
                                <span class="badge badge-light-success">
                                    {{ $user->follow_status }}
                                </span>
                            @else
                                <span class="badge badge-light-danger">N/A</span>
                            @endif
                        </td>

                        <td>{{ $user->follow_comment ?? 'N/A' }}</td>

                        <td>{{ $user->follow_up_user ?? 'N/A' }}</td>

                        <td>{{ $user->followupdate ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>