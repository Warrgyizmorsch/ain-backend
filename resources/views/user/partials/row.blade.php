{{-- mk 5 10 26 - Single User Row for User Management Table --}}
<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="symbol symbol-45px me-5">
                @if($user->photo)
                    <img src="{{ asset($user->photo) }}" alt="">
                @else
                  <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="">
                @endif
            </div>
            <div class="d-flex justify-content-start flex-column">
                <a href="#" class="text-dark fw-bolder text-hover-primary fs-6">{{$user->name}}</a>
                <span class="text-muted fs-7">
                    <i class="fa fa-globe text-info"></i>
                    @php
                        $cleanCode = ltrim((string) $user->countrycode, '+');
                    @endphp
                    {{ $codeToCountry[$cleanCode] ?? 'Unknown' }}
                </span>
                @php
                    $count = $user->orders_count;
                    
                    if($count > 10) { 
                        $class = "badge-light-success"; $label = "Loyal Customer"; 
                    } elseif($count >= 4) { 
                        $class = "badge-light-warning"; $label = "Retainer"; 
                    } else { 
                        $class = "badge-light-info"; $label = "Beginner"; 
                    }
                @endphp
                <span class="badge {{ $class }} fw-bold fs-8" style="width: fit-content;">{{ $label }}</span>
            </div>
        </div>
    </td>
    <td>
        <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">{{ mask_phone_for_display($user->countrycode, $user->mobile_no) }}</a>
        <span class="text-muted fw-bold text-muted d-block fs-7">{{ mask_email_for_display($user->email) }}</span>
    </td>
    <td data-user-group-badges="{{ $user->id }}">@forelse($user->groups as $group)<span class="badge badge-light-primary me-1 mb-1">{{ $group->name }}</span>@empty<span class="text-muted">-</span>@endforelse</td>
    <td>
        @if(($user->total_orders_count ?? 0) > 0)
            <span class="badge badge-light-success fw-bolder">Confirmed</span>
            <span class="text-muted d-block fs-8 mt-1">{{ $user->total_orders_count }} total order(s)</span>
        @elseif(($user->leads_count ?? 0) > 0)
            <span class="badge badge-light-warning fw-bolder">Not Confirmed</span>
            <span class="text-muted d-block fs-8 mt-1">{{ $user->leads_count }} lead(s), no order</span>
        @else
            <span class="badge badge-light-secondary fw-bolder">No Activity</span>
            <span class="text-muted d-block fs-8 mt-1">No lead or order</span>
        @endif
    </td>
    <td class="text-end">
        <div class="d-flex flex-column w-100 me-2">
            <div class="d-flex flex-stack mb-2">
            @if($user->role_id == 1)
                <span class="text-muted me-2 fs-7 fw-bold">Super Admin</span>
            @elseif($user->role_id == 2)
            <span class="text-muted me-2 fs-7 fw-bold">User</span>
            @elseif($user->role_id == 3)
            <span class="text-muted me-2 fs-7 fw-bold">Sub Admin</span>
            @elseif($user->role_id == 4)
            <span class="text-muted me-2 fs-7 fw-bold">Marketing Team</span>
            @elseif($user->role_id == 5)
            <span class="text-muted me-2 fs-7 fw-bold">Project Team</span>
            @elseif($user->role_id == 6)
            <span class="text-muted me-2 fs-7 fw-bold">WriterTL</span>
            @elseif($user->role_id == 7)
            <span class="text-muted me-2 fs-7 fw-bold">Sub Writer</span>
            @elseif($user->role_id == 8)
            <span class="text-muted me-2 fs-7 fw-bold">Writer Admin</span>
            @endif  
            </div>
            
        </div>
    </td>
    <td class="text-end">
        <div class="d-flex flex-column w-100 me-2">
            <div class="d-flex flex-stack mb-2">
                @if($user->created_at != '')
                <span class="text-muted me-2 fs-7 fw-bold">{{$user->created_at->format('d M Y D (h:i:s a)')}}</span>
                @endif
            </div>
            
        </div>
    </td>
    <td>
        <span class="badge badge-circle badge-primary fw-bold">{{ $user->orders_count }}</span>
    </td>
    <td>
        <span class="badge badge-circle badge-info fw-bold">{{ $user->followups_count ?? 0 }}</span>
    </td>
    <td>
        <div class="dropdown text-end">
            <button class="btn btn-sm btn-light-primary dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">Actions</button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="{{ route('orders.index', ['uid' => $user->id]) }}"><i class="fa fa-shopping-cart me-2 text-primary"></i>Orders</a></li>
                <li><button type="button" class="dropdown-item" data-user-group-button="{{ $user->id }}" data-groups='@json($user->groups->pluck("id"))' onclick="openUserGroupModal({{ $user->id }}, @js($user->name), JSON.parse(this.dataset.groups))"><i class="fa fa-users me-2 text-success"></i>Groups</button></li>
                <li><a class="dropdown-item" href="{{ route('user.report', $user->id) }}"><i class="fa fa-eye me-2 text-info"></i>View Profile</a></li>
                <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app{{$user->id}}"><i class="fa fa-edit me-2 text-warning"></i>Edit User</button></li>
                <li><hr class="dropdown-divider"></li>
                <li><button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#kt_modal_create_delete{{$user->id}}"><i class="fa fa-trash me-2"></i>Delete User</button></li>
            </ul>
        </div>
        <div class="d-none">
            <button type="button" class="btn btn-sm btn-light-success fw-bold me-1" data-user-group-button="{{ $user->id }}" data-groups='@json($user->groups->pluck("id"))' onclick="openUserGroupModal({{ $user->id }}, @js($user->name), JSON.parse(this.dataset.groups))">+G</button>
            <a data-bs-toggle="modal" data-bs-target="#kt_modal_create_app{{$user->id}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path>
                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path>
                    </svg>
                </span>
            </a>
            <a data-bs-toggle="modal" data-bs-target="#kt_modal_create_delete{{$user->id}}" href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path>
                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path>
                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path>
                    </svg>
                </span>
            </a>
            <a href="{{ route('user.report', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
               <i class="fa fa-eye"></i>
            </a>
        </div>

        @include('user.section.edit-section')
    </td>
</tr>
