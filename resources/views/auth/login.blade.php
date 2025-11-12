@extends('layouts.app', [
'namePage' => 'Login page',
'class' => 'login-page sidebar-mini',
'activePage' => 'login',
])
@section('content')
<div class="content">
    <div class="container">
        <div class="col-md-12 ml-auto mr-auto">
            <div class="header bg-gradient-primary py-10 py-lg-2 pt-lg-12">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 col-md-9">
                                <p class="text-lead text-light mt-3 mb-0">
                                    @include('alerts.migrations_check')
                                </p>
                            </div>
                            <div class="col-lg-5 col-md-6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 ml-auto mr-auto">
            <form role="form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card card-login card-plain">
                    <div class="card-header ">
                        <div class="logo-container" style="text-align: center;">
                            <img src="{{ asset('assets/img/SPS.png') }}" alt="">
                        </div>

                    </div>
                    <div class="card-body ">
                        <div
                            class="input-group no-border form-control-lg {{ $errors->has('email') ? ' has-danger' : '' }}">
                            <span class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="now-ui-icons users_circle-08"></i>
                                </div>
                            </span>
                            <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Email') }}" type="email" name="email"
                                value="{{ old('email', 'admin@nowui.com') }}" required autofocus>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                        <div
                            class="input-group no-border form-control-lg {{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="now-ui-icons objects_key-25"></i></i>
                                </div>
                            </div>
                            <input placeholder="Password"
                                class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                placeholder="{{ __('Password') }}" type="password" value="secret" required>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif

                        <button type="submit"
                            class="btn btn-primary btn-round btn-lg btn-block mb-2">{{ __('Get Started') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // === Nonaktifkan background bawaan template ===
    if (typeof demo !== "undefined" && demo.checkFullPageBackgroundImage) {
        // demo.checkFullPageBackgroundImage();
    }

    // === ATUR BACKGROUND CONTENT ===
    const content = document.querySelector('.content');
    if (content) {
        content.style.setProperty('background-color', '#ffffff', 'important'); // putih
        content.style.setProperty('min-height', '100vh', 'important'); // full tinggi layar
        content.style.setProperty('display', 'flex', 'important');
        content.style.setProperty('flex-direction', 'column', 'important');
        content.style.setProperty('justify-content', 'center', 'important');
        content.style.setProperty('align-items', 'center', 'important');
        content.style.setProperty('padding', '20px', 'important');
    }

    // === LOGO ===
    const container = document.querySelector('.logo-container');
    const logo = container ? container.querySelector('img') : null;
    if (container) {
        container.style.setProperty('display', 'flex', 'important');
        container.style.setProperty('justify-content', 'center', 'important');
        container.style.setProperty('align-items', 'center', 'important');
        container.style.setProperty('height', '200px', 'important');
    }
    if (logo) {
        logo.style.setProperty('width', '150px', 'important');
        logo.style.setProperty('height', 'auto', 'important');
        logo.style.setProperty('max-width', 'none', 'important');
        logo.style.setProperty('transform', 'scale(1.3)', 'important');
    }

    // === SPASI CARD ===
    const cardHeader = document.querySelector('.card-login .card-header');
    const cardBody = document.querySelector('.card-login .card-body');
    if (cardHeader) {
        cardHeader.style.setProperty('padding-bottom', '0', 'important');
        cardHeader.style.setProperty('margin-bottom', '-80px', 'important');
    }
    if (cardBody) {
        cardBody.style.setProperty('padding-top', '5px', 'important');
        cardBody.style.setProperty('text-align', 'left', 'important');
    }

    // === INPUT GROUP STYLE (ICON - INPUT, rata kiri & rapi tengah) ===
    const inputGroups = document.querySelectorAll('.input-group');
    inputGroups.forEach(group => {
        group.style.setProperty('display', 'flex', 'important');
        group.style.setProperty('flex-direction', 'row', 'important');
        group.style.setProperty('align-items', 'center', 'important');
        group.style.setProperty('justify-content', 'flex-start', 'important');
        group.style.setProperty('gap', '10px', 'important');
        group.style.setProperty('border', '2px solid #f96332', 'important');
        group.style.setProperty('border-radius', '10px', 'important');
        group.style.setProperty('background-color', '#fff', 'important');
        group.style.setProperty('padding', '8px 12px', 'important');
        group.style.setProperty('margin-bottom', '15px', 'important');
        group.style.setProperty('width', '100%', 'important');
        group.style.setProperty('box-sizing', 'border-box', 'important');
    });

    // === ICON ===
    const icons = document.querySelectorAll('.input-group-text');
    icons.forEach(icon => {
        icon.style.setProperty('background', 'transparent', 'important');
        icon.style.setProperty('color', '#29b14a', 'important');
        icon.style.setProperty('border', 'none', 'important');
        icon.style.setProperty('font-size', '20px', 'important');
        icon.style.setProperty('display', 'flex', 'important');
        icon.style.setProperty('align-items', 'center', 'important');
        icon.style.setProperty('justify-content', 'center', 'important');
        icon.style.setProperty('padding', '0', 'important');
        icon.style.setProperty('margin-right', '5px', 'important');
        icon.style.setProperty('height', '100%', 'important');
    });

    // === INPUT ===
    const inputs = document.querySelectorAll('.input-group input');
    inputs.forEach(input => {
        input.style.setProperty('border', 'none', 'important');
        input.style.setProperty('box-shadow', 'none', 'important');
        input.style.setProperty('background', 'transparent', 'important');
        input.style.setProperty('padding', '6px', 'important');
        input.style.setProperty('font-size', '16px', 'important');
        input.style.setProperty('color', '#29b14a', 'important');
        input.style.setProperty('flex', '1', 'important');
        input.style.setProperty('outline', 'none', 'important');
        input.style.setProperty('text-align', 'left', 'important');

        // === Pastikan border parent input-group awalnya hijau ===
        const group = input.closest('.input-group');
        if (group) {
            group.style.setProperty('border', '2px solid #29b14a', 'important');
            group.style.setProperty('border-radius', '10px', 'important');
            group.style.setProperty('background-color', '#fff', 'important');
            group.style.setProperty('display', 'flex', 'important');
            group.style.setProperty('align-items', 'center', 'important');
            group.style.setProperty('padding', '6px 12px', 'important');
            group.style.setProperty('gap', '8px', 'important');
        }

        // === Placeholder hitam ===
        const style = document.createElement('style');
        style.innerHTML = `
        input[name="${input.name}"]::placeholder {
            color: #000 !important;
            opacity: 0.85 !important;
        }
    `;
        document.head.appendChild(style);

        // === Efek fokus border hijau ===
        input.addEventListener('focus', () => {
            const parent = input.closest('.input-group');
            if (parent) parent.style.setProperty('border', '2.5px solid #29b14a', 'important');
        });
        input.addEventListener('blur', () => {
            const parent = input.closest('.input-group');
            if (parent) parent.style.setProperty('border', '2px solid #29b14a', 'important');
        });
    });

    // === UBAH WARNA TOMBOL "GET STARTED" ===
    const button = document.querySelector('.btn.btn-primary.btn-round.btn-lg.btn-block');
    if (button) {
        button.style.setProperty('background-color', '#29b14a', 'important'); // hijau
        button.style.setProperty('border', '2px solid #29b14a', 'important');
        button.style.setProperty('color', '#fff', 'important');
        button.style.setProperty('font-weight', '600', 'important');
        button.style.setProperty('transition', 'all 0.3s ease', 'important');

        // Efek hover
        button.addEventListener('mouseenter', () => {
            button.style.setProperty('background-color', '#1e8e39',
                'important'); // lebih gelap saat hover
            button.style.setProperty('border-color', '#1e8e39', 'important');
        });
        button.addEventListener('mouseleave', () => {
            button.style.setProperty('background-color', '#29b14a', 'important');
            button.style.setProperty('border-color', '#29b14a', 'important');
        });

        // Efek klik
        button.addEventListener('mousedown', () => {
            button.style.setProperty('transform', 'scale(0.97)', 'important');
        });
        button.addEventListener('mouseup', () => {
            button.style.setProperty('transform', 'scale(1)', 'important');
        });
    }


});
</script>
@endpush