@php
    $lead = $followup->lead;
    $leadUser = $lead?->user;
    $isSuperAdmin = auth()->check() && (int) auth()->user()->role_id === 1;

    $followUpUserId = $leadUser ? $leadUser->id : ($lead?->emp_id ?? null);
    $rawName = $leadUser?->name ?: ($lead?->user_name ?: 'Customer');
    $rawEmail = $leadUser?->email ?: ($lead?->email ?: '');
    $rawMobile = $leadUser?->mobile_no ?: ($lead?->mobile ?: '');
    $rawCC = $leadUser?->countrycode ?: ($lead?->countrycode ?: '');
    $cleanCC = preg_replace('/\D+/', '', (string)$rawCC);

    // Only treat as real email if contains @ and is not identical to name
    $hasValidEmail = !empty($rawEmail) && str_contains($rawEmail, '@') && (strtolower(trim($rawEmail)) !== strtolower(trim($rawName)));
    $maskedEmail = $isSuperAdmin ? $rawEmail : ($hasValidEmail ? mask_email_for_display($rawEmail) : '');
    $maskedMobile = $isSuperAdmin ? $rawMobile : ($rawMobile ? mask_mobile_only($cleanCC, $rawMobile) : '');

    $rawWAPhone = preg_replace('/\D+/', '', (string)($cleanCC . $rawMobile));
    $orderCode = $lead?->order_id ?: ('LEAD-' . $followup->lead_id);
    $orderSearchTerm = !empty($lead?->order_id) ? $lead->order_id : ($hasValidEmail ? $rawEmail : '');
    $orderEmailUrl = route('emails.index', array_filter(['account_id' => 2, 'search' => $orderSearchTerm]));
    $orderWhatsAppUrl = !empty($rawWAPhone) ? route('whatsapp.chat', ['phone' => $rawWAPhone]) : route('whatsapp.chat');

    $isDone = ($followup->status === 'done');
    $scheduledDate = \Carbon\Carbon::parse($followup->followup_date);
    $diffDays = \Carbon\Carbon::today()->diffInDays($scheduledDate, false);
    $isToday = (\Carbon\Carbon::today()->toDateString() === $scheduledDate->toDateString());
    $isOverdue = (!$isDone && $scheduledDate->toDateString() < \Carbon\Carbon::today()->toDateString());
@endphp

