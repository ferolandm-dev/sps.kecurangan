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
                        <table class="table table-hover align-middle text-nowrap mb-0" style="color:#333; width:100%;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th class="col-no text-center">#</th>

                                    <th class="col-id">
                                        <a href="{{ route('distributor.data', array_merge(request()->query(), [
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
                                        <a href="{{ route('distributor.data', array_merge(request()->query(), [
                'sort_by' => 'NAMA_DISTRIBUTOR',
                'sort_order' =>
                    (request('sort_by') === 'NAMA_DISTRIBUTOR' && request('sort_order') === 'asc')
                    ? 'desc'
                    : 'asc'
            ])) }}" class="text-success text-decoration-none">
                                            Nama Distributor
                                        </a>
                                    </th>

                                    <th class="col-action text-center">
                                        Total Salesman
                                    </th>
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
<link rel="stylesheet" href="{{ asset('assets/css/data-distributor.css') }}">

@endpush

@push('js')
<script>
window.DISTRIBUTOR_CONFIG = {
    salesmanUrl: "{{ url('/distributor/get-salesman') }}"
};
</script>

<script src="{{ asset('assets/js/sidebar-fix.js') }}"></script>
<script src="{{ asset('assets/js/ui-lock.js') }}"></script>
<script src="{{ asset('assets/js/data-distributor.js') }}"></script>
@endpush