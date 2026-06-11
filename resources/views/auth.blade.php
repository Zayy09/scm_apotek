<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login & Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>
<body>

<div class="container-auth">

    <!-- BACKGROUND -->
    <div class="panel login-mode" id="panel">

        <div class="white"></div>

        <div class="gradient">
            <div class="content-right">
                <h1 id="title">Selamat Datang!</h1>
                <div id="authLogo" class="auth-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Apotek" width="140" style="object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));">
                </div>
                <p id="desc">Mulai Kelola Toko Obat Templek Sehat Anda</p>
                <button type="button" onclick="toggleMode()">Daftar</button>
            </div>
        </div>

    </div>

    <!-- FORM -->
    <form method="POST" action="/login" id="authForm" class="form-box">
        @csrf

        <h2 id="formTitle">Masuk</h2>

        <!-- REGISTER -->
        <input type="text" name="name" placeholder="Nama Lengkap" class="register-only">
        <input type="email" name="email" placeholder="Email" class="register-only">

        <!-- UMUM -->
        <div class="input-group">
            <input type="text" name="username" placeholder="Masukkan Username" required>
            <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            </span>
        </div>
        
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required>
            <span class="toggle-password input-icon" onclick="togglePassword('password', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
        </div>

        <!-- REGISTER -->
        <div class="input-group register-only">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi">
            <span class="toggle-password input-icon" onclick="togglePassword('password_confirmation', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
        </div>

        <div class="options login-only">
            <label class="remember-me"><input type="checkbox" name="remember"> Ingatkan Saya</label>
            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Kata Sandi?</a>
        </div>
        <button type="submit" id="submitBtn">Masuk</button>

        <div class="toggle-link" onclick="toggleMode()" id="toggleText">
            Belum punya akun? Daftar
        </div>
    </form>

</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4a6cf7'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#4a6cf7'
        });
    @endif
    
    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#4a6cf7'
        });
    @endif
</script>
</body>
</html>