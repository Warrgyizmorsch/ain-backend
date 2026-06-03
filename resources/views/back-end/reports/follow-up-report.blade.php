@extends('layouts.app')

@section('content')

<div class="card card-flush mb-5">
    <div class="card-header">
        <h3 class="card-title fw-bold">Follow Up Report</h3>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('follow.up.report') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="number" name="year" value="{{ $year }}"
                        class="form-control form-control-solid" placeholder="Year">
                </div>

                <div class="col-md-3">
                    <input type="date" name="from_date"
                        value="{{ request('from_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-3">
                    <input type="date" name="to_date"
                        value="{{ request('to_date') }}"
                        class="form-control form-control-solid">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-sm btn-primary">Search</button>
                    <a href="{{ route('follow.up.report') }}" class="btn btn-sm btn-light-danger">Reset</a>
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
                        <th>Total Users</th>
                        <th>Follow Up Users</th>
                        <th>Negative But Convinced</th>
                        <th>Negative</th>
                        <th>Positive</th>
                        <th>Positive & Referral</th>
                        <th>Positive & Own Order</th>
                        <th>No Response</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($months as $month)
                        <tr>
                            <td class="fw-bold">{{ $month['month_name'] }}</td>

                            <td>
                                <button class="btn btn-sm btn-light-dark fw-bold px-4"
                                    onclick="toggleFollowupList('total-users-{{ $month['month_no'] }}')">
                                    {{ $month['total_users'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-primary fw-bold px-4"
                                    onclick="toggleFollowupList('follow-users-{{ $month['month_no'] }}')">
                                    {{ $month['followup_users'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-warning fw-bold"
                                    onclick="toggleFollowupList('negative-convinced-{{ $month['month_no'] }}')">
                                    {{ $month['negative_but_convinced'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-danger fw-bold"
                                    onclick="toggleFollowupList('negative-{{ $month['month_no'] }}')">
                                    {{ $month['negative'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-success fw-bold"
                                    onclick="toggleFollowupList('positive-{{ $month['month_no'] }}')">
                                    {{ $month['positive'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-info fw-bold"
                                    onclick="toggleFollowupList('positive-referral-{{ $month['month_no'] }}')">
                                    {{ $month['positive_referral'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-success fw-bold"
                                    onclick="toggleFollowupList('positive-own-order-{{ $month['month_no'] }}')">
                                    {{ $month['positive_own_order'] }}
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-warning fw-bold"
                                    onclick="toggleFollowupList('no-response-{{ $month['month_no'] }}')">
                                    {{ $month['no_response'] }}
                                </button>
                            </td>
                        </tr>

                        <tr id="total-users-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['total_users_list'],
                                    'title' => $month['month_name'].' - Total Users'
                                ])
                            </td>
                        </tr>

                        <tr id="follow-users-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['followup_users_list'],
                                    'title' => $month['month_name'].' - Follow Up Users'
                                ])
                            </td>
                        </tr>

                        <tr id="negative-convinced-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['negative_but_convinced_users'],
                                    'title' => $month['month_name'].' - Negative But Convinced'
                                ])
                            </td>
                        </tr>

                        <tr id="negative-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['negative_users'],
                                    'title' => $month['month_name'].' - Negative Users'
                                ])
                            </td>
                        </tr>

                        <tr id="positive-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['positive_users'],
                                    'title' => $month['month_name'].' - Positive Users'
                                ])
                            </td>
                        </tr>

                        <tr id="positive-referral-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['positive_referral_users'],
                                    'title' => $month['month_name'].' - Positive & Referral Users'
                                ])
                            </td>
                        </tr>

                        <tr id="positive-own-order-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['positive_own_order_users'],
                                    'title' => $month['month_name'].' - Positive & Own Order Users'
                                ])
                            </td>
                        </tr>

                        <tr id="no-response-{{ $month['month_no'] }}" class="followup-detail-row" style="display:none;">
                            <td colspan="9">
                                @include('back-end.reports.partials.followup-user-list', [
                                    'users' => $month['no_response_users'],
                                    'title' => $month['month_name'].' - No Response Users'
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
function toggleFollowupList(rowId) {
    $('.followup-detail-row').not('#' + rowId).hide();
    $('#' + rowId).toggle();
}
</script>

@endsection