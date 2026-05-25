@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Comment
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-12">
            <div class="col-xl-12">
                <div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
                    <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                        <div class="card-title">
                            <div class="d-flex justify-content-center flex-column me-3">
                                <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">Feedback Comment</a>
                                <div class="mb-0 lh-1">
                                    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                    <span class="fs-7 fw-bold text-muted"> {{$order->order_id}}</span>
                                </div>
                            </div>
                            @if($order->status_issue == 'Issue Raised')
                                <span class="badge badge-light-danger fs-7 fw-bold ">{{$order->status_issue}}</span>
                                @elseif($order->status_issue == 'Investigation Inprogress')
                                <span class="badge badge-light-info fs-7 fw-bold" >{{$order->status_issue}}</span>
                                @elseif($order->status_issue == "Client's Discussion")
                                <span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
                                @elseif($order->status_issue == 'Writer Discussion')
                                <span class="badge badge-light-warning fs-7 fw-bold" >{{$order->status_issue}}</span>
                                @elseif($order->status_issue == 'Case Resolved')
                                <span class="badge badge-light-success fs-7 fw-bold" >{{$order->status_issue}}</span>
                                @elseif($order->status_issue == 'Issue Raised Again')
                                <span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
                                @elseif($order->status_issue == 'Retention')
                                <span class="badge badge-light-danger fs-7 fw-bold" style="background:red; color:white">{{$order->status_issue}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body" id="kt_drawer_chat_messenger_body">
                        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
                        @foreach($order->feedback->sortByDesc('created_at') as $feedback)
                            @if( $feedback->comment != '')
                            @if($feedback->created_by != Auth::user()->id)
                            <div class="d-flex justify-content-start mb-10">
                                <div class="d-flex flex-column align-items-start">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="symbol symbol-35px symbol-circle">
                                            @if($feedback->user->photo)
                                            <img alt="Pic" src="{{ asset($feedback->user->photo) }}" />
                                            @else
                                            <img alt="Pic" src="assets/media/avatars/blank.png" />
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">{{$feedback->user->name}}</a>
                                            <span class="text-muted fs-7 mb-1">{{$feedback->created_at->diffForHumans()}}</span> <br>
                                            
                                        </div>
                                    </div>
                                    <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">{{ $feedback->comment}}</div>
                                    
                                </div>
                            </div>
                            @else
                            <div class="d-flex justify-content-end mb-10">
                                <div class="d-flex flex-column align-items-end">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3">
                                            <span class="text-muted fs-7 mb-1">{{$feedback->created_at->diffForHumans()}}</span>
                                            
                                        </div>
                                        <div class="symbol symbol-35px symbol-circle">
                                            <img alt="Pic" src="{{ asset($feedback->user->photo) }}" />
                                        </div>
                                    </div>
                                    <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">{{ $feedback->comment}}</div>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
                        <textarea class="form-control form-control-flush mb-3" rows="1" id="description{{$order->id}}" placeholder="Type a message"></textarea>
                        <input type="hidden" name="order_id" value="{{$order->id}}" id="{{$order->id}}">
                        <div class="d-flex flex-stack text-end">
                            <button class="btn btn-primary" id="sendButton{{$order->id}}" type="button" data-kt-element="send" onclick="sendFeedback({{ $order->id }})">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>


        <script>
    function sendFeedback(orderId) {
        var message = $('#description' + orderId).val();

        // Perform an AJAX request to insert feedback
        $.ajax({
            type: 'POST',
            url: 'comment/'+orderId,
            data: {
                '_token': '{{ csrf_token() }}',
                'comment': message,
                'order_id': orderId,
            },
            success: function (response) {
                var messageContainer = $('[data-kt-element="messages"]');

                // Create a new message HTML based on the response
                var newMessage = `
                    <div class="d-flex justify-content-end mb-10">
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <span class="text-muted fs-7 mb-1">${response.created_at}</span>
                                    <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                </div>
                                <div class="symbol symbol-35px symbol-circle">
                                    <img alt="Pic" src="${response.avatar ? response.avatar : 'assets/media/avatars/blank.png'}" />
                                </div>
                            </div>
                            <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">${response.message}</div>
                        </div>
                    </div>
                `;

                // Prepend the new message to the message container
                messageContainer.prepend(newMessage);

                // Clear the input field after sending feedback
                $('#description' + orderId).val('');

                // Check if there is a success message in the response
                if (response.status === 'success') {
                    // Perform additional actions upon successful feedback submission
                    console.log(orderId);
                    // Example: Redirect after successful feedback submission
                    // window.location.href = '{{ url('/feedback') }}';
                }
            },
            error: function (error) {
                // Handle errors if needed
                console.error('Error:', error);
            },
        });
    }
</script>






    @endsection