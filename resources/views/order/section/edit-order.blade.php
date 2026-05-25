<div class="modal fade" id="kt_modal_create_appaa{{$order->id}}" tabindex="-1" aria-hidden="true">
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
            <form id="kt_modal_new_target_form" class="form" action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Order View</h1>
                        @php
                            $statusColors = [
                                'Delivered' => 'green',
                                'Pending' => 'pink',
                                'Hold Work' => '#f1416c',
                                'In Progress' => '#7239ea',
                                'Completed' => '#eaea00',
                                'Feedback' => 'black',
                                'Feedback Delivered' => 'black',
                                'Cancelled' => '#f1416c',
                                'Draft Ready' => '#eaea00',
                                'Draft Delivered' => 'green',
                                'Other' => '#44f2e4',
                                'initiated' => 'pink',
                                // Add more statuses and their colors as needed
                            ];
                            @endphp

                            <div class="text-muted fw-bold fs-5">
                                <a href="/edit.{{$order->id}}">{{$order->order_id}}</a>
                                <a href="#" class="fw-bolder link-primary"> 
                                    <span class="label label-primary" style="background-color: {{ $statusColors[$order->projectstatus] ?? 'blue' }}; color: white; padding: 3px 10px 2px 10px; border-radius: 30px;">
                                        {{$order->projectstatus}}
                                    </span>
                                </a>
                            </div>
                    </div>
                    @if($order->user)
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">User Name</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" 
                                value="{{ $order->user->name ?? '' }}" name="user_name">
                        </div>

                        <div class="col-md-6 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Email</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->user->email}}" name="email">
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start order-container" data-order-id="{{$order->user->id}}">
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->countrycode}}" name="country_code" id="country_primary">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->mobile_no}}" name="mobile" id="primary">
                        </div>
                        <div class="col-md-2 fv-row d-flex align-items-end justify-content-center">
                            <!-- Button with double-sided arrow icon -->
                            <button type="button" class="btn btn-primary swapButton" id="swapButton{{$order->id}}">
                                <i class="fas fa-exchange-alt"></i> <!-- Font Awesome double-sided arrow icon -->
                            </button>
                        </div>
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->countrycode2}}" name="country_code2">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number-2</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{$order->user->mobile_no2}}" name="mobile2">
                        </div>
                    </div>
                    @else
                        User Not Available
                    @endif


                    <script>
                        document.querySelectorAll('#swapButton{{$order->id}}').forEach(function(button) {
                            button.addEventListener('click', function () {
                                var orderContainer = button.closest('.order-container');
                                var orderId = orderContainer.getAttribute('data-order-id');
                                
                                fetch('/swap-user-data', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({ user_id: orderId }),
                                })
                                .then(response => response.json())
                                .then(data => handleResponse(data, orderContainer))
                                .catch(error => console.error('Error:', error));
                            });
                            
                            function handleResponse(data, orderContainer) {
                                

                                if (data.success) {
                                    orderContainer.querySelector('[name="country_code"]').value = data.mobile1.country_code;
                                    orderContainer.querySelector('[name="mobile"]').value = data.mobile1.mobile_number;
                                    orderContainer.querySelector('[name="country_code2"]').value = data.mobile2.country_code;
                                    orderContainer.querySelector('[name="mobile2"]').value = data.mobile2.mobile_number;
                                    
                                    // Add any additional logic based on the response
                                }
                            }
                        });
                    </script>



                    
                    <div class="row g-9 mb-8 text-start">


                        <div class="col-md-4 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Module Code</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->module_code}}" name="module_code">
                        </div>
                        <div class="col-md-8 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Project Title</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$order->title}}" name="title">
                        </div>

                        <div class="col-md-8 mx-auto fv-row">
                        <label class="fs-6 fw-bold mb-2">User</label>
                        <input type="search" required class="form-control form-control-solid" placeholder="" oninput="searchUser(this.value)" name="user" list="user-list">
                        <datalist id="user-list"></datalist>
                    </div>

                    <script>
                        async function searchUser(value) {
                            try {
                                if (value.trim() !== '') {
                                    const response = await fetch(`/search-user?query=${encodeURIComponent(value)}`);
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    const data = await response.json();
                                    updateUsersList(data);
                                } else {
                                    clearUsersList();
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                // Handle error, e.g., show a message to the user
                            }
                        }

                        function updateUsersList(users) {
                            const datalist = document.getElementById('user-list');
                            datalist.innerHTML = '';
                            users.forEach(user => {
                                const option = document.createElement('option');
                                option.value = `${user.name} (${user.email}) (${user.mobile_no})`;
                                option.setAttribute('data-user-id', user.id); // Add data attribute for user id
                                datalist.appendChild(option);
                            });
                        }

                        function clearUsersList() {
                            document.getElementById('user-list').innerHTML = '';
                        }

                      
                        </script>



                    </div> 
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Order Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" name="order_date" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Writer Deadline</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_deadline" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}" name="delivery_date" onchange="showSelectedDate(this)">
                        </div>
                    </div>

                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Amount</label>
                        <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->amount}}  " name="amount">
                           
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Received Amount</label>
                            <input type="text" readonly class="form-control form-control-solid" placeholder="" value="{{$order->received_amount}} " name="r_amount">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Delivery Time</label>
                            @if($order->delivery_time != '')
                            <input type="time"  class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}" name="delivery_time" onchange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="delivery_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>
                       
                    </div>

                    


                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Word</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$order->pages}}" name="word">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Project Status</label>
                            <select name="status" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['Status'] as $status)
                                <option <?php if ( $order['projectstatus'] == $status['status']) {echo "selected";} ?> value="{{$status->status}}">{{$status->status}}</option>
                                @endforeach
                            </select>                           
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Writer Name</label>
                            <select name="writer_name" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['Team'] as $writer)
                                <option <?php if ( $order['writer_name'] == $writer['writer_name']) {echo "selected";} ?> value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                                @endforeach
                            </select>                    
                        </div>
                    </div>
                    
                    

                    <div class="row g-9 mb-8 text-start">
                       
                       
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Chapter</label>
                            <select name="chapter"aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value=" " <?php if ( $order['chapter'] == ' ') {echo "selected";} ?>> </option>
                                <option value="Chapter 1:  Introduction" <?php if ($order['chapter'] == 'Chapter 1:  Introduction') {echo "selected";} ?>>Chapter 1:  Introduction</option>
                                <option value="Chapter 2: Litreature Review" <?php if ($order['chapter'] == 'Chapter 2: Litreature Review') {echo "selected";} ?>>Chapter 2: Litreature Review</option>
                                <option value="Chapter 3: Methedology" <?php if ($order['chapter'] == 'Chapter 3: Methedology') {echo "selected";} ?>>Chapter 3: Methedology</option>
                                <option value="Chapter 4: Data Analysis" <?php if ($order['chapter'] == 'Chapter 4: Data Analysis') {echo "selected";} ?>>Chapter 4: Data Analysis</option>
                                <option value="Chapter 5: Conclusion & Recommendation" <?php if ($order['chapter']== 'Chapter 5: Conclusion & Recommendation') {echo "selected";} ?>>Chapter 5: Conclusion & Recommendation</option>
                            </select>    
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">College Name</label>
                            <select name="college_name" id="cld" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['college'] as $college)
                                    <option {{ $order['college_name'] == $college['college_name'] ? 'selected' : '' }} value="{{ $college->college_name }}">{{ $college->college_name }}</option>
                                @endforeach
                                <option value="add_college" data-select2-id="select2-data-18-e9lh12">Add College</option>
                            </select>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                        <script>
                            // Shorthand for $(document).ready()
                            $(function () {
                                // Use event delegation to handle the change event for #cld
                                $(document).on('change', '#cld', function () {
                                    // Check if the selected option is "Add College"
                                    if ($(this).val() === 'add_college') {
                                        // Open a new tab and redirect to the /college page
                                        window.open('/college', '_blank');
                                    }
                                });
                            });
                        </script>


                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Type Of Services</label>
                            <select name="services" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['service'] as $service)
                                    <option <?php if ( $order['services'] == $service['service_name']) {echo "selected";} ?> value="{{$service->service_name}}">{{$service->service_name}}</option>
                                @endforeach   
                            </select>                         
                        </div>

                        <script>
                            // Assuming you have jQuery loaded in your project
                            $(document).ready(function() {
                                $('select[name="paper_type"]').change(function() {
                                    if ($(this).val() === 'add_college') {
                                        window.location.href = '/college';
                                    }
                                });
                            });
                        </script>   
                    </div>

                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Draft Required</label>
                            <select name="daraft_status" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                    <option <?php if ( $order['draftrequired'] == 'Y') {echo "selected";} ?>  value="Y">Yes</option>
                                    <option <?php if ( $order['draftrequired'] == 'N') {echo "selected";} ?>value="N">No</option>
                            </select>                         
                          </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Draft Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->draft_date)->format('Y-m-d') }}" name="draft_date" onchange="showSelectedDate(this)">
                        </div>
                       
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Draft Time</label>
                            @if($order->draft_time != '')
                            <input type="time" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->draft_time)->format('H:i') }}" name="draft_time" oncange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="draft_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>


                    </div>
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Technical</label>
                            <select name="tech" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                <option {{ $order['tech'] == '1' ? 'selected' : '' }} value="1">Technical</option>
                                <option {{ ($order['tech'] == '0' || $order['tech'] == '') ? 'selected' : '' }} value="0">Non Technical</option>
                            </select>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Resit</label>
                            <select name="resit" aria-label="Select a Timezone" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                <option {{ $order['resit'] == 'on' ? 'selected' : '' }} value="on">Resit</option>
                                <option {{ ($order['resit'] == 'off' || $order['resit'] == '') ? 'selected' : '' }} value="off">Normal</option>
                            </select>
                        </div>


                       
                       
                       
                    </div>

                    <div class="row g-9 mb-8 text-center">
                        <div class="col-md-12 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Messages</label>
                            <textarea name="messages" value="{{$order->message}}"  class="form-control form-control-solid" id="" cols="30" rows="3">{{$order->message}}</textarea>
                        </div>
                     
                    </div>

                    <div class="card-footer">
                        <button  type="submit" class="btn btn-lg btn-primary fw-bolder">
							Submit
						</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
