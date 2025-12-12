@extends('layouts.app', [
'namePage' => 'Master Distributor',
'class' => 'sidebar-mini',
'activePage' => 'data_distributors',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show shadow-lg" role="alert"
                style="background: linear-gradient(135deg, #29b14a, #34d058); color:#fff; border:none; border-radius:14px;">
                <div class="d-flex align-items-center">
                    <span class="now-ui-icons ui-1_check mr-2" style="font-size:18px;"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" style="color:#fff; opacity:.8;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
            </div>
            @endif


            {{-- CARD DATA DISTRIBUTOR --}}
            <div class="card" style="border-radius:20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">Master Distributor</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- SEARCH --}}
                        <form action="{{ route('distributor.data') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari distributor..." value="{{ request('search') }}">
                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>

                        {{-- SHOW ALL --}}
                        @if (request()->has('all'))
                        <a href="{{ route('distributor.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('distributor.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- EXPORT --}}
                        @if (checkAccess('Master', 'Master Distributor', 'print'))
                        <a href="{{ route('distributor.exportExcel') }}" class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('distributor.exportPdf') }}" class="btn btn-danger btn-round"
                            style="background:#e74c3c;">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>
                {{-- TABLE --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" style="color:#333;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="text-center" style="width:5%">#</th>

                                    <th style="width:30%;">
                                        <a href="{{ route('distributor.data', array_merge(request()->query(), [
                                            'sort_by' => 'ID_DISTRIBUTOR',
                                            'sort_order' => (request('sort_by') === 'ID_DISTRIBUTOR' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>

                                    <th style="width:20%;">
                                        <a href="{{ route('distributor.data', array_merge(request()->query(), [
                                            'sort_by' => 'NAMA_DISTRIBUTOR',
                                            'sort_order' => (request('sort_by') === 'NAMA_DISTRIBUTOR' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Nama Distributor
                                        </a>
                                    </th>

                                    <th class="text-center" style="width:150px;">Total Salesman</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($distributor as $d)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration + (method_exists($distributor, 'firstItem') ? $distributor->firstItem() - 1 : 0) }}
                                    </td>

                                    <td>{{ $d->ID_DISTRIBUTOR }}</td>
                                    <td>{{ $d->NAMA_DISTRIBUTOR }}</td>

                                    <td class="text-center">
                                        <span class="badge-soft text-primary font-weight-bold" style="cursor:pointer;"
                                            onclick="showSalesman('{{ $d->ID_DISTRIBUTOR }}')">
                                            {{ $d->total_salesman }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data distributor</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $distributor->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>

{{-- MODAL SALES DISTRIBUTOR --}}
<div class="modal fade" id="modalSalesman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:750px;">
        <div class="modal-content border-0"
            style="background:rgba(255,255,255,0.97); border-radius:15px; box-shadow:0 4px 25px rgba(0,0,0,0.3);">

            <div class="modal-header" style="border-bottom:none;">
                <h5 class="modal-title text-primary" style="font-weight:600;">Daftar Salesman Distributor</h5>
            </div>

            <div class="modal-body" style="font-size:15px; color:#333;">
                <div id="salesmanTotal"></div>

                <div class="modal-table-wrapper">
                    <table class="table table-bordered table-striped">
                        <thead style="background:#29b14a; color:white;">
                            <tr>
                                <th class="text-center">#</th>
                                <th style="width:120px;">ID Salesman</th>
                                <th class="text-left">Nama Salesman</th>
                            </tr>
                        </thead>

                        <tbody id="tableSalesman"></tbody>
                    </table>
                </div>

                <div id="salesmanPagination" class="mt-2 text-center"></div>
            </div>

            <div class="modal-footer" style="border-top:none;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<style>
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
}

body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
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
    height: 95px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    display: flex !important;
    align-items: center !important;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

.navbar-soft .navbar-brand {
    color: #ffffff !important;
    font-size: 22px !important;
    font-weight: 700;
}

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
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important;
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

.pagination .page-link:hover {
    background: #29b14a !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(41, 177, 74, 0.35);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(41, 177, 74, 0.45) !important;
    transform: translateY(-2px);
}

.pagination .page-item.disabled .page-link {
    background: #f1f1f1 !important;
    color: #b4b4b4 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
}

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

.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #ff6b5c) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d84333, #fa5f50) !important;
}

.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #b0b3b7, #c9cbce) !important;
}

.btn-warning {
    background: linear-gradient(135deg, #eee733, #faf26b) !important;
    color: #333 !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e2db2e, #f0eb63) !important;
}

.btn-round {
    border-radius: 30px !important;
}

.btn i {
    font-size: 15px;
    margin-right: 6px;
}

.btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* ===========================================================
   SOFT UI SEARCH BAR
=========================================================== */

.action-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.search-group {
    display: flex;
    align-items: center;
    width: 260px;
    min-width: 260px;
}

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

/* ============================================
   RESPONSIVE TABLE – Header tidak pecah
============================================ */

.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
}

.table-responsive table {
    width: 100%;
    white-space: nowrap !important;
    table-layout: auto !important;
    min-width: max-content !important;
}

/* Padding sama seperti Master Salesman */
table.table th,
table.table td {
    white-space: nowrap !important;
    padding-left: 15px !important;
    padding-right: 15px !important;
    text-align: left !important;
}

