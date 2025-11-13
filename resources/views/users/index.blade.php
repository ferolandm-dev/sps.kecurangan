@extends('layouts.app', [
'namePage' => 'User Management',
'class' => 'sidebar-mini',
'activePage' => 'users',
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

            {{-- ⚙️ USER MANAGEMENT CARD --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="card-title mb-0 text-dark">{{ __('User Management') }}</h4>

                    @php
                    $canCreate = DB::table('user_access')
                    ->where('user_id', auth()->id())
                    ->where('main_menu', 'Pengaturan')
                    ->where('sub_menu', 'User Management')
                    ->value('can_create');
                    @endphp

                    @if ($canCreate)
                    <a href="{{ route('user.create') }}" class="btn btn-success btn-icon btn-round"
                        style="background:#29b14a;border:none;" title="Tambah User">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body" style="background: rgba(255,255,255,0.5); border-radius: 0 0 20px 20px;">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-items-center mb-0" style="color:#333;">
                            <thead style="color:#29b14a">
                                <tr>
                                    <th>Profile</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-center">Tanggal Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                @php
                                $access = DB::table('user_access')
                                ->where('user_id', auth()->id())
                                ->where('main_menu', 'Pengaturan')
                                ->where('sub_menu', 'User Management')
                                ->select('can_edit', 'can_delete')
                                ->first();
                                @endphp

                                <tr>
                                    <td>
                                        <span class="avatar avatar-sm rounded-circle">
                                            <img src="{{ asset('assets/img/default-avatar.png') }}" alt="avatar"
                                                style="max-width: 50px; border-radius: 50%;">
                                        </span>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">

                                        {{-- Tombol Atur Akses --}}
                                        <a href="{{ route('user.access', $user->id) }}"
                                            class="btn btn-info btn-icon btn-sm btn-round" title="Atur Akses">
                                            <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        @if ($access && $access->can_edit)
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-warning btn-icon btn-sm btn-round"
                                            style="background:#eee733;border:none;" title="Edit">
                                            <i class="now-ui-icons ui-2_settings-90"></i>
                                        </a>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        @if ($access && $access->can_delete)
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Card --}}
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        pagingType: "full_numbers",
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari user...",
        }
    });
});
</script>
@endpush