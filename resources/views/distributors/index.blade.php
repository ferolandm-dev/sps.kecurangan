@extends('layouts.app', [
'namePage' => 'Master Distributor',
'class' => 'sidebar-mini',
'activePage' => 'distributors',
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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0">{{ __('Master Distributor') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('distributors.index') }}" method="GET"
                            class="d-flex align-items-center mr-2" style="margin-top: 10px;">
                            <div class="input-group" style="width:250px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari distributor..."
                                    value="{{ request('search') }}"
                                    style="height:38px;border-radius:30px 0 0 30px;padding-left:15px; margin-top:10px;">
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
                        <a href="{{ route('distributors.index', request()->except('all')) }}"
                            class="btn btn-secondary btn-round mr-2" title="Tampilkan Semua Halaman">
                            <i class="now-ui-icons arrows-1_refresh-69"></i> Tampilkan Halaman
                        </a>
                        @else
                        <a href="{{ route('distributors.index', array_merge(request()->query(), ['all' => true])) }}"
                            class="btn btn-primary btn-round mr-2" title="Tampilkan Semua Data">
                            <i class="now-ui-icons ui-1_zoom-bold"></i> Tampilkan Semua
                        </a>
                        @endif

                        {{-- Tombol Tambah hanya muncul jika punya akses create --}}
                        @if (checkAccess('Master', 'Master Distributor', 'create'))
                        <a href="{{ route('distributors.create') }}" class="btn btn-primary btn-icon btn-round"
                            title="Tambah Distributor">
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
                                    <th>ID Distributor</th>
                                    <th>Nama Distributor</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($distributors as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->distributor }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-{{ strtolower($d->status) == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst(strtolower($d->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">

                                        {{-- Tombol Edit hanya muncul jika punya akses edit --}}
                                        @if (checkAccess('Master', 'Master Distributor', 'edit'))
                                        <a href="{{ route('distributors.edit', $d->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Delete hanya muncul jika punya akses delete --}}
                                        @if (checkAccess('Master', 'Master Distributor', 'delete'))
                                        <form action="{{ route('distributors.destroy', $d->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus distributor ini?');">
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
                                    <td colspan="4" class="text-center text-muted">Belum ada data distributor</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination hanya tampil jika bukan mode "Tampilkan Semua" --}}
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