@extends('layouts.app')
@section('content')

<style>
/* Custom UI adjustments to match the design */
.report-container {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 0.1rem 1rem 0.25rem rgba(0,0,0,0.05); /* very soft shadow if needed, or remove */
}

/* Form Styles */
.form-label {
    font-weight: 500;
    color: #4b5675;
    margin-bottom: 0.5rem;
}
.custom-input {
    border: 1px solid #e4e6ef;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #3f4254;
    transition: border-color 0.15s ease-in-out;
}
.custom-input:focus {
    border-color: #009ef7;
    outline: none;
    box-shadow: none;
}
.btn-search {
    background-color: #009ef7;
    color: #fff;
    border-radius: 6px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}
.btn-search:hover { background-color: #008be1; color: #fff; }

.btn-reset {
    background-color: #f1416c;
    color: #fff;
    border-radius: 6px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}
.btn-reset:hover { background-color: #d93961; color: #fff; }

/* Table Styles */
.table-clean {
    width: 100%;
    margin-top: 1rem;
}
.table-clean th {
    font-weight: 500;
    color: #181c32;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e4e6ef;
}
.table-clean td {
    padding: 1.2rem 1.5rem;
    vertical-align: middle;
    color: #3f4254;
    border-bottom: 1px solid #f5f8fa;
}
.count-text-btn {
    background: none;
    border: none;
    color: #009ef7;
    font-weight: 500;
    padding: 0;
    font-size: 1rem;
}
.count-text-btn:hover {
    text-decoration: underline;
    color: #008be1;
}

/* Sub-table (Orders) Styles */
.module-order-table thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2;
    border-bottom: 2px solid #eff2f5;
}
.expanded-row-bg {
    background-color: #fcfcfc;
}
</style>

<div class="report-container">
    <div class="mb-8">
        <h3 class="fw-bolder text-dark" style="font-size: 1.5rem;">
            Module Code Report
        </h3>
    </div>

    <!-- Form Section -->
    <form method="GET" action="{{ route('module.code.report') }}" class="mb-10">
        <div class="row g-4 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date"
                       name="from_date"
                       value="{{ request('from_date') }}"
                       class="form-control custom-input">
            </div>

            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date"
                       name="to_date"
                       value="{{ request('to_date') }}"
                       class="form-control custom-input">
            </div>

            <div class="col-md-3 position-relative">
                <label class="form-label">Module Code</label>
                <input type="text"
                       id="module_code_search"
                       class="form-control custom-input"
                       placeholder="Search module code"
                       autocomplete="off"
                       value="{{ request('module_code') }}">

                <input type="hidden"
                       name="module_code"
                       id="module_code"
                       value="{{ request('module_code') }}">

                <div id="module_code_result"
                     class="bg-white border rounded shadow-sm position-absolute w-100"
                     style="display:none; z-index:9999; max-height:220px; overflow-y:auto; top: 100%;">
                </div>
            </div>

            <div class="col-md-3 d-flex gap-3">
                <button type="submit" class="btn btn-search border-0">
                    Search
                </button>
                <a href="{{ route('module.code.report') }}" class="btn btn-reset border-0">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Table Section -->
    <div class="table-responsive mt-5">
        <table class="table-clean">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="70%">Module Code</th>
                    <th width="20%">
                        Count
                        @if($sort == 'desc')
                            <a href="{{ route('module.code.report', ['sort' => 'asc']) }}" class="text-primary text-decoration-none ms-1">
                                ↑
                            </a>
                        @else
                            <a href="{{ route('module.code.report', ['sort' => 'desc']) }}" class="text-primary text-decoration-none ms-1">
                                ↓
                            </a>
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($moduleCodes as $key => $module)
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $module->module_code ?: 'NA' }}
                        </td>
                        <td>
                            <button type="button"
                                    class="count-text-btn toggle-module-orders"
                                    data-target="module-orders-{{ $key }}">
                                {{ $module->total_orders }}
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Nested Orders Table (Hidden by default) -->
                    <tr id="module-orders-{{ $key }}" style="display:none;" class="expanded-row-bg">
                        <td colspan="3" class="p-4 border-bottom">
                            <div class="border rounded-3 p-4 bg-white shadow-sm">
                                <h5 class="mb-4 text-dark fw-bold">
                                    Orders For Module: {{ $module->module_code }}
                                </h5>
                                <div class="table-responsive rounded" style="max-height:500px; overflow-y:auto;">
                                    <table class="table table-borderless table-hover align-middle module-order-table w-100 m-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-muted fw-bold pb-3">#</th>
                                                <th class="text-muted fw-bold pb-3">Order ID</th>
                                                <th class="text-muted fw-bold pb-3">User</th>
                                                <th class="text-muted fw-bold pb-3">Project Title</th>
                                                <th class="text-muted fw-bold pb-3">Price</th>
                                                <th class="text-muted fw-bold pb-3">Deadline</th>
                                                <th class="text-muted fw-bold pb-3 text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top">
                                            @foreach($module->orders as $oKey => $order)
                                                <tr>
                                                    <td class="border-bottom py-3">{{ $oKey + 1 }}</td>
                                                    <td class="border-bottom py-3 fw-bold text-dark">{{ $order->order_id }}</td>
                                                    <td class="border-bottom py-3">
                                                        @if($order->user)
                                                            <div class="text-dark fw-bold">{{ $order->user->name }}</div>
                                                            @if(!empty($order->user->client_review))
                                                            <span class="duplicate-info-wrapper d-inline-block mt-1">
                                                                <a class="text-info" style="font-size:13px;">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </a>
                                                                <div class="duplicate-popup shadow">
                                                                    <span>{{$order->user->client_review}}</span>
                                                                </div>
                                                            </span>
                                                            @endif
                                                            
                                                            @php
                                                                $count = \App\Models\Order::where('uid', optional($order->user)->id)->count();
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

                                                            <div class="mt-1">
                                                                <span class="badge {{ $class }} fw-bold fs-8">{{ $label }}</span>
                                                            </div>
                                                            <div class="text-muted fs-7 mt-1">+{{ $order->user->countrycode }} {{ $order->user->mobile_no }}</div>
                                                            <div class="text-muted fs-7">{{ $order->user->email }}</div>
                                                        @else
                                                            <span class="badge badge-light-danger fs-7 fw-bold">User Was Deleted</span>
                                                        @endif
                                                    </td>
                                                    <td class="border-bottom py-3" style="min-width: 200px;">
                                                        <div class="text-dark">
                                                            {!! $order->title ?: '<span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>' !!}
                                                        </div>
                                                        @if($order->semester)
                                                            <div class="text-muted fs-7 mt-1">Semester: ({{ $order->semester }})</div>
                                                        @endif
                                                        <div class="mt-1 d-flex flex-wrap gap-1">
                                                            @if($order->chapter)
                                                                <span class="badge badge-light-danger fs-8 fw-bold">{{ $order->chapter }}</span>
                                                            @endif
                                                            @if($order->tech == '1')
                                                                <span class="badge badge-light-success fs-8 fw-bold">Technical Work</span>
                                                            @endif
                                                            @if($order->module_code)
                                                                <span class="badge badge-light-primary fs-8 fw-bold">{{ $order->module_code }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="border-bottom py-3 text-dark fw-bold">{{ $order->amount }}</td>
                                                    <td class="border-bottom py-3 text-muted">{{ $order->delivery_date }}</td>
                                                    <td class="border-bottom py-3 text-center">
                                                        @switch($order->projectstatus)
                                                            @case('Other')
                                                                <span class="badge fs-7 fw-bold" style="background:#44f2e4; color:black">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Pending')
                                                            @case('Initiated')
                                                                <span class="badge fs-7 fw-bold" style="background:pink; color:white">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('In Progress')
                                                                <span class="badge badge-light-info fs-7 fw-bold">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Hold Work')
                                                            @case('Hold(writer query)')
                                                            @case('Cancelled')
                                                                <span class="badge badge-light-danger fs-7 fw-bold">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Completed')
                                                            @case('Draft Ready')
                                                                <span class="badge fs-7 fw-bold" style="background:#eaea00; color:black">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Delivered')
                                                            @case('Draft Delivered')
                                                                <span class="badge fs-7 fw-bold" style="background:green; color:white">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Feedback')
                                                            @case('Feedback Delivered')
                                                                <span class="badge fs-7 fw-bold" style="background:black; color:white">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @case('Advance Assignment')
                                                                <span class="badge fs-7 fw-bold" style="background:#44f2e4; color:black">{{ $order->projectstatus }}</span>
                                                                @break
                                                            @default
                                                                <span class="badge badge-light-danger fs-7 fw-bold">Not Available</span>
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            No module codes found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-4 d-flex justify-content-end">
            {{ $moduleCodes->links() }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-module-orders')) {
        let targetId = e.target.getAttribute('data-target');
        let row = document.getElementById(targetId);

        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    }
});

