@extends('layouts.app')
@section('content')
<style>
	.shadow-sm {
    display: none;
}
.text-gray-700 {
    margin-top: revert;
}
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Payments
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                </div>
                
            </div>
        </div>
        <!-- Add Filter -->
        <div class="col-xl-12">
                @include('master.section.filter')
        </div>

        <div class="card card-xl-stretch  mb-xl-">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Payments List</span>
                    <span class="text-muted mt-1 fw-bold fs-7"></span>
                </h3>
            </div>
            <div class="card-body py-3">
                    <div class="d-flex justify-content-end mb-3">
                        <input type="checkbox" id="select-all">
                        <button id="bulk-update-btn" class="btn btn-primary btn-sm ms-2">Update Selected Status</button>
                    </div>
              
                    <div class="table-responsive">
                        <table  class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead class="p-2">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class='px-2'>Sr.No.</th>
                                        <th>Date</th>
                                        <th>Order Code</th>
                                        @if (auth()->user()->role_id == 1)
                                            <th>Client</th>
                                        @else
                                            <th>title</th>
                                        @endif
                                        
                                        <th>Amount</th>
                                        @if (auth()->user()->role_id == 1)
                                        <th>Update By</th>
                                        @endif
                                        <th>Payee Name</th>
                                        <th>Company Account</th>
                                        <th class='text-center'>Action</th>
                                    </tr>
                                </thead>
                                <tbody style="display:none" id="content" class="searchData">
                                
                                <tbody style="display:none" id="content" class="searchData">
                                    <!-- ... (your existing code) ... -->
                                </tbody >
                            
                                @foreach($data['payments'] as $payment)
                                  <tr @if ($payment->account_status == 1) style="font-weight: bold;" @endif>

                                    <td class='text-center'>{{$loop->index + 1 + ($data['payments']->perPage() * ($data['payments']->currentPage() - 1))}}</td>
                                        <td>{{$payment->payment_date  }}</td>
                                        <td>
                                            @if($payment->order && $payment->order->order_id)
                                                {{$payment->order->order_id}}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->role_id == 1)
                                                @if(optional($payment->order->user)->name)
                                                    {{ $payment->order->user->name }} <br>
                                                    <span>
                                                        @if(optional($payment->order->user)->mobile_no)
                                                            {{ $payment->order->user->mobile_no }} <br>
                                                        @endif
                                                        @if(optional($payment->order->user)->email)
                                                            ({{ $payment->order->user->email }})
                                                        @endif
                                                    </span>
                                                @else
                                                    N/A
                                                @endif
                                            @else
                                                {{ optional($payment->order)->title ?? 'N/A' }}
                                            @endif
                                        </td>

                                        <td>
                                        {{$payment->paid_amount  }}
                                        </td>
                                        @if (auth()->user()->role_id == 1)
                                        <td>{{ $payment->payment_update_by }}</td>
                                        @endif
                                        <td>{{ $payment->payee_name }}</td>
                                        <td>{{ $payment->company_accounts }}</td>
                                        <td  style="justify-content:center" class=" text-center icon-container my-auto d-flex">
                                        @if (auth()->user()->role_id == 1)
                                            
                                            <a href="#" style="color:white" class="btn btn-icon btn-bg-danger btn-active-color-light btn-sm me-1 delete-link" id="delete-payment-{{$payment->id}}" onclick="deletePayment({{$payment->id}})">
                                                <span class="svg-icon svg-icon-3">
                                                    <li class="fa fa-trash fa-lg"></li>
                                                </span>
                                            </a>
                                            @include('master.section.edit-payment')
                                            <a href="#"  data-bs-toggle="modal" data-bs-target="#kt_modal_create_appaa{{$payment->id}}" id="kt_toolbar_primary_button" class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            <!-- Add checkbox input -->
                                        @endif
                                            <a href="#" id="" style="color:white" class="btn btn-icon btn-bg-primary btn-active-color-light btn-sm me-1">
                                                <!-- <span class="svg-icon svg-icon-3">
                                                    <input class="form-check-input payment-checkbox" type="checkbox" id="checkbox{{$payment->id}}" data-payment-id="{{$payment->id}}">
                                                </span> -->
                                                <span class="svg-icon svg-icon-3">
                                                <input 
                                                    class="form-check-input payment-checkbox" 
                                                    type="checkbox" 
                                                    id="checkbox{{ $payment->id }}" 
                                                    data-payment-id="{{ $payment->id }}" 
                                                    {{ $payment->account_status == 1 ? 'checked' : '' }} 
                                                >
                                            </span>

    
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        {{ $data['payments']->appends(request()->except('page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
            

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".payment-checkbox");

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                const paymentId = this.getAttribute("data-payment-id");
                const isChecked = this.checked ? 1 : 0;

                fetch(`/update-account-status/${paymentId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({ account_status: isChecked }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || "Error updating status");
                        this.checked = !isChecked; // Revert checkbox state if the update fails
                    }
                })
                .catch(error => {
                    alert("Error updating status");
                    this.checked = !isChecked; // Revert checkbox state on error
                });
            });
        });
    });
</script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {
            // Select all checkboxes
             
            $('#select-all').on('click', function () {
                var isChecked = this.checked;
                $('.payment-checkbox').each(function () {
                    this.checked = isChecked;
                });
            });

            // Bulk update button
            $('#bulk-update-btn').on('click', function () {
                var selectedPayments = [];
                $('.payment-checkbox:checked').each(function () {
                    var paymentId = $(this).data('payment-id');
                    var status = $(this).data('status') ? 0 : 1; // Toggle status
                    selectedPayments.push({ payment_id: paymentId, status: status });
                });

                if (selectedPayments.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('payments.bulkUpdateStatus') }}',
                        data: {
                            payments: selectedPayments,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            handleResponse(data);
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Please select at least one payment to update.',
                    });
                }
            });
 


            function handleResponse(data) {
                console.log(data.message);

                // Reload the page if the update was successful
                if (data.message === 'Payment statuses updated successfully') {
                    window.location.reload();
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert library -->

    <script>
        function deletePayment(paymentId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this payment!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed deletion, send AJAX request
                    $.ajax({
                        url: '/Payments/' + paymentId,
                        type: 'DELETE',
                        headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        success: function(response) {
                            // Handle success
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success'
                            });
                            // Remove the deleted payment from the UI
                            $('#delete-payment-' + paymentId).remove();
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete payment',
                                icon: 'error'
                            });
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        }
    </script>



@endsection