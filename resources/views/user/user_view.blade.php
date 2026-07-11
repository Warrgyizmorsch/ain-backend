@extends('layouts.app')
@section('content')
@include('back-end.group-master.user-modal')
<style>
    .user-tabs { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .user-tab { border: 1px solid #e4e6ef; border-radius: 12px; padding: 18px; color: #5e6278; background: #fff; transition: .2s ease; }
    .user-tab:hover { border-color: #b5b5c3; transform: translateY(-1px); }
    .user-tab.active { color: #fff; border-color: #009ef7; background: linear-gradient(135deg, #009ef7, #006ae6); box-shadow: 0 8px 22px rgba(0, 158, 247, .22); }
    .user-tab.confirmed.active { border-color: #50cd89; background: linear-gradient(135deg, #50cd89, #20a86b); box-shadow: 0 8px 22px rgba(80, 205, 137, .22); }
    .user-tab.not-confirmed.active { border-color: #f1a208; background: linear-gradient(135deg, #ffc700, #e89600); box-shadow: 0 8px 22px rgba(255, 199, 0, .22); }
    .user-tab .tab-count { font-size: 24px; font-weight: 700; line-height: 1; }
    .user-tab .tab-label { font-weight: 700; margin-top: 8px; }
    .user-tab .tab-note { font-size: 12px; margin-top: 4px; opacity: .78; }
    .user-list-table tbody tr:hover { background: #f9fbfd; }
    @media (max-width: 767.98px) { .user-tabs { grid-template-columns: 1fr; } }
</style>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div id="kt_content_container" class="container-xxl">
        <div class="col-xl-12">
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                    <form action="" method="GET">
                        
                        <!-- YEH HIDDEN INPUT ADD KIYA HAI TAB STATE KE LIYE -->
                        <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">

                        <div class="row mb-3">
                            <!-- User ID filter -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select id="user-filter" name="user_id" aria-label="Select a User" data-placeholder="Search by name, email or phone" class="form-select form-select-solid form-select-lg">
                                    <option value=""></option>
                                    @if($data['selected_user'])
                                        <option value="{{ $data['selected_user']->id }}" selected>
                                            {{ $data['selected_user']->name }} ({{ $data['selected_user']->email }}) {{ $data['selected_user']->mobile_no }} / {{ $data['selected_user']->mobile_no2 }}
                                        </option>
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                    
                            <!-- Start Date filter -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <input type="date" name="start_date" placeholder="Start Date" class="form-control" value="{{ request('start_date') }}" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                    
                            <!-- End Date filter -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <input type="date" name="end_date" placeholder="End Date" class="form-control" value="{{ request('end_date') }}" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                    
                            <!-- Role filter -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="role" aria-label="Select a Role" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible">
                                    <option value="">Select Role</option>
                                    @foreach($data['role'] as $role)
                                        <option value="{{$role->id}}" {{ $role->id == request('role') ? 'selected' : '' }}>
                                            {{$role->role}}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                    
                            <!-- Country Code filter -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="countrycode" class="form-select form-control" data-control="select2" data-placeholder="Select Country">
                                    <option value="">Select Country</option>
                                    
                                    @if(isset($data['countryList']))
                                        @foreach($data['countryList'] as $country)
                                            <option value="{{ $country }}" {{ request('countrycode') == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    @endif
                                    
                                    <option value="Other" {{ request('countrycode') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <select name="order_category" class="form-select form-select-solid" data-control="select2" data-placeholder="Filter By Order Category">
                                    <option value="">All Categories</option>
                                    <option value="beginner" {{ request('order_category') == 'beginner' ? 'selected' : '' }}>Beginners</option>
                                    <option value="Retainer" {{ request('order_category') == 'Retainer' ? 'selected' : '' }}>Retainer</option>
                                    <option value="loyal" {{ request('order_category') == 'loyal' ? 'selected' : '' }}>Loyal Customers</option>
                                </select>
                            </div>

                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
    <select name="college_name"
            class="form-select form-select-solid"
            data-control="select2"
            data-placeholder="Filter By University">
        <option value="">All Universities</option>

        @foreach($data['collegeList'] as $college)
            <option value="{{ $college }}" {{ request('college_name') == $college ? 'selected' : '' }}>
                {{ $college }}
            </option>
        @endforeach
    </select>
</div>
                            <div class="col-lg-3 fv-row"><select name="group_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Filter By Group"><option value="">All Groups</option>@foreach($data['groups'] as $group)<option value="{{ $group->id }}" {{ (string)request('group_id') === (string)$group->id ? 'selected' : '' }}>{{ $group->name }}</option>@endforeach</select></div>
                    
                            <!-- Search and Reset buttons -->
                            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                <a href="/user" class="btn btn-sm btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Users
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In need</small>
                    </div>
                    
                </div>
            </div>
            <div class="row gy-5 g-xl-8">
                <div class="col-xl-12">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">User Management</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Manage lead conversion and customer status in one place</span>
                            </h3>
                            <a onclick="exportUsers()" style="height: fit-content;" class="btn btn-sm btn-danger">Export</a>
                        </div>

                        <div class="card-body pb-0 pt-4">
                            <div class="user-tabs">
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'all', 'page' => null]) }}" class="user-tab text-decoration-none {{ $tab === 'all' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countAll ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-users me-2"></i>All Customers</div>
                                    <div class="tab-note">Customers with a lead or an order</div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'confirmed', 'page' => null]) }}" class="user-tab confirmed text-decoration-none {{ $tab === 'confirmed' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countConfirmed ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-check-circle me-2"></i>Confirmed Users</div>
                                    <div class="tab-note">Customers who have placed at least one order</div>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['tab' => 'not_confirmed', 'page' => null]) }}" class="user-tab not-confirmed text-decoration-none {{ $tab === 'not_confirmed' ? 'active' : '' }}">
                                    <div class="tab-count">{{ number_format($countNotConfirmed ?? 0) }}</div>
                                    <div class="tab-label"><i class="fa fa-user-clock me-2"></i>Not Confirmed</div>
                                    <div class="tab-note">Lead created, but no order has been placed</div>
                                </a>
                            </div>
                        </div>

                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table user-list-table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            
                                            <th class="min-w-150px">User</th>
                                            <th class="min-w-140px">Contact</th>
                                            <th class="min-w-120px">Groups</th>
                                            <th class="min-w-140px">Conversion</th>
                                            <th class="min-w-140px">Role</th>
                                            <th class="min-w-120px">Join Date</th>
                                            <th class="min-w-120px">Order Count<br>(Last 1.5 Years)</th>
                                            <th class="min-w-120px">Follow-ups<br>(Last 1 Year)</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['users'] as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-45px me-5">
                                                        @if($user->photo)
                                                            <img src="{{ asset($user->photo) }}" alt="">
                                                        @else
                                                          <img src="assets/media/avatars/blank.png" alt="">
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
                                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6">@if($user->countrycode)+{{$user->countrycode}} @endif {{$user->mobile_no}}</a>
                                                <span class="text-muted fw-bold text-muted d-block fs-7">{{$user->email}}</span>
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
                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                    <button type="button" class="btn btn-sm btn-light-success fw-bold me-1" data-user-group-button="{{ $user->id }}" data-groups='@json($user->groups->pluck("id"))' onclick="openUserGroupModal({{ $user->id }}, @js($user->name), JSON.parse(this.dataset.groups))">+G</button>
                                                    <a  data-bs-toggle="modal" data-bs-target="#kt_modal_create_app{{$user->id}}"  href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" >
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
                                        @endforeach
                                        @if($data['users']->isEmpty())
                                            <tr>
                                                <td colspan="8" class="text-center py-10">
                                                    <i class="fa fa-search fs-2x text-muted mb-3"></i>
                                                    <div class="fw-bolder text-gray-700 fs-5">No users found</div>
                                                    <div class="text-muted fs-7">Try changing the filters and search again.</div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $data['users']->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#user-filter').select2({
            placeholder: 'Search by name, email or phone',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('search-user') }}',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return { user: params.term };
                },
                processResults: function (users) {
                    return {
                        results: users.map(function (user) {
                            var contact = user.email || user.mobile_no || user.mobile_no2 || '';
                            return { id: user.id, text: (user.name || 'Unnamed User') + ' (' + contact + ')' };
                        })
                    };
                },
                cache: true
            }
        });
    });

    function exportUsers() {
        // Retrieve filter parameters
        var userId = $('select[name="user_id"]').val();
        var startDate = $('input[name="start_date"]').val();
        var endDate = $('input[name="end_date"]').val();
        var roleId = $('select[name="role"]').val();
        var countrycode = $('select[name="countrycode"]').val(); // Isko bhi select kiye hue option me change kar diya taaki same rahe

        // Use CSRF token for security
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Prepare data for AJAX request
        var requestData = {
            _token: csrfToken,
            user_id: userId,
            start_date: startDate,
            end_date: endDate,
            role: roleId,
            countrycode: countrycode
        };

        // Send AJAX request to export endpoint
        $.ajax({
            type: 'GET',
            url: '{{ route('export.users') }}',
            data: requestData,
            xhrFields: {
                responseType: 'blob' // Ensures the response is handled as a blob
            },
            success: function (data) {
                // Create a blob object from the response data
                var blob = new Blob([data], { type: 'text/csv' });
                var url = window.URL.createObjectURL(blob);
                var link = document.createElement('a');

                // Generate file name with current timestamp
                var filename = 'users_' + new Date().toISOString().slice(0, 19).replace(/[-T:/]/g, '') + '.csv';
                link.href = url;
                link.download = filename;

                // Append link to the body and trigger download
                document.body.appendChild(link);
                link.click();
                
                // Clean up
                window.URL.revokeObjectURL(url);
                document.body.removeChild(link);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while exporting the data.');
            }
        });
    }
</script>
@endsection
