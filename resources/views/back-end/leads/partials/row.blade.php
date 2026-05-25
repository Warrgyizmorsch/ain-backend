@php
    $orderIdStyle  = "";

    if (!empty($lead->user)) {
        $hasFailedOrder = \App\Models\Order::where('uid', $lead->user->id)
            ->where('is_fail', 1)
            ->exists();

        if ($hasFailedOrder) {
            $orderIdStyle  = "background-color:#ffeaea !important; color:#b50000 !important; border:2px solid #ff0000 !important;";
        }
    }
@endphp
<tr id="lead-{{ $lead->id }}">
    <td class="text-center" style="padding-right: 0px;">{{ $index + 1 }}</td>

    <td class="text-center">
        <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
            @if($lead->flag == '1')
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input onchange="checkedLead(this, {{ $lead->id }})" class="form-check-input widget-13-check" type="checkbox"
                    checked value="1">
            </div>
            @else
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input onchange="checkedLead(this, {{ $lead->id }})" class="form-check-input widget-13-check" type="checkbox"
                    value="1">
            </div>
            @endif
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="{{ $lead->id }}" role="switch" checked
                    onchange="handleChange(this, {{ $lead->id }})">
            </div>
            <a href="{{ url('/lead/edit/' . $lead->id) }}" target="_blank" class="btn btn-sm btn-icon"
                style="background-color: #1e1e2d;">
                <li style="color: white;" class="fa fa-edit"></li>
            </a>
            <button type="button" class="btn btn-sm btn-primary btn-icon" onclick="convert(this, {{ $lead->id }})"
                id="convert-btn-{{ $lead->id }}">
                <i class="fa fa-sync"></i>
            </button>

            <!-- @if (auth()->user()->role_id == 1)
                <button type="button" id="loadTemplatesBtn{{ $lead->user->id ?? '' }}" class="btn btn-sm btn-success btn-icon"
                    onclick="loadTemplates({{ $lead->user->id ?? '' }})">
                    <i class="fa fa-whatsapp"></i>
                </button>

            @endif
             -->
            <button type="button" id="loadChat{{ $lead->id }}" class="btn btn-sm btn-warning btn-icon"
                onclick="loadchat({{ $lead->id }})">
                <li class="fa fa-phone"></li>
            </button>
            <!-- Fullscreen Chat Modal -->
            <!-- Select per lead/order -->
            <!-- Assign Type Toggle -->
            <div class="form-check form-switch" style="margin-left:6px;">
                <input
                    class="form-check-input assign-toggle"
                    type="checkbox"
                    id="type{{ $lead->id }}"
                    {{ ($lead->assign_type ?? 0) == 1 ? 'checked' : '' }}
                    onchange="handleTypeToggle(this, {{ $lead->id }})">
            </div>
            <button type="button" class="btn btn-sm btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#hideLeadModal" onclick="openDuplicateLeadModal({{ $lead->id }})" title="Mark Duplicate Lead">
                D
            </button>
            <style>
                .assign-toggle {
                    transform: scale(1.1);
                    cursor: pointer;
                }

                /* OFF = AIN (Yellow) */
                .assign-toggle:not(:checked) {
                    background-color: #FFC107 !important;
                    border-color: #e0a800 !important;
                }

                /* ON = Let's Learn (Green) */
                .assign-toggle:checked {
                    background-color: #28a745 !important;
                    border-color: #1e7e34 !important;
                }
            </style>
            <script>
                function handleTypeToggle(el, leadId) {
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

                            //  SAME tumhara original code
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
                            //  cancel pe toggle wapas
                            el.checked = !el.checked;
                        }
                    });
                }
            </script>
            <select
                id="leadReason{{ $lead->id }}"
                name="lead_reason[{{ $lead->id }}]"
                class="mini-select"
                onchange="handleLeadReason({{ $lead->id }})">
                <option value="">Select</option>
                <!-- <option value="Hot" {{ ($lead->l_status ?? '') == 'Hot' ? 'selected' : '' }}>Hot</option> -->
                <option value="Price" {{ ($lead->l_status ?? '') == 'Price' ? 'selected' : '' }}>Price</option>
                <option value="Deadline" {{ ($lead->l_status ?? '') == 'Deadline' ? 'selected' : '' }}>Deadline</option>
                <!-- <option value="Feedback" {{ ($lead->l_status ?? '') == 'Feedback' ? 'selected' : '' }}>Feedback</option> -->
                <option value="Serious Concern" {{ ($lead->l_status ?? '') == 'Serious Concern' ? 'selected' : '' }}>Serious Concern</option>
                <option value="Marks" {{ ($lead->l_status ?? '') == 'Marks' ? 'selected' : '' }}>Marks</option>
                <option value="Unknown" {{ ($lead->l_status ?? '') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                <option value="Quality" {{ ($lead->l_status ?? '') == 'Quality' ? 'selected' : '' }}>Quality</option>
                <option value="Customer Service" {{ ($lead->l_status ?? '') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
            </select>
            <style>
                .mini-select {
                    width: 130px !important;
                    height: 32px;
                    padding: 0 6px;
                    font-size: 12px;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                }

                .star-rating {
                    cursor: pointer;
                }

                .star {
                    color: #ccc;
                    font-size: 16px;
                }

                .star.active {
                    color: gold;
                }
            </style>
            <script>
                function handleLeadReason(leadId) {
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
                            console.log("Server Response:", data);
                            if (!data.status) {
                                alert("Update failed");
                            }
                        })
                        .catch(() => alert("Server error"));
                }
            </script>
        </div>

    </td>
    <td class="text-center">
        @php
            $latestCall = \DB::table('calls')
                ->where('lead_id', $lead->id)
                ->latest('id')
                ->first();

            $commentUser = $latestCall
                ? \App\Models\User::find($latestCall->created_by)
                : null;
        @endphp

        @if($latestCall)
            <div class="lead-comment-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-gray-800">
                        {{ $commentUser->name ?? 'User' }}
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
            <span class="text-muted">No Comment</span>
        @endif
    </td>
    <td class="text-center" style="{{ $orderIdStyle }}">
        @if ($lead['frontendorder'] == '1')
        <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
        @else
        {{ $lead->order_id }}
        @endif
        <br>
        @if ($lead['resit'] == 'on')
        <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
        @endif
        @if ($lead['service_type'] == 'First Class Work')
        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
        @endif
    </td>

    <td class="text-center">
        {{-- {{ $lead->user->name ?? 'No Name' }}<br> --}}
        @php
            $userLeadCount = !empty($lead->user)
    ? \App\Models\Leads::where('emp_id', $lead->user->id)
        ->where('is_converted', 0)
        ->where('status', 0)
        ->where('duplicate_lead', 0)
        ->count()
    : 0;
        @endphp

        {{ $lead->user->name ?? 'No Name' }}<br>

        <span class="badge badge-light-primary fs-8 fw-bold ms-1">
            Leads: {{ $userLeadCount }}
        </span>
        <br>

        @if(!empty($lead->user))

        @php
            $count = \App\Models\Order::where('uid', $lead->user->id)->count();

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
    <td class="text-center">{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</br>
    @if($lead->lead_source)
        <strong>Source:</strong><span>{{$lead->source->source_name}}</span>
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
        {!! $lead->price ? e($lead->price) : '<span class="badge badge-light-danger fs-7 fw-bold">No Price</span>' !!}
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
    document.addEventListener('click', function(e) {

        if (!e.target.classList.contains('star')) return;

        let star = e.target;
        let rating = star.closest('.star-rating');

        let stars = rating.querySelectorAll('.star');
        let value = star.getAttribute('data-value');
        let leadId = rating.getAttribute('data-id');

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

                } else {
                    alert("Failed to update status");
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                rating.dataset.loading = "0";
            });

    });
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