 @php
$rowStyle = "";
// Hum check kar rahe hain ki kya is order ka CLIENT fail ho chuka hai (is_fail == 1 in users table)
if (optional($order->user)->is_fail == 1) {

// Hum user ke 'failed_at' column se check karenge (Jo client table mein hai)
// Agar aapke pass users table mein failed_at nahi hai, toh hum order table wala use kar sakte hain
$failedDate = $order->user->failed_at ? \Carbon\Carbon::parse($order->user->failed_at) : null;

// Agar client fail hua hai aur abhi 1 saal nahi hua, toh RED box dikhao
if ($failedDate && $failedDate->diffInDays(now()) <= 365) {
    $rowStyle="background-color: #ffeaea !important; color: #b50000 !important; border: 2px solid #ff0000 !important;" ;
    } else {
    // 1 saal baad normal blue text (jaisa aapne manga tha)
    $rowStyle="color: blue;" ;
    }
    }
    // Baaki normal feedback clients ke liye
    elseif (optional($order->user)->feedback_issue == 1) {
    $rowStyle = "color: blue;";
    }
    @endphp

    @php

    $rowStyle = "";
    // user ka koi bhi failed order hai kya
    $hasFailedOrder = \App\Models\Order::where('uid', optional($order->user)->id)
        ->where('is_fail', 1)
        ->exists();

    if ($hasFailedOrder || $order->is_fail == 1) {
        $rowStyle = "
            background-color: #ffeaea !important;
            color: #b50000 !important;
            border: 2px solid #ff0000 !important;
        ";
    } elseif (optional($order->user)->feedback_issue == 1) {
        $rowStyle = "color: blue;";
    }

    @endphp
    {{-- <tr id="lead-{{ $order->id }}" @if( optional($order->user)->is_fail == 1 || optional($order->user)->feedback_issue == 1) style="color:blue" @endif id="order_{{ $order->id }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}" > --}}
    <tr id="lead-{{ $order->id }}">
        <td class="text-center" style="padding-right: 0px;">
            {{ $index + 1 }}

            <!-- Rendering All Modals and Drawer of Action Buttons here  -->
            @include('back-end.order.partials.fail')
        </td>


        <td class="text-center" style="position: sticky; left: 0; background: white; z-index: 2;">
            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">

                <!-- Edit Order Button -->
                <a target="_blank" style="background-color: #1e1e2d;" href="orders/edit/{{ $order->id }}" class="btn btn-icon btn-sm" title="Edit Order">
                    <i style="color: white;" class="fa fa-edit"></i>
                </a>

                <!-- Chat / Comment Button -->
                <button onclick="loadCommentDrawer({{ $order->id }})" class="btn btn-icon btn-secondary btn-sm" title="Open Chat">
                    <i class="fa fa-comment-alt"></i>
                </button>


                <!-- Button to Open Unified Payment Page -->
                <!-- Button to Open Unified Payment Page -->
                <a href="{{ route('orders.payment.form', ['orderId' => $order->id]) }}"
                    target="_blank"
                    class="btn btn-icon btn-success btn-sm position-relative"
                    title="Add/Edit Payment">

                    <i class="fa fa-money"></i>

                    {{-- Check if any payment is missing payee_name or company_accounts --}}
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

                @if(auth()->user()->role_id == 1)
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger" title="Looking For Refund" onclick="markLookingForRefund({{ $order->id }})">
                        <span class="fw-bold fs-6">R</span>
                    </button>

                    <button type="button" class="btn btn-icon btn-sm btn-light-primary" title="Add Additional" onclick="openAdditionalModal({{ $order->id }})">
                        <span class="fw-bold fs-6">A</span>
                    </button>
                @endif

                @if(auth()->user()->role_id == 9)
                <a onclick="CallToWriter('{{ $order->id }}')" class="btn btn-icon btn-bg-warning btn-active-color-dark btn-color-white btn-sm me-1 download-btn">
                    <span class="svg-icon svg-icon-3">
                        <i class="fa fa-phone fa-lg"></i>
                    </span>
                </a>
                @endif
            </div>

        </td>
        <td class="text-center">
            @php
            // 1. Latest comment nikalna
            $latestComment = \Illuminate\Support\Facades\DB::table('feddbacksheet')
            ->where('order_id', $order->id)
            ->orderBy('id', 'desc')
            ->first();

            // 2. User ki ID se Name nikalna
            $commentUserName = 'Admin'; // Default naam
            if ($latestComment && !empty($latestComment->created_by)) {
            // 'users' table me ID match karke name fetch kar rahe hain
            $user = \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $latestComment->created_by)
            ->first();

            if ($user) {
            $commentUserName = $user->name;
            }
            }
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
        {{-- <td class="text-center">
        <span>{{ $order->order_id }}</span><br>
        @if($order->team?->team_name)
        <span class="badge badge-light-primary fs-7 fw-bold mb-1">{{ $order->team->team_name }}</span><br>
        @endif
        @if($order->feedback_ticket)
        <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->feedback_ticket }}</span><br>
        @endif

        @if ($order->resit == 'on')
        <span class="badge badge-light-danger fs-7 fw-bold">Resit</span><br>
        @endif

        @if($order->services == 'First Class Work')
        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span><br>
        @endif

        @if($order->is_fail == 1)
        <span class="badge badge-light-danger fs-7 fw-bold">Fail Order<br>{{ \Carbon\Carbon::parse($order->failed_at)->format('d M Y H:i:s A') }}</span>
        @endif
        </td> --}}

        <td class="text-center" id="order-cell-{{ $order->id }}" style="{{ $rowStyle }}" class="{{ ($order->is_read == 1) ? 'bold-row' : '' }}">
           @if(optional($order->lead)->frontendorder == '1')
                <span class="badge badge-light-primary fs-7 fw-bold">
                    {{ $order->order_id }}
                </span><br>
            @else
                <span class="fw-bold text-gray-800">
                    {{ $order->order_id }}
                </span><br>
            @endif
            {{-- @if($order->team?->team_name)
            <span class="badge badge-light-primary fs-7 fw-bold mb-1">{{ $order->team->team_name }}</span><br>
            @endif --}}
            @if($order->team?->team_name)

            @if(auth()->user()->role_id == 1)
                <span 
                    class="badge badge-light-primary fs-7 fw-bold mb-1 cursor-pointer"
                    data-bs-toggle="modal"
                    data-bs-target="#changeTeamModal"
                    onclick="openTeamModal('{{ $order->id }}', '{{ $order->team_id }}')"
                >
                    {{ $order->team->team_name }}
                </span>
            @else
                <span class="badge badge-light-primary fs-7 fw-bold mb-1">
                    {{ $order->team->team_name }}
                </span>
            @endif
                <br>
            @endif

            @if($order->marks)
            <span class="fs-7 fw-bold">Marks:</span>{{ $order->marks }}<br>
            @endif

            @if($order->semester)
            <span class="badge badge-light-warning fs-7 fw-bold mb-1">{{ $order->semester }}</span><br>
            @endif

             @if($order->offer)
             <span class="badge badge-light-success fs-7 fw-bold mb-1">{{$order->offer}}</span><br>
            @endif

            @if($order->feedback_ticket)
            <span class="badge badge-light-danger fs-7 fw-bold mb-1">{{ $order->feedback_ticket }}</span><br>
            @endif

            @if ($order->resit == 'on')
            <span class="badge badge-light-danger fs-7 fw-bold mb-1">Resit</span><br>
            @endif

            @if($order->services == 'First Class Work')
            <span class="badge badge-light-info fs-7 fw-bold mb-1">First Class Work</span><br>
            @endif

            @if($order->is_fail == 1)
            <span class="badge badge-light-danger fs-7 fw-bold mt-1">
                Fail Order<br>{{ \Carbon\Carbon::parse($order->failed_at)->format('d M Y H:i:s A') }}
            </span>
            @endif
        </td>

        {{-- <td class="text-center">
        @if($order->user)
            {{ $order->user->name }}<br>
        <span class="badge badge-light-danger fs-7 fw-bold">+{{ $order->user->countrycode }} {{ $order->user->mobile_no }}</span><br>
        <span class="fs-7 fw-bold">{{ $order->user->email }}</span>
        @else
        <span class="badge badge-light-danger fs-7 fw-bold">User Was Deleted</span>
        @endif
        </td> --}}
        <td class="text-center">
            @if($order->user)
            {{ $order->user->name }}

            @if(!empty($order->user->client_review))
            <span class="duplicate-info-wrapper">

                <a
                    class=""
                    style="width:22px;height:22px;font-size:13px;line-height:1;">
                    <i class="fa fa-info-circle"></i>
                </a>

                <div class="duplicate-popup shadow">
                    <span>{{$order->user->client_review}}</span>
                </div>

            </span>
            @endif

            <br>
            @php
                $count = \App\Models\Order::where('uid', optional($order->user)->id)->count();
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

            <span class="badge {{ $class }} fw-bold fs-8" style="width: fit-content;">
                {{ $label }}
            </span>
            <span class="badge badge-light-danger fs-7 fw-bold">+{{ $order->user->countrycode }} {{ $order->user->mobile_no }}</span><br>
            <span class="fs-7 fw-bold">{{ $order->user->email }}</span><br>

            <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                <button type="button" class="btn btn-icon btn-sm btn-light-info" title="Add Review"
                    onclick="openReviewModal({{ $order->user->id }}, '{{ addslashes($order->user->client_review ?? '') }}')">
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
            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
        </td>

        {{-- <td class="text-center" style="cursor: pointer;" onclick="updateDeliveryDate({{ $order->id }})">
        @if($order->delivery_date)
        {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
        @else
        <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
        @endif
        @if($order->draftrequired == 'Y')
        <span class="badge badge-light-success fs-7 fw-bold">
            {{ \Carbon\Carbon::parse($order->draft_date)->format('d M Y') }} ({{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }})
        </span>
        @endif
        </td> --}}

        <td class="text-center" style="cursor: pointer;" onclick="updateDeliveryDate({{ $order->id }})">
            @php
            $deadlineDate = null;

            if ($order->delivery_date) {
            $dateTimeString = $order->delivery_date;

            if ($order->delivery_time) {
            $dateTimeString .= ' ' . $order->delivery_time;
            }

            $deadlineDate = \Carbon\Carbon::parse($dateTimeString);
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
            {!! $order->title ?: '<span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>' !!}
            <br>
            @if($order->semester)
            Semester: ({{ $order->semester }})
            @endif
            @if($order->chapter)
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->chapter }}</span>
            @endif
            @if($order->tech == '1')
            <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
            @endif
            @if($order->module_code)
            <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->module_code }}</span>
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
            {!! $order->pages ?: '<span class="badge badge-light-danger fs-7 fw-bold">N/A</span>' !!}
        </td> --}}

        <td class="text-center cursor-pointer" style="width:50px"
            onclick="openAdditionalHistory('{{ $order->order_id }}')">
            @php
                $extraWords = $order->additionals->sum('additional_word_count');
            @endphp

            @if($order->pages)
                {{ $order->pages }}@if($extraWords > 0)+{{ $extraWords }}@endif
            @else
                <span class="badge badge-light-danger fs-7 fw-bold">N/A</span>
            @endif
        </td>

        {{-- <td class="text-center" style="width:50px">
            £{!! $order->amount ?: '<span class="badge badge-light-danger fs-7 fw-bold">00.00</span>' !!}
        </td> --}}

        <td class="text-center cursor-pointer" style="width:50px"
            onclick="openAdditionalHistory('{{ $order->order_id }}')">
            @php
                $extraPrice = $order->additionals->sum('additional_price');
            @endphp

            @if($order->amount)
                £{{ $order->amount }}@if($extraPrice > 0)+{{ $extraPrice }}@endif
            @else
                <span class="badge badge-light-danger fs-7 fw-bold">£00.00</span>
            @endif
        </td>

        <td class="text-center" style="width:50px">
            £{!! $order->received_amount ?: '<span class="badge badge-light-danger fs-7 fw-bold">N/A</span>' !!}
        </td>

        <td class="text-center" style="width:50px">
            @if(is_numeric($order->amount) && is_numeric($order->received_amount))
            £{{ $order->amount - $order->received_amount }}
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
            // Is order ka current feedback nikalna
            $currentFeedback = \Illuminate\Support\Facades\DB::table('writer_feedbacks')
            ->where('order_id', $order->id)
            ->value('points');

            // Writer ki total rating nikalna (Highlight karne ke liye)
            $writerIdForSum = \Illuminate\Support\Facades\DB::table('writer_list')
            ->where('writer_name', $order->writer_name)
            ->value('id');

            $writerTotalPoints = 0;
            if ($writerIdForSum) {
            $writerTotalPoints = \Illuminate\Support\Facades\DB::table('writer_feedbacks')
            ->where('writer_id', $writerIdForSum)
            ->sum('points');
            }

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

            <button type="button" class="btn btn-sm btn-light-primary mt-1 py-1 px-3 fs-8"
                onclick="openWriterFeedbackModal({{ $order->id }}, '{{ $currentFeedback ?? '' }}')">
                <i class="fa fa-star fs-8"></i> Rate Writer
            </button>

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
            @endif
        </td>

       @if(auth()->user()->role_id == 1)
        <td class="text-center">
            Convert By: {{ $order->l_converted_by ?: 'N/A' }}<br>
            @if($order->failed_by)
            Failed By: {{ $order->failed_by }} at {{ $order->failed_at }}
            @else
            Failed By: N/A
            @endif
           
        </td>
        @endif
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
