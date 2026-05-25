{{-- @extends('layouts.app')

@section('content')
<!-- overflow-hidden main page scroller ko rokega -->
<div class="d-flex flex-column flex-lg-row px-4" style="margin-top: -45px; height: calc(100vh - 120px); overflow: hidden;">
    
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
        <div class="card card-flush h-100">
            <div class="card-header pt-7" id="kt_chat_contacts_header">
                <form class="w-100 position-relative" autocomplete="off">
                    <span class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-5 translate-middle-y">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-solid px-15" name="search" placeholder="Search user..." />
                </form>
            </div>
            <div class="card-body pt-5" id="kt_chat_contacts_body" style="height: calc(100% - 100px);">
                <!-- 100% height and auto scroll fixed internal height -->
                <div class="scroll-y me-n5 pe-5 h-100" style="overflow-y: auto;">
                    @foreach($users as $user)
                    <div class="d-flex flex-stack py-4 {{ $selectedUser && $selectedUser->id == $user->id ? 'bg-light px-3 rounded' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-45px symbol-circle">
                                <span class="symbol-label bg-light-primary text-primary fs-6 fw-bolder">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="ms-5">
                                <a href="{{ route('user.history', $user->id) }}" class="fs-5 fw-bolder text-gray-900 text-hover-primary mb-2">{{ $user->name }}</a>
                                <div class="fw-bold text-muted">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end ms-2">
                            <span class="badge badge-sm badge-circle badge-light-success" title="Total Follow-ups">{{ $user->total_followups }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed d-none"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--end::Sidebar-->

    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10 h-100">
        <div class="card h-100" id="kt_chat_messenger">
            @if($selectedUser)
            <div class="card-header" id="kt_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ $selectedUser->name }}'s History</a>
                        <div class="mb-0 lh-1">
                            <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                            <span class="fs-7 fw-bold text-muted">Showing all merged records</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body" id="kt_chat_messenger_body" style="height: calc(100% - 80px);">
                <!-- Fixed height and auto scroll -->
                <div class="scroll-y me-n5 pe-5 h-100" style="overflow-y: auto;">
                    @forelse($historyData as $history)
                    <div class="d-flex justify-content-start mb-10">
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center mb-2">
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-info text-info fw-bolder">
                                        <i class="{{ $history->type == 'Lead' ? 'bi bi-telephone text-info' : 'bi bi-cart text-info' }}"></i>
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <span class="fs-5 fw-bolder text-gray-900 me-1">{{ $history->type }}</span>
                                    <span class="text-muted fs-7 mb-1">
                                        @if($history->action_date)
                                            {{ \Carbon\Carbon::parse($history->action_date)->format('d M Y, h:i A') }}
                                        @else
                                            No Date
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start">
                                @if($history->reference_id)
                                    <div class="mb-2 text-primary">ID: #{{ $history->reference_id }}</div>
                                @endif
                                <div class="mb-2"><strong>Status:</strong> <span class="badge badge-light-dark">{{ !empty($history->status) ? $history->status : 'Pending' }}</span></div>
                                <div>{{ $history->note ?? 'No description/comment provided.' }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-10">No follow-up history found for this user.</div>
                    @endforelse
                </div>
            </div>
            @else
            <div class="card-body d-flex align-items-center justify-content-center h-100">
                <h4 class="text-muted">Please select a user from the left panel to view their history.</h4>
            </div>
            @endif
        </div>
    </div>
    <!--end::Content-->
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="px-4" style="margin-top: -45px;">
    
    <!--begin::Card-->
    <div class="card card-flush">
        <div class="card-header pt-7">
            <div class="card-title">
                <h2>User Follow-up Overview</h2>
            </div>
            <div class="card-toolbar">
                <form class="w-100 position-relative" autocomplete="off">
                    <span class="svg-icon svg-icon-2 svg-icon-gray-500 position-absolute top-50 ms-5 translate-middle-y">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control form-control-solid px-15 w-250px" name="search" placeholder="Search user..." />
                </form>
            </div>
        </div>

        <div class="card-body pt-5">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Emp Name</th>
                            <th class="min-w-200px">Email</th>
                            <!-- Backend se individually alag alag count nahi aa rahe hain, isliye total show kiya h -->
                            <th class="min-w-100px text-center">Total Follow-ups</th>
                            <th class="text-end min-w-70px">Details</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary text-primary fs-6 fw-bolder">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="#" class="text-dark fw-bolder text-hover-primary fs-6">{{ $user->name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="badge badge-light-success fs-7">{{ $user->total_followups }}</span>
                            </td>
                            <td class="text-end">
                                <!-- Arrow Button -->
                                <a href="{{ route('user.feedbackhistory', $user->id) }}" class="btn btn-sm btn-icon btn-light btn-active-light-primary">
                                    <i class="bi bi-chevron-{{ $selectedUser && $selectedUser->id == $user->id ? 'up' : 'down' }} fs-2"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Expanded Dropdown Area for Selected User -->
                        @if($selectedUser && $selectedUser->id == $user->id)
                        <tr>
                            <td colspan="4" class="p-0">
                                <div class="bg-light p-5 rounded m-3 border border-primary border-dashed">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h4 class="text-dark fw-bolder m-0">Detailed Follow-up History</h4>
                                        <div class="d-flex align-items-center">
                                            <span class="fs-7 text-muted me-2 fw-bold">Filter History:</span>
                                            <!-- Filter Dropdown -->
                                            <select class="form-select form-select-sm form-select-solid w-150px" onchange="filterHistory(this.value)">
                                                <option value="2">Last 2 Days</option>
                                                <option value="all">All Time</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle table-row-bordered fs-7">
                                            <thead class="bg-white">
                                                <tr class="fw-bolder text-muted">
                                                    <th class="ps-3 rounded-start">Type</th>
                                                    <th>ID</th>
                                                    <th>Status</th>
                                                    <th>Comment / Note</th>
                                                    <th class="rounded-end">Action Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($historyData as $history)
                                                    @php
                                                        // Check if data is within last 2 days
                                                        $daysDifference = \Carbon\Carbon::parse($history->action_date)->diffInDays(\Carbon\Carbon::now());
                                                        $isRecent = $daysDifference <= 2;
                                                    @endphp
                                                    
                                                    <!-- Row with dynamic classes for filtering -->
                                                    <tr class="history-row {{ $isRecent ? 'recent-data' : 'old-data d-none' }}" data-days="{{ $daysDifference }}">
                                                        <td class="ps-3">
                                                            @if($history->type == 'Lead')
                                                                <span class="badge badge-light-primary">Lead</span>
                                                            @elseif($history->type == 'Order')
                                                                <span class="badge badge-light-warning">Order</span>
                                                            @else
                                                                <span class="badge badge-light-danger">Ticket</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-primary fw-bolder">#{{ $history->reference_id ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-light-dark">{{ !empty($history->status) ? $history->status : 'Pending' }}</span>
                                                        </td>
                                                        <td style="max-width: 300px;" class="text-truncate" title="{{ $history->note }}">
                                                            {{ $history->note ?? '-' }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($history->action_date)->format('d M Y, h:i A') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-5">No follow-up history found for this user.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Card-->

</div>

<!-- JavaScript for Filter Logic -->
<script>
    function filterHistory(filterValue) {
        // Sabhi history rows ko pakdo
        let rows = document.querySelectorAll('.history-row');
        
        rows.forEach(function(row) {
            let days = parseInt(row.getAttribute('data-days'));
            
            if (filterValue === '2') {
                // Agar Last 2 Days select kiya h, to sirf < 2 wale show kro
                if (days <= 2) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            } else {
                // All Time select kiya h to sab dikha do
                row.classList.remove('d-none');
            }
        });
    }
</script>
@endsection