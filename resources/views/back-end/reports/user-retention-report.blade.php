@extends('layouts.app')

@section('content')

@php
    $currentCohort = $cohortType ?? 'order';
@endphp

<div class="card card-flush mb-5">
    <div class="card-header pt-4 pb-2">
        <h3 class="card-title fw-bold text-gray-800">
            <i class="fa fa-user-clock text-primary me-2"></i> Customer Retention & Inactivity Report
        </h3>
    </div>

    <div class="card-body pt-0 pb-4">
        <form method="GET" action="{{ route('user.retention.report') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Report / Cohort Basis</label>
                    <select name="cohort_type" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="order" {{ $currentCohort === 'order' ? 'selected' : '' }}>
                            Lapsed Customers (By Order Month - 0 Orders Later)
                        </option>
                        <option value="registration" {{ $currentCohort === 'registration' ? 'selected' : '' }}>
                            User Registration Cohort (By Account Signup Date)
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Year</label>
                    <input type="number"
                        name="year"
                        value="{{ $year }}"
                        class="form-control form-control-solid"
                        placeholder="Year">
                </div>

                <div class="col-md-4">
                    <label class="form-label fs-8 fw-bold text-gray-700 mb-1">Search Customer</label>
                    <input type="text"
                        name="user"
                        value="{{ request('user') }}"
                        class="form-control form-control-solid"
                        placeholder="Search Name / Email / Mobile">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('user.retention.report') }}" class="btn btn-light-danger" title="Reset Filters">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-flush">
    <div class="card-header border-0 pt-4 pb-2 d-flex align-items-center justify-content-between">
        <h3 class="card-title fw-bold mb-0">
            @if($currentCohort === 'order')
                Monthly Customer Retention & Lapsed Summary ({{ $year }})
            @else
                Monthly Registration Retention Summary ({{ $year }})
            @endif
        </h3>
        @if(auth()->check() && (int) auth()->user()->role_id === 1)
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-file-excel me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportRetentionSummaryTable()"><i class="fa fa-table me-2 text-primary"></i>Export Summary Table</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportAllRetainUsers()"><i class="fa fa-user-check me-2 text-success"></i>Export All Retained Customers ({{ $year }})</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportAllNotRetainUsers()"><i class="fa fa-user-times me-2 text-danger"></i>Export All Lapsed Customers ({{ $year }})</a></li>
                </ul>
            </div>
        </div>
        @endif
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="retentionSummaryTable">
                <thead>
                    <tr class="fw-bold bg-light">
                        <th>Month</th>
                        @if($currentCohort === 'order')
                            <th>Total Ordering Customers</th>
                            <th>Retained Customers (Ordered Later)</th>
                            <th>Lapsed Customers (0 Orders After Month)</th>
                        @else
                            <th>New Users</th>
                            <th>Users</th>
                            <th>Retain Users</th>
                            <th>Not Retain Users</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach($months as $month)
                        @if($currentCohort === 'order')
                            <tr>
                                <td class="fw-bold text-dark">{{ $month['month_name'] }}</td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-primary fw-bold"
                                        onclick="toggleUserList('all-{{ $month['month_no'] }}')"
                                        title="Click to view all customers who ordered in {{ $month['month_name'] }}">
                                        {{ $month['ordering_users']->count() }}
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-success fw-bold"
                                        onclick="toggleUserList('retain-{{ $month['month_no'] }}')"
                                        title="Click to view customers who placed orders after {{ $month['month_name'] }}">
                                        <i class="fa fa-check text-success me-1"></i>{{ $month['retain_users']->count() }}
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-danger fw-bolder"
                                        onclick="toggleUserList('not-retain-{{ $month['month_no'] }}')"
                                        title="Click to view customers who ordered in {{ $month['month_name'] }} but placed 0 orders afterwards">
                                        <i class="fa fa-user-times text-danger me-1"></i>{{ $month['not_retain_users']->count() }} Lapsed
                                    </button>
                                </td>
                            </tr>

                            <tr id="all-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="4">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['ordering_users'],
                                        'title' => $month['month_name'].' - All Ordering Customers'
                                    ])
                                </td>
                            </tr>

                            <tr id="retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="4">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['retain_users'],
                                        'title' => $month['month_name'].' - Retained Customers (Ordered Later)'
                                    ])
                                </td>
                            </tr>

                            <tr id="not-retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="4">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['not_retain_users'],
                                        'title' => $month['month_name'].' - Lapsed Customers (0 Orders After ' . $month['month_name'] . ')'
                                    ])
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="fw-bold">{{ $month['month_name'] }}</td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-primary"
                                        onclick="toggleUserList('new-{{ $month['month_no'] }}')">
                                        {{ $month['new_users']->count() }}
                                    </button>
                                </td>

                                <td>
                                    @php
                                        $allUsers = $month['retain_users']->merge($month['not_retain_users']);
                                        $totalUsersCount = $allUsers->count();
                                    @endphp

                                    <button type="button" class="btn btn-sm btn-light-dark"
                                        onclick="toggleUserList('users-{{ $month['month_no'] }}')">
                                        {{ $totalUsersCount }}
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-success"
                                        onclick="toggleUserList('retain-{{ $month['month_no'] }}')">
                                        {{ $month['retain_users']->count() }}
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-light-danger"
                                        onclick="toggleUserList('not-retain-{{ $month['month_no'] }}')">
                                        {{ $month['not_retain_users']->count() }}
                                    </button>
                                </td>
                            </tr>

                            <tr id="new-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="5">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['new_users'],
                                        'title' => $month['month_name'].' - New Users'
                                    ])
                                </td>
                            </tr>

                            <tr id="users-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="5">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['retain_users']->merge($month['not_retain_users']),
                                        'title' => $month['month_name'].' - Users'
                                    ])
                                </td>
                            </tr>

                            <tr id="retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="5">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['retain_users'],
                                        'title' => $month['month_name'].' - Retain Users'
                                    ])
                                </td>
                            </tr>

                            <tr id="not-retain-{{ $month['month_no'] }}" class="user-detail-row" style="display:none;">
                                <td colspan="5">
                                    @include('back-end.reports.partials.user-list', [
                                        'users' => $month['not_retain_users'],
                                        'title' => $month['month_name'].' - Not Retain Users'
                                    ])
                                </td>
                            </tr>
                        @endif
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

