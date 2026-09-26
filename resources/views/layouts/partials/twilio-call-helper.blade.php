@php
    $n2cPlugin = \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    $isN2cActive = (bool) ($n2cPlugin?->is_active ?? true);
    $twPlugin = \App\Models\PluginSetting::where('plugin_key', 'twilio_call')->first();
    $isTwActive = (bool) ($twPlugin?->is_active ?? true);
@endphp
<script>
    // Global call trigger — used by call buttons across Orders, Follow-ups, Next Follow-ups, Leads, Search, WhatsApp, etc.
    // Seamlessly dispatches calls to Next2Call Softphone or Twilio Voice based on active plugin configuration.
    window.initiateCustomerCall = function(phoneRaw, nameRaw) {
        let raw = phoneRaw;
        let name = nameRaw;

        // If phone is missing or contains mask asterisks ('*'), try getting the unmasked phone from known global CRM / Chat variables
        if (!raw || String(raw).includes('*')) {
            raw = (typeof window.selectedCustomerPhone !== 'undefined' && window.selectedCustomerPhone && !String(window.selectedCustomerPhone).includes('*')) ? window.selectedCustomerPhone :
                  (typeof window.selectedPhone !== 'undefined' && window.selectedPhone && !String(window.selectedPhone).includes('*')) ? window.selectedPhone :
                  (typeof selectedPhone !== 'undefined' && selectedPhone && !String(selectedPhone).includes('*')) ? selectedPhone :
                  document.querySelector('#wabMessagesBody')?.getAttribute('data-selected-phone') ||
                  document.querySelector('.wab-contact-item.is-active')?.getAttribute('data-phone') ||
                  document.querySelector('.wab-conv-footer input[name="phone"]')?.value ||
                  document.getElementById('waAssignPhoneInput')?.value ||
                  '';
        }

        if (!name || name === 'Customer') {
            name = (typeof window.selectedCustomerName !== 'undefined' && window.selectedCustomerName) ? window.selectedCustomerName :
                   (typeof window.currentCustomerName !== 'undefined' && window.currentCustomerName) ? window.currentCustomerName :
                   document.querySelector('.wab-conv-name')?.textContent?.trim() ||
                   document.querySelector('.wab-contact-item.is-active')?.getAttribute('data-name') ||
                   'Customer';
        }

        let cleanPhone = String(raw || '').replace(/[^\d+]/g, '');
        let digits = cleanPhone.replace(/\D/g, '');

        if (!cleanPhone || digits.length < 7) {
            const warningMsg = (!cleanPhone && (!phoneRaw || String(phoneRaw).trim() === ''))
                ? 'Please select a customer or chat conversation first.'
                : 'This contact does not have a valid phone number.';
            if (typeof toastr !== 'undefined') {
                toastr.warning(warningMsg);
            } else {
                alert(warningMsg);
            }
            return;
        }

        const isN2cConfigured = {{ $isN2cActive ? 'true' : 'false' }};
        const isTwilioConfigured = {{ $isTwActive ? 'true' : 'false' }};

        // 1. Next2Call Softphone (Direct in-browser WebRTC click-to-dial via Ringfy PBX)
        if (isN2cConfigured && typeof window.dialNext2CallNumber === 'function') {
            console.log('[Softphone Dispatcher] Calling via Next2Call Softphone:', cleanPhone, name);
            window.dialNext2CallNumber(cleanPhone, '', name);
            return;
        }

        // 2. Twilio Voice Softphone
        if (isTwilioConfigured && window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
            if (!cleanPhone.startsWith('+')) {
                cleanPhone = '+' + cleanPhone;
            }
            console.log('[Softphone Dispatcher] Calling via Twilio Softphone:', cleanPhone);
            window.twilioSoftphone.makeCall(cleanPhone, name);
            return;
        }

        // 3. Fallback: If Next2Call dial function is ready on the page
        if (typeof window.dialNext2CallNumber === 'function') {
            console.log('[Softphone Dispatcher] Calling via Next2Call (fallback):', cleanPhone, name);
            window.dialNext2CallNumber(cleanPhone, '', name);
            return;
        }

        // 4. Fallback: If Twilio softphone is loaded on the page
        if (window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
            if (!cleanPhone.startsWith('+')) {
                cleanPhone = '+' + cleanPhone;
            }
            console.log('[Softphone Dispatcher] Calling via Twilio (fallback):', cleanPhone);
            window.twilioSoftphone.makeCall(cleanPhone, name);
            return;
        }

        if (typeof toastr !== 'undefined') {
            toastr.error('No calling plugin (Next2Call or Twilio) is active or ready.');
        } else {
            alert('No calling plugin (Next2Call or Twilio) is active or ready.');
        }
    };
</script>
