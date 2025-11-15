@extends('layouts.app', [
'class' => 'sidebar-mini ',
'namePage' => 'User Profile',
'activePage' => 'profile',
'activeNav' => '',
])

@section('content')
<div class="panel-header panel-header-sm" style="background: #dbd300ff"></div>

<div class="content" style="backdrop-filter: blur(12px); margin-top: -70px; padding: 30px; color: #333;">

    <div class="row justify-content-center align-items-stretch">

        {{-- ================= LEFT COLUMN ================= --}}
        <div class="col-md-8">

            {{-- CARD : EDIT PROFILE --}}
            <div class="card shadow-lg border-0" style="border-radius: 22px; overflow:hidden; margin-bottom:25px;">

                <div class="card-header bg-white border-0" style="padding: 20px 25px;">
                    <h5 class="title text-dark mb-0" style="font-weight:700;">
                        Edit Profile
                    </h5>
                </div>

                <div class="card-body"
                    style="background: rgba(255,255,255,0.6); backdrop-filter:blur(5px); padding:25px 25px 35px;">

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        {{-- NAME --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Nama</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        {{-- EMAIL --}}
                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>

                        <div class="card-footer bg-transparent border-0 px-0 mt-4">
                            <button type="submit" class="btn btn-success btn-round px-4"
                                style="background:#29b14a;border:none;">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARD : CHANGE PASSWORD --}}
            <div class="card shadow-lg border-0" style="border-radius:22px; overflow:hidden;">

                <div class="card-header bg-white border-0" style="padding:20px 25px;">
                    <h5 class="title text-dark mb-0" style="font-weight:700;">
                        Ubah Password
                    </h5>
                </div>

                <div class="card-body"
                    style="background:rgba(255,255,255,0.6); backdrop-filter:blur(5px); padding:25px 25px 35px;">

                    <form method="post" action="{{ route('profile.password') }}">
                        @csrf
                        @method('put')

                        @include('alerts.success', ['key' => 'password_status'])

                        {{-- CURRENT PASSWORD --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Password Lama</label>
                            <input class="form-control" type="password" name="old_password" required>
                            @include('alerts.feedback', ['field' => 'old_password'])
                        </div>

                        {{-- NEW PASSWORD --}}
                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Password Baru</label>
                            <input class="form-control" type="password" name="password" required>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="form-group mt-3">
                            <label class="font-weight-bold">Konfirmasi Password</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>

                        <div class="card-footer bg-transparent border-0 px-0 mt-4">
                            <button type="submit" class="btn btn-success btn-round px-4"
                                style="background:#29b14a;border:none;">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ================= RIGHT COLUMN ================= --}}
        <div class="col-md-4">
            <div class="card card-user border-0 shadow-xl" style="
            border-radius:22px;
            background:rgba(255,255,255,0.95);
            backdrop-filter:blur(10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.10); /* shadow netral */
         ">

                <div class="card-body text-center pb-2">

                    {{-- AVATAR --}}
                    <div class="author mt-3">
                        <img class="avatar border-gray" src="{{ asset('assets/img/default-avatar.png') }}" alt="..."
                            style="
                        width:110px; 
                        height:110px; 
                        border-radius:50%;
                        box-shadow:0 5px 15px rgba(0,0,0,0.15); /* shadow netral */
                        border:3px solid #e0e0e0; /* border abu */
                     ">

                        <h5 class="title mt-3" style="color:#333; font-weight:700;">
                            {{ auth()->user()->name }}
                        </h5>

                        <p class="description" style="color:#666; font-size:14px;">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                <hr style="border-top:1px solid #dddddd; width:80%; margin:auto;"> {{-- garis netral --}}

                {{-- SOCIAL ICONS --}}
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
@push('js')
<style>
.panel-header {
    margin-top: -25px !important;
}

.btn-no-hover:hover,
.btn-no-hover:focus,
.btn-no-hover:active {
    background: white !important;
    color: #29b14a !important;
    box-shadow: none !important;
    transform: none !important;
}

input:invalid,
textarea:invalid,
select:invalid {
    box-shadow: none !important;
    border-color: #ced4da !important;
    /* warna abu normal */
}

input:focus,
textarea:focus,
select:focus {
    border-color: #4caf50 !important;
    /* hijau atau sesuai tema */
}

/* ===========================================================
   GLOBAL SOFT UI BUTTON STYLE
=========================================================== */
.btn {
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    padding: 8px 18px !important;
    transition: all 0.25s ease-in-out !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
}

/* SUCCESS BUTTON (Hijau) */
.btn-success {
    background: linear-gradient(135deg, #29b14a, #34d058) !important;
    color: #fff !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #25a344, #2fc655) !important;
}

/* DANGER BUTTON (Merah) */
.btn-danger {
    background: linear-gradient(135deg, #e74c3c, #ff6b5c) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d84333, #fa5f50) !important;
}

/* SECONDARY BUTTON (Abu) */
.btn-secondary {
    background: linear-gradient(135deg, #bfc2c7, #d6d8db) !important;
    color: #333 !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #b0b3b7, #c9cbce) !important;
}

/* WARNING BUTTON (Kuning lembut) */
.btn-warning {
    background: linear-gradient(135deg, #eee733, #faf26b) !important;
    color: #333 !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e2db2e, #f0eb63) !important;
}

/* ROUND STYLE */
.btn-round {
    border-radius: 30px !important;
}

/* ICON ALIGNMENT FIX */
.btn i {
    font-size: 15px;
    margin-right: 6px;
}

/* DISABLED BUTTON STYLE */
.btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.social-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(6px);
    box-shadow: 
        0 4px 12px rgba(0,0,0,0.08),
        inset 0 2px 4px rgba(255,255,255,0.8);
    color: #555;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .25s ease-in-out;
}

/* Hover effect */
.social-btn:hover {
    transform: translateY(-4px);
    box-shadow: 
        0 8px 20px rgba(0,0,0,0.15),
    background: white;
}

/* Icon color on hover */
.social-btn:hover i {
    color: #4caf50; /* biru modern */
}

</style>

endpush