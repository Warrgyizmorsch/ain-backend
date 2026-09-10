@extends('layouts.app')
@section('content')
<style>
    .shadow-sm {
        display: none;
    }
    .text-gray-700 {
        margin-top: revert;
    }

    /* Eliminate Metronic toolbar-fixed empty gap */
    #kt_content,
    .content {
        padding-top: 0 !important;
    }
    .toolbar,
    #kt_toolbar {
        margin-bottom: 0 !important;
    }
    .cancel-lead-filter-card {
        margin-top: 0 !important;
    }

    /* Clean table borders like in Orders & Follow-Up */
    .cleads-table th, 
    .cleads-table td {
        border: 1px solid #e4e6ef !important;
        vertical-align: middle;
    }
    .cleads-table thead th {
        background-color: #f5f8fa !important;
        color: #3f4254 !important;
        font-weight: 700 !important;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .cleads-table tbody tr:hover {
        background-color: #f8fbff !important;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Cancel Leads
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mt-2">
            @include('leads.section.cancel-lead-filter')
        </div>
        
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Cancel Leads</span>
                        <span class="text-muted mt-1 fw-bold fs-7">Total {{ $status1Leads->total() }} leads found</span>
                    </h3>
                </div>
                
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle gs-3 gy-3 border mb-0 cleads-table">
                            <thead>
                                <tr class="fw-bolder text-dark bg-light border-bottom border-gray-300">
                                    <th class="text-center px-2" style="width: 50px;">No</th>
                                    <th class="text-center" style="width: 140px;">Action</th>
                                    <th style="width: 130px;">Order ID</th>
                                    <th style="min-width: 180px;">Customer</th>
                                    <th style="min-width: 170px;">Mobile</th>
                                    <th class="text-center" style="width: 110px;">Order Date</th>
                                    <th style="min-width: 200px;">Project Title</th>
                                    <th class="text-center" style="width: 90px;">Words</th>
                                    <th class="text-center" style="width: 90px;">Price</th>
                                    <th class="text-center" style="width: 110px;">Delivery Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($status1Leads as $lead)
                                    <tr>
                                        <td class="text-center fw-bold text-gray-700">
                                            {{ ($status1Leads->currentPage() - 1) * $status1Leads->perPage() + $loop->iteration }}
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                                <div class="form-check form-switch my-auto me-1">
                                                    <input class="form-check-input" type="checkbox" id="{{ $lead->id }}" role="switch" unchecked onchange="handleChange(this, {{ $lead->id }})" title="Toggle Lead">
                                                </div>

                                                <a href="#" data-kt-drawer-toggle="#kt_drawer_chat"
                                                   id="kt_drawer_chat_toggle{{ $lead->id }}"
                                                   class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1" title="Call">
                                                    <i class="fa fa-phone fs-7 text-white"></i>
                                                </a>
                                                @include('leads.section.call-lead')

                                                @if(auth()->user()->role_id === 1)
                                                    <a href="#" id="{{ $lead->id }}" class="btn btn-icon btn-bg-danger btn-active-color-light btn-sm delete-link" title="Delete">
                                                        <span class="svg-icon svg-icon-3">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-inline-flex align-items-center">
                                                <span class="fw-bolder text-primary">{{ $lead->order_id }}</span>
                                                @if(!empty($lead->order_id))
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $lead->order_id }}', 'Order ID copied!');">
                                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        @php
                                            $leadUser = $lead->user;
                                            $rawLeadName = $leadUser->name ?? $lead->user_name ?? '';
                                            $rawLeadEmail = $leadUser->email ?? $lead->email ?? '';
                                            $leadCC = $leadUser->countrycode ?? $lead->countrycode ?? '';
                                            $cleanLeadCC = preg_replace('/\D+/', '', (string)$leadCC);
                                            $rawLeadMobile = $leadUser->mobile_no ?? $lead->mobile ?? '';
                                            $displayLeadEmail = $rawLeadEmail ? mask_email_for_display($rawLeadEmail) : '';
                                            $displayLeadMobile = $rawLeadMobile ? mask_mobile_only($leadCC, $rawLeadMobile) : '';
                                        @endphp
                                        <td>
                                            @if(!empty($rawLeadName))
                                                <div class="d-inline-flex align-items-center">
                                                    <span class="fw-bold text-dark">{{ $rawLeadName }}</span>
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Name" onclick="event.stopPropagation(); crmCopyToClipboard('{{ addslashes($rawLeadName) }}', 'Customer name copied!');">
                                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            @if(!empty($displayLeadEmail))
                                                <div class="d-inline-flex align-items-center my-1">
                                                    <span class="text-muted fs-8 text-break">{{ $displayLeadEmail }}</span>
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayLeadEmail }}', 'Email copied!');">
                                                        <i class="fa fa-clone fs-8 text-muted"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if(!empty($displayLeadMobile))
                                                <div class="d-inline-flex align-items-center gap-1 my-1">
                                                    @if(!empty($cleanLeadCC))
                                                        <span class="badge badge-light-primary fs-8 fw-bold">+{{ $cleanLeadCC }}</span>
                                                    @endif
                                                    <span class="badge badge-light-danger fs-7 fw-bold">{{ $displayLeadMobile }}</span>
                                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayLeadMobile }}', 'Mobile number copied!');">
                                                        <i class="fa fa-clone fs-8 text-danger"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center fs-7 text-gray-600">
                                            {{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}
                                        </td>
                                        
                                        <td class="fs-7 text-dark fw-bold">
                                            {{ $lead->project_title }}
                                        </td>

                                        <td class="text-center fs-7">
                                            {{ $lead->pages }}
                                        </td>

                                        <td class="text-center fw-bolder text-success fs-7">
                                            {{ $lead->price }}
                                        </td>

                                        <td class="text-center fs-7 text-gray-600">
                                            {{ $lead->deadline }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 px-2">
                            <div class="text-muted fs-7">
                                Showing {{ $status1Leads->firstItem() ?? 0 }} to {{ $status1Leads->lastItem() ?? 0 }} of {{ $status1Leads->total() }} entries
                            </div>
                            <div>
                                {{ $status1Leads->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@include('leads.section.cancelleadsscript')

@endsection