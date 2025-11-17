@extends('layouts.app', [
'namePage' => 'Menu Management',
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

    <div class="row">
        <div class="col-md-12">

            {{-- ✅ ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #29b14a 0%, #34d058 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(41,177,74,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ⚠️ ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{!! session('error') !!}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ✅ CARD MENU MANAGEMENT --}}
            <div class="card" style="border-radius:20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Menu Management') }}</h4>

                    {{-- Tombol Tambah Menu --}}
                    @if (checkAccess('Pengaturan', 'Menu Management', 'create'))
                    <a href="{{ route('menus.create') }}" class="btn btn-primary btn-icon btn-round"
                        style="background:#29b14a;border:none;" title="Tambah Menu">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">

                    {{-- ✅ Loop per Main Menu --}}
                    @forelse ($menus as $mainMenu => $items)
                    <div class="mb-5">
                        <h5 class="font-weight-bold mb-3" style="
            color:#29b14a;
            border-bottom:2px solid #ddd;
            padding-bottom:6px;">
                            {{ $mainMenu === '' ? 'Tanpa Kategori' : $mainMenu }}
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0" style="color:#333;">
                                <thead style="color:#29b14a;">
                                    <tr class="text-center">
                                        <th class="text-left">Sub Menu</th>
                                        <th class="text-left">Icon</th>
                                        <th class="text-left">Route</th>
                                        <th>Main Order</th>
                                        <th>Order</th>
                                        <th>CRUD</th>
                                        <th>Print</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $menu)
                                    <tr>
                                        <td class="text-left">{{ $menu->sub_menu ?? '-' }}</td>
                                        <td class="text-left">{{ $menu->icon ?? '-' }}</td>
                                        <td class="text-left">{{ $menu->route ?? '-' }}</td>
                                        <td class="text-center">{{ $menu->main_order ?? 0 }}</td>
                                        <td class="text-center">{{ $menu->order ?? 0 }}</td>

                                        {{-- CRUD --}}
                                        <td class="text-center">
                                            @if ($menu->can_crud)
                                            <span class="badge badge-success"
                                                style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">Ya</span>
                                            @else
                                            <span class="badge badge-secondary"
                                                style="border-radius:10px;padding:6px 10px;">Tidak</span>
                                            @endif
                                        </td>

                                        {{-- Print --}}
                                        <td class="text-center">
                                            @if ($menu->can_print)
                                            <span class="badge badge-success"
                                                style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">Ya</span>
                                            @else
                                            <span class="badge badge-secondary"
                                                style="border-radius:10px;padding:6px 10px;">Tidak</span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="text-center">
                                            @if (checkAccess('Pengaturan', 'Menu Management', 'edit'))
                                            <a href="{{ route('menus.edit', $menu->id) }}"
                                                class="btn btn-warning btn-icon btn-sm btn-round"
                                                style="background:#eee733;border:none;" title="Edit">
                                                <i class="now-ui-icons ui-2_settings-90"></i>
                                            </a>
                                            @endif

                                            @if (checkAccess('Pengaturan', 'Menu Management', 'delete'))
                                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round"
                                                    style="background:#e74c3c;border:none;" title="Hapus">
                                                    <i class="now-ui-icons ui-1_simple-remove"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">Belum ada menu</div>
                    @endforelse

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

/* =============================== */
/*   SOFT UI MODERN PAGINATION     */
/* =============================== */

.pagination {
    display: flex;
    gap: 6px;
}

.pagination .page-item {
    transition: 0.25s ease;
}

/* Default */
.pagination .page-link {
    color: #29b14a !important;
    border: none !important;
    background: #ffffff !important;
    border-radius: 12px !important;
    padding: 8px 14px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease-in-out;
}

/* Hover */
.pagination .page-link:hover {
    background: #29b14a !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(41, 177, 74, 0.35);
}

/* Active page */
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(41, 177, 74, 0.45) !important;
    transform: translateY(-2px);
}

/* Disabled */
.pagination .page-item.disabled .page-link {
    background: #f1f1f1 !important;
    color: #b4b4b4 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
}

/* Hover disabled (tidak berubah) */
.pagination .page-item.disabled .page-link:hover {
    background: #f1f1f1 !important;
    color: #b4b4b4 !important;
    transform: none !important;
    box-shadow: none !important;
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

/* ===========================================================
   SOFT UI SEARCH BAR
=========================================================== */
/* WRAPPER agar semua tombol & search sejajar */
.action-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    /* jarak antar elemen */
    margin-top: 10px;
}

/* SEARCH WRAPPER */
.search-group {
    display: flex;
    align-items: center;
    width: 260px;
    min-width: 260px;
}

/* SEARCH INPUT */
.search-input {
    height: 35px !important;
    border-radius: 20px 0 0 20px !important;
    border: 1px solid #cfd3d6 !important;
    padding-left: 15px !important;
    background: #fff;
    transition: all .2s ease-in-out;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    font-size: 14px;
}

/* SEARCH BUTTON */
.search-btn {
    height: 35px !important;
    border-radius: 0 20px 20px 0 !important;
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    border: none !important;
    color: #fff !important;
    padding: 0 16px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(41, 177, 74, 0.3) !important;
    transition: all .2s ease-in-out;
}

.search-btn:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(41, 177, 74, 0.4) !important;
}
</style>
@endpush
@push('js')
<script>
$(document).ready(function() {
    $('.table').DataTable({
        pagingType: "full_numbers",
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari menu...",
        }
    });
});
</script>
@endpush