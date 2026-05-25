@extends('layouts.app')

@section('content')

{{-- Search / Filter Card --}}
<div class="card mb-5" style="border: 0.5px solid #e4e4e4; border-radius: 12px; box-shadow: none;">
    <div class="card-body p-6">
        <div class="row g-4 align-items-end">

            <div class="col-md-3">
                <label class="d-block mb-2" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#888;">Search</label>
                <div class="d-flex align-items-center" style="background:#f5f5f5; border:0.5px solid #ddd; border-radius:8px; height:40px; padding:0 12px; gap:8px;">
                    <i class="bi bi-search" style="font-size:14px; color:#aaa;"></i>
                    <input type="text" id="orderSearch" placeholder="Order ID / Title"
                        style="background:transparent; border:none; outline:none; font-size:13px; color:#333; width:100%;">
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block mb-2" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#888;">From Date</label>
                <div class="d-flex align-items-center" style="background:#f5f5f5; border:0.5px solid #ddd; border-radius:8px; height:40px; padding:0 12px; gap:8px;">
                    <i class="bi bi-calendar3" style="font-size:14px; color:#aaa;"></i>
                    <input type="date" id="fromDate"
                        style="background:transparent; border:none; outline:none; font-size:13px; color:#333; width:100%;">
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block mb-2" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#888;">To Date</label>
                <div class="d-flex align-items-center" style="background:#f5f5f5; border:0.5px solid #ddd; border-radius:8px; height:40px; padding:0 12px; gap:8px;">
                    <i class="bi bi-calendar3-range" style="font-size:14px; color:#aaa;"></i>
                    <input type="date" id="toDate"
                        style="background:transparent; border:none; outline:none; font-size:13px; color:#333; width:100%;">
                </div>
            </div>

            <div class="col-md-3">
                <label class="d-block mb-2" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:transparent;">Actions</label>
                <div class="d-flex gap-2">
                    <button id="searchBtn"
                        style="flex:1; height:40px; background:#378ADD; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:500; display:inline-flex; align-items:center; justify-content:center; gap:6px; cursor:pointer;">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <button id="clearBtn" aria-label="Clear filters"
                        style="height:40px; width:40px; background:#f5f5f5; border:0.5px solid #ddd; border-radius:8px; font-size:15px; color:#888; display:inline-flex; align-items:center; justify-content:center; cursor:pointer;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Paper Type Tabs Card --}}
<div class="card mb-5" style="border: 0.5px solid #e4e4e4; border-radius: 12px; box-shadow: none;">
    <div class="card-body p-6">
        <div class="mb-4">
            <h3 style="font-size:15px; font-weight:500; color:#111; margin-bottom:3px;">Types of Paper</h3>
            <p style="font-size:12px; color:#999; margin:0;">Click a paper type to filter orders</p>
        </div>

        <div class="d-flex gap-2 pb-2" style="overflow-x:auto;">

            {{-- All Tab --}}
            <div class="paper-tab active" data-id="all"
                style="flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:5px;
                       padding:10px 20px; border-radius:8px; cursor:pointer; transition:all .18s;
                       border:0.5px solid #378ADD; background:#EBF4FD; min-width:72px;">
                <span class="tab-label" style="font-size:12px; font-weight:500; color:#185FA5;">All</span>
                <span id="count-all"
                    style="font-size:11px; font-weight:500; padding:2px 9px; border-radius:99px; background:#378ADD; color:#fff;">{{ $allCount }}</span>
            </div>

            @foreach($paperTypes as $type)
            <div class="paper-tab" data-id="{{ $type->id }}"
                style="flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:5px;
                       padding:10px 20px; border-radius:8px; cursor:pointer; transition:all .18s;
                       border:0.5px solid #e4e4e4; background:#f9f9f9; min-width:72px;">
                <span class="tab-label" style="font-size:12px; font-weight:500; color:#555;">{{ $type->paper_type }}</span>
                <span id="count-{{ $type->id }}"
                    style="font-size:11px; font-weight:500; padding:2px 9px; border-radius:99px; background:#e8e8e8; color:#666;">{{ $type->total_orders }}</span>
            </div>
            @endforeach

        </div>
    </div>
</div>

{{-- Orders Table Card --}}
<div class="card" style="border: 0.5px solid #e4e4e4; border-radius: 12px; box-shadow: none;">

    <div class="card-header bg-white" style="border-bottom: 0.5px solid #e4e4e4; border-radius: 12px 12px 0 0; padding: 1rem 1.5rem;">
        <div class="d-flex align-items-center gap-3">
            <div style="width:38px; height:38px; border-radius:50%; background:#EBF4FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-file-earmark-text" style="font-size:17px; color:#185FA5;"></i>
            </div>
            <div>
                <h3 id="orderListTitle" style="font-size:15px; font-weight:500; color:#111; margin:0;">All Orders</h3>
                <p style="font-size:12px; color:#999; margin:0;">Paper type wise order list</p>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0" style="font-size:13px; border-collapse:collapse; width:100%;">
                <thead>
                    <tr style="background:#f9f9f9;">
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap; width:48px;">Sr.</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">Order ID</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">User</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">Paper Type</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">Project Title</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">Order Date</th>
                        <th style="padding:10px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#999; border-bottom:0.5px solid #e4e4e4; white-space:nowrap;">Amount</th>
                    </tr>
                </thead>
                <tbody id="paperTypeOrderList">
                    <tr>
                        <td colspan="7" style="padding:3rem; text-align:center; color:#aaa; font-size:13px;">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm text-primary"></span>
                                <span>Loading...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="paperTypePagination" style="padding:12px 16px; border-top:0.5px solid #e4e4e4; display:flex; justify-content:flex-end;"></div>
    </div>
