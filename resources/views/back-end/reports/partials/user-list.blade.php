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
                    <th>Total Orders</th>
                    <th>Last Order Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="fw-bold">{{ $user->name ?? 'N/A' }}</td>
                        <td>+{{ $user->countrycode }} {{ $user->mobile_no }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-light-primary">
                                {{ $user->total_orders }}
                            </span>
                        </td>
                        <td>
                            {{ $user->last_order_date ? \Carbon\Carbon::parse($user->last_order_date)->format('d M Y') : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>