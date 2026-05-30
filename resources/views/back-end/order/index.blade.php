@extends('layouts.app')

@section('content')

<style>
    /* Red Blinking Animation */
    @keyframes blink-red {
        0% {
            color: #F1416C;
            opacity: 1;
        }

        50% {
            color: #F1416C;
            opacity: 0.4;
        }

        100% {
            color: #F1416C;
            opacity: 1;
        }
    }

    .timer-blink-danger {
        animation: blink-red 1s infinite;
        /* Har 1 second me blink hoga */
    }

    .timer-danger {
        color: #F1416C;
        /* Metronic Red - Deadline Nikal Gayi */
    }

    .timer-success {
        color: #50CD89;
        /* Metronic Green - Order Complete */
    }

    .timer-primary {
        color: #009EF7;
        /* Metronic Blue - Normal Running Timer */
    }

    .timer-missed {
        color: #FFA800;
    }
</style>
<div style="margin-top: -20px;" id="kt_content">

    @include('back-end.order.partials.filter',['overdueCount' => $overdueCount])
    <!-- Leads Table -->
    <div class="card card-xl-stretch mb-5">
        <div class="card-body py-3">
            <div id="scroll-order-table" style="max-height: 76vh; overflow-y: auto;">
                <div class="">
                    <table class="table table-bordered table-hover align-middle" id="leads-table">
                        <thead>
                            <tr class="fw-bolder text-dark bg-light">
                                <th class="min-w-50px text-center" style="padding-right: 0px; background: #F5F8FA;">Sr.
                                </th>
                                <th class="text-center"
                                    style="position: sticky; left: 0; background: #F5F8FA; z-index: 6;">Action</th>
                                        <th class="min-w-150px text-center" style="background: #F5F8FA;">Comment</th>
                                <th class="min-w-100px text-center" style="background: #F5F8FA;">Order ID</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">User</th>
                            
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Order Date</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Deadline</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Project Title</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Status</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Ticket Status</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Words</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Price</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Received</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Due</th>
                                <th class="min-w-150px text-center" style="background: #F5F8FA;">Writer</th>
                                @if(auth()->user()->role_id == 1)
                                <th class="min-w-200px text-center" style="background: #F5F8FA;">Other</th>
                                @endif
                            </tr>
                        </thead>
                        <!-- TABLE BODY -->
                        <tbody id="initial-order-rows" class="allData">
                            @foreach($orders as $index => $order)
                            @include('back-end.order.partials.row', ['order' => $order, 'index' => $index])
                            @endforeach
                        </tbody>

                        <tbody id="lead-rows" class="searchData" style="display: none;">
                            {{-- AJAX-rendered rows will go here --}}
                        </tbody>

                        <tbody id="spinner-row" style="display: none;">
                            <tr>
                                <td colspan="100%" style="text-align: left;">
                                    <div style="display: inline-flex; align-items: center; gap: 10px;">
                                        <div class="loading-spinner" style="margin-left: 10px;"></div>
                                        <div style="font-size: 14px; color: #555; font-weight: 500;">
                                            Please wait while loading data...
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>



                </div>

            </div>
        </div>
        {{-- <div class="order-summary-footer-wrapper">
            <div class="order-summary-footer">

                <div class="summary-box amount">
                    <span>Total</span>
                    <strong id="total-amount">£{{ number_format($totals['total_amount'] ?? 0, 2) }}</strong>
                </div>

                <div class="summary-box paid">
                    <span>Paid</span>
                    <strong id="total-paid">£{{ number_format($totals['total_paid'] ?? 0, 2) }}</strong>
                </div>

                <div class="summary-box due">
                    <span>Due</span>
                    <strong  id="total-due">£{{ number_format($totals['total_due'] ?? 0, 2) }}</strong>
                </div>

            </div>
        </div> --}}
        @if(auth()->user()->role_id == 1)
        <div class="order-summary-footer-wrapper">
            <div class="order-summary-footer">
                <div class="summary-box amount">
                    <span>Total</span>
                    <strong id="total-amount">
                        £{{ number_format($totals['total_amount'] ?? 0, 2) }}
                    </strong>
                </div>
                <div class="summary-box paid">
                    <span>Paid</span>
                    <strong id="total-paid">
                        £{{ number_format($totals['total_paid'] ?? 0, 2) }}
                    </strong>
                </div>
                <div class="summary-box due">
                    <span>Due</span>
                    <strong id="total-due">
                        £{{ number_format($totals['total_due'] ?? 0, 2) }}
                    </strong>
                </div>
            </div>
        </div>
        @endif
    </div>
    <style>
        .order-summary-footer-wrapper {
            width: 100%;
            position: sticky;
            bottom: 0;
            background: #ffffff;
            border-top: 1px solid #e4e6ef;
            padding: 10px 15px;
            z-index: 10;
        }

        .order-summary-footer {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            gap: 15px;
        }

        /* Card style */
        .summary-box {
            min-width: 130px;
            text-align: center;
            padding: 6px 10px;
            border-radius: 8px;
            background: #f5f8fa;
            border: 1px solid #e4e6ef;
        }

        .summary-box span {
            display: block;
            font-size: 11px;
            color: #7e8299;
            margin-bottom: 2px;
        }

        .summary-box strong {
            font-size: 14px;
            font-weight: 600;
            color: #3f4254;
        }

        /* subtle color indicators only */
        .summary-box.amount strong {
            color: #009ef7;
        }

        .summary-box.paid strong {
            color: #50cd89;
        }

        .summary-box.due strong {
            color: #f1416c;
        }
    </style>

    <div id="comment-drawer-container"></div>
    <script>
        function loadCommentDrawer(orderId) {
            $.ajax({
                url: `{{ route('orders.comment.drawer', ':id') }}`.replace(':id', orderId),
                type: 'GET',
                success: function(html) {
                    $('#comment-drawer-container').html(html);

                    // Initialize drawer manually
                    const el = document.querySelector('#kt_drawer_chat' + orderId);
                    const drawer = new KTDrawer(el);
                    drawer.show();
                },
                error: function(err) {
                    console.error('Drawer load failed:', err);
                    Swal.fire('Error', 'Failed to load comment drawer.', 'error');
                }
            });
        }
    </script>

    <!-- Styles -->
    <style>
        .content {
            padding: 0 !important;
        }

        table thead th {
            position: sticky;
            top: 0;
            z-index: 5;
        }



        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(138, 145, 155, 0.8);
            z-index: 1050;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner-border.text-light {
            color: white;
        }

        table.table td,
        table.table th {
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
    </style>

    <!-- Scripts -->

</div>

<div class="modal fade" id="clientReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="text-center mb-10">
                    <h3 class="mb-3">Client Behaviour</h3>
                    <div class="text-muted fw-bold fs-6">Add notes about client behaviour and interaction</div>
                </div>

                <form id="clientReviewForm" class="form">
                    <input type="hidden" id="review_user_id">
                    <div id="latestBehaviourBox" class="alert alert-light-info d-none mb-5">
                        <div class="fw-bold mb-1">Last Behaviour</div>
                        <div id="latestBehaviourText" class="text-gray-800 fs-7"></div>
                        {{-- <div id="latestBehaviourDate" class="text-muted fs-8 mt-1"></div> --}}
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row">
                        <textarea class="form-control form-control-solid" rows="4" id="review_text" placeholder="Enter client behaviour details..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3 btn-sm">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveClientReview()">
                            Save Behaviour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clientMarksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="text-center mb-10">
                    <h3 class="mb-3">Assign Marks</h3>
                    <div class="text-muted fw-bold fs-6">Select a marks range for this client</div>
                </div>

                <form id="clientMarksForm" class="form">
                    <input type="hidden" id="marks_user_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">Select Range</label>
                        <select class="form-select form-select-solid" id="marks_dropdown">
                            <option value="">Select Marks...</option>
                            <option value="below-50">Below 50</option>
                            <option value="50-60">50-60</option>
                            <option value="60-70">60-70</option>
                            <option value="70-80">70-80</option>
                            <option value="80-90">80-90</option>
                            <option value="90-100">90-100</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3 btn-sm">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveClientMarks()">
                            Save Marks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="writerFeedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="text-center mb-10">
                    <h3 class="mb-3">Writer Feedback</h3>
                    <div class="text-muted fw-bold fs-6">Rate the quality of work for this order</div>
                </div>

                <form id="writerFeedbackForm" class="form">
                    <input type="hidden" id="feedback_order_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">Select Rating</label>
                        <select class="form-select form-select-solid" id="feedback_dropdown">
                            <option value="">Select Quality...</option>
                            <option value="1">Good work</option>
                            <option value="0">Average</option>
                            <option value="-1">Poor work</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3 btn-sm">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveWriterFeedback()">
                            Save Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderHistoryModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 position-relative">

                <!-- Center Title -->
                <h5 class="modal-title w-100 text-center fw-semibold">
                    Order Status History
                </h5>

                <!-- Close Button (Right side) -->
                <button type="button"
                    class="btn btn-sm btn-icon position-absolute end-0 me-3"
                    data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>

            </div>
            <div class="modal-body scroll-y pt-0 pb-10 mx-5">
                <div class="card-body custom-card-action">
                    <ul id="historyList" class="list-unstyled mb-0 activity-feed-1"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="additionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Additional Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="additional_order_id">

                <div class="mb-3">
                    <label class="form-label fw-bold">Additional Word Count</label>
                    <input type="number" id="additional_word_count" class="form-control form-control-solid">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Additional Price</label>
                    <input type="number" id="additional_price" class="form-control form-control-solid">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveAdditionalDetails()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Additional History Modal -->
<div class="modal fade" id="additionalHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Additional History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="additionalHistoryBody">
                Loading...
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .activity-feed-1 {
        position: relative;
        padding-left: 30px;
    }

    /* vertical line */
    .activity-feed-1::before {
        content: "";
        position: absolute;
        top: 0;
        left: 10px;
        width: 2px;
        height: 100%;
        border-left: 2px dashed #d1d5db;
    }

    /* each item */
    .feed-item {
        position: relative;
        padding-bottom: 25px;
    }

    /* dot */
    .feed-item::before {
        content: "";
        position: absolute;
        left: -24px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ccc;
        border: 2px solid #fff;
    }

    /* colors */
    .feed-item-primary::before {
        background: #3b82f6;
    }

    .feed-item-success::before {
        background: #22c55e;
    }

    .feed-item-danger::before {
        background: #ef4444;
    }

    /* text truncate */
    .text-truncate-1-line {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .text-truncate-2-line {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function status(orderId) {
        let data = <?php echo json_encode($data['Status']); ?>;

        // Options prepare karein
        let optionsHtml = '<option value="">-- Select Status --</option>';
        Object.values(data).forEach((item) => {
            optionsHtml += `<option value="${item.status}">${item.status}</option>`;
        });

        Swal.fire({
            title: 'Update Order Status',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="fw-bold mb-1">Select New Status</label>
                        <select id="swal-status" class="form-select form-control">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="feedback-date-wrapper">
                        <label class="fw-bold mb-1">Feedback Delivery Date</label>
                        <input type="date" id="swal-feedback-date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold mb-1">Add Comment</label>
                        <textarea id="swal-comment" class="form-control" rows="3" placeholder="Enter status update details..."></textarea>
                    </div>
                </div>
            `,
            didOpen: () => {
                $('#swal-status').on('change', function () {
                    if ($(this).val() === 'Feedback') {
                        $('#feedback-date-wrapper').removeClass('d-none');
                    } else {
                        $('#feedback-date-wrapper').addClass('d-none');
                        $('#swal-feedback-date').val('');
                    }
                });
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm Update',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-active-light'
            },
            preConfirm: () => {
                const status = document.getElementById('swal-status').value;
                const comment = document.getElementById('swal-comment').value;
                const feedbackDate = document.getElementById('swal-feedback-date').value;

                if (!status) {
                    Swal.showValidationMessage('Status selection is required');
                    return false;
                }
                if (status === 'Feedback' && !feedbackDate) {
                    Swal.showValidationMessage('Feedback delivery date is required');
                    return false;
                }
                return {
                    status: status,
                    comment: comment,
                    feedback_date: feedbackDate
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'update_status',
                    data: {
                        orderId: orderId,
                        status: result.value.status, // Directly sending status string
                        comment: result.value.comment,
                        feedback_date: result.value.feedback_date,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.warning) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Payment Due!',
                                text: response.warning,
                                customClass: {
                                    confirmButton: 'btn btn-warning'
                                }
                            });
                        } else if (response.error) {
                            Swal.fire('Error', response.error, 'error');
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Status and Comment updated.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Server error occurred', 'error');
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }
</script>
<script>
    function updateDeliveryDate(orderId) {
        // Show date picker
        Swal.fire({
            title: 'Select Delivery Date',
            html: '<input type="date" id="deliveryDate" class="swal2-input">',
            confirmButtonText: 'Confirm',
            preConfirm: () => {
                const selectedDate = document.getElementById('deliveryDate').value;
                if (!selectedDate) {
                    Swal.showValidationMessage('Please select a delivery date');
                }
                return selectedDate;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Retrieve selected date
                const selectedDate = result.value;
                // Perform actions with the selected date (e.g., update status)
                console.log('Order ID:', orderId);
                console.log('Selected Delivery Date:', selectedDate);

                // Assuming you have the CSRF token available in a variable named csrfToken
                const csrfToken = '{{ csrf_token() }}';

                // Send AJAX request to update delivery date
                $.ajax({
                    url: 'update_date',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        orderId: orderId,
                        selectedDate: selectedDate
                    },
                    success: function(response) {
                        if (response.Error) {
                            // Show SweetAlert error message if there is an error in the response
                            Swal.fire('Invalid Delivery Date', response.Error, 'error');
                        } else {
                            // Reload the page if date updated successfully
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    }
                });
            }
        });
    }

    function rateWriter(orderId, ratingValue) {
        if (ratingValue === "") return;

        $.ajax({
            url: "{{ route('order.rate.writer') }}", // Ensure ye route web.php me ho
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                rating: ratingValue
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        text: "Writer feedback saved successfully!",
                        icon: "success",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function() {
                alert("Something went wrong while saving feedback.");
            }
        });
    }
</script>
<script>
    function CallToWriter(orderId) {
        // Assuming you're using jQuery
        $.ajax({
            type: 'POST',
            url: "{{ route('appdialnumber') }}",
            data: {
                order_id: orderId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Call initiated successfully.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.error || 'Something went wrong. Please Check the Calling Portal',
                    });
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Failed to communicate with the server. Please try again later.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                });
            }
        });
    }

    // Modal Open karne ka function
    // function openReviewModal(userId) {
    //     $('#review_user_id').val(userId);
    //     $('#review_text').val('');
    //     $('#clientReviewModal').modal('show');
    // }

    function openReviewModal(userId) {
    $('#review_user_id').val(userId);
    $('#review_text').val('');

    $('#latestBehaviourBox').addClass('d-none');
    $('#latestBehaviourText').text('');
    $('#latestBehaviourDate').text('');

    $.ajax({
        url: "/user/latest-behaviour/" + userId,
        type: "GET",
        success: function(response) {
            if (response.success && response.behaviour) {
                $('#latestBehaviourText').text(response.behaviour.behaviour);

                if (response.behaviour.created_at) {
                    $('#latestBehaviourDate').text('Added on: ' + response.behaviour.created_at);
                }

                $('#latestBehaviourBox').removeClass('d-none');
            }
        }
    });

    $('#clientReviewModal').modal('show');
}

    function orderHistoryModel(feedbacks) {

        let html = '';

        if (!feedbacks || feedbacks.length === 0) {
            html = `<p class="text-center text-muted">No History Found</p>`;
        } else {

            feedbacks.forEach(fb => {
                // status ke hisab se color class
                let colorClass = 'feed-item-primary';

                if (fb.status === 'Completed' || fb.status === 'Delivered') {
                    colorClass = 'feed-item-success';
                } else if (fb.status === 'Hold Work' || fb.status === 'Cancelled') {
                    colorClass = 'feed-item-danger';
                }

                html += `
                <li class="feed-item ${colorClass}">
                    <div class="d-flex gap-4 justify-content-between">

                        <div>
                            <div class="mb-2 text-truncate-1-line">
                                <span class="fw-semibold text-dark">
                                    ${fb.status}
                                </span>
                            </div>

                            <p class="fs-12 text-muted mb-3 text-truncate-2-line">
                                ${fb.comment ?? ''}
                            </p>

                            <div>
                                <span class="badge text-success border border-dashed border-gray-500">
                                    ${fb.user ? fb.user.name : 'Unknown'}
                                </span>

                                <span class="badge text-warning border border-dashed border-gray-500">
                                    Order: ${fb.order ? fb.order.order_id : ''}
                                </span>
                            </div>
                        </div>

                        <div class="fs-12 fw-medium text-uppercase text-muted text-nowrap">
                            ${formatDate(fb.created_at)}
                        </div>

                    </div>
                </li>
            `;
            });
        }

        document.getElementById('historyList').innerHTML = html;

        $('#orderHistoryModel').modal('show');
    }

    function formatDate(dateString) {
        let d = new Date(dateString);
        return d.toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Review Save karne ka function
    function saveClientReview() {
        let userId = $('#review_user_id').val();
        let reviewText = $('#review_text').val();

        if (!reviewText.trim()) {
            Swal.fire('Warning', 'Please enter behaviour details', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('user.save.review') }}",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            data: {
                user_id: userId,
                client_review: reviewText
            },
            success: function(response) {
                if (response.success) {
                    $('#clientReviewModal').modal('hide');
                    $('#review_text').val('');

                    Swal.fire({
                        text: "Behaviour saved successfully!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Not Saved',
                        text: response.message || 'Behaviour not saved'
                    });
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message ?? 'Something went wrong!'
                });
            }
        });
    }

    // Modal Open function for Marks
    function openMarksModal(orderId, currentMarks) {
        $('#marks_user_id').val(orderId);
        $('#marks_dropdown').val(currentMarks); // Agar pehle se marks hain toh auto-select ho jayega
        $('#clientMarksModal').modal('show');
    }

    // Save Marks via AJAX
    function saveClientMarks() {
        let orderId = $('#marks_user_id').val();
        let selectedMarks = $('#marks_dropdown').val();

        if (!selectedMarks) {
            alert("Please select a marks range.");
            return;
        }

        $.ajax({
            url: "{{ route('orders.save.marks') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                marks: selectedMarks
            },
            success: function(response) {
                if (response.success) {
                    $('#clientMarksModal').modal('hide');
                    Swal.fire({
                        text: "Marks saved successfully!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            },
            error: function() {
                alert("Something went wrong while saving marks!");
            }
        });
    }
</script>

<script>
    // Modal Open function for Writer Feedback
    function openWriterFeedbackModal(orderId, currentFeedback) {
        $('#feedback_order_id').val(orderId);

        // Agar pehle se rating di hui hai, toh dropdown auto-select ho jayega
        if (currentFeedback !== '') {
            $('#feedback_dropdown').val(currentFeedback);
        } else {
            $('#feedback_dropdown').val(''); // Reset
        }

        $('#writerFeedbackModal').modal('show');
    }

    // Save Feedback via AJAX
    function saveWriterFeedback() {
        let orderId = $('#feedback_order_id').val();
        let selectedRating = $('#feedback_dropdown').val();

        if (selectedRating === "") {
            alert("Please select a rating.");
            return;
        }

        $.ajax({
            url: "{{ route('order.rate.writer') }}", // Controller wala same route use hoga
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                rating: selectedRating
            },
            success: function(response) {
                if (response.success) {
                    $('#writerFeedbackModal').modal('hide');
                    Swal.fire({
                        text: "Writer feedback saved successfully!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then((result) => {
                        // Page refresh taaki Top Writer ka badge update ho sake
                        location.reload();
                    });
                } else {
                    alert("Failed to save feedback.");
                }
            },
            error: function() {
                alert("Something went wrong while saving feedback!");
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function updateAllTimers() {
            const timers = document.querySelectorAll('.order-countdown-timer');
            const now = new Date().getTime();

            timers.forEach(timer => {
                const status = timer.getAttribute('data-status');
                const deadlineStr = timer.getAttribute('data-deadline');
                const updatedStr = timer.getAttribute('data-updated');

                if (!deadlineStr) return;

                const deadlineDate = new Date(deadlineStr).getTime();

                // ==========================================
                // LOGIC 1: ORDER COMPLETED YA MISSED
                // ==========================================
                if (status === 'Completed' || status === 'Delivered') {

                    // Agar updated_at available hai toh check karein
                    if (updatedStr) {
                        const completionDate = new Date(updatedStr).getTime();

                        // Agar order deadline ke baad complete hua hai (MISSED)
                        if (completionDate > deadlineDate) {
                            timer.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i> Missed';
                            timer.className = 'order-countdown-timer fw-bolder fs-8 mt-1 timer-missed';
                            return;
                        }
                    }

                    // Agar time pe complete hua hai (COMPLETED)
                    timer.innerHTML = '<i class="fa fa-check-circle me-1"></i> Completed';
                    timer.className = 'order-countdown-timer fw-bolder fs-8 mt-1 timer-success';
                    return;
                }

                // ==========================================
                // LOGIC 2: COUNTDOWN CALCULATION
                // ==========================================
                const distance = deadlineDate - now;

                // Agar Deadline nikal gayi aur Order complete nahi hua (OVERDUE)
                if (distance < 0) {
                    const pastDistance = Math.abs(distance);
                    const days = Math.floor(pastDistance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((pastDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((pastDistance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((pastDistance % (1000 * 60)) / 1000);

                    timer.innerHTML = `Overdue: ${days}d ${hours}h ${minutes}m ${seconds}s`;
                    timer.className = 'order-countdown-timer fw-bolder fs-8 mt-1 timer-danger';
                    return;
                }

                // Normal Timer chal raha hai
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let timeText = '';
                if (days > 0) timeText += `${days}d `;
                timeText += `${hours}h ${minutes}m ${seconds}s`;

                timer.innerHTML = timeText;

                // Agar 30 minute ya usse kam bache hain (BLINK RED)
                if (days === 0 && hours === 0 && minutes < 30) {
                    timer.className = 'order-countdown-timer fw-bolder fs-8 mt-1 timer-blink-danger';
                } else {
                    // Normal time bacha hai (BLUE)
                    timer.className = 'order-countdown-timer fw-bolder fs-8 mt-1 timer-primary';
                }
            });
        }

        setInterval(updateAllTimers, 1000);
        updateAllTimers();

    // Looking for refund model
        window.markLookingForRefund = function(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Mark this order as looking for refund?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('orders.looking.refund') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order_id: orderId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        }
    });
</script>
<script>
function openAdditionalModal(orderId) {
    $('#additional_order_id').val(orderId);
    $('#additional_word_count').val('');
    $('#additional_price').val('');
    $('#additionalModal').modal('show');
}

function saveAdditionalDetails() {
    let orderId = $('#additional_order_id').val();
    let wordCount = $('#additional_word_count').val();
    let price = $('#additional_price').val();

    if (!wordCount && !price) {
        Swal.fire('Warning', 'Please enter word count or price', 'warning');
        return;
    }

    $.ajax({
        url: "{{ route('orders.additional.save') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            order_id: orderId,
            additional_word_count: wordCount,
            additional_price: price
        },
        success: function(response) {
            $('#additionalModal').modal('hide');

            Swal.fire({
                icon: 'success',
                title: response.message,
                timer: 1200,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire('Error', 'Additional details not saved', 'error');
        }
    });
}

function openAdditionalHistory(orderId) {
    $('#additionalHistoryBody').html('Loading...');
    $('#additionalHistoryModal').modal('show');

    $.ajax({
        url: "{{ url('/orders/additional-history') }}/" + orderId,
        type: "GET",
        success: function(response) {
            $('#additionalHistoryBody').html(response.html);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $('#additionalHistoryBody').html('<div class="text-danger text-center">History load nahi ho payi.</div>');
        }
    });
}
</script>
@endsection