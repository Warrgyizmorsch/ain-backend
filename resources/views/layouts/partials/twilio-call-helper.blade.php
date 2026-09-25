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
        const name = nameRaw || 'Customer';
        let cleanPhone = String(phoneRaw || '').replace(/[^\d+]/g, '');

        if (!cleanPhone) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('This contact does not have a valid phone number.');
            } else {
                alert('This contact does not have a valid phone number.');
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
