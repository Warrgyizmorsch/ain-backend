@extends('layouts.app')

@section('content')
   
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Order Edit
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignement In Need</small>
            </div>
            
        </div>
	</div>
    <div id="kt_content_container" class="container-xxl">
        <div class="modal-content rounded">
            
            
            <div class="modal-body scroll-y px-10 px-lg-15 pt-15 pb-15">
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Payment Edit</h1>
                    <div class="text-muted fw-bold fs-5">{{$order->order_id}}                                                                                        
                        <a href="#" class="fw-bolder link-primary">
                            <span class="label label-primary" style="background-color: 
                                    @if($order->projectstatus == 'Delivered') green;
                                    @elseif($order->projectstatus == 'Pending') pink;
                                    @elseif($order->projectstatus == 'Hold Work') #f1416c;
                                    @elseif($order->projectstatus == 'In Progress') #7239ea;
                                    @elseif($order->projectstatus == 'Completed') #eaea00;
                                    @elseif($order->projectstatus == 'Feedback') black;
                                    @elseif($order->projectstatus == 'Feedback Delivered') black;
                                    @elseif($order->projectstatus == 'Cancelled') #f1416c;
                                    @elseif($order->projectstatus == 'Draft Ready') #eaea00;
                                    @elseif($order->projectstatus == 'Draft Delivered') green;
                                    @elseif($order->projectstatus == 'Other') #44f2e4;
                                    @elseif($order->projectstatus == 'initiated') pink;
                                    @else #blue; {{-- Default color for other statuses --}}
                                    @endif
                                    color: white; padding: 3px 10px 2px 10px; border-radius: 30px;">
                                    {{$order->projectstatus}}
                                </span>
                        </a>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table  class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead class="p-2">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-15px">SR</th>
                                    <th class="min-w-50px">Order ID</th>
                                    <th class="min-w-30px">Payment Date</th>
                                    <th class="min-w-30px">Amount</th>
                                    <th class="min-w-40px">References</th>
                                   
                                    <th class="min-w-40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->payment as $payment)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{$order->order_id}}</td>
                                        <td>{{$payment->payment_date}}</td>
                                        <td>{{ $payment->paid_amount }}</td> <!-- Replace with the actual attribute you want to display -->
                                        <td>{{ $payment->reference }}</td> <!-- Replace with the actual attribute you want to display -->
                                        <td>
                                            @if(Auth::user()->role_id ==1 )
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_create_money{{$payment->id}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" id="deleteButton">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                            </a>


                                            <div class="modal fade" id="kt_modal_create_money{{$payment->id}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered mw-500px">
                                                    <div class="modal-content rounded">
                                                        <div class="modal-header pb-0 border-0 justify-content-end">
                                                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                                <span class="svg-icon svg-icon-1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                            <h3>Are You Sure To Delete Payment Deatail</h3>
                                                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                                            <thead class="p-2">
                                                                <tr class="fw-bolder text-muted bg-light">
                                                                    <th class="min-w-15px">Order ID</th>
                                                                   
                                                                    <th class="min-w-50px">Amount</th>
                                                                </tr>
                                                        
                                                                <tr>
                                                                    <td>{{$order->order_id}}</td>
                                                                    <td>{{ $payment->paid_amount }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="model-footer mb-5">
                                                            <div class="text-center pb-lg-0 pb-8">
                                                                <form method="POST" action="{{ route('orderpayments.delete', ['id' => $payment->id]) }}">
                                                                    @csrf
                                                                    @method('DELETE')    
                                                                    <input type="hidden" name="order_id" value="{{$order->id}}">                     
                                                                    <input type="hidden" name="amount" value="{{ $payment->paid_amount }}">                     
                                                                    <button  type="submit" class="btn btn-lg btn-primary fw-bolder">Submit</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endif
                                            <a href="{{ route('orderpayments.edit', ['order' => $order->id, 'payment' => $payment->id]) }}">
                                                <span><li class="fa fa-edit"></li></span>
                                            </a>

                                        </td> 

                                    </tr>
                                    @endforeach
                            </tbody>
                        
                        </table>
                        
                    </div>
                </div>
                   
            </div>
            <div style="width: 85% !important;" class="mb-5  d-flex flex-center p-15 shadow bg-body rounded  w-md-550px mx-auto ms-xl-20">
            <form 
                id="paymentForm" 
                action="{{ isset($editOrder) ? route('payment.updateM', ['id' => $editOrder->id]) : route('payment.update', ['id' => $order->id]) }}" 
                method="POST" 
                style="width:100%" 
                class="form fv-plugins-bootstrap5 fv-plugins-framework"
            >
                @csrf
                @method('PUT') <!-- This will correctly send a PUT request with both routes -->
                <!-- Add form fields here -->
                <div class="text-center mb-10">
                    <h1 class="text-dark mb-3">
                        {{ isset($editOrder) ? 'Update Payment' : 'Make Payment' }}
                        (Due Amount :-
                        @if(is_numeric($order->amount) && is_numeric($order->received_amount))
                            {{ $order->amount - $order->received_amount }} £
                        @else
                            N/A
                        @endif
                        )
                    </h1>
                </div>
                    <div class="row">
                        <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                            <label class="form-label fw-bolder text-dark fs-6">Payment Date and Time</label>
                            <input class="form-control form-control-solid" type="text" placeholder="" name="payment_date" autocomplete="off" value="{{ $editOrder->payment_date ?? now()->format('l d F Y h:i A') }}" readonly>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>


                        <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                            <label class="form-label fw-bolder text-dark fs-6">Paid Amount</label>
                            <input 
                                required 
                                class="form-control form-control-solid" 
                                type="text" 
                                placeholder="" 
                                name="amount" 
                                value="{{ old('amount', $editOrder->paid_amount ?? '') }}" 
                                {{ isset($editOrder->paid_amount) ? 'readonly' : '' }}
                                autocomplete="off"
                            >
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                    </div>
                    <div class="row">
                    <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                        <label class="form-label fw-bolder text-dark fs-6">Payee Name</label>
                        <input 
                             
                            class="form-control form-control-solid" 
                            type="text" 
                            placeholder="" 
                            name="payee_name" 
                            autocomplete="off" 
                            value="{{ old('payee_name', $editOrder->payee_name ?? '') }}"
                        >
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>

                    <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                        <label class="form-label fw-bolder text-dark fs-6">Company Accounts</label>
                        <select 
                             
                            name="company_accounts" 
                            id="company_accounts"  
                            class="form-select form-select-solid"
                        >
                            <option value="" disabled {{ empty(old('company_accounts', $editOrder->company_accounts ?? '')) ? 'selected' : '' }} hidden>
                                Select a Company Account
                            </option>
                            <option value="HDFC" {{ old('company_accounts', $editOrder->company_accounts ?? '') == 'HDFC' ? 'selected' : '' }}>HDFC</option>
                            <option value="Native" {{ old('company_accounts', $editOrder->company_accounts ?? '') == 'Native' ? 'selected' : '' }}>Native</option>
                            <option value="PayPal" {{ old('company_accounts', $editOrder->company_accounts ?? '') == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                            <option value="Skydo" {{ old('company_accounts', $editOrder->company_accounts ?? '') == 'Skydo' ? 'selected' : '' }}>Skydo</option>
                            <option value="Other" {{ old('company_accounts', $editOrder->company_accounts ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>

                        
                    </div>

                    <div class="row">
                        <div class=" mb-10 fv-plugins-icon-container col-12 text-center">
                            <label class="form-label fw-bolder  text-dark fs-6">Messages</label>
                            <textarea required class="form-control form-control-solid" name="message" id="" cols="30" rows="5">{{ $editOrder->reference ?? '' }}</textarea>                                    
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="text-center pb-lg-0 pb-8">
                        <button onclick="submitForm(this);" type="submit" class="btn btn-lg btn-primary fw-bolder">
                            Submit
                        </button>
                    </div>
                
                </form>
            </div>
        
        </div>
    </div>
</div>
        
                
<script>
    function submitForm(btn) {
        var form = btn.form;

        if (form.checkValidity()) {
            btn.disabled = true;
            btn.innerHTML = 'Submitting...'; 
            
            form.submit();
        } else {
            form.reportValidity();
        }
    }
</script>



@endsection