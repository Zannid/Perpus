<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="{{ Route('welcome')}}" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">E-Perpustakaan</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ Route('welcome')}}" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="{{ route('katalog') }}">Katalog</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <div class="header-actions d-flex align-items-center gap-2">
            @if(Auth::check())
                @php
                    $cartItems = \App\Models\Keranjang::with('buku')
                        ->where('user_id', Auth::id())
                        ->get();
                    $distinctBooks = $cartItems->count();
                    $totalQuantity = $cartItems->sum('jumlah');
                @endphp

                <div class="cart-dropdown-wrapper">
                    <button type="button" class="cart-btn" id="cartToggle" aria-label="Keranjang Buku">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-badge" style="{{ $distinctBooks > 0 ? '' : 'display:none;' }}">
                            {{ $distinctBooks }}
                        </span>
                    </button>

                    <!-- Cart Dropdown -->
                    <div class="cart-dropdown" id="cartDropdown">
                        <div class="cart-dropdown-header">
                            <h5><i class="bi bi-cart-check"></i> Keranjang Buku</h5>
                            <span class="cart-count">{{ $distinctBooks }} Buku</span>
                        </div>
                        <div id="cartContentWrapper">
                            @include('components.cart-items-partial', [
                                'cartItems'     => $cartItems,
                                'totalQuantity' => $totalQuantity
                            ])
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::check())
                <a href="{{ url('/dashboard') }}" class="btn-getstarted">Dashboard</a>
            @else
                <a href="{{ url('login') }}" class="btn-getstarted">Login</a>
            @endif
        </div>
    </div>
</header>

<style>
/* ============================================================
   HEADER ACTIONS
   ============================================================ */
.header-actions {
  position: relative;
  z-index: 1000;
  flex-shrink: 0;
}

/* Pastikan sitename tidak meluber di HP */
.sitename {
  font-size: clamp(0.95rem, 3vw, 1.4rem);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 160px;
}

@media (max-width: 400px) {
  .sitename {
    max-width: 120px;
    font-size: 0.9rem;
  }
}

/* ============================================================
   CART BUTTON
   ============================================================ */
.cart-dropdown-wrapper {
  position: relative;
}

.cart-btn {
  position: relative;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: 2px solid rgba(102, 126, 234, 0.2);
  background: rgba(102, 126, 234, 0.1);
  color: #667eea;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 18px;
  flex-shrink: 0;
}

.cart-btn:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border-color: transparent;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.cart-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  min-width: 18px;
  height: 18px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
  box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50%       { transform: scale(1.12); }
}

/* ============================================================
   CART DROPDOWN — desktop default
   ============================================================ */
.cart-dropdown {
  position: absolute;
  top: calc(100% + 14px);
  right: 0;
  width: 400px;
  max-height: 580px;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.18);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
  z-index: 9999;
  overflow: hidden;
  border: 1px solid rgba(102, 126, 234, 0.1);
  pointer-events: none;
  display: flex;
  flex-direction: column;
}

.cart-dropdown.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
  pointer-events: auto;
}

/* Arrow pointer — hidden on mobile */
.cart-dropdown::before {
  content: '';
  position: absolute;
  top: -7px;
  right: 18px;
  width: 14px;
  height: 14px;
  background: #fff;
  transform: rotate(45deg);
  border-left: 1px solid rgba(102, 126, 234, 0.1);
  border-top:  1px solid rgba(102, 126, 234, 0.1);
}

/* ============================================================
   CART DROPDOWN HEADER
   ============================================================ */
.cart-dropdown-header {
  padding: 14px 18px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.cart-dropdown-header h5 {
  font-size: 15px;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 7px;
}

.cart-count {
  background: rgba(255,255,255,0.25);
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  backdrop-filter: blur(10px);
  white-space: nowrap;
}

/* ============================================================
   CART DROPDOWN BODY
   ============================================================ */
.cart-dropdown-body {
  max-height: 270px;
  overflow-y: auto;
  padding: 12px;
  flex: 1 1 auto;
  min-height: 0;
}

.cart-dropdown-body::-webkit-scrollbar       { width: 5px; }
.cart-dropdown-body::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.cart-dropdown-body::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 10px;
}

/* ============================================================
   CART ITEM
   ============================================================ */
