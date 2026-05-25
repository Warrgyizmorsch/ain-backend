<!-- Edit Lead Modal -->
<div class="modal fade" id="kt_modal_create_edit_lead{{$lead['id']}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">            
            <div class="modal-header">
                <h5 class="modal-title" id="convertLeadModalLabel{{ $lead['id'] }}">Edit Lead (<strong class="text-success">Order Code: {{ $lead['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body p-9">
                <form id="editLeadForm{{$lead['id']}}" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data" action="{{ route('ll.lead.update', $lead['id']) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Project Title -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="project_title">Project Title</label>
                            <input type="text" class="form-control" id="project_title" name="project_title" value="{{ $lead['project_title'] }}" required>
                        </div>

                        <!-- Pages -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="pages">Word</label>
                            <input type="number" class="form-control" id="pages" name="pages" value="{{ $lead['pages'] }}" 
                                min="0" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || /\d/.test(event.key);">
                        </div>

                        <!-- Price -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="price">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $lead['price'] }}"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || event.key === '.' || /\d/.test(event.key);">
                        </div>

                        <!-- Deadline -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ \Carbon\Carbon::parse($lead['deadline'])->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Delivery Time -->
                        <!-- <div class="form-group col-md-6">
                            <label for="delivery_time">Delivery Time</label>
                            <input type="text" class="form-control" id="delivery_time" name="delivery_time" value="{{ $lead['delivery_time'] }}">
                        </div> -->                        

                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="workTypeSelect">Work Type</label>
                            <select required id="workTypeSelect" name="work_type" class="form-control">
                                <option value="">Select Work Type</option>
                                <option value="Standard" {{ $lead['work_type'] == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="First Class Work" {{ $lead['work_type'] == 'First Class Work' ? 'selected' : '' }}>First Class Work</option>
                            </select>
                        </div>
                        
                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="serviceTypeSelect">Service Type</label>
                            <select required id="serviceTypeSelect" name="service_type" class="form-control">
                                <option value="">Select Service Type</option>
                                <option value="Assignment" {{ $lead['service_type'] == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="Dissertation" {{ $lead['service_type'] == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                                <option value="Thesis" {{ $lead['service_type'] == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                                <option value="Research Project" {{ $lead['service_type'] == 'Research Project' ? 'selected' : '' }}>Research Project</option>
                            </select>
                        </div>
                        <!-- Subjects -->
                        <div class="form-group col-md-4 mb-2">
                            <div class="form-group">
                                <label for="subjectSelect">Subject</label>
                                <select required id="subjectSelect" name="subject" class="form-control">
                                    <option value="">Select Subject</option>
                                    <option value="Matlab" {{ $lead['subject'] == 'Matlab' ? 'selected' : '' }}>Matlab</option>
                                    <option value="Data Science" {{ $lead['subject'] == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Engineering" {{ $lead['subject'] == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="App Development" {{ $lead['subject'] == 'App Development' ? 'selected' : '' }}>App Development</option>
                                    <option value="Web Development" {{ $lead['subject'] == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="Exam" {{ $lead['subject'] == 'Exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="Public Health" {{ $lead['subject'] == 'Public Health' ? 'selected' : '' }}>Public Health</option>
                                    <option value="Presentation" {{ $lead['subject'] == 'Presentation' ? 'selected' : '' }}>Presentation (PPT)</option>
                                    <option value="Portfolio" {{ $lead['subject'] == 'Portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="Research Report" {{ $lead['subject'] == 'Research Report' ? 'selected' : '' }}>Research Report</option>
                                    <option value="Business Management" {{ $lead['subject'] == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                                    <option value="Project Management" {{ $lead['subject'] == 'Project Management' ? 'selected' : '' }}>Project Management</option>
                                    <option value="Essay" {{ $lead['subject'] == 'Essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="HRM" {{ $lead['subject'] == 'HRM' ? 'selected' : '' }}>HRM</option>
                                    <option value="Economic" {{ $lead['subject'] == 'Economic' ? 'selected' : '' }}>Economic</option>
                                    <option value="Other" {{ $lead['subject'] == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="assignment_details">Assignment Details</label>
                            <textarea required class="form-control" id="assignment_details" name="assignment_details" rows="4" cols="50">{{ $lead['assignment_details'] }}</textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal" type="button" class="btn btn-secondary">Close</button>
                <button type="submit" form="editLeadForm{{$lead['id']}}" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section End -->

<!-- Convert Lead Modal -->
<div class="modal fade" id="kt_modal_convert_lead{{ $lead['id'] }}" tabindex="-1" aria-labelledby="convertLeadModalLabel{{ $lead['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="convertLeadModalLabel{{ $lead['id'] }}">Convert Lead to Order (<strong class="text-success">Order Code: {{ $lead['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure you want to convert this lead into an order?</p>
                <form id="convertLeadForm{{ $lead['id'] }}" method="POST" action="{{ route('ll.lead.convert', $lead['id']) }}">
                    @csrf
                    <div class="row">
                        <!-- Project Title -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="project_title">Project Title</label>
                            <input type="text" class="form-control" id="project_title" name="project_title" value="{{ $lead['project_title'] }}" required>
                        </div>

                        <!-- Pages -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="pages">Word</label>
                            <input type="number" class="form-control" id="pages" name="pages" value="{{ $lead['pages'] }}" 
                                min="0" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || /\d/.test(event.key);">
                        </div>

                        <!-- Price -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="price">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $lead['price'] }}"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');" 
                                onkeydown="return event.key === 'Backspace' || event.key === 'Tab' || event.key === '.' || /\d/.test(event.key);">
                        </div>

                        <!-- Deadline -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ \Carbon\Carbon::parse($lead['deadline'])->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Delivery Time -->
                        <!-- <div class="form-group col-md-6">
                            <label for="delivery_time">Delivery Time</label>
                            <input type="text" class="form-control" id="delivery_time" name="delivery_time" value="{{ $lead['delivery_time'] }}">
                        </div> -->                        

                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="workTypeSelect">Work Type</label>
                            <select required id="workTypeSelect" name="work_type" class="form-control">
                                <option value="">Select Work Type</option>
                                <option value="Standard" {{ $lead['work_type'] == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="First Class Work" {{ $lead['work_type'] == 'First Class Work' ? 'selected' : '' }}>First Class Work</option>
                            </select>
                        </div>
                        
                        <!-- Work Type -->
                        <div class="form-group col-md-4 mb-2">
                            <label for="serviceTypeSelect">Service Type</label>
                            <select required id="serviceTypeSelect" name="service_type" class="form-control">
                                <option value="">Select Service Type</option>
                                <option value="Assignment" {{ $lead['service_type'] == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="Dissertation" {{ $lead['service_type'] == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                                <option value="Thesis" {{ $lead['service_type'] == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                                <option value="Research Project" {{ $lead['service_type'] == 'Research Project' ? 'selected' : '' }}>Research Project</option>
                            </select>
                        </div>
                        <!-- Subjects -->
                        <div class="form-group col-md-4 mb-2">
                            <div class="form-group">
                                <label for="subjectSelect">Subject</label>
                                <select required id="subjectSelect" name="subject" class="form-control">
                                    <option value="">Select Subject</option>
                                    <option value="Matlab" {{ $lead['subject'] == 'Matlab' ? 'selected' : '' }}>Matlab</option>
                                    <option value="Data Science" {{ $lead['subject'] == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                    <option value="Engineering" {{ $lead['subject'] == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="App Development" {{ $lead['subject'] == 'App Development' ? 'selected' : '' }}>App Development</option>
                                    <option value="Web Development" {{ $lead['subject'] == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="Exam" {{ $lead['subject'] == 'Exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="Public Health" {{ $lead['subject'] == 'Public Health' ? 'selected' : '' }}>Public Health</option>
                                    <option value="Presentation" {{ $lead['subject'] == 'Presentation' ? 'selected' : '' }}>Presentation (PPT)</option>
                                    <option value="Portfolio" {{ $lead['subject'] == 'Portfolio' ? 'selected' : '' }}>Portfolio</option>
                                    <option value="Research Report" {{ $lead['subject'] == 'Research Report' ? 'selected' : '' }}>Research Report</option>
                                    <option value="Business Management" {{ $lead['subject'] == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                                    <option value="Project Management" {{ $lead['subject'] == 'Project Management' ? 'selected' : '' }}>Project Management</option>
                                    <option value="Essay" {{ $lead['subject'] == 'Essay' ? 'selected' : '' }}>Essay</option>
                                    <option value="HRM" {{ $lead['subject'] == 'HRM' ? 'selected' : '' }}>HRM</option>
                                    <option value="Economic" {{ $lead['subject'] == 'Economic' ? 'selected' : '' }}>Economic</option>
                                    <option value="Other" {{ $lead['subject'] == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="form-group col-md-12 mb-2">
                            <label for="assignment_details">Assignment Details</label>
                            <textarea required class="form-control" id="assignment_details" name="assignment_details" rows="4" cols="50">{{ $lead['assignment_details'] }}</textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="convertLeadForm{{ $lead['id'] }}" class="btn btn-primary">Convert Lead</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Lead Modal -->
<div class="modal fade" id="kt_modal_cancel_lead{{ $lead['id'] }}" tabindex="-1" aria-labelledby="cancelLeadModalLabel{{ $lead['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelLeadModalLabel{{ $lead['id'] }}">Cancel Lead (<strong class="text-success">Order Code: {{ $lead['order_id'] }}</strong>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure you want to cancel this lead?</p>
                <form id="cancelLeadForm{{ $lead['id'] }}" method="POST" action="{{ route('ll.lead.cancel', $lead['id']) }}">
                    @csrf
                    <div class="form-group">
                        <label for="cancellation_message">Cancellation Message</label>
                        <textarea class="form-control" id="cancellation_message" name="cancellation_message" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="cancelLeadForm{{ $lead['id'] }}" class="btn btn-danger">Cancel Lead</button>
            </div>
        </div>
    </div>
</div>