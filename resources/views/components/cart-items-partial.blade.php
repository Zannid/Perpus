@if($cartItems->count() > 0)
    <div class="cart-dropdown-body">
        @foreach($cartItems as $item)
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="{{ $item->buku->foto ? asset('storage/buku/' . $item->buku->foto) : asset('assetsf/img/default-book.jpg') }}" 
                         alt="{{ $item->buku->judul }}">
                </div>
                <div class="cart-item-details">
                    <div class="cart-item-title">{{ Str::limit($item->buku->judul, 40) }}</div>
                    <div class="cart-item-code">{{ $item->buku->kode_buku }}</div>
                    <div class="cart-item-quantity">
                        <span class="quantity-label">Qty:</span>
                        <div class="quantity-controls">
                            <button type="button" 
                                    class="qty-btn qty-btn-minus" 
                                    data-cart-id="{{ $item->id }}"
                                    data-current-qty="{{ $item->jumlah }}"
                                    {{ $item->jumlah <= 1 ? 'disabled' : '' }}>
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="qty-value">{{ $item->jumlah }}</span>
                            <button type="button" 
                                    class="qty-btn qty-btn-plus" 
                                    data-cart-id="{{ $item->id }}"
                                    data-current-qty="{{ $item->jumlah }}"
                                    data-max-stock="{{ $item->buku->stok }}"
                                    {{ $item->jumlah >= $item->buku->stok ? 'disabled' : '' }}>
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <p class="cart-item-stock">Stok tersedia: {{ $item->buku->stok }}</p>
                </div>
                <div class="cart-item-remove">
                    <button type="button" 
                            class="btn-remove" 
                            data-cart-id="{{ $item->id }}"
                            title="Hapus">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="cart-dropdown-footer">
        <div class="cart-total">
            <span>Total Buku:</span>
            <strong>{{ $totalQuantity }} Buku</strong>
        </div>
        <div class="cart-actions">
            <a href="{{ route('keranjang.index') }}" class="btn-cart btn-view">
                <i class="bi bi-cart3"></i>
                Lihat Keranjang
            </a>
            <form action="{{ route('keranjang.submit') }}" method="POST">
                @csrf
                <button type="submit" class="btn-cart btn-submit">
                    <i class="bi bi-send"></i>
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>
@else
    <div class="cart-empty">
        <i class="bi bi-cart-x"></i>
        <p>Keranjang Anda masih kosong</p>
        <a href="{{ route('katalog') }}" class="btn-browse" onclick="closeCartDropdown()">
            <i class="bi bi-book"></i>
            Jelajahi Katalog
        </a>
    </div>
@endif