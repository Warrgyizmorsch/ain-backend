@extends('layouts.app')

@section('content')

<div class="container-xxl">

    <div class="card card-xl-stretch mb-5">

        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bolder">Activity Logs</h3>
        </div>

        <div class="card-body py-3">

            <div class="table-responsive">

                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">

                    <thead>
                        <tr class="fw-bolder text-muted">
                            <th>ID</th>
                            <th>User</th>
                            <th>Module</th>
                            <th>Action (JSON)</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($logs as $log)
                        <tr>

                            <td>
                                <span class="badge badge-light-primary">{{ $log->id }}</span>
                            </td>

                            <td>
                                <div class="fw-bold text-dark">
                                    {{ $log->user_id }}
                                </div>
                            </td>

                            <td>
                                <span class="badge badge-light-info">
                                    {{ $log->module }}
                                </span>
                            </td>

                            {{-- JSON DATA --}}
                            <td style="max-width:400px;">

                                @php
                                $data = $log->action;

                                if (is_string($data)) {
                                $decoded = json_decode($data, true);
                                $data = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $data;
                                }
                                @endphp

                                @if(is_array($data))
                                <div style="font-size:12px;background:#f4f6fa;padding:10px;border-radius:6px;">

                                    @foreach($data as $key => $value)
                                    <div>
                                        <b>{{ ucfirst($key) }}:</b> {{ is_array($value) ? json_encode($value) : $value }}
                                    </div>
                                    @endforeach

                                </div>
                                @else
                                <span>{{ $data }}</span>
                                @endif

                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $log->created_at->format('d M Y h:i A') }}
                                </span>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>

                {{ $logs->links() }}

            </div>

        </div>

    </div>

</div>

@endsection