let isLogin = true;

function toggleMode(){
    const panel = document.getElementById('panel');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const toggleText = document.getElementById('toggleText');
    const title = document.getElementById('title');
    const desc = document.getElementById('desc');
    const switchBtn = document.querySelector('.content-right button');
    const form = document.getElementById('authForm');

    panel.classList.toggle('register-mode');

    if(isLogin){
        // REGISTER
        formTitle.innerText = "Daftar Akun Baru";
        submitBtn.innerText = "Daftar Sekarang";
        toggleText.innerText = "Sudah punya akun? Masuk";

        title.innerText = "Sudah Punya Akun?";
        desc.innerText = "Masuk untuk melanjutkan akses ke sistem kami.";

        switchBtn.innerText = "Masuk";

        form.action = "/register";

        isLogin = false;
    }else{
        // LOGIN
        formTitle.innerText = "Masuk";
        submitBtn.innerText = "Masuk";
        toggleText.innerText = "Belum punya akun? Daftar";

        title.innerText = "Selamat Datang!";
        desc.innerText = "Mulai Kelola Apotek Anda dengan Mudah";

        switchBtn.innerText = "Daftar Sekarang";

        form.action = "/login";

        isLogin = true;
    }
}

function togglePassword(inputId, iconSpan) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    } else {
        input.type = "password";
        iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    }
}
