<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="/dashboard">
            <img alt="Logo" src="{{ asset('assets/media/avatars/logo-white_11zon.png') }}" class="h-60px logo" />
        </a>

        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="black" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="black" />
                </svg>
            </span>
        </div>
    </div>

    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">

                @php
                    $menuIds = [];
                    $submenuIds = [];
                    $menus =
                        isset($menus) && $menus->isNotEmpty()
                            ? $menus
                            : \App\Models\menu::with(['children.submenus', 'submenus'])->get();
                    $premission =
                        isset($premission) && $premission->isNotEmpty()
                            ? $premission
                            : \Illuminate\Support\Facades\DB::table('permission')->get();
                    $currentPath = trim(request()->path(), '/');
                    $currentRequestUri = trim(request()->getRequestUri(), '/');
                    $isActiveRoute = function ($route) use ($currentPath, $currentRequestUri) {
                        $route = trim((string) $route, '/');

                        if ($route === '') {
                            return $currentPath === '';
                        }

                        if (str_contains($route, '?')) {
                            return $currentRequestUri === $route || str_starts_with($currentRequestUri, $route . '&');
                        }

                        return $currentPath === $route || str_starts_with($currentPath, $route . '/');
                    };

                    if (auth()->check()) {
                        $userRoleId = (int) auth()->user()->role_id;
                        foreach ($premission as $perm) {
                            if ((int) $perm->role_id === $userRoleId) {
                                $menuIds = json_decode($perm->menu_id, true) ?? [];
                                $submenuIds = json_decode($perm->submenu_id, true) ?? [];
                            }
                        }
                    }

                    $menuIdsStr = array_map('strval', $menuIds);
                    $submenuIdsStr = array_map('strval', $submenuIds);

                    $menus = $menus->sortBy(function ($menu) {
                        return (int) $menu->sort_order;
                    });
                @endphp

                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                    </div>
                </div>

                @foreach ($menus as $menu)
                    @if ($menu->parent_id !== null && in_array((string) $menu->parent_id, $menuIdsStr))
                        @continue
                    @endif
                    @if ($menu->show_menu == 'Y' && in_array((string) $menu->id, $menuIdsStr))
                        @if ($menu->children->count() > 0)
                            @php
                                $isParentActive = false;
                                $visibleGroups = [];

                                foreach ($menu->children as $childMenu) {
                                    if (
                                        $childMenu->show_menu == 'Y' &&
                                        in_array((string) $childMenu->id, $menuIdsStr)
                                    ) {
                                        $visibleSubmenus = $childMenu->submenus->filter(function ($submenu) use (
                                            $submenuIdsStr,
                                        ) {
                                            return $submenu->show == 'Y' &&
                                                in_array((string) $submenu->id, $submenuIdsStr);
                                        });
                                        if ($visibleSubmenus->isNotEmpty() || !empty($childMenu->routes)) {
                                            $hasActiveChild =
                                                $visibleSubmenus->contains(function ($submenu) use ($isActiveRoute) {
                                                    return $isActiveRoute($submenu->routes);
                                                }) ||
                                                (!empty($childMenu->routes) && $isActiveRoute($childMenu->routes));
                                            if ($hasActiveChild) {
                                                $isParentActive = true;
                                            }
                                            $visibleGroups[] = [
                                                'menu' => $childMenu,
                                                'submenus' => $visibleSubmenus,
                                                'active' => $hasActiveChild,
                                            ];
                                        }
                                    }
                                }
                            @endphp

                            @if (count($visibleGroups) > 0)
                                <div data-kt-menu-trigger="click"
                                    class="menu-item menu-accordion {{ $isParentActive ? 'here show' : '' }}">
                                    <span class="menu-link {{ $isParentActive ? 'active' : '' }}">
                                        <span class="menu-icon">
                                            <i class="{{ $menu['icon_class'] ?: 'fa fa-circle-o' }}"></i>
                                        </span>
                                        <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>

                                    <div class="menu-sub menu-sub-accordion menu-active-bg" style="padding-left: 15px;">
                                        @foreach ($visibleGroups as $group)
                                            @if ($group['submenus']->isNotEmpty())
                                                <div data-kt-menu-trigger="click"
                                                    class="menu-item menu-accordion {{ $group['active'] ? 'here show' : '' }}">
                                                    <span class="menu-link {{ $group['active'] ? 'active' : '' }}">
                                                        <span class="menu-icon">
                                                            <i
                                                                class="{{ $group['menu']->icon_class ?: 'fa fa-circle-o' }}"></i>
                                                        </span>
                                                        <span class="menu-title">{{ $group['menu']->menu_name }}</span>
                                                        <span class="menu-arrow"></span>
                                                    </span>
                                                    <div class="menu-sub menu-sub-accordion menu-active-bg"
                                                        style="padding-left: 15px;">
                                                        @foreach ($group['submenus'] as $submenu)
                                                            @php $isSubmenuActive = $isActiveRoute($submenu->routes); @endphp
                                                            <div class="menu-item">
                                                                <a class="menu-link {{ $isSubmenuActive ? 'active' : '' }}"
                                                                    href="{{ url($submenu->routes) }}">
                                                                    <span class="menu-bullet"><span
                                                                            class="bullet bullet-dot"></span></span>
                                                                    <span
                                                                        class="menu-title">{{ $submenu->sub_menu_name }}</span>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="menu-item">
                                                    <a class="menu-link {{ $group['active'] ? 'active' : '' }}"
                                                        href="{{ url($group['menu']->routes) }}">
                                                        <span class="menu-icon">
                                                            <i
                                                                class="{{ $group['menu']->icon_class ?: 'fa fa-circle-o' }}"></i>
                                                        </span>
                                                        <span class="menu-title">{{ $group['menu']->menu_name }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @elseif ($menu->submenus->where('show', 'Y')->count() > 0)
                            @php
                                $visibleSubmenus = $menu->submenus->filter(function ($submenu) use ($submenuIdsStr) {
                                    if ($submenu->show != 'Y' || !in_array((string) $submenu->id, $submenuIdsStr)) {
                                        return false;
                                    }
                                    if (auth()->check() && auth()->user()->role_id == 9) {
                                        $subRoute = trim($submenu->routes, '/');
                                        $currentUserId = auth()->id();
                                        if ($currentUserId == 13715) {
                                            if (
                                                $subRoute == 'my-break-time-report' ||
                                                $subRoute == 'my-revoke-payments'
                                            ) {
                                                return false;
                                            }
                                        } else {
                                            if ($subRoute == 'break-time-report' || $subRoute == 'revoke-payments') {
                                                return false;
                                            }
                                        }
                                    }
                                    return true;
                                });

                                $hasActiveChild = $visibleSubmenus->contains(function ($submenu) use ($isActiveRoute) {
                                    return $isActiveRoute($submenu->routes);
                                });

                                $isReportsPayeeActive =
                                    strtolower($menu['menu_name']) == 'reports' &&
                                    auth()->check() &&
                                    auth()->user()->role_id == 1 &&
                                    $isActiveRoute('payee-report');

                                $isParentActive = $hasActiveChild || $isReportsPayeeActive;
                            @endphp

                            @if (
                                $visibleSubmenus->isNotEmpty() ||
                                    (strtolower($menu['menu_name']) == 'reports' && auth()->check() && auth()->user()->role_id == 1))
                                <div data-kt-menu-trigger="click"
                                    class="menu-item menu-accordion {{ $isParentActive ? 'here show' : '' }}">
                                    <span class="menu-link {{ $isParentActive ? 'active' : '' }}">
                                        <span class="menu-icon">
                                            <i class="{{ $menu['icon_class'] ?: 'fa fa-circle-o' }}"></i>
                                        </span>
                                        <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>

                                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                                        @foreach ($visibleSubmenus as $submenu)
                                            @php
                                                $isSubmenuActive = $isActiveRoute($submenu->routes);
                                            @endphp
                                            <div class="menu-item">
                                                <a class="menu-link {{ $isSubmenuActive ? 'active' : '' }}"
                                                    href="{{ url($submenu->routes) }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ $submenu->sub_menu_name }}</span>
                                                    @if (trim($submenu->routes, '/') == 'revoke-payments')
                                                        @if (isset($globalRevokeCount) && $globalRevokeCount > 0)
                                                            <span class="menu-badge">
                                                                <span
                                                                    class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalRevokeCount }}</span>
                                                            </span>
                                                        @endif
                                                    @elseif(trim($submenu->routes, '/') == 'my-revoke-payments')
                                                        @if (isset($globalMyRevokeCount) && $globalMyRevokeCount > 0)
                                                            <span class="menu-badge">
                                                                <span
                                                                    class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalMyRevokeCount }}</span>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                        @if (strtolower($menu['menu_name']) == 'reports' && auth()->check() && auth()->user()->role_id == 1)
                                            <div class="menu-item">
                                                <a class="menu-link {{ $isReportsPayeeActive ? 'active' : '' }}"
                                                    href="{{ url('payee-report') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Payment Report</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            @php
                                $isMenuActive = $isActiveRoute($menu['routes']);
                            @endphp

                            <div class="menu-item">
                                <a class="menu-link {{ $isMenuActive ? 'active' : '' }}"
                                    href="{{ url($menu['routes']) }}">
                                    <span class="menu-icon">
                                        <i class="{{ $menu['icon_class'] ?: 'fa fa-circle-o' }}"></i>
                                    </span>
                                    <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                    @if (trim($menu['routes'], '/') == 'revoke-payments')
                                        @if (isset($globalRevokeCount) && $globalRevokeCount > 0)
                                            <span class="menu-badge">
                                                <span
                                                    class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalRevokeCount }}</span>
                                            </span>
                                        @endif
                                    @elseif(trim($menu['routes'], '/') == 'my-revoke-payments')
                                        @if (isset($globalMyRevokeCount) && $globalMyRevokeCount > 0)
                                            <span class="menu-badge">
                                                <span
                                                    class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalMyRevokeCount }}</span>
                                            </span>
                                        @endif
                                    @elseif(trim($menu['routes'], '/') == 'admin/login-otp-notifications')
                                        @if (isset($globalLoginOtpCount) && $globalLoginOtpCount > 0)
                                            <span class="menu-badge">
                                                <span
                                                    class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalLoginOtpCount }}</span>
                                            </span>
                                        @endif
                                    @endif
                                </a>
                            </div>
                        @endif
                    @endif
                @endforeach


                @if (function_exists('is_email_campaign_enabled') && is_email_campaign_enabled())
                    <div class="menu-item">
                        <a class="menu-link {{ request()->is('emails/campaigns*') ? 'active' : '' }}"
                            href="{{ route('emails.campaigns.index') }}">
                            <span class="menu-icon">
                                <i class="fa fa-bullhorn fs-4 text-primary"></i>
                            </span>
                            <span class="menu-title">Email Campaigns</span>
                        </a>
                    </div>
                @endif


            </div>
        </div>
    </div>

    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <a href="" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover"
            data-bs-dismiss-="click" title="200+ in-house components and 3rd-party plugins">
            <span class="btn-label">AIN Team </span>
        </a>
    </div>
