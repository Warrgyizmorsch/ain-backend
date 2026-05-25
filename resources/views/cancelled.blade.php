@extends('layouts.app')
@section('content')
<style>
    .modal-dialog { max-width: 70%; }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Cancelled Leads
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Rejected Submissions</small>
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
                            <span class="card-label fw-bolder fs-3 mb-1 text-danger">Cancelled Leads</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Leads with their cancellation reasons</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" style="white-space: nowrap;">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-200px ps-4">Client Info</th>
                                        <th class="min-w-150px">Services & Type</th>
                                        <th class="min-w-250px text-danger">Cancel Reason</th>
                                        <th class="min-w-150px">Topic</th>
                                        <th class="min-w-120px">Word Count</th>
                                        <th class="min-w-100px">Source</th>
                                        <th class="min-w-150px pe-4">Cancelled Date</th>
                                        <th class="min-w-100px text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leads as $lead)
                                    <tr class="bg-light-danger" style="opacity: 0.85;">
                                        <td class="ps-4">
                                            <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ $lead->name ?? 'N/A' }}</a>
                                            <span class="text-muted fw-bold d-block fs-7 mt-1">{{ $lead->email ?? 'N/A' }}</span>
                                            <span class="text-muted fw-bold d-block fs-7">{{ $lead->country ?? '' }} {{ $lead->mobile_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bolder d-block fs-6">{{ $lead->services ?? 'N/A' }}</span>
                                            <span class="text-muted fw-bold d-block fs-7">{{ $lead->work_type ?? 'N/A' }}</span>
                                        </td>
                                        
                                        <td>
                                            <div class="border border-danger border-dashed rounded p-2 bg-white">
                                                <span class="text-danger fw-bolder fs-7">{{ $lead->cancel_reason ?? 'No reason provided.' }}</span>
                                            </div>
                                        </td>
                                        
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->enter_topic ?? 'N/A' }}</span></td>
                                        <td><span class="text-dark fw-bolder d-block fs-6">{{ $lead->word_count ?? 'N/A' }}</span></td>
                                        
                                        <td>
                                            @if(!empty($lead->source_url))
                                                <a href="{{ $lead->source_url }}" target="_blank" class="btn btn-sm btn-light-primary px-3 py-2">View</a>
                                            @else
                                                <span class="badge badge-light-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <span class="text-danger fw-bolder d-block fs-6">{{ !empty($lead->processed_at) ? \Carbon\Carbon::parse($lead->processed_at)->format('d M, Y') : 'N/A' }}</span>
                                            <span class="text-muted fs-7 fw-bold">{{ !empty($lead->processed_at) ? \Carbon\Carbon::parse($lead->processed_at)->format('h:i A') : '' }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_restore_lead_{{ $lead->id }}" title="Restore to Leads">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                        </svg>
                                                    </span>
                                                </a>

                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_permanent_delete_{{ $lead->id }}" title="Delete Permanently">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
                                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
                                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="kt_modal_restore_lead_{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog w-50">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Restore Lead (ID: <span class="text-primary">LT{{ $lead->id }}</span>)</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start" style="white-space: normal;">
                                                    <p class="fs-5 text-dark fw-bolder">Do you want to restore this lead back to the New Leads list?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ url('/restore-lead/'.$lead->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">Yes, Restore Lead</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="kt_modal_permanent_delete_{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog w-50">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Permanently</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start" style="white-space: normal;">
                                                    <div class="alert alert-danger d-flex align-items-center p-5 mb-0">
                                                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"/><rect x="11" y="14" width="2" height="2" rx="1" fill="black"/><rect x="11" y="7" width="2" height="5" rx="1" fill="black"/></svg>
                                                        </span>
                                                        <div class="d-flex flex-column">
                                                            <h4 class="mb-1 text-danger">Warning!</h4>
                                                            <span>Are you sure you want to permanently delete this lead (<strong>{{ $lead->name ?? 'Unknown' }}</strong>)? This action cannot be undone.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ url('/delete-lead/'.$lead->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">There are no cancelled leads.</td>
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
@endsection