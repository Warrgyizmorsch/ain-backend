@extends('layouts.app')

@section('content')

<div class="card card-flush mb-5">
    <div class="card-header">
        <h3 class="card-title fw-bold">User Retention Report</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('user.retention.report') }}">
            <div class="row g-3">

                <div class="col-md-3">
                    <select name="type" class="form-select form-select-solid">
                        <option value="new" {{ $type == 'new' ? 'selected' : '' }}>
                            New Users
                        </option>
                        <option value="retain" {{ $type == 'retain' ? 'selected' : '' }}>
                            Retain Users
                        </option>
                        <option value="not_retain" {{ $type == 'not_retain' ? 'selected' : '' }}>
                            Not Retain Users
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text"
                        name="user"
                        value="{{ request('user') }}"
                        class="form-control form-control-solid"
                        placeholder="Search Name / Email / Mobile">
                </div>

                <div class="col-md-2">
                    <input type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-2">
                    <input type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary">Search</button>
                    <a href="{{ route('user.retention.report') }}" class="btn btn-sm btn-light-danger">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="card card-flush">
    <div class="card-header">
        <h4 class="card-title fw-bold">
            @php
                $title = $type == 'new'
                    ? 'New Users'
                    : ($type == 'retain' ? 'Retain Users' : 'Not Retain Users');
            @endphp

            {{ $title }}
            <span class="badge badge-light-primary ms-2">{{ $users->count() }}</span>
        </h4>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="fw-bold bg-light">
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
                                    {{ $user->total_orders }}
                                </span>
                            </td>

                            <td>
                                {{ $user->last_order_date ? \Carbon\Carbon::parse($user->last_order_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

@endsection