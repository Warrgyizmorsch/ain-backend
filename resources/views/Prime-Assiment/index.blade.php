@extends('layouts.app')
@section('content')
<style>
    #kt_docs_quill_autosave { height: 400px; }
    .modal-dialog { max-width: 70%; }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        
        <div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter Leads</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <form action="">
                        <div class="row mb-3">
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <input value="{{ request('name') }}" type="text" name="name" class="form-control form-control-solid" placeholder="Search By Name "/>
                            </div>
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <input value="{{ request('email') }}" type="text" name="email" class="form-control form-control-solid" placeholder="Search By Email "/>
                            </div>
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <button type='submit' class="btn btn-sm btn-primary">Search</button>
                                <a href="{{ url('/prime-leads') }}" class="btn btn-sm btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Leads List
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Prime Assignment</small>
                </div>
                <div class="d-flex align-items-center py-1">
                    <button class="btn btn-sm btn-primary" onclick="openLeadModal('create')" data-bs-toggle="modal" data-bs-target="#kt_modal_lead_form">Create Lead</button>
                </div>
            </div>
        </div>

        <div class="row gy-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">All Leads</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Recent website submissions</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" style="white-space: nowrap;">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-200px ps-4">Client Info</th>
                                        <th class="min-w-150px">Services</th>
                                        <th class="min-w-150px">Subject</th>
                                        <th class="min-w-150px">Work Type</th>
                                        <th class="min-w-120px">Deadline</th>
                                        <th class="min-w-120px">Word Count</th>
                                        <th class="min-w-150px">Topic</th>
                                        <th class="min-w-250px">Specify Requirements</th>
                                        <th class="min-w-100px">Source</th>
                                        <th class="min-w-150px">Date & Time</th>
                                        <th class="min-w-100px text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leads as $lead)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $lead->name ?? 'N/A' }}</a>
                                            <span class="text-muted fw-bold d-block fs-7 mt-1">{{ $lead->email ?? 'N/A' }}</span>
                                            <span class="text-muted fw-bold d-block fs-7">{{ $lead->country ?? '' }} {{ $lead->mobile_number ?? 'N/A' }}</span>
                                        </td>
                                        <td><span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->services ?? 'N/A' }}</span></td>
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->subject ?? 'N/A' }}</span></td>
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->work_type ?? 'N/A' }}</span></td>
                                        <td><span class="badge badge-light-danger fs-8 fw-bolder">{{ $lead->select_urgency ?? 'N/A' }}</span></td>
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->word_count ?? 'N/A' }}</span></td>
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->enter_topic ?? 'N/A' }}</span></td>
                                        <td>
                                            <span class="text-muted fs-7 fw-bold" title="{{ $lead->requirements ?? '' }}">
                                                {{ \Illuminate\Support\Str::limit($lead->requirements ?? 'N/A', 40, '...') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!empty($lead->source_url))
                                                <a href="{{ $lead->source_url }}" target="_blank" class="btn btn-sm btn-light-primary px-3 py-2">View</a>
                                            @else
                                                <span class="badge badge-light-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">{{ !empty($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->format('d M, Y') : 'N/A' }}</span>
                                            <span class="text-muted fs-7 fw-bold">{{ !empty($lead->created_at) ? \Carbon\Carbon::parse($lead->created_at)->format('h:i A') : '' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                
                                                <button onclick="openLeadModal('edit', {{ json_encode($lead) }})" data-bs-toggle="modal" data-bs-target="#kt_modal_lead_form" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path><path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path></svg>
                                                    </span>
                                                </button>

                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_convert_lead_{{ $lead->id }}" title="Convert to Order">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                                        </svg>
                                                    </span>
                                                </a>

                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_cancel_lead_{{ $lead->id }}" title="Cancel Lead">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                        </svg>
                                                    </span>
                                                </a>

                                                <a data-bs-toggle="modal" data-bs-target="#kt_modal_delete_{{ $lead->id }}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path><path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path><path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path></svg>
                                                    </span>
                                                </a>

                                                <div class="modal fade" id="kt_modal_delete_{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog w-50">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start" style="white-space: normal;">
                                                                Are you sure you want to delete this lead from <strong>{{ $lead->name ?? 'Unknown' }}</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ url('/delete-lead/'.$lead->id) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="kt_modal_convert_lead_{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog w-50">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Convert Lead 
                                                                    {{-- (Lead ID: <span class="text-success">PA{{ $lead->id }}</span>) --}}
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start" style="white-space: normal;">
                                                                <p class="fs-5 text-dark fw-bolder">Are you sure you want to convert this lead into an order?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ url('/convert-lead/'.$lead->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success">Convert to Order</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="kt_modal_cancel_lead_{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog w-50">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Cancel Lead 
                                                                    {{-- <span class="text-success">(Lead ID: LT{{ $lead->id }})</span> --}}
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ url('/cancel-lead/'.$lead->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body text-start" style="white-space: normal;">
                                                                    <p class="fs-5 text-dark fw-bolder">Are you sure you want to cancel this lead?</p>
                                                                    
                                                                    <div class="mt-3">
                                                                        <label class="form-label text-dark">Cancellation Message</label>
                                                                        <textarea name="cancel_message" class="form-control" rows="4" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn text-white" style="background-color: #e83e8c;">Cancel Lead</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-5">No leads available yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            {{ $leads->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_lead_form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Create New Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="leadForm" action="{{ url('/store-lead') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Your Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" placeholder="+44 - UK">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Number">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Services</label>
                            <select name="services" class="form-select">
                                <option value="">Select Service</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Dissertation">Dissertation</option>
                                <option value="Thesis">Thesis</option>
                                <option value="Research Project">Research Project</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Subject</label>
                            <select name="subject" class="form-select">
                                <option value="">Select Subject</option>
                                <option value="Matlab">Matlab</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Engineering">Engineering</option>
                                <option value="App Development">App Development</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Exam">Exam</option>
                                <option value="Public Health">Public Health</option>
                                <option value="Presentation (PPT)">Presentation (PPT)</option>
                                <option value="Portfolio">Portfolio</option>
                                <option value="Research Report">Research Report</option>
                                <option value="Business Management">Business Management</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Essay">Essay</option>
                                <option value="HRM">HRM</option>
                                <option value="Economic">Economic</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Work Type</label>
                            <select name="work_type" class="form-select">
                                <option value="">Select Work Type</option>
                                <option value="Standard">Standard</option>
                                <option value="First Class Work">First Class Work</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Select Urgency</label>
                            <select name="select_urgency" class="form-select">
                                <option value="">Select Urgency</option>
                                <option value="1 Day">1 Day</option>
                                <option value="2 Days">2 Days</option>
                                <option value="3 Days">3 Days</option>
                                <option value="4 Days">4 Days</option>
                                <option value="5 Days">5 Days</option>
                                <option value="7 Days">7 Days</option>
                                <option value="10 Days">10 Days</option>
                                <option value="14 Days">14 Days</option>
                                <option value="21+ Days">21+ Days</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Word Count</label>
                            <input type="text" name="word_count" class="form-control" placeholder="Enter Word Count (1 Page = 250 Words)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Enter Topic</label>
                            <input type="text" name="enter_topic" class="form-control" placeholder="Enter Topic">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Specify Your Requirements Here</label>
                            <textarea name="requirements" class="form-control" rows="3" placeholder="The more instructions you provide, the better our writers can craft a paper that meets your needs."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Source URL</label>
                            <input type="text" name="source_url" class="form-control" placeholder="https://primeassignmenthelp.co.uk/">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openLeadModal(mode, leadData = null) {
        const form = document.getElementById('leadForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const submitBtn = document.getElementById('submitBtn');

        if (mode === 'create') {
            // Setup form for Creating
            modalTitle.innerText = 'Create New Lead';
            form.action = "{{ url('/store-lead') }}";
            methodField.value = 'POST';
            submitBtn.innerText = 'Save Lead';
            
            // Clear all fields
            form.reset();
            // Set default source URL for Prime
            form.querySelector('[name="source_url"]').value = 'https://primeassignmenthelp.co.uk/';
            
        } else if (mode === 'edit' && leadData) {
            // Setup form for Editing
            modalTitle.innerText = 'Edit Lead: ' + (leadData.name || '');
            form.action = "{{ url('/update-lead') }}/" + leadData.id;
            methodField.value = 'PUT';
            submitBtn.innerText = 'Update Lead';

            // Populate fields with existing data
            form.querySelector('[name="name"]').value = leadData.name || '';
            form.querySelector('[name="email"]').value = leadData.email || '';
            form.querySelector('[name="country"]').value = leadData.country || '';
            form.querySelector('[name="mobile_number"]').value = leadData.mobile_number || '';
            form.querySelector('[name="services"]').value = leadData.services || '';
            form.querySelector('[name="subject"]').value = leadData.subject || '';
            form.querySelector('[name="work_type"]').value = leadData.work_type || '';
            form.querySelector('[name="select_urgency"]').value = leadData.select_urgency || '';
            form.querySelector('[name="word_count"]').value = leadData.word_count || '';
            form.querySelector('[name="enter_topic"]').value = leadData.enter_topic || '';
            form.querySelector('[name="requirements"]').value = leadData.requirements || '';
            form.querySelector('[name="source_url"]').value = leadData.source_url || '';
        }
    }
</script>

@endsection