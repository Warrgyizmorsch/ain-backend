@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-5 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('emails.campaigns.index') }}" class="text-muted text-hover-primary fs-7">
                    <i class="fa fa-arrow-left me-1"></i> Campaigns
                </a>
                <span class="text-muted">/</span>
                <span class="badge {{ $campaign->status_badge }} fw-bold">{{ $campaign->status_label }}</span>
            </div>
            <h1 class="fs-2 fw-bolder text-dark mb-0">{{ $campaign->name }}</h1>
            <div class="text-muted fs-8 mt-1">
                Subject: <strong class="text-gray-800">{{ $campaign->subject }}</strong> &bull;
                Sender: <strong class="text-gray-800">{{ $campaign->emailConfiguration?->email_address ?: 'Default SMTP' }}</strong>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
                <form method="POST" action="{{ route('emails.campaigns.send-now', $campaign->id) }}" onsubmit="return confirm('Launch this campaign now?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success fw-bold">
                        <i class="fa fa-paper-plane me-1"></i> Send Now
                    </button>
                </form>
            @endif

            @if($campaign->status === 'processing')
                <form method="POST" action="{{ route('emails.campaigns.pause', $campaign->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning fw-bold">
                        <i class="fa fa-pause me-1"></i> Pause Campaign
                    </button>
                </form>
            @elseif($campaign->status === 'paused')
                <form method="POST" action="{{ route('emails.campaigns.resume', $campaign->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success fw-bold">
                        <i class="fa fa-play me-1"></i> Resume Sending
                    </button>
                </form>
            @endif

            <a href="{{ route('emails.campaigns.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>

    <!-- Progress Bar & Status Alert -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center fs-7 fw-bold mb-2">
                <span>Sending Progress: {{ $campaign->sent_count }} of {{ $campaign->total_recipients }} emails delivered</span>
                <span class="text-primary">{{ $campaign->progress_percentage }}%</span>
            </div>
            <div class="progress h-10px bg-light-primary rounded">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $campaign->progress_percentage }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Analytics Counters -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Target Total</div>
                    <div class="fs-2 fw-bolder text-dark">{{ number_format($campaign->total_recipients) }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Delivered</div>
                    <div class="fs-2 fw-bolder text-primary">{{ number_format($campaign->delivered_count) }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Opened</div>
                    <div class="fs-2 fw-bolder text-success">{{ number_format($campaign->opened_count) }}</div>
                    <div class="text-success fs-8 fw-bold">{{ $campaign->open_rate }}% rate</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Clicked</div>
                    <div class="fs-2 fw-bolder text-warning">{{ number_format($campaign->clicked_count) }}</div>
                    <div class="text-warning fs-8 fw-bold">{{ $campaign->click_rate }}% rate</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Failed / Errors</div>
                    <div class="fs-2 fw-bolder text-danger">{{ number_format($campaign->failed_count) }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 text-center">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Send Speed</div>
                    <div class="fs-2 fw-bolder text-info">{{ $campaign->throttle_rate }}</div>
                    <div class="text-muted fs-8">per minute</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipient Logs Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header border-0 pt-4 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title fw-bolder text-dark fs-5 mb-0">Recipient Delivery Logs</h3>
            <form method="GET" action="{{ route('emails.campaigns.show', $campaign->id) }}" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control form-control-sm form-control-solid" placeholder="Search recipient..." value="{{ request('search') }}">
                <select name="status" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @if(request('search') || request('status'))
                    <a href="{{ route('emails.campaigns.show', $campaign->id) }}" class="btn btn-sm btn-light"><i class="fa fa-sync"></i></a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-200 align-middle gs-4 gy-3 mb-0">
                    <thead class="bg-light fw-bold fs-8 text-uppercase text-gray-600">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Recipient Name</th>
                            <th>Email Address</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Opened At</th>
                            <th>Clicked At</th>
                            <th>Details / Error</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($recipients as $index => $rec)
                        <tr>
                            <td>{{ $recipients->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $rec->recipient_name ?: '—' }}</span>
                            </td>
                            <td>
                                <span class="font-monospace text-gray-800">{{ $rec->recipient_email }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $rec->status_badge }} fw-bold text-uppercase fs-9">{{ $rec->status }}</span>
                            </td>
                            <td>
                                @if($rec->sent_at)
                                    <span class="text-dark fs-8">{{ $rec->sent_at->format('d M, h:i:s A') }}</span>
                                @else
                                    <span class="text-muted fs-8">—</span>
                                @endif
                            </td>
                            <td>
                                @if($rec->opened_at)
                                    <span class="badge badge-light-success text-success fs-9 fw-bold">
                                        <i class="fa fa-check me-1 fs-9"></i>{{ $rec->opened_at->format('d M, h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted fs-8">—</span>
                                @endif
                            </td>
                            <td>
                                @if($rec->clicked_at)
                                    <span class="badge badge-light-warning text-warning fs-9 fw-bold">
                                        <i class="fa fa-mouse-pointer me-1 fs-9"></i>{{ $rec->clicked_at->format('d M, h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted fs-8">—</span>
                                @endif
                            </td>
                            <td>
                                @if($rec->error_message)
                                    <span class="text-danger fs-8" title="{{ $rec->error_message }}">
                                        {{ Str::limit($rec->error_message, 40) }}
                                    </span>
                                @else
                                    <span class="text-muted fs-8">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No recipients found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recipients->hasPages())
                <div class="card-footer py-3 d-flex justify-content-end">
                    {{ $recipients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
