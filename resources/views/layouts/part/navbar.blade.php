<style>
/* Hilangkan caret/arrow dari dropdown */
.navbar .dropdown-toggle::after {
    display: none !important;
}

/* Notifikasi belum dibaca */
.notif-unread {
    background: #eef5ff !important;
    font-weight: 600;
}

/* Animasi bell bergetar */
.bell-shake {
    animation: shake 0.5s ease-in-out 1;
}

@keyframes shake {
    0% { transform: rotate(0); }
    25% { transform: rotate(-20deg); }
    50% { transform: rotate(20deg); }
    75% { transform: rotate(-10deg); }
    100% { transform: rotate(0); }
}

/* Animasi badge muncul */
.badge-animate {
    animation: pop 0.3s ease-out;
}

@keyframes pop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}

/* Animasi fade out untuk notifikasi yang dihapus */
.notif-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; transform: translateX(0); }
    100% { opacity: 0; transform: translateX(20px); }
}
</style>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Notifikasi -->
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
    <li class="nav-item dropdown me-3">
        <a class="nav-link dropdown-toggle position-relative" 
            href="#" data-bs-toggle="dropdown" 
            id="notifBell">

            <i class="bx bx-bell bx-sm"></i>

            @if(!empty($jumlahNotifikasi) && $jumlahNotifikasi > 0)
            <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" 
                id="notifBadge">
                {{ $jumlahNotifikasi }}
            </span>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header fw-semibold d-flex justify-content-between align-items-center">
                <span>Notifikasi Peminjaman</span>
                @if($jumlahNotifikasi > 0)
                <a href="{{ route('peminjaman.markAllRead') }}" class="badge bg-primary text-decoration-none">
                    Tandai Semua
                </a>
                @endif
            </li>

            <li><hr class="dropdown-divider m-0"></li>

            <div id="notifContainer">
                @forelse($notifikasiPeminjaman as $notif)
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-start notif-item {{ $notif->status_baca ? '' : 'notif-unread' }}"
                        href="{{ route('petugas.acc', $notif->id) }}"
                        data-id="{{ $notif->id }}"
                    >
                        <div>
                            <span class="fw-semibold d-block">
                                {{ $notif->user->name }}
                                @if(!$notif->status_baca)
                                <span class="badge bg-primary ms-2 notif-new">BARU</span>
                                @endif
                            </span>
                            <small class="text-muted">
                                Mengajukan peminjaman: {{ Str::limit($notif->buku->judul, 30) }}
                            </small>
                            <br>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                </li>
                @empty
                <li>
                    <div class="dropdown-item text-center text-muted py-3">
                        Tidak ada notifikasi baru
                    </div>
                </li>
                @endforelse
            </div>
        </ul>
    </li>
    @endif

    <!-- ========================================== -->
    <!-- BELL 2: Notifikasi untuk USER (Peminjam) -->
    <!-- (Notifikasi ACC/Tolak dari Admin) -->
    <!-- ========================================== -->
    @if(Auth::user()->role == 'user' || Auth::user()->role == 'member')
    <li class="nav-item dropdown me-3">
        <a class="nav-link position-relative" href="#" id="userNotificationDropdown" 
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-bell bx-sm"></i>
            
            @php
                $unreadCount = auth()->user()->notifications()->unread()->count();
            @endphp
            
            @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow" 
            aria-labelledby="userNotificationDropdown" 
            style="min-width: 350px; max-height: 400px; overflow-y: auto;">
            
            <li class="dropdown-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Notifikasi Saya</span>
                @if($unreadCount > 0)
                <a href="{{ route('notifications.markAllRead') }}" 
                    class="text-decoration-none small badge bg-primary">
                    Tandai Semua
                </a>
                @endif
            </li>
            
            <li><hr class="dropdown-divider"></li>

            @php
                $notifications = auth()->user()->notifications()
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
            @endphp

            @forelse($notifications as $notification)
            <li>
                <a class="dropdown-item {{ !$notification->is_read ? 'bg-light' : '' }} py-3" 
                    href="{{ route('notifications.read', $notification->id) }}">
                    
                    <div class="d-flex align-items-start">
                        <div class="me-2">
                            @if($notification->type == 'success')
                                <i class="bx bx-check-circle text-success fs-4"></i>
                            @elseif($notification->type == 'danger')
                                <i class="bx bx-x-circle text-danger fs-4"></i>
                            @elseif($notification->type == 'warning')
                                <i class="bx bx-error text-warning fs-4"></i>
                            @else
                                <i class="bx bx-info-circle text-info fs-4"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="mb-1 small fw-bold">{{ $notification->title }}</h6>
                            <p class="mb-1 small text-muted">{{ Str::limit($notification->message, 80) }}</p>
                            <small class="text-muted">
                                <i class="bx bx-time-five"></i> 
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>

                        @if(!$notification->is_read)
                        <span class="badge bg-primary rounded-pill ms-2">Baru</span>
                        @endif
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            @empty
            <li>
                <div class="text-center py-4 text-muted">
                    <i class="bx bx-bell-off fs-1 d-block mb-2"></i>
                    <p class="mb-0">Tidak ada notifikasi</p>
                </div>
            </li>
            @endforelse

            @if($notifications->count() > 0)
            <li>
                <a class="dropdown-item text-center text-primary py-2" 
                    href="{{ route('notifications.index') }}">
                    Lihat Semua Notifikasi
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    <!-- Profile User (tetap di sebelah kanan) -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <!-- kode profile dropdown Anda -->
    </li>

            <!-- Nama User -->
            <li class="nav-item lh-1 me-3">
                Hi, {{ Auth::user()->name }}
            </li>

            <!-- Dropdown User -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(Auth::user()->role == 'admin')
                            <img src="{{ asset('storage/admin/' . Auth::user()->foto) }}" class="w-px-40 h-auto rounded-circle" />
                        @elseif(Auth::user()->role == 'petugas')
                            <img src="{{ asset('storage/petugas/' . Auth::user()->foto) }}" class="w-px-40 h-auto rounded-circle" />
                        @else
                            <img src="{{ asset('storage/user/' . Auth::user()->foto) }}" class="w-px-40 h-auto rounded-circle" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="bx bx-user me-2"></i> My Profile
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i> Log Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== SIDEBAR TOGGLE =====
    const toggleBtn = document.querySelector('.layout-menu-toggle');
    const layoutMenu = document.getElementById('layout-menu');

    if (toggleBtn && layoutMenu) {
        toggleBtn.addEventListener('click', function () {
            layoutMenu.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed',
                layoutMenu.classList.contains('collapsed') ? 'true' : 'false'
            );
        });

        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            layoutMenu.classList.add('collapsed');
        }
    }

    // ===== NOTIFIKASI SYSTEM =====
    const badge = document.getElementById('notifBadge');
    const bell = document.getElementById('notifBell');

    // Animasi badge dan bell saat load
    if (badge) {
        badge.classList.add('badge-animate');
    }

    if (badge && bell) {
        bell.classList.add('bell-shake');
        setTimeout(() => bell.classList.remove('bell-shake'), 500);
    }

    // Fungsi untuk update badge count
    function updateBadgeCount() {
        const unreadCount = document.querySelectorAll('.notif-item.notif-unread').length;
        
        if (unreadCount > 0) {
            if (!badge) {
                // Buat badge baru jika belum ada
                const newBadge = document.createElement('span');
                newBadge.id = 'notifBadge';
                newBadge.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
                newBadge.textContent = unreadCount;
                bell.querySelector('i').parentElement.appendChild(newBadge);
            } else {
                badge.textContent = unreadCount;
            }
        } else {
            // Hapus badge jika count = 0
            if (badge) {
                badge.classList.add('notif-fade-out');
                setTimeout(() => badge.remove(), 300);
            }
        }
    }

    // Event listener untuk setiap notifikasi
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault(); // Cegah navigasi default
            
            const id = this.dataset.id;
            const isUnread = this.classList.contains('notif-unread');
            const href = this.getAttribute('href');

            if (!isUnread) {
                // Jika sudah dibaca, langsung redirect
                window.location.href = href;
                return;
            }

            // Tandai sebagai dibaca via AJAX
            fetch(`/peminjaman/notif/read/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ubah tampilan notifikasi
                    this.classList.remove('notif-unread');
                    const badgeNew = this.querySelector('.notif-new');
                    if (badgeNew) {
                        badgeNew.remove();
                    }

                    // Update badge count
                    updateBadgeCount();

                    // Redirect ke halaman
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                } else {
                    console.error('Gagal menandai notifikasi:', data.message);
                    window.location.href = href;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = href;
            });
        });
    });
});
</script>