/* Kolom pertama (#) — lebih ke kiri (rapat seperti di Salesman) */
table.table th:first-child,
table.table td:first-child {
    text-align: center !important;
    padding-left: 0px !important;
    margin-left: -8px !important;
    width: 36px !important;
}

/* Kolom ID Distributor */
table.table th:nth-child(2),
table.table td:nth-child(2) {
    width: 140px !important;
}

/* Kolom Nama Distributor */
table.table th:nth-child(3),
table.table td:nth-child(3) {
    width: 400px !important;
    padding-left: 8px !important;
}

table.table th:nth-child(3),
table.table td:nth-child(3) {
    width: 440px !important;
    padding-left: 8px !important;
}



/* Kolom terakhir tetap rata tengah */
table.table th:last-child,
table.table td:last-child {
    text-align: center !important;
}



/* ============================================================
   FIX MODAL BACKDROP TERLALU GELAP & MENGHALANGI KLIK
   ============================================================ */
.modal-backdrop.show {
    opacity: 0.15 !important;
    /* tidak gelap */
    background: rgba(0, 0, 0, 0.15) !important;
    pointer-events: none !important;
    /* supaya backdrop tidak memblok klik */
}

/* Tombol navigasi modal */
#modalPrev,
#modalNext,
#modalCloseBtn {
    z-index: 2106 !important;
    pointer-events: auto !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    color: #333 !important;
}

/* ====== FIX TABEL MOBILE ====== */
@media screen and (max-width: 768px) {

    .table-responsive table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        min-width: 600px;
        /* cegah header bertumpuk */
    }

    /* Kolom # agar tidak turun */
    table.table th:first-child,
    table.table td:first-child {
        width: 40px !important;
    }
}

/* ================================
   FIX MODAL TABLE RESPONSIVE
   ================================ */

.modal-table-wrapper {
    max-height: 55vh;
    overflow-y: auto;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}

/* Tabel modal: tidak bertumpuk & rapi */
#modalSalesman table {
    min-width: 600px !important;
    /* diperbesar agar kolom rapi */
    white-space: nowrap !important;
    table-layout: auto !important;
}

/* Header tabel tetap menempel saat scroll */
#modalSalesman thead {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #29b14a !important;
    color: white !important;
}

/* ===============================
   ALIGNMENT & PADDING (PERBAIKAN)
   =============================== */

/* Padding sel pada modal — samakan dengan tabel utama */
#modalSalesman table th,
#modalSalesman table td {
    padding-left: 15px !important;
    padding-right: 15px !important;
    text-align: left !important;
    /* default rata kiri */
    vertical-align: middle !important;
}

/* Kolom pertama (#) — tetap center */
#modalSalesman table th:nth-child(1),
#modalSalesman table td:nth-child(1) {
    text-align: center !important;
    width: 50px !important;
    padding-left: 0 !important;
}

/* Kolom kedua (ID Salesman) — rata kiri */
#modalSalesman table th:nth-child(2),
#modalSalesman table td:nth-child(2) {
    text-align: left !important;
    width: 140px !important;
}

/* Kolom ketiga (Nama Salesman) — FIX: benar-benar rata kiri */
#modalSalesman table th:nth-child(3),
#modalSalesman table td:nth-child(3) {
    text-align: left !important;
    width: 350px !important;
    padding-left: 10px !important;
}
</style>

@endpush
@push('js')
<script>
function showSalesman(idDistributor, pageUrl = null) {

    $("#tableSalesman").html(`
        <tr>
            <td colspan="3" class="text-center text-muted py-3">Loading...</td>
        </tr>
    `);

    $("#salesmanPagination").html("");
    $("#modalSalesman").modal('show');

    let url = pageUrl ?? ("{{ url('/distributor/get-salesman') }}/" + idDistributor);

    $.get(url, function(res) {

        // ===============================
        // FORMAT BARU (SAMA SEPERTI ASS)
        // ===============================

        let list = res.data || []; // ARRAY data
        let indexStart = res.first ?? 1; // NOMOR HALAMAN

        let rows = "";

        if (list.length === 0) {
            rows = `
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                </tr>
            `;
        } else {
            list.forEach((row, i) => {
                rows += `
                    <tr>
                        <td class="text-center">${indexStart + i}</td>
                        <td>${row.ID_SALESMAN ?? '-'}</td>
                        <td class="text-left">${row.NAMA_SALESMAN ?? '-'}</td>
                    </tr>
                `;
            });
        }

        $("#tableSalesman").html(rows);

        $("#salesmanTotal").html(`
            <div class="mb-2 text-right">
                <p class="text-primary font-weight-bold" style="font-size:14px;">
                    Total Salesman: ${res.total_salesman}
                </p>
            </div>
        `);

        $("#salesmanPagination").html(res.pagination);

        // AJAX pagination
        $("#salesmanPagination a.page-link").click(function(e) {
            e.preventDefault();
            showSalesman(idDistributor, $(this).attr("href"));
        });

    }).fail(function(xhr) {

        $("#tableSalesman").html(`
            <tr>
                <td colspan="3" class="text-center text-danger py-3">Gagal memuat data</td>
            </tr>
        `);
    });
}
</script>
@endpush