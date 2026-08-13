@php
    $orderIdStyle  = "";
    $firstFailedOrderAt = $lead->first_failed_order_at ?? null;
    $leadCreatedAt = $lead->create_at ?? $lead->created_at ?? null;

    if (!empty($lead->user) && !empty($firstFailedOrderAt) && !empty($leadCreatedAt)) {
        $failedAt = \Carbon\Carbon::parse($firstFailedOrderAt);
        $createdAt = \Carbon\Carbon::parse($leadCreatedAt);

        if ($createdAt->gt($failedAt)) {
            $orderIdStyle  = "background-color:#ffeaea !important; color:#b50000 !important; border:2px solid #ff0000 !important;";
        }
    }
@endphp
<tr id="lead-{{ $lead->id }}">
    <td class="text-center" style="padding-right: 0px;">{{ $index + 1 }}</td>

    <td class="text-center align-middle" style="min-width: 165px; padding: 6px;">
        <div class="d-flex flex-column align-items-center justify-content-center gap-1">
            <!-- 4x2 Grid for Buttons & Switches -->
            <div style="display: grid; grid-template-columns: repeat(4, 32px); gap: 6px; align-items: center; justify-items: center;">
                
                <!-- Row 1, Col 1: Flag Checkbox -->
                <div class="form-check form-check-sm form-check-custom form-check-solid m-0 p-0 d-flex align-items-center justify-content-center">
                    <input onchange="checkedLead(this, {{ $lead->id }})" class="form-check-input widget-13-check m-0 action-checkbox" type="checkbox"
                        {{ $lead->flag == '1' ? 'checked' : '' }} value="1" title="Lead Flag">
                </div>

                <!-- Row 1, Col 2: Group Master Button -->
                @if($lead->user)
                    <button type="button" class="btn btn-sm btn-icon btn-light-success fw-bold p-0 d-inline-flex align-items-center justify-content-center shadow-xs" 
                        style="width: 28px; height: 28px; border: 1px solid #b5e5c4;" title="Manage User Groups" 
                        data-user-group-button="{{ $lead->user->id }}" 
                        data-groups='@json($lead->user->groups->pluck("id"))' 
                        onclick="openUserGroupModal({{ $lead->user->id }}, @js($lead->user->name), JSON.parse(this.dataset.groups))">G</button>
                @else
                    <div></div>
                @endif

                <!-- Row 1, Col 3: Lead Active Switch -->
                <div class="form-check form-switch m-0 p-0 d-flex align-items-center justify-content-center" title="Lead Status Switch">
                    <input class="form-check-input m-0" type="checkbox" id="{{ $lead->id }}" role="switch" checked
                        onchange="handleChange(this, {{ $lead->id }})">
                </div>

                <!-- Row 1, Col 4: Edit Button -->
                <a href="{{ url('/lead/edit/' . $lead->id) }}" target="_blank" 
                    class="btn btn-sm btn-icon p-0 d-inline-flex align-items-center justify-content-center shadow-xs"
                    style="background-color: #1e1e2d; width: 28px; height: 28px; border-radius: 6px;" title="Edit Lead">
                    <i style="color: white;" class="fa fa-edit"></i>
                </a>

                <!-- Row 2, Col 1: Convert / Sync Button -->
                <button type="button" class="btn btn-sm btn-primary btn-icon p-0 d-inline-flex align-items-center justify-content-center shadow-xs" 
                    style="width: 28px; height: 28px; border-radius: 6px;" onclick="convert(this, {{ $lead->id }})"
                    id="convert-btn-{{ $lead->id }}" title="Convert Lead">
                    <i class="fa fa-sync fs-8"></i>
                </button>

                <!-- Row 2, Col 2: Phone / Chat Button -->
                <button type="button" id="loadChat{{ $lead->id }}" class="btn btn-sm btn-warning btn-icon p-0 d-inline-flex align-items-center justify-content-center shadow-xs"
                    style="width: 28px; height: 28px; border-radius: 6px;" onclick="loadchat({{ $lead->id }})" title="Lead Chat & Calls">
                    <i class="fa fa-phone fs-8 text-white"></i>
                </button>

                <!-- Row 2, Col 3: Assign Type Switch -->
                <div class="form-check form-switch m-0 p-0 d-flex align-items-center justify-content-center" title="Assign Type (AIN / Let's Learn)">
                    <input
                        class="form-check-input assign-toggle m-0"
                        type="checkbox"
                        id="type{{ $lead->id }}"
                        {{ ($lead->assign_type ?? 0) == 1 ? 'checked' : '' }}
                        onchange="handleTypeToggle(this, {{ $lead->id }})">
                </div>

                <!-- Row 2, Col 4: Duplicate Lead Button -->
                <button type="button" class="btn btn-sm btn-danger btn-icon fw-bold p-0 d-inline-flex align-items-center justify-content-center shadow-xs" 
                    style="width: 28px; height: 28px; border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#hideLeadModal" 
                    onclick="openDuplicateLeadModal({{ $lead->id }})" title="Mark Duplicate Lead">
                    D
                </button>
            </div>

            <!-- Row 3: Select Lead Reason Dropdown -->
            <div class="w-100 d-flex justify-content-center mt-1.5">
                <select
                    id="leadReason{{ $lead->id }}"
                    name="lead_reason[{{ $lead->id }}]"
                    class="form-select form-select-sm text-center py-1 px-2 fs-8 fw-bold action-reason-dropdown"
                    style="width: 100%; max-width: 148px; height: 30px;"
                    onchange="handleLeadReason({{ $lead->id }})">
                    <option value="">Select Reason</option>
                    <option value="Price" {{ ($lead->l_status ?? '') == 'Price' ? 'selected' : '' }}>Price</option>
                    <option value="Deadline" {{ ($lead->l_status ?? '') == 'Deadline' ? 'selected' : '' }}>Deadline</option>
                    <option value="Serious Concern" {{ ($lead->l_status ?? '') == 'Serious Concern' ? 'selected' : '' }}>Serious Concern</option>
                    <option value="Marks" {{ ($lead->l_status ?? '') == 'Marks' ? 'selected' : '' }}>Marks</option>
                    <option value="Unknown" {{ ($lead->l_status ?? '') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                    <option value="Quality" {{ ($lead->l_status ?? '') == 'Quality' ? 'selected' : '' }}>Quality</option>
                    <option value="Customer Service" {{ ($lead->l_status ?? '') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                </select>
            </div>
        </div>

        <style>
            .action-checkbox {
                width: 18px !important;
                height: 18px !important;
                border: 2px solid #64748b !important;
                border-radius: 4px !important;
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }
            .action-checkbox:checked {
                background-color: #009ef7 !important;
                border-color: #009ef7 !important;
            }
            .action-reason-dropdown {
                border: 1.5px solid #cbd5e1 !important;
                border-radius: 6px !important;
                background-color: #f8fafc !important;
                color: #1e293b !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }
            .action-reason-dropdown:hover, .action-reason-dropdown:focus {
                border-color: #009ef7 !important;
                background-color: #ffffff !important;
                box-shadow: 0 0 0 3px rgba(0, 158, 247, 0.15) !important;
            }
            .assign-toggle {
                transform: scale(1.05);
                cursor: pointer;
            }
            .assign-toggle:not(:checked) {
                background-color: #FFC107 !important;
                border-color: #e0a800 !important;
            }
            .assign-toggle:checked {
                background-color: #28a745 !important;
                border-color: #1e7e34 !important;
            }
        </style>
        <script>
            if (typeof handleTypeToggle === 'undefined') {
                window.handleTypeToggle = function(el, leadId) {
                    let assign_type = el.checked ? 1 : 0;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to change assign type?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ url('/lead/assign-type') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    lead_id: leadId,
                                    assign_type: assign_type
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (!data.status) {
                                    alert("Failed to update");
                                }
                            })
                            .catch(() => alert("Server error"));
                        } else {
                            el.checked = !el.checked;
                        }
                    });
                };
            }

            if (typeof handleLeadReason === 'undefined') {
                window.handleLeadReason = function(leadId) {
                    let value = document.getElementById('leadReason' + leadId).value;
                    fetch("{{ url('/lead-reason-update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            lead_id: leadId,
                            l_status: value 
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.status) {
                            alert("Update failed");
                        }
                    })
                    .catch(() => alert("Server error"));
                };
            }
        </script>
    </td>
    <td class="text-center" id="lead-recent-chat-{{ $lead->id }}">
        @php
            $latestCall = $lead->latest_customer_call ?? $lead->latestCall ?? null;
            $commentUser = $latestCall?->user;
            $isFromOtherLead = $latestCall && ($latestCall->lead_id != $lead->id);
            $callOrderCode = !empty($latestCall?->lead?->order_id) ? $latestCall->lead->order_id : ('#' . $latestCall?->lead_id);
        @endphp

        @if($latestCall)
            <div class="lead-comment-box lead-recent-chat-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-gray-800">
                        {{ $commentUser->name ?? 'User' }}
                        @if($isFromOtherLead)
                            <span class="badge badge-light-warning text-dark fs-9 ms-1 fw-bold" title="Taken on another order of this customer">Order {{ $callOrderCode }}</span>
                        @else
                            <span class="badge badge-light-primary fs-9 ms-1 fw-bold">Order {{ $callOrderCode }}</span>
                        @endif
                    </span>

                    <span class="text-muted fs-8">
                        {{ \Carbon\Carbon::parse($latestCall->created_at)->diffForHumans() }}
                    </span>
                </div>

                <div class="text-gray-800 mb-2">
                    {{ $latestCall->description ?? 'No comment' }}
                </div>

                <div class="text-primary fw-bold fs-7">
                    <i class="fa fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::parse($latestCall->created_at)->format('d M Y, h:i A') }}
                </div>
            </div>
        @else
            <span class="badge badge-light-secondary text-muted fs-8">No Recent Chat</span>
        @endif
    </td>
    <td class="text-center" style="{{ $orderIdStyle }}">
        @if ($lead['frontendorder'] == '1')
        <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
        @else
        {{ $lead->order_id }}
        @endif
        <br>
        @php
            $creatorUser = $lead->creator ?? (is_numeric($lead->created_by) ? \App\Models\User::find($lead->created_by) : null);
            $creatorDisplay = $creatorUser 
                ? $creatorUser->name . ' (ID: ' . $creatorUser->id . ')'
                : ($lead->created_by ?: (auth()->user()?->name ? auth()->user()->name . ' (ID: ' . auth()->user()->id . ')' : 'Admin User'));
        @endphp
        <span class="badge badge-light-info fs-8 mt-1 fw-semibold" title="Lead Creator">
            Created By: {{ $creatorDisplay }}
        </span>
        <br>
        @if ($lead['resit'] == 'on')
        <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
        @endif
        @if ($lead['service_type'] == 'First Class Work')
        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
        @endif

        @php
            $filesList = \App\Models\Files::where(function($q) use ($lead) {
                if (!empty($lead->order_id)) {
                    $q->where('order_id', (string)$lead->order_id);
                }
                $q->orWhere('order_id', (string)$lead->id);
                if (\Illuminate\Support\Facades\Schema::hasColumn('files', 'lead_id')) {
                    $q->orWhere('lead_id', (string)$lead->id);
                }
            })->get();
        @endphp

        @if($filesList->count() > 0)
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-light-primary fw-bold px-2 py-1 fs-8 d-inline-flex align-items-center gap-1 shadow-sm border border-primary border-opacity-25"
                    data-bs-toggle="modal" data-bs-target="#leadFilesModal-{{ $lead->id }}"
                    title="View & Download Attachments">
                    <i class="fa fa-paperclip text-primary fs-7"></i>
                    <span>Files</span>
                    <span class="badge bg-primary text-white rounded-pill ms-1" style="font-size: 10px; padding: 2px 6px;">{{ $filesList->count() }}</span>
                </button>
            </div>
        @endif
    </td>

    <td class="text-center">
        {{-- {{ $lead->user->name ?? 'No Name' }}<br> --}}
        @php
            $userLeadCount = !empty($lead->user)
                ? ($lead->user->active_leads_count ?? 0)
                : 0;
        @endphp

        {{ $lead->user->name ?? 'No Name' }}<br>
        @if($lead->user)<span data-user-group-badges="{{ $lead->user->id }}">@foreach($lead->user->groups as $group)<span class="badge badge-light-primary fs-8 me-1">{{ $group->name }}</span>@endforeach</span><br>@endif

        <span class="badge badge-light-primary fs-8 fw-bold ms-1">
            Leads: {{ $userLeadCount }}
        </span>
        <br>

        @if(!empty($lead->user))

        @php
            $count = $lead->user->orders_count ?? 0;

            if($count > 10) { 
                $class = "badge-light-success"; 
                $label = "Loyal Customer"; 
            } elseif($count >= 4) { 
                $class = "badge-light-warning"; 
                $label = "Repeated"; 
            } else { 
                $class = "badge-light-info"; 
                $label = "Beginner"; 
            }
        @endphp

        <span class="badge {{ $class }} fw-bold fs-8 mb-1">
            {{ $label }}
        </span><br>

    @endif
        <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead->user->mobile_no ?? '' }}</span></br>
        @if(!empty($lead->user))
        <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
            <button type="button" class="btn btn-icon btn-sm btn-light-info"
                onclick="openReviewModal({{ $lead->user->id}}, '{{ addslashes($lead->user->client_review ?? '') }}')">
                <span class="fw-bold fs-6">B</span>
            </button>

            <div class="star-rating" data-id="{{ $lead->id }}" data-current="{{ e($lead->lead_status) }}">
                <i class="fa fa-star star" data-value="1"></i>
                <i class="fa fa-star star" data-value="2"></i>
                <i class="fa fa-star star" data-value="3"></i>
            </div>
        </div>
        @endif

    </td>
    <!-- <td class="text-center">{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</br>
    @if($lead->lead_source && !empty($lead->source))
        <strong>Source:</strong>
        <span>
            @if($lead->source && $lead->source->source_icon)
                <img src="{{ asset($lead->source->source_icon) }}"
                style="height:16px;width:16px;object-fit:cover;vertical-align:middle;border-radius:2px;margin-right:3px;"
                title="{{ $lead->source->source_name }}">
            @endif
            {{ $lead->source->source_name }}
        </span>
    @endif
    </td> -->
    <td class="text-center">
        <div class="fw-bolder text-gray-800 fs-6">
            {{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}
        </div>
        @if($lead->source)
            <div class="d-flex justify-content-center align-items-center mt-1">
                <span class="badge badge-light-info d-flex align-items-center gap-1 px-2 py-1" style="border: 1px solid rgba(0, 158, 247, 0.15); border-radius: 4px;">
                    @if(!empty($lead->source->source_icon))
                        <img src="{{ asset($lead->source->source_icon) }}"
                            style="height:14px; width:14px; object-fit:cover; border-radius:3px;"
                            title="{{ $lead->source->source_name }}"
                            onerror="this.style.display='none'">
                    @endif
                    <span class="fw-bold fs-8" style="color: #009ef7;">
                        {{ $lead->source->source_name }}
                    </span>
                </span>
            </div>
        @endif
    </td>
    <td class="text-center">
        {!! $lead->project_title
        ? e($lead->project_title)
        : '<span class="badge badge-light-danger fs-7 fw-bold">No Title</span>' !!}
        @if ($lead->semester)
        <br><span class="badge badge-light-success fs-7">Semester: {{ $lead->semester }}</span>
        @endif
        @if ($lead->tech === 'on')
        <br><span class="badge badge-light-success fs-7">Technical Work</span>
        @endif
        @if ($lead->module_code)
        <br><span class="badge badge-light-danger fs-7">{{ $lead->module_code }}</span>
        @endif
    </td>
    <td class="text-center">
        {!! $lead->pages ? e($lead->pages) : '<span class="badge badge-light-danger fs-7 fw-bold">No Pages</span>' !!}
    </td>
    <td class="text-center">
        @if(!empty($lead->coupon_code))
            <div class="text-success small fw-bold">Coupon: {{ $lead->coupon_code }}</div>
            <div class="text-danger small">-£{{ number_format((float)($lead->coupon_discount_amount ?? 0), 2) }}</div>
        @endif
        {!! $lead->price ? (is_numeric($lead->price) ? '£' . e($lead->price) : e($lead->price)) : '<span class="badge badge-light-danger fs-7 fw-bold">No Price</span>' !!}
    </td>
    <td class="text-center">
        @php
            $orderRecord = $lead->attached_order_record ?? \App\Models\Order::where(function($q) use ($lead) {
                $q->where('lead_id', $lead->id);
                if (!empty($lead->order_id)) {
                    $q->orWhere('order_id', (string)$lead->order_id);
                }
            })->first();

            $basePriceAmt = $orderRecord && is_numeric($orderRecord->amount) 
                ? (float)$orderRecord->amount 
                : (is_numeric($lead->price) ? (float)$lead->price : 0);

            $recvPriceAmt = $orderRecord && is_numeric($orderRecord->received_amount) 
                ? (float)$orderRecord->received_amount 
                : 0;

            $dueAmt = max(0, $basePriceAmt - $recvPriceAmt);
        @endphp

        @if($basePriceAmt > 0 || is_numeric($lead->price))
            £{{ $dueAmt }}
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
        @endif
    </td>
    <td class="text-center">
        {{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}
        @if ($lead->delivery_time)
        <span class="badge badge-light-info fs-7 fw-bold">({{ $lead->delivery_time }})</span>
        @endif

        @if ($lead->draft_required == 'Yes')
        <br><span class="badge badge-light-success fs-7">{{ $lead->draft_date }}</span>
        <br><span class="badge badge-light-success fs-7">{{ $lead->draft_time }}</span>
        @endif
    </td>
</tr>

@if(isset($filesList) && $filesList->count() > 0)
<div class="modal fade text-start" id="leadFilesModal-{{ $lead->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white px-4 py-3 align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-folder-open text-warning fs-4"></i>
                    <h5 class="modal-title fw-bold text-white mb-0">
                        Lead Files & Attachments
                        <span class="badge bg-primary fs-8 ms-2">#{{ $lead->order_id ?? $lead->id }}</span>
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    @foreach($filesList as $f)
                        @php
                            $path = $f->file_data ?? $f->file_name ?? '';
                            if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                                if (!str_contains($path, '/')) {
                                    $path = 'images/orders/' . $path;
                                }
                                $fullUrl = asset(ltrim($path, '/'));
                            } else {
                                $fullUrl = $path;
                            }

                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']) || str_contains(strtolower($f->file_type ?? ''), 'image');
                            $fileName = $f->file_name ?? basename($path);
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden" style="background: #ffffff;">
                                
                                <!-- File Preview Container -->
                                <div class="text-center p-3 d-flex align-items-center justify-content-center" style="height: 140px; background: #f8f9fa; border-bottom: 1px solid #eef2f5;">
                                    @if($isImage)
                                        <a href="{{ $fullUrl }}" target="_blank" title="Click to view full image">
                                            <img src="{{ $fullUrl }}" class="img-fluid rounded shadow-sm" style="max-height: 110px; max-width: 100%; object-fit: contain;" onerror="this.onerror=null; this.src='https://via.placeholder.com/120?text=Image';">
                                        </a>
                                    @else
                                        <div class="text-center">
                                            @if($ext == 'pdf')
                                                <i class="fa fa-file-pdf text-danger fs-1 mb-2"></i>
                                            @elseif(in_array($ext, ['doc', 'docx']))
                                                <i class="fa fa-file-word text-primary fs-1 mb-2"></i>
                                            @elseif(in_array($ext, ['xls', 'xlsx']))
                                                <i class="fa fa-file-excel text-success fs-1 mb-2"></i>
                                            @elseif(in_array($ext, ['zip', 'rar', '7z']))
                                                <i class="fa fa-file-archive text-warning fs-1 mb-2"></i>
                                            @else
                                                <i class="fa fa-file-alt text-secondary fs-1 mb-2"></i>
                                            @endif
                                            <div class="fw-bold text-uppercase fs-8 text-muted">{{ $ext ?: 'FILE' }}</div>
                                        </div>
                                    @endif
                                </div>

                                <!-- File Details -->
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <div class="fw-bold text-dark text-truncate fs-7" title="{{ $fileName }}">
                                            {{ $fileName }}
                                        </div>
                                        <div class="text-muted fs-8">
                                            Uploaded: {{ \Carbon\Carbon::parse($f->created_at ?? now())->format('d M Y, h:i A') }}
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex gap-2 mt-auto">
                                        <a href="{{ $fullUrl }}" target="_blank" class="btn btn-sm btn-light-info flex-grow-1 d-flex align-items-center justify-content-center gap-1 py-1 fs-8 fw-bold">
                                            <i class="fa fa-eye fs-8"></i> View
                                        </a>
                                        <a href="{{ $fullUrl }}" download="{{ $fileName }}" class="btn btn-sm btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1 py-1 fs-8 fw-bold">
                                            <i class="fa fa-download fs-8"></i> Download
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-white px-4 py-2">
                <button type="button" class="btn btn-light-secondary btn-sm fw-bold" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endif

<div class="modal fade" id="hideLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Mark Duplicate Lead
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-bold">
                    Order ID
                </label>
                <input type="hidden" id="duplicate_lead_id">
                <input type="text"
                       id="hide_order_id"
                       class="form-control"
                       placeholder="Enter Order ID">
                {{-- <small class="text-muted mt-2 d-block">
                    This lead will be hidden from lead list but not deleted.
                </small> --}}
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button"
                        class="btn btn-danger"
                        onclick="hideLeadByOrderId()">

                    <i class="fa fa-eye-slash"></i>
                    Mark Duplicate
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clientReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="text-center mb-10">
                    <h3 class="mb-3">Client Behaviour</h3>
                    <div class="text-muted fw-bold fs-6">Add notes about client behaviour and interaction</div>
                </div>

                <form id="clientReviewForm" class="form">
                    <input type="hidden" id="review_user_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <textarea class="form-control form-control-solid" rows="4" id="review_text" placeholder="Enter client behaviour details..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3 btn-sm">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveClientReview()">
                            Save Behaviour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>

    .lead-comment-box {
    background: #f5f8fa;
    border: 1px solid #e4e6ef;
    border-radius: 8px;
    padding: 12px 14px;
    width: 260px;
    min-height: 92px;
    margin: 0 auto;
    text-align: left;
    box-shadow: none;
    }

    .lead-comment-box .text-gray-800 {
        color: #3f4254;
        font-size: 14px;
        line-height: 1.4;
    }

    .lead-comment-box .fs-8 {
        font-size: 12px !important;
    }
    /* Slide-in animation */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Desktop defaults */


    .modal.fade.custom-slide-right .modal-dialog {
        position: fixed;
        top: 0;
        right: 0;
        margin: 0;
        height: 100vh;

        width: 40%;
        transform: translateX(100%);
        transition: transform 0.4s ease-out;
        display: flex;
        flex-direction: column;
    }

    .modal.fade.custom-slide-right.show .modal-dialog {
        transform: translateX(0);
        animation: slideInRight 0.4s ease-out;
        border-radius: 0;
    }

    /* Modal content should fill height */
    /* .modal-content {
        height: 100vh;
        display: flex;
        flex-direction: column;
        border-radius: 0;
    } */

    .modals {
        height: 100vh;
        display: flex;
        flex-direction: column;
        border-radius: 0;
    }

    /* Body grows and scrolls as needed */
    .modal-body {
        flex: 1;
        overflow-y: auto;
    }

    /* Responsive for mobile */
    @media (max-width: 768px) {
        .modal.fade.custom-slide-right .modal-dialog {
            width: 100%;
        }
    }
</style>

<script>
    window.initLeadStars = function(container = document) {
        const ratings = container.matches && container.matches('.star-rating')
            ? [container]
            : container.querySelectorAll('.star-rating');

        ratings.forEach(rating => {
            const stars = rating.querySelectorAll('.star');
            const current = rating.getAttribute('data-current');

            let fillCount = 0;
            if (current === 'Cold') fillCount = 1;
            if (current === 'Warm') fillCount = 2;
            if (current === 'Hot') fillCount = 3;

            stars.forEach(star => star.classList.remove('active'));
            for (let i = 0; i < fillCount; i++) {
                stars[i].classList.add('active');
            }
        });
    };

    window.updateLeadStatusTabCount = function(status, change) {
        if (!['Cold', 'Warm', 'Hot'].includes(status) || change === 0) return;

        const badge = document.querySelector(`.lead-status-tabs .nav-link[data-status="${status}"] .lead-tab-count`);
        if (!badge) return;

        const current = parseInt((badge.textContent || '0').trim(), 10) || 0;
        badge.textContent = Math.max(0, current + change);
    };

    document.addEventListener('DOMContentLoaded', function() {

        // ⭐ Auto fill from DB (run once)
        document.querySelectorAll('.star-rating').forEach(rating => {

            const stars = rating.querySelectorAll('.star');
            const current = rating.getAttribute('data-current');

            let fillCount = 0;
            if (current === 'Cold') fillCount = 1;
            if (current === 'Warm') fillCount = 2;
            if (current === 'Hot') fillCount = 3;

            for (let i = 0; i < fillCount; i++) {
                stars[i].classList.add('active');
            }
        });

    });


    // ⭐ Click handler (EVENT DELEGATION - only once)
    if (!window.leadStarClickHandlerBound) {
    window.leadStarClickHandlerBound = true;
    document.addEventListener('click', function(e) {

        if (!e.target.classList.contains('star')) return;

        let star = e.target;
        let rating = star.closest('.star-rating');

        let stars = rating.querySelectorAll('.star');
        let value = star.getAttribute('data-value');
        let leadId = rating.getAttribute('data-id');
        let oldStatus = rating.getAttribute('data-current') || '';

        // 🔒 Prevent multiple API calls
        if (rating.dataset.loading === "1") return;
        rating.dataset.loading = "1";

        // UI update
        stars.forEach(s => s.classList.remove('active'));
        for (let i = 0; i < value; i++) {
            stars[i].classList.add('active');
        }

        // Mapping
        let status = '';
        if (value == 1) status = 'Cold';
        if (value == 2) status = 'Warm';
        if (value == 3) status = 'Hot';

       console.log('hh');
        fetch("{{ route('update-leads-status') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    lead_id: leadId,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                if (data.status) {
                    if (oldStatus !== status) {
                        window.updateLeadStatusTabCount(oldStatus, -1);
                        window.updateLeadStatusTabCount(status, 1);
                    }
                    rating.setAttribute('data-current', status);
                } else {
                    window.initLeadStars(rating);
                    alert("Failed to update status");
                }
            })
            .catch(err => {
                window.initLeadStars(rating);
                console.error(err);
            })
            .finally(() => {
                rating.dataset.loading = "0";
            });

    });
    }
