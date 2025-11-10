@extends('layouts.app', [
'namePage' => 'User Management',
'class' => 'sidebar-mini',
'activePage' => 'users',
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

            {{-- ✅ CARD USER MANAGEMENT --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ __('User Management') }}</h4>

                    @php
                    $canCreate = DB::table('user_access')
                    ->where('user_id', auth()->id())
                    ->where('main_menu', 'Pengaturan')
                    ->where('sub_menu', 'User Management')
                    ->value('can_create');
                    @endphp

                    @if ($canCreate)
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-round" title="Tambah User">
                        <i class="now-ui-icons ui-1_simple-add"></i>
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead class="text-primary">
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
                                            class="btn btn-warning btn-icon btn-sm btn-round" title="Edit">
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
                                                title="Hapus">
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