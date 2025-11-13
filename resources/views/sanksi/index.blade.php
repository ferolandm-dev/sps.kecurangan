@extends('layouts.app', [
'namePage' => 'Master Sanksi',
'class' => 'sidebar-mini',
'activePage' => 'sanksi',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #c3be25ff"></div>

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


            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Sanksi') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- Form Pencarian --}}
                        <form action="{{ route('sanksi.index') }}" method="GET" class="d-flex align-items-center mr-2"
                            style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari sanksi..."
                                    value="{{ request('search') }}"
                                    style="height:38px;border-radius:30px 0 0 30px;padding-left:15px; margin-top:10px;">
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
                        <a href="{{ route('sanksi.index', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2" style="background:#eee733;color:#000;border:none;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('sanksi.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2" style="background:#29b14a;border:none;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah Sanksi --}}
                        @if (checkAccess('Master', 'Master Sanksi', 'create'))
                        <a href="{{ route('sanksi.create') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none;" title="Tambah Sanksi">
                            <i class="now-ui-icons ui-1_simple-add"></i>
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

                                    <th style="width:20%;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                        'sort_by' => 'jenis',
                        'sort_order' => (request('sort_by') === 'jenis' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Jenis
                                        </a>
                                    </th>

                                    <th style="width:35%;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                        'sort_by' => 'keterangan',
                        'sort_order' => (request('sort_by') === 'keterangan' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Keterangan
                                        </a>
                                    </th>

                                    <th style="width:20%; text-align:center;">
                                        <a href="{{ route('sanksi.index', array_merge(request()->query(), [
                        'sort_by' => 'nilai',
                        'sort_order' => (request('sort_by') === 'nilai' && request('sort_order') === 'asc') ? 'desc' : 'asc'
                    ])) }}" class="text-success text-decoration-none">
                                            Nilai (Rupiah)
                                        </a>
                                    </th>

                                    <th style="width:20%; text-align:center;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sanksi as $index => $item)
                                <tr>
                                    <td style="text-align:center;">
                                        {{ $loop->iteration + (method_exists($sanksi, 'firstItem') ? $sanksi->firstItem() - 1 : 0) }}
                                    </td>

                                    <td>{{ $item->jenis }}</td>

                                    <td class="text-truncate" style="max-width: 300px;" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan }}
                                    </td>

                                    <td style="text-align:center;">
                                        Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if (checkAccess('Master', 'Master Sanksi', 'edit'))
                                        <a href="{{ route('sanksi.edit', $item->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        @if (checkAccess('Master', 'Master Sanksi', 'delete'))
                                        <form action="{{ route('sanksi.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round"
                                                style="background:#e74c3c;border:none;" title="Hapus">
                                                <i class="now-ui-icons ui-1_simple-remove"></i>
                                            </button>
                                        </form>
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
                    {{-- Pagination --}}
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
@endsection