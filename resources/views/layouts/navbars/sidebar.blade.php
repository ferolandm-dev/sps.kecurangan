<div class="sidebar" data-color="green" style="background-color: #29b14a; min-height: 100vh; color: #fff;">
    <style>
    /* 🌿 Efek Glass: Hover & Active */
    .sidebar .nav li>a,
    .sidebar .nav .collapse .nav li>a {
        position: relative;
        transition: all 0.25s ease-in-out;
        border-radius: 10px;
    }

    /* ✨ Hover dan Active — efek kaca bening */
    .sidebar .nav li>a:hover,
    .sidebar .nav li.active>a,
    .sidebar .nav li.active>a:hover,
    .sidebar .nav .collapse .nav li.active>a,
    .sidebar .nav .collapse .nav li>a:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        /* lapisan kaca */
        backdrop-filter: blur(6px) !important;
        /* efek blur kaca */
        -webkit-backdrop-filter: blur(6px) !important;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
        /* garis tipis di pinggir */
        color: #fff !important;
    }

    /* 🟢 Saat menu utama terbuka (collapse aktif) juga tampil seperti kaca */
    .sidebar .nav>li>a[aria-expanded="true"],
    .sidebar .nav>li.active>a[aria-expanded="true"] {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(6px) !important;
        -webkit-backdrop-filter: blur(6px) !important;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
        color: #fff !important;
    }

    /* 💫 Transisi lembut */
    .sidebar .nav li>a i,
    .sidebar .nav li>a p {
        color: #fff !important;
        transition: all 0.25s ease-in-out;
    }
    </style>



    <div class="logo">
        <a href="#" class="simple-text logo-mini" style="font-size: 12px; color: #fff;">
            {{ __('SPS') }}
        </a>
        <a href="#" class="simple-text logo-normal" style="font-size: 12px; color: #fff;">
            {{ __('SINAR PANGAN SEJAHTERA') }}
        </a>
    </div>

    <div class="sidebar-wrapper" id="sidebar-wrapper" style="color: #fff;">
        @php
        use Illuminate\Support\Facades\DB;

        $menus = DB::table('menus')
        ->select('id', 'main_menu', 'sub_menu', 'icon', 'route', 'order', 'main_order')
        ->orderBy('main_order')
        ->orderBy('main_menu')
        ->orderBy('order')
        ->get()
        ->groupBy('main_menu');
        @endphp

        <ul class="nav" id="sidebar-menu" style="color: #fff;">
            @foreach ($menus as $mainMenu => $subs)
            {{-- 🔹 MENU TANPA MAIN_MENU (misal Dashboard) --}}
            @if (empty($mainMenu))
            @foreach ($subs as $menu)
            @php
            $canAccess = false;
            foreach ($userMenus as $main => $subList) {
            if (
            ($main === $menu->main_menu || $main === null || $main === '') &&
            (in_array($menu->sub_menu, $subList) || empty($menu->sub_menu))
            ) {
            $canAccess = true;
            break;
            }
            }
            @endphp

            @if ($canAccess)
            <li class="{{ $activePage == $menu->route ? 'active' : '' }}">
                <a href="{{ $menu->route ? route($menu->route) : '#' }}" style="color: #fff;">
                    <i class="{{ $menu->icon ?? 'now-ui-icons design_bullet-list-67' }}" style="color: #fff;"></i>
                    <p style="color: #fff;">{{ __($menu->sub_menu ?? $menu->main_menu ?? 'Menu') }}</p>
                </a>
            </li>
            @endif
            @endforeach

            {{-- 🔹 MENU DENGAN MAIN_MENU --}}
            @elseif (isset($userMenus[$mainMenu]) && count($subs) > 0)
            @php
            $visibleSubs = $subs->filter(function ($sub) use ($userMenus, $mainMenu) {
            return in_array($sub->sub_menu, $userMenus[$mainMenu]) || $sub->sub_menu === null;
            });

            $collapseId = 'menu_' . ($subs->first()->id ?? crc32($mainMenu));
            $isActive = $visibleSubs->pluck('route')->contains(function ($r) use ($activePage) {
            return $r === $activePage || request()->routeIs($r . '*');
            });
            @endphp

            @if ($visibleSubs->count() > 0)
            <li>
                <a data-toggle="collapse" href="#{{ $collapseId }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                    data-parent="#sidebar-menu" style="color: #fff;">
                    <i class="
                                    @switch($mainMenu)
                                        @case('Dashboard') now-ui-icons business_chart-pie-36 @break
                                        @case('Master') now-ui-icons education_agenda-bookmark @break
                                        @case('Data') now-ui-icons files_single-copy-04 @break
                                        @case('Pengaturan') now-ui-icons ui-1_settings-gear-63 @break
                                        @default now-ui-icons design_bullet-list-67
                                    @endswitch
                                " style="color: #fff;"></i>
                    <p style="color: #fff;">{{ __($mainMenu) }} <b class="caret" style="color: #fff;"></b></p>
                </a>

                <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}" data-parent="#sidebar-menu">
                    <ul class="nav" style="color: #fff;">
                        @foreach ($visibleSubs as $sub)
                        @php
                        $subIsActive = $activePage == $sub->route || request()->routeIs($sub->route . '*');
                        @endphp
                        <li class="{{ $subIsActive ? 'active' : '' }}">
                            <a href="{{ $sub->route ? route($sub->route) : '#' }}" style="color: #fff;"
                                @if($subIsActive) data-parent-active="true" @endif>
                                <i class="{{ $sub->icon ?? 'now-ui-icons design_bullet-list-67' }}"
                                    style="color: #fff;"></i>
                                <p style="color: #fff;">{{ __($sub->sub_menu ?? $sub->main_menu) }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            @endif
            @endif
            @endforeach
        </ul>
    </div>
</div>