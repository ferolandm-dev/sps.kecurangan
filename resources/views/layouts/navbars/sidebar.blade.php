<div class="sidebar" data-color="orange">
    <div class="logo">
        <a href="#" class="simple-text logo-mini" style="font-size: 12px;">
            {{ __('SPS') }}
        </a>
        <a href="#" class="simple-text logo-normal" style="font-size: 12px;">
            {{ __('SINAR PANGAN SEJAHTERA') }}
        </a>
    </div>

    <div class="sidebar-wrapper" id="sidebar-wrapper">
        @php
        use Illuminate\Support\Str;
        use Illuminate\Support\Facades\DB;

        // Ambil semua menu dari DB dan kelompokkan per main_menu
        $menus = DB::table('menus')
        ->select('id', 'main_menu', 'sub_menu', 'icon', 'route', 'order', 'main_order')
        ->orderBy('main_order') // urut utama pakai main_order
        ->orderBy('main_menu')
        ->orderBy('order')
        ->get()
        ->groupBy('main_menu');
        @endphp

        <ul class="nav" id="sidebar-menu">
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
                <a href="{{ $menu->route ? route($menu->route) : '#' }}">
                    <i class="{{ $menu->icon ?? 'now-ui-icons design_bullet-list-67' }}"></i>
                    <p>{{ __($menu->sub_menu ?? $menu->main_menu ?? 'Menu') }}</p>
                </a>
            </li>
            @endif
            @endforeach

            {{-- 🔹 MENU DENGAN MAIN_MENU --}}
            @elseif (isset($userMenus[$mainMenu]) && count($subs) > 0)
            @php
            // Filter submenu yang bisa diakses user
            $visibleSubs = $subs->filter(function ($sub) use ($userMenus, $mainMenu) {
            return in_array($sub->sub_menu, $userMenus[$mainMenu]) || $sub->sub_menu === null;
            });

            // Buat ID collapse unik & aman berdasarkan ID menu pertama (tidak bisa tabrakan)
            $collapseId = 'menu_' . ($subs->first()->id ?? crc32($mainMenu));
            $isActive = $visibleSubs->pluck('route')->contains(function ($r) use ($activePage) {
            return $r === $activePage || request()->routeIs($r . '*');
            });

            @endphp

            @if ($visibleSubs->count() > 0)
            <li>
                {{-- Tombol Collapse --}}
                <a data-toggle="collapse" href="#{{ $collapseId }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                    data-parent="#sidebar-menu">
                    <i class="
                                    @switch($mainMenu)
                                        @case('Dashboard') now-ui-icons business_chart-pie-36 @break
                                        @case('Master') now-ui-icons education_agenda-bookmark @break
                                        @case('Data') now-ui-icons files_single-copy-04 @break
                                        @case('Pengaturan') now-ui-icons ui-1_settings-gear-63 @break
                                        @default now-ui-icons design_bullet-list-67
                                    @endswitch
                                "></i>
                    <p>{{ __($mainMenu) }} <b class="caret"></b></p>
                </a>

                {{-- Submenu --}}
                <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}" data-parent="#sidebar-menu">
                    <ul class="nav">
                        @foreach ($visibleSubs as $sub)
                        <li class="{{ $activePage == $sub->route ? 'active' : '' }}">
                            <a href="{{ $sub->route ? route($sub->route) : '#' }}">
                                <i class="{{ $sub->icon ?? 'now-ui-icons design_bullet-list-67' }}"></i>
                                <p>{{ __($sub->sub_menu ?? $sub->main_menu) }}</p>
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