<script>
    // Global Twilio call trigger — used by the call-button component and any
    // page-specific "Call" buttons. Backed by window.twilioSoftphone (see twilio-softphone-widget).
    if (typeof window.initiateCustomerCall !== 'function') {
        window.initiateCustomerCall = function(phoneRaw, nameRaw) {
            const name = nameRaw || 'Customer';
            let cleanPhone = (phoneRaw || '').replace(/[^\d+]/g, '');

            if (!cleanPhone) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('This contact does not have a valid phone number.');
                } else {
                    alert('This contact does not have a valid phone number.');
                }
                return;
            }

            if (!cleanPhone.startsWith('+')) {
                cleanPhone = '+' + cleanPhone;
            }

            if (window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
                window.twilioSoftphone.makeCall(cleanPhone, name);
            } else if (typeof toastr !== 'undefined') {
                toastr.error('Twilio Voice softphone is not ready or active.');
            } else {
                alert('Twilio Voice softphone is not ready or active.');
            }
        };
    }
</script>
