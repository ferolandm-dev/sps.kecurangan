@extends('layouts.app', [
'namePage' => 'Master Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'data_kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style=" background: linear-gradient(135deg, #29b14a 0%, #34d058 100%); color: #fff;
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


            {{-- ✅ CARD DATA KECURANGAN --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Kecurangan') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- 🔍 Form Pencarian --}}
                        <form action="{{ route('kecurangan.index') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari kecurangan..." value="{{ request('search') }}">

                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>


                        {{-- 🔄 Tombol Tampilkan Semua / Halaman --}}
                        @if (request()->has('all'))
                        <a href="{{ request()->fullUrlWithoutQuery('all') }}" class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;"
                            title="Tampilkan Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ request()->fullUrlWithQuery(['all' => true]) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;" title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah Asisten Manager (akses: create) --}}
                        @if (checkAccess('Master', 'Master Kecurangan', 'create'))
                        <a href="{{ route('kecurangan.create') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none;" title="Tambah Kecurangan">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- 📋 TABEL DATA --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 kecurangan-table" style="color:#333;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="col-no text-center" style="width:40px;">#</th>
                                    <th class="col-id-sales" style="width:120px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'id_sales',
                        'sort_order' => (request('sort_by') === 'id_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            ID Sales
                                        </a>
                                    </th>

                                    <th class="col-nama-sales" style="width:200px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'nama_sales',
                        'sort_order' => (request('sort_by') === 'nama_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Nama Sales
                                        </a>
                                    </th>

                                    <th class="col-distributor" style="width:350px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'distributor',
                        'sort_order' => (request('sort_by') === 'distributor' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Distributor
                                        </a>
                                    </th>

                                    <th class="col-nama-ass" style="width:300px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'nama_asisten_manager',
                        'sort_order' => (request('sort_by') === 'nama_asisten_manager' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    <th class="col-jenis-sanksi" style="width:150px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'jenis_sanksi',
                        'sort_order' => (request('sort_by') === 'jenis_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Jenis Sanksi
                                        </a>
                                    </th>

                                    <th class="col-ket-sanksi" style="width:320px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'keterangan_sanksi',
                        'sort_order' => (request('sort_by') === 'keterangan_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Keterangan Sanksi
                                        </a>
                                    </th>

                                    <th class="col-nilai-sanksi" style="width:160px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'nilai_sanksi',
                        'sort_order' => (request('sort_by') === 'nilai_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Nilai Sanksi
                                        </a>
                                    </th>

                                    <th class="col-toko" style="width:200px;">Toko</th>
                                    <th class="col-kunjungan text-center" style="width:150px;">Kunjungan</th>

                                    <th class="col-tanggal" style="width:110px;">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                        'sort_by' => 'tanggal',
                        'sort_order' => (request('sort_by') === 'tanggal' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Tanggal
                                        </a>
                                    </th>

                                    <th class="col-keterangan text-center" style="width:180px;">Keterangan</th>
                                    <th class="col-kuartal" style="width:110px;">Kuartal</th>
                                    <th class="col-aksi text-center" style="width:200px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($kecurangan as $index => $item)
                                <tr style="vertical-align: top;">
                                    <td class="text-center">
                                        {{ $loop->iteration + (method_exists($kecurangan, 'firstItem') ? $kecurangan->firstItem() - 1 : 0) }}
                                    </td>
                                    <td>{{ $item->id_sales }}</td>
                                    <td>{{ $item->nama_sales }}</td>
                                    <td>{{ $item->distributor }}</td>
                                    <td>{{ $item->nama_asisten_manager }}</td>
                                    <td>{{ $item->jenis_sanksi }}</td>
                                    <td>{{ $item->keterangan_sanksi }}</td>
                                    <td>Rp {{ number_format($item->nilai_sanksi, 0, ',', '.') }}</td>
                                    <td>{{ $item->toko }}</td>
                                    <td class="text-center">{{ $item->kunjungan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>

                                    <td class="text-center">
                                        @if ($item->keterangan)
                                        <button class="btn btn-info btn-sm btn-round btn-lihat-keterangan"
                                            data-keterangan="{{ $item->keterangan }}">
                                            <i class="now-ui-icons files_paper"></i>
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->kuartal }}</td>

                                    <td class="text-center" style="vertical-align: top;">
                                        <button class="btn btn-info btn-icon btn-sm btn-round btn-lihat-bukti"
                                            data-id="{{ $item->id }}" title="Lihat Bukti"
                                            style="background:#17a2b8;border:none;">
                                            <i class="now-ui-icons media-1_album"></i>
                                        </button>
                                        @if($item->validasi == 0)
                                        <form action="{{ route('kecurangan.validasi', $item->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-icon btn-sm btn-round"
                                                title="Validasi Data" onclick="return confirm('Validasi data ini?');"
                                                style="background:#29b14a;border:none;">
                                                <i class="now-ui-icons ui-1_check"></i>
                                            </button>
                                        </form>

                                        @if (checkAccess('Master', 'Master Kecurangan', 'edit'))
                                        <a href="{{ route('kecurangan.edit', $item->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round" title="Edit Data"
                                            style="background:#f39c12;border:none;">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        @if (checkAccess('Master', 'Master Kecurangan', 'delete'))
                                        <form action="{{ route('kecurangan.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round"
                                                title="Hapus Data" style="background:#e74c3c;border:none;">
                                                <i class="now-ui-icons ui-1_simple-remove"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @else
                                        <span class="badge"
                                            style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">
                                            Tervalidasi
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="15" class="text-center text-muted">Belum ada data kecurangan</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>


                    {{-- 📄 Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $kecurangan->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===================== MODAL LIHAT KETERANGAN ===================== --}}
<div class="modal fade" id="modalKeterangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:700px;">
        <div class="modal-content border-0" style="background:rgba(255,255,255,0.97);
            border-radius:15px;
            box-shadow:0 4px 25px rgba(0,0,0,0.3);">
            <div class="modal-header" style="border-bottom:none;">
                <h5 class="modal-title text-success" style="font-weight:600;">
                    <i class="now-ui-icons"></i> Keterangan
                </h5>
            </div>
            <div class="modal-body" style="font-size:15px; color:#333; text-align:justify; line-height:1.6em;">
                <p id="isiKeterangan"></p>
            </div>
            <div class="modal-footer" style="border-top:none;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL LIHAT BUKTI ===================== --}}
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top:-10px;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:1000px;">
        <div class="modal-content border-0" style="background:rgba(255,255,255,0.97);
            border-radius:15px;
            box-shadow:0 4px 25px rgba(0,0,0,0.3);
            overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom:none;">
                <h5 class="modal-title" style="font-weight:600;">
                    <i class="now-ui-icons"></i> Bukti Kecurangan
                </h5>
            </div>

            {{-- Isi Modal --}}
            <div class="modal-body d-flex justify-content-center align-items-center"
                style="height:75vh; position:relative;">
                {{-- Gambar utama --}}
                <img id="modalImage" src="" alt="Preview"
                    style="max-width:90%; max-height:90%; object-fit:contain; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition:0.3s;">

                {{-- Tombol Navigasi --}}
                <button type="button" id="modalPrev" class="btn btn-link" style="
    position:absolute;
    left:0;
    top:0;
    height:100%;
    width:120px;
    display:flex;
    align-items:center;
    justify-content:flex-start;
    padding-left:25px;
    font-size:42px;
    color:#333;
    text-decoration:none;
    opacity:0.7;
    background:transparent;
    border:none;
">
                    ‹
                </button>
                <button type="button" id="modalNext" class="btn btn-link" style="
    position:absolute;
    right:0;
    top:0;
    height:100%;
    width:120px;
    display:flex;
    align-items:center;
    justify-content:flex-end;
    padding-right:25px;
    font-size:42px;
    color:#333;
    text-decoration:none;
    opacity:0.7;
    background:transparent;
    border:none;
">
                    ›
                </button>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top:none;justify-content:center;">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
<style>
.kecurangan-table {
    table-layout: fixed !important;
    width: 100%;
}

/* HEADER TIDAK TERPOTONG & BOLEH BARIS 2 */
.kecurangan-table thead th {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
    vertical-align: middle;
}

/* BODY TETAP RAPI */
.kecurangan-table tbody td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* scroll aman */
.table-responsive {
    overflow-x: auto;
}

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

/* Pastikan ditempatkan terakhir agar menimpa Bootstrap */
#modalPrev,
#modalNext {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    -webkit-tap-highlight-color: transparent !important;
    color: #333 !important;
    /* sesuaikan warna */
    text-decoration: none !important;
}

/* Hilangkan efek hover / fokus / aktif sepenuhnya */
#modalPrev:hover,
#modalNext:hover,
#modalPrev:focus,
#modalNext:focus,
#modalPrev:active,
#modalNext:active,
#modalPrev:focus-visible,
#modalNext:focus-visible {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
    opacity: 0.6 !important;
    /* atur sesuai kebutuhan, atau 1 */
    transform: none !important;
}

/* Khusus untuk tombol bootstrap .btn-link yang mungkin menambahkan inner focus di Firefox */
#modalPrev::-moz-focus-inner,
#modalNext::-moz-focus-inner {
    border: 0 !important;
    padding: 0 !important;
}

/* Jika masih muncul garis biru di Chrome pada focus, override ring color */
#modalPrev:focus,
#modalNext:focus {
    box-shadow: 0 0 0 0 transparent !important;
}

/* OPTIONAL: jika tetap ada style dari .btn-link atau .btn, paksa prioritas lebih tinggi */
button#modalPrev.btn,
button#modalNext.btn {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {

    let fotoList = [];
    let currentIndex = 0;

    // === MODAL LIHAT BUKTI ===
    $('.btn-lihat-bukti').on('click', function() {
        const id = $(this).data('id');
        fotoList = [];
        currentIndex = 0;

        $('#modalBukti').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });

        $.ajax({
            url: `/kecurangan/${id}/bukti`,
            method: 'GET',
            beforeSend: function() {
                $('#modalImage').attr('src', '').attr('alt', 'Memuat...');
            },
            success: function(response) {
                if (!response.length) {
                    $('#modalImage').attr('alt', 'Tidak ada foto.');
                    return;
                }

                fotoList = response.map(f => f.url);
                showModalImage(currentIndex);
            },
            error: function() {
                $('#modalImage').attr('alt', 'Gagal memuat foto.');
            }
        });
    });

    function showModalImage(index) {
        if (!fotoList.length) return;

        $('#modalImage').addClass('fade-out');
        setTimeout(() => {
            $('#modalImage')
                .attr('src', fotoList[index])
                .removeClass('fade-out');
        }, 150);
    }

    $('#modalNext').on('click', function() {
        if (!fotoList.length) return;
        currentIndex = (currentIndex + 1) % fotoList.length;
        showModalImage(currentIndex);
    });

    $('#modalPrev').on('click', function() {
        if (!fotoList.length) return;
        currentIndex = (currentIndex - 1 + fotoList.length) % fotoList.length;
        showModalImage(currentIndex);
    });

    // keyboard
    $(document).on('keydown', function(e) {
        if (!$('#modalBukti').hasClass('show')) return;
        if (e.key === 'Escape') $('#modalBukti').modal('hide');
        else if (e.key === 'ArrowRight') $('#modalNext').trigger('click');
        else if (e.key === 'ArrowLeft') $('#modalPrev').trigger('click');
    });

    // Lock scroll saat modal buka
    $('#modalBukti').on('shown.bs.modal', function() {
        $('body').css('overflow', 'hidden');

        $('#modalBukti').css('overflow', 'hidden');
        $('#modalBukti .modal-body').css({
            'overflow': 'hidden',
            'touch-action': 'none'
        });

        $(document).on('touchmove.modalBlock', function(e) {
            if ($('#modalBukti').hasClass('show') &&
                $(e.target).closest('#modalBukti').length) {
                e.preventDefault();
            }
        });
    });

    // Reset setelah modal tutup
    $('#modalBukti').on('hidden.bs.modal', function() {
        $('#modalImage').attr('src', '');
        fotoList = [];
        currentIndex = 0;

        $('body').css('overflow', 'auto');
        $('#modalBukti, #modalBukti .modal-body').css({
            'overflow': '',
            'touch-action': ''
        });

        $(document).off('touchmove.modalBlock');
    });

    // === MODAL KETERANGAN ===
    $('.btn-lihat-keterangan').on('click', function() {
        const isi = $(this).data('keterangan');
        $('#isiKeterangan').text(isi);
        $('#modalKeterangan').modal('show');
    });

});
</script>
@endpush