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
                                        <h3>{{$order->order_id}}({{$order->user->name}})

                                        </h3>
                                        <br> <p style="font-size:large">{{$order->title}}</p>

                                        <table class="table table-bordered">

                                            <tbody>
                                                <tr>
                                                    <td>Order Amount</td>
                                                    <td>{{$order->amount}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Received Amount</td>
                                                    <td>{{ $order->received_amount }} £</td>
                                                </tr>
                                                <tr>
                                                    <td>Due Amount</td>
                                                    <td>{{ $order->amount - $order->received_amount }} £</td>
                                                </tr>
                                            </tbody>
                                        </table>


                                        @if($order->projectstatus == 'Other')
                                        <span class="badge badge-light-primary fs-7 fw-bold "
                                            style="background:#44f2e4; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Pending')
                                        <span class="badge badge-light-warning fs-7 fw-bold"
                                            style="background:pink; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'In Progress')
                                        <span
                                            class="badge badge-light-info fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Hold Work')
                                        <span
                                            class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Completed')
                                        <span class="badge badge-light-warning fs-7 fw-bold"
                                            style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Delivered')
                                        <span class="badge badge-light-success fs-7 fw-bold"
                                            style="background:green; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Feedback')
                                        <span class="badge badge-light-primary fs-7 fw-bold"
                                            style="background:black; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Feedback Delivered')
                                        <span class="badge badge-light-danger fs-7 fw-bold"
                                            style="background:black; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Cancelled')
                                        <span
                                            class="badge badge-light-danger fs-7 fw-bold">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Draft Ready')
                                        <span class="badge badge-light-primary fs-7 fw-bold"
                                            style="background:#eaea00; color:black">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Draft Delivered')
                                        <span class="badge badge-light-primary fs-7 fw-bold"
                                            style="background:green; color:white">{{$order->projectstatus}}</span>
                                        @elseif($order->projectstatus == 'Initiated')
                                        <span class="badge badge-light-primary fs-7 fw-bold"
                                            style="background:pink; color:white">{{$order->projectstatus}}</span>
                                        @endif
                                        <br><br>

                                        @if( $order->chapter != '' )
                                        <span class="badge badge-light-danger fs-7 fw-bold">{{$order->chapter}}</span>
                                        @endif
                                        <br> <br>

                                        @if( $order->tech == '1' )
                                        <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
                                        @endif
                                        <br> <br>
                                        @if($order->services == 'First Class Work')
                                        <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
                                        @endif
                                        <br> <br>
                                        @if($order->is_fail == 1)
                                        <span class="badge badge-light-danger fs-7 fw-bold">Fail Order</span>
                                        @endif
                                        <br> <br>
                                        @if ($order->resit == 'on')
                                        <span class="badge badge-light-danger fs-7 fw-bold">Resit</span>
                                        @endif
                                        <br> <br>

                                        <div class="fv-plugins-message-container invalid-feedback"></div>

                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_create_appaa{{$order->id}}"
                                            id="kt_toolbar_primary_button"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 m-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li class="fa fa-eye "></li>
                                            </span>
                                        </a>
                                        @include('order.section.edit-order')

                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_create_money{{$order->id}}"
                                            id="kt_toolbar_primary_button"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 m-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li class="fa fa-money"></li>
                                            </span>
                                        </a>
                                        @include('order.section.payment-edit')

                                        <a href="#" onclick="showConfirmation({{ $order->id }})"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 m-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li class="fa fa-close"></li>
                                            </span>
                                        </a>
                                        @include('order.section.fail-order')


                                        <a href="#" data-kt-drawer-toggle="#kt_drawer_chat"
                                            id="kt_drawer_chat_toggle{{ $order->order_id }}"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 m-1">
                                            <span class="svg-icon svg-icon-3">
                                                <i class="fa fa-comment"></i>
                                            </span>
                                        </a>
                                        @include('order.section.comment-order')

                                        <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa"
                                            id="kt_toolbar_primary_button"
                                            class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1 m-1">
                                            <span class="svg-icon svg-icon-3">
                                                <li style="color:white" class="fa fa-phone fa-lg"></li>
                                            </span>
                                        </a>
                                    </div>

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
                        <div class="table-responsive">
                            <div class="modal-content rounded mt-5">
                                <div class="card-body py-3">
                                    <div>
                                        <!--begin::Messenger-->
                                        <div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
                                            <!--begin::Card header-->
                                            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                                                <!--begin::Title-->
                                                <div class="card-title">
                                                    <!--begin::User-->
                                                    <div class="d-flex justify-content-center flex-column me-3">
                                                        <a href="#"
                                                            class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">

                                                        </a>
                                                        <!--begin::Info-->
                                                        <div class="mb-0 lh-1 text-start">
                                                            <span
                                                                class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                                                            <span class="fs-7 fw-bold text-muted">Call Back</span>
                                                        </div>
                                                        <!--end::Info-->
                                                    </div>
                                                    <!--end::User-->
                                                </div>

                                            </div>
                                            <div class="card-body" id="kt_drawer_chat_messenger_body">
                                            <div class="scroll-y me-n5 pe-5" data-kt-element="messages"
                                                data-kt-scroll="true" data-kt-scroll-activate="true"
                                                data-kt-scroll-height="auto"
                                                data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
                                                data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body"
                                                data-kt-scroll-offset="0px">
                                                    @foreach($order->ordercall->sortByDesc('created_at') as $call)
                                                    @if($call->created_by != auth()->user()->id )
                                                    <div class="d-flex justify-content-start mb-10">
                                                        <div class="d-flex flex-column align-items-start">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="symbol symbol-35px symbol-circle">
                                                                    @if($order->user->photo)
                                                                    <img alt="Pic"
                                                                        src="{{ asset($order->user->photo) }}" />
                                                                    @else
                                                                    <img alt="Pic"
                                                                        src="assets/media/avatars/blank.png" />
                                                                    @endif
                                                                </div>
                                                                <div class="ms-3">
                                                                    <a href="#"
                                                                        class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">{{$call->user->name}}</a>
                                                                    <span
                                                                        class="text-muted fs-7 mb-1">{{$call->created_at->diffForHumans()}}</span>
                                                                </div>
                                                            </div>
                                                            <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start"
                                                                data-kt-element="message-text">{!! $call->message!!}
                                                            </div>
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

                                                                    <img alt="Pic"src="{{ asset(Auth::user()->photo) }}" />
                                                                </div>
                                                            </div>
                                                            <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end"
                                                                data-kt-element="message-text">{!!$call->message!!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                </div>
                                                <form action="OrderCallInsert.{{$order->id}}" method="post">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <div id="kt_docs_quill_editor" style="min-height: 100px;"></div>
                                                        <input type="hidden" id="blogContent" name="callcontent"
                                                            required>
                                                    </div>

                                                    <script>
                                                        var quill = new Quill('#kt_docs_quill_editor', {
                                                            theme: 'snow',
                                                            placeholder: 'Type your content here...',
                                                        });

                                                        quill.on('text-change', function () {
                                                            var content = quill.root.innerHTML;
                                                            $('#blogContent').val(content);
                                                        });

                                                        quill.on('editor-change', function (eventName, ...args) {
                                                            if (eventName === 'text-change') {
                                                                var newHeight = quill.root.scrollHeight + 'px';
                                                                $('#kt_docs_quill_editor').css('min-height', newHeight);
                                                            }
                                                        });
                                                    </script>

                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            </form>
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





    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    @endsection