document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const photoInput = document.getElementById('photoInput');
    const previewImg = document.getElementById('previewImg');
    const photoBase64 = document.getElementById('photoBase64');
    const imageCrop = document.getElementById('imageCrop');
    const cropModalEl = document.getElementById('cropModal');
    const cropBtn = document.getElementById('cropBtn');

    // Original inputs data caching for Cancel button
    let originalName = '';
    let originalEmail = '';
    let originalUsername = '';

    // EDIT MODE
    if (editBtn) {
        editBtn.onclick = () => {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');

            originalName = nameInput.value;
            originalEmail = emailInput.value;
            originalUsername = usernameInput.value;

            [nameInput, emailInput, usernameInput].forEach(i => {
                i.removeAttribute('readonly');
                i.classList.remove('bg-light');
            });

            editBtn.classList.add('d-none');
            saveBtn.classList.remove('d-none');
            if (cancelBtn) cancelBtn.classList.remove('d-none');
        };
    }

    // CANCEL EDIT MODE
    if (cancelBtn) {
        cancelBtn.onclick = () => {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');

            nameInput.value = originalName;
            emailInput.value = originalEmail;
            usernameInput.value = originalUsername;

            [nameInput, emailInput, usernameInput].forEach(i => {
                i.setAttribute('readonly', 'true');
                i.classList.add('bg-light');
            });

            editBtn.classList.remove('d-none');
            saveBtn.classList.add('d-none');
            cancelBtn.classList.add('d-none');
        };
    }

    // CROP IMAGE
    let cropper;
    let cropModal;
    if (cropModalEl) {
        cropModal = new bootstrap.Modal(cropModalEl);
    }

    // click photo -> trigger file input
    const avatarContainer = document.querySelector('.profile-avatar-container');
    if (avatarContainer) {
        avatarContainer.onclick = () => {
            photoInput.click();
        };
    } else if (previewImg) {
        previewImg.onclick = () => {
            photoInput.click();
        };
    }

    // choose file
    if (photoInput) {
        photoInput.addEventListener('change', function(e){
            const file = e.target.files[0];

            if(file){
                const reader = new FileReader();

                reader.onload = function(event){
                    imageCrop.src = event.target.result;

                    imageCrop.onload = function(){
                        cropModal.show();
                    }
                }

                reader.readAsDataURL(file);
            }
        });
    }

    // init cropper when modal is shown
    if (cropModalEl) {
        cropModalEl.addEventListener('shown.bs.modal', function () {
            cropper = new Cropper(imageCrop, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1
            });
        });

        // destroy cropper on close to prevent canvas bugs
        cropModalEl.addEventListener('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });
    }

    // crop button action
    if (cropBtn) {
        cropBtn.onclick = function () {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });

            if (!canvas) return alert("Crop gagal");

            const base64 = canvas.toDataURL('image/png');

            if (!base64 || base64.length < 1000) {
                return alert("Gambar gagal diproses!");
            }

            // set hidden input
            photoBase64.value = base64;

            // preview image
            previewImg.src = base64;

            cropModal.hide();

            // Auto trigger edit/save mode to let user know they should save changes
            if (saveBtn.classList.contains('d-none')) {
                if (editBtn) editBtn.click();
            }
        };
    }

    // Toggle Password Visibility
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
