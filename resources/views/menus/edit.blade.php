@extends('layouts.app', [
'namePage' => isset($menu) ? 'Edit Menu' : 'Tambah Menu',
'class' => 'sidebar-mini',
'activePage' => 'menus',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content" style="
    backdrop-filter: blur(10px);
    margin-top: -60px;
    padding: 30px;
    color: #333;
">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #29b14a 0%, #34d058 100%);
                color: #fff; border: none; border-radius: 14px; padding: 14px 18px;
                font-weight: 500; letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(41,177,74,0.3);
                display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;
            ">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                    style="color:#fff;opacity:0.8;font-size:22px;margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff; border: none; border-radius: 14px; padding: 14px 18px;
                font-weight: 500; letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;
            ">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{!! session('error') !!}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                    style="color:#fff;opacity:0.8;font-size:22px;margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ALERT VALIDASI --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff; border: none; border-radius: 14px; padding: 14px 18px;
                font-weight: 500; letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                margin-bottom: 25px;
            ">
                <div class="d-flex align-items-start">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;margin-top:2px;"></i>
                    <ul class="mb-0 pl-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                    style="color:#fff;opacity:0.8;font-size:22px;margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- FORM TAMBAH / EDIT MENU --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">
                        {{ isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru' }}
                    </h4>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <form method="POST"
                        action="{{ isset($menu) ? route('menus.update', $menu->id) : route('menus.store') }}">
                        @csrf
                        @if (isset($menu))
                        @method('PUT')
                        @endif

                        {{-- Main Menu --}}
                        <div class="form-group">
                            <label for="main_menu">{{ __('Main Menu') }}</label>
                            <input type="text" name="main_menu" id="main_menu"
                                class="form-control @error('main_menu') is-invalid @enderror"
                                value="{{ old('main_menu', $menu->main_menu ?? '') }}" placeholder="Contoh: Master">
                            @error('main_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sub Menu --}}
                        <div class="form-group">
                            <label for="sub_menu">{{ __('Sub Menu') }}</label>
                            <input type="text" name="sub_menu" id="sub_menu"
                                class="form-control @error('sub_menu') is-invalid @enderror"
                                value="{{ old('sub_menu', $menu->sub_menu ?? '') }}" placeholder="Contoh: Data User">
                            @error('sub_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div class="form-group">
                            <label for="icon">{{ __('Icon') }}</label>
                            <input type="text" name="icon" id="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                value="{{ old('icon', $menu->icon ?? '') }}"
                                placeholder="Contoh: now-ui-icons users_single-02">
                            @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Route --}}
                        <div class="form-group">
                            <label for="route">{{ __('Route') }}</label>
                            <input type="text" name="route" id="route"
                                class="form-control @error('route') is-invalid @enderror"
                                value="{{ old('route', $menu->route ?? '') }}" placeholder="Contoh: users.index">
                            @error('route')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Main Order --}}
                        <div class="form-group">
                            <label for="main_order">{{ __('Urutan Main Menu') }}</label>
                            <input type="number" name="main_order" id="main_order"
                                class="form-control @error('main_order') is-invalid @enderror"
                                value="{{ old('main_order', $menu->main_order ?? 0) }}" placeholder="Contoh: 1">
                            @error('main_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Order Sub Menu --}}
                        <div class="form-group">
                            <label for="order">{{ __('Urutan Sub Menu') }}</label>
                            <input type="number" name="order" id="order"
                                class="form-control @error('order') is-invalid @enderror"
                                value="{{ old('order', $menu->order ?? 0) }}" min="0">
                            @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hak Akses --}}
                        <div class="border-top pt-3 mt-3">
                            <h5 class="mb-3">{{ __('Hak Akses Menu') }}</h5>
                            <div class="row d-inline-flex">
                                <div class="col-md-5">
                                    <label>{{ __('CRUD') }}</label>
                                    <select name="can_crud" class="form-select mb-2" style="width: 200px">
                                        <option value="1"
                                            {{ old('can_crud', $menu->can_crud ?? 0) == 1 ? 'selected' : '' }}>Ya
                                        </option>
                                        <option value="0"
                                            {{ old('can_crud', $menu->can_crud ?? 0) == 0 ? 'selected' : '' }}>Tidak
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label>{{ __('PRINT') }}</label>
                                    <select name="can_print" class="form-select" style="width: 200px">
                                        <option value="1"
                                            {{ old('can_print', $menu->can_print ?? 0) == 1 ? 'selected' : '' }}>Ya
                                        </option>
                                        <option value="0"
                                            {{ old('can_print', $menu->can_print ?? 0) == 0 ? 'selected' : '' }}>Tidak
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-3 mb-3">
                            * Minimal isi salah satu field utama (Main Menu, Sub Menu, atau Route).<br>
                            * “Urutan Main Menu” menentukan posisi menu utama di sidebar.<br>
                            * “Urutan Sub Menu” menentukan posisi submenu di dalam Main Menu yang sama.
                        </small>

                        <div class="text-right mt-4">
                            <a href="{{ route('menus.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                Simpan
                            </button>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
    /* warna abu normal */
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* hijau atau sesuai tema */
}

.panel-header-sps {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%);
    height: 120px !important;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* ========================================
   NAVBAR MATCHING — SAME GRADIENT AS HEADER
========================================= */

.navbar-soft {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%) !important;
    border: none !important;
    box-shadow: none !important;

    /* Tinggi navbar sesuai permintaan */
    height: 95px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;

    display: flex !important;
    align-items: center !important;

    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* Brand */
.navbar-soft .navbar-brand {
    color: #ffffff !important;
    font-size: 22px !important;
    font-weight: 700;
}

/* Icons */
.navbar-soft .nav-link i {
    color: #ffffff !important;
    font-size: 22px;
    transition: .2s ease;
}

.navbar-soft .nav-link:hover i {
    color: #333 !important;
}

.navbar-soft {
    transition: none !important; /* matikan transisi container */
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important; /* biarkan hover tetap smooth */
}

/* ===========================================================
   GLOBAL SOFT UI BUTTON STYLE
=========================================================== */
.btn {
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 8px 18px !important;
    transition: all 0.25s ease-in-out !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
}

/* SUCCESS BUTTON (Hijau) */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

/* DANGER BUTTON (Merah) */
.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #ff6b5c) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d84333, #fa5f50) !important;
}

/* SECONDARY BUTTON (Abu) */
.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #b0b3b7, #c9cbce) !important;
}

/* WARNING BUTTON (Kuning lembut) */
.btn-warning {
    background: linear-gradient(135deg, #eee733, #faf26b) !important;
    color: #333 !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e2db2e, #f0eb63) !important;
}

/* ROUND STYLE */
.btn-round {
    border-radius: 30px !important;
}

/* ICON ALIGNMENT FIX */
.btn i {
    font-size: 15px;
    margin-right: 6px;
}

/* DISABLED BUTTON STYLE */
.btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}
</style>
@endpush