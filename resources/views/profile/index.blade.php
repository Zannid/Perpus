@extends('layouts.backend')
@section('title', 'E-Perpus - Profil Saya')

@section('css')
<style>
    /* ============================================================
       PROFILE HEADER SECTION
       ============================================================ */
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 60px 0 100px;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .profile-header-content {
        position: relative;
        z-index: 1;
        text-align: center;
        color: white;
    }

    .profile-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .profile-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* ============================================================
       PROFILE CARD
       ============================================================ */
    .profile-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 2;
    }

    .profile-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        background: white;
    }

    /* ============================================================
       AVATAR SECTION
       ============================================================ */
    .avatar-section {
        padding: 40px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        text-align: center;
        position: relative;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
    }

    .avatar-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .avatar-image:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .avatar-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .role-badge {
        display: inline-block;
        padding: 8px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* ============================================================
       FILE INPUT CUSTOM
       ============================================================ */
    .custom-file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 400px;
        margin: 20px auto;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 24px;
        background: white;
        border: 2px dashed #667eea;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #667eea;
        font-weight: 600;
    }

    .file-upload-label:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .file-upload-label i {
        font-size: 20px;
    }

    .file-name-display {
        text-align: center;
        margin-top: 10px;
        font-size: 0.9rem;
        color: #6c757d;
        font-style: italic;
    }

    /* ============================================================
       FORM SECTION
       ============================================================ */
    .form-section {
        padding: 40px;
    }

    .form-group-custom {
        margin-bottom: 25px;
    }

    .form-label-custom {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label-custom i {
        color: #667eea;
    }

    .form-control-custom {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .form-control-custom.is-invalid {
        border-color: #dc3545;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
    }

    /* ============================================================
       INFO CARDS
       ============================================================ */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .info-card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
    }

    .info-card-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-card-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    /* ============================================================
       BUTTONS
       ============================================================ */
    .btn-custom-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-custom-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-custom-secondary {
        background: white;
        border: 2px solid #667eea;
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        color: #667eea;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-custom-secondary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    /* ============================================================
       ALERT
       ============================================================ */
    .alert-custom-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 12px;
        color: white;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        animation: slideInDown 0.5s ease;
    }

    .alert-custom-success i {
        font-size: 24px;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================================
       STATS SECTION
       ============================================================ */
    .stats-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .stats-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stats-title i {
        color: #667eea;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 768px) {
        .profile-header {
            padding: 40px 0 80px;
        }

        .profile-header h2 {
            font-size: 1.5rem;
        }

        .avatar-image {
            width: 120px;
            height: 120px;
        }

        .form-section,
        .avatar-section {
            padding: 30px 20px;
        }

        .btn-custom-primary,
        .btn-custom-secondary {
            width: 100%;
            justify-content: center;
        }

        .info-cards {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Profile Header -->
<div class="profile-header">
    <div class="container">
        <div class="profile-header-content">
            <h2><i class="bx bx-user-circle me-2"></i>Profil Saya</h2>
            <p>Kelola informasi profil dan preferensi akun Anda</p>
        </div>
    </div>
</div>

<div class="container profile-wrapper mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Success Alert -->
            @if (session('success'))
                <div class="alert alert-custom-success mb-4" role="alert">
                    <i class="bx bx-check-circle"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="profile-card">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf

                    <!-- Avatar Section -->
                    <div class="avatar-section">
                        <div class="avatar-wrapper">
                            @if(Auth::user()->role == 'admin')
                                <img src="{{ asset('storage/admin/' . Auth::user()->foto) }}" 
                                     alt="Avatar"
                                     class="avatar-image"
                                     id="avatarPreview">
                                <div class="avatar-badge">
                                    <i class="bx bx-shield"></i>
                                </div>
                            @elseif(Auth::user()->role == 'petugas')
                                <img src="{{ asset('storage/petugas/' . Auth::user()->foto) }}" 
                                     alt="Foto Petugas"
                                     class="avatar-image"
                                     id="avatarPreview">
                                <div class="avatar-badge">
                                    <i class="bx bx-briefcase"></i>
                                </div>
                            @else
                                <img src="{{ asset('storage/user/' . Auth::user()->foto) }}" 
                                     alt="Foto User"
                                     class="avatar-image"
                                     id="avatarPreview">
                                <div class="avatar-badge">
                                    <i class="bx bx-user"></i>
                                </div>
                            @endif
                        </div>

                        <h5 class="mb-2">{{ Auth::user()->name }}</h5>
                        <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>

                        <div class="custom-file-upload">
                            <input type="file" name="photo" id="photoInput" accept="image/*" class="@error('photo') is-invalid @enderror">
                            <label for="photoInput" class="file-upload-label">
                                <i class="bx bx-cloud-upload"></i>
                                <span>Pilih Foto Baru</span>
                            </label>
                            <div class="file-name-display" id="fileName">Belum ada file dipilih</div>
                            @error('photo')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="form-section">
                        <!-- Info Cards -->
                        <div class="info-cards">
                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bx bx-envelope"></i>
                                </div>
                                <div class="info-card-label">Email</div>
                                <div class="info-card-value">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bx bx-calendar"></i>
                                </div>
                                <div class="info-card-label">Bergabung</div>
                                <div class="info-card-value">{{ Auth::user()->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bx bx-time"></i>
                                </div>
                                <div class="info-card-label">Terakhir Update</div>
                                <div class="info-card-value">{{ Auth::user()->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <!-- Stats Section (if user) -->
                        @if(Auth::user()->role == 'user')
                            <div class="stats-section">
                                <h6 class="stats-title">
                                    <i class="bx bx-bar-chart"></i>
                                    Statistik Aktivitas
                                </h6>
                                <div class="info-cards">
                                    <div class="info-card">
                                        <div class="info-card-icon">
                                            <i class="bx bx-book"></i>
                                        </div>
                                        <div class="info-card-label">Buku Dipinjam</div>
                                        <div class="info-card-value">0</div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-card-icon">
                                            <i class="bx bx-history"></i>
                                        </div>
                                        <div class="info-card-label">Riwayat</div>
                                        <div class="info-card-value">0</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Form Fields -->
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="bx bx-user"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                <i class="bx bx-envelope"></i>
                                Alamat Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <button type="button" class="btn btn-custom-secondary" onclick="window.location.reload()">
                                <i class="bx bx-reset"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-custom-primary">
                                <i class="bx bx-save"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Preview image sebelum upload
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileNameDisplay = document.getElementById('fileName');
        const avatarPreview = document.getElementById('avatarPreview');
        
        if (file) {
            // Update file name display
            fileNameDisplay.textContent = file.name;
            fileNameDisplay.style.color = '#667eea';
            fileNameDisplay.style.fontWeight = '600';
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                
                // Add animation
                avatarPreview.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    avatarPreview.style.transform = 'scale(1)';
                }, 200);
            }
            reader.readAsDataURL(file);
        } else {
            fileNameDisplay.textContent = 'Belum ada file dipilih';
            fileNameDisplay.style.color = '#6c757d';
            fileNameDisplay.style.fontWeight = '400';
        }
    });

    // Form validation
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        
        if (nameInput.value.trim() === '' || emailInput.value.trim() === '') {
            e.preventDefault();
            
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nama dan Email tidak boleh kosong!',
                confirmButtonColor: '#667eea'
            });
        }
    });

    // Auto-hide success alert
    @if(session('success'))
        setTimeout(function() {
            const alert = document.querySelector('.alert-custom-success');
            if (alert) {
                alert.style.animation = 'slideOutUp 0.5s ease';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }, 5000);
    @endif
</script>

<style>
    @keyframes slideOutUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
</style>
@endsection