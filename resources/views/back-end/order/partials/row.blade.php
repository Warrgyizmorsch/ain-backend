    @php
    $roleId = optional(auth()->user())->role_id ?? 0;
    $orderIdStyle = "";
    $isFrontendOrder = optional($order->lead)->frontendorder == '1'
        || optional($order->frontendLead)->frontendorder == '1';

    $effectiveUser = $order->user ?: (optional($order->lead)->user ?: optional($order->frontendLead)->user);
    $effectiveTitle = $order->title ?: (optional($order->lead)->project_title ?: optional($order->frontendLead)->project_title);
    $effectiveOrderDate = $order->order_date ?: (optional($order->lead)->created_at ?: (optional($order->lead)->create_at ?: $order->created_at));
    $effectiveDeliveryDate = $order->delivery_date ?: (optional($order->lead)->deadline ?: optional($order->frontendLead)->deadline);
    $effectiveDeliveryTime = $order->delivery_time ?: (optional($order->lead)->delivery_time ?: optional($order->frontendLead)->delivery_time);
    $effectivePages = $order->pages ?: (optional($order->lead)->pages ?: optional($order->frontendLead)->pages);
    $effectiveAmount = $order->amount ?: (optional($order->lead)->price ?: optional($order->frontendLead)->price);
    $effectiveSemester = $order->semester ?: (optional($order->lead)->semester ?: optional($order->frontendLead)->semester);
    $effectiveChapter = $order->chapter ?: (optional($order->lead)->chapter ?: optional($order->frontendLead)->chapter);
    $effectiveTech = $order->tech ?: (optional($order->lead)->tech ?: optional($order->frontendLead)->tech);
    $effectiveResit = $order->resit ?: (optional($order->lead)->resit ?: optional($order->frontendLead)->resit);
    $effectiveModuleCode = $order->module_code ?: (optional($order->lead)->module_code ?: optional($order->frontendLead)->module_code);
    $effectiveServices = $order->services ?: (optional($order->lead)->service_type ?: optional($order->frontendLead)->service_type);

    if ($order->failed_followup_highlight ?? false) {
        $orderIdStyle = "
            background-color: #ffeaea !important;
            color: #b50000 !important;
            border: 2px solid #ff0000 !important;
        ";
    } elseif (optional($effectiveUser)->feedback_issue == 1) {
        $orderIdStyle = "color: blue;";
    }

    @endphp
    {{-- <tr id="lead-{{ $order->id }}" @if( optional($effectiveUser)->is_fail == 1 || optional($effectiveUser)->feedback_issue == 1) style="color:blue" @endif id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" > --}}
    <tr id="lead-{{ $order->id }}">
        <td class="text-center" style="padding-right: 0px;">
            {{ $index + 1 }}
        </td>


        <td class="text-center" style="position: sticky; left: 0; background: white; z-index: 2;">
            <div style="display:grid; grid-template-columns:repeat(4, max-content); justify-content:center; align-items:center; gap:6px;">

                @if($effectiveUser)
                    <button type="button" class="btn btn-sm btn-light-success fw-bold" title="Manage User Groups" data-user-group-button="{{ $effectiveUser->id }}" data-groups='@json($effectiveUser->groups->pluck("id"))' onclick="openUserGroupModal({{ $effectiveUser->id }}, @js($effectiveUser->name), JSON.parse(this.dataset.groups))">G</button>
                @endif

                <!-- Edit Order Button -->
                <a target="_blank" style="background-color: #1e1e2d;" href="orders/edit/{{ $order->id }}" class="btn btn-icon btn-sm" title="Edit Order">
                    <i style="color: white;" class="fa fa-edit"></i>
                </a>

                <!-- Call Customer Button (Twilio) -->
                @php
                    $rowPhone = optional($effectiveUser)->mobile_no ?? '';
                    $rowCC = preg_replace('/\D+/', '', (string)(optional($effectiveUser)->countrycode ?? ''));
                    $rowName = addslashes(optional($effectiveUser)->name ?? 'Customer');
                @endphp
                @if($rowPhone)
                <a href="#"
                   id="twilioCallBtnorderrow{{ $order->id }}"
                   onclick="event.preventDefault(); event.stopPropagation(); initiateCustomerCall('{{ $rowCC . $rowPhone }}', '{{ $rowName }}');"
                   class="btn btn-icon btn-sm"
                   style="width:28px;height:28px;min-width:28px;border-radius:6px;background-color:#25D366;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;transition:transform 0.2s ease,background-color 0.2s ease;"
                   onmouseover="this.style.backgroundColor='#1ebd58';this.style.transform='scale(1.1)';"
                   onmouseout="this.style.backgroundColor='#25D366';this.style.transform='scale(1)';"
                   title="Call via Twilio">
                    <i class="fa fa-phone text-white" style="font-size:12px;"></i>
                </a>
                @endif

                <!-- Button to Open Unified Payment Page -->
                <a href="{{ route('orders.payment.form', ['orderId' => $order->id]) }}"
                    target="_blank"
                    class="btn btn-icon btn-success btn-sm position-relative"
                    title="Add/Edit Payment">
                    <i class="fa fa-money"></i>
                    @if($order->payment->contains(function($p) {
                        return empty($p->payee_name) || empty($p->company_accounts);
                    }))
                    <i class="fa fa-question-circle text-danger bg-white"
                        title="Incomplete payment info"
                        style="position: absolute; top: -3px; right: -3px; font-size: 11px; border-radius: 50%;"></i>
                    @endif
                </a>

                <!-- Mark as Failed Button -->
                <a href="javascript:void(0);" onclick="showConfirmation({{ $order->id }}, {{ $order->is_fail }})" class="btn btn-icon btn-danger btn-sm" title="Mark as Failed">
                    <i class="fa fa-times-circle"></i>
                </a>

                @if(in_array($roleId, [1, 4, 9]))
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Looking For Refund" onclick="markLookingForRefund({{ $order->id }})">
                        <span class="fw-bold fs-6">R</span>
                    </button>

                    <button type="button" class="btn btn-icon btn-sm btn-light-primary" title="Add Additional" onclick="openAdditionalModal({{ $order->id }})">
                        <span class="fw-bold fs-6">A</span>
                    </button>
                @endif

                @if($roleId == 9)
                <a onclick="CallToWriter('{{ $order->id }}')" class="btn btn-icon btn-bg-warning btn-active-color-dark btn-color-white btn-sm me-1 download-btn">
                    <span class="svg-icon svg-icon-3">
                        <i class="fa fa-phone fa-lg"></i>
                    </span>
                </a>
                @endif

                <!-- Chat / Comment Button (MOVED TO LAST POSITION) -->
                <button onclick="loadCommentDrawer({{ $order->id }})" class="btn btn-icon btn-secondary btn-sm" title="Open Chat / Comments">
                    <span>T</span>
                </button>
            </div>
        </td>
        
        <td id="order-cell-{{ $order->id }}" class="text-center {{ ($order->is_read == 1) ? 'bold-row' : '' }}" style="{{ $orderIdStyle }}">
           @if($isFrontendOrder)
                <div class="d-inline-flex align-items-center justify-content-center">
                    <span class="badge badge-light-primary fs-7 fw-bold">
                        {{ $order->order_id }}
                    </span>
                    @if(!empty($order->order_id))
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $order->order_id }}', 'Order ID copied!');">
                            <i class="fa fa-clone fs-8 text-muted"></i>
                        </button>
                    @endif
                </div><br>
            @else
                <div class="d-inline-flex align-items-center justify-content-center">
                    <span class="fw-bold text-gray-800">
                        {{ $order->order_id }}
                    </span>
                    @if(!empty($order->order_id))
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $order->order_id }}', 'Order ID copied!');">
                            <i class="fa fa-clone fs-8 text-muted"></i>
                        </button>
                    @endif
                </div><br>
            @endif
            @if($order->team?->team_name)
                @php
                    $orderRawEmail = optional($effectiveUser)->email ?? '';
                    $writeEmailUrl = route('emails.index', array_filter(['account_id' => 3, 'search' => $orderRawEmail]));
                @endphp
                <div class="d-inline-flex align-items-center justify-content-center gap-1 mb-1">
                    @if($roleId == 1)
                        <span 
                            class="badge badge-light-primary fs-7 fw-bold cursor-pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#changeTeamModal"
                            onclick="openTeamModal('{{ $order->id }}', '{{ $order->team_id }}')"
                        >
                            {{ $order->team->team_name }}
                        </span>
                    @else
                        <span class="badge badge-light-primary fs-7 fw-bold">
                            {{ $order->team->team_name }}
                        </span>
                    @endif
                    <a href="{{ $writeEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 20px !important; height: 20px !important; min-width: 20px !important;" title="Write Email: {{ $orderRawEmail ?: 'Open Write Email Channel' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                    </a>
                </div><br>
            @endif

            @if($order->marks)
            <span class="fs-7 fw-bold">Marks:</span>{{ $order->marks }}<br>
            @endif

            @if($effectiveSemester)
            <span class="badge badge-light-warning fs-7 fw-bold mb-1">{{ $effectiveSemester }}</span>
            @endif

             @if($order->offer)
             <span class="badge badge-light-success fs-7 fw-bold mb-1">{{$order->offer}}</span><br>
            @endif

            <span id="orderTicketNumber{{ $order->id }}"
                class="badge badge-light-danger fs-7 fw-bold mb-1 {{ $order->feedback_ticket ? '' : 'd-none' }}">
                {{ $order->feedback_ticket ?: '' }}
            </span>

            @if ($effectiveResit == 'on' || $effectiveResit == '1')
            <span class="badge badge-light-danger fs-7 fw-bold mb-1">Resit</span><br>
            @endif

            @if($effectiveServices == 'First Class Work')
            <span class="badge badge-light-info fs-7 fw-bold mb-1">First Class Work</span>
            @endif

            @if($order->is_fail == 1)
            <span class="badge badge-light-danger fs-7 fw-bold mt-1">
                Fail Order<br>{{ \Carbon\Carbon::parse($order->failed_at)->format('d M Y H:i:s A') }}
            </span>
            @endif
        </td>
         <td class="text-center" id="referral-cell-{{ $order->id }}">
            @if($order->referal && strtolower($order->referal) !== 'no')
                <span class="badge badge-light-success fs-7 fw-bold mb-1">Conversation: Yes</span><br>
                @if($order->client_will_refer)
                    <span class="badge {{ $order->client_will_refer === 'yes' ? 'badge-light-success' : 'badge-light-danger' }} fs-7 fw-bold">
                        Will Refer: {{ $order->client_will_refer === 'yes' ? 'Yes' : 'No' }}
                    </span>
                @endif
            @else
                <div class="dropdown d-inline-block">
                    <button class="btn {{ $order->referal && strtolower($order->referal) === 'no' ? 'btn-light-danger btn-referral-no' : 'btn-light btn-active-light-primary' }} btn-sm py-1 px-3 fs-7 fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $order->referal && strtolower($order->referal) === 'no' ? 'Conversation: No' : 'Referral' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-7 py-2" style="min-width: 100px;">
                        <li><a class="dropdown-item py-2 text-success fw-bold" href="javascript:void(0)" onclick="openReferralModal('{{ $order->id }}', '{{ $order->order_id }}')">Yes</a></li>
                        <li><a class="dropdown-item py-2 text-danger fw-bold" href="javascript:void(0)" onclick="submitReferral('{{ $order->id }}', 'no')">No</a></li>
                    </ul>
                </div>
            @endif
        </td>

        <td class="text-center">
            @if($effectiveUser)
            @php
                $userAssignedLabels = optional($effectiveUser)->labels ?? collect();
                $userAssignedLabelIds = $userAssignedLabels->pluck('id')->all();
                $rawUserMobile = $effectiveUser->mobile_no ?: '';
                $rawUserEmail = $effectiveUser->email ?: '';
            @endphp
            <div class="d-flex align-items-center justify-content-center">
                <span class="fw-bold">{{ $effectiveUser->name }}</span>
            </div>

            @if(!empty($effectiveUser->client_review))
            <span class="duplicate-info-wrapper">

                <a
                    class=""
                    style="width:22px;height:22px;font-size:13px;line-height:1;">
                    <i class="fa fa-info-circle"></i>
                </a>

                <div class="duplicate-popup shadow">
                    <span>{{$effectiveUser->client_review}}</span>
                </div>

            </span>
            @endif

            @php
                $count = optional($effectiveUser)->orders_count ?? 0;
                if($count > 10) { 
                    $class = "badge-light-success"; 
                    $label = "Loyal Customer"; 
                } elseif($count >= 4) { 
                    $class = "badge-light-warning"; 
                    $label = "Retainer"; 
                } else { 
                    $class = "badge-light-info"; 
                    $label = "Beginner"; 
                } 
                $displayMobile = mask_phone_for_display($effectiveUser->countrycode, $effectiveUser->mobile_no);
                $displayEmail  = mask_email_for_display($effectiveUser->email);
            @endphp

            <div class="d-inline-flex align-items-center justify-content-center gap-1 my-1">
                <span class="badge {{ $class }} fw-bold fs-8" style="width: fit-content;">
                    {{ $label }}
                </span>
                @if(!empty($rawUserMobile))
                    <span class="badge badge-light-danger fs-7 fw-bold">{{ $displayMobile }}</span>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayMobile }}', 'Mobile number copied!');">
                        <i class="fa fa-clone fs-8 text-danger"></i>
                    </button>
                @endif
            </div><br>

            @if(!empty($effectiveUser->email))
                <div class="d-inline-flex align-items-center justify-content-center my-1">
                    <span class="fs-7 fw-bold text-break">{{ $displayEmail }}</span>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayEmail }}', 'Email copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                </div><br>
            @endif

            @php
                $orderRawWAPhone = preg_replace('/\D+/', '', (string)((optional($effectiveUser)->countrycode ?? '') . (optional($effectiveUser)->mobile_no ?? '')));
                $orderRawEmail = optional($effectiveUser)->email ?? '';
                $orderEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $orderRawEmail]));
                $orderWhatsAppUrl = !empty($orderRawWAPhone) ? route('whatsapp.chat', ['phone' => $orderRawWAPhone]) : route('whatsapp.chat');
            @endphp

            {{-- Direct Contact Actions: WhatsApp & Email --}}
            <div class="d-inline-flex align-items-center justify-content-center gap-2 my-1">
                <a href="{{ $orderWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-wa" title="WhatsApp: {{ $orderRawWAPhone ?: 'Open Chat' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                    </svg>
                </a>
                <a href="{{ $orderEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" title="Email: {{ $orderRawEmail ?: 'Open Emails' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>
                </a>
            </div>
            <br>

            {{-- User Assigned Labels Chips --}}
            <div class="d-flex flex-wrap justify-content-center gap-1 my-1" data-user-labels-badges="{{ $effectiveUser->id }}" @if(!empty($rawUserMobile)) data-user-labels-badges-phone="{{ preg_replace('/\D+/', '', $rawUserMobile) }}" @endif>
                @foreach($userAssignedLabels as $lbl)
                    <span class="badge" style="background:{{ $lbl->color }}1f; color:{{ $lbl->color }}; border:1px solid {{ $lbl->color }}4d; font-size: 10px; padding: 2px 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                        <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:{{ $lbl->color }};"></span>{{ $lbl->name }}
                    </span>
                @endforeach
            </div>

            @if($effectiveUser->groups)
            <span data-user-group-badges="{{ $effectiveUser->id }}">@foreach($effectiveUser->groups as $group)<span class="badge badge-light-primary fs-8 me-1">{{ $group->name }}</span>@endforeach</span>
            @endif

            <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                <button type="button" class="btn btn-icon btn-sm btn-light-success crm-btn-tag" title="Assign Labels" data-user-label-button="{{ $effectiveUser->id }}" data-labels='@json($userAssignedLabelIds)' onclick="openUserLabelModal({{ $effectiveUser->id }}, @js($effectiveUser->name), @js($displayMobile), @js($displayEmail), JSON.parse(this.dataset.labels || '[]'))">
                    <i class="fa fa-tag fs-7"></i>
                </button>

                <button type="button" class="btn btn-icon btn-sm btn-light-info" title="Add Review" onclick="openReviewModal({{ $effectiveUser->id }})">
                    <span class="fw-bold fs-6">B</span>
                </button>

                <button type="button" class="btn btn-icon btn-sm btn-light-primary" title="Assign Marks"
                    onclick="openMarksModal({{$order->id }}, '{{ $order->marks ?? '' }}')">
                    <span class="fw-bold fs-6">M</span>
                </button>

                @if($order->looking_for_refund == 1)
                    <span class="btn btn-icon btn-sm btn-light-danger" title="Looking For Refund">
                        <span class="fw-bold fs-6">R</span>
                    </span>
                @endif
            </div>

            @else
            <span class="badge badge-light-danger fs-7 fw-bold">User Was Deleted</span>
            @endif
        </td>

        <td class="text-center">
            @if(!empty($effectiveOrderDate) && strtotime($effectiveOrderDate))
                {{ \Carbon\Carbon::parse($effectiveOrderDate)->format('d M Y') }}
            @else
                <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
            @endif
        </td>

        <td class="text-center" style="cursor: pointer;" onclick="updateDeliveryDate({{ $order->id }})">
            @php
            $deadlineDate = null;

            if ($effectiveDeliveryDate) {
                $dateTimeString = $effectiveDeliveryDate;

                if ($effectiveDeliveryTime) {
                    $dateTimeString .= ' ' . $effectiveDeliveryTime;
                }

                try {
                    $deadlineDate = \Carbon\Carbon::parse($dateTimeString);
                } catch (\Exception $e) {
                    $deadlineDate = null;
                }
            }
            $isOverdue = $deadlineDate && $deadlineDate->isPast() && !in_array($order->projectstatus, ['Delivered', 'Completed']);
            @endphp

            @if($deadlineDate)
            <span style="{{ $isOverdue ? 'color: #F1416C; font-weight: bold;' : '' }}">
                {{ $deadlineDate->format('d M Y') }}
            </span>
            @else
            <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
            @endif

            @if($order->draftrequired == 'Y')
            <br>
            <span class="badge badge-light-success fs-8 fw-bold mt-1">
                Draft: {{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }})
            </span>
            @endif

            @if($deadlineDate)
            <div class="order-countdown-timer fw-bolder fs-8 mt-2"
                data-deadline="{{ $deadlineDate->toIso8601String() }}"
                data-status="{{ $order->projectstatus }}"
                data-updated="{{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->toIso8601String() : '' }}">
                <i class="fa fa-spinner fa-spin fs-8"></i> Loading...
            </div>
            @endif

            @if($order->f_delivery_date)
                <div class="mt-1">
                    <span class="badge badge-light-warning fs-8 fw-bold">
                        Feedback Date: {{ \Carbon\Carbon::parse($order->f_delivery_date)->format('d M Y') }}
                    </span>
                </div>
            @endif
        </td>

        <td class="text-center" style="width:50px">
            {!! $effectiveTitle ?: '<span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>' !!}
            <br>
            @if($effectiveSemester)
            Semester: ({{ $effectiveSemester }})
            @endif
            @if($effectiveChapter)
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $effectiveChapter }}</span>
            @endif
            @if($effectiveTech == '1' || $effectiveTech === 'on')
            <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
            @endif
            @if($effectiveModuleCode)
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $effectiveModuleCode }}</span>
            @endif
        </td>

        <td class="text-center" style="cursor: pointer;" onclick="status('{{ $order->id }}')">
            @switch($order->projectstatus)
            @case('Other')
            <span class="badge badge-light-primary fs-7 fw-bold" style="background:#44f2e4; color:black">{{ $order->projectstatus }}</span>
            @break
            @case('Pending')
            <span class="badge badge-light-warning fs-7 fw-bold" style="background:pink; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('In Progress')
            <span class="badge badge-light-info fs-7 fw-bold">{{ $order->projectstatus }}</span>
            @break
            @case('Hold Work')
            @case('Hold(writer query)')
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->projectstatus }}</span>
            @break
            @case('writer query')
            @case('Writer Query')
            <span class="badge badge-light-secondary fs-7 fw-bold" style="background:#e4e6ef; color:#181c32">
                {{ $order->projectstatus }}
            </span>
            @break
            @case('Completed')
            <span class="badge badge-light-warning fs-7 fw-bold" style="background:#eaea00; color:black">{{ $order->projectstatus }}</span>
            @break
            @case('Delivered')
            <span class="badge badge-light-success fs-7 fw-bold" style="background:green; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('Feedback')
            <span class="badge badge-light-primary fs-7 fw-bold" style="background:black; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('Feedback Delivered')
            <span class="badge badge-light-danger fs-7 fw-bold" style="background:black; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('Cancelled')
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->projectstatus }}</span>
            @break
            @case('Draft Ready')
            <span class="badge badge-light-primary fs-7 fw-bold" style="background:#eaea00; color:black">{{ $order->projectstatus }}</span>
            @break
            @case('Draft Delivered')
            <span class="badge badge-light-primary fs-7 fw-bold" style="background:green; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('Initiated')
            <span class="badge badge-light-primary fs-7 fw-bold" style="background:pink; color:white">{{ $order->projectstatus }}</span>
            @break
            @case('Advance Assignment')
            <span class="badge badge-light-danger fs-7 fw-bold" style="background:#44f2e4; color:black">{{ $order->projectstatus }}</span>
            @break

            @default
            <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
            @endswitch
            @php
            $statusCounts = $data['projectStatusCounts']->where('order_Id', $order->id)
            ->where('status', $order->projectstatus);
            @endphp
            @if ($statusCounts->isNotEmpty())
            @foreach ($statusCounts as $statusCount)
            <span class="badge badge-sm badge-circle badge-light-success">{{ $statusCount->count }}</span>
            @endforeach

            @if($order->feedback && $order->feedback->count() > 0)
            <button type="button"
                class="btn btn-icon btn-sm btn-light mt-2"
                title="View History"
                onclick='event.stopPropagation(); orderHistoryModel({!! json_encode($order->feedback) !!})'>
                <i class="fas fa-history"></i>
            </button>
            @endif
            @endif
            @if(optional($order->lead)->l_status)
                <br>
                <span class="badge badge-light-info fs-8 fw-bold mt-1">
                    Lead: {{ $order->lead->l_status }}
                </span>
            @endif
        </td>

        <td class="text-center">
            @switch($order->status_issue)
            @case('Issue Raised')
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->status_issue }}</span>
            @break
            @case('Client Discussion Done')
            <span class="badge badge-light-info fs-7 fw-bold">{{ $order->status_issue }}</span>
            @break
            @case('Writer discussion Done')
            <span class="badge badge-light-success fs-7 fw-bold">{{ $order->status_issue }}</span>
            @break
            @case('Work in progress')
            <span class="badge badge-light-warning fs-7 fw-bold">{{ $order->status_issue }}</span>
            @break
            @case('Case Resolved')
            <span class="badge badge-light-success fs-7 fw-bold">{{ $order->status_issue }}</span>
            @break

            @case('Issues Raised Again')
            <span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{ $order->status_issue }}</span>
            @break
            @case('Retention')
            <span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{ $order->status_issue }}</span>
            @break
            @default
            <span class="badge badge-light-warning fs-7 fw-bold">Not Available</span>
            @endswitch
        </td>

        {{-- <td class="text-center" style="width:50px">
            {!! $effectivePages ?: '<span class="badge badge-light-danger fs-7 fw-bold">N/A</span>' !!}
        </td> --}}

        <td class="text-center cursor-pointer" style="width:50px"
            onclick="openAdditionalHistory('{{ $order->order_id }}')">
            @php
                $extraWords = $order->additionals ? $order->additionals->sum('additional_word_count') : 0;
            @endphp

            @if($effectivePages)
                {{ $effectivePages }}@if($extraWords > 0)+{{ $extraWords }}@endif
            @elseif($extraWords > 0)
                {{ $extraWords }}
            @else
                <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
            @endif
        </td>

        {{-- <td class="text-center" style="width:50px">
            £{!! $effectiveAmount ?: '<span class="badge badge-light-danger fs-7 fw-bold">00.00</span>' !!}
        </td> --}}

        <td class="text-center cursor-pointer" style="width:50px"
            onclick="openAdditionalHistory('{{ $order->order_id }}')">
            @php
                $extraPrice = $order->additionals ? $order->additionals->sum('additional_price') : 0;
            @endphp

            @if(!empty($order->coupon_code))
                <div class="text-success small fw-bold">Coupon: {{ $order->coupon_code }}</div>
                <div class="text-danger small">-£{{ number_format((float)($order->coupon_discount_amount ?? 0), 2) }}</div>
            @endif

            @if($effectiveAmount)
                £{{ $effectiveAmount }}@if($extraPrice > 0)+{{ $extraPrice }}@endif
            @elseif($extraPrice > 0)
                £{{ $extraPrice }}
            @else
                <span class="badge badge-light-danger fs-7 fw-bold">£00.00</span>
            @endif
        </td>

        <td class="text-center" style="width:50px">
            £{!! $order->received_amount ?: '<span class="badge badge-light-danger fs-7 fw-bold">N/A</span>' !!}
        </td>

        <td class="text-center" style="width:50px">
            @php
                $extraPriceAmt = $order->additionals ? (float)$order->additionals->sum('additional_price') : 0;
                $basePriceAmt  = is_numeric($effectiveAmount) ? (float)$effectiveAmount : 0;
                $recvPriceAmt  = is_numeric($order->received_amount) ? (float)$order->received_amount : 0;
                $calcDueAmt    = max(0, ($basePriceAmt + $extraPriceAmt) - $recvPriceAmt);
            @endphp
            @if(is_numeric($effectiveAmount) || $extraPriceAmt > 0)
            £{{ $calcDueAmt }}
            @else
            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
            @endif
        </td>

        {{-- <td class="text-center">
        @if($order->writer_name)
            {{ $order->writer_name }}<br>
        <span style="background-color: #f8f5ff;" class="badge badge-light-info fs-7 fw-bold">{{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}</span>
        @else
        <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
        @endif
        </td> --}}
        <td class="text-center">
            @if($order->writer_name)
            @php
            // Is order ka current feedback aur writer total controller se preloaded hai
            $currentFeedback = $order->current_writer_feedback_points;
            $writerTotalPoints = (int) ($order->writer_total_points ?? 0);

            $isGoodWriter = $writerTotalPoints >= 6;
            @endphp

            @if($isGoodWriter)
            <div class="badge badge-light-success border border-success border-dashed fw-bolder fs-6 mb-1 p-2" title="Top Writer (Points: {{ $writerTotalPoints }})">
                <i class="fa fa-medal text-success me-1"></i> {{ $order->writer_name }}
            </div><br>
            @else
            <span class="text-dark fw-bold">{{ $order->writer_name }}</span><br>
            @endif
            <span style="background-color: #f8f5ff;" class="badge badge-light-info fs-7 fw-bold mb-2">
                {{ \Carbon\Carbon::parse($order->writer_deadline)->format('d M Y') }}
            </span><br>

            @if($order->writerstatus_date)
                <br>
                <span class="badge badge-light-warning fs-8 fw-bold mt-1">
                    Writer Query: {{ \Carbon\Carbon::parse($order->writerstatus_date)->format('d M Y h:i A') }}
                </span>
            @endif

            @php
                $orderRawEmail = optional($effectiveUser)->email ?? '';
                $writeEmailUrl = route('emails.index', array_filter(['account_id' => 3, 'search' => $orderRawEmail]));
            @endphp
            <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 fs-8"
                    onclick="openWriterFeedbackModal({{ $order->id }}, '{{ $currentFeedback ?? '' }}')">
                    <i class="fa fa-star fs-8"></i> Rate Writer
                </button>
                <a href="{{ $writeEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 26px !important; height: 26px !important;" title="Write Email: {{ $orderRawEmail ?: 'Open Write Email Channel' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>
                </a>
            </div>

            {{-- @else
            <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
            @endif --}}

            @else
                <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
                @if($order->writerstatus_date)
                    <br>
                    <span class="badge badge-light-warning fs-8 fw-bold mt-1">
                        W Q: {{ \Carbon\Carbon::parse($order->writerstatus_date)->format('d M Y h:i A') }}
                    </span>
                @endif
                @php
                    $orderRawEmail = optional($effectiveUser)->email ?? '';
                    $writeEmailUrl = route('emails.index', array_filter(['account_id' => 3, 'search' => $orderRawEmail]));
                @endphp
                <div class="mt-1">
                    <a href="{{ $writeEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" style="width: 26px !important; height: 26px !important;" title="Write Email: {{ $orderRawEmail ?: 'Open Write Email Channel' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                    </a>
                </div>
            @endif
        </td>

       @if($roleId == 1)
        <td style="min-width:220px;">
            <div class="border rounded p-3 bg-light">

                @php
                    $createdByName = $order->preloaded_creator_name
                        ?? ($order->lead?->creator ? $order->lead->creator->name . ' (ID: ' . $order->lead->creator->id . ')' : null)
                        ?? ($order->frontendLead?->creator ? $order->frontendLead->creator->name . ' (ID: ' . $order->frontendLead->creator->id . ')' : null)
                        ?? ($order->created_by ?? ($order->lead?->created_by ?? (auth()->user()?->name ? auth()->user()->name . ' (ID: ' . auth()->user()->id . ')' : 'Admin User')));
                @endphp

                <div class="mb-2">
                    <span class="fw-bold text-primary">Created By:</span>
                    <span>{{ $createdByName }}</span>
                </div>

                <div class="mb-2">
                    <span class="fw-bold text-success">Convert By:</span>
                    <span>{{ $order->l_converted_by ?: 'N/A' }}</span>
                </div>

                <div>
                    <span class="fw-bold text-danger">Failed By:</span>

                    @if($order->failed_by)
                        <span>{{ $order->failed_by }}</span></br>
                        <small class="text-muted">
                            {{ date('d M Y h:i A', strtotime($order->failed_at)) }}
                        </small>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>

            </div>
        </td>
        @endif

        <td class="text-center">
            @php
            // 1. Latest comment preloaded feedback relation se nikalna
            $latestComment = $order->feedback->first();

            // 2. User ki ID se Name preloaded relation se
            $commentUserName = $latestComment?->user?->name ?? 'Admin';
            @endphp

            @if($latestComment && !empty($latestComment->comment))
            <div style="background-color: #f5f8fa; border-radius: 8px; padding: 12px; margin-top: 5px; border: 1px solid #e4e6ef; text-align: left; min-width: 220px;">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bolder fs-6" style="color: #5e6278;">
                        {{ $commentUserName }}
                    </span>
                    <span class="text-muted fs-8 fw-bold">
                        @if(isset($latestComment->created_at))
                        {{ \Carbon\Carbon::parse($latestComment->created_at)->diffForHumans() }}
                        @endif
                    </span>
                </div>

                <div class="text-dark fs-7 mb-3" style="word-wrap: break-word; line-height: 1.4;">
                    {{ $latestComment->comment }}
                </div>

                <div class="d-flex align-items-center fw-bolder fs-7" style="color: #3e5cb9;">
                    <i class="fa fa-calendar-alt me-2" style="color: #8da4ef; font-size: 1.1rem;"></i>
                    @if(isset($latestComment->created_at))
                    {{ \Carbon\Carbon::parse($latestComment->created_at)->format('d M Y, h:i A') }}
                    @else
                    Date N/A
                    @endif
                </div>

            </div>
            @else
            <span class="badge badge-light-secondary text-muted fs-8">No Comments Found</span>
            @endif
        </td>
    </tr>

    <!-- Change Team Modal -->
<div class="modal fade" id="changeTeamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Change Team</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="modal_order_id">

                <div class="mb-3">
                    <label>Select Team</label>

                    <select class="form-select" id="modal_team_id">
                        <option value="">Select Team</option>

                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">
                                {{ $team->team_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        onclick="updateTeam()">
                    Update
                </button>
            </div>

        </div>
    </div>
</div>

    <style>
        .duplicate-info-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        /* hidden by default */
        .duplicate-popup {
            position: absolute;
            top: 28px;
            left: 0;
            min-width: 170px;
            background: #fff;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            z-index: 99999;
            display: none;
        }

        /* hover on full wrapper */
        .duplicate-info-wrapper:hover .duplicate-popup {
            display: block;
        }

        .btn.btn-light-danger.btn-referral-no:hover,
        .btn.btn-light-danger.btn-referral-no:focus,
        .btn.btn-light-danger.btn-referral-no:active,
        .btn.btn-light-danger.btn-referral-no.show {
            background-color: #ffe6e8 !important;
            color: #f1416c !important;
        }
    </style>
    <script>

    function openTeamModal(orderId, teamId)
    {
        $('#modal_order_id').val(orderId);
        $('#modal_team_id').val(teamId);
    }

    function updateTeam()
    {
        let orderId = $('#modal_order_id').val();
        let teamId  = $('#modal_team_id').val();

        $.ajax({
            url: "{{ route('orders.change.team') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                team_id: teamId
            },

            success: function(response)
            {
                $('#changeTeamModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Team Updated Successfully',
                    timer: 1500,
                    showConfirmButton: false
                });

                location.reload();
            }
        });
    }

</script>

    {{-- <script>
	function status(orderId) {
		let data = <?php echo json_encode($data['Status']); ?>;
		let statusValues = Object.values(data).map(item => item.status);
		Swal.fire({
			title: 'Change Status',
			text: 'Select Status',
			icon: 'info',
			input: 'select',
			inputOptions: statusValues,
			inputPlaceholder: 'Select status',
			showCancelButton: true,
			confirmButtonText: 'OK',
			cancelButtonText: 'Cancel',
			preConfirm: (selectedStatus) => {
				if (!selectedStatus) {
					Swal.fire({
						title: 'Error!',
						text: 'Status cannot be empty!',
						icon: 'error'
					});
					return false; // Prevent further execution
				}
				
				let updateData = {
					orderId: orderId,
					status: selectedStatus,
					_token: '{{ csrf_token() }}'
    };
    $.ajax({
    type: 'POST',
    url: 'update_status',
    data: updateData,
    success: function(response) {
    if (response.warning) {
    Swal.fire({
    icon: 'warning',
    title: 'Warning',
    text: response.warning
    });
    } else {
    Swal.fire({
    icon: 'success',
    title: 'Success',
    text: 'Status updated successfully'
    }).then(() => {
    // Reload the page after showing the success message
    location.reload();
    });
    }
    },
    error: function(xhr, status, error) {
    console.log(updateData);
    }
    });
    }
    });
    }
    </script> --}}
