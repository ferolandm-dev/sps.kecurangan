@extends('layouts.app', [
'namePage' => 'Menu Management',
'class' => 'sidebar-mini',
'activePage' => 'menus',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row">
        <div class="col-md-12">

            {{-- ✅ ALERT SUCCESS --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ❌ ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ✅ CARD MENU MANAGEMENT --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ __('Menu Management') }}</h4>

                    {{-- Tombol Tambah Menu --}}
                    @if (checkAccess('Pengaturan', 'Menu Management', 'create'))
                    <a href="{{ route('menus.create') }}" class="btn btn-icon btn-primary btn-round"
                        title="Tambah Menu">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    {{-- ✅ Loop per Main Menu --}}
                    @forelse ($menus as $mainMenu => $items)
                    <tr>
                        <div class="mb-5">
                            <h5 class="font-weight-bold text-primary mb-3" style="border-bottom:2px solid #ddd;">
                                {{ $mainMenu === '' ? 'Tanpa Kategori' : $mainMenu }}
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatable">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th>Sub Menu</th>
                                            <th>Icon</th>
                                            <th>Route</th>
                                            <th>Main Order</th>
                                            <th>Order</th>
                                            <th>CRUD</th>
                                            <th>Print</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $menu)
                                        <tr>
                                            <td>{{ $menu->sub_menu ?? '-' }}</td>
                                            <td>{{ $menu->icon ?? '-' }}</td>
                                            <td>{{ $menu->route ?? '-' }}</td>
                                            <td class="text-center">{{ $menu->main_order ?? 0 }}</td>
                                            <td class="text-center">{{ $menu->order ?? 0 }}</td>

                                            {{-- Kolom CRUD --}}
                                            <td class="text-center">
                                                @if ($menu->can_crud)
                                                <span class="badge badge-success">Ya</span>
                                                @else
                                                <span class="badge badge-secondary">Tidak</span>
                                                @endif
                                            </td>

                                            {{-- Kolom Print --}}
                                            <td class="text-center">
                                                @if ($menu->can_print)
                                                <span class="badge badge-success">Ya</span>
                                                @else
                                                <span class="badge badge-secondary">Tidak</span>
                                                @endif
                                            </td>

                                            {{-- Aksi --}}
                                            <td class="text-center">
                                                {{-- Tombol Edit --}}
                                                @if (checkAccess('Pengaturan', 'Menu Management', 'edit'))
                                                <a href="{{ route('menus.edit', $menu->id) }}"
                                                    class="btn btn-warning btn-icon btn-sm btn-round" title="Edit">
                                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                                </a>
                                                @endif

                                                {{-- Tombol Delete --}}
                                                @if (checkAccess('Pengaturan', 'Menu Management', 'delete'))
                                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-icon btn-sm btn-round" title="Hapus">
                                                        <i class="now-ui-icons ui-1_simple-remove"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Belum ada menu</div>
                        @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        pagingType: "full_numbers",
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari menu...",
        }
    });
});
</script>
@endpush