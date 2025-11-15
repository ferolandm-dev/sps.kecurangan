    <div class="sidebar" data-color="green" style="background-color: #29b14a; min-height: 100vh; color: #fff;">

        <div class="logo" style="text-align: center; padding: 10px 0;">
            <div class="logo-image">
                <img src="{{ asset('assets/img/SPS LOGO.png') }}" alt="SPS Logo"
                    style="height: 50px; width: auto; display: block; margin: 0 auto;">
            </div>
            <div class="logo-text"
                style="margin-top: 10px; font-size: 12px; color: #fff; font-weight: 600; line-height: 1.2;">
                {{ __('SINAR PANGAN SEJAHTERA') }}
            </div>
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
                @php
                use Illuminate\Support\Facades\Auth;

                $userId = Auth::id();

                // Ambil semua menu user yang punya can_access = 1
                $userAccess = DB::table('user_access')
                ->where('user_id', $userId)
                ->where('can_access', 1)
                ->get()
                ->map(function ($row) {
                return [
                'main' => $row->main_menu,
                'sub' => $row->sub_menu,
                ];
                });

                $accessMap = $userAccess->groupBy('main')->map(function ($group) {
                return $group->pluck('sub')->filter()->toArray();
                });
                @endphp

                @foreach ($menus as $mainMenu => $subs)
                @php
                $mainAccess = $accessMap->has($mainMenu);
                @endphp

                @if (!$mainAccess)
                @continue
                @endif

                {{-- 🔹 Menu tanpa sub_menu --}}
                @if ($subs->count() === 1 && empty($subs->first()->sub_menu))
                @php $menu = $subs->first(); @endphp
                <li class="{{ $activePage == $menu->route ? 'active' : '' }}">
                    <a href="{{ $menu->route ? route($menu->route) : '#' }}" style="color:#fff;">
                        <i class="{{ $menu->icon ?? 'now-ui-icons design_bullet-list-67' }}" style="color:#fff;"></i>
                        <p style="color:#fff;">{{ __($menu->main_menu) }}</p>
                    </a>
                </li>
                @else
                @php
                $visibleSubs = collect($subs)->filter(function ($sub) use ($accessMap, $mainMenu) {
                if (!isset($accessMap[$mainMenu])) return false;
                return in_array($sub->sub_menu, $accessMap[$mainMenu]);
                });

                $collapseId = 'menu_' . ($subs->first()->id ?? crc32($mainMenu));
                $isActive = $visibleSubs->pluck('route')->contains(function ($r) use ($activePage) {
                return $r === $activePage || request()->routeIs($r . '*');
                });
                @endphp

                @if ($visibleSubs->count() > 0)
                <li>
                    <a href="javascript:void(0);" class="menu-toggle {{ $isActive ? '' : 'collapsed' }}"
                        data-target="#{{ $collapseId }}" style="color:#fff;">
                        <i class="
                            @switch($mainMenu)
                                @case('Master') now-ui-icons education_agenda-bookmark @break
                                @case('Data') now-ui-icons files_single-copy-04 @break
                                @case('Pengaturan') now-ui-icons ui-1_settings-gear-63 @break
                                @default now-ui-icons design_bullet-list-67
                            @endswitch
                        " style="color:#fff;"></i>
                        <p style="color:#fff;">{{ __($mainMenu) }} <b class="caret" style="color:#fff;"></b></p>
                    </a>

                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                        <ul class="nav" style="color:#fff;">
                            @foreach ($visibleSubs as $sub)
                            @php
                            $subIsActive = $activePage == $sub->route || request()->routeIs($sub->route . '*');
                            @endphp
                            <li class="{{ $subIsActive ? 'active' : '' }}">
                                <a href="{{ $sub->route ? route($sub->route) : '#' }}" style="color:#fff;">
                                    <i class="{{ $sub->icon ?? 'now-ui-icons design_bullet-list-67' }}"
                                        style="color:#fff;"></i>
                                    <p style="color:#fff;">{{ __($sub->sub_menu ?? $sub->main_menu) }}</p>
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

            {{-- 🔧 SCRIPT AGAR LANGSUNG TERBUKA SAAT KLIK SEKALI --}}
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar-menu');

                sidebar.querySelectorAll('.menu-toggle').forEach(trigger => {
                    trigger.addEventListener('click', function() {
                        const targetSelector = this.getAttribute('data-target');
                        const target = document.querySelector(targetSelector);

                        // Tutup semua collapse lain
                        sidebar.querySelectorAll('.collapse.show').forEach(openMenu => {
                            if (openMenu !== target) {
                                $(openMenu).collapse('hide');
                            }
                        });

                        // Toggle menu yang diklik (langsung 1 klik)
                        $(target).collapse('toggle');
                    });
                });
            });
            </script>


        </div>
    </div>
    @push('css')
    <style>
.sidebar .nav li>a,
.sidebar .nav .collapse .nav li>a {
    position: relative;
    transition: all 0.25s ease-in-out;
    border-radius: 10px;
}

.sidebar .nav li>a:hover,
.sidebar .nav li.active>a,
.sidebar .nav li.active>a:hover,
.sidebar .nav .collapse .nav li.active>a,
.sidebar .nav .collapse .nav li>a:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(6px) !important;
    -webkit-backdrop-filter: blur(6px) !important;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
    color: #fff !important;
}

.sidebar .nav>li>a[aria-expanded="true"],
.sidebar .nav>li.active>a[aria-expanded="true"] {
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(6px) !important;
    -webkit-backdrop-filter: blur(6px) !important;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15);
    color: #fff !important;
}

.sidebar .nav li>a i,
.sidebar .nav li>a p {
    color: #fff !important;
    transition: all 0.25s ease-in-out;
}
    </style>
    @end