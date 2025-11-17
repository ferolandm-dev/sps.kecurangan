@extends('layouts.app', [
'namePage' => 'User Profile',
'class' => 'sidebar-mini',
'activePage' => 'profile',
])

@section('content')

{{-- HEADER GRADIENT --}}
<div class="panel-header panel-header-sm panel-header-sps"></div>

<div class="content">

    <div class="row justify-content-center align-items-stretch">

        {{-- ================= LEFT COLUMN ================= --}}
        <div class="col-md-8">

            {{-- EDIT PROFILE --}}
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow:hidden; margin-bottom:25px;">

                <div class="card-header bg-white border-0" style="padding: 20px 25px;">
                    <h4 class="card-title mb-0 text-dark" style="font-weight:700;">Edit Profile</h4>
                </div>

                <div class="card-body" style="
                    background: rgba(255,255,255,0.5);
                    border-radius: 0 0 20px 20px;
                    padding:25px 25px 35px;
                ">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        <div class="form-group">
                            <label class="font-weight-bold">Nama</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>

                        <div class="card-footer bg-transparent border-0 px-0 mt-4">
                            <button type="submit" class="btn btn-success btn-round px-4">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- CHANGE PASSWORD --}}
            <div class="card shadow-lg border-0" style="border-radius:20px; overflow:hidden;">

                <div class="card-header bg-white border-0" style="padding:20px 25px;">
                    <h4 class="card-title mb-0 text-dark" style="font-weight:700;">Ubah Password</h4>
                </div>

                <div class="card-body" style="
                    background: rgba(255,255,255,0.5);
                    border-radius: 0 0 20px 20px;
                    padding:25px 25px 35px;
                ">
                    <form method="post" action="{{ route('profile.password') }}">
                        @csrf
                        @method('put')

                        @include('alerts.success', ['key' => 'password_status'])

                        <div class="form-group">
                            <label class="font-weight-bold">Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                            @include('alerts.feedback', ['field' => 'old_password'])
                        </div>

                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>

                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="card-footer bg-transparent border-0 px-0 mt-4">
                            <button type="submit" class="btn btn-success btn-round px-4">
                                Ubah Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        {{-- ================= RIGHT COLUMN ================= --}}
        <div class="col-md-4">
            <div class="card card-user shadow-xl border-0" style="
                border-radius:22px;
                background:rgba(255,255,255,0.95);
                backdrop-filter:blur(10px);
            ">

                <div class="card-body text-center pb-2">

                    <div class="author mt-3">

                        {{-- FOTO --}}
                        <img class="avatar border-gray" src="{{ asset('assets/img/default-avatar.png') }}" style="
                                width:110px;
                                height:110px;
                                border-radius:50%;
                                border:3px solid #e0e0e0;
                                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                            ">

                        <h5 class="title mt-3" style="color:#333; font-weight:700;">
                            {{ auth()->user()->name }}
                        </h5>

                        <p style="color:#666; font-size:14px;">{{ auth()->user()->email }}</p>

                    </div>
                </div>

                <hr style="border-top:1px solid #ddd; width:80%; margin:auto;">

                <div class="button-container d-flex justify-content-center pb-3 pt-2">
                    @foreach (['facebook-square','twitter','google-plus-square'] as $icon)
                    <button class="social-btn mx-2">
                        <i class="fab fa-{{ $icon }}"></i>
                    </button>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
@push('styles')
<style>
/* PANEL HEADER GRADIENT */
body,
.wrapper,
.main-panel {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
    background-attachment: fixed !important;
    /* supaya smooth */
}


.panel-header-sps {
    background: transparent !important;
    box-shadow: none !important;
}


.content {
    background: transparent !important;
}

/* ========================================
   NAVBAR MATCHING — SAME GRADIENT AS HEADER
========================================= */

.navbar-soft {
    background: linear-gradient(90deg, #29b14a 0%, #dbd300 85%) !important;
    border: none !important;
    box-shadow: none !important;

    /* Tinggi navbar sesuai permintaan */
    height: 95px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;

    display: flex !important;
    align-items: center !important;

    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* Brand */
.navbar-soft .navbar-brand {
    color: #ffffff !important;
    font-size: 22px !important;
    font-weight: 700;
}

/* Icons */
.navbar-soft .nav-link i {
    color: #ffffff !important;
    font-size: 22px;
    transition: .2s ease;
}

.navbar-soft .nav-link:hover i {
    color: #333 !important;
}

.navbar-soft {
    transition: none !important;
    /* matikan transisi container */
}

.navbar-soft .nav-link i,
.navbar-soft .navbar-brand {
    transition: color .25s ease, transform .25s ease !important;
    /* biarkan hover tetap smooth */
}


/* INPUT */
input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
}

/* BUTTON GLOBAL */
.btn {
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 8px 18px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    transition: .25s ease !important;
}

.btn:hover {
    transform: translateY(-2px);
}

/* HIJAU */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: white !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

/* SOCIAL BUTTON */
.social-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(6px);
    font-size: 26px;
    border: none;
    transition: .25s;
}

.social-btn:hover {
    transform: translateY(-4px);
    background: white;
}

.social-btn:hover i {
    color: #29b14a;
}

/* Hilangkan lingkaran biru di button sosial */
.social-btn:focus,
.social-btn:active,
.social-btn:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}
</style>
@endpush