<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 60px;">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $key => $user)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $user->user_name }}</td>
            <td>{{ mask_email_for_display($user->email) }}</td>
            <td>{{ mask_phone_for_display(null, $user->mobile) }}</td>
            <td> {{ \Carbon\Carbon::parse($user->create_at)->format('d M Y h:i A') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">No Data Found</td>
        </tr>
        @endforelse
    </tbody>
</table>