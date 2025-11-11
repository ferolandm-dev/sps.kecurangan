@extends('layouts.app', [
'namePage' => 'Edit Menu',
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
                    <h4 class="card-title">{{ __('Edit Menu') }}</h4>
                </div>

                <div class="card-body">
                    {{-- ALERT ERROR DARI VALIDASI --}}
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- ALERT ERROR DARI QUERY (DUPLICATE ORDER) --}}
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- ALERT SUCCESS --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    {{-- FORM EDIT MENU --}}
                    <form method="POST" action="{{ route('menus.update', $menu->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Main Menu --}}
                        <div class="form-group">
                            <label for="main_menu">{{ __('Main Menu') }}</label>
                            <input type="text" name="main_menu" id="main_menu"
                                class="form-control @error('main_menu') is-invalid @enderror"
                                value="{{ old('main_menu', $menu->main_menu) }}" placeholder="Contoh: Master">
                            @error('main_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sub Menu --}}
                        <div class="form-group">
                            <label for="sub_menu">{{ __('Sub Menu') }}</label>
                            <input type="text" name="sub_menu" id="sub_menu"
                                class="form-control @error('sub_menu') is-invalid @enderror"
                                value="{{ old('sub_menu', $menu->sub_menu) }}" placeholder="Contoh: Data User">
                            @error('sub_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div class="form-group">
                            <label for="icon">{{ __('Icon') }}</label>
                            <input type="text" name="icon" id="icon"
                                class="form-control @error('icon') is-invalid @enderror"
                                value="{{ old('icon', $menu->icon) }}"
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
                                value="{{ old('route', $menu->route) }}" placeholder="Contoh: users.index">
                            @error('route')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Main Order --}}
                        <div class="form-group">
                            <label for="main_order">{{ __('Urutan Main Menu') }}</label>
                            <input type="number" name="main_order" id="main_order"
                                class="form-control @error('main_order') is-invalid @enderror"
                                value="{{ old('main_order', $menu->main_order) }}" placeholder="Contoh: 1">
                            @error('main_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Order Sub Menu --}}
                        <div class="form-group">
                            <label for="order">{{ __('Urutan Sub Menu') }}</label>
                            <input type="number" name="order" id="order"
                                class="form-control @error('order') is-invalid @enderror"
                                value="{{ old('order', $menu->order) }}" min="0">
                            @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hak Akses Menu --}}
                        <div class="border-top pt-3 mt-3">
                            <h5 class="mb-3">{{ __('Hak Akses Menu') }}</h5>

                            <div class="row d-inline-flex">
                                {{-- CRUD --}}
                                <div class="col-md-5">
                                    <label>{{ __('CRUD') }}</label>
                                    <select name="can_crud" class="form-select mb-2" style="width: 200px">
                                        <option value="1" {{ old('can_crud', $menu->can_crud) == 1 ? 'selected' : '' }}>
                                            Ya</option>
                                        <option value="0" {{ old('can_crud', $menu->can_crud) == 0 ? 'selected' : '' }}>
                                            Tidak</option>
                                    </select>
                                </div>

                                {{-- Print --}}
                                <div class="col-md-5">
                                    <label>{{ __('PRINT') }}</label>
                                    <select name="can_print" class="form-select" style="width: 200px">
                                        <option value="1"
                                            {{ old('can_print', $menu->can_print) == 1 ? 'selected' : '' }}>Ya</option>
                                        <option value="0"
                                            {{ old('can_print', $menu->can_print) == 0 ? 'selected' : '' }}>Tidak
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-3 mb-3">
                            * Minimal isi salah satu field utama (Main Menu, Sub Menu, atau Route).
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