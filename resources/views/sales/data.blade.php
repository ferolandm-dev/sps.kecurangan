@extends('layouts.app', [
'namePage' => 'Data Sales',
'class' => 'sidebar-mini',
'activePage' => 'data_sales',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #dbd300ff"></div>

<div class="content" style="
    backdrop-filter: blur(12px);
    margin-top: -70px;
    padding: 30px;
    color: #333;
">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container"
                role="alert" style="
                    background: rgba(41,177,74,0.2);
                    border: 1px solid #29b14a;
                    color: #155724;
                    border-radius: 12px;
                ">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color:#155724;">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
                <span data-notify="icon" class="now-ui-icons ui-1_check"></span>
                <span data-notify="message">{{ session('success') }}</span>
            </div>
            @endif

            {{-- CARD DATA SALES --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Data Sales') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('sales.data') }}" method="GET" class="d-flex align-items-center mr-2"
                            style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari sales..."
                                    value="{{ request('search') }}"
                                    style="height:38px;border-radius:30px 0 0 30px;padding-left:15px;margin-top:10px;">
                                <div class="input-group-append">
                                    <button class="btn btn-success btn-round" type="submit"
                                        style="height:38px;border-radius:0 30px 30px 0;background:#29b14a;border:none;">
                                        <i class="now-ui-icons ui-1_zoom-bold"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Tombol Tampilkan Semua / Paginate --}}
                        @if (request()->has('all'))
                        <a href="{{ route('sales.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;"
                            title="Tampilkan Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('sales.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;" title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ✅ Tombol Cetak (Excel & PDF) --}}
                        @if (checkAccess('Data', 'Data Sales', 'print'))
                        <a href="{{ route('sales.exportExcel') }}"
                            class="btn btn-success btn-round mr-2 d-flex align-items-center"
                            style="margin-top:10px;background:#29b14a;border:none;" title="Export Excel">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('sales.exportPDF') }}"
                            class="btn btn-danger btn-round d-flex align-items-center"
                            style="margin-top:10px;background:#e74c3c;border:none;" title="Export PDF">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
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

                                    <th style="width:15%;">
                                        <a href="{{ route('sales.data', array_merge(request()->query(), [
                                            'sort_by' => 'id',
                                            'sort_order' => (request('sort_by') === 'id' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            ID Sales
                                        </a>
                                    </th>

                                    <th style="width:25%;">
                                        <a href="{{ route('sales.data', array_merge(request()->query(), [
                                            'sort_by' => 'nama',
                                            'sort_order' => (request('sort_by') === 'nama' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Nama Sales
                                        </a>
                                    </th>

                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('sales.data', array_merge(request()->query(), [
                                            'sort_by' => 'id_distributor',
                                            'sort_order' => (request('sort_by') === 'id_distributor' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>

                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('sales.data', array_merge(request()->query(), [
                                            'sort_by' => 'total_kecurangan',
                                            'sort_order' => (request('sort_by') === 'total_kecurangan' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Total Kecurangan
                                        </a>
                                    </th>

                                    <th style="width:15%; text-align:center;">
                                        <a href="{{ route('sales.data', array_merge(request()->query(), [
                                            'sort_by' => 'status',
                                            'sort_order' => (request('sort_by') === 'status' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Status
                                        </a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sales as $index => $item)
                                <tr>
                                    <td style="text-align:center;">
                                        {{ $loop->iteration + (method_exists($sales, 'firstItem') ? $sales->firstItem() - 1 : 0) }}
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $item->nama }}">
                                        {{ $item->nama }}
                                    </td>
                                    <td style="text-align:center;">{{ $item->id_distributor }}</td>
                                    <td style="text-align:center;">{{ $item->total_kecurangan ?? 0 }}</td>
                                    <td style="text-align:center;">
                                        <span class="badge" style="background: {{ strtolower($item->status) == 'aktif' ? '#29b14a' : '#e74c3c' }};
                                                color:white; border-radius:10px; padding:6px 10px;">
                                            {{ ucfirst(strtolower($item->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Belum ada data sales
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $sales->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection