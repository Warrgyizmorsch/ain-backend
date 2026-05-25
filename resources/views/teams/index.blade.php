@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">
        <div class="row">
            <!-- Left Column: Create/Edit Form -->
            <div class="col-md-5">
                <div class="card card-flush mb-5" id="teamFormCard">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title" id="formTitle">Create New Team</h3>
                    </div>
                    <div class="card-body">
                        <form id="teamForm">
                            @csrf
                            <input type="hidden" name="team_id" id="team_id">

                            <div class="mb-4">
                                <label class="form-label required">Team Name</label>
                                <input type="text" class="form-control" name="team_name" id="team_name" required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Priority</label>
                                    <input type="number" class="form-control" name="priority" id="priority">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Percentage</label>
                                    <input type="number" class="form-control" name="percentage" id="percentage">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Select Members</label>
                                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                    <div class="row" id="memberListContainer">
                                        @foreach ($members as $member)
                                            <div class="col-12 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input member-checkbox" type="checkbox" value="{{ $member->id }}" id="member_{{ $member->id }}">
                                                    <label class="form-check-label" for="member_{{ $member->id }}">{{ $member->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <div class="text-end">
                                <button type="reset" class="btn btn-light me-2" onclick="resetForm()">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Create Team</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Right Column: Team List -->
            <div class="col-md-7">
                <div class="card card-flush">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Teams</h3>
                    </div>
                    <div class="card-body" id="teamListContainer" style="overflow-x: auto;">
                        @include('teams.partials.list', ['teams' => $teams])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
function resetForm() {
    $('#teamForm')[0].reset();
    $('#team_id').val('');
    $('#submitBtn').text('Create Team');
    $('#formTitle').text('Create New Team');
    $('.member-checkbox').prop('checked', false);
    location.reload();
}

$('#teamForm').on('submit', function (e) {
    e.preventDefault();

    const formData = {
        _token: $('input[name="_token"]').val(),
        team_id: $('#team_id').val(),
        team_name: $('#team_name').val(),
        priority: $('#priority').val(),
        percentage: $('#percentage').val(),
        member_ids: $('.member-checkbox:checked').map(function () {
            return $(this).val();
        }).get()
    };

    const isEdit = formData.team_id !== '';
    const url = isEdit ? '{{ route("teams.update") }}' : '{{ route("teams.store") }}';

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        success: function (response) {
            resetForm();
            $('#teamListContainer').html(response.view);

            // ✅ Update member checkbox list on the form
            let html = '';
            response.members.forEach(function (member) {
                html += `
                    <div class="col-12 mb-2">
                        <div class="form-check">
                            <input class="form-check-input member-checkbox" type="checkbox" value="${member.id}" id="member_${member.id}">
                            <label class="form-check-label" for="member_${member.id}">${member.name}</label>
                        </div>
                    </div>
                `;
            });
            $('#memberListContainer').html(html); // Replace the current member list
        },
        error: function (xhr) {
            let errorMessage = 'Failed to submit the form. Please check your input.';

            // If Laravel returns a JSON error message (e.g. for percentage check)
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });

            console.error(xhr.responseText);
        }

    });
});
</script>
<script>
function editTeam(teamId) {
    $.ajax({
        url: `/teams/${teamId}/edit`,
        method: 'GET',
        success: function (response) {
            $('#team_id').val(response.id);
            $('#team_name').val(response.team_name);
            $('#priority').val(response.priority);
            $('#percentage').val(response.percentage);

            // Dynamically build member list
            let html = '';
            response.members.forEach(function (member) {
                const checked = response.member_ids.includes(member.id) ? 'checked' : '';
                html += `
                    <div class="col-12 mb-2">
                        <div class="form-check">
                            <input class="form-check-input member-checkbox" type="checkbox" value="${member.id}" id="member_${member.id}" ${checked}>
                            <label class="form-check-label" for="member_${member.id}">${member.name}</label>
                        </div>
                    </div>
                `;
            });

            $('#memberListContainer').html(html); // Replace the entire list

            $('#formTitle').text('Edit Team');
            $('#submitBtn').text('Update Team');

            $('html, body').animate({
                scrollTop: $('#teamFormCard').offset().top
            }, 500);
        },
        error: function (xhr) {
            let errorMessage = 'Failed to submit the form. Please check your input.';

            // If Laravel returns a JSON error message (e.g. for percentage check)
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });

            console.error(xhr.responseText);
        }

    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteTeam(teamId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the team!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/teams/${teamId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#teamListContainer').html(response.view);
                    resetForm(); // Reset form in case you're editing the deleted team
                    Swal.fire(
                        'Deleted!',
                        'The team has been deleted.',
                        'success'
                    ).then(() => {
                        location.reload(); // Reload page after success alert
                    });
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Failed to delete the team.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>

@endsection

