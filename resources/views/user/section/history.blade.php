<div class="bg-light p-4 rounded border">

    <h5>User History</h5>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Type</th>
                <th>ID</th>
                <th>Status</th>
                <th>Note</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @forelse($history as $h)
                <tr>
                    <td>{{ $h->type }}</td>
                    <td>#{{ $h->reference_id }}</td>
                    <td>{{ $h->status ?? 'Pending' }}</td>
                    <td>{{ \Str::limit($h->note, 40) }}</td>
                    <td>{{ \Carbon\Carbon::parse($h->action_date)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No history found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>