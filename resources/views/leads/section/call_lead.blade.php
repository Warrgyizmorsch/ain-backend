@extends('layouts.app')

@section('content')

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Call Back
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-8">
            <div class="col-xl-4">
                <div class="card card-xxl-stretch mb-xl-8">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Order</span>
                            <span class="text-muted fw-bold fs-7">Deatils</span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="row mb-12">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container ">
                                        <h3>{{$lead->order_id}}({{$lead->user_name}})
                                        </h3>
                                        <br>
                                        <p style="font-size:large">{{$lead->title}}</p>
                                        @if( $lead->tech == 'on' )
                                        <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
                                        @endif
                                        <br> <br>
                                        @if($lead->service == 'First Class Work')
                                        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
                                        @endif
                                        <br> <br>

                                        @if ($lead->resit == 'on')
                                        <span class="badge badge-light-danger fs-7 fw-bold">Resit</span>
                                        @endif
                                        <br> <br>
                                    </div>
                                </div>
                                <div class="row">
                                <a href="#" data-bs-toggle="modal"data-bs-target="#kt_modal_create_appaa{{ $lead->order_id }}"id="kt_toolbar_primary_button"class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                fill="black"></path>
                                            <path
                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                </a>
                                </div>
                                
                            </div>
                                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Call Back Detail</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">
                                    </a>
                                    <div class="mb-0 lh-1 text-start">
                                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                        <span class="fs-7 fw-bold text-muted">Call Back</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="kt_drawer_chat_messenger_body">
                            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true"
                                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                                data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
                                data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
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
                                                <a href="#"
                                                    class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">
                                                    @if($call->user)
                                                    {{$call->user->name}}
                                                    @endif
                                                </a>
                                                <span
                                                    class="text-muted fs-7 mb-1">{{$call->created_at->diffForHumans()}}</span>
                                            </div>
                                        </div>
                                        <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start"
                                            data-kt-element="message-text">{{ $call->description}}</div>
                                    </div>
                                </div>
                                @else
                                <div class="d-flex justify-content-end mb-10">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-3">
                                                <span
                                                    class="text-muted fs-7 mb-1">{{$call->created_at->diffForHumans()}}</span>
                                                <a href="#"
                                                    class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle">

                                                @if($call->user->photo)
                                                <img src="{{ asset(Auth::user()->photo) }}" />
                                                @else
                                                <img alt="Pic" src="assets/media/avatars/blank.png" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end"
                                            data-kt-element="message-text">{{ $call->description}}</div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
                                    <textarea class="form-control form-control-flush mb-3" rows="1"
                                        id="description{{$lead->id}}" placeholder="Type a message"></textarea>
                                    <input type="hidden" name="lead_id" value="{{$lead->id}}" id='lead_id{{$lead->id}}'>
                                    <div class="d-flex flex-stack text-end">
                                        <button class="btn btn-primary" id="sendButton{{$lead->id}}" type="button"
                                            data-kt-element="send">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        // Assuming your send button has the ID 'sendButton'
        $('#sendButton{{$lead->id}}').click(function () {
            var description = $('#description{{$lead->id}}').val();
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
                    var messageContainer = $('[data-kt-element="messages"]');
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
@include('leads.section.edit-lead')

<style>
    /* Fix first div */
    #kt_drawer_chat_messenger_header {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #fff;
        /* Set background color as needed */
    }

    /* Fix last div */
    #kt_drawer_chat_messenger_footer {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background-color: #fff;
        /* Set background color as needed */
    }
</style>


@endsection