@extends('layouts.app', [
'namePage' => 'User Access',
'class' => 'sidebar-mini',
'activePage' => 'user',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content" style="
    backdrop-filter: blur(10px);
    margin-top: -60px;
    padding: 30px;
    color: #333;
">
    <div class="card shadow-sm" style="border-radius: 20px;">
        <div class="card-header d-flex justify-content-between align-items-center ">
            <h4 class="card-title mb-0 ">Pengaturan Akses - {{ $user->name }}</h4>
        </div>

        {{-- ✅ Flash Message --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="now-ui-icons mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
            <i class="now-ui-icons ui-1_simple-remove mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        {{-- ✅ Card Body --}}
        <div class="card-body">
            <form action="{{ route('user.updateAccess', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @php
                    $menus = DB::table('menus')
                    ->select('id', 'main_menu', 'sub_menu', 'can_crud', 'can_print', 'main_order', 'order')
                    ->orderBy('main_order')
                    ->orderBy('order')
                    ->get()
                    ->groupBy('main_menu');
                    @endphp

                    @foreach ($menus as $mainKey => $subs)
                    @php
                    $main = $mainKey ?: 'root';
                    $filteredSubs = $subs->filter(fn($s) => strtolower($s->sub_menu) !== 'user profile');
                    if ($filteredSubs->isEmpty()) continue;

                    $hasSubMenu = $filteredSubs->contains(fn($s) => !empty($s->sub_menu));
                    $accessGroup = $userAccess[$mainKey] ?? $userAccess[''] ?? collect();
                    @endphp

                    <div class="col-md-12">
                        <div class="menu-access-box">
                            <div class="d-flex align-items-center mb-2">
                                {{-- Jika tidak ada sub menu, checkbox di kiri --}}
                                @if (!$hasSubMenu)
                                @php
                                $menu = $filteredSubs->first();
                                $access = collect($accessGroup)->firstWhere('sub_menu', null);
                                @endphp

                                <label class="custom-checkbox mb-0 mr-2">
                                    <input type="hidden" name="access[{{ $main }}][_main_][can_access]" value="0">
                                    <input type="checkbox" name="access[{{ $main }}][_main_][can_access]" value="1"
                                        {{ $access && $access->can_access ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                                @endif

                                <h5 class="menu-access-title mb-0">
                                    {{ $mainKey ? ucfirst($mainKey) : 'Tanpa Kategori' }}
                                </h5>
                            </div>

                            {{-- Jika punya sub menu, tampilkan di dalam kotak --}}
                            @if ($hasSubMenu)
                            @foreach ($filteredSubs as $sub)
                            @php
                            if (empty($sub->sub_menu)) continue;
                            $access = collect($accessGroup)->firstWhere('sub_menu', $sub->sub_menu);
                            $canCrud = (int) ($sub->can_crud ?? 0);
                            $canPrint = (int) ($sub->can_print ?? 0);
                            @endphp

                            <div class="custom-checkbox-group ml-3">
                                <label class="custom-checkbox">
                                    <input type="hidden" name="access[{{ $main }}][{{ $sub->sub_menu }}][can_access]"
                                        value="0">
                                    <input type="checkbox" name="access[{{ $main }}][{{ $sub->sub_menu }}][can_access]"
                                        value="1" {{ $access && $access->can_access ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="label-text">{{ ucfirst($sub->sub_menu) }}</span>
                                </label>

                                <div class="ml-4 crud-options">
                                    @if ($canCrud === 1)
                                    <label class="custom-checkbox small-checkbox">
                                        <input type="hidden"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_create]" value="0">
                                        <input type="checkbox"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_create]" value="1"
                                            {{ $access && $access->can_create ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="label-text">Create</span>
                                    </label>

                                    <label class="custom-checkbox small-checkbox">
                                        <input type="hidden" name="access[{{ $main }}][{{ $sub->sub_menu }}][can_edit]"
                                            value="0">
                                        <input type="checkbox"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_edit]" value="1"
                                            {{ $access && $access->can_edit ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="label-text">Edit</span>
                                    </label>

                                    <label class="custom-checkbox small-checkbox">
                                        <input type="hidden"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_delete]" value="0">
                                        <input type="checkbox"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_delete]" value="1"
                                            {{ $access && $access->can_delete ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="label-text">Delete</span>
                                    </label>
                                    @endif

                                    @if ($canPrint === 1)
                                    <label class="custom-checkbox small-checkbox">
                                        <input type="hidden" name="access[{{ $main }}][{{ $sub->sub_menu }}][can_print]"
                                            value="0">
                                        <input type="checkbox"
                                            name="access[{{ $main }}][{{ $sub->sub_menu }}][can_print]" value="1"
                                            {{ $access && $access->can_print ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="label-text">Print</span>
                                    </label>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>


                <div class="text-right mt-3">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary btn-round">Batal</a>
                    <button type="submit" class="btn btn-success btn-round">
                        <i class="now-ui-icons"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.panel-header-sps {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%);
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
    transition: none !important;
    /* matikan transisi container */
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important;
    /* biarkan hover tetap smooth */
}


.menu-access-box {
    border: 1px solid #e4e4e4;
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 20px;
    background-color: #fff;
    transition: all 0.2s ease;
}

.menu-access-box:hover {
    background-color: #fef9f7;
    box-shadow: 0 0 10px rgba(249, 99, 50, 0.1);
}

.menu-access-title {
    font-size: 17px;
    font-weight: 600;
    color: #29b14a;
    margin-bottom: 12px;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
    cursor: pointer;
    position: relative;
    user-select: none;
}

.custom-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    width: 18px;
    height: 18px;
    background-color: #fff;
    border: 2px solid #29b14a;
    border-radius: 4px;
    transition: all 0.2s ease;
    display: inline-block;
    position: relative;
}

.custom-checkbox .checkmark::after {
    content: "";
    position: absolute;
    display: none;
}

.custom-checkbox input:checked~.checkmark::after {
    display: block;
}

.custom-checkbox .checkmark::after {
    left: 4px;
    top: 0px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.label-text {
    font-size: 15px;
    color: #333;
}

.crud-options {
    display: flex;
    gap: 16px;
    margin-left: 28px;
    margin-bottom: 8px;
}

.crud-label {
    font-size: 14px;
    color: #666;
}

.crud-label input {
    margin-right: 4px;
}

/* Menonaktifkan Ikon Centang */
.crud-options .checkmark::after {
    /* Menghapus tampilan tanda centang dengan memastikan propertinya tidak ditampilkan */
    content: none !important;
    display: none !important;
}

/* KODE LAINNYA UNTUK UKURAN CHECKMARK TETAP SAMA */
.crud-options .checkmark {
    width: 12px !important;
    height: 12px !important;
    border-width: 1px !important;
    border-radius: 10px;
}

/* Pastikan warna latar belakang saat di-check tetap ada */
.custom-checkbox input:checked~.checkmark {
    background-color: #29b14a;
    border-color: #29b14a;
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