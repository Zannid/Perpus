<script>
document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const sidebar = document.getElementById("layout-menu");
    const sidebarToggleBtns = document.querySelectorAll(".layout-menu-toggle");

    if (sidebar && sidebarToggleBtns.length > 0) {
        // Restore state dari localStorage
        if (localStorage.getItem("sidebarState") === "collapsed") {
            body.classList.add("layout-menu-collapsed");
        }

        // Toggle semua tombol yang ada
        sidebarToggleBtns.forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                body.classList.toggle("layout-menu-collapsed");
                localStorage.setItem(
                    "sidebarState",
                    body.classList.contains("layout-menu-collapsed")
                        ? "collapsed"
                        : "expanded"
                );
            });
        });
    }
});
</script>


<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo E-Perpustakaan" width="110" height="110">
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1 pt-3">
    <!-- Dashboard -->
    <li class="menu-item {{ Request::is('home') ? 'active' : '' }}">
      <a href="{{ route('home') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>

    <!-- Menu Utama -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Menu Utama</span>
    </li>

    <li class="menu-item {{ Request::is('buku*') ? 'active' : '' }}">
      <a href="{{ route('buku.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book"></i>
        <div data-i18n="Basic">Buku</div>
      </a>
    </li>

    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
      <li class="menu-item {{ Request::is('kategori*') ? 'active' : '' }}">
        <a href="{{ route('kategori.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-category"></i>
          <div data-i18n="Basic">Kategori</div>
        </a>
      </li>

      <li class="menu-item {{ Request::is('lokasi*') ? 'active' : '' }}">
        <a href="{{ route('lokasi.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-map"></i>
          <div data-i18n="Basic">Lokasi</div>
        </a>
      </li>

      <li class="menu-item {{ Request::is('barangmasuk*') ? 'active' : '' }}">
        <a href="{{ route('barangmasuk.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-log-in"></i>
          <div data-i18n="Basic">Barang Masuk</div>
        </a>
      </li>

      <li class="menu-item {{ Request::is('barangkeluar*') ? 'active' : '' }}">
        <a href="{{ route('barangkeluar.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-log-out"></i>
          <div data-i18n="Basic">Barang Keluar</div>
        </a>
      </li>
    @endif

    <li class="menu-item {{ Request::is('peminjaman*') ? 'active' : '' }}">
      <a href="{{ route('peminjaman.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book-reader"></i>
        <div data-i18n="Basic">Peminjaman</div>
      </a>
    </li>

    <li class="menu-item {{ Request::is('pengembalian*') ? 'active' : '' }}">
      <a href="{{ route('pengembalian.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-refresh"></i>
        <div data-i18n="Basic">Pengembalian</div>
      </a>
    </li>

    @if(Auth::user()->role == 'petugas' || Auth::user()->role == 'admin')
<li class="menu-item {{ request()->routeIs('acc') ? 'active' : '' }}">
    <a href="{{ route('petugas.acc') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-check-circle"></i>
        <div data-i18n="Basic">Pengajuan</div>
    </a>
</li>


    @endif

    @if(Auth::user()->role == 'admin')
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Pengguna</span>
      </li>

      <li class="menu-item {{ Request::is('admin*') ? 'active' : '' }}">
        <a href="{{ route('admin.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
          <div data-i18n="Basic">Admin</div>
        </a>
      </li>
      <li class="menu-item {{ Request::is('user*') ? 'active' : '' }}">
        <a href="{{ route('user.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Basic">User</div>
        </a>
      </li>

      <li class="menu-item {{ request()->routeIs('petugas.*') ? 'active' : '' }}">
          <a href="{{ route('petugas.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user-check"></i>
              <div data-i18n="Basic">Petugas</div>
          </a>
      </li>
    @endif
  </ul>
</aside>
