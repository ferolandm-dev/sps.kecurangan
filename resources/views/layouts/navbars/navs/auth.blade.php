<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute custom-navbar">
    <div class="container-fluid">

        {{-- LEFT AREA: Page Title + Breadcrumb --}}
        <div class="navbar-wrapper d-flex align-items-center">

            {{-- Toggle for mobile --}}
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>


            <div class="breadcrumb-wrapper-mini">
    {!! $breadcrumb ?? '' !!}
</div>

        </div>

        {{-- RIGHT AREA --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>


        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">

                {{-- ACCOUNT DROPDOWN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle no-focus" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">

                        <i class="now-ui-icons users_single-02"></i>
                        <p><span class="d-lg-none d-md-block">{{ __('Account') }}</span></p>

                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-animated"
                        aria-labelledby="navbarDropdownMenuLink">

                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="now-ui-icons users_single-02 mr-2"></i> My Profile
                        </a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            <i class="now-ui-icons media-1_button-power mr-2"></i> Logout
                        </a>
                    </div>
                </li>

            </ul>
        </div>

    </div>
</nav>


{{-- CUSTOM STYLE --}}
<style>
/* Remove default background and shadow */
.custom-navbar {
    box-shadow: none !important;
    background: transparent !important;
}

#navigation {
    margin-top: 20px !important;
}

.navbar-toggler {
    margin-top: 20px !important;
}


/* ======================================
   BREADCRUMB: Glass Premium (High Contrast)
====================================== */

.breadcrumb-custom {
    margin-top: -2px;
}

.breadcrumb-custom nav {
    margin: 0;
}

/* ======================================
   BREADCRUMB — Clean White + ALL Green Text
====================================== */

.breadcrumb-sps {
    display: flex;
    gap: 10px;
    align-items: center;

    padding: 10px 20px;
    margin: 0;
    margin-top: 20px;

    background: #ffffff;
    border-radius: 14px;

    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(8px);
    list-style: none;
}

/* Item default */
.breadcrumb-sps li {
    font-size: 14px;
    font-weight: 700;
    color: #29b14a !important;
    /* SEMUA HIJAU */
    position: relative;
}

/* Active item */
.breadcrumb-sps li.active {
    color: #29b14a !important;
    font-weight: 900;
}

/* Divider */
.breadcrumb-sps li:not(:last-child)::after {
    content: "›";
    margin-left: 8px;
    margin-right: 4px;
    color: #29b14a;
    /* divider juga hijau */
    font-size: 15px;
    font-weight: 900;
}

/* Link = hijau juga */
.breadcrumb-sps li a {
    color: #29b14a !important;
    /* LINK JUGA HIJAU */
    text-decoration: none;
    font-weight: 700;
    padding: 3px 6px;
    border-radius: 6px;
    transition: 0.18s ease;
}

/* Hover tetap hijau, hanya background yang muncul */
.breadcrumb-sps li a:hover {
    color: #29b14a !important;
    /* tetap hijau */
    background: rgba(41, 177, 74, 0.12);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.10);
}



/* ======================================
   DROPDOWN — Warna sama untuk Mobile & Desktop
====================================== */

/* Base dropdown */
.navbar .dropdown-menu,
.navbar-nav .dropdown-menu {
    background: #ffffff !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    padding: 8px 0;
    overflow: hidden;
}

/* Dropdown item */
.dropdown-menu .dropdown-item {
    padding: 12px 20px;
    font-weight: 600;
    color: #333 !important;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 0 !important;
    transition: 0.2s ease;
}

/* Hover in ALL screen sizes */
.dropdown-menu .dropdown-item:hover {
    background: rgba(41, 177, 74, 0.12) !important;
    color: #29b14a !important;
}

/* Icon warna */
.dropdown-menu .dropdown-item i {
    color: #888 !important;
    font-size: 18px;
}

/* Hover icon ikut hijau */
.dropdown-menu .dropdown-item:hover i {
    color: #29b14a !important;
}

/* ======================================
   Mobile FIX: Hilangkan flicker collapse
====================================== */

@media (max-width: 991px) {

    /* Background collapse */
    .navbar-collapse {
        background: #ffffff !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border-radius: 14px;
        padding: 15px 10px;
        transition: none !important;
    }

    /* Saat collapse masih proses animasi */
    .navbar-collapse.collapsing {
        background: #ffffff !important;
        height: auto !important;
        transition: none !important;
    }

    /* Dropdown di dalam collapse */
    .navbar-collapse .dropdown-menu {
        position: static !important;
        float: none !important;
        background: #ffffff !important;
        box-shadow: none !important;
        padding-left: 0;
        padding-right: 0;
        margin-top: 10px;
    }
}

/* ======================================
   FIX: Ketika layar diperbesar dari mobile
====================================== */
@media (max-width: 575px) {

  .breadcrumb-sps {
    display: flex;
    flex-wrap: nowrap;                 /* jangan wrap */
    overflow-x: auto;                  /* scroll horizontal */
    -webkit-overflow-scrolling: touch; /* smooth iPhone */
    white-space: nowrap !important;    /* tetap 1 baris */
    padding: 8px 12px;
    gap: 10px;

    max-width: 280px;                  /* <<< KUNCI BIAR KECIL */
    border-radius: 12px;
  }

  .breadcrumb-sps::-webkit-scrollbar {
    display: none;                     /* scrollbar hilang */
  }

  .breadcrumb-sps li {
    flex: 0 0 auto;
    white-space: nowrap;
  }
}
</style>