@php
    $alertTypes = ['success', 'error', 'warning', 'info'];
@endphp

@foreach ($alertTypes as $type)
    @if ($message = Session::get($type))
        <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
        </div>
        <script>
            setTimeout(function () {
                $('.alert-{{ $type }}').alert('close');
            }, 5000);
        </script>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>You are not authenticated. Please try again!</strong>
    </div>
    <script>
        setTimeout(function () {
            $('.alert-danger').alert('close');
        }, 5000);
    </script>
@endif
