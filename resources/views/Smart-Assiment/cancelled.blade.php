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
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">Koi cancelled lead nahi hai.</td>
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