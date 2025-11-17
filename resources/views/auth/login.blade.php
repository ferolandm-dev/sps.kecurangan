@extends('layouts.login', [
'namePage' => 'LOGIN SPS',
'class' => 'login-page',
'activePage' => 'login',
])

@section('content')
<div class="content login-wrapper">

    <div class="login-card-container fadeUp fade-delay-1">

        <div class="logo-container fadeUp fade-delay-1">
            <img src="{{ asset('assets/img/SPS.png') }}" alt="Logo">
        </div>

        <form method="POST" action="{{ route('login') }}">

            @csrf

            {{-- EMAIL --}}
            <div class="input-group-custom fadeUp fade-delay-1">
                <i class="now-ui-icons users_circle-08 icon-left"></i>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
            </div>

            @error('email')
            <span class="error-text fadeUp fade-delay-1">{{ $message }}</span>
            @enderror

            {{-- PASSWORD --}}
            <div class="input-group-custom fadeUp fade-delay-1">
                <i class="now-ui-icons objects_key-25 icon-left"></i>
                <input id="password-input" type="password" name="password" placeholder="Password" required>
                <i class="fas fa-eye icon-right" id="toggle-password"></i>
            </div>

            @error('password')
            <span class="error-text fadeUp fade-delay-1">{{ $message }}</span>
            @enderror

            {{-- BUTTON --}}
            <button type="submit" class="btn-submit fadeUp fade-delay-1">Login</button>

        </form>

    </div>

</div>
@endsection

@push('styles')
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/SPS LOGO.png') }}">
<link rel="icon" type="image/png" href="{{ asset('assets/img/SPS LOGO.png') }}">
<style>
/* ========= BACKGROUND LOGIN (override template) ========= */
html,
body {
    margin: 0 !important;
    padding: 0 !important;
    height: 100vh !important;
    overflow: hidden !important;
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
}

/* override background bawaan NOW UI Dashboard */
.content.login-wrapper {
    background: linear-gradient(140deg, #29b14a 0%, #c7c500 50%, #dbd300 92%) !important;
}


/* Wrapper */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ========= ANIMASI FADEUP GLOBAL ========= */
.fadeUp {
    opacity: 0;
    transform: translateY(25px);
    animation: fadeUp 0.7s ease-out forwards;
}

.fade-delay-1 {
    animation-delay: .1s;
}

.fade-delay-2 {
    animation-delay: .2s;
}

.fade-delay-3 {
    animation-delay: .3s;
}

.fade-delay-4 {
    animation-delay: .4s;
}

.fade-delay-5 {
    animation-delay: .5s;
}

@keyframes fadeUp {
    0% {
        opacity: 0;
        transform: translateY(25px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Card */
.login-card-container {
    width: 380px;
    background: rgba(255, 255, 255, 0.92);
    padding: 35px 30px;
    border-radius: 18px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    text-align: center;
    backdrop-filter: blur(10px);
}

/* Logo */
.logo-container img {
    width: 150px;
    margin-bottom: 15px;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.25));
}

/* Input group */
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

/* Icons */
.icon-left,
.icon-right {
    font-size: 20px;
    color: #29b14a;
}

/* Input field */
.input-group-custom input {
    border: none;
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

/* Hover */
.input-group-custom:hover,
.input-group-custom:focus-within {
    border-color: #1f8f3a !important;
}

/* Error text */
.error-text {
    display: block;
    margin-top: -10px;
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

/* Hapus border biru bawaan browser */
input:focus,
button:focus,
textarea:focus,
select:focus {
    outline: none !important;
    box-shadow: none !important;
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