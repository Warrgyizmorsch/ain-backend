@extends('layouts.app')
@section('content')

<style>
.uni-order-table thead th{
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 2;
}
</style>

<div class="card">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title fw-bolder">
            University Report
        </h3>
    </div>
    <div class="card card-xxl-stretch mb-5 mb-xl-8 ms-9">
        <form method="GET" action="{{ route('university.report') }}" class="mb-5">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">
                        From Date
                    </label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        To Date
                    </label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div class="col-md-3 position-relative">
                    <label class="form-label">
                        University
                    </label>
                    <input type="text" id="university_search" class="form-control" placeholder="Search university" autocomplete="off" value="{{ request('university') }}">
                    <input type="hidden" name="university" id="university" value="{{ request('university') }}">
                    <div id="university_result" class="bg-white border rounded shadow-sm position-absolute w-100" style="display:none; z-index:9999; max-height:220px; overflow-y:auto;">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        Search
                    </button>
                    <a href="{{ route('university.report') }}" class="btn btn-sm btn-danger">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body py-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th width="80">#</th>
                        <th>
                            University
                        </th>
                        <th width="180">
                            Count
                            @if($sort == 'desc')
                                <a href="{{ route('university.report', ['sort' => 'asc']) }}">
                                    ↑
                                </a>
                            @else
                                <a href="{{ route('university.report', ['sort' => 'desc']) }}">
                                    ↓
                                </a>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($universities as $key => $uni)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $uni->college_name }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light-primary toggle-uni-orders" data-target="uni-orders-{{ $key }}">
                                    {{ $uni->total_users }}
                                </button>
                            </td>
                        </tr>
                        <tr id="uni-orders-{{ $key }}" style="display:none;">
                            <td colspan="3">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h5 class="mb-4">
                                            Users For University:
                                            {{ $uni->college_name }}
                                        </h5>
                                        <div class="table-responsive" style="max-height:600px; overflow-y:auto; overflow-x:auto; border:1px solid #ddd; border-radius:10px;">
                                            <table class="table table-bordered table-sm align-middle uni-order-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>User Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>User Type</th>
                                                        <th>Total Orders</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($uni->users as $uKey => $user)
                                                    @php
                                                        $orderCount = \App\Models\Order::where('uid', $user->id)->count();
                                                        if($orderCount > 10) {
                                                            $class = "badge-light-success";
                                                            $label = "Loyal Customer";
                                                        } elseif($orderCount >= 4) {
                                                            $class = "badge-light-warning";
                                                            $label = "Repeated";
                                                        } else {
                                                            $class = "badge-light-info";
                                                            $label = "Beginner";
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $uKey + 1 }}
                                                        </td>
                                                        <td>
                                                            {{ $user->name }}
                                                            @if(!empty($user->client_review))
                                                                <span class="duplicate-info-wrapper d-inline-block mt-1">
                                                                    <a class="text-info" style="font-size:13px;">
                                                                        <i class="fa fa-info-circle"></i>
                                                                    </a>
                                                                    <div class="duplicate-popup shadow">
                                                                        <span>{{$user->client_review}}</span>
                                                                    </div>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $user->email }}
                                                        </td>
                                                        <td>
                                                            +{{ $user->countrycode }}
                                                            {{ $user->mobile_no }}
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $class }} fw-bold fs-8">
                                                                {{ $label }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $orderCount }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                No university found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $universities->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-uni-orders')) {
        let targetId = e.target.getAttribute('data-target');
        let row = document.getElementById(targetId);
        row.style.display =
            row.style.display === 'none'
            ? 'table-row'
            : 'none';
    }
});

$(document).ready(function () {
    let uniTimer = null;
    $('#university_search').on('keyup', function () {
        let search = $(this).val();
        $('#university').val('');
        clearTimeout(uniTimer);
        if (search.length < 2) {
            $('#university_result').hide().html('');
            return;
        }
        uniTimer = setTimeout(function () {
            $.ajax({
                url: "{{ route('search.university') }}",
                type: "GET",
                data: {
                    search: search
                },
                success: function (universities) {
                    let html = '';
                    if (universities.length > 0) {
                        universities.forEach(function (uni) {
                            html += `
                                <div class="uni-item px-3 py-2 border-bottom bg-white"
                                     style="cursor:pointer;"
                                     data-uni="${uni}">
                                    ${uni}
                                </div>
                            `;
                        });
                    } else {
                        html = `
                            <div class="px-3 py-2 text-muted bg-white">
                                No university found
                            </div>
                        `;
                    }
                    $('#university_result')
                        .html(html)
                        .show();
                }
            });
        }, 300);
    });

    $(document).on('click', '.uni-item', function () {
        let uni = $(this).data('uni');
        $('#university_search').val(uni);
        $('#university').val(uni);
        $('#university_result').hide();
    });

    $(document).on('click', function (e) {
        if (
            !$(e.target).closest(
                '#university_search, #university_result'
            ).length
        ) {
            $('#university_result').hide();
        }
    });
});
</script>
@endsection