</script>
<script>
    function openReviewModal(userId, existingReview) {
        $('#review_user_id').val(userId);
        $('#review_text').val(existingReview);
        $('#clientReviewModal').modal('show');
    }

    function saveClientReview() {
        let userId = $('#review_user_id').val();
        let reviewText = $('#review_text').val();

        $.ajax({
            url: "{{ route('user.save.review') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                user_id: userId,
                client_review: reviewText
            },
            success: function(response) {
                if (response.success) {
                    $('#clientReviewModal').modal('hide');
                    Swal.fire({
                        text: "Review saved successfully!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            },
            error: function() {
                alert("Something went wrong!");
            }
        });
    }
</script>

<script>
    function openDuplicateLeadModal(leadId) {
        $('#duplicate_lead_id').val(leadId);
        $('#hide_order_id').val('');
    }

    function hideLeadByOrderId() {
        let leadId = $('#duplicate_lead_id').val();
        let orderId = $('#hide_order_id').val();

        if (!leadId) {
            Swal.fire('Error', 'Lead ID missing', 'error');
            return;
        }
        if (!orderId) {
            Swal.fire('Error', 'Please enter Order ID', 'error');
            return;
        }
        $.ajax({
            url: "{{ route('lead.duplicate') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                lead_id: leadId,
                order_id: orderId
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire('Success', response.message, 'success')
                        .then(() => {
                            $('#hideLeadModal').modal('hide');
                            $('#hide_order_id').val('');
                            $('#duplicate_lead_id').val('');
                            location.reload();
                        });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Something went wrong!', 'error');
                console.log(xhr.responseText);
            }
        });
    }
</script>
