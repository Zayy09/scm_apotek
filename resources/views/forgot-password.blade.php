<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Kata Sandi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<style>
    .form-box {
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .panel {
        display: none;
    }
    body {
        background: linear-gradient(135deg, #6a5acd, #5aa9ff);
    }
    .back-btn {
        display: inline-block;
        margin-top: 15px;
        text-align: center;
        width: 100%;
        color: #4a6cf7;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }
    .back-btn:hover {
        text-decoration: underline;
    }
    p.info-text {
        text-align: center;
        font-size: 13px;
        color: #666;
        margin-bottom: 20px;
    }
</style>
</head>
<body>

<div class="container-auth">
    <!-- FORM -->
    <form method="POST" action="/forgot-password" class="form-box">
        @csrf
        <h2 id="formTitle">Reset Kata Sandi</h2>
        
        <p class="info-text">Masukkan username dan email Anda untuk mereset kata sandi.</p>

        <div class="input-group">
            <input type="text" name="username" placeholder="Masukkan Username" required value="{{ old('username') }}">
            <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            </span>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Masukkan Email Anda" required value="{{ old('email') }}">
            <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </span>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Kata Sandi Baru" required>
            <span class="toggle-password input-icon" onclick="togglePassword('password', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
        </div>

        <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru" required>
            <span class="toggle-password input-icon" onclick="togglePassword('password_confirmation', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
        </div>

        <button type="submit">Reset Kata Sandi</button>
        
        <a href="{{ route('login') }}" class="back-btn">Kembali ke halaman Masuk</a>
    </form>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
