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

                <!-- Row 2, Col 2b: Call Buttons: Twilio & Next2Call -->
                @php
                    $leadPhone = $lead->user->mobile_no ?? $lead->mobile ?? '';
                    $leadCC = preg_replace('/\D+/', '', (string)($lead->user->countrycode ?? $lead->countrycode ?? ''));
                    $leadName = addslashes($lead->user->name ?? $lead->user_name ?? 'Customer');
                @endphp
                @if($leadPhone)
                {{-- Twilio Call Button --}}
                <a href="#"
                   id="twilioCallBtnlead{{ $lead->id }}"
                   onclick="event.preventDefault(); event.stopPropagation(); initiateTwilioCall('{{ $leadCC . $leadPhone }}', '{{ $leadName }}');"
                   class="btn btn-sm btn-icon p-0 d-inline-flex align-items-center justify-content-center shadow-xs"
                   style="width:28px;height:28px;border-radius:6px;background-color:#25D366;color:#ffffff;transition:transform 0.2s ease,background-color 0.2s ease;"
                   onmouseover="this.style.backgroundColor='#1ebd58';this.style.transform='scale(1.1)';"
                   onmouseout="this.style.backgroundColor='#25D366';this.style.transform='scale(1)';"
                   title="Call via Twilio">
                    <i class="fa fa-phone text-white" style="font-size:12px;"></i>
                </a>

                {{-- Next2Call Button (with red 2 badge) --}}
                <a href="#"
                   id="n2cCallBtnlead{{ $lead->id }}"
                   onclick="event.preventDefault(); event.stopPropagation(); initiateNext2Call('{{ $leadCC . $leadPhone }}', '{{ $leadName }}');"
                   class="btn btn-sm btn-icon p-0 d-inline-flex align-items-center justify-content-center shadow-xs position-relative"
                   style="width:28px;height:28px;border-radius:6px;background-color:#25D366;color:#ffffff;transition:transform 0.2s ease,background-color 0.2s ease;"
                   onmouseover="this.style.backgroundColor='#1ebd58';this.style.transform='scale(1.1)';"
                   onmouseout="this.style.backgroundColor='#25D366';this.style.transform='scale(1)';"
                   title="Call via Next2Call">
                    <i class="fa fa-phone text-white" style="font-size:12px;"></i>
                    <span style="position:absolute;bottom:-2px;right:-1px;background:#e53e3e;color:#ffffff;font-size:8px;font-weight:900;line-height:1;padding:1px 2.5px;border-radius:2px;box-shadow:0 1px 2px rgba(0,0,0,0.3);font-family:Arial,sans-serif;pointer-events:none;">2</span>
                </a>
                @endif

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

            <!-- Follow-up Button -->
            <div class="w-100 d-flex justify-content-center mt-1">
                <button type="button" 
                        onclick="openLeadFollowupDrawer({{ $lead->id }})" 
                        class="btn btn-sm btn-light-primary w-100 py-1 px-2 fs-8 fw-bolder d-flex align-items-center justify-content-center gap-1 shadow-xs" 
                        style="max-width: 148px; border: 1px solid #b5d8ff;"
                        title="Lead Follow-ups">
                    <i class="fa fa-calendar-check-o text-primary fs-8"></i>
                    <span>Followup</span>
                    @if(!empty($lead->next_followup_date))
                        <span id="lead_followup_badge_{{ $lead->id }}" class="badge badge-primary px-1 py-0 fs-9 ms-1" title="Next Followup: {{ \Carbon\Carbon::parse($lead->next_followup_date)->format('d M') }}">
                            {{ \Carbon\Carbon::parse($lead->next_followup_date)->format('d M') }}
                        </span>
                    @else
                        <span id="lead_followup_badge_{{ $lead->id }}" class="badge badge-secondary px-1 py-0 fs-9 ms-1 d-none"></span>
                    @endif
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
        <div class="d-inline-flex align-items-center justify-content-center">
            @if ($lead['frontendorder'] == '1')
                <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
            @else
                <span>{{ $lead->order_id }}</span>
            @endif
            @if(!empty($lead->order_id))
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $lead->order_id }}', 'Order ID copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            @endif
        </div>
        <br>
        @php
            $creatorUser = $lead->creator;
            if (!$creatorUser && !empty($lead->created_by) && is_numeric($lead->created_by)) {
                $creatorUser = \App\Models\User::select('id', 'name')->find($lead->created_by);
            }
            if ($creatorUser) {
                $creatorDisplay = $creatorUser->name . ' (ID: ' . $creatorUser->id . ')';
            } elseif (!empty($lead->created_by) && !is_numeric($lead->created_by)) {
                $creatorDisplay = $lead->created_by;
            } elseif (!empty($lead->frontendorder) && $lead->frontendorder == 1) {
                $creatorDisplay = 'Website / Frontend';
            } elseif (!empty($lead->lead_source) && $lead->lead_source == 8) {
                $creatorDisplay = 'WhatsApp Bot';
            } else {
                $creatorDisplay = 'Website / System';
            }
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
            $filesList = $lead->attached_files ?? collect();
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

        <div class="fw-bold">{{ $lead->user->name ?? 'No Name' }}</div>
        @php
            $rowUserId = $lead->user->id ?? $lead->emp_id ?? null;
        @endphp
        @if(!empty($rowUserId))
            <div class="d-inline-flex align-items-center gap-1 my-1">
                <span class="badge badge-light-dark fs-8 fw-bold">ID: {{ $rowUserId }}</span>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-dark p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy User ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $rowUserId }}', 'User ID copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            </div><br>
        @endif
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

        @php
            $leadUserMobile = $lead->user->mobile_no ?? $lead->mobile ?? null;
            $leadCountryCode = $lead->user->countrycode ?? $lead->countrycode ?? null;
            $leadUserEmail = $lead->user->email ?? $lead->email ?? null;
        @endphp

        @if(!empty($leadUserMobile))
            @php 
                $leadDisplayMobile = mask_mobile_only($leadCountryCode, $leadUserMobile); 
                $cleanLeadCC = preg_replace('/\D+/', '', (string)$leadCountryCode);
            @endphp
            <div class="d-inline-flex align-items-center justify-content-center gap-1 my-1">
                @if(!empty($cleanLeadCC))
                    <span class="badge badge-light-primary fs-8 fw-bold">+{{ $cleanLeadCC }}</span>
                @endif
                <span class="badge badge-light-danger fs-7 fw-bold">{{ $leadDisplayMobile }}</span>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $leadDisplayMobile }}', 'Mobile number copied!');">
                    <i class="fa fa-clone fs-8 text-danger"></i>
                </button>
            </div><br>
        @endif

        @if(!empty($leadUserEmail))
            @php $leadDisplayEmail = mask_email_for_display($leadUserEmail); @endphp
            <div class="d-inline-flex align-items-center justify-content-center my-1">
                <span class="fs-7 fw-bold text-gray-700 text-break">{{ $leadDisplayEmail }}</span>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $leadDisplayEmail }}', 'Email copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            </div><br>
        @endif

        @php
            $rawLeadWAPhone = preg_replace('/\D+/', '', (string)(($leadCountryCode ?? '') . ($leadUserMobile ?? '')));
            $leadRawEmail = $lead->user->email ?? $lead->email ?? '';
            $leadEmailUrl = !empty($leadRawEmail) ? route('emails.index', ['search' => $leadRawEmail]) : route('emails.index');
        @endphp

        {{-- Direct Contact Actions: WhatsApp & Email --}}
        <div class="d-inline-flex align-items-center justify-content-center gap-2 my-1">
            <form method="POST" action="{{ route('whatsapp.chat.open-lead') }}" target="_blank" class="d-inline-flex m-0">
                @csrf
                <input type="hidden" name="lead_ref" value="{{ $lead->id }}">
                <button type="submit" class="btn btn-icon btn-sm crm-btn-wa" title="Open WhatsApp Chat">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                </svg>
                </button>
            </form>
            <a href="{{ $leadEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" title="Email: {{ $leadRawEmail ?: 'Open Emails' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                </svg>
            </a>
        </div>
        <br>

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
        @if($lead->project_title)
            <div class="d-inline-flex align-items-center justify-content-center flex-wrap">
                <span>{{ $lead->project_title }}</span>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Project Title" data-copy-text="{{ $lead->project_title }}" onclick="event.stopPropagation(); crmCopyToClipboard(this.getAttribute('data-copy-text'), 'Project Title copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            </div>
        @else
            <span class="badge badge-light-danger fs-7 fw-bold">No Title</span>
        @endif
        @if ($lead->semester)
        <br><span class="badge badge-light-success fs-7">Semester: {{ $lead->semester }}</span>
        @endif
        @if ($lead->tech === 'on')
        <br><span class="badge badge-light-success fs-7">Technical Work</span>
        @endif
        @if ($lead->module_code)
            <br>
            <div class="d-inline-flex align-items-center justify-content-center mt-1">
                <span class="badge badge-light-danger fs-7">{{ $lead->module_code }}</span>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Module Code" data-copy-text="{{ $lead->module_code }}" onclick="event.stopPropagation(); crmCopyToClipboard(this.getAttribute('data-copy-text'), 'Module Code copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            </div>
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
