@extends('layouts.app', [
'namePage' => 'Master Asisten Manager',
'class' => 'sidebar-mini',
'activePage' => 'asisten_managers',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0">{{ __('Master Asisten Manager') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('asisten_manager.index') }}" method="GET" class="d-flex align-items-center mr-2"
                            style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari Asisten Manager..." value="{{ request('search') }}"
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
                        <a href="{{ route('asisten_manager.index', request()->except('all')) }}"
                            class="btn btn-secondary btn-round mr-2" title="Tampilkan Halaman Terbatas">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('asisten_manager.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-primary btn-round mr-2" title="Tampilkan Semua Asisten Manager">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah Asisten Manager (akses: create) --}}
                        @if (checkAccess('Master', 'Master ASS', 'create'))
                        <a href="{{ route('asisten_manager.create') }}" class="btn btn-primary btn-icon btn-round"
                            title="Tambah Asisten Manager">
                            <i class="now-ui-icons ui-1_simple-add"></i>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID Asisten Manager</th>
                                    <th>Nama</th>
                                    <th>ID Distributor</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($asistenManagers as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->id_distributor }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-{{ strtolower($item->status) == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst(strtolower($item->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Edit --}}
                                        @if (checkAccess('Master', 'Master ASS', 'edit'))
                                        <a href="{{ route('asisten_manager.edit', $item->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        @if (checkAccess('Master', 'Master ASS', 'delete'))
                                        <form action="{{ route('asisten_manager.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon btn-sm btn-round"
                                                title="Hapus">
                                                <i class="now-ui-icons ui-1_simple-remove"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data Asisten Manager.
                                    </td>
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
