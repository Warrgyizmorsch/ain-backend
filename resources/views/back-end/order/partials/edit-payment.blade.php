<div class="modal fade" id="kt_modal_create_money{{$order->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-950px">
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
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Payment Edit</h1>
                        <div class="text-muted fw-bold fs-5">{{$order->order_id}}                                                                                        
                        <a href="#" class="fw-bolder link-primary"> <span class="label label-primary" style="background-color:Green; color:white; padding:3px 10px 2px 10px ; border-radius:30px">{{$order->projectstatus}}</span></a></div>
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
                                            <!-- Include SweetAlert library -->
                                            <!--<script src="sweetalert2.min.js"></script>-->
                                            <!--<link rel="stylesheet" href="sweetalert2.min.css">-->

                                            <!-- Your table cell with SweetAlert -->
                                            <td>
                                                @if(Auth::user()->role_id == 1)
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-payment" data-payment-id="{{ $payment->id }}">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
                                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
                                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
                                                        </svg>
                                                    </span>
                                                </a>
                                                @endif
                                                <a href="{{ route('orderpayments.edit', ['order' => $order->id, 'payment' => $payment->id]) }}">
                                                    <span><li class="fa fa-edit"></li></span>
                                                </a>

                                            </td>

                                            <!-- Script to handle SweetAlert -->
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const deleteButtons = document.querySelectorAll('.delete-payment');

                                                    deleteButtons.forEach(button => {
                                                        button.addEventListener('click', function () {
                                                            const paymentId = this.getAttribute('data-payment-id');

                                                            Swal.fire({
                                                                title: 'Are you sure?',
                                                                text: 'Are You Sure To Delete Payment Deatail !',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Yes, delete it!'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Redirect to your delete route or submit the form
                                                                    const form = document.createElement('form');
                                                                    form.method = 'POST';
                                                                    form.action = '{{ route('orderpayments.delete', ['id' => '__paymentId__']) }}'.replace('__paymentId__', paymentId);
                                                                    form.innerHTML = `
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                                        <input type="hidden" name="amount" value="{{ $payment->paid_amount }}">
                                                                    `;
                                                                    document.body.appendChild(form);
                                                                    form.submit();
                                                                }
                                                            });
                                                        });
                                                    });
                                                });
                                            </script>

                                        </tr>
                                    @endforeach
                                </tbody>
							
							</table>
                            
						</div>
					</div>
                   
            </div>
                 <div style="width: 85% !important;" class="mb-5  d-flex flex-center p-15 shadow bg-body rounded  w-md-550px mx-auto ms-xl-20">
						<form id="paymentForm" action="{{ route('payment.update', ['id' =>  $order->id ]) }}" method="post" style="width:100%" class="form fv-plugins-bootstrap5 fv-plugins-framework"  >
                        @csrf
                        @method('PUT')
                            <div class="text-center mb-10">
								<h1 class="text-dark mb-3">Make Payment (Due Amount :- 
                                @if(is_numeric($order->amount) && is_numeric($order->received_amount))
									{{ $order->amount - $order->received_amount }} £
								@else
									N/A
								@endif 
                                )</h1>
							</div>
                            <div class="row">
                                <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                                    <label class="form-label fw-bolder text-dark fs-6">Payment Date and Time</label>
                                    <input class="form-control form-control-solid" type="text" placeholder="" name="payment_date" autocomplete="off" value="{{ now()->format('l d F Y h:i A') }}" readonly>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>


                                <div class=" mb-10 fv-plugins-icon-container col-6 text-start">
                                    <label class="form-label fw-bolder  text-dark fs-6">Paid Amount</label>
                                    <input required class="form-control form-control-solid" type="text" placeholder="" name="amount" autocomplete="off">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                                    <label class="form-label fw-bolder text-dark fs-6">Payee Name</label>
                                    <input  class="form-control form-control-solid" type="text" placeholder="" name="payee_name" autocomplete="off" value="">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="mb-10 fv-plugins-icon-container col-6 text-start">
                                    <label class="form-label fw-bolder text-dark fs-6">Company Accounts</label>
                                    <select  name="company_accounts" id="company_accounts" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                        <option value="" disabled selected hidden>Select a Company Account</option>
                                        <option value="HDFC">HDFC</option>
                                        <option value="Native">Native</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Skydo">Skydo</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                
                            </div>

                            <div class="row">
                                 <div class=" mb-10 fv-plugins-icon-container col-12 text-center">
                                    <label class="form-label fw-bolder  text-dark fs-6">Messages</label>
                                    <textarea required class="form-control form-control-solid" name="message" id="" cols="30" rows="5"></textarea>                                    
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
							
							
							
							<div class="text-center pb-lg-0 pb-8">
								<button onclick="submitForm(this);" type="submit" class="btn btn-lg btn-primary fw-bolder">
									Submit
								</button>
							</div>
						<div></div>
                    </form>
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
					</div>
                    <br>
        </div>
    </div>
</div>




