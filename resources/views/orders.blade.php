@extends('layouts.app')
@section('content')
<style>
    .modal-dialog { max-width: 70%; }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        
        <div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter Orders</span>
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
                                <a href="{{ request()->url() }}" class="btn btn-sm btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Orders List
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Converted from Leads</small>
                </div>
                <div class="d-flex align-items-center py-1">
                    <a href="{{ url('/smart-leads') }}" class="btn btn-sm btn-light-primary">Back to Leads</a>
                </div>
            </div>
        </div>

        <div class="row gy-5 g-xl-8 mt-2">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1 text-success">All Orders</span>
                            <span class="text-muted mt-1 fw-bold fs-7">All successfully converted leads</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" style="white-space: nowrap;">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-200px ps-4">Client Info</th>
                                        <th class="min-w-150px">Services</th>
                                        <th class="min-w-150px">Subject & Type</th>
                                        <th class="min-w-120px">Deadline</th>
                                        <th class="min-w-120px">Word Count</th>
                                        <th class="min-w-150px">Topic</th>
                                        <th class="min-w-250px">Requirements</th>
                                        <th class="min-w-100px">Source</th>
                                        <th class="min-w-150px">Converted Date</th>
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
                                        <td><span class="badge badge-light-success fs-7 fw-bold">{{ $lead->services ?? 'N/A' }}</span></td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">{{ $lead->subject ?? 'N/A' }}</span>
                                            <span class="text-muted fw-bold d-block fs-7">{{ $lead->work_type ?? 'N/A' }}</span>
                                        </td>
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
                                            <span class="text-success fw-bolder d-block fs-6">{{ !empty($lead->processed_at) ? \Carbon\Carbon::parse($lead->processed_at)->format('d M, Y') : 'N/A' }}</span>
                                            <span class="text-muted fs-7 fw-bold">{{ !empty($lead->processed_at) ? \Carbon\Carbon::parse($lead->processed_at)->format('h:i A') : '' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                <button onclick="openLeadModal('edit', {{ json_encode($lead) }})" data-bs-toggle="modal" data-bs-target="#kt_modal_lead_form" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Order Details">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                                                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5">No new orders have been received.</td>
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
                <h5 class="modal-title" id="modalTitle">Edit Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="leadForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="PUT">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country Code</label>
                            <input type="text" name="country" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control">
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
                            <label class="form-label">Select Urgency (Deadline)</label>
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
                            <input type="text" name="word_count" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Enter Topic</label>
                            <input type="text" name="enter_topic" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Specify Your Requirements Here</label>
                            <textarea name="requirements" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Source URL</label>
                            <input type="text" name="source_url" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Update Order</button>
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

        if (mode === 'edit' && leadData) {
            modalTitle.innerText = 'Edit Order: ' + (leadData.name || '');
            form.action = "{{ url('/update-lead') }}/" + leadData.id;
            methodField.value = 'PUT';
            submitBtn.innerText = 'Update Order';

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