@extends('layouts.app')

@section('content')

<div class="card card-flush mb-5">
    <div class="card-header">
        <h3 class="card-title fw-bold">My Break Time Report</h3>
    </div>

    <div class="card-body">
        <form method="GET" class="row g-3">
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
                <a href="{{ route('my.break.time.report') }}" class="btn btn-sm btn-light-danger">Reset</a>
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
                        <th>Date</th>
                        <th>Total Breaks</th>
                        <th>Total Break Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $key => $row)
                        @php
                            $seconds = $row->total_break_seconds ?? 0;
                            $hours = floor($seconds / 3600);
                            $minutes = floor(($seconds % 3600) / 60);
                            $secs = $seconds % 60;
                        @endphp

                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ \Carbon\Carbon::parse($row->break_date)->format('d M Y') }}</td>

                            <td>
                                <span class="badge badge-light-primary">
                                    {{ $row->total_breaks }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-light-success">
                                    {{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:
                                    {{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}:
                                    {{ str_pad($secs, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
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