@if(auth()->check() && (int) auth()->user()->role_id === 1)
function exportRetentionUserList(btn, title) {
    var table = $(btn).closest('.p-3').find('table.retention-user-table')[0];
    if (!table) return;

    var csv = [];
    var headerRow = [];
    $(table).find('thead tr th:not(.no-export)').each(function() {
        headerRow.push('"' + $(this).text().trim().replace(/"/g, '""') + '"');
    });
    csv.push(headerRow.join(','));

    $(table).find('tbody tr').each(function() {
        var row = [];
        var tds = $(this).find('td:not(.no-export)');
        if (tds.length === 1 && $(tds[0]).attr('colspan')) {
            return;
        }
        tds.each(function() {
            var text = $(this).text().trim().replace(/\s+/g, ' ');
            text = text.replace(/"/g, '""');
            row.push('"' + text + '"');
        });
        if (row.length > 0) {
            csv.push(row.join(','));
        }
    });

    downloadCSV(csv, (title || 'User_Retention_List') + '.csv');
}

function exportRetentionSummaryTable() {
    var table = $('#retentionSummaryTable')[0];
    if (!table) return;

    var csv = [];
    var headerRow = [];
    $(table).find('thead tr th:not(.no-export)').each(function() {
        headerRow.push('"' + $(this).text().trim().replace(/"/g, '""') + '"');
    });
    csv.push(headerRow.join(','));

    $(table).find('tbody > tr').not('.user-detail-row').each(function() {
        var row = [];
        $(this).find('td:not(.no-export)').each(function() {
            var text = $(this).text().trim().replace(/\s+/g, ' ');
            text = text.replace(/"/g, '""');
            row.push('"' + text + '"');
        });
        if (row.length > 0) {
            csv.push(row.join(','));
        }
    });

    downloadCSV(csv, 'User_Retention_Summary_{{ $year }}.csv');
}

function exportAllRetainUsers() {
    var csv = [];
    csv.push('"Month","#","Customer","Mobile","Email","Lifetime Orders","Last Order Date","Inactivity"');

    $('tr[id^="retain-"]').each(function() {
        var monthTitle = $(this).find('h5').text().trim();
        var monthName = monthTitle.split(' - ')[0] || '';
        $(this).find('tbody tr').each(function() {
            var tds = $(this).find('td:not(.no-export)');
            if (tds.length === 1 && $(tds[0]).attr('colspan')) return;
            var row = ['"' + monthName + '"'];
            tds.each(function() {
                var text = $(this).text().trim().replace(/\s+/g, ' ').replace(/"/g, '""');
                row.push('"' + text + '"');
            });
            if (row.length > 1) {
                csv.push(row.join(','));
            }
        });
    });

    downloadCSV(csv, 'All_Retained_Customers_{{ $year }}.csv');
}

function exportAllNotRetainUsers() {
    var csv = [];
    csv.push('"Month","#","Customer","Mobile","Email","Lifetime Orders","Last Order Date","Inactivity"');

    $('tr[id^="not-retain-"]').each(function() {
        var monthTitle = $(this).find('h5').text().trim();
        var monthName = monthTitle.split(' - ')[0] || '';
        $(this).find('tbody tr').each(function() {
            var tds = $(this).find('td:not(.no-export)');
            if (tds.length === 1 && $(tds[0]).attr('colspan')) return;
            var row = ['"' + monthName + '"'];
            tds.each(function() {
                var text = $(this).text().trim().replace(/\s+/g, ' ').replace(/"/g, '""');
                row.push('"' + text + '"');
            });
            if (row.length > 1) {
                csv.push(row.join(','));
            }
        });
    });

    downloadCSV(csv, 'All_Lapsed_Customers_{{ $year }}.csv');
}

function downloadCSV(csvArray, filename) {
    var csvContent = "\uFEFF" + csvArray.join("\r\n");
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var link = document.createElement("a");
    var cleanFilename = filename.replace(/[^a-zA-Z0-9_\-\.]/g, '_');
    link.setAttribute("href", url);
    link.setAttribute("download", cleanFilename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
@endif
</script>

@endsection
