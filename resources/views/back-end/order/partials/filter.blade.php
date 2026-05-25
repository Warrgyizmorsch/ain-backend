<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span id="filter-total" class="card-label fw-bolder fs-3 mb-1">Filter</span>
        </h3>
        <div class="card-toolbar gap-2">
            <a href="javascript:void(0)" id="teamAlphaBtn" class="btn btn-sm btn-info">
                Team-Alpha {{ $alphaCount ?? 0 }}
            </a>
            <a href="javascript:void(0)" id="teamGigaBtn" class="btn btn-sm btn-dark">
                Team-Giga {{ $gigaCount ?? 0 }}
            </a>
        </div>
    </div>
    <div class="card-body py-3">
        <form action="">
            <div class="row mb-3">
                <div class="col-md-3 fv-row">
                    <input type="search" name="search" id="search" class="form-control form-control-solid"
                        placeholder="OrderCode or Title">
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('searchInput');

                        searchInput.addEventListener('input', function(event) {
                            const inputValue = event.target.value;
                            const sanitizedValue = inputValue.replace(/\s/g, ''); // Remove spaces

                            // Update input value without spaces
                            if (inputValue !== sanitizedValue) {
                                searchInput.value = sanitizedValue;
                            }
                        });
                    });
                </script>

                <div class="col-md-3 fv-row">
                    <input type="text" list="searchDatalist" id="searchInput" name="user"
                        class="form-control form-control-solid" placeholder="User-Name,Number,Email" autocomplete="off">
                    <!-- Datalist for displaying search results -->
                    <datalist id="searchDatalist"></datalist>
                    <!-- Container to display search results -->
                    <div id="searchResultss"></div>
                    <!-- Hidden field to store the selected value -->
                    <input type="hidden" id="selectedValue" name="uid">
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#searchInput').on('input', function() {
                            var searchValue = $(this).val();

                            if (searchValue.length >= 3) {
                                $.ajax({
                                    url: "{{ route('search-order') }}",
                                    type: "GET",
                                    data: {
                                        user: searchValue
                                    },
                                    success: function(response) {
                                        var results = '';
                                        if (response.length > 0) {
                                            // Populate the datalist with search results
                                            $('#searchDatalist').empty();
                                            $.each(response, function(key, value) {
                                                // Append each option with email, name, and mobile number
                                                $('#searchDatalist').append('<option value="' + value.email + '">' + value.name + ' (' + value.mobile_no + ')</option>');
                                            });
                                            if (response.length === 1) {
                                                // If there is only one result, automatically fill in the search input
                                                $('#searchInput').val(response[0].email);
                                                // Store the selected value in the hidden field
                                                $('#selectedValue').val(response[0].id);
                                            }
                                        } else {
                                            results = '<div>No results found</div>';
                                        }
                                        $('#searchResultss').html(results);
                                    }
                                });
                            } else {
                                $('#searchResultss').empty();
                            }
                        });

                        // Handle click on search result
                        $('#searchInput').on('input', function() {
                            var selectedEmail = $(this).val();
                            var selectedOption = $('#searchDatalist option[value="' + selectedEmail + '"]');
                            if (selectedOption.length > 0) {
                                // If the selected value exists in the datalist, get its associated ID
                                var selectedId = selectedOption.data('id');
                                $('#selectedValue').val(selectedId);
                            } else {
                                // If the selected value doesn't exist in the datalist, clear the hidden field
                                $('#selectedValue').val('');
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
                    <a href="javascript:void(0)" id="overdueBtn" class="btn btn-sm btn-danger">
                        Overdue {{ $overdueCount }}
                    </a>
                    <a href="javascript:void(0)" id="todayDeadlineBtn"
                    class="btn btn-sm btn-warning">
                        Today's Deadline
                    </a>

                    <a href="javascript:void(0)" id="todayWriterDeadlineBtn"
                    class="btn btn-sm btn-primary">
                        Writer's Deadline
                    </a>

                    <input type="hidden" id="today_deadline_filter" value="">
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
                </div>
                @if( auth()->user()->role_id == 1)
                <button type="button" class="btn btn-sm btn-danger" style="display: none;" id="export-order-btn">
                    Export
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
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
search: $('#search').val(),
uid: $('#selectedValue').val(),
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
    let offset = 0;
    const limit = 50;
    let loading = false;
    let hasMore = true;
    let filters = {};

    let runningTotals = {
        total_amount: 0,
        total_paid: 0,
        total_due: 0
    };

    $(document).on('click', '#overdueBtn', function() {

        // sirf overdue value set karo
        $('#deadline_status').val('overdue').trigger('change');

        // SAME function jo search button use karta hai
        applyFilters();
    });

        $(document).on('click', '#todayDeadlineBtn', function(e) {
        e.preventDefault();

        $('#today_deadline_filter').val('1');
        $('#today_writer_deadline_filter').val('');

        $('#filter_team_id').val('');
        $('#deadline_status').val('').trigger('change');

        applyFilters();
    });

    $(document).on('click', '#todayWriterDeadlineBtn', function(e) {
        e.preventDefault();

        $('#today_writer_deadline_filter').val('1');
        $('#today_deadline_filter').val('');

        $('#filter_team_id').val('');
        $('#deadline_status').val('').trigger('change');

        applyFilters();
    });

    $(document).on('click', '#writerQueryBtn', function(e) {
        e.preventDefault();

        $('#status').val('writer query').trigger('change');

        $('#today_deadline_filter').val('');
        $('#today_writer_deadline_filter').val('');
        $('#filter_team_id').val('');
        $('#deadline_status').val('').trigger('change');

        applyFilters();
    });

   $(document).on('click', '#holdWorkBtn', function(e) {
        e.preventDefault();

        $('#status').val('Hold Work').trigger('change');

        $('#today_deadline_filter').val('');
        $('#today_writer_deadline_filter').val('');
        $('#filter_team_id').val('');
        $('#deadline_status').val('').trigger('change');

        applyFilters();
    });

    $(document).on('click', '#teamAlphaBtn', function() {
        $('#filter_team_id').val('1'); // Alpha ki ID 1 hai
        applyFilters();
    });

    // --- NAYA LOGIC: Team Giga Click ---
    $(document).on('click', '#teamGigaBtn', function() {
        $('#filter_team_id').val('2'); // Giga ki ID 2 hai
        applyFilters();
    });

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
            success: function(response) {
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

                if (response.totals) {
                    runningTotals.total_amount += Number(response.totals.total_amount ?? 0);
                    runningTotals.total_paid += Number(response.totals.total_paid ?? 0);
                    runningTotals.total_due += Number(response.totals.total_due ?? 0);

                    $('#total-amount').text('£' + runningTotals.total_amount);
                    $('#total-paid').text('£' + runningTotals.total_paid);
                    $('#total-due').text('£' + runningTotals.total_due);
                }


                $('#filter-total').text(`Filtered Orders (${response.total ?? 0} total)`);

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
            holdBtn: $('#holdBtn').val(),
            today_deadline_filter: $('#today_deadline_filter').val(),
            today_writer_deadline_filter: $('#today_writer_deadline_filter').val()
            
        };
        localStorage.setItem('order_filters', JSON.stringify(filters));



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

        fetchData(false);
    }

    function resetFilters() {
        localStorage.removeItem('order_filters');
        // Clear filter values
        $('input[type=search], input[type=date], input[type=month], input[type=text], input[type=hidden]').val('');
        $('select').val('').trigger('change');
        $('#filter_team_id').val('');

        // Reset state
        offset = 0;
        hasMore = true;
        filters = {};
        disableScrollHandler();

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

        let savedFilters = localStorage.getItem('order_filters');
        if (savedFilters) {
            filters = JSON.parse(savedFilters);

            $('#search').val(filters.search);
            $('#selectedValue').val(filters.uid);
            $('#status').val(filters.status).trigger('change');
            $('#writer').val(filters.writer).trigger('change');
            $('#date_status').val(filters.dateStatus).trigger('change');
            $('#from_date').val(filters.fromDate);
            $('#to_date').val(filters.toDate);
            $('#writerTL').val(filters.WriterTL).trigger('change');
            $('#SubWriter').val(filters.SubWriter).trigger('change');
            $('#college').val(filters.college).trigger('change');
            $('#extra').val(filters.extra).trigger('change');
            $('#module_code').val(filters.module_code);
            $('#paper_type').val(filters.paper_type).trigger('change');
            $('#semester').val(filters.semester).trigger('change');
            $('#payment').val(filters.payment).trigger('change');
            $('#month').val(filters.month);
            $('#deadline_status').val(filters.deadline_status).trigger('change');
            $('#filter_team_id').val(filters.team_id);
            $('#offer').val(filters.offer).trigger('change');
            $('#duec').val(filters.duec).trigger('change');
            $('#today_deadline_filter').val(filters.today_deadline_filter);
            $('#today_writer_deadline_filter').val(filters.today_writer_deadline_filter);

            offset = 0;
            hasMore = true;

            $('#initial-order-rows').hide();
            $('#lead-rows').show().empty();
            $('#resetFiltersBtn').show();

            fetchData(false);
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
                    uid: $('#selectedValue').val() || "",
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
                    month: $('#month').val() || ""
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
                    .then(res => res.json())
                    .then(() => {
                        localStorage.setItem("orderExportStatus", "pending");
                        Swal.fire({
                            title: 'Export started!',
                            text: 'Your file will be ready shortly.',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    })
                    .catch(error => {
                        console.error("Export Error: ", error);
                        $('#export-order-btn').show(); // Error aane pe wapas button dikha do
                    });
            }
        });
    });
</script>