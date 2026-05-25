@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.ticket-report') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Select Month</label>
                    <input type="month" name="month" value="{{ $month }}" class="form-control form-control-solid">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Team Performance (Tickets & Failure)</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Report for {{ date('F Y', strtotime($month)) }}</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-100px rounded-start">Team Name</th>
                                    <th class="min-w-100px">Tickets (%)</th>
                                    <th class="min-w-100px text-center">Failed Jobs</th>
                                    <th class="min-w-100px text-end pe-4">Total Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teamReport as $team)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $team->team }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column w-100 me-2">
                                            <div class="d-flex flex-stack mb-2">
                                                <span class="text-muted me-2 fs-7 fw-bold">
                                                    {{ $team->ticket_count }} ({{ $team->ticket_percentage }}%)
                                                </span>
                                            </div>

                                            <div class="progress h-6px w-100">
                                                <div class="progress-bar bg-danger"
                                                    role="progressbar"
                                                    style="width: {{ $team->ticket_percentage }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                   <td class="text-center">
                                        <span class="badge badge-light-danger fw-bolder px-4 py-3">
                                            {{ $team->failed_jobs }}
                                            ({{ $team->failed_percentage }}%)
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="text-muted fw-bold">{{ $team->total_orders }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Top Clients with Complaints</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Client-wise feedback analysis</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="ps-4 min-w-150px">Client</th>
                                    <th class="min-w-100px">Tickets</th>
                                    <th class="min-w-100px">Failed</th>
                                    <th class="min-w-100px text-end">Total Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientReport as $client)
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-3">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold">{{ substr($client->client_name, 0, 1) }}</span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-bolder fs-6">{{ $client->client_name }}</span>
                                                <span class="text-muted fw-bold fs-7">{{ $client->client_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light-warning fw-bold">{{ $client->ticket_count }}</span></td>
                                    <td><span class="badge badge-light-danger fw-bold">{{ $client->failed_jobs }}</span></td>
                                    <td class="text-end pe-4"><span class="text-dark fw-bolder">{{ $client->total_orders }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection