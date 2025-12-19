@extends('layouts.app', [
'namePage' => 'Master ASS',
'class' => 'sidebar-mini',
'activePage' => 'data_ass',
])

@section('content')

{{-- HEADER --}}
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- SUCCESS ALERT --}}
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


            {{-- CARD --}}
            <div class="card" style="border-radius: 20px;">

                {{-- CARD HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master ASS') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- SEARCH --}}
                        <form action="{{ route('ass.data') }}" method="GET" class="mr-2">
                            <div class="search-group">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari ASS..." value="{{ request('search') }}">
                                <button class="btn search-btn" type="submit">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                </button>
                            </div>
                        </form>

                        {{-- SHOW ALL --}}
                        @if (request()->has('all'))
                        <a href="{{ route('ass.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('ass.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- EXPORT --}}
                        @if (checkAccess('Master', 'Master ASS', 'print'))
                        <a href="{{ route('ass.exportExcel') }}" class="btn btn-success btn-round mr-2"
                            style="margin-top:10px;background:#29b14a;border:none;">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('ass.exportPdf') }}" class="btn btn-danger btn-round"
                            style="margin-top:10px;background:#e74c3c;border:none;">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>


                {{-- ===================== CARD BODY ===================== --}}
                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color:#333; width:100%;"
                            style="color:#333; table-layout:fixed; width:100%;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="col-no text-center">#</th>

                                    <th class="col-id">
                                        <a href="{{ route('ass.data', array_merge(request()->query(), [
                'sort_by' => 'ID_SALESMAN',
                'sort_order' =>
                    (request('sort_by') === 'ID_SALESMAN' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            ID ASS
                                        </a>
                                    </th>

                                    <th class="col-id">
                                        <a href="{{ route('ass.data', array_merge(request()->query(), [
                'sort_by' => 'ID_DISTRIBUTOR',
                'sort_order' =>
                    (request('sort_by') === 'ID_DISTRIBUTOR' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>

                                    <th class="col-name">
                                        <a href="{{ route('ass.data', array_merge(request()->query(), [
                'sort_by' => 'NAMA_SALESMAN',
                'sort_order' =>
                    (request('sort_by') === 'NAMA_SALESMAN' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    <th class="col-action text-center">
                                        Total Kecurangan
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($ass as $item)
                                <tr>
                                    <td class="text-center">
                                        @if(method_exists($ass, 'firstItem'))
                                        {{ $loop->iteration + ($ass->firstItem() - 1) }}
                                        @else
                                        {{ $loop->iteration }}
                                        @endif
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
                                        Belum ada data ASS
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $ass->links('pagination::bootstrap-4') }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>


{{-- MODAL --}}
<div class="modal fade" id="modalKecurangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:750px;">
        <div class="modal-content border-0"
            style="background:rgba(255,255,255,0.97); border-radius:15px; box-shadow:0 4px 25px rgba(0,0,0,0.3);">

            <div class="modal-header" style="border-bottom:none;">
                <h5 class="modal-title text-danger" style="font-weight:600;">Daftar Kecurangan (ASS)</h5>
            </div>

            <div class="modal-body kecurangan-body" style="font-size:15px; color:#333;">
                <div id="kecuranganTotal"></div>

                <div class="table-wrapper-fixed table-responsive">
                    <table class="table table-bordered table-striped"
                        style="background:white; border-radius:10px; overflow:hidden;">
                        <thead style="background:#e74c3c; color:white;">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Jenis Sanksi</th>
                                <th class="text-center">Keterangan Sanksi</th>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="tableKecurangan"></tbody>
                    </table>
                </div>

                <div id="kecuranganPagination" class="mt-2 text-center"></div>

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
<link rel="stylesheet" href="{{ asset('assets/css/modal-kecurangan.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/data-ass.css') }}">

<style>

</style>
@endpush

@push('js')
<script>
window.ASS_CONFIG = {
    kecuranganUrl: "{{ url('/ass/get-kecurangan') }}"
};
</script>

<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="{{ asset('assets/js/data-ass.js') }}"></script>
@endpush