<!--begin:Menu sub-->
<div class="menu-sub menu-sub-accordion ">
    @forelse ($menu as $submenu)
        @php
            if (!empty($submenu->submenu) && isset($submenu->submenu)) {
                $is_route_sub_submenu = getValueMatch($submenu, request()->url(), true, 'url');
            }
        @endphp

        @if (!empty($submenu->show_in) && isset($submenu->show_in))
            @hasanyrole($submenu->show_in->roles)
            @else
                @if (empty($submenu->submenu))
                    @continue
                @endif
            @endhasanyrole
        @endif
        <!--begin:Menu item-->
        <div class="menu-item @if (!empty($submenu->submenu) && isset($submenu->submenu)) {{ $is_route_sub_submenu ? 'here' : '' }} {{ $is_route_sub_submenu ? 'show' : '' }} @endif {{ isset($menu->submenu) ? 'menu-accordion' : '' }}"
            {{ isset($submenu->submenu) ? 'data-kt-menu-trigger="click"' : '' }}>
            <!--begin:Menu link-->
            <a class="menu-link @if (str_contains($submenu->url, $segment)) {{ request()->is($segment . '*') ? 'active' : '' }} @endif"
                href="{{ isset($submenu->url) ? $submenu->url : '#' }}"
                @if (isset($submenu->newTab)) {{ 'target=_blank' }} @endif>
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">{{ __($submenu->name) }}</span>
            </a>
            <!--end:Menu link-->
            @if (isset($submenu->submenu))
                @include('components.web.sidebar-submenu', ['menu' => $submenu->submenu])
            @endif
        </div>
        <!--end:Menu item-->

    @empty
    @endforelse

</div>
<!--end:Menu sub-->