$(document).ready(function () {

    let moduleTimer = null;

    $('#module_code_search').on('keyup', function () {
        let search = $(this).val();

        $('#module_code').val('');
        clearTimeout(moduleTimer);

        if (search.length < 2) {
            $('#module_code_result').hide().html('');
            return;
        }

        moduleTimer = setTimeout(function () {
            $.ajax({
                url: "{{ route('search.module.codes') }}",
                type: "GET",
                data: {
                    search: search
                },
                success: function (codes) {
                    let html = '';

                    if (codes.length > 0) {
                        codes.forEach(function (code) {
                            html += `
                                <div class="module-code-item px-3 py-2 border-bottom bg-white"
                                     style="cursor:pointer;"
                                     data-code="${code}">
                                    ${code}
                                </div>
                            `;
                        });
                    } else {
                        html = `<div class="px-3 py-2 text-muted bg-white">No module code found</div>`;
                    }

                    $('#module_code_result').html(html).show();
                },
                error: function (xhr) {
                    console.log('Module search error:', xhr.responseText);
                }
            });
        }, 300);
    });

    $(document).on('click', '.module-code-item', function () {
        let code = $(this).data('code');

        $('#module_code_search').val(code);
        $('#module_code').val(code);
        $('#module_code_result').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#module_code_search, #module_code_result').length) {
            $('#module_code_result').hide();
        }
    });

});
</script>

@endsection