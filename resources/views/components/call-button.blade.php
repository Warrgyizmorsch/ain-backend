@props([
    'phone' => '',
    'countrycode' => '',
    'name' => '',
    'id' => null,
])

@php
    $btnId = $id ? 'twilioCallBtn' . $id : null;
@endphp

<a href="#"
   @if($btnId) id="{{ $btnId }}" @endif
   onclick="event.preventDefault(); initiateCustomerCall({{ Js::from((string) $countrycode) }} + {{ Js::from((string) $phone) }}, {{ Js::from((string) $name) }});"
   title="Call via Twilio"
   {{ $attributes->merge(['class' => 'btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1']) }}>
    <span class="svg-icon svg-icon-3">
        <i class="fa fa-phone fa-lg"></i>
    </span>
</a>
