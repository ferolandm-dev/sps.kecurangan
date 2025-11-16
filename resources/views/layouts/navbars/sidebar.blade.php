    {{-- Jangan tampilkan sidebar pada halaman login --}}
    @if (request()->routeIs('login'))
    @php return; @endphp
    @endif

    <div class="sidebar" data-color="green" style="background-color: #29b14a; min-height: 100vh; color: #fff;">

        <div class="logo" style="text-align: center; padding: 10px 0;">
            <div class="logo-image">
                <img src="{{ asset('assets/img/SPS LOGO.png') }}" alt="SPS Logo"
                    style="height: 50px; width: auto; display: block; margin: 0 auto;">
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
    @push('js')
    <style>
/* ---- SIDEBAR BACKGROUND (NEW) ---- */
.sidebar {
    background: linear-gradient(180deg,
            #29b14a 0%,
            #29b14a 85%,
            rgba(219, 211, 0, 0.35) 100%) !important;
    color: #fff !important;
}


/* ---- BASE LINK STYLE ---- */
.sidebar .nav li>a,
.sidebar .nav .collapse .nav li>a {
    position: relative;
    border-radius: 12px;
    padding: 10px 15px;
    transition: all 0.25s ease-in-out;
    color: #fff !important;
}

/* ---- HOVER STATE ---- */
.sidebar .nav li>a:hover,
.sidebar .nav .collapse .nav li>a:hover {
    background: linear-gradient(90deg,
            rgba(255, 255, 255, 0.20) 0%,
            rgba(255, 255, 255, 0.10) 100%) !important;

    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
    color: #fff !important;
}

/* ---- ACTIVE MENU ---- */
.sidebar .nav li.active>a,
.sidebar .nav li.active>a:hover {
    background: linear-gradient(90deg,
            rgba(255, 255, 255, 0.35) 0%,
            rgba(255, 255, 255, 0.18) 100%) !important;

    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.40);
    font-weight: 600;
}

/* ---- EXPANDED MAIN MENU ---- */
.sidebar .nav>li>a[aria-expanded="true"] {
    background: linear-gradient(90deg,
            rgba(255, 255, 255, 0.28) 0%,
            rgba(255, 255, 255, 0.15) 100%) !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.30);
    color: #fff !important;
}

/* ---- ICON & TEXT ---- */
.sidebar .nav li>a i,
.sidebar .nav li>a p {
    color: #fff !important;
    transition: all .25s ease-in-out;
}

/* ---- ICON MOVE ON HOVER ---- */
.sidebar .nav li>a:hover i {
    transform: translateX(3px);
}

/* ---- SUBMENU ---- */
.sidebar .nav .collapse .nav li>a {
    padding-left: 34px !important;
}
    </style>
    @endpush