</div>

<style>
    /* --- SIDEBAR CONTAINER & TYPOGRAPHY --- */
    #kt_aside {
        background-color: #0f172a !important;
        /* Deep Slate Navy */
        border-right: 1px solid #1e293b;
    }

    #kt_aside .menu-section {
        color: #64748b !important;
        font-weight: 700;
        letter-spacing: 1px;
        padding-left: 10px;
    }

    /* --- MENU LINKS BASE STYLING --- */
    #kt_aside .menu-link {
        border-radius: 8px;
        margin: 2px 10px;
        padding: 10px 12px;
        transition: all 0.2s ease;
    }

    #kt_aside .menu-title {
        font-weight: 500;
        font-size: 1rem;
        color: #94a3b8;
        /* Muted text for inactive */
    }

    #kt_aside .menu-icon i {
        color: #64748b;
        /* Muted icon for inactive */
        font-size: 1.1rem;
        transition: color 0.2s ease;
    }

    /* Hover State */
    #kt_aside .menu-link:hover {
        background-color: #1e293b !important;
    }

    #kt_aside .menu-link:hover .menu-title,
    #kt_aside .menu-link:hover .menu-icon i {
        color: #ffffff !important;
    }

    /* --- ACTIVE & HERE STATES (Modern Look) --- */
    #kt_aside .menu-item.here>.menu-link,
    #kt_aside .menu-link.active {
        background-color: rgba(59, 130, 246, 0.15) !important;
        /* Subtle blue tint */
        border-left: 3px solid #3b82f6;
        /* Bright blue indicator */
        border-radius: 0 8px 8px 0;
        /* Keep right corners rounded */
        margin-left: 0;
        /* Flush to left edge */
        padding-left: 22px;
        /* Adjust padding for the border */
    }

    #kt_aside .menu-item.here>.menu-link .menu-title,
    #kt_aside .menu-item.here>.menu-link .menu-icon i,
    #kt_aside .menu-item.here>.menu-link .menu-arrow,
    #kt_aside .menu-link.active .menu-title,
    #kt_aside .menu-link.active .menu-icon i,
    #kt_aside .menu-link.active .menu-bullet,
    #kt_aside .menu-link.active .menu-arrow {
        color: #ffffff !important;
    }

    /* Submenu Active State */
    #kt_aside .menu-sub .menu-link.active {
        background-color: rgba(59, 130, 246, 0.1) !important;
        border-left: 2px solid #60a5fa;
        border-radius: 0 8px 8px 0;
        margin-left: 0;
        padding-left: 22px;
    }

    #kt_aside .menu-link.active .bullet {
        background-color: #3b82f6 !important;
    }

    /* --- BADGES & NOTIFICATIONS --- */
    #kt_aside .menu-badge .badge {
        background-color: #ef4444 !important;
        /* Modern Red */
        color: white;
        padding: 3px 6px;
        font-size: 0.65rem;
        box-shadow: 0 0 0 2px #0f172a;
        /* Creates a cutout effect */
    }

    /* --- FOOTER BUTTON (AIN Team) --- */
    #kt_aside_footer .btn-primary {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
        font-weight: 600;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    #kt_aside_footer .btn-primary:hover {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* --- SCROLLBAR POLISH --- */
    #kt_aside_menu_wrapper::-webkit-scrollbar {
        width: 4px;
    }

    #kt_aside_menu_wrapper::-webkit-scrollbar-thumb {
        background-color: #334155;
        border-radius: 4px;
    }

    #kt_aside_menu_wrapper::-webkit-scrollbar-track {
        background-color: transparent;
    }

    /* Excel / Spreadsheets icon accent */
    #kt_aside .menu-link .menu-icon i.fa-file-excel {
        color: #107c41 !important;
    }

    #kt_aside .menu-link:hover .menu-icon i.fa-file-excel,
    #kt_aside .menu-link.active .menu-icon i.fa-file-excel {
        color: #22c55e !important;
    }
</style>
