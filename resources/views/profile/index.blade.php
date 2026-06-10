@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- HERO HEADER -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); position: relative;">
        <!-- Decorative SVG shapes in background for premium look -->
        <div style="position: absolute; right: -50px; top: -50px; opacity: 0.1; width: 300px; height: 300px; border-radius: 50%; background: white;"></div>
        <div style="position: absolute; left: -100px; bottom: -100px; opacity: 0.05; width: 400px; height: 400px; border-radius: 50%; background: white;"></div>
        
        <div class="card-body p-4 p-md-5 text-white position-relative">
            <div class="row align-items-center">
                <div class="col-auto mb-3 mb-md-0 text-center">
                    <div class="position-relative d-inline-block rounded-circle p-1" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
                        <!-- Avatar Hover Effect -->
                        <div class="profile-avatar-container" style="position: relative; width: 130px; height: 130px; border-radius: 50%; overflow: hidden; cursor: pointer;">
                            <img 
                                id="previewImg"
                                src="{{ auth()->user()->photo 
                                    ? asset('storage/' . auth()->user()->photo) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                class="w-100 h-100"
                                style="object-fit: cover; transition: all 0.3s;"
                            >
                            <div class="avatar-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); opacity: 0; transition: all 0.3s;">
                                <i class="bi bi-camera fs-3 mb-1"></i>
                                <small style="font-size: 11px; font-weight: 500;">Ubah Foto</small>
                            </div>
                        </div>
                    </div>
                    <!-- Hidden photo input -->
                    <input type="file" id="photoInput" class="d-none" accept="image/*">
                </div>
                
                <div class="col text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2 flex-wrap">
                        <h2 class="mb-0 fw-bold">{{ auth()->user()->name }}</h2>
                        <span class="badge bg-white text-primary fw-semibold px-3 py-1 rounded-pill" style="font-size: 13px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                    <p class="mb-0 opacity-75 d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-0">
                        <i class="bi bi-envelope"></i> {{ auth()->user()->email }}
                        <span class="d-none d-md-inline">•</span>
                        <i class="bi bi-person-badge"></i> @<span>{{ auth()->user()->username }}</span>
                    </p>
                </div>
                
                <!-- LOGOUT BUTTON -->
                <div class="col-12 col-md-auto mt-3 mt-md-0 text-center text-md-end">
                    <form action="/logout" method="POST" id="logoutForm" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger px-4 py-2.5 fw-semibold rounded-pill d-inline-flex align-items-center gap-2 border border-2 border-white shadow hover-scale" style="transition: all 0.2s; font-size: 14px;">
                            <i class="bi bi-box-arrow-right fs-5"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY GRID -->
    <div class="row">
        <!-- SETTINGS & FORMS (FULL WIDTH) -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: white; height: 100%;">
                
                <!-- CUSTOM MODERN TABS HEADER -->
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <ul class="nav nav-pills custom-modern-tabs p-1 bg-light rounded-4" id="profileTabs" role="tablist" style="width: fit-content;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-3 px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button" role="tab" aria-controls="info-pane" aria-selected="true">
                                <i class="bi bi-person-lines-fill"></i> Detail Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-3 px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-pane" type="button" role="tab" aria-controls="security-pane" aria-selected="false">
                                <i class="bi bi-shield-lock"></i> Keamanan
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="profileTabsContent">
                        
                        <!-- TAB 1: PROFILE DETAILS -->
                        <div class="tab-pane fade show active animate-fade-in" id="info-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                            <h5 class="fw-bold mb-3 text-dark">Informasi Pribadi</h5>
                            <p class="text-muted mb-4" style="font-size: 13px;">Kelola informasi profil Anda. Klik tombol "Edit Profil" untuk memperbarui informasi data diri Anda.</p>
                            
                            <form action="/profile/update" method="POST" id="profileForm">
                                @csrf
                                
                                <!-- Hidden photo input BASE64 -->
                                <input type="hidden" name="photo" id="photoBase64">

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Nama Lengkap</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                            <input type="text" id="name" name="name" 
                                                   class="form-control bg-light border-0" 
                                                   value="{{ auth()->user()->name }}" readonly required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Email</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" id="email" name="email" 
                                                   class="form-control bg-light border-0" 
                                                   value="{{ auth()->user()->email }}" readonly required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Username</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge text-muted"></i></span>
                                            <input type="text" id="username" name="username" 
                                                   class="form-control bg-light border-0" 
                                                   value="{{ auth()->user()->username }}" readonly required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Hak Akses (Role)</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-briefcase text-muted"></i></span>
                                            <input type="text" class="form-control bg-light border-0 text-muted" 
                                                   value="{{ ucfirst(auth()->user()->role) }}" readonly disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" id="editBtn" class="btn btn-warning px-4 py-2 fw-semibold rounded-3 text-white">
                                        <i class="bi bi-pencil-square me-1"></i> Edit Profil
                                    </button>
                                    
                                    <button type="submit" id="saveBtn" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 d-none">
                                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                                    </button>
                                    
                                    <button type="button" id="cancelBtn" class="btn btn-light px-4 py-2 fw-semibold rounded-3 d-none">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: SECURITY / CHANGE PASSWORD -->
                        <div class="tab-pane fade animate-fade-in" id="security-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
                            <h5 class="fw-bold mb-3 text-dark">Keamanan Akun</h5>
                            <p class="text-muted mb-4" style="font-size: 13px;">Ganti kata sandi Anda secara berkala untuk menjaga akun tetap aman.</p>
                            
                            <form action="/profile/password" method="POST" id="passwordForm">
                                @csrf

                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Kata Sandi Saat Ini</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-key text-muted"></i></span>
                                            <input type="password" name="current_password" class="form-control bg-light border-0" placeholder="••••••••" required>
                                            <button class="btn bg-light border-0 text-muted px-3 toggle-password" type="button">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Kata Sandi Baru</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                                            <input type="password" name="password" class="form-control bg-light border-0" placeholder="Min. 5 karakter" required>
                                            <button class="btn bg-light border-0 text-muted px-3 toggle-password" type="button">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-muted mb-1" style="font-size: 12px;">Konfirmasi Kata Sandi Baru</label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                            <input type="password" name="password_confirmation" class="form-control bg-light border-0" placeholder="Konfirmasi sandi baru" required>
                                            <button class="btn bg-light border-0 text-muted px-3 toggle-password" type="button">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
                                    <i class="bi bi-shield-check me-1"></i> Perbarui Kata Sandi
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL CROP ================= -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      
      <div class="modal-header border-0 pt-4 px-4 pb-0">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-crop text-primary me-2"></i>Sesuaikan Foto Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4 text-center">
        <div class="img-container rounded-3 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="max-height: 450px;">
            <img id="imageCrop" style="max-width: 100%; max-height: 400px; display: block;">
        </div>
      </div>

      <div class="modal-footer border-0 pb-4 px-4 pt-0">
        <button class="btn btn-light px-4 py-2 fw-semibold rounded-3" data-bs-dismiss="modal">Batal</button>
        <button id="cropBtn" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
            <i class="bi bi-check2-circle me-1"></i>Potong & Simpan
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Extra styles for this page to look ultra premium -->
<style>
    /* Pulse animation for active dot */
    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.5; }
    }
    
    /* Hover avatar effect */
    .profile-avatar-container:hover .avatar-overlay {
        opacity: 1 !important;
    }
    .profile-avatar-container:hover img {
        transform: scale(1.08);
    }
    
    /* Input Groups */
    .input-group-custom {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    .input-group-custom:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .input-group-custom .form-control {
        box-shadow: none !important;
        font-size: 14px;
        padding: 10px 12px;
    }
    .input-group-custom .form-control:not([readonly]) {
        background-color: white !important;
    }
    .input-group-custom .input-group-text {
        padding-left: 15px;
        padding-right: 5px;
    }
    
    /* Tabs Styling */
    .custom-modern-tabs {
        background-color: #f3f4f6 !important;
        border: 1px solid #e5e7eb;
    }
    .custom-modern-tabs .nav-link {
        color: #6b7280;
        font-size: 14px;
        border-radius: 10px !important;
    }
    .custom-modern-tabs .nav-link.active {
        background-color: white !important;
        color: #3b82f6 !important;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    /* Smooth tab transition */
    .animate-fade-in {
        animation: tabFadeIn 0.3s ease-out;
    }
    @keyframes tabFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- SweetAlert2 Alerts on success or errors -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3b82f6',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3b82f6'
            });
        @endif

        @if($errors->any() && !session('success'))
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    });
</script>

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush

@endsection