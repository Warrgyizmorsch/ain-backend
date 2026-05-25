@extends('layouts.app')
@section('content')

<style>
/* Custom UI adjustments to match the design */
.duplicate-leads-wrapper {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 0.1rem 1rem 0.25rem rgba(0,0,0,0.02); /* very soft shadow */
}

/* Header & Search Form */
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
}
.page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #181c32;
    margin: 0;
}
.custom-search-input {
    border: 1px solid #e4e6ef;
    border-radius: 6px;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    color: #3f4254;
    width: 220px;
    transition: border-color 0.15s ease-in-out;
}
.custom-search-input:focus {
    border-color: #009ef7;
    outline: none;
}
.custom-filter-btn {
    background-color: #009ef7;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: background-color 0.2s;
}
.custom-filter-btn:hover {
    background-color: #008be1;
    color: #ffffff;
}

/* Table Styling */
.table-clean {
    width: 100%;
    margin-bottom: 0;
}
.table-clean th {
    font-weight: 500;
    color: #4b5675;
    padding: 1rem;
    border-bottom: 1px solid #f4f6f8; /* Soft underline for header */
}
.table-clean td {
    padding: 1.5rem 1rem;
    vertical-align: middle;
    color: #3f4254;
    border: none; /* Removing borders to match screenshot */
}

/* Customizing specific badge colors to exactly match the screenshot */
.badge-light-danger {
    background-color: #fff5f8 !important;
    color: #f1416c !important;
}
.badge-light-info { /* specifically mapping 'Beginner' to the purple in screenshot */
    background-color: #f8f5ff !important;
    color: #7239ea !important;
}
</style>

