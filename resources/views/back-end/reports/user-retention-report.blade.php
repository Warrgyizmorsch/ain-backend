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
                    <input type="number"
                        name="year"
                        value="{{ $year }}"
                        class="form-control form-control-solid"
                        placeholder="Year">
                </div>

                <div class="col-md-4">
                    <input type="text"
                        name="user"
                        value="{{ request('user') }}"
                        class="form-control form-control-solid"
                        placeholder="Search Name / Email / Mobile">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-sm btn-primary">Search</button>
                    <a href="{{ route('user.retention.report') }}" class="btn btn-sm btn-light-danger">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-flush">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="fw-bold bg-light">
                        <th>Month</th>
                        {{-- <th>New Users</th> --}}
                        <th>Users</th>
                        <th>Retain Users</th>
                        <th>Not Retain Users</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($months as $month)
                        <tr>
                            <td class="fw-bold">{{ $month['month_name'] }}</td>

                            {{-- <td>
                                <button class="btn btn-sm btn-light-primary"
                                    onclick="toggleUserList('new-{{ $month['month_no'] }}')">
                                    {{ $month['new_users']->count() }}
                                </button>
                            </td> --}}

                            <td>
                                @php
                                    $allUsers = $month['retain_users']->merge($month['not_retain_users']);
                                    $totalUsersCount = $allUsers->count();
                                @endphp

                                <button class="btn btn-sm btn-light-dark"
                                    onclick="toggleUserList('users-{{ $month['month_no'] }}')">
                                    {{ $totalUsersCount }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-success"
                                    onclick="toggleUserList('retain-{{ $month['month_no'] }}')">
                                    {{ $month['retain_users']->count() }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-danger"
                                    onclick="toggleUserList('not-retain-{{ $month['month_no'] }}')">
                                    {{ $month['not_retain_users']->count() }}
                                </button>
                            </td>
                        </tr>

                        {{-- <tr id="new-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                            <td colspan="4">
                                @include('back-end.reports.partials.user-list', [
                                    'users' => $month['new_users'],
                                    'title' => $month['month_name'].' - New Users'
                                ])
                            </td>
                        </tr> --}}

                        <tr id="users-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                            <td colspan="5">
                                @include('back-end.reports.partials.user-list', [
                                    'users' => $month['retain_users']->merge($month['not_retain_users']),
                                    'title' => $month['month_name'].' - Users'
                                ])
                            </td>
                        </tr>

                        <tr id="retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                            <td colspan="4">
                                @include('back-end.reports.partials.user-list', [
                                    'users' => $month['retain_users'],
                                    'title' => $month['month_name'].' - Retain Users'
                                ])
                            </td>
                        </tr>

                        <tr id="not-retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                            <td colspan="4">
                                @include('back-end.reports.partials.user-list', [
                                    'users' => $month['not_retain_users'],
                                    'title' => $month['month_name'].' - Not Retain Users'
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleUserList(rowId) {
    $('.user-detail-row').not('#' + rowId).hide();
    $('#' + rowId).toggle();
}
</script>

@endsection