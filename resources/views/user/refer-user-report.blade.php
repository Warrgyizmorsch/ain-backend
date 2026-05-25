@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Refer User Report</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>
                        Refer Count
                        @if($sort == 'desc')
                            <a href="{{ route('refer.user.report', ['sort' => 'asc']) }}">↑</a>
                        @else
                            <a href="{{ route('refer.user.report', ['sort' => 'desc']) }}">↓</a>
                        @endif
                    </th>
                </tr>
            </thead>

            <tbody>
            @forelse($users as $key => $user)
                <tr>
                    <td>{{ $users->firstItem() + $key }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_no }}</td>
                    <td>
                        @if($user->refer_count > 0)
                            <button type="button"
                                    class="btn btn-sm btn-light-primary toggle-refer-users"
                                    data-target="refer-users-{{ $user->id }}">
                                {{ $user->refer_count }}
                            </button>
                        @else
                            <span class="badge badge-light-secondary">0</span>
                        @endif
                    </td>
                </tr>

                @if($user->refer_count > 0)
                    <tr id="refer-users-{{ $user->id }}" style="display:none;">
                        <td colspan="5">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="mb-3">
                                        Referred Users By: {{ $user->name }}
                                    </h6>

                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->referredUsers as $rKey => $refUser)
                                                <tr>
                                                    <td>{{ $rKey + 1 }}</td>
                                                    <td>{{ $refUser->name }}</td>
                                                    <td>{{ $refUser->email }}</td>
                                                    <td>{{ $refUser->mobile_no }}</td>
                                                    <td>{{ $refUser->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data found</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-refer-users')) {
        let targetId = e.target.getAttribute('data-target');
        let row = document.getElementById(targetId);

        if (row.style.display === 'none') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }
});
</script>

@endsection