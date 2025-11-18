@extends('layouts.app', [
'namePage' => 'Master Sanksi',
'class' => 'sidebar-mini',
'activePage' => 'sanksi',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">

    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
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


            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Sanksi') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- Form Pencarian --}}
                        {{-- 🔍 Form Pencarian --}}
                        <form action="{{ route('sanksi.index') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari sanksi..." value="{{ request('search') }}">

                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>

                        {{-- Tombol Tampilkan Semua / Paginate --}}
                        @if (request()->has('all'))
                        <a href="{{ route('sanksi.index', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2" style="background:#eee733;color:#000;border:none;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('sanksi.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2" style="background:#29b14a;border:none;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah Sanksi --}}
                        @if (checkAccess('Master', 'Master Sanksi', 'create'))
                        <a href="{{ route('sanksi.create') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none;" title="Tambah Sanksi">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" style="color:#333;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th style="width:5%; text-align:center;">#</th>

                                    <th style="width:20%;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                                                'sort_by' => 'jenis',
                                                'sort_order' => (request('sort_by') === 'jenis' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                            ])) }}" class="text-success text-decoration-none">
                                            Jenis Sanksi
                                        </a>
                                    </th>


                                    <th style="width:35%;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                                                'sort_by' => 'keterangan',
                                                'sort_order' => (request('sort_by') === 'keterangan' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                            ])) }}" class="text-success text-decoration-none">
                                            Keterangan
                                    </th>

                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                                                'sort_by' => 'nilai',
                                                'sort_order' => (request('sort_by') === 'nilai' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                            ])) }}" class="text-success text-decoration-none">
                                            Nilai
                                    </th>

                                    <th style="width:20%; text-align:center;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sanksi as $index => $item)
                                <tr>
                                    <td style="text-align:center;">
                                        {{ $loop->iteration + (method_exists($sanksi, 'firstItem') ? $sanksi->firstItem() - 1 : 0) }}
                                    </td>

                                    <td>{{ $item->jenis }}</td>

                                    <td class="text-truncate" style="max-width: 300px;" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan }}
                                    </td>

                                    <td style="text-align:center;">
                                        Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if (checkAccess('Master', 'Master Sanksi', 'edit'))
                                        <a href="{{ route('sanksi.edit', $item->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        @if (checkAccess('Master', 'Master Sanksi', 'delete'))
                                        <form action="{{ route('sanksi.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                class="btn btn-danger btn-icon btn-sm btn-round btn-confirm"
                                                data-action="delete" data-url="{{ route('sanksi.destroy', $item->id) }}"
                                                title="Hapus" style="background:#e74c3c;border:none;">
                                                <i class="now-ui-icons ui-1_simple-remove"></i>
                                            </button>

                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data sanksi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $sanksi->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

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