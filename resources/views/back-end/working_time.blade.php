@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.working-report') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search Employee</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-solid" placeholder="Name or Email...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Select Date</label>
                    <input type="date" name="date" value="{{ $today }}" class="form-control form-control-solid">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary px-6">Filter Report</button>
                    <a href="{{ route('admin.working-report') }}" class="btn btn-light-danger px-6">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Employee Daily Activity</span>
                <span class="text-muted mt-1 fw-bold fs-7">Tracking for: {{ date('d M, Y', strtotime($today)) }}</span>
            </h3>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Employee</th>
                            <th>Status</th>
                            <th>First Login At</th>
                            <th class="min-w-200px">Working Productivity</th>
                            <th>Active Hours</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold">
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-4">
                                        <img src="{{ asset($user->photo ?? 'assets/media/avatars/blank.png') }}" class="rounded-circle" alt="">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                                        <span class="text-muted fs-7">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->is_online)
                                    <div class="badge badge-light-success d-inline-flex align-items-center">
                                        <span class="bullet bullet-dot bg-success h-6px w-6px me-2"></span>Online
                                    </div>
                                @else
                                    <div class="badge badge-light-secondary d-inline-flex align-items-center">
                                        <span class="bullet bullet-dot bg-gray-400 h-6px w-6px me-2"></span>Offline
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark fs-7">
                                    {{ $user->first_login_at ? date('h:i A', strtotime($user->first_login_at)) : 'No Login Found' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    // Assuming 8 hours (28800 sec) is standard workday
                                    $percentage = min(($user->today_active_seconds / 28800) * 100, 100);
                                    $progressColor = $percentage > 70 ? 'bg-success' : ($percentage > 30 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="d-flex flex-column w-100 me-2">
                                    <div class="d-flex flex-stack mb-2">
                                        <span class="text-muted me-2 fs-7 fw-bold">{{ round($percentage) }}%</span>
                                    </div>
                                    <div class="progress h-6px w-100 bg-light-primary">
                                        <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light-primary py-2 px-3 fw-bolder fs-7">
                                    {{ gmdate("H:i:s", $user->today_active_seconds) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('user.history', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View Detailed Logs">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($user->is_online)
                                <form action="{{ route('admin.force-logout', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Force Logout" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted fs-7">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</div>
                <div>{{ $users->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection