@extends('layouts.app', [
'class' => 'sidebar-mini ',
'namePage' => 'User Profile',
'activePage' => 'profile',
'activeNav' => '',
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
        <div class="col-md-8">
            <div class="card" style="border-radius:20px;">
                <div class="card-header">
                    <h5 class="title text-dark">{{__(" Edit Profile")}}</h5>
                </div>

                <div class="card-body" style="background:rgba(255,255,255,0.5); border-radius:0 0 20px 20px;">
                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        {{-- ALERT --}}
                        @include('alerts.success')

                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__(" Name")}}</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', auth()->user()->name) }}">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__(" Email address")}}</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', auth()->user()->email) }}">
                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                {{__('Simpan')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card" style="border-radius:20px;">
                <div class="card-header">
                    <h5 class="title text-dark">{{__("Password")}}</h5>
                </div>

                <div class="card-body" style="background:rgba(255,255,255,0.5); border-radius:0 0 20px 20px;">
                    <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        {{-- ALERT --}}
                        @include('alerts.success', ['key' => 'password_status'])

                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__(" Current Password")}}</label>
                                    <input class="form-control" type="password" name="old_password" required>
                                    @include('alerts.feedback', ['field' => 'old_password'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__(" New Password")}}</label>
                                    <input class="form-control" type="password" name="password" required>
                                    @include('alerts.feedback', ['field' => 'password'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__(" Confirm New Password")}}</label>
                                    <input class="form-control" type="password" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-round"
                                style="background:#29b14a;border:none;">
                                {{__('Ubah Password')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card card-user"
                style="border-radius:20px; max-height:321px; border: none; box-shadow: 0 4px 12px rgba(41, 177, 74, 0.25);">

                <div class="card-body text-center">
                    <div class="author">
                        <a href="#">
                            <img class="avatar border-gray" src="{{asset('assets/img/default-avatar.png')}}" alt="...">
                            <h5 class="title" style="color:#29b14a; font-weight:600;">
                                {{ auth()->user()->name }}
                            </h5>
                        </a>
                        <p class="description" style="color:#555;">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                <hr style="border-top: 1px solid #29b14a33;">

                <div class="button-container d-flex justify-content-center pb-3">
                    <button class="btn btn-icon btn-round btn-lg btn-no-hover"
                        style="background:white; color:#29b14a; margin: 0 5px; border:none; font-size:40px">
                        <i class="fab fa-facebook-square"></i>
                    </button>

                    <button class="btn btn-icon btn-round btn-lg btn-no-hover"
                        style="background:white; color:#29b14a; margin: 0 5px; border:none; font-size:40px">
                        <i class="fab fa-twitter"></i>
                    </button>

                    <button class="btn btn-icon btn-round btn-lg btn-no-hover"
                        style="background:white; color:#29b14a; margin: 0 5px; border:none; font-size:40px">
                        <i class="fab fa-google-plus-square"></i>
                    </button>
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
</style>

endpush