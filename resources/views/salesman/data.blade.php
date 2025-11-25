@extends('layouts.app', [
'namePage' => 'Master Salesman',
'class' => 'sidebar-mini',
'activePage' => 'data_sales',
])

@section('content')

{{-- ============================================================
     HEADER PANEL
     ============================================================ --}}
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ============================================================
                 ALERT SUCCESS
                 ============================================================ --}}
            @if (session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert"
                style="background: rgba(41,177,74,0.2); border: 1px solid #29b14a; color: #155724; border-radius: 12px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#155724;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_check"></span>
                <span data-notify="message">{{ session('success') }}</span>
            </div>
            @endif


            {{-- ============================================================
                 CARD DATA SALESMAN
                 ============================================================ --}}
            <div class="card" style="border-radius: 20px;">

                {{-- ===================== CARD HEADER ===================== --}}
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Salesman') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- ===================== FORM PENCARIAN ===================== --}}
                        <form action="{{ route('salesman.data') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari salesman..." value="{{ request('search') }}">
                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>

                        {{-- ===================== TAMPILKAN SEMUA ===================== --}}
                        @if (request()->has('all'))
                        <a href="{{ route('salesman.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('salesman.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ===================== EXPORT BUTTON ===================== --}}
                        @if (checkAccess('Master', 'Master Salesman', 'print'))
                        <a href="{{ route('salesman.exportExcel') }}" class="btn btn-success btn-round mr-2"
                            style="margin-top:10px;background:#29b14a;border:none;">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('salesman.exportPdf') }}" class="btn btn-danger btn-round"
                            style="margin-top:10px;background:#e74c3c;border:none;">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>


                {{-- ===================== CARD BODY ===================== --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">

                    {{-- ===================== TABEL DATA ===================== --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color:#333; width:100%;"
                            style="color:#333; table-layout:fixed; width:100%;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="text-center" style="width:5%;">#</th>

                                    <th style="width:20%;">
                                        <a href="{{ route('salesman.data', array_merge(request()->query(), [
                    'sort_by' => 'ID_SALESMAN',
                    'sort_order' => (request('sort_by') === 'ID_SALESMAN' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                ])) }}" class="text-success text-decoration-none">
                                            ID Salesman
                                        </a>
                                    </th>

                                    <th style="width:20%;">
                                        <a href="{{ route('salesman.data', array_merge(request()->query(), [
                    'sort_by' => 'ID_DISTRIBUTOR',
                    'sort_order' => (request('sort_by') === 'ID_DISTRIBUTOR' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                ])) }}" class="text-success text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>

                                    <th style="width:35%;">
                                        <a href="{{ route('salesman.data', array_merge(request()->query(), [
                    'sort_by' => 'NAMA_SALESMAN',
                    'sort_order' => (request('sort_by') === 'NAMA_SALESMAN' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                ])) }}" class="text-success text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    <th class="text-center" style="width:150px;">
                                        Total Kecurangan
                                    </th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($salesman as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration + ($salesman->firstItem() - 1) }}
                                    </td>

                                    <td>{{ $item->ID_SALESMAN }}</td>
                                    <td>{{ $item->ID_DISTRIBUTOR }}</td>
                                    <td>{{ $item->NAMA_SALESMAN }}</td>

                                    <td class="text-center">
                                        <span class="badge-soft text-danger font-weight-bold" style="cursor:pointer;"
                                            onclick="showKecurangan('{{ $item->ID_SALESMAN }}')">
                                            {{ $item->total_kecurangan }}
                                        </span>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">
                                        Belum ada data salesman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                    {{-- ===================== PAGINATION ===================== --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $salesman->links('pagination::bootstrap-4') }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>


{{-- ============================================================
     MODAL KEKURANGAN
     ============================================================ --}}
<div class="modal fade" id="modalKecurangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:750px;">
        <div class="modal-content border-0"
            style="background:rgba(255,255,255,0.97); border-radius:15px; box-shadow:0 4px 25px rgba(0,0,0,0.3);">

            <div class="modal-header" style="border-bottom:none;">
                <h5 class="modal-title text-danger" style="font-weight:600;">Daftar Kecurangan</h5>
            </div>

            {{-- ===================== MODAL BODY ===================== --}}
            <div class="modal-body kecurangan-body" style="font-size:15px; color:#333;">

                <div id="kecuranganTotal"></div>
                {{-- Tabel Scroll --}}
                <div class="table-wrapper-fixed table-responsive">
                    <table class="table table-bordered table-striped"
                        style="background:white; border-radius:10px; overflow:hidden;">
                        <thead style="background:#e74c3c; color:white;">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Jenis Sanksi</th>
                                <th class="text-center">Keterangan Sanksi</th>
                                <th class="text-center">Nilai Sanksi</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="tableKecurangan"></tbody>
                    </table>
                </div>

                {{-- Total + Pagination --}}
                <div class="kecurangan-bottom mt-2">
                    <div id="kecuranganPagination"></div>
                </div>

            </div>
            <div class="modal-footer" style="border-top:none;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
@endsection



@push ('styles')
{{-- ========== LOAD CSS ========== --}}
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<style>
/* ============================================================
   FORM STATE (INVALID & FOCUS)
   - Mengatur warna border saat input invalid / focus
   ============================================================ */
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
    /* abu default */
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* warna hijau fokus */
}


