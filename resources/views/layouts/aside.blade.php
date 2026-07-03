<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="/dashboard">
            <img alt="Logo" src="{{ asset('assets/media/avatars/logo-white_11zon.png')}}" class="h-60px logo" />
        </a>

        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                </svg>
            </span>
        </div>
    </div>

    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">

                @php
                    $menuIds = [];
                    $submenuIds = [];
                    $menus = $menus ?? collect();
                    $premission = $premission ?? collect();
                    $currentPath = trim(request()->path(), '/');
                    $isActiveRoute = function ($route) use ($currentPath) {
                        $route = trim((string) $route, '/');

                        if ($route === '') {
                            return $currentPath === '';
                        }

                        return $currentPath === $route || str_starts_with($currentPath, $route . '/');
                    };
                @endphp

                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                    </div>
                </div>

                @php
                    $isWhatsappActive = $isActiveRoute('whatsapp/settings') || $isActiveRoute('whatsapp/chat');
                @endphp
                @if(auth()->check() && auth()->user()->role_id == 1)
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isWhatsappActive ? 'here show' : '' }}">
                    <span class="menu-link {{ $isWhatsappActive ? 'active' : '' }}">
                        <span class="menu-icon">
                            <li class="fa fa-whatsapp"></li>
                        </span>
                        <span class="menu-title">WhatsApp</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ $isActiveRoute('whatsapp/settings') ? 'active' : '' }}" href="{{ route('whatsapp.settings') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Settings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ $isActiveRoute('whatsapp/chat') ? 'active' : '' }}" href="{{ route('whatsapp.chat') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Chat</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @if(auth()->check() && auth()->user()->role_id == 1)
                    <div class="menu-item">
                        <a class="menu-link {{ $isActiveRoute('subjects') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                            <span class="menu-icon"><i class="fa fa-book"></i></span><span class="menu-title">Prefix</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ $isActiveRoute('service-pages') ? 'active' : '' }}" href="{{ route('service-pages.index') }}">
                            <span class="menu-icon"><i class="fa fa-file-text-o"></i></span><span class="menu-title">Dynamic Page</span>
                        </a>
                    </div>
                @endif

                @foreach($premission as $permission)
                    @if(auth()->check() && auth()->user()->role_id == $permission->role_id)
                        @php
                            $menuIds = json_decode($permission->menu_id, true) ?? [];
                            $submenuIds = json_decode($permission->submenu_id, true) ?? [];
                        @endphp
                    @endif
                @endforeach

                @php
                    // Add any menu IDs here that you want to nest inside "Other" (ID 43) - only for admin (role_id == 1)
                    $otherChildrenMenuIds = (auth()->check() && auth()->user()->role_id == 1) ? [2,3,12,14,15,16,21,23,25,27,28,30,32,33,34,35,36,37,38,39] : []; 
                @endphp

                @foreach ($menus as $menu)
                    @if (in_array($menu->id, $otherChildrenMenuIds))
                        @continue
                    @endif
                    @if ($menu->show_menu == 'Y' && in_array($menu->id, $menuIds))
                        @if ($menu->id == 43)
                            @if (auth()->check() && auth()->user()->role_id == 1)
                                @php
                                    $isOtherActive = false;
                                    $visibleGroups = [];
                                    
                                    foreach ($otherChildrenMenuIds as $childMenuId) {
                                        $childMenu = $menus->firstWhere('id', $childMenuId);
                                        if ($childMenu && in_array($childMenuId, $menuIds)) {
                                            $visibleSubmenus = $childMenu->submenus->filter(function ($submenu) use ($submenuIds) {
                                                return $submenu->show == 'Y' && in_array($submenu->id, $submenuIds);
                                            });
                                            if ($visibleSubmenus->isNotEmpty()) {
                                                $hasActiveChild = $visibleSubmenus->contains(function ($submenu) use ($isActiveRoute) {
                                                    return $isActiveRoute($submenu->routes);
                                                });
                                                if ($hasActiveChild) {
                                                    $isOtherActive = true;
                                                }
                                                $visibleGroups[] = [
                                                    'menu' => $childMenu,
                                                    'submenus' => $visibleSubmenus,
                                                    'active' => $hasActiveChild
                                                ];
                                            }
                                        }
                                    }
                                @endphp

                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isOtherActive ? 'here show' : '' }}">
                                    <span class="menu-link {{ $isOtherActive ? 'active' : '' }}">
                                        <span class="menu-icon">
                                            <li class="{{ $menu['icon_class'] }}"></li>
                                        </span>
                                        <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>

                                    <div class="menu-sub menu-sub-accordion menu-active-bg" style="padding-left: 15px;">
                                        @foreach ($visibleGroups as $group)
                                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $group['active'] ? 'here show' : '' }}">
                                                <span class="menu-link {{ $group['active'] ? 'active' : '' }}">
                                                    <span class="menu-icon">
                                                        <li class="{{ $group['menu']->icon_class }}"></li>
                                                    </span>
                                                    <span class="menu-title">{{ $group['menu']->menu_name }}</span>
                                                    <span class="menu-arrow"></span>
                                                </span>
                                                <div class="menu-sub menu-sub-accordion menu-active-bg" style="padding-left: 15px;">
                                                    @foreach ($group['submenus'] as $submenu)
                                                        @php $isSubmenuActive = $isActiveRoute($submenu->routes); @endphp
                                                        <div class="menu-item">
                                                            <a class="menu-link {{ $isSubmenuActive ? 'active' : '' }}" href="{{ url($submenu->routes) }}">
                                                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                                <span class="menu-title">{{ $submenu->sub_menu_name }}</span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @elseif (count($menu->submenus) > 0)
                            @php
                                $visibleSubmenus = $menu->submenus->filter(function ($submenu) use ($submenuIds) {
                                    if ($submenu->show != 'Y' || !in_array($submenu->id, $submenuIds)) {
                                        return false;
                                    }
                                    if (auth()->check() && auth()->user()->role_id == 9) {
                                        $subRoute = trim($submenu->routes, '/');
                                        $currentUserId = auth()->id();
                                        if ($currentUserId == 13715) {
                                            if ($subRoute == 'my-break-time-report' || $subRoute == 'my-revoke-payments') {
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

                                $isReportsPayeeActive = strtolower($menu['menu_name']) == 'reports'
                                    && auth()->check()
                                    && auth()->user()->role_id == 1
                                    && $isActiveRoute('payee-report');

                                $isParentActive = $hasActiveChild || $isReportsPayeeActive;
                            @endphp

                            @if ($visibleSubmenus->isNotEmpty() || (strtolower($menu['menu_name']) == 'reports' && auth()->check() && auth()->user()->role_id == 1))
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isParentActive ? 'here show' : '' }}">
                                    <span class="menu-link {{ $isParentActive ? 'active' : '' }}">
                                        <span class="menu-icon">
                                            <li class="{{ $menu['icon_class'] }}"></li>
                                        </span>
                                        <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                        <span class="menu-arrow"></span>
                                    </span>

                                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                                        {{-- @foreach ($menu->submenus as $submenu)
                                            <div class="menu-item">
                                                <a class="menu-link" href="{{ url($submenu->routes) }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ $submenu->sub_menu_name }}</span>
                                                </a>
                                            </div>
                                        @endforeach --}}
                                        @foreach ($visibleSubmenus as $submenu)
                                            @php
                                                $isSubmenuActive = $isActiveRoute($submenu->routes);
                                            @endphp
                                                <div class="menu-item">
                                                    <a class="menu-link {{ $isSubmenuActive ? 'active' : '' }}" href="{{ url($submenu->routes) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span class="menu-title">{{ $submenu->sub_menu_name }}</span>
                                                        @if(trim($submenu->routes, '/') == 'revoke-payments')
                                                            @if(isset($globalRevokeCount) && $globalRevokeCount > 0)
                                                                <span class="menu-badge">
                                                                    <span class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalRevokeCount }}</span>
                                                                </span>
                                                            @endif
                                                        @elseif(trim($submenu->routes, '/') == 'my-revoke-payments')
                                                            @if(isset($globalMyRevokeCount) && $globalMyRevokeCount > 0)
                                                                <span class="menu-badge">
                                                                    <span class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalMyRevokeCount }}</span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </a>
                                                </div>
                                        @endforeach
                                        @if(strtolower($menu['menu_name']) == 'reports' && auth()->check() && auth()->user()->role_id == 1)
                                            <div class="menu-item">
                                                <a class="menu-link {{ $isReportsPayeeActive ? 'active' : '' }}" href="{{ url('payee-report') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Paayment Report</span>
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
                                <a class="menu-link {{ $isMenuActive ? 'active' : '' }}" href="{{ url($menu['routes']) }}">
                                    <span class="menu-icon">
                                        <li class="{{ $menu['icon_class'] }}"></li>
                                    </span>
                                    <span class="menu-title">{{ $menu['menu_name'] }}</span>
                                    @if(trim($menu['routes'], '/') == 'revoke-payments')
                                        @if(isset($globalRevokeCount) && $globalRevokeCount > 0)
                                            <span class="menu-badge">
                                                <span class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalRevokeCount }}</span>
                                            </span>
                                        @endif
                                    @elseif(trim($menu['routes'], '/') == 'my-revoke-payments')
                                        @if(isset($globalMyRevokeCount) && $globalMyRevokeCount > 0)
                                            <span class="menu-badge">
                                                <span class="badge badge-circle badge-danger fw-bold fs-8">{{ $globalMyRevokeCount }}</span>
                                            </span>
                                        @endif
                                    @endif
                                </a>
                            </div>

                        @endif
                    @endif
                @endforeach

            </div>
        </div>
    </div>

    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <a href="" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="200+ in-house components and 3rd-party plugins">
            <span class="btn-label">AIN Team </span>
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    #kt_aside .menu-item.here > .menu-link,
    #kt_aside .menu-link.active {
        background-color: #1b84ff !important;
        color: #ffffff !important;
        border-radius: 8px;
    }

    #kt_aside .menu-item.here > .menu-link .menu-title,
    #kt_aside .menu-item.here > .menu-link .menu-icon,
    #kt_aside .menu-item.here > .menu-link .menu-arrow,
    #kt_aside .menu-link.active .menu-title,
    #kt_aside .menu-link.active .menu-icon,
    #kt_aside .menu-link.active .menu-bullet,
    #kt_aside .menu-link.active .menu-arrow {
        color: #ffffff !important;
    }

    #kt_aside .menu-link.active .bullet {
        background-color: #ffffff !important;
    }

    #kt_aside .menu-sub .menu-link.active {
        background-color: rgba(27, 132, 255, 0.18) !important;
        color: #ffffff !important;
    }
</style>
