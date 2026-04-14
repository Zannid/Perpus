@extends('layouts.backend')
@section('title', 'E-Perpus - Bayar Denda dengan QRIS')
@section('content')

<style>
:root {
    --primary: #667eea;
    --primary-dark: #764ba2;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --light-bg: #f5f6ff;
    --dark-text: #1e2459;
    --muted: #6b7280;
    --border: #e5e7eb;
    --shadow-md: 0 6px 24px rgba(0,0,0,0.10);
    --radius: 16px;
}

.qris-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    background: #fafafa;
}

.qris-card {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

/* Header */
.qris-card-header {
    background: var(--primary-gradient);
    padding: 24px 28px;
    text-align: center;
}
.qris-card-header .header-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px;
    font-size: 26px; color: #fff;
}
.qris-card-header h4 {
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    margin: 0;
}
.qris-card-header p {
    color: rgba(255,255,255,0.85);
    font-size: 13px;
    margin: 4px 0 0;
}

/* Body */
.qris-card-body { padding: 28px; }

/* Denda info */
.denda-info {
    background: var(--light-bg);
    border: 1.5px solid rgba(102,126,234,0.2);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-bottom: 24px;
}
.denda-label {
    font-size: 13px;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}
.denda-amount {
    font-size: 32px;
    font-weight: 900;
    color: var(--dark-text);
    line-height: 1;
}
.denda-amount span {
    font-size: 18px;
    font-weight: 600;
    color: var(--primary);
    margin-right: 4px;
}

/* Info rows */
.info-rows { margin-bottom: 24px; }
.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px dashed var(--border);
    font-size: 13px;
}
.info-row:last-child { border-bottom: none; }
.info-row i { color: var(--primary); font-size: 16px; flex-shrink: 0; }
.info-row-label { color: var(--muted); flex: 1; }
.info-row-value { font-weight: 700; color: var(--dark-text); }

/* Button */
.btn-pay {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    background: var(--primary-gradient);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(102,126,234,0.3);
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-pay:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(102,126,234,0.4);
}
.btn-pay:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

/* Back link */
.back-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--muted);
    text-decoration: none;
    transition: color 0.2s;
}
.back-link:hover { color: var(--primary); }

/* Notice */
.qris-notice {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: #fef9c3;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 20px;
    font-size: 12px;
    color: #92400e;
    line-height: 1.6;
}
.qris-notice i { font-size: 15px; flex-shrink: 0; margin-top: 1px; }

/* ── Loading Overlay ── */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}
.loading-card {
    width: min(90%, 320px);
    padding: 28px 24px;
    background: #fff;
    border-radius: var(--radius);
    box-shadow: 0 20px 50px rgba(15,23,42,0.2);
    text-align: center;
}
.loader-dots {
    display: inline-flex;
    gap: 10px;
    margin-bottom: 16px;
}
.loader-dots span {
    width: 13px; height: 13px;
    border-radius: 50%;
    background: var(--primary-gradient);
    background: var(--primary);
    opacity: 0.4;
    animation: dotPulse 1s ease-in-out infinite;
}
.loader-dots span:nth-child(2) { animation-delay: 0.15s; }
.loader-dots span:nth-child(3) { animation-delay: 0.3s; }
@keyframes dotPulse {
    0%, 100% { transform: translateY(0); opacity: 0.4; }
    50%       { transform: translateY(-8px); opacity: 1; }
}
.loading-title { font-size: 15px; font-weight: 700; color: var(--dark-text); margin-bottom: 6px; }
.loading-subtitle { font-size: 13px; color: var(--muted); line-height: 1.6; }
.d-none { display: none !important; }

/* Responsive */
@media (max-width: 480px) {
    .qris-card-body { padding: 20px 18px; }
    .denda-amount { font-size: 26px; }
    .qris-card-header { padding: 20px 18px; }
}
</style>

<div class="qris-page">
    <div class="qris-card">

        {{-- Header --}}
        <div class="qris-card-header">
            <div class="header-icon">
                <i class="bx bx-qr-code-scan"></i>
            </div>
            <h4>Pembayaran Denda</h4>
            <p>Selesaikan pembayaran dengan QRIS</p>
        </div>

        {{-- Body --}}
        <div class="qris-card-body">

            {{-- Nominal Denda --}}
            <div class="denda-info">
                <div class="denda-label">Total Denda yang Harus Dibayar</div>
                <div class="denda-amount">
                    <span>Rp</span>{{ number_format($peminjaman->due_denda, 0, ',', '.') }}
                </div>
            </div>

            {{-- Info Tambahan --}}
            <div class="info-rows">
                <div class="info-row">
                    <i class="bi bi-receipt"></i>
                    <span class="info-row-label">Kode Peminjaman</span>
                    <span class="info-row-value">{{ $peminjaman->kode_peminjaman ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span class="info-row-label">Metode Bayar</span>
                    <span class="info-row-value">QRIS</span>
                </div>
                <div class="info-row">
                    <i class="bi bi-shield-check"></i>
                    <span class="info-row-label">Keamanan</span>
                    <span class="info-row-value" style="color:#16a34a;">Terenkripsi & Aman</span>
                </div>
            </div>

            {{-- Tombol Bayar --}}
            <button id="payButton" onclick="pay()" class="btn-pay">
                <i class="bi bi-qr-code-scan"></i>
                Bayar Sekarang
            </button>

            {{-- Back --}}
            <a href="{{ url()->previous() }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            {{-- Notice --}}
            <div class="qris-notice">
                <i class="bi bi-info-circle-fill"></i>
                <span>Pastikan saldo atau limit QRIS Anda mencukupi sebelum melanjutkan pembayaran. Denda tidak dapat dikembalikan setelah pembayaran berhasil.</span>
            </div>

        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-card">
        <div class="loader-dots">
            <span></span><span></span><span></span>
        </div>
        <div class="loading-title">Sedang memuat QRIS</div>
        <div class="loading-subtitle">Tunggu sebentar, kami menyiapkan metode pembayaran Anda.</div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('d-none');
    document.getElementById('payButton').setAttribute('disabled', 'disabled');
}
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('d-none');
    document.getElementById('payButton').removeAttribute('disabled');
}
function pay() {
    showLoading();
    fetch('{{ route("peminjaman.pay.qris", $peminjaman->id) }}', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                fetch("{{ route('peminjaman.pay.qris.confirm', $peminjaman->id) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ result: result })
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href = data.message
                        ? "{{ route('peminjaman.index') }}" + '?success=' + encodeURIComponent(data.message)
                        : "{{ route('peminjaman.index') }}";
                })
                .catch(() => { window.location.href = "{{ route('peminjaman.index') }}"; });
            },
            onPending: function() { alert("Menunggu pembayaran..."); },
            onError:   function() { alert("Pembayaran gagal!"); },
            onClose:   function() { alert("Kamu menutup jendela pembayaran!"); }
        });
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert("Terjadi kesalahan saat memuat pembayaran.");
    });
}
</script>
@endsection