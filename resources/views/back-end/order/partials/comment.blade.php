<div style="overflow: hidden;" id="kt_drawer_chat{{ $order->id }}" class="bg-body" data-kt-drawer="true"
    data-kt-drawer-name="chat{{ $order->id }}" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'95%', 'md':'65%', 'lg':'37%'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_drawer_chat_toggle{{ $order->id }}"
    data-kt-drawer-close="#kt_drawer_chat_close{{ $order->id }}">

    <!-- Drawer Content -->
    <div class="card w-100 rounded-0 border-0 h-100 d-flex flex-column">
        <!-- Header -->
        <div class="card-header bg-primary text-white" id="kt_drawer_chat_messenger_header" style="border-radius: 0px;">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <span class="fs-4 fw-bold text-white">Order: {{ $order->order_id }}</span>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <small class="text-white-50">Feedback Thread</small>
                        <span id="drawerTicketNumber{{ $order->id }}"
                            class="badge bg-white text-danger {{ $order->feedback_ticket ? '' : 'd-none' }}">
                            {{ $order->feedback_ticket ?: '' }}
                        </span>
                        @if((int) $order->is_fail === 1)
                            <span class="badge bg-danger text-white">Failed Order</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-close-white" id="kt_drawer_chat_close{{ $order->id }}">
                    <i class="bi bi-x-lg fs-2 text-white"></i>
                </div>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="card-body p-4 flex-grow-1 chat-scroll">
            <ul class="list-unstyled activity-feed-1" id="messageContainer{{ $order->id }}">

                {{-- Lead Chat --}}
                @if($order->lead && $order->lead->call && $order->lead->call->count())
                <li class="chat-item lead-chat fw-bold text-primary mb-6 text-center" style="background:#e7f1ff; color:#0d6efd; padding:6px; border-radius:6px;">
                    Lead Chat
                </li>
                @foreach($order->lead->call->sortBy('updated_at') as $chat)
                @if(!empty($chat->description))

                @php $isCurrentUser = $chat->created_by == Auth::id(); @endphp

                <li class="feed-item {{ $isCurrentUser ? 'feed-item-success' : 'feed-item-primary' }} chat-item lead-chat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $isCurrentUser ? 'You' : ($chat->user->name ?? 'User') }}</strong>
                            <span class="badge bg-primary ms-2" style="font-size:10px;">
                                Lead
                            </span>
                        </div>
                        <small>{{ optional($chat->updated_at)->format('d M Y, h:i A') }}</small>
                    </div>

                    <div class="text-muted mt-1">
                        {{ $chat->description }}
                    </div>

                    <!-- <small class="text-info">
                        Order: {{ $order->order_id }}
                    </small> -->
                </li>

                @endif
                @endforeach
                @endif


                {{-- Order Chat --}}
                @if($order->feedback && $order->feedback->count())

                <li class="chat-heading order-heading chat-item order-chat fw-bold text-success mb-6  text-center" style="background:#e8f8f0; color:#198754; padding:6px; border-radius:6px;">
                    Order Chat
                </li>
                @foreach($order->feedback->sortBy('created_at') as $feedback)
                @if($feedback->comment != '' && $feedback->status != 'Referred')

                @php $isCurrentUser = $feedback->created_by == Auth::id(); @endphp

                <li class="feed-item {{ $isCurrentUser ? 'feed-item-success' : 'feed-item-primary' }} chat-item order-chat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $isCurrentUser ? 'You' : $feedback->user->name }}</strong>
                            <!-- <span class="badge bg-primary ms-2" style="font-size:10px;">
                                Order
                            </span> -->
                            @if($feedback->status )
                            <span class="badge bg-info ms-1" style="font-size:10px;">
                                {{ $feedback->status }}
                            </span>
                            @endif
                        </div>
                        <small>{{ $feedback->created_at->format('d M Y, h:i A') }}</small>
                    </div>

                    <div class="text-muted mt-1">
                        {{ $feedback->comment }}
                    </div>

                </li>

                @endif
                @endforeach
                @endif

                @if($order->feedback && $order->feedback->count())
                <li class="chat-heading chat-item ticket-chat fw-bold mb-6  text-center"
                    style="background:#fdecea; color:#dc3545; padding:6px; border-radius:6px;">
                    Ticket Chat
                </li>
                @foreach($order->feedback->sortBy('created_at') as $feedback)
                @if($feedback->action_comment != '')

                @php $isCurrentUser = $feedback->created_by == Auth::id(); @endphp

                <li class="feed-item {{ $isCurrentUser ? 'feed-item-success' : 'feed-item-primary' }} chat-item ticket-chat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $isCurrentUser ? 'You' : $feedback->user->name }}</strong>
                            <!-- <span class="badge bg-primary ms-2" style="font-size:10px;">
                                Ticket
                            </span> -->
                            @if($feedback->status )
                            <span class="badge bg-info ms-1" style="font-size:10px;">
                                {{ $feedback->status }}
                            </span>
                            @endif
                        </div>
                        <small>{{ $feedback->created_at->format('d M Y, h:i A') }}</small>
                    </div>

                    <div class="text-muted mt-1">
                        {{ $feedback->action_comment }}
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-light-dark fs-9">
                            Order status: {{ $feedback->order_status ?? 'N/A' }}
                        </span>
                    </div>

                </li>

                @endif
                @endforeach
                @endif

                {{-- Followup Chat --}}
                @if($order->followupComments && $order->followupComments->count())

                <li class="chat-heading chat-item followup-chat fw-bold mb-6 text-center"
                    style="background:#fff3cd; color:#856404; padding:6px; border-radius:6px;">
                    Followup Chat
                </li>

                @foreach($order->followupComments->sortBy('created_at') as $follow)
                @if($follow->comment != '')

                @php $isCurrentUser = $follow->created_by == Auth::id(); @endphp

                <li class="feed-item {{ $isCurrentUser ? 'feed-item-success' : 'feed-item-primary' }} chat-item followup-chat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $isCurrentUser ? 'You' : ($follow->user->name ?? 'User') }}</strong>

                            @if($follow->status)
                            <span class="badge bg-warning ms-1" style="font-size:10px;">
                                {{ $follow->status }}
                            </span>
                            @endif
                        </div>

                        <small>{{ $follow->created_at->format('d M Y, h:i A') }}</small>
                    </div>

                    <div class="text-muted mt-1">
                        {{ $follow->comment }}
                    </div>
                </li>

                @endif
                @endforeach
                @endif

                {{-- Referral Chat --}}
                @if($order->feedback && $order->feedback->whereIn('status', ['Referred', 'Not Referred', 'Conversation: Yes', 'Conversation: No'])->count())
                <li class="chat-heading referral-heading chat-item referral-chat fw-bold mb-6 text-center" style="background:#f3e8ff; color:#6b21a8; padding:6px; border-radius:6px;">
                    Referral Chat
                </li>
                @foreach($order->feedback->whereIn('status', ['Referred', 'Not Referred', 'Conversation: Yes', 'Conversation: No'])->sortBy('created_at') as $feedback)
                @if($feedback->comment != '')
                @php $isCurrentUser = $feedback->created_by == Auth::id(); @endphp
                <li class="feed-item {{ $isCurrentUser ? 'feed-item-success' : 'feed-item-primary' }} chat-item referral-chat">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $isCurrentUser ? 'You' : ($feedback->user->name ?? 'User') }}</strong>
                            @php $conversationYes = in_array($feedback->status, ['Referred', 'Conversation: Yes'], true); @endphp
                            <span class="badge ms-1 text-white" style="font-size:10px; background-color: {{ $conversationYes ? '#198754' : '#dc3545' }};">
                                Conversation: {{ $conversationYes ? 'Yes' : 'No' }}
                            </span>
                            <span class="badge ms-1 text-white" style="font-size:10px; background-color: {{ $feedback->client_will_refer === 'yes' ? '#198754' : ($feedback->client_will_refer === 'no' ? '#dc3545' : '#6c757d') }};">
                                Will Refer: {{ $feedback->client_will_refer === 'yes' ? 'Yes' : ($feedback->client_will_refer === 'no' ? 'No' : 'N/A') }}
                            </span>
                        </div>
                        <small>{{ $feedback->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <div class="text-muted mt-1">
                        {{ $feedback->comment }}
                    </div>
                </li>
                @endif
                @endforeach
                @endif

            </ul>
        </div>

        <div class="mb-2 d-flex gap-2 justify-content-center">
            <button class="btn btn-sm btn-light-primary chat-filter active" data-chat-filter="all" onclick="filterChat('all', {{ $order->id }}, this)">All</button>
            <button class="btn btn-sm btn-light-warning chat-filter" data-chat-filter="lead" onclick="filterChat('lead', {{ $order->id }}, this)">Lead</button>
            <button class="btn btn-sm btn-light-success chat-filter" data-chat-filter="order" onclick="filterChat('order', {{ $order->id }}, this)">Order</button>
            <button class="btn btn-sm btn-light-danger chat-filter" data-chat-filter="ticket" onclick="filterChat('ticket', {{ $order->id }}, this)">
                Ticket
            </button>
            <button class="btn btn-sm btn-light-warning chat-filter" data-chat-filter="followup" onclick="filterChat('followup', {{ $order->id }}, this)">
                Followup
            </button>
            <button class="btn btn-sm chat-filter referral-filter" data-chat-filter="referral" onclick="filterChat('referral', {{ $order->id }}, this)">
                Referral
            </button>
        </div>

        <!-- Footer Input -->
        <div class="card-footer border-top p-3">
            <div id="referralControls{{ $order->id }}" class="mb-2 d-none">
                <label class="fs-8 fw-bold text-gray-700 mb-1">Will the client refer someone?</label>
                <select id="clientWillRefer{{ $order->id }}" class="form-select form-select-sm">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div class="d-flex">
                <input type="text" id="description{{ $order->id }}" class="form-control me-2"
                    placeholder="Type your message...">
                <button class="btn btn-primary" onclick="sendFeedback({{ $order->id }})">Send</button>
            </div>
            <small id="referralCommentHint{{ $order->id }}" class="text-muted d-none mt-1">Referral comment is optional.</small>
        </div>
    </div>
</div>

<style>
    .activity-feed-1 {
        position: relative;
        padding-left: 30px;
    }

    /* vertical line */
    .activity-feed-1::before {
        content: "";
        position: absolute;
        top: 0;
        left: 10px;
        width: 2px;
        height: 100%;
        border-left: 2px dashed #d1d5db;
    }

    /* each item */
    .feed-item {
        position: relative;
        padding-bottom: 25px;
    }

    /* dot */
    .feed-item::before {
        content: "";
        position: absolute;
        left: -24px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 45%;
        background: #ccc;
        border: 2px solid #fff;
    }

    /* colors */
    .feed-item-primary::before {
        background: #3b82f6;
    }

    .feed-item-success::before {
        background: #22c55e;
    }

    .feed-item-danger::before {
        background: #ef4444;
    }

    .chat-filter {
        border: 2px solid transparent !important;
        transition: all .18s ease;
    }

    .chat-filter.active {
        border-color: currentColor !important;
        box-shadow: 0 4px 12px rgba(63, 66, 84, .22) !important;
        font-weight: 800;
        transform: translateY(-2px);
    }

    .chat-filter.active::before {
        content: "\2713";
        margin-right: 5px;
        font-weight: 900;
    }

    .referral-filter {
        color: #6b21a8 !important;
        background-color: #f3e8ff !important;
    }

    .referral-filter.active {
        color: #fff !important;
        background-color: #6b21a8 !important;
        border-color: #6b21a8 !important;
    }

    /* text truncate */
    .text-truncate-1-line {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .text-truncate-2-line {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Chat Bubble Styles -->
<style>
    .bg-primary {
        background-color: var(--primary);
    }

    /* .chat-box {
        padding: 1rem;
        overflow-y: auto;
        max-height: 400px;
        display: flex;
        flex-direction: column;
    } */
    .chat-box {
        padding: 1rem;
    }

    .chat-scroll {
        overflow-y: auto;
        height: 100%;
    }

    .message-bubble {
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 18px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        font-size: 0.95rem;
        line-height: 1.4;
        position: relative;
    }

    .message-bubble.sent {
        background-color: #dcf8c6;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .message-bubble.received {
        background-color: #f9f9f9;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }

    .message-meta {
        font-size: 0.7rem;
        color: #666;
        margin-top: 5px;
        text-align: right;
    }

    .message-text {
        word-wrap: break-word;
    }
</style>

<!-- Feedback Script -->
<script>
   function filterChat(type, orderId, button) {
    let container = $('#messageContainer' + orderId);
    container.data('active-chat', type);
    $(button).siblings('.chat-filter').removeClass('active shadow-sm');
    $(button).addClass('active shadow-sm');
    $(button).siblings('.chat-filter').attr('aria-pressed', 'false');
    $(button).attr('aria-pressed', 'true');
    $('#referralControls' + orderId).toggleClass('d-none', type !== 'referral');
    $('#referralCommentHint' + orderId).toggleClass('d-none', type !== 'referral');
    $('#description' + orderId).attr(
        'placeholder',
        type === 'referral' ? 'Referral comment (optional)...' : 'Type your message...'
    );

    // sab hide
    container.find('.chat-item').css('display', 'none');

    if (type === 'all') {
        container.find('.chat-item').css('display', 'list-item');

    } else if (type === 'lead') {
        container.find('.lead-chat').css('display', 'list-item');

    } else if (type === 'order') {
        container.find('.order-chat').css('display', 'list-item');

    } else if (type === 'ticket') {
        container.find('.ticket-chat').css('display', 'list-item');

    } else if (type === 'followup') {
        container.find('.followup-chat').css('display', 'list-item');
    } else if (type === 'referral') {
        container.find('.referral-chat').css('display', 'list-item');
    }
}

    function sendFeedback(orderId) {
        var message = $('#description' + orderId).val();
        var container = $('#messageContainer' + orderId);
        var activeChat = container.data('active-chat') || 'all';

        if (activeChat !== 'referral' && message.trim() === '') return;

        if (activeChat === 'referral') {
            var clientWillRefer = $('#clientWillRefer' + orderId).val();

            $.ajax({
                type: 'POST',
                url: @json(route('orders.save.referral')),
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    status: 'yes',
                    client_will_refer: clientWillRefer,
                    comment: message
                },
                success: function(response) {
                    var isYes = response.referral_status === 'yes';
                    var badgeColor = isYes ? '#198754' : '#dc3545';
                    var newMsg = `
        <li class="feed-item feed-item-success chat-item referral-chat">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>You</strong>
                    <span class="badge ms-1 text-white" style="font-size:10px; background-color:${badgeColor}">
                        Conversation: Yes
                    </span>
                    <span class="badge ms-1 text-white" style="font-size:10px; background-color:${response.client_will_refer === 'yes' ? '#198754' : '#dc3545'}">
                        Will Refer: ${response.client_will_refer_label}
                    </span>
                </div>
                <small>${response.created_at}</small>
            </div>
            <div class="text-muted mt-1">${response.comment}</div>
        </li>`;

                    container.append(newMsg);
                    $('#description' + orderId).val('');
                    container.scrollTop(container[0].scrollHeight);
                },
                error: function(err) {
                    console.error('Referral send failed:', err);
                }
            });

            return;
        }

        if (activeChat === 'ticket') {
            $.ajax({
                type: 'POST',
                url: @json(route('send.feedback')),
                data: {
                    _token: '{{ csrf_token() }}',
                    message: message,
                    order_id: orderId
                },
                success: function(response) {
                    var newMsg = `
        <li class="feed-item feed-item-success chat-item ticket-chat">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>You</strong>
                    <span class="badge bg-danger ms-1" style="font-size:10px;">Ticket</span>
                    <span class="badge bg-dark ms-1" style="font-size:10px;">Order: ${response.order_status}</span>
                </div>
                <small>${response.created_at}</small>
            </div>
            <div class="text-muted mt-1">${response.message}</div>
        </li>`;

                    container.append(newMsg);
                    $('#drawerTicketNumber' + orderId)
                        .removeClass('d-none')
                        .text(response.ticket);
                    $('#orderTicketNumber' + orderId)
                        .removeClass('d-none')
                        .text(response.ticket);
                    $('#description' + orderId).val('');
                    container.scrollTop(container[0].scrollHeight);
                },
                error: function(err) {
                    console.error('Ticket send failed:', err);
                }
            });

            return;
        }

        $.ajax({
            type: 'POST',
            url: 'comment/' + orderId,
            data: {
                _token: '{{ csrf_token() }}',
                comment: message,
                order_id: orderId
            },
            success: function(response) {
                var newMsg = `
        <li class="feed-item feed-item-success chat-item order-chat">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>You</strong>
                    <span class="badge bg-primary ms-1" style="font-size:10px;">
                        Order
                    </span>
                </div>
                <small>${response.created_at}</small>
            </div>

            <div class="text-muted mt-1">
                ${response.message}
            </div>
        </li>
    `;

                container.append(newMsg);

                $('#description' + orderId).val('');
                container.scrollTop(container[0].scrollHeight);
            },
            error: function(err) {
                console.error('Send failed:', err);
            }
        });
    }
</script>
