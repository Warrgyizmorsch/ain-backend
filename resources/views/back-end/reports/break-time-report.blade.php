@extends('layouts.app')

@section('content')

<div class="card card-flush mb-5">
    <div class="card-header">
        <h3 class="card-title fw-bold">Break Time Report</h3>
    </div>

    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="employee" value="{{ request('employee') }}"
                    class="form-control form-control-solid" placeholder="Search Employee">
            </div>

            <div class="col-md-3">
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="form-control form-control-solid">
            </div>

            <div class="col-md-3">
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="form-control form-control-solid">
            </div>

            <div class="col-md-3">
                <button class="btn btn-sm btn-primary">Search</button>
                <a href="{{ route('break.time.report') }}" class="btn btn-sm btn-light-danger">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-flush">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr class="fw-bold bg-light">
                        <th>#</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Break Type</th>
                        <th>Time</th>
                        <th>Break Duration</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $key => $row)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $row->employee_name ?? 'N/A' }}</td>

                            <td>
                                {{ $row->start_time ? \Carbon\Carbon::parse($row->start_time)->format('d M Y') : 'N/A' }}
                            </td>

                            <td>
                                @if($row->break_type == 'Lunch Break')
                                    <span class="badge badge-light-warning">Lunch Break</span>
                                @elseif($row->break_type == 'Tea Break')
                                    <span class="badge badge-light-info">Tea Break</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ $row->break_type ?? 'N/A' }}</span>
                                @endif
                            </td>

                            <td>
                                @if($row->start_time && $row->end_time)
                                    <span class="badge badge-light-primary">
                                        {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($row->end_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="badge badge-light-danger">Running</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $seconds = $row->total_seconds ?? 0;
                                    $hours = floor($seconds / 3600);
                                    $minutes = floor(($seconds % 3600) / 60);
                                    $secs = $seconds % 60;
                                @endphp

                                <span class="badge badge-light-success">
                                    {{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:
                                    {{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}:
                                    {{ str_pad($secs, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No break report found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection