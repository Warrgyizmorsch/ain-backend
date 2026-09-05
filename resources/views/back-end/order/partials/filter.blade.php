<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        {{-- <h3 class="card-title align-items-start flex-column">
            <span id="filter-total" class="card-label fw-bolder fs-3 mb-1">Filter</span>
        </h3> --}}
        <div class="d-flex align-items-center gap-3">
            <h3 class="card-title align-items-start flex-column mb-0">
                <span id="filter-total" class="card-label fw-bolder fs-3 mb-1">
                    Filter
                </span>
            </h3>

            <button type="button" id="toggleFilterBtn" class="btn btn-sm btn-primary">
                Show Filters
            </button>
        </div>
        @if(empty($hideOrderQuickFilters))
        <div class="card-toolbar gap-2">
            <a href="javascript:void(0)" id="teamAlphaBtn" class="btn btn-sm btn-info">
                Team-Alpha {{ $alphaCount ?? 0 }}
            </a>
            <a href="javascript:void(0)" id="teamGigaBtn" class="btn btn-sm btn-dark">
                Team-Giga {{ $gigaCount ?? 0 }}
            </a>
        </div>
        @endif
    </div>
    <div class="card-body py-3" id="filterBody" style="display:none;">
        <form action="">
            <div class="row mb-3">
                <div class="col-md-3 fv-row">
                    <input type="search" name="search" id="search" class="form-control form-control-solid"
                        placeholder="OrderCode or Title">
                </div>

                <script src="{{ asset('js/jquery.js') }}"></script>

                <div class="col-md-3 fv-row position-relative">
                    <input type="text" id="searchInput" name="user"
                        class="form-control form-control-solid" placeholder="User-Name,Number,Email" autocomplete="off">
                    <!-- Container to display custom search results dropdown -->
                    <div id="searchResultss" class="dropdown-menu w-100 shadow-lg p-0 mt-1" style="display:none; max-height: 250px; overflow-y: auto; z-index: 1050; position: absolute;"></div>
                    <!-- Hidden field to store the selected value -->
                    <input type="hidden" id="selectedValue" name="uid">
                </div>
                <div class="col-md-3 fv-row"><select id="group_id" name="group_id" class="form-select form-select-solid" data-control="select2" data-placeholder="User Group"><option value="">All Groups</option>@foreach(\App\Models\GroupMaster::where('status',1)->orderBy('name')->get(['id','name']) as $group)<option value="{{ $group->id }}">{{ $group->name }}</option>@endforeach</select></div>

                <script>
                    $(document).ready(function() {
                        let searchTimeout = null;

                        $('#searchInput').on('input focus', function() {
                            var searchValue = $(this).val().trim();

                            clearTimeout(searchTimeout);

                            if (searchValue.length >= 2) {
                                $('#searchResultss').html(
                                    '<div class="p-3 text-center text-muted fs-7 d-flex align-items-center justify-content-center gap-2">' +
                                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                                        '<span>Searching users...</span>' +
                                    '</div>'
                                ).show();

                                searchTimeout = setTimeout(function() {
                                    $.ajax({
                                        url: "{{ route('search-order') }}",
                                        type: "GET",
                                        data: {
                                            user: searchValue
                                        },
                                        success: function(response) {
                                            var resultsHtml = '';
                                            if (response && response.length > 0) {
                                                $.each(response, function(key, value) {
                                                    var mobileStr = value.mobile_no ? ' | 📞 ' + value.mobile_no : '';
                                                    resultsHtml += '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                                        'data-id="' + value.id + '" data-email="' + value.email + '" data-name="' + value.name + '">' +
                                                        '<div class="fw-bolder text-dark fs-6">' + value.name + '</div>' +
                                                        '<div class="text-muted fs-7">' + value.email + mobileStr + '</div>' +
                                                        '</a>';
                                                });
                                            } else {
                                                resultsHtml = '<div class="p-3 text-muted fs-7 text-center">No results found</div>';
                                            }
                                            $('#searchResultss').html(resultsHtml).show();
                                        },
                                        error: function() {
                                            $('#searchResultss').html('<div class="p-3 text-danger fs-7 text-center">Error loading results</div>').show();
                                        }
                                    });
                                }, 250);
                            } else {
                                $('#searchResultss').hide().empty();
                                if (searchValue.length === 0) {
                                    $('#selectedValue').val('');
                                }
                            }
                        });

                        // Handle click on custom dropdown item
                        $(document).on('click', '.user-select-item', function(e) {
                            e.preventDefault();
                            var selectedId = $(this).attr('data-id');
                            var selectedEmail = $(this).attr('data-email');
                            var selectedName = $(this).attr('data-name');

                            $('#searchInput').val(selectedName + ' (' + selectedEmail + ')');
                            $('#selectedValue').val(selectedId);
                            $('#searchResultss').hide().empty();
                        });

                        // Close dropdown on clicking outside
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('#searchInput, #searchResultss').length) {
                                $('#searchResultss').hide();
                            }
                        });
                    });
                </script>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="status" id="status" aria-label="Select a Language" data-control="select2"
                        data-placeholder="Status" class="form-select form-select-solid form-select-lg "
                        data-select2-id="select2-data-13-mh4q" tabindex="-1">
                        <option value="">Status</option>
                        @foreach($data['Status'] as $Status)
                        <option value="{{$Status->status}}">{{$Status->status}}</option>
                        @endforeach
                    </select>
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="writer" id="writer" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Writer Teams " class="form-select form-select-solid form-select-lg "
                        data-select2-id="select2-data-16-79699" tabindex="-1">
                        <option value="">select</option>
                        <option value="Not Assign">Not Assign</option>

                        @foreach($data['Team'] as $writer)
                        <option value="{{$writer->writer_name}}">{{$writer->writer_name}}</option>
                        @endforeach
                    </select>
                </div>

                @if(auth()->user()->role_id == 1)
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="writerTL" id="writerTL" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Writer TL" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        @foreach($data['writerTL'] as $tl)
                        <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="SubWriter" id="SubWriter" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Sub Writer" class="form-select form-select-solid form-select-lg"
                        tabindex="-1">
                        <option value=""></option>
                        @foreach($data['SubWriter'] as $Sub)
                        <option value="{{ $Sub->id }}">{{ $Sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="college" id="college" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="College Name" class="form-select form-select-solid form-select-lg"
                        tabindex="-1">
                        <option value=""></option>
                        @foreach($data['college'] as $college)
                        <option value="{{$college->college_name}}">{{$college->college_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="extra" id="extra" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Tech,Resit,Failed Job" class="form-select form-select-solid form-select-lg"
                        tabindex="-1">
                        <option value=""></option>
                        <!-- Option for Tech -->
                        <option value="tech" {{ old('extra') == 'tech' ? 'selected' : '' }}>Tech</option>
                        <!-- Option for Resit -->
                        <option value="resit" {{ old('extra') == 'resit' ? 'selected' : '' }}>Resit</option>
                        <!-- Option for Failed Job -->
                        <option value="1" {{ old('extra') == '1' ? 'selected' : '' }}>First Class Work</option>
                        <option value="failedjob">Failed Job</option>
                        <option value="overdue">Overdue Deadline</option>
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container mt-3">
                    <select name="deadline_status" id="deadline_status" aria-label="Select Deadline Status" data-control="select2"
                        data-placeholder="Deadline Status" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        <option value="overdue" {{ request('deadline_status') == 'overdue' ? 'selected' : '' }}>Overdue Deadline</option>
                        <option value="missed" {{ request('deadline_status') == 'missed' ? 'selected' : '' }}>Missed Order</option>
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container mt-3">
                    <select name="offer" id="offer" aria-label="Select offer" data-control="select2"
                        data-placeholder="offer" class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        <option value="Original" {{ request('offer') == 'Original' ? 'selected' : '' }}>Original</option>
                        <option value="Discounted" {{ request('offer') == 'Discounted' ? 'selected' : '' }}>Discounted</option>
                        <option value="Special Price" {{ request('offer') == 'Special Price' ? 'selected' : '' }}>Special Price</option>
                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container mt-3">
                    <select name="duec" id="duec" data-control="select2" aria-label="Due" data-placeholder="Due"
                        class="form-select form-select-solid form-select-lg"
                        tabindex="-1">
                        <option value=""></option>
                        <option value="due" {{ old('duec') == 'due' ? 'selected' : '' }}>Due Amount</option>
                        <option value="no due" {{ old('duec') == 'no due' ? 'selected' : '' }}>No Due Amount</option>

                    </select>
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container mt-3">
                    <select name="marks_filter" id="marks_filter" data-control="select2"
                        aria-label="Assign Marks" data-placeholder="Assign Marks"
                        class="form-select form-select-solid form-select-lg" tabindex="-1">
                        <option value=""></option>
                        <option value="below-50">Below 50</option>
                        <option value="50-60">50-60</option>
                        <option value="60-70">60-70</option>
                        <option value="70-80">70-80</option>
                        <option value="80-90">80-90</option>
                        <option value="90-100">90-100</option>
                    </select>
                </div>



            </div>
            <div class="row mb-1 additional-filters" style="display:none;">
                <div class="col-md-3 fv-row mb-3">
                    <input type="date" name="from_date" id="from_date" class="form-control form-control-solid"
                        placeholder="Search By From Date">
                </div>
                <div class="col-md-3 fv-row">
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-solid"
                        placeholder="Search By To Date">
                </div>

                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="date_status" id="date_status" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Date Type"
                        class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                        data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value="">Date Type</option>
                        <option value="writer_deadline">Writer Deadline</option>
                        <option value="delivery_date">Delivery Date</option>
                        <option value="draft_date">Draft Date</option>
                        <option value="failed_at">Failed Date</option>
                        <option value="overdue">Overdue Deadline</option>

                    </select>
                </div>
                <div class="col-md-3 fv-row">
                    <input type="search" name="module_code" id="module_code" class="form-control form-control-solid"
                        placeholder="Module Code">
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="paper_type" id="paper_type" aria-label="Select a Language" data-control="select2"
                        data-placeholder="Paper Type" class="form-select form-select-solid form-select-lg "
                        data-select2-id="select2-data-13-mh4q" tabindex="-1">
                        <option value=""></option>
                        @foreach($data['paper'] as $paperType)
                        <option value="{{$paperType->paper_type}}">{{$paperType->paper_type}}</option>
                        @endforeach
                    </select>
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="semester" id="semester" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Search By Semester"
                        class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                        data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value="">semester</option>
                        <option value="I semester">I semester</option>
                        <option value="II semester">II semester</option>
                        <option value="III semester">III semester</option>
                        <option value="IV semester">IV semester</option>
                        <option value="final semester">final semester</option>
                    </select>
                </div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="payment" id="payment" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Search By Payment Type"
                        class="form-select form-select-solid form-select-lg select2-hidden-accessible"
                        data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value="">--Select Payeename Type--</option>
                        <option value="empty">Empty Payee Name</option>
                    </select>
                </div>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4 || auth()->user()->role_id == 9)
                <div class="col-md-3 fv-row mb-3">
                    <input type="month" name="month" id="month" class="form-control form-control-solid"
                        placeholder="Search By Month">
                </div>
                @endif
            </div>


            <div class="col-lg-12 fv-row fv-plugins-icon-container" style="display: flex; justify-content: space-between; align-items: center;">
                <!-- <button type='submit' class="btn btn-sm btn-primary" >Search</button> -->
                <div>

                    <a onclick="applyFilters()" class="btn btn-sm btn-primary">Search</a>
                    <button type="button" id="resetFiltersBtn" class="btn btn-sm btn-danger" style="display: none;">Reset</button>
                    <button type="button" id="showMoreFilters" class="btn btn-sm btn-success">Show More Filters</button>
                    @if(empty($hideOrderQuickFilters))
                    <a href="javascript:void(0)" id="overdueBtn" class="btn btn-sm btn-danger">
                        Overdue {{ $overdueCount }}
                    </a>
                    <a href="javascript:void(0)" id="todayDeadlineBtn"
                    class="btn btn-sm btn-warning">
                        Today's Deadline
                    </a>
                    <a href="javascript:void(0)" id="yesterdayDeadlineBtn"
                    class="btn btn-sm btn-light-warning">
                        Yesterday's Deadline
                    </a>

                    <a href="javascript:void(0)" id="todayWriterDeadlineBtn"
                    class="btn btn-sm btn-primary">
                        Writer's Deadline
                    </a>

                    <input type="hidden" id="today_deadline_filter" value="">
                    <input type="hidden" id="yesterday_deadline_filter" value="">
                    <input type="hidden" id="today_writer_deadline_filter" value="">
                    <a href="javascript:void(0)" id="writerQueryBtn" class="btn btn-sm btn-secondary">
                        Writer Query
                    </a>
                    <a href="javascript:void(0)" id="holdWorkBtn" class="btn btn-sm btn-warning">
                        Hold Work
                    </a>
                    {{-- <a href="javascript:void(0)" id="teamAlphaBtn" class="btn btn-sm btn-info">
                        Team-Alpha {{ $alphaCount ?? 0 }}
                    </a>
                    <a href="javascript:void(0)" id="teamGigaBtn" class="btn btn-sm btn-dark">
                        Team-Giga {{ $gigaCount ?? 0 }}
                    </a> --}}
                    <input type="hidden" id="filter_team_id" value="">
                    @endif
                </div>
                @if( auth()->user()->role_id == 1)
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-danger" style="display: none;" id="export-order-btn">
                        Export
                    </button>
                    <div id="order-export-progress" class="order-export-progress" style="display:none;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <span id="order-export-progress-message" class="fs-8 fw-bold text-gray-700">Preparing export...</span>
                                    <span id="order-export-progress-percent" class="fs-8 fw-bolder text-primary">0%</span>
                                </div>
                                <div class="progress h-6px">
                                    <div id="order-export-progress-bar"
                                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        role="progressbar" style="width:0%" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>
                                </div>
                            </div>
                            <button type="button" id="close-order-export-progress" class="btn btn-icon btn-sm btn-light-danger" title="Close progress">
                                <span class="fs-4 fw-bold">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
    .quick-filter-active {
        border: 2px solid #50CD89 !important;
        box-shadow: 0 0 10px rgba(80, 205, 137, 0.7) !important;
        outline: none !important;
    }

    .order-export-progress {
        width: 310px;
        padding: 8px 10px;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 8px;
        box-shadow: 0 3px 12px rgba(63, 66, 84, .12);
    }

    .loading-container div {
        font-size: 14px;
        font-weight: 500;
    }

    .loading-container {
        position: relative;
        height: 100%;
        /* Adjust this value according to your layout */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading-spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .error {
        color: red;
    }

    /* Additional styling can be added here */
</style>

{{-- <script>
    let offset = 0;
    const limit = 50;
    let loading = false;
    let hasMore = true;
    let filters = {};

    function fetchData(append = false) {
        if (loading || !hasMore) return;

        loading = true;

        if (append) {
            $('#spinner-row').show();
        } else {
            // $('#preloader2').show();
            $('#spinner-row').show();

        }

        $.ajax({
            url: "{{ route('orders.filter') }}",
type: "GET",
data: {
...filters,
limit: limit,
offset: offset
},
success: function (response) {
const noResults = response.message || response.total === 0 || !response.html.trim();

if (noResults) {
$('#lead-rows').html(`
<tr>
    <td colspan="15" class="text-left text-danger" style="padding-left: 10px;">
        ${response.message || 'No orders found for the selected filters.'}
    </td>
</tr>
`);
hasMore = false;
disableScrollHandler();
} else {
if (append) {
$('#lead-rows').append(response.html);
} else {
$('#lead-rows').html(response.html);
}
offset += response.count;
hasMore = response.has_more;
enableScrollHandler();
}

$('#filter-total').text(`Filtered Orders (${response.total ?? 0} total)`);

$('#spinner-row').hide();
$('#preloader2').hide();
$('#export-order-btn').show();
loading = false;
},
error: function () {
$('#spinner-row').hide();
$('#preloader2').hide();
loading = false;
}
});
}

function applyFilters() {
offset = 0;
hasMore = true;
disableScrollHandler();

filters = {
search: ($('#search').val() || '').trim(),
uid: ($('#selectedValue').val() || '').trim(),
user: ($('#searchInput').val() || '').trim(),
group_id: $('#group_id').val(),
status: $('#status').val(),
writer: $('#writer').val(),
dateStatus: $('#date_status').val(),
fromDate: $('#from_date').val(),
toDate: $('#to_date').val(),
WriterTL: $('#writerTL').val(),
SubWriter: $('#SubWriter').val(),
college: $('#college').val(),
extra: $('#extra').val(),
module_code: $('#module_code').val(),
paper_type: $('#paper_type').val(),
semester: $('#semester').val(),
payment: $('#payment').val(),
month: $('#month').val()
};

const allEmpty = Object.values(filters).every(val => !val || val.trim() === "");

if (allEmpty) {
Swal.fire({
icon: 'warning',
title: 'No Filters Applied',
text: 'Please fill at least one filter to search.',
confirmButtonColor: '#3085d6',
confirmButtonText: 'OK'
});
return;
}

// 🔄 Hide initial blade data, show AJAX target tbody
$('#initial-order-rows').hide();
$('#lead-rows').show().empty();

fetchData(false);
}

function resetFilters() {
// Clear filter values
$('input[type=search], input[type=date], input[type=month], input[type=text], input[type=hidden]').val('');
$('select').val('').trigger('change');

// Reset state
offset = 0;
hasMore = true;
filters = {};
disableScrollHandler();

// 🔄 Hide AJAX data, show initial blade-rendered data
$('#lead-rows').hide().empty();
$('#initial-order-rows').show();
$('#filter-total').text(`All Orders`);
$('#export-order-btn').hide();
}

function enableScrollHandler() {
$('#scroll-order-table').off('scroll').on('scroll', function () {
const container = $(this);
const scrollTop = container.scrollTop();
const containerHeight = container.innerHeight();
const scrollHeight = this.scrollHeight;

if (scrollTop + containerHeight >= scrollHeight - 50) {
fetchData(true); // Load more and append
}
});
}

function disableScrollHandler() {
$('#scroll-order-table').off('scroll');
}

$(document).ready(function () {
// Populate SubWriters when WriterTL changes
function populateSubwriters() {
const tlId = $('#writerTL').val();
const subwriterSelect = $('#SubWriter');
const selectedSubWriter = subwriterSelect.val();

subwriterSelect.empty();

if (tlId !== '') {
$.ajax({
type: 'GET',
url: '/fetch-subwriters',
data: { tlId },
success: function (data) {
$.each(data, function (key, value) {
subwriterSelect.append(`<option value="${value.id}">${value.name}</option>`);
});
subwriterSelect.val(selectedSubWriter);
},
error: function (err) {
console.error('Error fetching SubWriters:', err);
}
});
}
}

// Show/Hide more filters
$('#showMoreFilters').on('click', function () {
$('.additional-filters').toggle();
const isVisible = $('.additional-filters').is(':visible');
$(this).text(isVisible ? 'Hide More Filters' : 'Show More Filters');
});

// TL change triggers subwriter update
$(document).on('change', '#writerTL', populateSubwriters);

// Reset filters and show original data
$('#resetFiltersBtn').on('click', function () {
resetFilters();
});

// Optionally: load default base data via AJAX on first load (commented out)
// fetchData(false);
});
</script> --}}

<script>
    let filterStorageKey = "{{ $filterStorageKey ?? 'order_filters' }}";
    let offset = 0;
    const limit = 20;
    let loading = false;
    let hasMore = true;
    let filters = {};

    let currentTotalCount = 0;

    let runningTotals = {
        total_amount: 0,
        total_paid: 0,
        total_due: 0
    };

    function highlightActiveQuickFilters() {
        $('#overdueBtn, #todayDeadlineBtn, #yesterdayDeadlineBtn, #todayWriterDeadlineBtn, #writerQueryBtn, #holdWorkBtn, #teamAlphaBtn, #teamGigaBtn').removeClass('quick-filter-active');

        if ($('#deadline_status').val() === 'overdue') {
            $('#overdueBtn').addClass('quick-filter-active');
        }
        if ($('#today_deadline_filter').val() === '1') {
            $('#todayDeadlineBtn').addClass('quick-filter-active');
        }
        if ($('#yesterday_deadline_filter').val() === '1') {
            $('#yesterdayDeadlineBtn').addClass('quick-filter-active');
        }
        if ($('#today_writer_deadline_filter').val() === '1') {
            $('#todayWriterDeadlineBtn').addClass('quick-filter-active');
        }
        if ($('#status').val() === 'writer query') {
            $('#writerQueryBtn').addClass('quick-filter-active');
        }
        if ($('#status').val() === 'Hold Work') {
            $('#holdWorkBtn').addClass('quick-filter-active');
        }
        if ($('#filter_team_id').val() === '1') {
            $('#teamAlphaBtn').addClass('quick-filter-active');
        }
        if ($('#filter_team_id').val() === '2') {
            $('#teamGigaBtn').addClass('quick-filter-active');
        }
    }

    $(document).on('click', '#overdueBtn', function(e) {
        e.preventDefault();
        if ($('#deadline_status').val() === 'overdue') {
            $('#deadline_status').val('').trigger('change');
        } else {
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#deadline_status').val('overdue').trigger('change');
        }
        applyFilters();
    });

    $(document).on('click', '#todayDeadlineBtn', function(e) {
        e.preventDefault();
        if ($('#today_deadline_filter').val() === '1') {
            $('#today_deadline_filter').val('');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#today_deadline_filter').val('1');
        }
        applyFilters();
    });

    $(document).on('click', '#yesterdayDeadlineBtn', function(e) {
        e.preventDefault();
        if ($('#yesterday_deadline_filter').val() === '1') {
            $('#yesterday_deadline_filter').val('');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#yesterday_deadline_filter').val('1');
        }
        applyFilters();
    });

    $(document).on('click', '#todayWriterDeadlineBtn', function(e) {
        e.preventDefault();
        if ($('#today_writer_deadline_filter').val() === '1') {
            $('#today_writer_deadline_filter').val('');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#today_writer_deadline_filter').val('1');
        }
        applyFilters();
    });

    $(document).on('click', '#writerQueryBtn', function(e) {
        e.preventDefault();
        if ($('#status').val() === 'writer query') {
            $('#status').val('').trigger('change');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#status').val('writer query').trigger('change');
        }
        applyFilters();
    });

    $(document).on('click', '#holdWorkBtn', function(e) {
        e.preventDefault();
        if ($('#status').val() === 'Hold Work') {
            $('#status').val('').trigger('change');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#status').val('Hold Work').trigger('change');
        }
        applyFilters();
    });

    $(document).on('click', '#teamAlphaBtn', function(e) {
        e.preventDefault();
        if ($('#filter_team_id').val() === '1') {
            $('#filter_team_id').val('');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#filter_team_id').val('1');
        }
        applyFilters();
    });

    $(document).on('click', '#teamGigaBtn', function(e) {
        e.preventDefault();
        if ($('#filter_team_id').val() === '2') {
            $('#filter_team_id').val('');
        } else {
            $('#deadline_status').val('').trigger('change');
            $('#today_deadline_filter').val('');
            $('#yesterday_deadline_filter').val('');
            $('#today_writer_deadline_filter').val('');
            $('#status').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#filter_team_id').val('2');
        }
        applyFilters();
    });

    function fetchData(append = false) {
        if (loading || !hasMore) return;

        loading = true;

        if (append) {
            $('#spinner-row').show();
        } else {
            $('#spinner-row').show();
        }

        let reqData = {
            ...filters,
            limit: limit,
            offset: offset
        };
        if (append && currentTotalCount > 0) {
            reqData.total = currentTotalCount;
        }

        $.ajax({
            url: "{{ $filterRoute ?? route('orders.filter') }}",
            type: "GET",
            data: reqData,
            success: function(response) {
                const isBatchEmpty = (!response.html || !response.html.trim() || response.count === 0);

                if (isBatchEmpty) {
                    hasMore = false;
                    disableScrollHandler();
                    if (!append) {
                        $('#lead-rows').html(`
                            <tr>
                                <td colspan="15" class="text-left text-danger" style="padding-left: 10px;">
                                    ${response.message || 'No orders found for the selected filters.'}
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    if (append) {
                        $('#lead-rows').append(response.html);
                    } else {
                        $('#lead-rows').html(response.html);
                    }
                    offset += response.count;
                    hasMore = response.has_more;
                    enableScrollHandler();
                }

                if (response.totals) {
                    runningTotals.total_amount += Number(response.totals.total_amount ?? 0);
                    runningTotals.total_paid += Number(response.totals.total_paid ?? 0);
                    runningTotals.total_due += Number(response.totals.total_due ?? 0);

                    $('#total-amount').text('£' + runningTotals.total_amount);
                    $('#total-paid').text('£' + runningTotals.total_paid);
                    $('#total-due').text('£' + runningTotals.total_due);
                }

                if (response.total !== undefined) {
                    currentTotalCount = response.total;
                }

                // $('#filter-total').text(`Filtered Orders (${response.total ?? 0} total)`);
                $('#filter-total').text(`{{ $filterTitle ?? 'Filtered Orders' }} (${response.total ?? currentTotalCount} total)`);

                $('#spinner-row').hide();
                $('#preloader2').hide();
                $('#export-order-btn').show();
                loading = false;
            },
            error: function() {
                $('#spinner-row').hide();
                $('#preloader2').hide();
                loading = false;
            }
        });
    }

    function applyFilters() {
        offset = 0;
        hasMore = true;

        runningTotals = {
        total_amount: 0,
        total_paid: 0,
        total_due: 0
    };

        disableScrollHandler();

        // NAYA UPDATE YAHAN HAI: deadline_status add kar diya gaya hai
        filters = {
            search: $('#search').val(),
            uid: $('#selectedValue').val(),
            user: $('#searchInput').val(),
            group_id: $('#group_id').val(),
            status: $('#status').val(),
            writer: $('#writer').val(),
            dateStatus: $('#date_status').val(),
            fromDate: $('#from_date').val(),
            toDate: $('#to_date').val(),
            WriterTL: $('#writerTL').val(),
            SubWriter: $('#SubWriter').val(),
            college: $('#college').val(),
            extra: $('#extra').val(),
            module_code: $('#module_code').val(),
            paper_type: $('#paper_type').val(),
            semester: $('#semester').val(),
            payment: $('#payment').val(),
            month: $('#month').val(),
            deadline_status: $('#deadline_status').val(), // Naya field add kiya
            team_id: $('#filter_team_id').val(),
            offer: $('#offer').val(),
            duec: $('#duec').val(),
            marks_filter: $('#marks_filter').val(),
            holdBtn: $('#holdBtn').val(),
            today_deadline_filter: $('#today_deadline_filter').val(),
            yesterday_deadline_filter: $('#yesterday_deadline_filter').val(),
            today_writer_deadline_filter: $('#today_writer_deadline_filter').val()
            
        };
        // localStorage.setItem('order_filters', JSON.stringify(filters));
        localStorage.setItem(filterStorageKey, JSON.stringify(filters));



        const allEmpty = Object.values(filters).every(val => !val || val.trim() === "");

        if (allEmpty) {
            Swal.fire({
                icon: 'warning',
                title: 'No Filters Applied',
                text: 'Please fill at least one filter to search.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        // 🔄 Hide initial blade data, show AJAX target tbody
        $('#initial-order-rows').hide();
        $('#lead-rows').show().empty();
        $('#resetFiltersBtn').show();

        highlightActiveQuickFilters();

        fetchData(false);
    }

    function resetFilters() {
        // localStorage.removeItem('order_filters');
        localStorage.removeItem(filterStorageKey);
        // Clear filter values
        $('input[type=search], input[type=date], input[type=month], input[type=text], input[type=hidden]').val('');
        $('#searchResultss').hide().empty();
        $('select').val('').trigger('change');
        $('#filter_team_id').val('');

        // Reset state
        offset = 0;
        hasMore = true;
        filters = {};
        disableScrollHandler();

        highlightActiveQuickFilters();

         runningTotals = {
        total_amount: 0,
        total_paid: 0,
        total_due: 0
    };

        // 🔄 Hide AJAX data, show initial blade-rendered data
        $('#lead-rows').hide().empty();
        $('#initial-order-rows').show();
        $('#filter-total').text(`All Orders`);
        $('#export-order-btn').hide();

        // NAYI LINE: Reset hone ke baad button wapas hide kar do
        $('#resetFiltersBtn').hide();
    }

    function enableScrollHandler() {
        $('#scroll-order-table').off('scroll').on('scroll', function() {
            const container = $(this);
            const scrollTop = container.scrollTop();
            const containerHeight = container.innerHeight();
            const scrollHeight = this.scrollHeight;

            if (scrollTop + containerHeight >= scrollHeight - 50) {
                fetchData(true); // Load more and append
            }
        });
    }

    function disableScrollHandler() {
        $('#scroll-order-table').off('scroll');
    }

    $(document).ready(function() {
        // Populate SubWriters when WriterTL changes
        function populateSubwriters() {
            const tlId = $('#writerTL').val();
            const subwriterSelect = $('#SubWriter');
            const selectedSubWriter = subwriterSelect.val();

            subwriterSelect.empty();

            if (tlId !== '') {
                $.ajax({
                    type: 'GET',
                    url: '/fetch-subwriters',
                    data: {
                        tlId
                    },
                    success: function(data) {
                        $.each(data, function(key, value) {
                            subwriterSelect.append(`<option value="${value.id}">${value.name}</option>`);
                        });
                        subwriterSelect.val(selectedSubWriter);
                    },
                    error: function(err) {
                        console.error('Error fetching SubWriters:', err);
                    }
                });
            }
        }

        // Show/Hide more filters
        $('#showMoreFilters').on('click', function() {
            $('.additional-filters').toggle();
            const isVisible = $('.additional-filters').is(':visible');
            $(this).text(isVisible ? 'Hide More Filters' : 'Show More Filters');
        });

        // TL change triggers subwriter update
        $(document).on('change', '#writerTL', populateSubwriters);

        // Reset filters and show original data
        $('#resetFiltersBtn').on('click', function() {
            resetFilters();
        });

        // Check URL parameters first (e.g. ?search=... or ?uid=... or ?phone=...)
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search') || urlParams.get('order') || urlParams.get('search_order') || urlParams.get('phone');
        const uidParam = urlParams.get('uid');
        const userParam = urlParams.get('user');
        const statusParam = urlParams.get('status');

        if (searchParam || uidParam || userParam || statusParam) {
            localStorage.removeItem(filterStorageKey);

            if (searchParam) $('#search').val(searchParam);
            if (uidParam) $('#selectedValue').val(uidParam);
            if (userParam) $('#searchInput').val(userParam);
            if (statusParam) $('#status').val(statusParam).trigger('change');

            $('#filterBody').show();
            $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');

            applyFilters();
            return;
        }

        // let savedFilters = localStorage.getItem('order_filters');
        let savedFilters = localStorage.getItem(filterStorageKey);
        if (savedFilters) {
            try {
                filters = JSON.parse(savedFilters);
                const hasActiveFilters = Object.values(filters).some(val => val && String(val).trim() !== "");

                if (hasActiveFilters) {
                    $('#search').val(filters.search || '');
                    $('#selectedValue').val(filters.uid || '');
                    $('#searchInput').val(filters.user || '');
                    $('#group_id').val(filters.group_id || '').trigger('change');
                    $('#status').val(filters.status || '').trigger('change');
                    $('#writer').val(filters.writer || '').trigger('change');
                    $('#date_status').val(filters.dateStatus || '').trigger('change');
                    $('#from_date').val(filters.fromDate || '');
                    $('#to_date').val(filters.toDate || '');
                    $('#writerTL').val(filters.WriterTL || '').trigger('change');
                    $('#SubWriter').val(filters.SubWriter || '').trigger('change');
                    $('#college').val(filters.college || '').trigger('change');
                    $('#extra').val(filters.extra || '').trigger('change');
                    $('#module_code').val(filters.module_code || '');
                    $('#paper_type').val(filters.paper_type || '').trigger('change');
                    $('#semester').val(filters.semester || '').trigger('change');
                    $('#payment').val(filters.payment || '').trigger('change');
                    $('#month').val(filters.month || '');
                    $('#deadline_status').val(filters.deadline_status || '').trigger('change');
                    $('#filter_team_id').val(filters.team_id || '');
                    $('#offer').val(filters.offer || '').trigger('change');
                    $('#duec').val(filters.duec || '').trigger('change');
                    $('#marks_filter').val(filters.marks_filter || '').trigger('change');
                    $('#today_deadline_filter').val(filters.today_deadline_filter || '');
                    $('#yesterday_deadline_filter').val(filters.yesterday_deadline_filter || '');
                    $('#today_writer_deadline_filter').val(filters.today_writer_deadline_filter || '');

                    // Auto-open filter section so user can see restored active filters
                    $('#filterBody').show();
                    $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');

                    const hasMoreFilterData = !!(filters.fromDate || filters.toDate || filters.dateStatus || filters.module_code || filters.paper_type || filters.semester || filters.payment || filters.month);
                    if (hasMoreFilterData) {
                        $('.additional-filters').show();
                        $('#showMoreFilters').text('Hide More Filters');
                    }

                    offset = 0;
                    hasMore = true;

                    $('#initial-order-rows').hide();
                    $('#lead-rows').show().empty();
                    $('#resetFiltersBtn').show();

                    highlightActiveQuickFilters();

                    fetchData(false);
                } else {
                    localStorage.removeItem(filterStorageKey);
                }
            } catch (e) {
                localStorage.removeItem(filterStorageKey);
            }
        }

        // Optionally: load default base data via AJAX on first load (commented out)
        // fetchData(false);
    });
</script>

<script>
    // Document ready hone ke baad hi export ka button chalega
    $(document).ready(function() {
        // Event delegation use kiya hai taaki agar button baad mein bhi load ho tab bhi click chal jaaye
        $(document).on("click", "#export-order-btn", function() {
            // Button ko instantly hide kar do
            $(this).hide();

            Swal.fire({
                title: 'Choose Export Option',
                icon: 'question',
                showConfirmButton: true,
                confirmButtonText: 'Export All',
                showDenyButton: true,
                denyButtonText: 'Custom Export',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
            }).then((result) => {
                // jQuery se smoothly values get karna
                const filters = {
                    search: $('#search').val() || "",
                    uid: $('#selectedValue').val() || "", group_id: $('#group_id').val() || "",
                    status: $('#status').val() || "",
                    writer: $('#writer').val() || "",
                    dateStatus: $('#date_status').val() || "",
                    fromDate: $('#from_date').val() || "",
                    toDate: $('#to_date').val() || "",
                    WriterTL: $('#writerTL').val() || "",
                    SubWriter: $('#SubWriter').val() || "",
                    college: $('#college').val() || "",
                    extra: $('#extra').val() || "",
                    module_code: $('#module_code').val() || "",
                    paper_type: $('#paper_type').val() || "",
                    semester: $('#semester').val() || "",
                    month: $('#month').val() || "",
                    payment: $('#payment').val() || "",
                    deadline_status: $('#deadline_status').val() || "",
                    team_id: $('#filter_team_id').val() || "",
                    offer: $('#offer').val() || "",
                    duec: $('#duec').val() || "",
                    marks_filter: $('#marks_filter').val() || "",
                    today_deadline_filter: $('#today_deadline_filter').val() || "",
                    yesterday_deadline_filter: $('#yesterday_deadline_filter').val() || "",
                    today_writer_deadline_filter: $('#today_writer_deadline_filter').val() || ""
                };

                if (result.isConfirmed) {
                    sendExport(filters);
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Select Columns to Export',
                        html: `
                            <div style="text-align: left;">
                                <label><input type="checkbox" value="order_id" class="export-column" > Order ID</label><br>
                                <label><input type="checkbox" value="customer_name" class="export-column" > Name</label><br>
                                <label><input type="checkbox" value="customer_email" class="export-column" > Email</label><br>
                                <label><input type="checkbox" value="customer_country_code" class="export-column" > Country Code</label><br>
                                <label><input type="checkbox" value="customer_phone" class="export-column" > Phone</label><br>
                                <label><input type="checkbox" value="order_date" class="export-column" > Order Date</label><br>
                                <label><input type="checkbox" value="deadline" class="export-column" > Deadline</label><br>
                                <label><input type="checkbox" value="project_title" class="export-column" > Project Title</label><br>
                                <label><input type="checkbox" value="status" class="export-column" > Status</label><br>
                                <label><input type="checkbox" value="pages" class="export-column" > Words</label><br>
                                <label><input type="checkbox" value="price" class="export-column" > Price</label><br>
                                <label><input type="checkbox" value="received" class="export-column" > Received</label><br>
                                <label><input type="checkbox" value="due" class="export-column" > Due</label><br>
                                <label><input type="checkbox" value="writer" class="export-column" > Writer</label><br>
                                <label><input type="checkbox" value="other" class="export-column" > Other</label><br>
                            </div>
                        `,
                        confirmButtonText: 'Export Selected',
                        showCancelButton: true,
                        preConfirm: () => {
                            // jQuery se checked values nikalna
                            const selected = $(".export-column:checked").map(function() {
                                return $(this).val();
                            }).get();

                            if (selected.length === 0) {
                                Swal.showValidationMessage("Please select at least one column");
                            }
                            return selected;
                        }
                    }).then((colRes) => {
                        if (colRes.isConfirmed) {
                            sendExport({
                                ...filters,
                                selected_columns: colRes.value
                            });
                        } else {
                            // Agar custom popup cancel ho jaye toh wapas button dikha do
                            $('#export-order-btn').show();
                        }
                    });
                } else {
                    // Agar main popup cancel ho jaye toh wapas button dikha do
                    $('#export-order-btn').show();
                }
            });

            function sendExport(payload) {
                // Fetch API ka use kiya gaya hai jaisa tumne likha tha
                fetch("/order/export", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload),
                    })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data.message || 'Export could not be started.');
                        return data;
                    })
                    .then((data) => {
                        sessionStorage.removeItem('orderExportProgressDismissed');
                        localStorage.setItem("orderExportStatus", "pending");
                        if (window.showExportProgress) {
                            window.showExportProgress('order', data);
                        }
                    })
                    .catch(error => {
                        console.error("Export Error: ", error);
                        $('#export-order-btn').show();
                        Swal.fire('Export Failed', error.message || 'Please try again.', 'error');
                    });
            }
        });
    });
</script>
<script>
    $(document).ready(function () {

    $('#toggleFilterBtn').on('click', function () {
        const isCurrentlyVisible = $('#filterBody').is(':visible');

        $('#filterBody').slideToggle(300);

        if (isCurrentlyVisible) {
            $(this).text('Show Filters')
                   .removeClass('btn-danger')
                   .addClass('btn-primary');
        } else {
            $(this).text('Hide Filters')
                   .removeClass('btn-primary')
                   .addClass('btn-danger');
        }
    });

});
</script>
