{{-- mk 5 10 26 - Batch User Rows for Infinite Scroll --}}
@forelse($users as $user)
    @include('user.partials.row', ['user' => $user, 'codeToCountry' => $codeToCountry, 'data' => $data])
@empty
    @if(request('page', 1) == 1)
        <tr>
            <td colspan="9" class="text-center py-10">
                <i class="fa fa-search fs-2x text-muted mb-3"></i>
                <div class="fw-bolder text-gray-700 fs-5">No users found</div>
                <div class="text-muted fs-7">Try changing the filters and search again.</div>
            </td>
        </tr>
    @endif
@endforelse