<tr id="row_followup_{{ $followup->id }}" class="{{ $isDone ? 'table-light' : ($isOverdue ? 'bg-hover-light-danger' : ($isToday ? 'bg-hover-light-warning' : 'bg-hover-light-primary')) }}">
    {{-- 1. SR --}}
    <td class="text-center fw-bold text-muted py-2">{{ $loopIndex }}</td>

    {{-- 2. Order Code with Copy Button --}}
    <td class="text-center py-2">
        <div class="d-inline-flex align-items-center justify-content-center">
            @if($lead)
                <a href="{{ url('/lead/edit/' . $lead->id) }}" target="_blank" class="fw-bold text-dark text-hover-primary fs-7">
                    {{ $orderCode }}
                </a>
            @else
                <span class="fw-bold text-dark fs-7">{{ $orderCode }}</span>
            @endif
            @if(!empty($orderCode))
                <button type="button" 
                        class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" 
                        style="width: 18px; height: 18px;" 
                        title="Copy Order Code" 
                        onclick="event.stopPropagation(); crmCopyToClipboard('{{ $orderCode }}', 'Order code copied!');">
                    <i class="fa fa-clone fs-8 text-muted"></i>
                </button>
            @endif
        </div>
        @if($lead && !empty($lead->lead_status))
            <span class="badge badge-light-{{ $lead->lead_status == 'Hot' ? 'danger' : ($lead->lead_status == 'Warm' ? 'warning' : 'primary') }} fs-9 py-0 px-2 mt-0.5 d-block">
                {{ $lead->lead_status }}
            </span>
        @endif
    </td>

    {{-- 3. User Details (Name, User ID, Email, Mobile Masking, Action Icons) --}}
    <td class="py-2">
        <div class="d-flex flex-column gap-1">
            {{-- Name + Copy --}}
            @if(!empty($rawName))
                <div class="d-flex align-items-center">
                    <span class="fw-bolder text-dark fs-6">{{ $rawName }}</span>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" 
                            style="width: 18px; height: 18px;" 
                            title="Copy Name" 
                            onclick="event.stopPropagation(); crmCopyToClipboard('{{ addslashes($rawName) }}', 'Customer name copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                </div>
            @endif

            {{-- User ID + Copy --}}
            @if(!empty($followUpUserId))
                <div class="d-inline-flex align-items-center gap-1">
                    <span class="badge badge-light-dark fs-8 fw-bold">ID: {{ $followUpUserId }}</span>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-active-light-dark p-0 flex-shrink-0" 
                            style="width: 18px; height: 18px;" 
                            title="Copy User ID" 
                            onclick="event.stopPropagation(); crmCopyToClipboard('{{ $followUpUserId }}', 'User ID copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                </div>
            @endif

            {{-- Email + Copy: Render only if it's a real email address --}}
            @if($hasValidEmail && !empty($maskedEmail))
                <div class="d-flex align-items-center">
                    <span class="text-muted fs-8 text-break">{{ $maskedEmail }}</span>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" 
                            style="width: 18px; height: 18px;" 
                            title="Copy Email" 
                            onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedEmail }}', 'Email copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                </div>
            @endif

            {{-- Mobile + Masking + Copy --}}
            @if(!empty($maskedMobile))
                <div class="d-flex align-items-center flex-wrap gap-1 mt-0.5">
                    @if(!empty($cleanCC))
                        <span class="badge badge-light-primary fs-8 fw-bold py-0.5 px-1.5">+{{ $cleanCC }}</span>
                    @endif
                    <span class="badge badge-light-danger fs-8 fw-bold py-0.5 px-1.5">{{ $maskedMobile }}</span>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" 
                            style="width: 18px; height: 18px;" 
                            title="Copy Mobile" 
                            onclick="event.stopPropagation(); crmCopyToClipboard('{{ $maskedMobile }}', 'Mobile number copied!');">
                        <i class="fa fa-clone fs-8 text-danger"></i>
                    </button>
                </div>
            @endif

            {{-- Contact Action Icons: Twilio Call, WhatsApp, Email --}}
            <div class="d-inline-flex align-items-center gap-1 mt-1">
                @if(!empty($rawMobile))
                    <a href="#" 
                       onclick="event.preventDefault(); event.stopPropagation(); initiateCustomerCall('{{ $cleanCC . $rawMobile }}', '{{ addslashes($rawName ?: 'Customer') }}');"
                       class="btn btn-icon btn-sm shadow-sm"
                       style="width: 22px; height: 22px; min-width: 22px; border-radius: 5px; background-color: #25D366; color: #ffffff; display: inline-flex; align-items: center; justify-content: center;"
                       title="Call: {{ $cleanCC . $rawMobile }}">
                        <i class="fa fa-phone text-white" style="font-size: 10px;"></i>
                    </a>
                @endif

                <a href="{{ $orderWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm" style="width: 22px !important; height: 22px !important; min-width: 22px !important;" title="WhatsApp: {{ $rawWAPhone ?: 'Open Chat' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                        <path fill="#25D366" d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                    </svg>
                </a>

                @if($hasValidEmail)
                    <a href="{{ $orderEmailUrl }}" target="_blank" class="btn btn-icon btn-sm" style="width: 22px !important; height: 22px !important; min-width: 22px !important;" title="Email: {{ $rawEmail ?: 'Open Emails' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 16 16">
                            <path fill="#009ef7" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </td>

    {{-- 4. Project / Price --}}
    <td class="py-2">
        @if($lead)
            <span class="text-dark fw-bold fs-7 d-block text-truncate" style="max-width: 170px;" title="{{ $lead->project_title }}">
                {{ $lead->project_title ?: 'N/A' }}
            </span>
            <div class="d-flex align-items-center gap-2 mt-1">
                @if($lead->price)
                    <span class="badge badge-light-success fs-9 fw-bolder">£{{ $lead->price }}</span>
                @endif
                @if($lead->deadline)
                    <span class="text-muted fs-9" title="Deadline"><i class="fa fa-clock-o text-muted"></i> {{ \Carbon\Carbon::parse($lead->deadline)->format('d M') }}</span>
                @endif
            </div>
        @else
            <span class="text-muted fs-7">-</span>
        @endif
    </td>

    {{-- 5. Follow-up Note (With Author + Date & Time) --}}
    <td class="py-2">
        <div class="p-2 rounded bg-white border border-gray-200 shadow-xs" style="max-width: 320px;">
            <div class="d-flex align-items-center justify-content-between mb-1 pb-1 border-bottom border-gray-100">
                <span class="fw-bolder fs-8 text-primary">
                    <i class="fa fa-user-circle text-primary me-1"></i>{{ $followup->user->name ?? 'Staff' }}
                </span>
                <span class="text-muted fs-9">
                    <i class="fa fa-clock-o text-muted me-1"></i>{{ \Carbon\Carbon::parse($followup->created_at)->format('d M, h:i A') }}
                </span>
            </div>
            <div class="fs-8 text-dark lh-base" style="white-space: pre-wrap; word-break: break-word; max-height: 70px; overflow-y: auto;">
                {{ $followup->message }}
            </div>
        </div>
    </td>

    {{-- 6. Scheduled Next Follow-up Date --}}
    <td class="text-center py-2">
        @if($isToday)
            <span class="badge badge-light-warning text-dark fw-bolder fs-7 px-2.5 py-1.5 d-inline-block border border-warning border-dashed">
                <i class="fa fa-calendar-check-o text-warning me-1"></i>{{ $scheduledDate->format('d M, Y') }}
            </span>
        @elseif($isOverdue)
            <span class="badge badge-light-danger text-danger fw-bolder fs-7 px-2.5 py-1.5 d-inline-block border border-danger border-dashed">
                <i class="fa fa-calendar-times-o text-danger me-1"></i>{{ $scheduledDate->format('d M, Y') }}
            </span>
        @else
            <span class="badge badge-light-primary text-primary fw-bolder fs-7 px-2.5 py-1.5 d-inline-block border border-primary border-dashed">
                <i class="fa fa-calendar-o text-primary me-1"></i>{{ $scheduledDate->format('d M, Y') }}
            </span>
        @endif
        <span class="text-muted fs-9 d-block mt-0.5">{{ $scheduledDate->format('l') }}</span>
    </td>

    {{-- 7. Status --}}
    <td class="text-center status-col py-2">
        @if($isDone)
            <span class="badge badge-light-success fs-8 fw-bold px-2 py-1 border border-success border-dashed">
                <i class="fa fa-check-circle me-1 text-success"></i>Done
            </span>
            @if($followup->doneByUser)
                <span class="d-block text-muted fs-9 mt-1">by {{ $followup->doneByUser->name }}</span>
            @endif
        @elseif($isOverdue)
            <span class="badge badge-light-danger text-danger border border-danger border-dashed fs-8 fw-bolder px-2 py-1">
                <i class="fa fa-exclamation-triangle text-danger me-1"></i>{{ abs($diffDays) }} day{{ abs($diffDays) === 1 ? '' : 's' }} overdue
            </span>
        @elseif($isToday)
            <span class="badge badge-light-warning text-dark border border-warning border-dashed fs-8 fw-bolder px-2 py-1">
                <i class="fa fa-clock-o text-warning me-1"></i>Due Today
            </span>
        @else
            <span class="badge badge-light-primary text-primary border border-primary border-dashed fs-8 fw-bold px-2 py-1">
                <i class="fa fa-clock-o text-primary me-1"></i>Upcoming
            </span>
        @endif
    </td>

    {{-- 8. Action: Check (Done) button & History Drawer button --}}
    <td class="text-center py-2" style="vertical-align: middle;">
        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 10px; vertical-align: middle;">
            <!-- Done Check Button -->
            @if(!$isDone)
                <button type="button" 
                        class="btn btn-sm btn-icon btn-light-success shadow-xs action-done-btn" 
                        style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; border-radius: 6px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(80, 205, 137, 0.3); box-sizing: border-box;"
                        onclick="handleDoneClick({{ $followup->id }}, this)" 
                        title="Mark Follow-up as Completed (Done)">
                    <i class="fa fa-check text-success" style="font-size: 13px;"></i>
                </button>
            @else
                <button type="button" 
                        class="btn btn-sm btn-icon btn-light-success shadow-xs disabled" 
                        style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; border-radius: 6px; padding: 0; display: inline-flex; align-items: center; justify-content: center; cursor: default; opacity: 1; border: 1px solid rgba(80, 205, 137, 0.3); box-sizing: border-box;"
                        title="Completed">
                    <i class="fa fa-check text-success" style="font-size: 13px;"></i>
                </button>
            @endif

            <!-- History / Drawer Button (Opens side drawer for this lead) -->
            <button type="button" 
                    class="btn btn-sm btn-icon btn-light-primary shadow-xs" 
                    style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; border-radius: 6px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(0, 158, 247, 0.3); box-sizing: border-box;"
                    onclick="openLeadFollowupDrawer({{ $followup->lead_id }})" 
                    title="Open Follow-up History & Schedule Next Note">
                <i class="fa fa-history text-primary" style="font-size: 13px;"></i>
            </button>
        </div>
    </td>
</tr>
