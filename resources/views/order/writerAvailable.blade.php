@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Writer-Availability
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                    </h3>
                </div>
                <form action="">
                    <div class="card-body py-3">
                        <div class="d-flex">
                            <div class="col-lg-3 fv-row fv-plugins-icon-container me-2">
                                <input type="date" name="available_date"  class="form-control form-control-solid" placeholder="Search By OrderCode">
                            </div>
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <a href="/writer-available" class="btn btn-sm btn-danger">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="card card-xl-stretch mb-xl-">
            <div class="card-body py-3">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Available Writers</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                       <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
    <thead class="p-2">
        <tr class="fw-bolder text-muted bg-light">
            <th class="min-w-55px text-center">SR</th>
            <th class="min-w-150px">Date</th>
            <th class="min-w-150px">Writer Name</th>
        </tr>
    </thead>
    <tbody>
        <!-- First loop -->
        @foreach($users as $writer)
        <tr>
            <td>{{$loop->index + 1}}</td>
            <td>{{$today}}</td>
            <td>{{$writer['name']}}</td>
        </tr>
        @endforeach
        <!-- Second loop starting after the maximum key of the first loop -->
        @php $startingIndex = count($users) + 1; @endphp
        @foreach($usersWithTime as $writer)
        <tr>
            <td>{{$startingIndex + $loop->index}}</td>
            <td>{{$today}}</td>
            <td>
                {{$writer['name']}} free after ({{$writer['writerWork'][0]['order']['writer_ud_h']}})
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
