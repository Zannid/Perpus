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

        <div class="header-actions d-flex align-items-center gap-3">
            <!-- Cart Icon with Dropdown -->
            @if(Auth::check())
                @php
                    $cartItems = \App\Models\Keranjang::with('buku')
                        ->where('user_id', Auth::id())
                        ->get();
                    $distinctBooks = $cartItems->count();
                    $totalQuantity = $cartItems->sum('jumlah');
                @endphp

                <div class="cart-dropdown-wrapper">
                    <button type="button" class="cart-btn" id="cartToggle">
                        <i class="bi bi-cart3"></i>
                        @if($distinctBooks > 0)
                            <span class="cart-badge">{{ $distinctBooks }}</span>
                        @else
                            <span class="cart-badge" style="display: none;">0</span>
                        @endif
                    </button>

                    <!-- Cart Dropdown -->
                    <div class="cart-dropdown" id="cartDropdown">
                        <div class="cart-dropdown-header">
                            <h5>
                                <i class="bi bi-cart-check"></i>
                                Keranjang Buku
                            </h5>
                            <span class="cart-count">{{ $distinctBooks }} Buku</span>
                        </div>
                        <div id="cartContentWrapper">
                            @include('components.cart-items-partial', ['cartItems' => $cartItems, 'totalQuantity' => $totalQuantity])
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login/Dashboard Button -->
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
}

/* ============================================================
   CART BUTTON
   ============================================================ */
.cart-dropdown-wrapper {
  position: relative;
}