<div class="duplicate-leads-wrapper">
    <div class="header-flex">
        <h2 class="page-title">Duplicate Leads</h2>
        <form method="GET" action="{{ route('duplicate.leads') }}" class="d-flex gap-3 align-items-start">
            <input type="text" name="order_id" value="{{ request('order_id') }}" class="custom-search-input" placeholder="Search Order ID" autocomplete="off">
            <input type="text" name="duplicate_order_id" value="{{ request('duplicate_order_id') }}" class="custom-search-input" placeholder="Duplicate Order ID" autocomplete="off">
            <div class="position-relative">
                <input type="text" id="user_search" name="user_text" value="{{ request('user_text') }}" class="custom-search-input" placeholder="Name, Email, Mobile" autocomplete="off">
                <input type="hidden" name="user_id" id="user_id" value="{{ request('user_id') }}">
                <div id="user_result" class="bg-white border rounded shadow-sm position-absolute w-100" style="display:none; z-index:9999; max-height:220px; overflow-y:auto;"></div>
            </div>
            <button type="submit" class="custom-filter-btn">
                Filter
            </button>
            <a href="{{ route('duplicate.leads') }}" class="btn btn-sm btn-light-danger" style="height:41px; padding:10px 18px;">
                Reset
            </a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-clean align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Order ID</th>
                    <th class="text-center">Duplicate Of</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Order Date</th>
                    <th class="text-center">Project Title</th>
                    <th class="text-center">Words</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Delivery Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $key => $lead)
                    <tr>
                        <td>
                            {{ $leads->firstItem() + $key }}
                        </td>
                        <td class="text-center">
                            @if ($lead['frontendorder'] == '1')
                                <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
                            @else
                                {{ $lead->order_id }}
                            @endif
                            <br>
                            @if ($lead['resit'] == 'on')
                                <span class="badge badge-light-danger fs-7 fw-bold mt-1">Resit Work</span>
                            @endif
                            @if ($lead['service_type'] == 'First Class Work')
                                <span class="badge badge-light-info fs-7 fw-bold mt-1">First Class Work</span>
                            @endif
                        </td>

                        <td>
                            {{ $lead->duplicate_order_id ?? 'N/A' }}
                        </td>

                        <td class="text-center">
                            <span class="d-block text-dark">{{ $lead->user->name ?? 'No Name' }}</span>
                            @if(!empty($lead->user))

                                @php
                                    $count = \App\Models\Order::where('uid', $lead->user->id)->count();

                                    if($count > 10) { 
                                        $class = "badge-light-success"; 
                                        $label = "Loyal Customer"; 
                                    } elseif($count >= 4) { 
                                        $class = "badge-light-warning"; 
                                        $label = "Repeated"; 
                                    } else { 
                                        $class = "badge-light-info"; 
                                        $label = "Beginner"; 
                                    }
                                @endphp
                                <span class="badge {{ $class }} fw-bold fs-8 my-1 px-3 py-2">
                                    {{ $label }}
                                </span><br>
                            @endif
                            <span class="badge badge-light-danger fs-8 fw-bold px-3 py-2">{{ $lead->user->mobile_no ?? '' }}</span>
                        </td>
                        
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}<br>
                            @if($lead->lead_source)
                                <strong class="fs-8 mt-1 d-inline-block">Source:</strong> <span class="fs-8">{{$lead->source->source_name}}</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            {!! $lead->project_title
                            ? e($lead->project_title)
                            : '<span class="badge badge-light-danger fs-8 fw-bold px-3 py-2">No Title</span>' !!}
                            
                            @if ($lead->semester)
                                <br><span class="badge badge-light-success fs-8 mt-1">Semester: {{ $lead->semester }}</span>
                            @endif
                            @if ($lead->tech === 'on')
                                <br><span class="badge badge-light-success fs-8 mt-1">Technical Work</span>
                            @endif
                            @if ($lead->module_code)
                                <br><span class="badge badge-light-danger fs-8 mt-1">{{ $lead->module_code }}</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            {!! $lead->pages ? e($lead->pages) : '<span class="badge badge-light-danger fs-8 fw-bold px-3 py-2">No Pages</span>' !!}
                        </td>
                        
                        <td class="text-center">
                            {!! $lead->price ? e($lead->price) : '<span class="badge badge-light-danger fs-8 fw-bold px-3 py-2">No Price</span>' !!}
                        </td>
                        
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($lead->deadline)->format('d M Y') }}
                            @if ($lead->delivery_time)
                                <br><span class="badge badge-light-info fs-8 fw-bold mt-1">({{ $lead->delivery_time }})</span>
                            @endif

                            @if ($lead->draft_required == 'Yes')
                                <br><span class="badge badge-light-success fs-8 mt-1">{{ $lead->draft_date }}</span>
                                <br><span class="badge badge-light-success fs-8">{{ $lead->draft_time }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            No Duplicate Leads Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5 d-flex justify-content-end">
        {{ $leads->links() }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function () {
    let userTimer = null;
    $('#user_search').on('keyup', function () {
        let search = $(this).val();
        $('#user_id').val('');
        clearTimeout(userTimer);

        if (search.length < 2) {
            $('#user_result').hide().html('');
            return;
        }
        userTimer = setTimeout(function () {
            $.ajax({
                url: "{{ route('search.refer.users') }}",
                type: "GET",
                data: { search: search },
                success: function (users) {
                    let html = '';

                    if (users.length > 0) {
                        users.forEach(function (user) {
                            html += `
                                <div class="user-item px-3 py-2 border-bottom"
                                     style="cursor:pointer;"
                                     data-id="${user.id}"
                                     data-name="${user.name ?? ''}"
                                     data-email="${user.email ?? ''}"
                                     data-mobile="${user.mobile_no ?? ''}">
                                    <strong>${user.name ?? 'No Name'}</strong><br>
                                    <small>${user.email ?? ''} | ${user.mobile_no ?? ''}</small>
                                </div>
                            `;
                        });
                    } else {
                        html = `<div class="px-3 py-2 text-muted">No user found</div>`;
                    }

                    $('#user_result').html(html).show();
                }
            });
        }, 300);
    });

    $(document).on('click', '.user-item', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let mobile = $(this).data('mobile');

        $('#user_id').val(id);
        $('#user_search').val(name + ' - ' + mobile + ' - ' + email);
        $('#user_result').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#user_search, #user_result').length) {
            $('#user_result').hide();
        }
    });

});
</script>
@endsection