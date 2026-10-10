@props([
    'phone' => '',
    'countrycode' => '',
    'name' => '',
    'id' => null,
])

@php
    $twilioBtnId = $id ? 'twilioCallBtn' . $id : null;
    $n2cBtnId = $id ? 'n2cCallBtn' . $id : null;
@endphp

{{-- Twilio Call Button --}}
<a href="#"
   @if($twilioBtnId) id="{{ $twilioBtnId }}" @endif
   onclick="event.preventDefault(); initiateTwilioCall({{ Js::from((string) $countrycode) }} + {{ Js::from((string) $phone) }}, {{ Js::from((string) $name) }});"
   title="Call via Twilio"
   {{ $attributes->merge(['class' => 'btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1']) }}>
    <span class="svg-icon svg-icon-3">
        <i class="fa fa-phone fa-lg"></i>
    </span>
</a>

{{-- Next2Call Button (with red 2 badge) --}}
<a href="#"
   @if($n2cBtnId) id="{{ $n2cBtnId }}" @endif
   onclick="event.preventDefault(); initiateNext2Call({{ Js::from((string) $countrycode) }} + {{ Js::from((string) $phone) }}, {{ Js::from((string) $name) }});"
   title="Call via Next2Call"
   {{ $attributes->merge(['class' => 'btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1 position-relative']) }}>
    <span class="svg-icon svg-icon-3">
        <i class="fa fa-phone fa-lg"></i>
    </span>
    <span style="position:absolute;bottom:-2px;right:-1px;background:#e53e3e;color:#ffffff;font-size:8px;font-weight:900;line-height:1;padding:1px 2.5px;border-radius:2px;box-shadow:0 1px 2px rgba(0,0,0,0.3);font-family:Arial,sans-serif;pointer-events:none;">2</span>
</a>
