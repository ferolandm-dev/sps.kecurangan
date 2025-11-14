@extends('layouts.app', [
'namePage' => 'Data Distributor',
'class' => 'sidebar-mini',
'activePage' => 'data_distributors',
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
            <div class="alert alert-success alert-with-icon alert-dismissible fade show shadow-lg"
                data-notify="container" role="alert" style="
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
                    <span data-notify="icon" class="now-ui-icons ui-1_check mr-2" style="font-size:18px;"></span>
                    <span data-notify="message">{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
        color:#fff;
        opacity:0.8;
        font-size:22px;
        margin-left:10px;
    ">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
            </div>
            @endif


            {{-- CARD DATA DISTRIBUTOR --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Data Distributor') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('distributors.data') }}" method="GET"
                            class="d-flex align-items-center mr-2" style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari distributor..."
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
                        <a href="{{ route('distributors.data', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2"
                            style="background:#eee733;color:#000;border:none;margin-top:10px;"
                            title="Tampilkan Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('distributors.data', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2"
                            style="background:#29b14a;border:none;margin-top:10px;" title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- ✅ Tombol Cetak (Excel + PDF) --}}
                        @if (checkAccess('Data', 'Data Distributor', 'print'))
                        <a href="{{ route('distributors.exportExcel') }}"
                            class="btn btn-success btn-round mr-2 d-flex align-items-center"
                            style="margin-top:10px;background:#29b14a;border:none;" title="Export Excel">
                            <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                        </a>

                        <a href="{{ route('distributors.exportPDF') }}"
                            class="btn btn-danger btn-round d-flex align-items-center"
                            style="margin-top:10px;background:#e74c3c;border:none;" title="Export PDF">
                            <i class="now-ui-icons files_paper mr-1"></i> PDF
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap align-middle mb-0" style="color:#333;">
                            <thead style="color:#29b14a;">
                                <tr>
                                    <th style="width:5%; text-align:center;">#</th>
                                    <th style="width:15%;">
                                        <a href="{{ route('distributors.data', array_merge(request()->query(), [
                                            'sort_by' => 'id',
                                            'sort_order' => (request('sort_by') === 'id' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            ID Distributor
                                        </a>
                                    </th>
                                    <th style="width:30%;">
                                        <a href="{{ route('distributors.data', array_merge(request()->query(), [
                                            'sort_by' => 'distributor',
                                            'sort_order' => (request('sort_by') === 'distributor' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Nama Distributor
                                        </a>
                                    </th>
                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('distributors.data', array_merge(request()->query(), [
                                            'sort_by' => 'jumlah_sales',
                                            'sort_order' => (request('sort_by') === 'jumlah_sales' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Jumlah Sales Aktif
                                        </a>
                                    </th>
                                    <th style="width:15%; text-align:center;">
                                        <a href="{{ route('distributors.data', array_merge(request()->query(), [
                                            'sort_by' => 'status',
                                            'sort_order' => (request('sort_by') === 'status' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                                        ])) }}" class="text-success text-decoration-none">
                                            Status
                                        </a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($distributors as $index => $d)
                                <tr>
                                    <td style="text-align:center;">
                                        {{ $loop->iteration + (method_exists($distributors, 'firstItem') ? $distributors->firstItem() - 1 : 0) }}
                                    </td>
                                    <td>{{ $d->id }}</td>
                                    <td class="text-truncate" style="max-width: 250px;" title="{{ $d->distributor }}">
                                        {{ $d->distributor }}
                                    </td>
                                    <td style="text-align:center;">{{ $d->jumlah_sales ?? 0 }}</td>
                                    <td style="text-align:center;">
                                        <span class="badge" style="background: {{ strtolower($d->status) == 'aktif' ? '#29b14a' : '#e74c3c' }};
                                                   color:white; border-radius:10px; padding:6px 10px;">
                                            {{ ucfirst(strtolower($d->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data distributor</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if (!request()->has('all'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $distributors->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('js')
<style>
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
    /* warna abu normal */
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* hijau atau sesuai tema */
}
</style>
@endpush