.cart-item {
  display: flex;
  gap: 12px;
  padding: 10px;
  background: #f8f9fa;
  border-radius: 12px;
  margin-bottom: 9px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.cart-item:hover {
  background: #fff;
  border-color: #667eea;
  box-shadow: 0 4px 12px rgba(102,126,234,0.1);
}

.cart-item-image {
  width: 58px;
  height: 84px;
  border-radius: 8px;
  overflow: hidden;
  flex-shrink: 0;
  box-shadow: 0 3px 10px rgba(0,0,0,0.12);
}

.cart-item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cart-item-details {
  flex: 1;
  min-width: 0;
}

.cart-item-title {
  font-size: 13px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 4px;
  line-height: 1.3;
  word-break: break-word;
}

.cart-item-code {
  font-size: 11px;
  color: #667eea;
  margin-bottom: 7px;
  font-weight: 600;
}

.cart-item-quantity {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
  flex-wrap: wrap;
}

.quantity-label {
  font-size: 11px;
  color: #666;
  font-weight: 600;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #fff;
  padding: 3px 7px;
  border-radius: 7px;
  border: 2px solid #e0e6ed;
}

.qty-btn {
  width: 22px;
  height: 22px;
  border: none;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 11px;
  padding: 0;
}

.qty-btn:hover:not(:disabled) {
  transform: scale(1.1);
  box-shadow: 0 3px 10px rgba(102,126,234,0.3);
}

.qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.qty-value {
  font-size: 12px;
  font-weight: 700;
  color: #2c3e50;
  min-width: 18px;
  text-align: center;
}

.cart-item-stock {
  font-size: 10px;
  color: #999;
  margin: 0;
}

.cart-item-remove {
  display: flex;
  align-items: flex-start;
  flex-shrink: 0;
}

.btn-remove {
  width: 26px;
  height: 26px;
  border: none;
  background: rgba(255,71,87,0.1);
  color: #ff4757;
  border-radius: 7px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 11px;
  padding: 0;
}

.btn-remove:hover {
  background: #ff4757;
  color: #fff;
  transform: rotate(90deg);
}

/* Loading */
.qty-btn.loading { pointer-events: none; opacity: 0.6; }
.qty-btn.loading::after {
  content: '';
  width: 10px;
  height: 10px;
  border: 2px solid #fff;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ============================================================
   EMPTY STATE
   ============================================================ */
.cart-empty {
  text-align: center;
  padding: 40px 20px;
}

.cart-empty i {
  font-size: 52px;
  color: #e0e6ed;
  margin-bottom: 12px;
}

.cart-empty p {
  font-size: 15px;
  color: #999;
  margin-bottom: 18px;
}

.btn-browse {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 22px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  font-size: 13px;
  transition: all 0.3s ease;
}

.btn-browse:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102,126,234,0.3);
  color: #fff;
}

/* ============================================================
   CART DROPDOWN FOOTER
   ============================================================ */
.cart-dropdown-footer {
  padding: 13px 18px;
  background: #f8f9fa;
  border-top: 2px solid #e0e6ed;
  flex-shrink: 0;
}

.cart-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  font-size: 13px;
  color: #2c3e50;
}

.cart-total strong {
  font-size: 15px;
  color: #667eea;
  font-weight: 700;
}

.cart-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.btn-cart {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  padding: 9px 14px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
}

