@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <div>
            <h1 class="fs-2 fw-bolder text-dark mb-1">
                <i class="fa fa-bullhorn text-primary me-2"></i>Email Campaigns
            </h1>
            <div class="text-muted fw-bold fs-7">
                Design, schedule, and track bulk email campaigns to Leads, Customers, and Writers.
            </div>
        </div>
        <div>
            <a href="{{ route('emails.campaigns.create') }}" class="btn btn-primary fw-bold">
                <i class="fa fa-plus me-1"></i> Create Campaign
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4">
            <i class="fa fa-check-circle fs-3 text-success me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Metric Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light-primary p-3 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa fa-paper-plane fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-8 fw-bold text-uppercase">Total Campaigns</div>
                        <div class="fs-2 fw-bolder text-dark">{{ number_format($totalCampaigns) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light-info p-3 text-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa fa-envelope-open-text fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-8 fw-bold text-uppercase">Total Sent</div>
                        <div class="fs-2 fw-bolder text-dark">{{ number_format($totalSent) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light-success p-3 text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa fa-eye fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-8 fw-bold text-uppercase">Avg. Open Rate</div>
                        <div class="fs-2 fw-bolder text-success">{{ $avgOpenRate }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light-warning p-3 text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa fa-mouse-pointer fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted fs-8 fw-bold text-uppercase">Avg. Click Rate</div>
                        <div class="fs-2 fw-bolder text-warning">{{ $avgClickRate }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header border-0 pt-4 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title fw-bolder text-dark fs-5 mb-0">All Campaigns</h3>
            <form method="GET" action="{{ route('emails.campaigns.index') }}" class="d-flex align-items-center gap-2">
                <input type="text" name="search" class="form-control form-control-sm form-control-solid" placeholder="Search campaign..." value="{{ request('search') }}">
                <select name="status" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sending</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @if(request('search') || request('status'))
                    <a href="{{ route('emails.campaigns.index') }}" class="btn btn-sm btn-light" title="Reset"><i class="fa fa-sync"></i></a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-200 align-middle gs-5 gy-4 mb-0">
                    <thead class="bg-light fw-bold fs-8 text-uppercase text-gray-600">
                        <tr>
                            <th>Campaign Name</th>
                            <th>Audience</th>
                            <th>Sender Account</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Open Rate</th>
                            <th>Click Rate</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($campaigns as $camp)
                        <tr>
                            <td>
                                <a href="{{ route('emails.campaigns.show', $camp->id) }}" class="fw-bolder text-dark text-hover-primary fs-6">
                                    {{ $camp->name }}
                                </a>
                                <div class="text-muted fs-8 text-truncate" style="max-width: 250px;">{{ $camp->subject }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light-info fw-bold">{{ $camp->audience_label }}</span>
                                <div class="text-muted fs-8 mt-1">{{ number_format($camp->total_recipients) }} recipients</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark fs-8">{{ $camp->emailConfiguration?->email_address ?: 'Default SMTP' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $camp->status_badge }} fw-bold">{{ $camp->status_label }}</span>
                            </td>
                            <td style="min-width: 130px;">
                                <div class="d-flex justify-content-between fs-8 text-muted mb-1">
                                    <span>{{ $camp->sent_count }} / {{ $camp->total_recipients }}</span>
                                    <span>{{ $camp->progress_percentage }}%</span>
                                </div>
                                <div class="progress h-6px bg-light-primary">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $camp->progress_percentage }}%;"></div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bolder text-success fs-7">{{ $camp->open_rate }}%</span>
                                <div class="text-muted fs-9">{{ $camp->opened_count }} opens</div>
                            </td>
                            <td>
                                <span class="fw-bolder text-warning fs-7">{{ $camp->click_rate }}%</span>
                                <div class="text-muted fs-9">{{ $camp->clicked_count }} clicks</div>
                            </td>
                            <td>
                                <div class="text-dark fw-semibold fs-8">{{ $camp->created_at->format('d M Y') }}</div>
                                <div class="text-muted fs-9">{{ $camp->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('emails.campaigns.show', $camp->id) }}" class="btn btn-sm btn-icon btn-light-primary" title="View Report">
                                        <i class="fa fa-chart-line fs-8"></i>
                                    </a>

                                    @if($camp->status === 'draft' || $camp->status === 'scheduled')
                                        <form method="POST" action="{{ route('emails.campaigns.send-now', $camp->id) }}" class="d-inline" onsubmit="return confirm('Launch this campaign now?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="Send Now">
                                                <i class="fa fa-paper-plane fs-8"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($camp->status === 'processing')
                                        <form method="POST" action="{{ route('emails.campaigns.pause', $camp->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-warning" title="Pause">
                                                <i class="fa fa-pause fs-8"></i>
                                            </button>
                                        </form>
                                    @elseif($camp->status === 'paused')
                                        <form method="POST" action="{{ route('emails.campaigns.resume', $camp->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="Resume">
                                                <i class="fa fa-play fs-8"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('emails.campaigns.destroy', $camp->id) }}" class="d-inline" onsubmit="return confirm('Delete this campaign?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Delete">
                                            <i class="fa fa-trash fs-8"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa fa-bullhorn fs-1 text-primary mb-2 d-block"></i>
                                <h6 class="fw-bold text-dark">No email campaigns created yet</h6>
                                <p class="text-muted fs-8 mb-3">Create your first marketing campaign to engage with leads and customers.</p>
                                <a href="{{ route('emails.campaigns.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus me-1"></i> Create Campaign
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($campaigns->hasPages())
                <div class="card-footer py-3 d-flex justify-content-end">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
