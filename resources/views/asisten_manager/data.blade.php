@extends('layouts.app', [
'namePage' => 'Data Asisten Manager',
'class' => 'sidebar-mini',
'activePage' => 'data_asisten_managers',
])

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_check"></span>
                <span data-notify="message">{{ session('success') }}</span>
            </div>
            @endif

            {{-- CARD DATA ASISTEN MANAGER --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0">{{ __('Data Asisten Manager') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('asisten_manager.data') }}" method="GET"
                            class="d-flex align-items-center mr-2" style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari asisten manager..." value="{{ request('search') }}"
                                    style="height:38px;border-radius:30px 0 0 30px;padding-left:15px;margin-top:10px;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-round" type="submit"
                                        style="height:38px;border-radius:0 30px 30px 0;">
                                        <i class="now-ui-icons ui-1_zoom-bold"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Tombol Tampilkan Semua / Paginate --}}
                        @if (request()->has('all'))
                        <a href="{{ route('asisten_manager.data', request()->except('all')) }}"
                            class="btn btn-secondary btn-round mr-2" style="margin-top:10px;" title="Tampilkan Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('asisten_manager.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-primary btn-round mr-2" style="margin-top:10px;"
                            title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ✅ Tombol Cetak (Excel & PDF) --}}
                        @if (checkAccess('Data', 'Data ASS', 'print'))
                        <a href="{{ route('asisten_manager.exportExcel') }}"
                            class="btn btn-success btn-round mr-2 d-flex align-items-center" style="margin-top:10px;"
                            title="Export Excel">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('asisten_manager.exportPDF') }}"
                            class="btn btn-danger btn-round d-flex align-items-center" style="margin-top:10px;"
                            title="Export PDF">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap">
                            <thead class="text-primary">
                                <tr>
                                    <th style="width:5%; text-align:center;">#</th>

                                    <th style="width:15%;">
                                        <a href="{{ route('asisten_manager.data', array_merge(request()->query(), [
                                            'sort_by' => 'id',
                                            'sort_order' => (request('sort_by') === 'id' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-primary text-decoration-none">
                                            ID Asisten Manager
                                        </a>
                                    </th>

                                    <th style="width:25%;">
                                        <a href="{{ route('asisten_manager.data', array_merge(request()->query(), [
                                            'sort_by' => 'nama',
                                            'sort_order' => (request('sort_by') === 'nama' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-primary text-decoration-none">
                                            Nama ASS
                                        </a>
                                    </th>

                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('asisten_manager.data', array_merge(request()->query(), [
                                            'sort_by' => 'id_distributor',
                                            'sort_order' => (request('sort_by') === 'id_distributor' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-primary text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>

                                    <th style="width:15%; text-align:center;">
                                        <a href="{{ route('asisten_manager.data', array_merge(request()->query(), [
                                            'sort_by' => 'status',
                                            'sort_order' => (request('sort_by') === 'status' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-primary text-decoration-none">
                                            Status
                                        </a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($asistenManagers as $index => $item)
                                <tr>
                                    <td style="text-align:center;">
                                        {{ $loop->iteration + (method_exists($asistenManagers, 'firstItem') ? $asistenManagers->firstItem() - 1 : 0) }}
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $item->nama }}">
                                        {{ $item->nama }}
                                    </td>
                                    <td style="text-align:center;">{{ $item->id_distributor }}</td>
                                    <td style="text-align:center;">
                                        <span
                                            class="badge badge-{{ strtolower($item->status) == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst(strtolower($item->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data Asisten Manager</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $asistenManagers->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection