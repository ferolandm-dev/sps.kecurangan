@extends('layouts.app', [
'namePage' => 'Tambah Menu',
'class' => 'sidebar-mini',
'activePage' => 'menus',
])

@section('content')
<div class="panel-header panel-header-sm"></div>

<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Tambah Menu Baru') }}</h4>
                </div>

                <div class="card-body">
                    {{-- 🔔 ALERT ERROR VALIDASI --}}
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi Kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- 🔔 ALERT ERROR CUSTOM (misal dari controller -> with('error', '...')) --}}
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- 🔔 ALERT SUCCESS --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- FORM TAMBAH MENU --}}
                    <form method="POST" action="{{ route('menus.store') }}">
                        @csrf

                        {{-- Main Menu --}}
                        <div class="form-group">
                            <label for="main_menu">{{ __('Main Menu') }}</label>
                            <input type="text" name="main_menu" id="main_menu"
                                class="form-control @error('main_menu') is-invalid @enderror"
                                placeholder="Contoh: Master">
                            @error('main_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sub Menu --}}
                        <div class="form-group">
                            <label for="sub_menu">{{ __('Sub Menu') }}</label>
                            <input type="text" name="sub_menu" id="sub_menu"
                                class="form-control @error('sub_menu') is-invalid @enderror"
                                placeholder="Contoh: Data User">
                            @error('sub_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div class="form-group">
                            <label for="icon">{{ __('Icon') }}</label>
                            <input type="text" name="icon" id="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                placeholder="Contoh: now-ui-icons users_single-02">
                            @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Route --}}
                        <div class="form-group">
                            <label for="route">{{ __('Route') }}</label>
                            <input type="text" name="route" id="route"
                                class="form-control @error('route') is-invalid @enderror"
                                placeholder="Contoh: users.index">
                            @error('route')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Main Order --}}
                        <div class="form-group">
                            <label for="main_order">{{ __('Urutan Main Menu') }}</label>
                            <input type="number" name="main_order" id="main_order"
                                class="form-control @error('main_order') is-invalid @enderror" value="0"
                                placeholder="Contoh: 1 untuk menu utama pertama">
                            @error('main_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Order (Sub Menu) --}}
                        <div class="form-group">
                            <label for="order">{{ __('Urutan Sub Menu') }}</label>
                            <input type="number" name="order" id="order"
                                class="form-control @error('order') is-invalid @enderror" value="0"
                                placeholder="Contoh: 1 untuk urutan pertama di dalam main menu">
                            @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hak Akses Menu --}}
                        <div class="border-top pt-3 mt-3">
                            <h5 class="mb-3">{{ __('Hak Akses Menu') }}</h5>

                            <div class="row">
                                {{-- CRUD --}}
                                <div class="col-md-3">
                                    <label>{{ __('CRUD') }}</label>
                                    <select name="can_crud" class="form-control">
                                        <option value="1">Ya</option>
                                        <option value="0" selected>Tidak</option>
                                    </select>
                                </div>

                                {{-- Print --}}
                                <div class="col-md-3">
                                    <label>{{ __('Print') }}</label>
                                    <select name="can_print" class="form-control">
                                        <option value="1">Ya</option>
                                        <option value="0" selected>Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-3 mb-3">
                            * Minimal isi salah satu field utama (Main Menu, Sub Menu, atau Route).
                            <br>* "Urutan Main Menu" menentukan posisi menu utama di sidebar.
                            <br>* "Urutan Sub Menu" menentukan posisi submenu di dalam Main Menu yang sama.
                        </small>

                        <div class="text-right">
                            <a href="{{ route('menus.index') }}" class="btn btn-secondary btn-round">Batal</a>
                            <button type="submit" class="btn btn-success btn-round">
                                <i class="now-ui-icons"></i> {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                    {{-- END FORM --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection