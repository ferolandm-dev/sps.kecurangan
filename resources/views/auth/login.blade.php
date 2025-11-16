@extends('layouts.login', [
'namePage' => 'Login page',
'class' => 'login-page',
'activePage' => 'login',
])

@section('content')
<div class="content login-wrapper">

    <div class="login-card-container">

        <div class="logo-container">
            <img src="{{ asset('assets/img/SPS.png') }}" alt="Logo">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="input-group-custom">
                <i class="now-ui-icons users_circle-08 icon-left"></i>

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>

                <div class="focus-underline"></div>
            </div>

            @error('email')
            <span class="error-text">{{ $message }}</span>
            @enderror

            {{-- PASSWORD --}}
            <div class="input-group-custom">
                <i class="now-ui-icons objects_key-25 icon-left"></i>

                <input id="password-input" type="password" name="password" placeholder="Password" required>

                <i class="fas fa-eye icon-right" id="toggle-password"></i>

                <div class="focus-underline"></div>
            </div>

            @error('password')
            <span class="error-text">{{ $message }}</span>
            @enderror

            {{-- BUTTON --}}
            <button type="submit" class="btn-submit">Login</button>
        </form>

    </div>

</div>
@endsection

@push('styles')
<style>
/* ============= GLOBAL RESET FOR LOGIN PAGE ============= */
html,
body {
    overflow: hidden !important;
    height: 100dvh !important;  /* fix mobile scroll */
    max-height: 100dvh !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
}

/* Wrapper utama */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card */
.login-card-container {
    width: 380px;
    background: #fff;
    padding: 35px 30px;
    border-radius: 18px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Logo */
.logo-container img {
    width: 150px;
    transform: scale(1.2);
    margin-bottom: 15px;
}

/* Input Group Modern */
.input-group-custom {
    position: relative;
    margin-bottom: 22px;
    border: 2px solid #29b14a;
    border-radius: 12px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    background: #fff;
    transition: .2s ease;
}

/* Icon kiri */
.icon-left {
    font-size: 20px;
    color: #29b14a;
    margin-right: 10px;
}

/* Input field */
.input-group-custom input {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    flex: 1;
    font-size: 16px;
    color: #29b14a;
    background: transparent;
}

/* Placeholder */
.input-group-custom input::placeholder {
    color: #555 !important;
    opacity: 0.7 !important;
}

/* Icon kanan (eye) */
.icon-right {
    font-size: 20px;
    cursor: pointer;
    color: #29b14a;
    margin-left: 10px;
}

/* Hover & Focus Border */
.input-group-custom:hover,
.input-group-custom:focus-within {
    border-color: #1f8f3a !important;
}

/* Error message */
.error-text {
    display: block;
    margin-top: -12px;
    margin-bottom: 10px;
    color: #e60000;
    font-size: 13px;
}

/* Button */
.btn-submit {
    width: 100%;
    padding: 12px;
    background: #29b14a;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: .3s ease;
}

.btn-submit:hover {
    background: #1e8f39;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener("DOMContentLoaded", function() {

    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password-input');

    togglePassword.addEventListener('click', () => {
        const isHidden = passwordInput.type === "password";

        passwordInput.type = isHidden ? "text" : "password";

        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
    });

});
</script>
@endpush