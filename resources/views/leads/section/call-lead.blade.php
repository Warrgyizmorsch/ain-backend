<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle{{ $lead->id }}" data-kt-drawer-close="#kt_drawer_chat_close">
    <div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{$lead->order_id}}</a>
                    <div class="mb-0 lh-1">
                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                        <span class="fs-7 fw-bold text-muted">Active</span>
                    </div>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_close">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body" id="kt_drawer_chat_messenger_body">
            <div class="scroll-y me-n5 pe-5" data-kt-element="messages{{$lead->id}}" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
                 @foreach($lead->call->sortByDesc('created_at') as $call)
                    @if($call->created_by != Auth::user()->id)
                    <div class="d-flex justify-content-start mb-10">
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex align-items-center mb-2">
                                <div class="symbol symbol-35px symbol-circle">
                                @if($call->user)
                                    @if($call->user->photo)
                                    <img alt="Pic" src="{{ asset($call->user->photo) }}" />
                                    @else
                                    <img alt="Pic" src="assets/media/avatars/blank.png" />
                                    @endif
                                @endif
                                </div>
                                <div class="ms-3">
                                    <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">
                                @if($call->user)
                                    {{$call->user->name}}
                                @endif                                    
                                    </a>
                                    <span class="text-muted fs-7 mb-1">{{$call->created_at->diffForHumans()}}</span>
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">{{ $call->description}}</div>
                        </div>
                    </div>
                    @else
                    <div class="d-flex justify-content-end mb-10">
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <span class="text-muted fs-7 mb-1">{{$call->created_at->diffForHumans()}}</span>
                                    <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                </div>
                                <div class="symbol symbol-35px symbol-circle">
                                
                                    @if($call->user->photo)
                                    <img  src="{{ asset(Auth::user()->photo) }}" />
                                    @else
                                    <img alt="Pic" src="assets/media/avatars/blank.png" />
                                    @endif
                            
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">{{ $call->description}}</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        
            <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
        <textarea class="form-control form-control-flush mb-3" rows="1" id="description"  placeholder="Type a message"></textarea>
        <input type="hidden" name="lead_id" value="{{$lead->id}}" id='lead_id{{$lead->id}}'>
        <div class="d-flex flex-stack text-end">
            <button class="btn btn-primary" id="sendButton{{$lead->id}}" type="button" data-kt-element="send">Send</button>
        </div>
    </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Assuming your send button has the ID 'sendButton'
        $('#sendButton{{$lead->id}}').click(function () {
            var description = $('#description').val();
            var id = $('#lead_id{{$lead->id}}').val();

            $.ajax({
                type: 'POST',
                url: '{{ route("send-data") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'description': description,
                    'id': id,
                },
                success: function (response) {
                    var messageContainer = $('[data-kt-element="messages{{$lead->id}}"]');
                    var newMessage = `
                        <div class="d-flex justify-content-end mb-10">
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <span class="text-muted fs-7 mb-1">${response.created_at}</span>
                                        <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                    </div>
                                    <div class="symbol symbol-35px symbol-circle">
                                        <img alt="Pic" src="${response.avatar}" />
                                    </div>
                                </div>
                                <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">${response.message}</div>
                            </div>
                        </div>
                    `;

                    // Use prepend to add the new message at the beginning
                    messageContainer.prepend(newMessage);

                    // Clear the input field
                    $('#description{{$lead->id}}').val('');
                },
            });
        });
    });
</script>
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
</style>
