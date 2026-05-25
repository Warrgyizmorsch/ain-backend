@extends('layouts.app')
@section('content')

    @include('history.user.filterForm')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-flex justify-content-between align-items-center mb-5">
                            <h5>Currently Logged In Users</h5>
                        </div>

                        <div id="user-list">
                            @include('history.user.userList')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    <script>
        $(function () {
                function fetchFilteredUsers(url = null) {
                    const data = $('#filterForm').serialize();
                    $.ajax({
                        url: url || "{{ route('admin.user.session.filter') }}",
                        method: 'GET',
                        data: data,
                        beforeSend: function () {
                            $('#user-list').html('<p>Loading...</p>');
                        },
                        success: function (response) {
                            $('#user-list').html(response);
                        },
                        error: function () {
                            alert('Something went wrong!');
                        }
                    });
                }

                // On filter submit
                $('#filterForm').on('submit', function (e) {
                    e.preventDefault();
                    fetchFilteredUsers();
                });

                // On reset click
                $('#resetFilter').on('click', function () {
                    $('#filterForm')[0].reset();
                    fetchFilteredUsers();
                });

                $(document).on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    const urlParams = $(this).attr('href').split('?')[1]; // get just the query string
                    const ajaxUrl = "{{ route('admin.user.session.filter') }}" + '?' + urlParams;

                    fetchFilteredUsers(ajaxUrl);
                });

            });

    </script>


@endsection