@extends('layouts.app', [
'namePage' => 'User Management',
'class' => 'sidebar-mini',
'activePage' => 'users',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
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
                margin-bottom: 25px;
            ">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;
                ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ⚙️ USER MANAGEMENT CARD --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('User Management') }}</h4>

                    @php
                    $canCreate = DB::table('user_access')
                    ->where('user_id', auth()->id())
                    ->where('main_menu', 'Pengaturan')
                    ->where('sub_menu', 'User Management')
                    ->value('can_create');
                    @endphp

                    @if ($canCreate)
                    <a href="{{ route('user.create') }}" class="btn btn-success btn-icon btn-round"
                        style="background:#29b14a;border:none;" title="Tambah User">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-items-center mb-0" style="color:#333;">
                            <thead style="color:#29b14a">
                                <tr>
                                    <th>Profile</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-center">Tanggal Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                @php
                                $access = DB::table('user_access')
                                ->where('user_id', auth()->id())
                                ->where('main_menu', 'Pengaturan')
                                ->where('sub_menu', 'User Management')
                                ->select('can_edit', 'can_delete')
                                ->first();
                                @endphp

                                <tr>
                                    <td>
                                        <span class="avatar avatar-sm rounded-circle">
                                            <img src="{{ asset('assets/img/default-avatar.png') }}" alt="avatar"
                                                style="max-width: 50px; border-radius: 50%;">
                                        </span>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">

                                        {{-- Tombol Atur Akses --}}
                                        @if ($user->id != 1 || auth()->id() == 1)
                                        <a href="{{ route('user.access', $user->id) }}"
                                            class="btn btn-info btn-icon btn-sm btn-round" title="Atur Akses">
                                            <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Edit --}}
                                        @if ($access && $access->can_edit && ($user->id != 1 || auth()->id() == 1))
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        @if ($access && $access->can_delete && ($user->id != 1 || auth()->id() == 1))
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-icon btn-sm btn-round btn-confirm"
                                                data-action="delete" data-url="{{ route('user.delete', $user->id) }}"
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

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
</div>
{{-- ===================== MODAL KONFIRMASI DELETE ===================== --}}
<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:420px;">
        <div class="modal-content" style="
            background:white;
            border-radius:18px;
            box-shadow:0 6px 20px rgba(0,0,0,0.25);
        ">
            <div class="modal-body text-center p-4">

                <i id="confirmIcon" class="now-ui-icons ui-1_alert" style="font-size:48px;color:#e74c3c;"></i>

                <h4 class="mt-3 mb-2" id="confirmTitle" style="font-weight:700;">Konfirmasi</h4>
                <p class="text-muted" id="confirmMessage" style="font-size:15px;"></p>

                <div class="mt-4 d-flex justify-content-center gap-2">
                    <button class="btn btn-secondary btn-round" data-dismiss="modal">
                        Batal
                    </button>

                    <form id="confirmForm" method="POST" class="m-0">
                        @csrf
                        @method("POST")
                        <button type="submit" class="btn btn-danger btn-round">
                            Lanjutkan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
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

body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
    /* supaya smooth */
}


.panel-header-sps {
    background: transparent !important;
    box-shadow: none !important;
}


.content {
    background: transparent !important;
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
</style>
@endpush
@push('js')
<script>
// =============================
//      MODAL KONFIRMASI GLOBAL
// =============================
$(document).on('click', '.btn-confirm', function() {

    let action = $(this).data('action');
    let url = $(this).data('url');

    // Set form action ke URL delete/validasi
    $('#confirmForm').attr('action', url);

    // Reset method
    $('#confirmForm input[name="_method"]').remove();
    $('#confirmForm input[name="_method"]').remove();

    // DELETE
    if (action === 'delete') {
        $('#confirmTitle').text('Hapus Data?');
        $('#confirmMessage').text('Data yang dihapus tidak dapat dikembalikan.');

        $('#confirmForm').append('<input type="hidden" name="_method" value="DELETE">');
        $('#confirmForm button[type="submit"]').removeClass('btn-success').addClass('btn-danger');
    }

    // VALIDASI
    if (action === 'validasi') {
        $('#confirmIcon').removeClass().addClass('now-ui-icons ui-1_check')
            .css('color', '#29b14a');

        $('#confirmTitle').text('Validasi Data?');
        $('#confirmMessage').text('Pastikan data sudah benar sebelum divalidasi.');

        $('#confirmForm button[type="submit"]').removeClass('btn-danger').addClass('btn-success');
    }

    // Tampilkan modal
    $('#modalConfirm').modal('show');
});
</script>
@endpush