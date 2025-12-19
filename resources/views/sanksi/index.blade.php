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

            {{-- ====================================================
                 ALERT SUCCESS (Notifikasi Berhasil)
                 Muncul ketika ada session 'success'
            ===================================================== --}}
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
                {{-- Tombol Tutup --}}
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


            {{-- ====================================================
                 CARD UTAMA: LIST MASTER SANKSI
            ===================================================== --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Sanksi') }}</h4>

                    {{-- ===========================
                         ACTIONS & SEARCH BAR
                    ============================ --}}
                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- =============================
                             FORM SEARCH (PENCARIAN)
                        ============================== --}}
                        <form action="{{ route('sanksi.index') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari sanksi..." value="{{ request('search') }}">

                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>

                        {{-- ======================================================
                             TOMBOL UNTUK TAMPILKAN SEMUA / PAGINASI
                        ======================================================= --}}
                        @if (request()->has('all'))
                        {{-- Mode TAMBAH: kembali ke paginasi --}}
                        <a href="{{ route('sanksi.index', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2" style="background:#eee733;color:#000;border:none;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        {{-- Mode PAGINASI → tampilkan semua --}}
                        <a href="{{ route('sanksi.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2" style="background:#29b14a;border:none;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ======================================================
                             TOMBOL TAMBAH MASTER SANKSI (Butuh Akses)
                        ======================================================= --}}
                        @if (checkAccess('Master', 'Master Sanksi', 'create'))
                        <a href="{{ route('sanksi.create') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none;" title="Tambah Sanksi">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif

                    </div>
                </div>

                {{-- ====================================================
                     TABLE WRAPPER
                ===================================================== --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0"
                            style="color:#333; table-layout:fixed; width:100%;">

                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="col-no text-center">#</th>

                                    {{-- JENIS --}}
                                    <th class="col-name">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                'sort_by' => 'jenis',
                'sort_order' =>
                    (request('sort_by') === 'jenis' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Jenis Sanksi
                                        </a>
                                    </th>

                                    {{-- KETERANGAN --}}
                                    <th class="col-desc">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                'sort_by' => 'keterangan',
                'sort_order' =>
                    (request('sort_by') === 'keterangan' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Keterangan
                                        </a>
                                    </th>

                                    {{-- NILAI --}}
                                    <th class="col-value">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                'sort_by' => 'nilai',
                'sort_order' =>
                    (request('sort_by') === 'nilai' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nilai
                                        </a>
                                    </th>

                                    {{-- AKSI --}}
                                    <th class="col-action text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sanksi as $item)
                                <tr>
                                    {{-- NOMOR --}}
                                    <td class="text-center">
                                        {{ $loop->iteration + (method_exists($sanksi, 'firstItem') ? $sanksi->firstItem() - 1 : 0) }}
                                    </td>

                                    {{-- JENIS --}}
                                    <td>{{ $item->JENIS }}</td>

                                    {{-- KETERANGAN (truncate rapi sama dengan table ASS) --}}
                                    <td class="text-truncate" style="max-width:280px;" title="{{ $item->KETERANGAN }}">
                                        {{ $item->KETERANGAN }}
                                    </td>

                                    {{-- NILAI --}}
                                    <td>
                                        Rp {{ number_format($item->NILAI, 0, ',', '.') }}
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">

                                        @if (checkAccess('Master', 'Master Sanksi', 'edit'))
                                        <a href="{{ route('sanksi.edit', $item->ID) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        @if (checkAccess('Master', 'Master Sanksi', 'delete'))
                                        <button type="button"
                                            class="btn btn-danger btn-icon btn-sm btn-round btn-confirm"
                                            data-action="delete" data-url="{{ route('sanksi.destroy', $item->ID) }}"
                                            title="Hapus" style="background:#e74c3c;border:none;">
                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                        </button>
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


                    {{-- PAGINATION (disembunyikan ketika 'all=true') --}}
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

{{-- ============================================================
     MODAL KONFIRMASI DELETE (Dipicu oleh tombol .btn-confirm)
=============================================================== --}}
<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:420px;">
        <div class="modal-content" style="
            background:white;
            border-radius:18px;
            box-shadow:0 6px 20px rgba(0,0,0,0.25);
        ">
            <div class="modal-body text-center p-4">

                {{-- ICON WARNING --}}
                <i id="confirmIcon" class="now-ui-icons ui-1_alert" style="font-size:48px;color:#e74c3c;"></i>

                {{-- TITLE & MESSAGE --}}
                <h4 class="mt-3 mb-2" id="confirmTitle" style="font-weight:700;">Konfirmasi</h4>
                <p class="text-muted" id="confirmMessage" style="font-size:15px;"></p>

                {{-- BUTTON Aksi --}}
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
<link rel="stylesheet" href="{{ asset('assets/css/index-sanksi.css') }}">

@endpush

@push('js')
<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="{{ asset('assets/js/index-sanksi.js') }}"></script>
@endpush