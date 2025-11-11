@extends('layouts.app', [
'namePage' => 'Data Kecurangan',
'class' => 'sidebar-mini',
'activePage' => 'data_kecurangan',
])

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- CARD DATA --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <h4 class="card-title mb-0">{{ __('Data Kecurangan') }}</h4>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 10px; margin-top: 8px;">

                            {{-- Tombol Tambah Kecurangan --}}
                            <a href="{{ route('kecurangan.index') }}" class="btn btn-primary btn-icon btn-round"
                                title="Tambah Sales">
                                <i class="now-ui-icons ui-1_simple-add"></i>
                            </a>

                            {{-- Form Pencarian --}}
                            <form action="{{ route('kecurangan.data') }}" method="GET"
                                class="d-flex align-items-center mr-2" style="margin-top: 10px;">
                                <div class="input-group" style="width:250px;">
                                    <input type="text" name="search" class="form-control" placeholder="Cari sales..."
                                        value="{{ request('search') }}"
                                        style="height:38px;border-radius:30px 0 0 30px;padding-left:15px;margin-top:10px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-round" type="submit"
                                            style="height:38px;border-radius:0 30px 30px 0;">
                                            <i class="now-ui-icons ui-1_zoom-bold"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            {{-- Tombol Tampilkan Semua / Halaman --}}
                            @if (request()->has('all'))
                            <a href="{{ route('kecurangan.data', request()->except('all')) }}"
                                class="btn btn-secondary btn-round" style="height:38px;display:flex;align-items:center;"
                                title="Tampilkan Semua Halaman">
                                <i class="now-ui-icons arrows-1_refresh-69"></i>&nbsp;Tampilkan Halaman
                            </a>
                            @else
                            <a href="{{ route('kecurangan.data', array_merge(request()->query(), ['all' => true])) }}"
                                class="btn btn-primary btn-round" style="height:38px;display:flex;align-items:center;"
                                title="Tampilkan Semua Data">
                                <i class="now-ui-icons ui-1_zoom-bold"></i>&nbsp;Tampilkan Semua
                            </a>
                            @endif

                            {{-- Tombol Export Excel --}}
                            @if (checkAccess('Data', 'Data Kecurangan', 'print'))
                            <a href="{{ route('kecurangan.exportExcel') }}"
                                class="btn btn-success btn-round mr-2 d-flex align-items-center"
                                style="margin-top:10px;" title="Export Excel">
                                <i class="now-ui-icons files_single-copy-04 mr-1"></i> Excel
                            </a>
                            @endif

                            {{-- Tombol Export PDF --}}
                            @if (checkAccess('Data', 'Data Kecurangan', 'print'))
                            <a href="{{ route('kecurangan.exportPDF') }}"
                                class="btn btn-danger btn-round d-flex align-items-center" style="margin-top:10px;"
                                title="Export PDF">
                                <i class="now-ui-icons files_paper mr-1"></i> PDF
                            </a>
                            @endif

                        </div>
                    </div>

                    {{-- 🔍 Form Filter Tanggal --}}
                    <form action="{{ route('kecurangan.data') }}" method="GET"
                        class="d-flex align-items-center flex-wrap" style="gap: 10px; margin-top: 10px;">

                        {{-- 📅 Tanggal Mulai --}}
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}" style="width:200px; height:38px;" title="Tanggal Mulai">

                        {{-- 📅 Tanggal Akhir --}}
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}" style="width:200px; height:38px;" title="Tanggal Akhir">

                        {{-- 🎯 Tombol Filter --}}
                        <button type="submit"
                            class="btn btn-success btn-icon btn-round d-flex align-items-center justify-content-center"
                            style="height:38px; padding: 0 20px;" title="Filter">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1"></i>
                        </button>

                        {{-- 🔄 Tombol Reset --}}
                        <a href="{{ route('kecurangan.data') }}"
                            class="btn btn-secondary btn-icon btn-round d-flex align-items-center justify-content-center"
                            style="height:38px; padding: 0 20px;" title="Reset Filter">
                            <i class="now-ui-icons arrows-1_refresh-69"></i>
                        </a>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <style>
                        .table {
                            border-collapse: separate;
                            border-spacing: 0 10px;
                            width: 100%;
                        }

                        .table th,
                        .table td {
                            padding: 12px 18px;
                            vertical-align: middle;
                            white-space: nowrap;
                            text-align: left;
                        }

                        .table thead th {
                            font-weight: 600;
                            border-bottom: 2px solid rgba(255, 255, 255, 0.15);
                        }

                        .table tbody tr {
                            background-color: rgba(255, 255, 255, 0.03);
                            border-radius: 12px;
                            transition: background-color 0.2s ease;
                        }

                        .table tbody tr:hover {
                            background-color: rgba(255, 255, 255, 0.08);
                        }

                        .badge-success {
                            background-color: #28a745;
                            color: #fff;
                            padding: 6px 10px;
                            border-radius: 10px;
                            font-size: 12px;
                        }

                        @media (max-width: 768px) {

                            .table th,
                            .table td {
                                padding: 10px 12px;
                                font-size: 13px;
                                white-space: normal;
                            }
                        }
                        </style>

                        @php
                        $sortBy = request('sort_by', 'tanggal');
                        $sortOrder = request('sort_order', 'desc');
                        function sortLink($label, $column) {
                        $isActive = request('sort_by') === $column;
                        $nextOrder = ($isActive && request('sort_order') === 'asc') ? 'desc' : 'asc';
                        return '<a
                            href="' . route('kecurangan.data', array_merge(request()->query(), ['sort_by' => $column, 'sort_order' => $nextOrder])) . '"
                            style="color:inherit;text-decoration:none;">' . $label . '</a>';
                        }
                        @endphp

                        <table class="table table-hover align-middle">
                            <thead class="text-primary">
                                <tr>
                                    <th>{!! sortLink('#', 'id') !!}</th>
                                    <th>{!! sortLink('ID Sales', 'id_sales') !!}</th>
                                    <th>{!! sortLink('Nama Sales', 'nama_sales') !!}</th>
                                    <th>{!! sortLink('Distributor', 'distributor') !!}</th>
                                    <th>{!! sortLink('Nama ASS', 'nama_asisten_manager') !!}</th>
                                    <th>{!! sortLink('Toko', 'toko') !!}</th>
                                    <th class="text-center">{!! sortLink('Kunjungan', 'kunjungan') !!}</th>
                                    <th>{!! sortLink('Tanggal', 'tanggal') !!}</th>
                                    <th>{!! sortLink('Keterangan', 'keterangan') !!}</th>
                                    <th>{!! sortLink('Kuartal', 'kuartal') !!}</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kecurangan as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration + (method_exists($kecurangan, 'firstItem') ? $kecurangan->firstItem() - 1 : 0) }}
                                    </td>
                                    <td>{{ $item->id_sales }}</td>
                                    <td>{{ $item->nama_sales }}</td>
                                    <td>{{ $item->distributor }}</td>
                                    <td>{{ $item->nama_asisten_manager }}</td>
                                    <td>{{ $item->toko }}</td>
                                    <td class="text-center">{{ $item->kunjungan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>{{ $item->kuartal }}</td>
                                    <td class="text-center">
                                        @if($item->validasi == 0)
                                        <form action="{{ route('kecurangan.validasi', $item->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm btn-round"
                                                onclick="return confirm('Validasi data ini?');">
                                                <i class="now-ui-icons"></i> Validasi
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge badge-success">Tervalidasi</span>
                                        @endif

                                        @if($item->validasi == 0)
                                        <form action="{{ route('kecurangan.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round">
                                                <i class="now-ui-icons ui-1_simple-remove"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">Belum ada data kecurangan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
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
@endsection