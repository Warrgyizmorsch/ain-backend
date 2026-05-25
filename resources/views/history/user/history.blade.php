@extends('layouts.app')
@section('content')
    <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 10px !important;
            min-width: 100px;
            border: 1px solid #dee2e6 !important;
        }

        table th {
            font-weight: bold !important;
        }

        .history-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding: 0.5rem 2rem;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="history-strip">
                        <h5>Login History for <strong>{{ $user->name }}</strong></h5>
                        <a href="{{ route('admin.user.session') }}" class="">← Back</a>
                    </div>

                    <div class="card-body">
                        @if ($user->loginHistories->isEmpty())
                            <p class="text-muted">No login/logout history found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table all-package theme-table dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>IP Address</th>
                                            <th>User Agent</th>
                                            <th>Login Time</th>
                                            <th>Logout Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->loginHistories as $index => $history)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $history->ip_address ?? '-' }}</td>
                                                <td>{{ Str::limit($history->user_agent ?? '-', 60) }}</td>
                                                <td>{{ $history->created_at ? $history->created_at->format('d M Y, h:i A') : '-' }}
                                                </td>
                                                <td>
                                                    @if ($history->updated_at && $history->updated_at != $history->created_at)
                                                        {{ $history->updated_at->format('d M Y, h:i A') }}
                                                    @else
                                                        Currently Logged In
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection