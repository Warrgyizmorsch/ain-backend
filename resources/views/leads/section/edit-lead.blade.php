<div class="modal fade" id="kt_modal_create_appaa{{ $lead->order_id }}" tabindex="-1" aria-hidden="true">
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
            <form id="kt_modal_new_target_form{{$lead->id}}" class="form" method="POST" action="{{ route('leads.edit', ['id' => $lead->id ]) }}">
                @csrf
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Leads View/Edit</h1>
                        <div class="text-muted fw-bold fs-5">{{ $lead->order_id }}</div>
                    </div>
                   
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">User Name</label>
                        <input type="text" required  class="form-control form-control-solid" placeholder="" value="{{ $lead->user->name }}" name="user_name">
                           
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Email</label>
                            <input type="text"  required class="form-control form-control-solid" placeholder="" value="{{ $lead->user->email }}" name="email">
                        </div>
                    </div>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" required id='country_primary' class="form-control form-control-solid" placeholder="" value="{{ $lead->user->countrycode }}" name="countrycode">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Mobile Number</label>
                            <input type="text" required id="primary" class="form-control form-control-solid" placeholder="" value="{{$lead->user->mobile_no}}" name="mobile">
                        </div>
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Swipe Option</label>
                            <li class="fa fa-rotate-right btn btn-primary rotate-icon{{$lead->order_id}}" id="{{$lead->emp_id}}" data-id="{{$lead->id}}"></li>
                        </div>
                        <div class="col-md-2 fv-row">
                            <label class="fs-6 fw-bold mb-2">Country Code</label>
                            <input type="text" class="form-control form-control-solid" placeholder="" value="{{ $lead->user->countrycode2 }}" name="countrycode2">
                        </div>
                        <div class="col-md-3 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Secondary Mobile Number</label>
                            <input type="text" id="mobile2" class="form-control form-control-solid" placeholder="" value="{{$lead->user->mobile2}}" name="mobile_no2">
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('.rotate-icon{{$lead->order_id}}').click(function() {
                                var empId = $(this).data('id'); // Get the data-id attribute value
                                $.ajax({
                                    url: '/updateUser/' + empId, // Append empId to the URL
                                    type: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            // Update UI with new data received from the server
                                            $('[name="countrycode"]').val(response.mobile1.country_code);
                                            $('[name="mobile"]').val(response.mobile1.mobile_number);
                                            $('[name="countrycode2"]').val(response.mobile2.country_code);
                                            $('[name="mobile_no2"]').val(response.mobile2.mobile_number);
                                            console.log('Updated user data:', response);
                                        } else {
                                            console.error(response.message);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(error);
                                    }
                                });
                            });
                        });
                        </script>



                    
                    <div class="row g-9 mb-8 ">
                        <div class="col-md-4 mx-auto fv-row">
                                <label class=" fs-6 fw-bold mb-2">Module Code</label>
                                <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->module_code}}" name="module_code">
                            </div>
                            <div class="col-md-8 mx-auto fv-row">
                                <label class=" fs-6 fw-bold mb-2">Project Title</label>
                                <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$lead->project_title}}" name="project_title">
                            </div>
                        </div>   
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Pages</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->pages}}" name="pages">
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-bold mb-2">Lead Status</label>
                            <select name="l_status" aria-label="Select Lead Status" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                                <option value="" <?php if (empty($lead['l_status'])) echo "selected"; ?> data-select2-id="select2-data-18-e9lh12"></option>
                                <option <?php if ($lead['l_status'] == 'Waiting') echo "selected"; ?> value="Waiting">Waiting</option>
                                <option <?php if ($lead['l_status'] == 'Quote') echo "selected"; ?> value="Quote">Quote</option>
                                <option <?php if ($lead['l_status'] == 'Confirmation') echo "selected"; ?> value="Confirmation">Confirmation</option>
                            </select>
                        </div>

                    </div>

                    <div class="row g-9 mb-8 text-start">
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-bold mb-2">Services</label>
                        <select name="service_type" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                            <option value="" <?php if (empty($lead['service_type'])) echo "selected"; ?> data-select2-id="select2-data-18-e9lh12"></option>
                            @foreach($service as $service)
                                <option <?php if ($lead['service_type'] == $service['service_name']) echo "selected"; ?> value="{{ $service['service_name'] }}">{{ $service['service_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 fv-row d-flex">
                        <label class="fs-6 fw-bold mb-2">Technical</label>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input name="tech" style="height: 2.5rem;width: 2.5rem;" 
                                class="form-check-input submenu-checkbox" 
                                type="checkbox"  data-kt-check="true" 
                                data-kt-check-target=".widget-9-check"
                                {{ $lead['tech'] == 'on' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="col-md-3 fv-row d-flex">
                        <label class="fs-6 fw-bold mb-2">Resit</label>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input name="resit" style="height: 2.5rem;width: 2.5rem;" 
                                class="form-check-input submenu-checkbox" 
                                type="checkbox"  data-kt-check="true" 
                                data-kt-check-target=".widget-9-check"
                                {{ $lead['resit'] == 'on' ? 'checked' : '' }}>
                        </div>
                    </div>
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($lead->deadline)->format('Y-m-d') }}" name="delivery_date" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-6 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Time</label>
                            <input type="time" class="form-control form-control-solid" placeholder="" value="{{ $lead->delivery_time }}" name="delivery_time" onchange="showSelectedTime(this)">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Amount</label>
                            <input type="text"  class="form-control form-control-solid" placeholder="" value="{{$lead->price}}" name="price">
                        </div>
                    </div>

                    

                    <div class="row g-9 mb-8 text-center">
                        <div class="col-md-12 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Messages</label>
                            <textarea name="message"  class="form-control form-control-solid" id="" cols="30" rows="3" required>{{$lead->message}}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="action" value="submit">Submit</button>
                        <button type="button" class="btn btn-secondary convert-btn{{$lead->id}}" data-lead-id="{{$lead->id}}">Convert</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
</div>
