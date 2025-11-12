@extends('layouts.app', [
'namePage' => 'Menu Management',
'class' => 'sidebar-mini',
'activePage' => 'menus',
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

            {{-- ✅ ALERT SUCCESS --}}
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
                margin-bottom: 25px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- ⚠️ ALERT ERROR --}}
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="
                background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
                color: #fff;
                border: none;
                border-radius: 14px;
                padding: 14px 18px;
                font-weight: 500;
                letter-spacing: 0.3px;
                box-shadow: 0 4px 12px rgba(231,76,60,0.3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 25px;">
                <div class="d-flex align-items-center">
                    <i class="now-ui-icons ui-1_bell-53 mr-2" style="font-size:18px;"></i>
                    <span>{!! session('error') !!}</span>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="
                    color:#fff;
                    opacity:0.8;
                    font-size:22px;
                    margin-left:10px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif


            {{-- ✅ CARD MENU MANAGEMENT --}}
            <div class="card" style="border-radius:20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('Menu Management') }}</h4>

                    {{-- Tombol Tambah Menu --}}
                    @if (checkAccess('Pengaturan', 'Menu Management', 'create'))
                    <a href="{{ route('menus.create') }}" class="btn btn-primary btn-icon btn-round"
                        style="background:#29b14a;border:none;" title="Tambah Menu">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">

                    {{-- ✅ Loop per Main Menu --}}
                    @forelse ($menus as $mainMenu => $items)
                    <div class="mb-5">
                        <h5 class="font-weight-bold mb-3" style="
            color:#29b14a;
            border-bottom:2px solid #ddd;
            padding-bottom:6px;">
                            {{ $mainMenu === '' ? 'Tanpa Kategori' : $mainMenu }}
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0" style="color:#333;">
                                <thead style="color:#29b14a;">
                                    <tr class="text-center">
                                        <th class="text-left">Sub Menu</th>
                                        <th class="text-left">Icon</th>
                                        <th class="text-left">Route</th>
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
                                        <td class="text-left">{{ $menu->sub_menu ?? '-' }}</td>
                                        <td class="text-left">{{ $menu->icon ?? '-' }}</td>
                                        <td class="text-left">{{ $menu->route ?? '-' }}</td>
                                        <td class="text-center">{{ $menu->main_order ?? 0 }}</td>
                                        <td class="text-center">{{ $menu->order ?? 0 }}</td>

                                        {{-- CRUD --}}
                                        <td class="text-center">
                                            @if ($menu->can_crud)
                                            <span class="badge badge-success"
                                                style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">Ya</span>
                                            @else
                                            <span class="badge badge-secondary"
                                                style="border-radius:10px;padding:6px 10px;">Tidak</span>
                                            @endif
                                        </td>

                                        {{-- Print --}}
                                        <td class="text-center">
                                            @if ($menu->can_print)
                                            <span class="badge badge-success"
                                                style="background:#29b14a;color:white;border-radius:10px;padding:6px 10px;">Ya</span>
                                            @else
                                            <span class="badge badge-secondary"
                                                style="border-radius:10px;padding:6px 10px;">Tidak</span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="text-center">
                                            @if (checkAccess('Pengaturan', 'Menu Management', 'edit'))
                                            <a href="{{ route('menus.edit', $menu->id) }}"
                                                class="btn btn-warning btn-icon btn-sm btn-round"
                                                style="background:#eee733;border:none;" title="Edit">
                                                <i class="now-ui-icons ui-2_settings-90"></i>
                                            </a>
                                            @endif

                                            @if (checkAccess('Pengaturan', 'Menu Management', 'delete'))
                                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                                class="d-inline">
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
    $('.table').DataTable({
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