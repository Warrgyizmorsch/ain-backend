@extends('layouts.app')
@section('content')
<style>
    .menu-card-wrapper {
        transition: transform 0.2s, opacity 0.2s;
    }
    .card-custom {
        border-radius: 0.65rem;
    }
    /* Customize active switch color to success/green */
    .form-switch .form-check-input:checked {
        background-color: #50cd89 !important;
        border-color: #50cd89 !important;
    }
    .submenu-list {
        background: #fdfdfd;
        border-radius: 8px;
    }
    .search-highlight {
        background-color: #fff3cd;
        padding: 0 2px;
        border-radius: 2px;
    }
    .menu-card-wrapper { cursor: grab; }
    .menu-card-wrapper.sortable-ghost { opacity: .45; }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">

        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack mb-4">
                <div data-kt-swapper="true" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">User Right Settings
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                        <small class="text-muted fs-7 fw-bold my-1 ms-1">Manage Role-Based Menu Access</small>
                    </h1>
                </div>
            </div>
        </div>

        <form action="{{ route('userright') }}" method="post" id="permissionsForm">
            @csrf

            <!-- Header Card: Role Selection, Search & Actions -->
            <div class="card mb-8 shadow-sm">
                <div class="card-body py-6">
                    <div class="row align-items-center g-4">
                        <!-- Role Selector -->
                        <div class="col-md-4">
                            <label class="form-label fw-bolder fs-6 text-gray-700 required">Role</label>
                            <select id="role_id" name="role_id" data-control="select2" class="form-select form-select-solid form-select-lg fw-bold" required>
                                <option value="">Select a Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role['id'] }}">{{ $role['role'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Search/Filter Bar -->
                        <div class="col-md-4">
                            <label class="form-label fw-bolder fs-6 text-gray-700">Search Menu / Submenu</label>
                            <div class="position-relative">
                                <input type="text" id="search_filter" class="form-control form-control-solid" placeholder="Type to filter..." />
                                <span class="position-absolute top-50 translate-middle-y end-0 me-3 text-muted">
                                    <i class="fa fa-search fs-5"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Bulk Controls & Save Button -->
                        <div class="col-md-4 d-flex justify-content-md-end align-items-end gap-3 mt-md-6 pt-3">
                            <button type="button" id="btn_select_all" class="btn btn-light-success btn-sm fw-bold">Select All</button>
                            <button type="button" id="btn_clear_all" class="btn btn-light-danger btn-sm fw-bold">Clear All</button>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-6">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Cards Grid -->
            <div class="row g-6" id="permissions_grid">
                @foreach ($menus->whereNull('parent_id')->where('id', '!=', 44) as $menu)
                    <div class="col-md-6 col-lg-4 col-xl-4 menu-card-wrapper" data-menu-name="{{ strtolower($menu->menu_name) }}">
                        <div class="card card-custom h-100 shadow-sm border border-gray-200">
                            
                            <!-- Card Header -->
                            <div class="card-header border-0 min-h-60px py-4 px-6 bg-light d-flex align-items-center justify-content-between" style="border-top-left-radius: 0.65rem; border-top-right-radius: 0.65rem;">
                                <div class="card-title m-0 d-flex align-items-center">
                                    <span class="card-icon me-3 text-primary">
                                        <i class="{{ $menu->icon_class }} fs-4"></i>
                                    </span>
                                    <h3 class="card-label fw-bolder fs-5 text-gray-800 m-0">{{ $menu->menu_name }}</h3>
                                </div>
                                <div class="card-toolbar m-0">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input menu-checkbox parent-menu-checkbox" name="menu_id[]" type="checkbox" value="{{ $menu->id }}" id="menu_{{ $menu->id }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body py-5 px-6">
                                
                                <!-- Level 2: Children Menus (if any) -->
                                @if ($menu->children->count() > 0)
                                    <div class="menu-children-list">
                                        @foreach ($menu->children as $child)
                                            <div class="child-menu-item mb-4 pb-3 border-bottom border-gray-100" data-menu-name="{{ strtolower($child->menu_name) }}">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="fw-bold fs-6 text-gray-700">
                                                        <i class="{{ $child->icon_class }} fs-6 me-2 text-muted"></i>{{ $child->menu_name }}
                                                    </span>
                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input menu-checkbox child-menu-checkbox" name="menu_id[]" type="checkbox" value="{{ $child->id }}" data-parent-id="{{ $menu->id }}" id="menu_{{ $child->id }}">
                                                    </div>
                                                </div>

                                                <!-- Level 3: Submenus of Child Menu -->
                                                @if ($child->submenus->count() > 0)
                                                    <div class="submenu-list p-3 d-flex flex-wrap gap-x-4 gap-y-2 mt-2 bg-light bg-opacity-50">
                                                        @foreach ($child->submenus as $submenu)
                                                            <div class="form-check form-check-custom form-check-solid me-3 submenu-item" data-menu-name="{{ strtolower($submenu->sub_menu_name) }}">
                                                                <input name="submenu_id[]" class="form-check-input submenu-checkbox" type="checkbox" value="{{ $submenu->id }}" data-menu-id="{{ $child->id }}" id="submenu_{{ $submenu->id }}">
                                                                <label class="form-check-label fs-7 text-gray-600 fw-semibold cursor-pointer" for="submenu_{{ $submenu->id }}">{{ $submenu->sub_menu_name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Level 2: Direct Submenus of Top-Level Menu (if any) -->
                                @if ($menu->submenus->count() > 0)
                                    <div class="submenu-list d-flex flex-column gap-3 p-3 bg-light bg-opacity-50 {{ $menu->children->count() > 0 ? 'mt-4' : '' }}">
                                        @foreach ($menu->submenus as $submenu)
                                            <div class="form-check form-check-custom form-check-solid submenu-item" data-menu-name="{{ strtolower($submenu->sub_menu_name) }}">
                                                <input name="submenu_id[]" class="form-check-input submenu-checkbox" type="checkbox" value="{{ $submenu->id }}" data-menu-id="{{ $menu->id }}" id="submenu_{{ $submenu->id }}">
                                                <label class="form-check-label fs-6 text-gray-700 fw-semibold cursor-pointer" for="submenu_{{ $submenu->id }}">{{ $submenu->sub_menu_name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($menu->children->count() == 0 && $menu->submenus->count() == 0)
                                    <div class="text-center py-4">
                                        <span class="text-muted fs-7 italic">No Submenus or Child Items</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Horizontal Full-Width "Other" Menu Card -->
            @php
                $otherMenu = $menus->firstWhere('id', 44);
            @endphp
            @if ($otherMenu)
                <div class="row mt-8 menu-card-wrapper" id="other_menu_wrapper" data-menu-name="{{ strtolower($otherMenu->menu_name) }}">
                    <div class="col-12">
                        <div class="card card-custom shadow-sm border border-gray-200">
                            
                            <!-- Card Header -->
                            <div class="card-header border-0 min-h-60px py-4 px-6 bg-light d-flex align-items-center justify-content-between" style="border-top-left-radius: 0.65rem; border-top-right-radius: 0.65rem;">
                                <div class="card-title m-0 d-flex align-items-center">
                                    <span class="card-icon me-3 text-primary">
                                        <i class="{{ $otherMenu->icon_class }} fs-4"></i>
                                    </span>
                                    <h3 class="card-label fw-bolder fs-5 text-gray-800 m-0">{{ $otherMenu->menu_name }}</h3>
                                </div>
                                <div class="card-toolbar m-0">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input menu-checkbox parent-menu-checkbox" name="menu_id[]" type="checkbox" value="{{ $otherMenu->id }}" id="menu_{{ $otherMenu->id }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body: Grid of Child Menus -->
                            <div class="card-body py-6 px-6">
                                @if ($otherMenu->children->count() > 0)
                                    <div class="row g-6">
                                        @foreach ($otherMenu->children as $child)
                                            <div class="col-md-6 col-lg-4 col-xl-3 child-menu-item mb-4" data-menu-name="{{ strtolower($child->menu_name) }}">
                                                <div class="p-5 border border-gray-200 rounded bg-light bg-opacity-30 h-100">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="fw-bold fs-6 text-gray-800">
                                                            <i class="{{ $child->icon_class }} fs-6 me-2 text-muted"></i>{{ $child->menu_name }}
                                                        </span>
                                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input menu-checkbox child-menu-checkbox" name="menu_id[]" type="checkbox" value="{{ $child->id }}" data-parent-id="{{ $otherMenu->id }}" id="menu_{{ $child->id }}">
                                                        </div>
                                                    </div>

                                                    <!-- Level 3: Submenus of Child Menu -->
                                                    @if ($child->submenus->count() > 0)
                                                        <div class="submenu-list p-3 d-flex flex-column gap-2 mt-2 bg-white rounded border border-gray-100">
                                                            @foreach ($child->submenus as $submenu)
                                                                <div class="form-check form-check-custom form-check-solid submenu-item" data-menu-name="{{ strtolower($submenu->sub_menu_name) }}">
                                                                    <input name="submenu_id[]" class="form-check-input submenu-checkbox" type="checkbox" value="{{ $submenu->id }}" data-menu-id="{{ $child->id }}" id="submenu_{{ $submenu->id }}">
                                                                    <label class="form-check-label fs-7 text-gray-600 fw-semibold cursor-pointer" for="submenu_{{ $submenu->id }}">{{ $submenu->sub_menu_name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <span class="text-muted fs-7 italic">No Submenus or Child Items</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
$(document).ready(function () {

    const permissionsGrid = document.getElementById('permissions_grid');
    new Sortable(permissionsGrid, {
        animation: 150,
        handle: '.card-header',
        ghostClass: 'sortable-ghost'
    });

    function applyRoleMenuOrder(menuIds) {
        const order = (menuIds || []).map(String);
        const cards = Array.from(permissionsGrid.querySelectorAll('.menu-card-wrapper'));
        cards.sort(function (first, second) {
            const firstIndex = order.indexOf(first.querySelector('.menu-checkbox')?.value);
            const secondIndex = order.indexOf(second.querySelector('.menu-checkbox')?.value);
            return (firstIndex < 0 ? 100000 : firstIndex) - (secondIndex < 0 ? 100000 : secondIndex);
        }).forEach(function (card) {
            permissionsGrid.appendChild(card);
        });
    }

    // Fetch and Populate Permissions when Role is Selected
    $('#role_id').change(function () {
        var selectedRoleId = $(this).val();

        // Clear all switches first
        $('.menu-checkbox, .submenu-checkbox').prop('checked', false);

        if (!selectedRoleId) {
            return;
        }

        $.ajax({
            type: 'GET',
            url: '{{ route("rolePermission") }}',
            data: { role_id: selectedRoleId },
            success: function (response) {
                applyRoleMenuOrder(response.menuid || []);
                // Populate Menu Checkboxes
                $.each(response.menuid || [], function (index, id) {
                    $('.menu-checkbox[value="' + id + '"]').prop('checked', true);
                });

                // Populate Submenu Checkboxes
                $.each(response.submenuid || [], function (index, id) {
                    $('.submenu-checkbox[value="' + id + '"]').prop('checked', true);
                });
            },
            error: function(xhr, status, error) {
                console.error("Failed to load permissions: ", error);
            }
        });
    });

    // 1. Parent Menu checkbox change
    $('.parent-menu-checkbox').change(function () {
        var parentId = $(this).val();
        var isChecked = $(this).is(':checked');

        // Toggle all its direct child menus
        $('.child-menu-checkbox[data-parent-id="' + parentId + '"]').prop('checked', isChecked).trigger('change');

        // Toggle direct submenus (for menus with no child menus, e.g. normal dropdown menus)
        $('.submenu-checkbox[data-menu-id="' + parentId + '"]').prop('checked', isChecked);
    });

    // 2. Child Menu checkbox change
    $('.child-menu-checkbox').change(function () {
        var childId = $(this).val();
        var parentId = $(this).data('parent-id');
        var isChecked = $(this).is(':checked');

        // Toggle all submenus under this child menu
        $('.submenu-checkbox[data-menu-id="' + childId + '"]').prop('checked', isChecked);

        // Auto-handle the parent-level checkbox
        if (isChecked) {
            $('.parent-menu-checkbox[value="' + parentId + '"]').prop('checked', true);
        } else {
            // If all siblings are unchecked, uncheck the parent menu
            var anySiblingChecked = $('.child-menu-checkbox[data-parent-id="' + parentId + '"]:checked').length > 0;
            if (!anySiblingChecked) {
                $('.parent-menu-checkbox[value="' + parentId + '"]').prop('checked', false);
            }
        }
    });

    // 3. Submenu checkbox change
    $('.submenu-checkbox').change(function () {
        var menuId = $(this).data('menu-id');
        var isChecked = $(this).is(':checked');

        if (isChecked) {
            // Auto check the menu (could be parent menu OR child menu)
            $('.menu-checkbox[value="' + menuId + '"]').prop('checked', true);
            
            // If it's a child menu, auto check the top parent as well
            var parentId = $('.child-menu-checkbox[value="' + menuId + '"]').data('parent-id');
            if (parentId) {
                $('.parent-menu-checkbox[value="' + parentId + '"]').prop('checked', true);
            }
        } else {
            // If all submenus under this menu are unchecked, auto uncheck the menu
            var anySubmenuChecked = $('.submenu-checkbox[data-menu-id="' + menuId + '"]:checked').length > 0;
            if (!anySubmenuChecked) {
                $('.menu-checkbox[value="' + menuId + '"]').prop('checked', false);

                // If it was a child menu, check if parent should be unchecked
                var parentId = $('.child-menu-checkbox[value="' + menuId + '"]').data('parent-id');
                if (parentId) {
                    var anyChildChecked = $('.child-menu-checkbox[data-parent-id="' + parentId + '"]:checked').length > 0;
                    if (!anyChildChecked) {
                        $('.parent-menu-checkbox[value="' + parentId + '"]').prop('checked', false);
                    }
                }
            }
        }
    });

    // Select All Permissions
    $('#btn_select_all').click(function () {
        $('.menu-checkbox, .submenu-checkbox').prop('checked', true);
    });

    // Clear All Permissions
    $('#btn_clear_all').click(function () {
        $('.menu-checkbox, .submenu-checkbox').prop('checked', false);
    });

    // Live Search Filter
    $('#search_filter').on('input', function () {
        var query = $(this).val().toLowerCase().trim();

        if (query === '') {
            $('.menu-card-wrapper').show();
            $('.child-menu-item').show();
            $('.submenu-item').show();
            return;
        }

        $('.menu-card-wrapper').each(function () {
            var card = $(this);
            var cardName = card.data('menu-name') || '';
            var matchesCard = cardName.includes(query);

            var matchingChildrenCount = 0;
            var matchingSubmenuCount = 0;

            // Check children
            card.find('.child-menu-item').each(function () {
                var childItem = $(this);
                var childName = childItem.data('menu-name') || '';
                var matchesChild = childName.includes(query);

                var matchingSubmenusInChild = 0;

                // Check submenus inside child
                childItem.find('.submenu-item').each(function () {
                    var subItem = $(this);
                    var subName = subItem.data('menu-name') || '';
                    if (subName.includes(query)) {
                        subItem.show();
                        matchingSubmenusInChild++;
                        matchingSubmenuCount++;
                    } else {
                        subItem.hide();
                    }
                });

                if (matchesChild || matchingSubmenusInChild > 0) {
                    childItem.show();
                    matchingChildrenCount++;
                } else {
                    childItem.hide();
                }
            });

            // Check direct submenus of parent
            card.find('> .card-body > .submenu-list > .submenu-item').each(function () {
                var subItem = $(this);
                var subName = subItem.data('menu-name') || '';
                if (subName.includes(query)) {
                    subItem.show();
                    matchingSubmenuCount++;
                } else {
                    subItem.hide();
                }
            });

            if (matchesCard || matchingChildrenCount > 0 || matchingSubmenuCount > 0) {
                card.show();
            } else {
                card.hide();
            }
        });
    });

});
</script>
@endsection
