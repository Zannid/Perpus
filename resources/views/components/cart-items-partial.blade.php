@if($cartItems && $cartItems->count() > 0)
    <div class="cart-dropdown-body">
        @foreach($cartItems as $item)
            <div class="cart-item" data-cart-id="{{ $item->id }}">
                <div class="cart-item-image">
                    @if($item->buku && $item->buku->foto)
                        <img src="{{ asset('storage/buku/' . $item->buku->foto) }}" alt="{{ $item->buku->judul ?? 'Buku' }}">
                    @else
                        <img src="{{ asset('assets/img/default-book.png') }}" alt="Default Book">
                    @endif
                </div>

                <div class="cart-item-details">
                    <div class="cart-item-title">{{ Str::limit($item->buku->judul ?? 'Judul Buku', 50) }}</div>
                    <div class="cart-item-code">
                        @if($item->buku && $item->buku->kode_buku)
                            Kode: {{ $item->buku->kode_buku }}
                        @else
                            Penulis: {{ $item->buku->penulis ?? '-' }}
                        @endif
                    </div>
                    
                    <div class="cart-item-quantity">
                        <span class="quantity-label">Jumlah:</span>
                        <div class="quantity-controls">
                            <button 
                                type="button" 
                                class="qty-btn qty-btn-minus"
                                data-cart-id="{{ $item->id }}"
                                data-current-qty="{{ $item->jumlah }}"
                                {{ $item->jumlah <= 1 ? 'disabled' : '' }}>
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="qty-value">{{ $item->jumlah }}</span>
                            <button 
                                type="button" 
                                class="qty-btn qty-btn-plus"
                                data-cart-id="{{ $item->id }}"
                                data-current-qty="{{ $item->jumlah }}"
                                data-max-stock="{{ $item->buku->stok ?? 0 }}"
                                {{ $item->jumlah >= ($item->buku->stok ?? 0) ? 'disabled' : '' }}>
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <p class="cart-item-stock">Stok tersedia: {{ $item->buku->stok ?? 0 }}</p>
                </div>

                <div class="cart-item-remove">
                    <button 
                        type="button" 
                        class="btn-remove"
                        data-cart-id="{{ $item->id }}"
                        title="Hapus item">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="cart-dropdown-footer">
        <div class="cart-total">
            <span>Total Item:</span>
            <strong>{{ $totalQuantity ?? $cartItems->sum('jumlah') }} Buku</strong>
        </div>
        <div class="cart-actions">
            <a href="{{ route('keranjang.index') }}" class="btn-cart btn-view">
                <i class="bi bi-eye"></i>
                Lihat Keranjang
            </a>
            <form action="{{ route('keranjang.submit') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-cart btn-submit">
                    <i class="bi bi-send"></i>
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>
@else
    <div class="cart-dropdown-body">
        <div class="cart-empty">
            <i class="bi bi-cart-x"></i>
            <p>Keranjang Anda masih kosong</p>
            <a href="{{ route('katalog') }}" class="btn-browse">
                <i class="bi bi-book"></i>
                Jelajahi Katalog
            </a>
        </div>
    </div>
@endif