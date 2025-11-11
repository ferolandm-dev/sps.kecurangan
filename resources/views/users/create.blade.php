@extends('layouts.app', [
'namePage' => 'Add User',
'class' => 'sidebar-mini',
'activePage' => 'users',
])

@section('content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="col-md-13 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Tambah User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-round">Simpan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary btn-round">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection