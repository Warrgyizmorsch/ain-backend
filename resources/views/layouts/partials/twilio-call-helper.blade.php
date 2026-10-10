@php
    $n2cPlugin = \App\Models\PluginSetting::where('plugin_key', 'next2call')->first();
    $isN2cActive = (bool) ($n2cPlugin?->is_active ?? true);
    $twPlugin = \App\Models\PluginSetting::where('plugin_key', 'twilio_call')->first();
    $isTwActive = (bool) ($twPlugin?->is_active ?? true);
@endphp
<script>
    // Helper to resolve contact phone & name across CRM pages & WhatsApp chat
    function crmResolveCallTarget(phoneRaw, nameRaw) {
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
        // Strip leading zeros if 11 digits (e.g. 09610092299 -> 9610092299)
        let digitsOnly = cleanPhone.replace(/\D/g, '');
        if (digitsOnly.startsWith('0') && digitsOnly.length === 11) {
            digitsOnly = digitsOnly.slice(1);
            cleanPhone = cleanPhone.startsWith('+') ? ('+' + digitsOnly) : digitsOnly;
        }
        return { cleanPhone, name, rawPhone: raw };
    }

    // 1. Direct Twilio Voice Call Trigger
    window.initiateTwilioCall = function(phoneRaw, nameRaw) {
        const target = crmResolveCallTarget(phoneRaw, nameRaw);
        let cleanPhone = target.cleanPhone;
        const name = target.name;
        let digits = cleanPhone.replace(/\D/g, '');

        if (!cleanPhone || digits.length < 7) {
            const warningMsg = (!cleanPhone && (!phoneRaw || String(phoneRaw).trim() === ''))
                ? 'Please select a customer or chat conversation first.'
                : 'This contact does not have a valid phone number.';
            if (typeof toastr !== 'undefined') toastr.warning(warningMsg);
            else alert(warningMsg);
            return;
        }

        // Clean leading zero
        if (digits.startsWith('0') && digits.length === 11) {
            digits = digits.slice(1);
        }

        // Normalize to E.164
        let phoneToDial = '';
        if (digits.length === 10 && /^[6-9]/.test(digits)) {
            phoneToDial = '+91' + digits;
        } else if (digits.length === 12 && digits.startsWith('91')) {
            phoneToDial = '+' + digits;
        } else if (cleanPhone.startsWith('+')) {
            phoneToDial = '+' + digits;
        } else {
            phoneToDial = '+' + digits;
        }

        if (window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
            console.log('[Twilio Softphone] Calling:', phoneToDial, name);
            window.twilioSoftphone.makeCall(phoneToDial, name);
            return;
        }

        if (typeof toastr !== 'undefined') toastr.error('Twilio Voice softphone is not ready or active.');
        else alert('Twilio Voice softphone is not ready or active.');
    };

    // 2. Direct Next2Call PBX Softphone Trigger (with red '2' icon)
    window.initiateNext2Call = function(phoneRaw, nameRaw) {
        const target = crmResolveCallTarget(phoneRaw, nameRaw);
        const cleanPhone = target.cleanPhone;
        const name = target.name;
        const digits = cleanPhone.replace(/\D/g, '');

        if (!cleanPhone || digits.length < 7) {
            const warningMsg = (!cleanPhone && (!phoneRaw || String(phoneRaw).trim() === ''))
                ? 'Please select a customer or chat conversation first.'
                : 'This contact does not have a valid phone number.';
            if (typeof toastr !== 'undefined') toastr.warning(warningMsg);
            else alert(warningMsg);
            return;
        }

        if (typeof window.dialNext2CallNumber === 'function') {
            console.log('[Next2Call Softphone] Calling:', cleanPhone, name);
            window.dialNext2CallNumber(cleanPhone, '', name);
            return;
        }

        if (typeof toastr !== 'undefined') toastr.error('Next2Call softphone is not ready or active.');
        else alert('Next2Call softphone is not ready or active.');
    };

    // 3. Global call trigger — backwards compatible fallback
    window.initiateCustomerCall = function(phoneRaw, nameRaw) {
        const isN2cConfigured = {{ $isN2cActive ? 'true' : 'false' }};
        const isTwilioConfigured = {{ $isTwActive ? 'true' : 'false' }};

        if (isN2cConfigured && typeof window.dialNext2CallNumber === 'function') {
            return window.initiateNext2Call(phoneRaw, nameRaw);
        }
        if (isTwilioConfigured && window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
            return window.initiateTwilioCall(phoneRaw, nameRaw);
        }
        if (typeof window.dialNext2CallNumber === 'function') {
            return window.initiateNext2Call(phoneRaw, nameRaw);
        }
        if (window.twilioSoftphone && typeof window.twilioSoftphone.makeCall === 'function') {
            return window.initiateTwilioCall(phoneRaw, nameRaw);
        }

        if (typeof toastr !== 'undefined') toastr.error('No calling plugin (Next2Call or Twilio) is active or ready.');
        else alert('No calling plugin (Next2Call or Twilio) is active or ready.');
    };
</script>
