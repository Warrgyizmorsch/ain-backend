<!-- Modal: Trigger Twilio Call for Order -->
<div class="modal fade" id="twilioOrderCallModal" tabindex="-1" aria-labelledby="twilioOrderCallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="twilioOrderCallModalLabel">
                    <i class="fa fa-phone text-primary me-2"></i> Twilio Call Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="twilioOrderCallForm">
                @csrf
                <input type="hidden" id="twilio_order_id" name="order_id" value="">
                
                <div class="modal-body p-6">
                    <div class="card bg-light-primary border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted fs-7">Order ID:</span>
                                <span class="fw-bolder text-dark" id="twilio_modal_order_label">#</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted fs-7">Customer Name:</span>
                                <span class="fw-bolder text-dark" id="twilio_modal_customer_name">-</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted fs-7">Customer Phone:</span>
                                <span class="fw-bolder text-primary fs-6" id="twilio_modal_customer_phone">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Call Method:</label>
                        <div class="d-flex gap-3">
                            <div class="form-check form-check-custom form-check-solid flex-fill border rounded p-3 cursor-pointer">
                                <input class="form-check-input" type="radio" name="call_method" value="webrtc" id="method_webrtc" checked>
                                <label class="form-check-label fw-bold ms-2 cursor-pointer" for="method_webrtc">
                                    <i class="fa fa-laptop text-primary me-1"></i> Browser Softphone
                                    <div class="text-muted fs-8">Direct Mic/Headset call (Hold, Cut, Mute)</div>
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid flex-fill border rounded p-3 cursor-pointer">
                                <input class="form-check-input" type="radio" name="call_method" value="bridge" id="method_bridge">
                                <label class="form-check-label fw-bold ms-2 cursor-pointer" for="method_bridge">
                                    <i class="fa fa-mobile-phone text-success me-1"></i> Mobile Bridge Call
                                    <div class="text-muted fs-8">Twilio rings your phone first</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="twilioAgentPhoneBox" class="mb-3 d-none">
                        <label class="form-label fw-bold">Your Agent Phone Number:</label>
                        <input type="text" id="twilio_agent_phone" name="agent_phone" class="form-control font-monospace" placeholder="e.g. +917850888522" value="{{ auth()->user()->mobile ?? auth()->user()->mobile_no ?? '' }}">
                        <div class="text-muted fs-8 mt-1">
                            Twilio will ring this number first. When answered, it will dial the customer.
                        </div>
                    </div>

                    <div id="twilioOrderCallAlert" class="d-none mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="twilioCallSubmitBtn" class="btn btn-success">
                        <i class="fa fa-phone me-1"></i> <span id="twilioCallSubmitBtnText">Start Call Now</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentTwilioTargetPhone = '';
let currentTwilioCustomerName = '';

$('input[name="call_method"]').on('change', function() {
    if ($(this).val() === 'bridge') {
        $('#twilioAgentPhoneBox').removeClass('d-none');
    } else {
        $('#twilioAgentPhoneBox').addClass('d-none');
    }
});

function triggerTwilioCall(orderId, customerName, countryCode, mobile) {
    $('#twilio_order_id').val(orderId);
    $('#twilio_modal_order_label').text('#' + orderId);
    $('#twilio_modal_customer_name').text(customerName || 'N/A');
    currentTwilioCustomerName = customerName || 'Customer';
    
    let cleanCode = (countryCode || '').replace(/[^\d+]/g, '');
    let cleanMobile = (mobile || '').replace(/\D/g, '');
    let formattedPhone = cleanMobile;
    if (cleanCode && !cleanMobile.startsWith(cleanCode.replace('+', ''))) {
        formattedPhone = (cleanCode.startsWith('+') ? cleanCode : '+' + cleanCode) + cleanMobile;
    } else if (!formattedPhone.startsWith('+')) {
        formattedPhone = '+' + formattedPhone;
    }

    currentTwilioTargetPhone = formattedPhone;
    $('#twilio_modal_customer_phone').text(formattedPhone.trim() || 'No phone provided');
    
    $('#twilioOrderCallAlert').addClass('d-none').html('');
    $('#twilioCallSubmitBtn').prop('disabled', false);
    $('#twilioCallSubmitBtnText').text('Start Call Now');
    
    $('#twilioOrderCallModal').modal('show');
}

$('#twilioOrderCallForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#twilioCallSubmitBtn');
    const alertBox = $('#twilioOrderCallAlert');
    const orderId = $('#twilio_order_id').val();
    const method = $('input[name="call_method"]:checked').val();

    if (!orderId) {
        Swal.fire('Error', 'Invalid Order selected.', 'error');
        return;
    }

    // 1. Direct WebRTC Browser Calling
    if (method === 'webrtc') {
        if (!currentTwilioTargetPhone || currentTwilioTargetPhone === '+') {
            Swal.fire('Error', 'Customer does not have a valid phone number.', 'warning');
            return;
        }

        $('#twilioOrderCallModal').modal('hide');
        if (window.twilioSoftphone) {
            window.twilioSoftphone.makeCall(currentTwilioTargetPhone, currentTwilioCustomerName + ' (Order #' + orderId + ')');
        } else {
            Swal.fire('Error', 'Softphone widget is loading. Please try again in a moment.', 'warning');
        }
        return;
    }

    // 2. Bridge Call Mode
    const agentPhone = $('#twilio_agent_phone').val().trim();
    btn.prop('disabled', true);
    $('#twilioCallSubmitBtnText').text('Calling...');
    alertBox.removeClass('d-none').html(`
        <div class="alert alert-light-info d-flex align-items-center mb-0">
            <i class="fa fa-spinner fa-spin fs-4 text-info me-3"></i>
            <div>Dialing your agent phone <strong>${agentPhone || 'configured'}</strong>... Please answer the incoming call to connect with customer.</div>
        </div>
    `);

    $.ajax({
        url: "{{ route('plugins.twilio.order.call') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            order_id: orderId,
            agent_phone: agentPhone
        },
        success: function(res) {
            btn.prop('disabled', false);
            $('#twilioCallSubmitBtnText').text('Start Call Now');
            alertBox.html(`
                <div class="alert alert-success d-flex align-items-center mb-0">
                    <i class="fa fa-check-circle fs-3 text-success me-3"></i>
                    <div>
                        <strong>Call Initiated!</strong><br>
                        ${res.message} (Status: <span class="badge badge-success">${res.status || 'queued'}</span>)
                    </div>
                </div>
            `);

            setTimeout(function() {
                $('#twilioOrderCallModal').modal('hide');
            }, 3000);
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            $('#twilioCallSubmitBtnText').text('Start Call Now');
            let errMsg = xhr.responseJSON?.message || 'Failed to initiate Twilio call.';
            alertBox.html(`
                <div class="alert alert-danger d-flex align-items-center mb-0">
                    <i class="fa fa-exclamation-triangle fs-3 text-danger me-3"></i>
                    <div><strong>Call Failed:</strong> ${errMsg}</div>
                </div>
            `);
        }
    });
});
</script>
