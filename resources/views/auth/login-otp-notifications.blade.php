@extends('layouts.app')

@section('content')
<div class="container-fluid py-6">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
        <div>
            <h1 class="fs-2 fw-bolder mb-1">Login OTP Notifications</h1>
            <div class="text-muted fw-semibold">User-wise OTP login request history.</div>
        </div>
        <form method="GET" action="{{ route('admin.login-otp-notifications') }}" class="d-flex align-items-center gap-2">
            <select name="user_id" class="form-select form-select-sm w-250px">
                <option value="">All users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            @if(request('user_id'))
                <a href="{{ route('admin.login-otp-notifications') }}" class="btn btn-sm btn-light">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <span class="fw-bolder">Login OTP History</span>
            </div>
            <div class="card-toolbar">
                <span class="badge badge-light-primary">{{ $notifications->total() }} records</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle mb-0">
                    <thead>
                        <tr class="text-gray-500 fw-bold fs-7 text-uppercase">
                            <th class="ps-6 py-4">User</th>
                            <th>IP</th>
                            <th>OTP</th>
                            <th>Type</th>
                            <th>Attempts</th>
                            <th>System</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th class="pe-6">Expires</th>
                            <th class="pe-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notification)
                            @php
                                $systemBan = $bans[$notification->user_id] ?? null;
                                $isSystemBanned = $systemBan && $systemBan->isActive();
                            @endphp
                            <tr>
                                <td class="ps-6">
                                    <div class="fw-bolder text-gray-900">{{ $notification->user->name ?? 'Deleted user' }}</div>
                                    <div class="text-muted fs-7">{{ $notification->user->email ?? '' }}</div>
                                </td>
                                <td class="fw-semibold">{{ $notification->ip_address ?? 'N/A' }}</td>
                                <td>
                                    @if(($notification->purpose ?? 'user_admin_approval') === 'admin_email_login')
                                        <span class="badge badge-light-info">Email sent</span>
                                        <div class="text-muted fs-8">{{ $notification->email_to }}</div>
                                    @elseif($notification->status === 'pending' && (!$notification->expires_at || $notification->expires_at->isFuture()))
                                        <span class="badge badge-light-success fs-5 fw-bolder letter-spacing-normal">{{ $notification->otp_code }}</span>
                                    @else
                                        <span class="text-muted">Expired/Used</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($notification->purpose ?? 'user_admin_approval') === 'admin_email_login')
                                        <span class="badge badge-light-primary">Admin Email</span>
                                    @else
                                        <span class="badge badge-light-warning">User Approval</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($notification->failed_attempts ?? 0) > 0)
                                        <span class="badge badge-light-danger">{{ $notification->failed_attempts }} wrong</span>
                                        <div class="text-muted fs-8">
                                            {{ optional($notification->last_failed_at)->format('d M Y h:i A') }}
                                        </div>
                                    @else
                                        <span class="badge badge-light-success">0 wrong</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isSystemBanned)
                                        <span class="badge badge-light-danger">Banned</span>
                                        <div class="text-muted fs-8">
                                            Until {{ optional($systemBan->banned_until)->format('d M Y h:i A') }}
                                        </div>
                                    @else
                                        <span class="badge badge-light-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badge = $notification->status === 'pending' ? 'badge-light-warning' : 'badge-light-success';
                                        if ($notification->expires_at && $notification->expires_at->isPast() && $notification->status === 'pending') {
                                            $badge = 'badge-light-danger';
                                        } elseif ($notification->status === 'blocked') {
                                            $badge = 'badge-light-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($notification->status) }}</span>
                                </td>
                                <td>{{ optional($notification->created_at)->format('d M Y h:i A') }}</td>
                                <td class="pe-6">{{ optional($notification->expires_at)->format('d M Y h:i A') }}</td>
                                <td class="pe-6">
                                    @if($notification->user_id)
                                        @if($isSystemBanned)
                                            <form method="POST" action="{{ route('admin.login-otp-system-unban') }}">
                                                @csrf
                                                <input type="hidden" name="ip_address" value="{{ $notification->ip_address }}">
                                                <input type="hidden" name="user_id" value="{{ $notification->user_id }}">
                                                <button type="submit" class="btn btn-sm btn-light-success">Unban</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.login-otp-system-ban') }}">
                                                @csrf
                                                <input type="hidden" name="ip_address" value="{{ $notification->ip_address }}">
                                                <input type="hidden" name="user_id" value="{{ $notification->user_id }}">
                                                <button type="submit" class="btn btn-sm btn-light-danger">Ban 24h</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-10">
                                    No login OTP notifications yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
