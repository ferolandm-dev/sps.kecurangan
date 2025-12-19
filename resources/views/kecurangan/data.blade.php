@extends('layouts.app', [
'namePage' => 'Report Kasus',
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
                <div class="card-header d-flex justify-content-between align-items-start flex-wrap">

                    <h4 class="card-title mb-0 text-dark">{{ __('Report Kasus') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- 🔍 Form Pencarian --}}
                        <form action="{{ route('kecurangan.data') }}" method="GET" class="mr-2">
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

                        {{-- ✅ Export Excel & PDF --}}
                        @if (checkAccess('Report', 'Report Kasus', 'print'))

                        {{-- Tombol Export Excel (langsung download sesuai filter) --}}
                        <a href="{{ route('kecurangan.exportExcel', request()->query()) }}"
                            class="btn btn-success btn-round mr-2 d-flex align-items-center"
                            style="margin-top:10px;background:#29b14a;border:none;" title="Export Excel">

                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        {{-- Tombol Export PDF (langsung download sesuai filter) --}}
                        <a href="{{ route('kecurangan.exportPDF', request()->query()) }}"
                            class="btn btn-danger btn-round d-flex align-items-center"
                            style="margin-top:10px;background:#e74c3c;border:none;" title="Export PDF">

                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>

                        @endif
                    </div>
                    <div class="w-100"></div>
                    <div class="d-flex justify-content-end align-items-center flex-wrap" style="margin-top:10px;">

                        <!-- Filter -->
                        <button class="btn btn-success btn-round d-flex align-items-center" data-toggle="modal"
                            data-target="#modalFilter"
                            style="height:38px;background:#29b14a;border:none;margin-right:10px;">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1"></i> Filter
                        </button>

                        <!-- Reset -->
                        <a href="{{ route('kecurangan.data') }}"
                            class="btn btn-secondary btn-round d-flex align-items-center"
                            style="height:38px;margin-right:10px;">
                            <i class="now-ui-icons arrows-1_refresh-69 mr-1"></i> Reset
                        </a>

                    </div>
                </div>

                {{-- 📋 TABEL DATA --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 kecurangan-table" style="color:#333;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="col-no text-center">#</th>

                                    {{-- ID SALES --}}
                                    <th class="col-id-sales text-center">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'id_sales',
                'sort_order' => (request('sort_by') === 'id_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            ID Sales
                                        </a>
                                    </th>

                                    {{-- NAMA SALES --}}
                                    <th class="col-nama-sales">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'nama_sales',
                'sort_order' => (request('sort_by') === 'nama_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama Sales
                                        </a>
                                    </th>

                                    {{-- DISTRIBUTOR --}}
                                    <th class="col-distributor">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'distributor',
                'sort_order' => (request('sort_by') === 'distributor' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Distributor
                                        </a>
                                    </th>

                                    {{-- NAMA ASS --}}
                                    <th class="col-nama-ass">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'nama_ass',
                'sort_order' => (request('sort_by') === 'nama_ass' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    {{-- JENIS SANKSI --}}
                                    <th class="col-jenis-sanksi">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'jenis_sanksi',
                'sort_order' => (request('sort_by') === 'jenis_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Jenis Sanksi
                                        </a>
                                    </th>

                                    {{-- KETERANGAN SANKSI --}}
                                    <th class="col-ket-sanksi">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'keterangan_sanksi',
                'sort_order' => (request('sort_by') === 'keterangan_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Keterangan Sanksi
                                        </a>
                                    </th>

                                    {{-- NILAI SANKSI --}}
                                    <th class="col-nilai-sanksi">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'nilai_sanksi',
                'sort_order' => (request('sort_by') === 'nilai_sanksi' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nilai Sanksi
                                        </a>
                                    </th>

                                    {{-- TOKO --}}
                                    <th class="col-toko">Toko</th>

                                    {{-- KUNJUNGAN --}}
                                    <th class="col-kunjungan text-center">Kunjungan</th>

                                    {{-- TANGGAL --}}
                                    <th class="col-tanggal text-center">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'tanggal',
                'sort_order' => (request('sort_by') === 'tanggal' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Tanggal Kasus
                                        </a>
                                    </th>

                                    {{-- CREATED AT --}}
                                    <th class="col-created text-center">
                                        <a href="{{ route('kecurangan.data', array_merge(request()->query(), [
                'sort_by' => 'CREATED_AT',
                'sort_order' => (request('sort_by') === 'CREATED_AT' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Tanggal Buat
                                        </a>
                                    </th>

                                    {{-- KETERANGAN --}}
                                    <th class="col-keterangan text-center">Keterangan</th>

                                    {{-- KUARTAL --}}
                                    <th class="col-kuartal">Kuartal</th>

                                    {{-- AKSI --}}
                                    <th class="col-aksi text-center">Aksi</th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($kecurangan as $index => $item)
                                <tr style="vertical-align: top;">
                                    <td class="text-center">
                                        {{ $loop->iteration + (method_exists($kecurangan, 'firstItem') ? $kecurangan->firstItem() - 1 : 0) }}
                                    </td>
                                    <td class="text-center">{{ $item->ID_SALES }}</td>
                                    <td>{{ $item->nama_sales }}</td>
                                    <td>{{ $item->DISTRIBUTOR }}</td>
                                    <td>{{ $item->nama_ass }}</td>
                                    <td>{{ $item->JENIS_SANKSI }}</td>
                                    <td>{{ $item->KETERANGAN_SANKSI }}</td>
                                    <td>Rp {{ number_format($item->NILAI_SANKSI, 0, ',', '.') }}</td>
                                    <td>{{ $item->TOKO }}</td>
                                    <td class="text-center">{{ $item->KUNJUNGAN }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->TANGGAL)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($item->CREATED_AT)->format('d/m/Y') }}</td>

                                    <td class="text-center">
                                        @if ($item->KETERANGAN)
                                        <button class="btn btn-info btn-sm btn-round btn-lihat-keterangan"
                                            data-keterangan="{{ $item->KETERANGAN }}">
                                            <i class="now-ui-icons files_paper"></i>
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->KUARTAL }}</td>

                                    <td class="text-center" style="vertical-align: top;">
                                        <button class="btn btn-info btn-icon btn-sm btn-round btn-lihat-bukti"
                                            data-id="{{ $item->ID }}" title="Lihat Bukti"
                                            style="background:#17a2b8;border:none;">
                                            <i class="now-ui-icons media-1_album"></i>
                                        </button>
                                        @if($item->VALIDASI == 0)
                                        <form action="{{ route('kecurangan.validasi', $item->ID) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                        </form>
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
                <h5 class="modal-title" style="font-weight:600;">
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

{{-- Modal Filter --}}
<div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- modal-lg: lebih lebar --}}
        <div class="modal-content"
            style="border-radius:15px; box-shadow:0 4px 25px rgba(0,0,0,0.3); overflow:hidden; border:none !important;">

            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Filter Data</h5>
            </div>

            <form action="{{ route('kecurangan.data') }}" method="GET">
                <div class="modal-body">

                    <div class="row">

                        {{-- NAMA ASS --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-dark font-weight-bold">Nama ASS</label>
                                <select name="ass" class="form-control select2">
                                    <option value="">Semua ASS</option>
                                    @foreach ($assList as $ass)
                                    <option value="{{ $ass->ID_SALESMAN }}"
                                        {{ request('ass') == $ass->ID_SALESMAN ? 'selected' : '' }}>
                                        {{ $ass->ID_SALESMAN }} - {{ $ass->NAMA_SALESMAN }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- NAMA SALES --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-dark font-weight-bold">Nama Sales</label>
                                <select name="sales" class="form-control select2">
                                    <option value="">Semua Sales</option>
                                    @foreach ($sales as $row)
                                    <option value="{{ $row->id_sales }}"
                                        {{ request('sales') == $row->id_sales ? 'selected' : '' }}>
                                        {{ $row->id_sales }} - {{ $row->nama_sales }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- JENIS SANKSI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-dark font-weight-bold">Jenis Sanksi</label>
                                <select id="filter_jenis_sanksi_filter" name="jenis_sanksi"
                                    class="form-control select2">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisSanksi as $row)
                                    <option value="{{ $row }}" {{ request('jenis_sanksi') == $row ? 'selected' : '' }}>
                                        {{ $row }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- KETERANGAN SANKSI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-dark font-weight-bold">Keterangan Sanksi</label>
                                <select id="filter_keterangan_sanksi_filter" name="keterangan_sanksi"
                                    class="form-control select2">
                                    <option value="">Semua Keterangan</option>
                                    @if(request('jenis_sanksi'))
                                    @foreach(($keteranganSanksi ?? collect())->where('JENIS', request('jenis_sanksi'))
                                    as $ket)
                                    <option value="{{ $ket->KETERANGAN }}"
                                        {{ request('keterangan_sanksi') == $ket->KETERANGAN ? 'selected' : '' }}>
                                        {{ $ket->KETERANGAN }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- TANGGAL BUAT --}}
                        <div class="col-md-6">
                            <label class="text-dark font-weight-bold d-block">Tanggal Buat</label>

                            <div class="form-group mb-2">
                                <label class="text-dark small mb-1">Mulai</label>
                                <input type="text" name="created_start_date" class="form-control datepicker"
                                    placeholder="dd/mm/yyyy" value="{{ request('created_start_date') }}">
                            </div>

                            <div class="form-group">
                                <label class="text-dark small mb-1">Akhir</label>
                                <input type="text" name="created_end_date" class="form-control datepicker"
                                    placeholder="dd/mm/yyyy" value="{{ request('created_end_date') }}">
                            </div>
                        </div>

                        {{-- TANGGAL KEJADIAN --}}
                        <div class="col-md-6">
                            <label class="text-dark font-weight-bold d-block">Tanggal Kasus</label>

                            <div class="form-group mb-2">
                                <label class="text-dark small mb-1">Mulai</label>
                                <input type="text" name="start_date" class="form-control datepicker"
                                    placeholder="dd/mm/yyyy" value="{{ request('start_date') }}">
                            </div>

                            <div class="form-group">
                                <label class="text-dark small mb-1">Akhir</label>
                                <input type="text" name="end_date" class="form-control datepicker"
                                    placeholder="dd/mm/yyyy" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        {{-- VALIDASI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-dark font-weight-bold">Status Validasi</label>
                                <select name="validasi" class="form-control select2">
                                    <option value="">Semua</option>
                                    <option value="1" {{ request('validasi') === "1" ? 'selected' : '' }}>
                                        Sudah Validasi
                                    </option>
                                    <option value="0" {{ request('validasi') === "0" ? 'selected' : '' }}>
                                        Belum Validasi
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div> {{-- row end --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-round">Filter</button>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection

@push('styles')
{{-- ========== LOAD CSS ========== --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/sidebar-fix.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/ui-lock.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-focus-input.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-background-wrapper.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-header.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-navbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-btn.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-btn-variant.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-pagination.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-search-bar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-backdrop.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/global-table-stable.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/modal-navigation-buttons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/data-kecurangan.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/calendar-ui.css') }}">
@endpush

@push('js')
<script>
window.KECURANGAN_REPORT_CONFIG = {
    keteranganByJenisUrl: "{{ url('/kecurangan/get-keterangan') }}",
    buktiByIdUrl: "{{ url('/kecurangan') }}"
};
</script>

<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/data-kecurangan.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
@endpush