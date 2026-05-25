<!-- Edit Lead Modal -->
<div class="modal fade" id="kt_modal_create_edit_order{{$order['id']}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">            
            <div class="modal-header">
                <h5 class="modal-title" id="convertLeadModalLabel{{ $order['id'] }}">Edit Lead (<strong class="text-success">Order Code: {{ $order['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body p-9">
                <form id="editOrderForm{{$order['id']}}" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data" action="{{ route('ll.order.update', $order['id']) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Project Title -->
                        <div class="form-group col-md-8 mb-2">
                            <label for="project_title">Project Title</label>
                            <input type="text" class="form-control" id="project_title" name="project_title" value="{{ $order['project_title'] }}" required>
                        </div>

                        <!-- Order Status -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="statusSelect">Status</label>
                            <select required id="statusSelect" name="order_status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="Delivered" {{ $order['order_status'] == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Pending" {{ $order['order_status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Hold Work" {{ $order['order_status'] == 'Hold Work' ? 'selected' : '' }}>Hold Work</option>
                                <option value="In Progress" {{ $order['order_status'] == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $order['order_status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Feedback" {{ $order['order_status'] == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                <option value="Feedback Delivered" {{ $order['order_status'] == 'Feedback Delivered' ? 'selected' : '' }}>Feedback Delivered</option>
                                <option value="Cancelled" {{ $order['order_status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Draft Ready" {{ $order['order_status'] == 'Draft Ready' ? 'selected' : '' }}>Draft Ready</option>
                                <option value="Draft Delivered" {{ $order['order_status'] == 'Draft Delivered' ? 'selected' : '' }}>Draft Delivered</option>
                                <option value="Initiated" {{ $order['order_status'] == 'Initiated' ? 'selected' : '' }}>Initiated</option>
                                <option value="Other" {{ $order['order_status'] == 'Other' ? 'selected' : '' }}>Other</option>
                                <option value="Advance Assignment" {{ $order['order_status'] == 'Advance Assignment' ? 'selected' : '' }}>Advance Assignment</option>
                            </select>

                        </div>

                        <!-- Pages -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="pages">Word</label>
                            <input type="number" class="form-control" id="pages" name="pages" value="{{ $order['pages'] }}" 
                                min="0" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || /\d/.test(event.key);">
                        </div>

                        <!-- Price -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="price">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $order['price'] }}"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || event.key === '.' || /\d/.test(event.key);">
                        </div>

                        <!-- Deadline -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ \Carbon\Carbon::parse($order['deadline'])->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Delivery Time -->
                        <!-- <div class="form-group col-md-6">
                            <label for="delivery_time">Delivery Time</label>
                            <input type="text" class="form-control" id="delivery_time" name="delivery_time" value="{{ $order['delivery_time'] }}">
                        </div> -->                        

                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="workTypeSelect">Work Type</label>
                            <select required id="workTypeSelect" name="work_type" class="form-control">
                                <option value="">Select Work Type</option>
                                <option value="Standard" {{ $order['work_type'] == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="First Class Work" {{ $order['work_type'] == 'First Class Work' ? 'selected' : '' }}>First Class Work</option>
                            </select>
                        </div>
                        
                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="serviceTypeSelect">Service Type</label>
                            <select required id="serviceTypeSelect" name="service_type" class="form-control">
                                <option value="">Select Service Type</option>
                                <option value="Assignment" {{ $order['service_type'] == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="Dissertation" {{ $order['service_type'] == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                                <option value="Thesis" {{ $order['service_type'] == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                                <option value="Research Project" {{ $order['service_type'] == 'Research Project' ? 'selected' : '' }}>Research Project</option>
                            </select>
                        </div>
                        <!-- Subjects -->
                        <div class="form-group col-md-4 mb-2">
                            <div class="form-group">
                                <label for="subjectSelect">Subject</label>
                                <select required id="subjectSelect" name="subject" class="form-control">
                                    <option value="">Select Subject</option>
                                    <option value="Matlab" {{ $order['subject'] == 'Matlab' ? 'selected' : '' }}>Matlab</option>
                                    <option value="Data Science" {{ $order['subject'] == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Engineering" {{ $order['subject'] == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="App Development" {{ $order['subject'] == 'App Development' ? 'selected' : '' }}>App Development</option>
                                    <option value="Web Development" {{ $order['subject'] == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="Exam" {{ $order['subject'] == 'Exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="Public Health" {{ $order['subject'] == 'Public Health' ? 'selected' : '' }}>Public Health</option>
                                    <option value="Presentation" {{ $order['subject'] == 'Presentation' ? 'selected' : '' }}>Presentation (PPT)</option>
                                    <option value="Portfolio" {{ $order['subject'] == 'Portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="Research Report" {{ $order['subject'] == 'Research Report' ? 'selected' : '' }}>Research Report</option>
                                    <option value="Business Management" {{ $order['subject'] == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                                    <option value="Project Management" {{ $order['subject'] == 'Project Management' ? 'selected' : '' }}>Project Management</option>
                                    <option value="Essay" {{ $order['subject'] == 'Essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="HRM" {{ $order['subject'] == 'HRM' ? 'selected' : '' }}>HRM</option>
                                    <option value="Economic" {{ $order['subject'] == 'Economic' ? 'selected' : '' }}>Economic</option>
                                    <option value="Other" {{ $order['subject'] == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="assignment_details">Assignment Details</label>
                            <textarea required class="form-control" id="assignment_details" name="assignment_details" rows="4" cols="50">{{ $order['assignment_details'] }}</textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal" type="button" class="btn btn-secondary">Close</button>
                <button type="submit" form="editOrderForm{{$order['id']}}" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section End -->