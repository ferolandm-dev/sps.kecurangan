@extends('layouts.app', [
'namePage' => 'Transaksi Kasus',
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
                    <h4 class="card-title mb-0 text-dark">{{ __('Transaksi Kasus') }}</h4>

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
                        @if (checkAccess('Transaksi', 'Transaksi Kasus', 'create'))
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
                                    <th class="col-no text-center">#</th>

                                    {{-- ID SALES --}}
                                    <th class="col-id-sales text-center">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'ID_SALES',
                'sort_order' => (request('sort_by') === 'ID_SALES' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            ID Sales
                                        </a>
                                    </th>

                                    {{-- NAMA SALES --}}
                                    <th class="col-nama-sales">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'NAMA_SALESMAN',
                'sort_order' => (request('sort_by') === 'NAMA_SALESMAN' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama Sales
                                        </a>
                                    </th>

                                    {{-- DISTRIBUTOR --}}
                                    <th class="col-distributor">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'DISTRIBUTOR',
                'sort_order' => (request('sort_by') === 'DISTRIBUTOR' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Distributor
                                        </a>
                                    </th>

                                    {{-- NAMA ASS --}}
                                    <th class="col-nama-ass">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'NAMA_ASS',
                'sort_order' => (request('sort_by') === 'NAMA_ASS' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    {{-- JENIS SANKSI --}}
                                    <th class="col-jenis-sanksi">
                                        Jenis Sanksi
                                    </th>

                                    {{-- KETERANGAN SANKSI --}}
                                    <th class="col-ket-sanksi">
                                        Keterangan Sanksi
                                    </th>

                                    {{-- NILAI SANKSI --}}
                                    <th class="col-nilai-sanksi">
                                        Nilai Sanksi
                                    </th>

                                    {{-- TOKO --}}
                                    <th class="col-toko">
                                        Toko
                                    </th>

                                    {{-- KUNJUNGAN --}}
                                    <th class="col-kunjungan text-center">
                                        Kunjungan
                                    </th>

                                    {{-- TANGGAL KASUS --}}
                                    <th class="col-tanggal text-center">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'TANGGAL',
                'sort_order' => (request('sort_by') === 'TANGGAL' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Tanggal Kasus
                                        </a>
                                    </th>

                                    {{-- CREATED AT --}}
                                    <th class="col-created text-center">
                                        <a href="{{ route('kecurangan.index', array_merge(request()->query(), [
                'sort_by' => 'CREATED_AT',
                'sort_order' => (request('sort_by') === 'CREATED_AT' && request('sort_order') === 'asc') ? 'desc' : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Tanggal Buat
                                        </a>
                                    </th>

                                    {{-- KETERANGAN --}}
                                    <th class="col-keterangan text-center">
                                        Keterangan
                                    </th>

                                    {{-- KUARTAL --}}
                                    <th class="col-kuartal">
                                        Kuartal
                                    </th>

                                    {{-- AKSI --}}
                                    <th class="col-aksi text-center">
                                        Aksi
                                    </th>
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
                                            <button type="button"
                                                class="btn btn-success btn-icon btn-sm btn-round btn-confirm"
                                                data-action="validasi"
                                                data-url="{{ route('kecurangan.validasi', $item->ID) }}"
                                                title="Validasi Data" style="background:#29b14a;border:none;">
                                                <i class="now-ui-icons ui-1_check"></i>
                                            </button>
                                        </form>


                                        @if (checkAccess('Transaksi', 'Transaksi Kasus', 'edit'))
                                        <a href="{{ route('kecurangan.edit', $item->ID) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round" title="Edit Data"
                                            style="background:#f39c12;border:none;">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        @if (checkAccess('Transaksi', 'Transaksi Kasus', 'delete'))
                                        <form action="{{ route('kecurangan.destroy', $item->ID) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-icon btn-sm btn-round btn-confirm"
                                                data-action="delete"
                                                data-url="{{ route('kecurangan.destroy', $item->ID) }}"
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

{{-- ===================== MODAL KONFIRMASI ===================== --}}
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
<link rel="stylesheet" href="{{ asset('assets/css/index-kecurangan.css') }}">
@endpush

@push('js')
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="{{ asset('assets/js/index-kecurangan.js') }}"></script>
@endpush