/* ============================================================
   GLOBAL BACKGROUND (BODY, WRAPPER, MAIN PANEL)
   - Background gradient utama dashboard
   ============================================================ */
body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
    /* smooth scroll */
}


/* ============================================================
   PANEL HEADER & CONTENT TRANSPARENT
   ============================================================ */
.panel-header-sps {
    background: transparent !important;
    box-shadow: none !important;
}

.content {
    background: transparent !important;
}


/* ============================================================
   CUSTOM NAVBAR (MIRROR GRADIENT HEADER)
   ============================================================ */
.navbar-soft {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%) !important;
    border: none !important;
    box-shadow: none !important;

    /* Ukuran navbar */
    height: 95px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;

    /* Vertical center */
    display: flex !important;
    align-items: center !important;

    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;

    transition: none !important;
    /* matikan transisi container */
}

/* Brand logo teks */
.navbar-soft .navbar-brand {
    color: #ffffff !important;
    font-size: 22px !important;
    font-weight: 700;
}

/* Icon di kanan navbar */
.navbar-soft .nav-link i {
    color: #ffffff !important;
    font-size: 22px;
    transition: .2s ease;
}

/* Hover icon navbar */
.navbar-soft .nav-link:hover i {
    color: #333 !important;
}

/* Hover smooth */
.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important;
}


/* ============================================================
   SOFT UI PAGINATION (MODERN STYLE)
   ============================================================ */
.pagination {
    display: flex;
    gap: 6px;
}

.pagination .page-item {
    transition: 0.25s ease;
}

/* Default state */
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

/* Disabled pagination */
.pagination .page-item.disabled .page-link {
    background: #f1f1f1 !important;
    color: #b4b4b4 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
}

/* Disabled hover tetap sama */
.pagination .page-item.disabled .page-link:hover {
    background: #f1f1f1 !important;
    color: #b4b4b4 !important;
    transform: none !important;
    box-shadow: none !important;
}


/* ============================================================
   GLOBAL BUTTON STYLE (SOFT UI BUTTONS)
   ============================================================ */
.btn {
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 8px 18px !important;
    transition: all 0.25s ease-in-out !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
}

/* Hover efek */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
}

/* ================= BUTTON COLOR VARIANTS ================ */

/* Hijau */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

/* Merah */
.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #ff6b5c) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d84333, #fa5f50) !important;
}

/* Abu */
.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #b0b3b7, #c9cbce) !important;
}

/* Kuning */
.btn-warning {
    background: linear-gradient(135deg, #eee733, #faf26b) !important;
    color: #333 !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e2db2e, #f0eb63) !important;
}

/* Round button */
.btn-round {
    border-radius: 30px !important;
}

/* Icon di tombol */
.btn i {
    font-size: 15px;
    margin-right: 6px;
}

/* Disabled state */
.btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}


/* ============================================================
   SEARCH BAR (INPUT + BUTTON)
   ============================================================ */

/* Wrapper pencarian */
.action-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

/* Group input + tombol */
.search-group {
    display: flex;
    align-items: center;
    width: 260px;
    min-width: 260px;
}

/* Input pencarian */
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

/* Tombol search */
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


/* ============================================================
   MODAL KEKURANGAN (RESPONSIVE + SMOOTH RESIZE)
   ============================================================ */

