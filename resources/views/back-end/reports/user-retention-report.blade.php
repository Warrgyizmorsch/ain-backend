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
    <div class="card-header border-0 pt-4 pb-2 d-flex align-items-center justify-content-between">
        <h3 class="card-title fw-bold mb-0">Monthly Retention Summary ({{ $year }})</h3>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-file-excel me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportRetentionSummaryTable()"><i class="fa fa-table me-2 text-primary"></i>Export Summary Table</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportAllRetainUsers()"><i class="fa fa-user-check me-2 text-success"></i>Export All Retain Users ({{ $year }})</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportAllNotRetainUsers()"><i class="fa fa-user-times me-2 text-danger"></i>Export All Not Retain Users ({{ $year }})</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="retentionSummaryTable">
                <thead>
                    <tr class="fw-bold bg-light">
                        <th>Month</th>
                        <th>New Users</th>
                        <th>Users</th>
                        <th>Retain Users</th>
                        <th>Not Retain Users</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($months as $month)
                        <tr>
                            <td class="fw-bold">{{ $month['month_name'] }}</td>

                            <td>
                                <button class="btn btn-sm btn-light-primary"
                                    onclick="toggleUserList('new-{{ $month['month_no'] }}')">
                                    {{ $month['new_users']->count() }}
                                </button>
                            </td>

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

function exportRetentionUserList(btn, title) {
    var table = $(btn).closest('.p-3').find('table.retention-user-table')[0];
    if (!table) return;

    var csv = [];
    var headerRow = [];
    $(table).find('thead tr th').each(function() {
        headerRow.push('"' + $(this).text().trim().replace(/"/g, '""') + '"');
    });
    csv.push(headerRow.join(','));

    $(table).find('tbody tr').each(function() {
        var row = [];
        var tds = $(this).find('td');
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
    csv.push('"Month","New Users","Users","Retain Users","Not Retain Users"');

    $(table).find('tbody > tr').not('.user-detail-row').each(function() {
        var row = [];
        $(this).find('td').each(function() {
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
    csv.push('"Month","#","User","Mobile","Email","Total Orders","Last Order Date"');

    $('tr[id^="retain-"]').each(function() {
        var monthTitle = $(this).find('h5').text().trim();
        var monthName = monthTitle.split(' - ')[0] || '';
        $(this).find('tbody tr').each(function() {
            var tds = $(this).find('td');
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

    downloadCSV(csv, 'All_Retain_Users_{{ $year }}.csv');
}

function exportAllNotRetainUsers() {
    var csv = [];
    csv.push('"Month","#","User","Mobile","Email","Total Orders","Last Order Date"');

    $('tr[id^="not-retain-"]').each(function() {
        var monthTitle = $(this).find('h5').text().trim();
        var monthName = monthTitle.split(' - ')[0] || '';
        $(this).find('tbody tr').each(function() {
            var tds = $(this).find('td');
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

    downloadCSV(csv, 'All_Not_Retain_Users_{{ $year }}.csv');
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
</script>

@endsection