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

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li><strong>{{ $error }}</strong></li>
            @endforeach
        </ul>
    </div>
    <script>
        setTimeout(function () {
            $('.alert-danger').alert('close');
        }, 8000);
    </script>
@endif
