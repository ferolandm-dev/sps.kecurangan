@extends('layouts.app', [
'namePage' => 'Master Sales',
'class' => 'sidebar-mini',
'activePage' => 'sales',
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
                    <h4 class="card-title mb-0 text-dark">{{ __('Master Sales') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- Form Pencarian --}}
                        <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center mr-2"
                            style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari sales..."
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
                        <a href="{{ route('sales.index', request()->except('all')) }}"
                            class="btn btn-warning btn-round mr-2" style="background:#eee733;color:#000;border:none;">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('sales.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-success btn-round mr-2" style="background:#29b14a;border:none;">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah Sales --}}
                        @if (checkAccess('Master', 'Master Sales', 'create'))
                        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-icon btn-round"
                            style="background:#29b14a;border:none;" title="Tambah Sales">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" style="color:#333;">
                            <thead style="color:#29b14a">
                                <tr>
                                    <th>ID Sales</th>
                                    <th>Nama</th>
                                    <th>ID Distributor</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sales as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->id_distributor }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-{{ strtolower($item->status) == 'aktif' ? 'success' : 'danger' }}"
                                            style="background: {{ strtolower($item->status)=='aktif' ? '#29b14a' : '#e74c3c' }};
                                                color:white; border-radius:10px; padding:6px 10px;">
                                            {{ ucfirst(strtolower($item->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Edit --}}
                                        @if (checkAccess('Master', 'Master Sales', 'edit'))
                                        <a href="{{ route('sales.edit', $item->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        @if (checkAccess('Master', 'Master Sales', 'delete'))
                                        <form action="{{ route('sales.destroy', $item->id) }}" method="POST"
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
                                        Tidak ada data sales.
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