.cart-btn {
  position: relative;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  border: 2px solid rgba(102, 126, 234, 0.2);
  background: rgba(102, 126, 234, 0.1);
  color: #667eea;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 20px;
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
  top: -5px;
  right: -5px;
  background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;
  box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* ============================================================
   CART DROPDOWN
   ============================================================ */
.cart-dropdown {
  position: absolute;
  top: calc(100% + 15px);
  right: 0;
  width: 420px;
  max-height: 600px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
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

.cart-dropdown::before {
  content: '';
  position: absolute;
  top: -8px;
  right: 20px;
  width: 16px;
  height: 16px;
  background: #fff;
  transform: rotate(45deg);
  border-left: 1px solid rgba(102, 126, 234, 0.1);
  border-top: 1px solid rgba(102, 126, 234, 0.1);
}

/* ============================================================
   CART DROPDOWN HEADER
   ============================================================ */
.cart-dropdown-header {
  padding: 15px 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.cart-dropdown-header h5 {
  font-size: 16px;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cart-count {
  background: rgba(255, 255, 255, 0.25);
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

/* ============================================================
   CART DROPDOWN BODY
   ============================================================ */
.cart-dropdown-body {
  max-height: 280px;
  overflow-y: auto;
  padding: 15px;
  padding-bottom: 10px;
  flex: 1 1 auto;
  min-height: 0;
}

.cart-dropdown-body::-webkit-scrollbar {
  width: 6px;
}

.cart-dropdown-body::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.cart-dropdown-body::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 10px;
}

/* ============================================================
   CART ITEM
   ============================================================ */
.cart-item {
  display: flex;
  gap: 15px;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 15px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.cart-item:hover {
  background: #fff;
  border-color: #667eea;
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.cart-item-image {
  width: 70px;
  height: 100px;
  border-radius: 10px;
  overflow: hidden;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
  font-size: 14px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 5px;
  line-height: 1.3;
}

.cart-item-code {
  font-size: 12px;
  color: #667eea;
  margin-bottom: 8px;
  font-weight: 600;
}

.cart-item-quantity {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 5px;
}

.quantity-label {
  font-size: 12px;
  color: #666;
  font-weight: 600;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fff;
  padding: 4px 8px;
  border-radius: 8px;
  border: 2px solid #e0e6ed;
}

.qty-btn {
  width: 24px;
  height: 24px;
  border: none;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 12px;
  padding: 0;
}

.qty-btn:hover:not(:disabled) {
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.qty-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.qty-value {
  font-size: 13px;
  font-weight: 700;
  color: #2c3e50;
  min-width: 20px;
  text-align: center;
}

.cart-item-stock {
  font-size: 11px;
  color: #999;
  margin: 0;
}

.cart-item-remove {
  display: flex;
  align-items: flex-start;
}

.btn-remove {
  width: 28px;
  height: 28px;
  border: none;
  background: rgba(255, 71, 87, 0.1);
  color: #ff4757;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 12px;
  padding: 0;
}

.btn-remove:hover {
  background: #ff4757;
  color: #fff;
  transform: rotate(90deg);
}

/* Loading State */
.qty-btn.loading {
  pointer-events: none;
  opacity: 0.6;
}

.qty-btn.loading::after {
  content: '';
  width: 12px;
  height: 12px;
  border: 2px solid #fff;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* ============================================================
   CART EMPTY STATE
   ============================================================ */
.cart-empty {
  text-align: center;
  padding: 50px 20px;
}

.cart-empty i {
  font-size: 60px;
  color: #e0e6ed;
  margin-bottom: 15px;
}

.cart-empty p {
  font-size: 16px;
  color: #999;
  margin-bottom: 20px;
}

.btn-browse {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
}

.btn-browse:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
  color: #fff;
}

/* ============================================================
   CART DROPDOWN FOOTER
   ============================================================ */
.cart-dropdown-footer {
  padding: 15px 20px;
  background: #f8f9fa;
  border-top: 2px solid #e0e6ed;
  flex-shrink: 0;
}

.cart-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 14px;
  color: #2c3e50;
}

.cart-total strong {
  font-size: 16px;
  color: #667eea;
  font-weight: 700;
}

.cart-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.btn-cart {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 16px;
  border-radius: 12px;
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

.btn-view:hover {
  background: #667eea;
  color: #fff;
}

.btn-submit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
  .cart-dropdown {
    width: 360px;
    right: -20px;
  }
  .cart-dropdown::before {
    right: 35px;
  }
}

@media (max-width: 576px) {
  .cart-dropdown {
    position: fixed;
    top: 70px !important;
    left: 15px;
    right: 15px;
    width: auto;
    max-height: calc(100vh - 90px);
    display: flex;
    flex-direction: column;
  }
  .cart-dropdown::before {
    display: none;
  }
  .cart-btn {
    width: 44px;
    height: 44px;
    font-size: 18px;
  }
  .cart-badge {
    top: -3px;
    right: -3px;
    min-width: 18px;
    height: 18px;
    font-size: 10px;
  }
  .cart-dropdown-body {
    max-height: none;
    flex: 1;
    overflow-y: auto;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartToggle = document.getElementById('cartToggle');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartWrapper = document.querySelector('.cart-dropdown-wrapper');

    // Toggle dropdown saat tombol cart diklik
    if (cartToggle) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleCartDropdown();
        });
    }

    // Fungsi untuk toggle dropdown
    function toggleCartDropdown() {
        if (cartDropdown) {
            cartDropdown.classList.toggle('show');
        }
    }

    // Fungsi untuk menutup dropdown
    window.closeCartDropdown = function() {
        if (cartDropdown) {
            cartDropdown.classList.remove('show');
        }
    }

    // Menutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        if (cartWrapper && !cartWrapper.contains(event.target)) {
            closeCartDropdown();
        }
    });

    // Mencegah dropdown tertutup saat klik di dalam dropdown
    if (cartDropdown) {
        cartDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Menutup dropdown dengan tombol ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCartDropdown();
        }
    });

    // Event delegation untuk tombol quantity di dalam dropdown
    setupCartEventListeners();
});

