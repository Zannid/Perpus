<style>
/* Hilangkan caret/arrow dari dropdown */
.navbar .dropdown-toggle::after {
    display: none !important;
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
                <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-sm"></i>
                    @if(!empty($jumlahNotifikasi) && $jumlahNotifikasi > 0)
                        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                            {{ $jumlahNotifikasi }}
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="dropdown-header fw-semibold">
                        Notifikasi Peminjaman
                    </li>

                    @forelse($notifikasiPeminjaman ?? [] as $notif)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between" href="{{ route('petugas.acc', $notif->id) }}">
                                <div>
                                    <span class="fw-semibold d-block">{{ $notif->user->name }}</span>
                                    <small class="text-muted">
                                        Mengajukan peminjaman: {{ $notif->buku->judul }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </a>
                        </li>
                    @empty
                        <li>
                            <span class="dropdown-item text-muted text-center">
                                Tidak ada pengajuan baru
                            </span>
                        </li>
                    @endforelse

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center fw-semibold" href="{{ route('peminjaman.index') }}">
                            Lihat Semua
                        </a>
                    </li>
                </ul>
            </li>
            @endif
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
    const toggleBtn = document.querySelector('.layout-menu-toggle');
    const layoutMenu = document.getElementById('layout-menu'); // pastikan sidebar punya id="layout-menu"

    if (toggleBtn && layoutMenu) {
        toggleBtn.addEventListener('click', function () {
            layoutMenu.classList.toggle('collapsed');
            
            // Simpan state di localStorage supaya tetap collapsed walaupun refresh
            localStorage.setItem('sidebarCollapsed',
                layoutMenu.classList.contains('collapsed') ? 'true' : 'false'
            );
        });

        // Saat halaman reload, cek state sebelumnya
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            layoutMenu.classList.add('collapsed');
        }
    }
});
</script>
