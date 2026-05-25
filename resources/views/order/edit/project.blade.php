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
                        <div class="text-muted fw-bold fs-5">
                            <a href="/edit.{{$order->id}}">{{$order->order_id}}</a>
                            <a href="#" class="fw-bolder link-primary"> <span class="label label-primary" style="background-color: 
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
                                </span></a></div>
                    </div>
                   
                    
                    
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Module Code</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$order->module_code}}" name="module_code">
                        </div>
                        <div class="col-md-8 mx-auto fv-row">
                            <label class=" fs-6 fw-bold mb-2">Project Title</label>
                            <input type="text" required class="form-control form-control-solid" placeholder="" value="{{$order->title}}" name="title">
                        </div>
                    </div>
                    <div class="row g-9 mb-8 text-start">
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Order Date</label>
                            <input type="date" readonly class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" name="order_date" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-bold mb-2">Writer Deadline</label>
                            <input type="date" class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline)->format('Y-m-d') }}" name="writer_deadline" onchange="showSelectedDate(this)">
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Writer Deadline Time</label>
                            @if($order->writer_deadline_time != '')
                            <input type="time"  class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->writer_deadline_time)->format('H:i') }}" name="writer_deadline_time" onchange="showSelectedTime(this)">
                            @else
                            <input type="time" class="form-control form-control-solid" placeholder="" value="" name="writer_deadline_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>
                        <div class="col-md-4 fv-row text-start">
                            <label class="fs-6 fw-bold mb-2">Delivery Date</label>
                            <input type="date" readonly class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}" name="delivery_date" onchange="showSelectedDate(this)">
                        </div>
                    </div>

                    <div class="row g-9 mb-8 text-start">
                        
                        <div class="col-md-4 fv-row text-start">
                            <label class=" fs-6 fw-bold mb-2">Delivery Time</label>
                            @if($order->delivery_time != '')
                            <input readonly type="time"  class="form-control form-control-solid" placeholder="" value="{{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}" name="delivery_time" onchange="showSelectedTime(this)">
                            @else
                            <input readonly type="time" class="form-control form-control-solid" placeholder="" value="" name="delivery_time" onchange="showSelectedTime(this)">
                            @endif
                        </div>
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
                       
                    </div>

                    


                    <div class="row g-9 mb-8 text-start">
                        
                        <div class="col-md-4 fv-row">
                            <label class=" fs-6 fw-bold mb-2">Writer Name</label>
                            <select name="writer_name" aria-label="Select a Timezone" data-control="select2"  class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
						        <option value="" data-select2-id="select2-data-18-e9lh12"></option>
                                @foreach($data['Team'] as $writer)
                                <option <?php if ( $order['writer_name'] == $writer['writer_name']) {echo "selected";} ?> value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                                @endforeach
                            </select>                    
                        </div>
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