// Setup event listeners untuk cart items (bisa dipanggil ulang setelah update)
function setupCartEventListeners() {
    const cartContentWrapper = document.getElementById('cartContentWrapper');
    
    if (!cartContentWrapper) return;

    // Event delegation untuk semua tombol di dalam cart
    cartContentWrapper.addEventListener('click', function(e) {
        const target = e.target.closest('button');
        
        if (!target) return;

        // Tombol Tambah Quantity
        if (target.classList.contains('qty-btn-plus')) {
            e.preventDefault();
            const cartId = target.dataset.cartId;
            const currentQty = parseInt(target.dataset.currentQty);
            const maxStock = parseInt(target.dataset.maxStock);
            
            if (currentQty < maxStock) {
                updateCartQuantity(cartId, currentQty + 1, target);
            }
        }

        // Tombol Kurang Quantity
        if (target.classList.contains('qty-btn-minus')) {
            e.preventDefault();
            const cartId = target.dataset.cartId;
            const currentQty = parseInt(target.dataset.currentQty);
            
            if (currentQty > 1) {
                updateCartQuantity(cartId, currentQty - 1, target);
            }
        }

        // Tombol Remove
        if (target.classList.contains('btn-remove')) {
            e.preventDefault();
            const cartId = target.dataset.cartId;
            removeCartItem(cartId, target);
        }
    });
}

// Fungsi untuk update quantity via AJAX
function updateCartQuantity(cartId, newQuantity, button) {
    // Tambahkan loading state
    button.classList.add('loading');
    button.disabled = true;

    fetch('/cart/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        button.classList.remove('loading');
        button.disabled = false;

        if (data.success) {
            // Update cart display
            refreshCartDisplay(data);
        } else {
            showNotification(data.message || 'Gagal mengupdate quantity', 'error');
        }
    })
    .catch(error => {
        button.classList.remove('loading');
        button.disabled = false;
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Fungsi untuk remove item via AJAX
function removeCartItem(cartId, button) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        return;
    }

    button.classList.add('loading');
    button.disabled = true;

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            cart_id: cartId
        })
    })
    .then(response => response.json())
    .then(data => {
        button.classList.remove('loading');
        button.disabled = false;

        if (data.success) {
            // Update cart display
            refreshCartDisplay(data);
            showNotification(data.message || 'Item berhasil dihapus', 'success');
        } else {
            showNotification(data.message || 'Gagal menghapus item', 'error');
        }
    })
    .catch(error => {
        button.classList.remove('loading');
        button.disabled = false;
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Fungsi untuk refresh tampilan cart
function refreshCartDisplay(data) {
    // Update badge
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        if (data.totalItems > 0) {
            badge.textContent = data.totalItems;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    // Update cart count
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = data.totalItems + ' Buku';
    }

    // Update cart content
    const cartContentWrapper = document.getElementById('cartContentWrapper');
    if (cartContentWrapper && data.html) {
        cartContentWrapper.innerHTML = data.html;
        // Re-setup event listeners setelah content di-update
        setupCartEventListeners();
    }
}

/**
 * Fungsi utama untuk update cart dari halaman lain (katalog, detail buku, dll)
 * Panggil fungsi ini setelah berhasil menambah item ke keranjang
 */
window.updateCartFromOutside = function(data) {
    if (data.success) {
        // Update badge
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            if (data.totalItems > 0) {
                badge.textContent = data.totalItems;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Update cart count
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = data.totalItems + ' Buku';
        }

        // Update cart content jika ada HTML
        if (data.html) {
            const cartContentWrapper = document.getElementById('cartContentWrapper');
            if (cartContentWrapper) {
                cartContentWrapper.innerHTML = data.html;
                setupCartEventListeners();
            }
        }

        // Show notification
        showNotification(data.message || 'Buku berhasil ditambahkan ke keranjang', 'success');

        // Tampilkan dropdown sebentar
        const dropdown = document.getElementById('cartDropdown');
        if (dropdown) {
            dropdown.classList.add('show');
            setTimeout(() => {
                dropdown.classList.remove('show');
            }, 2500);
        }
    } else {
        showNotification(data.message || 'Gagal menambahkan ke keranjang', 'error');
    }
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'success') {
    // Jika menggunakan SweetAlert
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
        // Fallback: gunakan alert biasa
        alert(message);
    }
}

// Untuk kompatibilitas dengan kode lama
window.updateCartAjax = window.updateCartFromOutside;
</script>