.btn-view {
  background: #fff;
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-view:hover { background: #667eea; color: #fff; }

.btn-submit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  box-shadow: 0 6px 18px rgba(102,126,234,0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 26px rgba(102,126,234,0.4);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */

/* Tablet */
@media (max-width: 768px) {
  .cart-dropdown {
    width: 340px;
    right: -10px;
  }
  .cart-dropdown::before { right: 26px; }
}

/* Mobile — full-width bottom sheet style */
@media (max-width: 575.98px) {
  .cart-dropdown {
    position: fixed;
    /* turun tepat di bawah header, sesuaikan angka 64px dengan tinggi header Anda */
    top: 64px !important;
    left: 10px;
    right: 10px;
    width: auto !important;
    max-height: calc(100dvh - 80px);
    border-radius: 16px;
    transform: translateY(-8px);
  }

  .cart-dropdown.show {
    transform: translateY(0);
  }

  /* Sembunyikan arrow pointer */
  .cart-dropdown::before { display: none; }

  /* Body bisa scroll bebas */
  .cart-dropdown-body {
    max-height: none;
    flex: 1 1 auto;
    overflow-y: auto;
  }

  /* Item lebih compact di HP */
  .cart-item {
    gap: 10px;
    padding: 9px;
  }

  .cart-item-image {
    width: 50px;
    height: 72px;
  }

  .cart-item-title { font-size: 12px; }
  .cart-item-code  { font-size: 10px; }

  /* Tombol Dashboard/Login lebih kecil */
  .btn-getstarted {
    font-size: 0.78rem !important;
    padding: 6px 12px !important;
  }
}

/* Sangat sempit (≤360px) */
@media (max-width: 360px) {
  .cart-dropdown {
    left: 6px;
    right: 6px;
  }

  .cart-btn {
    width: 38px;
    height: 38px;
    font-size: 16px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartToggle  = document.getElementById('cartToggle');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartWrapper  = document.querySelector('.cart-dropdown-wrapper');

    if (cartToggle) {
        cartToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            cartDropdown && cartDropdown.classList.toggle('show');
        });
    }

    window.closeCartDropdown = function () {
        cartDropdown && cartDropdown.classList.remove('show');
    };

    document.addEventListener('click', function (e) {
        if (cartWrapper && !cartWrapper.contains(e.target)) closeCartDropdown();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCartDropdown();
    });

    if (cartDropdown) {
        cartDropdown.addEventListener('click', function (e) { e.stopPropagation(); });
    }

    setupGlobalCartEventListener();
});

function setupGlobalCartEventListener() {
    document.addEventListener('click', function (e) {
        const button = e.target.closest('button');
        if (!button) return;

        const wrapper = document.getElementById('cartContentWrapper');
        if (!wrapper || !wrapper.contains(button)) return;

        if (button.classList.contains('qty-btn-plus')) {
            e.preventDefault(); e.stopPropagation();
            const qty  = parseInt(button.dataset.currentQty);
            const max  = parseInt(button.dataset.maxStock);
            if (qty < max) updateCartQuantity(button.dataset.cartId, qty + 1, button);
        }
        else if (button.classList.contains('qty-btn-minus')) {
            e.preventDefault(); e.stopPropagation();
            const qty = parseInt(button.dataset.currentQty);
            if (qty > 1) updateCartQuantity(button.dataset.cartId, qty - 1, button);
        }
        else if (button.classList.contains('btn-remove')) {
            e.preventDefault(); e.stopPropagation();
            removeCartItem(button.dataset.cartId, button);
        }
    }, true);
}

function updateCartQuantity(cartId, newQty, button) {
    button.classList.add('loading');
    button.disabled = true;

    fetch('/cart/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ cart_id: cartId, quantity: newQty })
    })
    .then(r => r.json())
    .then(data => {
        button.classList.remove('loading');
        button.disabled = false;
        if (data.success) refreshCartDisplay(data);
        else showNotification(data.message || 'Gagal mengupdate quantity', 'error');
    })
    .catch(() => {
        button.classList.remove('loading');
        button.disabled = false;
        showNotification('Terjadi kesalahan', 'error');
    });
}

function removeCartItem(cartId, button) {
    if (!confirm('Hapus item ini dari keranjang?')) return;

    button.classList.add('loading');
    button.disabled = true;

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ cart_id: cartId })
    })
    .then(r => r.json())
    .then(data => {
        button.classList.remove('loading');
        button.disabled = false;
        if (data.success) {
            refreshCartDisplay(data);
            showNotification(data.message || 'Item dihapus', 'success');
        } else {
            showNotification(data.message || 'Gagal menghapus', 'error');
        }
    })
    .catch(() => {
        button.classList.remove('loading');
        button.disabled = false;
        showNotification('Terjadi kesalahan', 'error');
    });
}

function refreshCartDisplay(data) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent    = data.totalItems;
        badge.style.display  = data.totalItems > 0 ? 'flex' : 'none';
    }

    const cartCount = document.querySelector('.cart-count');
    if (cartCount) cartCount.textContent = data.totalItems + ' Buku';

    const wrapper = document.getElementById('cartContentWrapper');
    if (wrapper && data.html) wrapper.innerHTML = data.html;
}

window.updateCartFromOutside = function (data) {
    if (data.success) {
        refreshCartDisplay(data);
        showNotification(data.message || 'Berhasil ditambahkan ke keranjang', 'success');
    } else {
        showNotification(data.message || 'Gagal menambahkan', 'error');
    }
};

window.updateCartAjax = window.updateCartFromOutside;

function showNotification(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Berhasil' : 'Gagal',
            text: message,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        alert(message);
    }
}

document.addEventListener('submit', function (e) {
    const form = e.target;
    const isCartForm = form.action.includes('/cart/add') ||
                       form.classList.contains('add-to-cart-form') ||
                       form.dataset.cartAction === 'add';
    if (!isCartForm) return;

    e.preventDefault();
    const formData = new FormData(form);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            buku_id:  parseInt(formData.get('buku_id')),
            quantity: parseInt(formData.get('quantity') || 1)
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && window.updateCartFromOutside) window.updateCartFromOutside(data);
        else if (!data.success) showNotification(data.message || 'Gagal', 'error');
    })
    .catch(() => showNotification('Terjadi kesalahan', 'error'));
}, false);

window.addToCartAjax = function (bukuId, quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ buku_id: bukuId, quantity })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && window.updateCartFromOutside) window.updateCartFromOutside(data);
        else if (!data.success) showNotification(data.message || 'Gagal', 'error');
    })
    .catch(() => showNotification('Terjadi kesalahan', 'error'));
};
</script>