</div>

<style>
.paper-tab:hover {
    border-color: #378ADD !important;
    background: #EBF4FD !important;
}
.paper-tab:hover .tab-label {
    color: #185FA5 !important;
}
#paperTypeOrderList tr:hover td {
    background: #f9f9f9;
}
#paperTypeOrderList td {
    padding: 11px 16px;
    border-bottom: 0.5px solid #f0f0f0;
    vertical-align: middle;
    color: #333;
    transition: background .12s;
}
#paperTypeOrderList tr:last-child td {
    border-bottom: none;
}
#searchBtn:hover { opacity: .88; }
#clearBtn:hover { background: #eee !important; }
</style>

@endsection

@push('scripts')
<script>
let selectedPaperType = 'all';

function loadOrders(page = 1) {
    $('#paperTypeOrderList').html(`
        <tr>
            <td colspan="7" style="padding:3rem; text-align:center; color:#aaa; font-size:13px;">
                <div class="d-flex flex-column align-items-center gap-2">
                    <span class="spinner-border spinner-border-sm text-primary"></span>
                    <span>Loading...</span>
                </div>
            </td>
        </tr>
    `);

    $('#paperTypePagination').html('');

    $.ajax({
        url: "{{ route('paper.type.report.orders') }}",
        type: "GET",
        data: {
            paper_type: selectedPaperType,
            order: $('#orderSearch').val(),
            from_date: $('#fromDate').val(),
            to_date: $('#toDate').val(),
            page: page
        },
        success: function (response) {
            $('#paperTypeOrderList').html(response.html);
            $('#paperTypePagination').html(response.pagination);

            $('#count-all').text(response.all_count);

            if (response.counts) {
                $.each(response.counts, function (typeId, count) {
                    $('#count-' + typeId).text(count);
                });
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            $('#paperTypeOrderList').html(`
                <tr>
                    <td colspan="7" style="padding:2rem; text-align:center; color:#dc3545; font-size:13px;">
                        Something went wrong. Please try again.
                    </td>
                </tr>
            `);
        }
    });
}

$(document).on('click', '.pagination-btn', function () {
    let page = $(this).data('page');
    loadOrders(page);
});

$(document).on('click', '.paper-tab', function () {
    selectedPaperType = $(this).data('id');

    // Reset all tabs
    $('.paper-tab').each(function () {
        $(this).css({
            'border': '0.5px solid #e4e4e4',
            'background': '#f9f9f9'
        });
        $(this).find('.tab-label').css('color', '#555');
        $(this).find('span[id^="count-"]').css({ 'background': '#e8e8e8', 'color': '#666' });
    });

    // Activate selected tab
    $(this).css({
        'border': '0.5px solid #378ADD',
        'background': '#EBF4FD'
    });
    $(this).find('.tab-label').css('color', '#185FA5');
    $(this).find('span[id^="count-"]').css({ 'background': '#378ADD', 'color': '#fff' });

    let title = $(this).find('.tab-label').first().text().trim();
    $('#orderListTitle').text(title + ' Orders');

    loadOrders();
});

$(document).on('click', '#searchBtn', function () {
    loadOrders();
});

$(document).on('click', '#clearBtn', function () {
    $('#orderSearch').val('');
    $('#fromDate').val('');
    $('#toDate').val('');

    selectedPaperType = 'all';

    // Reset all tabs
    $('.paper-tab').each(function () {
        $(this).css({
            'border': '0.5px solid #e4e4e4',
            'background': '#f9f9f9'
        });
        $(this).find('.tab-label').css('color', '#555');
        $(this).find('span[id^="count-"]').css({ 'background': '#e8e8e8', 'color': '#666' });
    });

    // Activate All tab
    $('.paper-tab[data-id="all"]').css({
        'border': '0.5px solid #378ADD',
        'background': '#EBF4FD'
    });
    $('.paper-tab[data-id="all"] .tab-label').css('color', '#185FA5');
    $('.paper-tab[data-id="all"] span[id="count-all"]').css({ 'background': '#378ADD', 'color': '#fff' });

    $('#orderListTitle').text('All Orders');

    loadOrders();
});

$(document).ready(function () {
    loadOrders();
});
</script>
@endpush