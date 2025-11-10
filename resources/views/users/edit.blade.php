@extends('layouts.app', [
    'namePage' => 'Edit User',
    'class' => 'sidebar-mini',
    'activePage' => 'user',
])

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="col-md-12 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="title text-primary mb-0">Edit User</h5>
            </div>

            <div class="card-body">
                {{-- 🔔 Global Alert untuk semua error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="form-group">
                        <label>Nama</label>
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name', $user->name) }}" 
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label>Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email', $user->email) }}" 
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label>Password (Opsional)</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Biarkan kosong jika tidak ingin mengubah password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="form-control" 
                            placeholder="Ulangi password baru">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-round">Batal</a>
                        <button type="submit" class="btn btn-success btn-round">
                            <i class="now-ui-icons "></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ✨ Sedikit CSS tambahan untuk tampilan rapi --}}
<style>
    .alert ul {
        padding-left: 20px;
    }
    .invalid-feedback {
        display: block;
        font-size: 13px;
    }
</style>
@endsection
