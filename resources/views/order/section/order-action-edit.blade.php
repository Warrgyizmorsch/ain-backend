<div id="kt_drawer_chat{{ $order->order_id }}" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle{{ $order->order_id }}" data-kt-drawer-close="#kt_drawer_chat_close">
    <!--begin::Messenger-->
    <div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
        <!--begin::Card header-->
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <!--begin::Title-->
            <div class="card-title">
                <!--begin::User-->
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{$order->order_id}}</a>
                    <!--begin::Info-->
                    <div class="mb-0 lh-1">
                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                        <span class="fs-7 fw-bold text-muted">Action Activity</span>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::User-->
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_close">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body" id="kt_drawer_chat_messenger_body">
    <div class="scroll-y me-n5 pe-5" data-kt-element="messages{{ $order->order_id }}" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
        
        <div class="timeline-label">
            @foreach($order->feedback->sortByDesc('created_at') as $feedback)
                @if($feedback->action_comment != '')
                    <div class="timeline-item d-flex align-items-center mb-7">
                        <div class="timeline-line border-start-2 border-gray-300 ms-2 me-4" style="height: 100%; position: absolute; left: 8px;"></div>
                        
                        <div class="timeline-icon me-4" style="z-index: 1;">
                            <i class="fa fa-genderless text-{{ $feedback->created_by == Auth::user()->id ? 'primary' : 'success' }} fs-1"></i>
                        </div>

                        <div class="timeline-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="pe-3">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bolder">
                                        {{ $feedback->user->name ?? 'User' }} {{ $feedback->created_by == Auth::user()->id ? '(You)' : '' }}
                                    </a>
                                    <div class="text-muted fw-bold fs-7 mt-1">{{ $feedback->action_comment }}</div>
                                    
                                    <div class="mt-2">
                                        @php
                                            $badgeClass = 'badge-light-primary';
                                            $status = $feedback->status;
                                            if($status == 'Issue Raised' || $status == 'Issue Raised Again' || $status == 'Retention') $badgeClass = 'badge-light-danger';
                                            elseif($status == 'Work in progress') $badgeClass = 'badge-light-warning';
                                            elseif($status == 'Case Resolved' || $status == 'Writer discussion Done') $badgeClass = 'badge-light-success';
                                            elseif($status == 'Client Discussion Done') $badgeClass = 'badge-light-info';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} fw-bold fs-8">{{ $status }}</span>
                                    </div>
                                </div>
                                <span class="text-muted fw-bold fs-8 text-end min-w-80px">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        </div>
</div>
        @if(Auth::user()->role_id != 4)        
        {{-- <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
            <textarea class="form-control form-control-flush mb-3" rows="1" id="message{{$order->id}}"  placeholder="Type a message"></textarea>
            <input type="hidden" name="order_id" value="{{$order->id}}" id='order_id{{$order->id}}'>
            <div class="d-flex flex-stack text-end">
               
                <button class="btn btn-primary" id="sendButton{{$order->id}}" type="button" data-kt-element="send">Send</button>
            </div>
        </div> --}}
        <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
            <div class="mb-3">
                <select name="status" id="drawer_status{{$order->id}}" class="form-control form-control-sm mb-2">
                    <option value="">Select status (Optional)...</option>
                    @if(Auth::user()->role_id == 4)
                        <option {{ $order->status_issue == "Client Discussion Done" ? 'selected' : '' }} value="Client Discussion Done">Client Discussion Done</option>
                        <option {{ $order->status_issue == "Issue Raised Again" ? 'selected' : '' }} value="Issue Raised Again">Issue Raised Again</option>
                        <option {{ $order->status_issue == "Retention" ? 'selected' : '' }} value="Retention">Retention</option>
                    @else
                        <option {{ $order->status_issue == "Issue Raised" ? 'selected' : '' }} value="Issue Raised">Issue Raised</option>
                        <option {{ $order->status_issue == "Client Discussion Done" ? 'selected' : '' }} value="Client Discussion Done">Client Discussion Done</option>
                        <option {{ $order->status_issue == "Writer discussion Done" ? 'selected' : '' }} value="Writer discussion Done">Writer discussion Done</option>
                        <option {{ $order->status_issue == "Work in progress" ? 'selected' : '' }} value="Work in progress">Work in progress</option>
                        <option {{ $order->status_issue == "Case Resolved" ? 'selected' : '' }} value="Case Resolved">Case Resolved</option>
                        <option {{ $order->status_issue == "Issue Raised Again" ? 'selected' : '' }} value="Issue Raised Again">Issue Raised Again</option>
                        <option {{ $order->status_issue == "Retention" ? 'selected' : '' }} value="Retention">Retention</option>
                    @endif
                </select>
            </div>

            <textarea class="form-control form-control-flush mb-3" rows="1" id="message{{$order->id}}" placeholder="Type a message"></textarea>
            <input type="hidden" name="order_id" value="{{$order->id}}" id='order_id{{$order->id}}'>
            
            <div class="d-flex flex-stack text-end">
                <button class="btn btn-primary" id="sendButton{{$order->id}}" type="button" data-kt-element="send">Send</button>
            </div>
        </div>
        @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- <script>
    $(document).ready(function() {
        // Assuming your send button has the ID 'sendButton'
        $('#sendButton{{$order->id}}').click(function() {
            var message = $('#message{{$order->id}}').val();
            var order_id = $('#order_id{{$order->id}}').val();

            // alert(message)

            $.ajax({
                type: 'POST',
                url: '{{ route("send.feedback") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'message': message,
                    'order_id': order_id,
                },
                success: function(response) {
                    var messageContainer = $('[data-kt-element="messages{{ $order->order_id }}"]');
                    var statusBadge = getStatusBadge(response);

                    var newMessage = `
                        <div class="d-flex justify-content-end mb-10">
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <span class="text-muted fs-7 mb-1">${response.created_at}</span>
                                        <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                        ${statusBadge}
                                    </div>
                                    <div class="symbol symbol-35px symbol-circle">
                                        <img alt="Pic" src="${response.avatar ? response.avatar : 'assets/media/avatars/blank.png'}" />
                                    </div>
                                </div>
                                <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">${response.message}</div>
                            </div>
                        </div>
                    `;

                    // Use prepend to add the new message at the beginning
                    messageContainer.prepend(newMessage);

                    // Clear the input field
                    $('#message{{$order->id}}').val('');
                },
            });
        });

        // Function to get status badge based on response status
        function getStatusBadge(response) {
            var status = response.f_status;
            var badgeClass = '';
            var badgeStyle = '';

            switch (status) {
                case 'Issue Raised':
                    badgeClass = 'badge-light-danger';
                    break;
                case 'Investigation Inprogress':
                    badgeClass = 'badge-light-info';
                    break;
                case "Client's Discussion":
                    badgeClass = 'badge-light-success';
                    break;
                case 'Writer Discussion':
                    badgeClass = 'badge-light-warning';
                    break;
                case 'Case Resolved':
                    badgeClass = 'badge-light-success';
                    break;
                case 'Issue Raised Again':
                    badgeClass = 'badge-light-danger';
                    badgeStyle = 'style="background:red; color:white"';
                    break;
                case 'Retention':
                    badgeClass = 'badge-light-danger';
                    badgeStyle = 'style="background:red; color:white"';
                    break;
                default:
                    badgeClass = 'badge-light-primary';
            }

            return `<span class="badge fs-7 fw-bold ${badgeClass}" ${badgeStyle}>${status}</span>`;
        }
    });
