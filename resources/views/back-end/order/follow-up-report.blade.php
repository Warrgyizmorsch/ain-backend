@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Follow Up Report</h3>
    </div>

    <div class="card-body">

        <form method="GET" action="{{ route('follow.up.report') }}" class="mb-5">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date"
                           name="from_date"
                           value="{{ request('from_date') }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date"
                           name="to_date"
                           value="{{ request('to_date') }}"
                           class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('follow.up.report') }}" class="btn btn-sm btn-danger">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Follow Up Count</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($followupUsers as $key => $item)

                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $item->userData->name ?? 'N/A' }}</td>

                            <td>{{ $item->userData->email ?? 'N/A' }}</td>

                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-light-primary toggle-followups"
                                        data-target="followups-{{ $item->created_by }}">
                                    {{ $item->followup_count }}
                                </button>
                            </td>
                        </tr>

                        <tr id="followups-{{ $item->created_by }}" style="display:none;">
                            <td colspan="4">

                                <div class="card bg-light">
                                    <div class="card-body p-3">

                                        <h5 class="mb-3">
                                            Follow Ups By: {{ $item->userData->name ?? 'N/A' }}
                                        </h5>

                                        <div class="table-responsive"
                                             style="max-height:500px; overflow-y:auto; overflow-x:auto; border:1px solid #ddd; border-radius:8px;">

                                            <table class="table table-sm table-bordered mb-0">

                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Order ID</th>
                                                        <th>Client</th>
                                                        <th>Comment</th>
                                                        <th>Status</th>
                                                        <th>Follow Up Date</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($item->followups as $fKey => $followup)

                                                        <tr>
                                                            <td>{{ $fKey + 1 }}</td>

                                                            <td>
                                                                {{ $followup->order->order_id ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $followup->order->user->name ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{ $followup->comment ?? $followup->action_comment }}
                                                            </td>

                                                            <td>
                                                                {{ $followup->status ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                @if($followup->updated_at)
                                                                    {{ \Carbon\Carbon::parse($followup->updated_at)->format('d M Y, h:i A') }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                </tbody>

                                            </table>

                                        </div>

                                    </div>
                                </div>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                No follow up found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-followups')) {
        let targetId = e.target.getAttribute('data-target');
        let row = document.getElementById(targetId);

        row.style.display = row.style.display === 'none'
            ? 'table-row'
            : 'none';
    }
});
</script>

@endsection