/* Modal responsive: tinggi mengikuti isi, tapi tetap dibatasi layar */
#modalKecurangan .modal-content {
    height: auto !important;
    max-height: 90vh;
    /* batas maksimum tinggi */
    display: flex;
    flex-direction: column;
    transition: max-height .25s ease, height .25s ease;
    /* smooth resize */
}

/* Body fleksibel — membungkus tabel & pagination */
#modalKecurangan .modal-body {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    /* hindari body ikut scroll */
    padding-bottom: 0 !important;
}

/* Area tabel scroll — hanya tabel yang scroll, bukan seluruh modal */
#modalKecurangan .table-wrapper-fixed {
    flex: 1;
    max-height: 55vh;
    /* batas maksimal tinggi tabel */
    min-height: 120px;
    /* tetap rapi walau data sedikit */
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    transition: max-height .25s ease;
    /* smooth resize */
}

/* Total & Pagination — fixed di bawah modal, tidak ikut scroll */
#kecuranganBottom {
    flex-shrink: 0;
    margin-top: 10px;
}

#kecuranganPagination {
    flex-shrink: 0;
    margin-top: 10px;
    text-align: center;
}

/* ============================================
   RESPONSIVE TABLE – Header tidak pecah
============================================ */
.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}

.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
}

/* Header & cell tidak boleh turun baris */
table.table th,
table.table td {
    white-space: nowrap !important;
    padding-left: 15px !important;
    padding-right: 15px !important;
}

/* Header rata kiri */
table.table th {
    text-align: left !important;
}

/* Kolom nomor */
table.table th:first-child,
table.table td:first-child {
    text-align: center !important;
    width: 40px !important;
    padding-left: 0 !important;
}

/* Kolom total kecurangan */
table.table th:last-child,
table.table td:last-child {
    text-align: center !important;
    width: 150px !important;
    padding-left: 0 !important;
}

/* Mobile font */
@media (max-width: 576px) {
    table.table {
        font-size: 12px !important;
    }
}
</style>
@endpush

@push('js')
{{-- ========== LOAD JS ========== --}}
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>

<script>
/* ========== OPEN MODAL & LOAD DATA ========== */
function showKecurangan(idSales, pageUrl = null) {

    /* ========== LOADING STATE ========== */
    $("#tableKecurangan").html(`
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Loading...</td>
                </tr>
            `);

    $("#kecuranganPagination").html("");
    $("#modalKecurangan").modal('show');

    /* ========== URL HANDLING ========== */
    let url = pageUrl ?? ("{{ url('/salesman/get-kecurangan') }}/" + idSales);

    /* ========== AJAX FETCH ========== */
    $.get(url, function(res) {

        /* ========== RENDER TABLE ROWS ========== */
        let indexStart = res.first ?? 1;
        let rows = "";

        if (!res.data || res.data.length === 0) {
            rows = `
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                        </tr>
                    `;
        } else {
            res.data.forEach((row, i) => {
                rows += `
                            <tr>
                                <td class="text-center">${indexStart + i}</td>
                                <td>${row.JENIS_SANKSI ?? '-'}</td>
                                <td>${row.KETERANGAN_SANKSI ?? '-'}</td>
                                <td> Rp ${new Intl.NumberFormat("id-ID").format(row.NILAI_SANKSI ?? 0)}</td>
                                <td>${row.TANGGAL}</td>
                            </tr>
                        `;
            });
        }

        $("#tableKecurangan").html(rows);

        /* ========== RENDER TOTAL ========== */
        let totalHTML = `
                    <div class="mb-2 text-right">
                        <p class="text-danger font-weight-bold" style="font-size:14px;">
                            Total Nilai Sanksi: Rp ${new Intl.NumberFormat("id-ID").format(res.total_nilai ?? 0)}
                        </p>
                    </div>
                `;

        $("#kecuranganTotal").html(totalHTML);

        /* ========== RENDER PAGINATION ========== */
        $("#kecuranganPagination").append(res.pagination);

        /* ========== PAGINATION AJAX HANDLER ========== */
        $("#kecuranganPagination a.page-link").click(function(e) {
            e.preventDefault();
            showKecurangan(idSales, $(this).attr("href"));
        });

    }).fail(function(xhr) {

        /* ========== ERROR STATE ========== */
        console.log("AJAX Error:", xhr.responseText);

        $("#tableKecurangan").html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger py-3">Gagal memuat data</td>
                    </tr>
                `);
    });

}
</script>
@endpush