</script> --}}
<script>
$(document).ready(function () {

    $('#sendButton{{$order->id}}').click(function () {

        let btn      = $(this);
        let message  = $('#message{{$order->id}}').val().trim();
        let order_id = $('#order_id{{$order->id}}').val();

        /* STATUS VALUE FIX */
        let status   = $('#drawer_status{{$order->id}} option:selected').val();

        if(message == ''){
            alert('Message required');
            return false;
        }

        btn.prop('disabled', true).text('Sending...');

        $.ajax({
            type: "POST",
            url: "{{ route('send.feedback') }}",

            data: {
                _token: "{{ csrf_token() }}",
                message: message,
                order_id: order_id,
                status: status
            },

            success: function(response){
    let messageContainer = $('[data-kt-element="messages{{ $order->order_id }}"] .timeline-label'); // container changed to timeline-label
    let statusBadge = getStatusBadge(response.f_status);

    let html = `
    <div class="timeline-item d-flex align-items-center mb-7">
        <div class="timeline-icon me-4" style="z-index: 1;">
            <i class="fa fa-genderless text-primary fs-1"></i>
        </div>
        <div class="timeline-content flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="pe-3">
                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bolder">You</a>
                    <div class="text-muted fw-bold fs-7 mt-1">${response.message}</div>
                    <div class="mt-2">${statusBadge}</div>
                </div>
                <span class="text-muted fw-bold fs-8 text-end min-w-80px">${response.created_at}</span>
            </div>
        </div>
    </div>`;

    messageContainer.prepend(html);
    $('#message{{$order->id}}').val('');
    $('#drawer_status{{$order->id}}').val(response.f_status);

    btn.prop('disabled', false).removeClass('btn-primary').addClass('btn-success').text('Sent ✓');

    setTimeout(function(){
        btn.removeClass('btn-success').addClass('btn-primary').text('Update & Send');
    }, 1200);
},

            error:function(xhr){
                btn.prop('disabled', false).text('Update & Send');
                alert('Error');
                console.log(xhr.responseText);
            }

        });

    });


    function getStatusBadge(status){

        let badgeClass = '';
        let badgeStyle = '';

        switch(status){

            case 'Issue Raised':
                badgeClass='badge-light-danger';
            break;

            case 'Client Discussion Done':
                badgeClass='badge-light-info';
            break;

            case 'Writer discussion Done':
                badgeClass='badge-light-success';
            break;

            case 'Work in progress':
                badgeClass='badge-light-warning';
            break;

            case 'Case Resolved':
                badgeClass='badge-light-success';
            break;

            case 'Issue Raised Again':
                badgeClass='badge-light-danger';
                badgeStyle='style="background:red;color:white"';
            break;

            case 'Retention':
                badgeClass='badge-light-danger';
                badgeStyle='style="background:red;color:white"';
            break;

            default:
                badgeClass='badge-light-primary';
        }

        return `<span class="badge fs-7 fw-bold ${badgeClass}" ${badgeStyle}>${status}</span>`;
    }

});
</script>


</div>
<!-- Your existing HTML code -->





<style>
    /* Fix first div */
    #kt_drawer_chat_messenger_header {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #fff; /* Set background color as needed */
    }

    /* Fix last div */
    #kt_drawer_chat_messenger_footer {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background-color: #fff; /* Set background color as needed */
    }

    .timeline-label {
        position: relative;
    }
    .timeline-item {
        position: relative;
    }
    /* Timeline ki vertical line */
    .timeline-label::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #eff2f5;
        z-index: 0;
    }
    .timeline-icon i {
        background-color: #ffffff; /* Dot ke piche white background taaki line kate hue dikhe */
        padding: 2px;
    }
</style>
