@extends('layouts.app')
@section('title', 'E-Perpus - Bayar Denda dengan QRIS')
@section('content')
<div class="container">
    <h4>Bayar Denda dengan QRIS</h4>
    <p>Total Denda: <strong>Rp {{ number_format($peminjaman->due_denda, 0, ',', '.') }}</strong></p>

    <button id="payButton" onclick="pay()" class="btn btn-success">Bayar Sekarang</button>
</div>

<div id="loadingOverlay" class="loading-overlay d-none">
    <div class="loading-card">
        <div class="loader-dots" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="loading-title">Sedang memuat QRIS</div>
        <div class="loading-subtitle">Tunggu sebentar, kami menyiapkan metode pembayaran Anda.</div>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}
.loading-card {
    width: min(90%, 340px);
    padding: 24px;
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid rgba(13, 110, 253, 0.18);
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
    text-align: center;
}
.loader-dots {
    display: inline-flex;
    gap: 10px;
    margin-bottom: 18px;
}
.loader-dots span {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #0d6efd;
    opacity: 0.35;
    animation: dotPulse 1s ease-in-out infinite;
}
.loader-dots span:nth-child(2) {
    animation-delay: 0.15s;
}
.loader-dots span:nth-child(3) {
    animation-delay: 0.3s;
}
@keyframes dotPulse {
    0%, 100% { transform: translateY(0); opacity: 0.35; }
    50% { transform: translateY(-8px); opacity: 1; }
}
.loading-title {
    margin-bottom: 8px;
    font-size: 1rem;
    font-weight: 700;
    color: #0d3e86;
}
.loading-subtitle {
    font-size: 0.92rem;
    color: #4b5563;
    line-height: 1.5;
}
.d-none { display: none !important; }
</style>

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
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        window.snap.pay(data.snap_token, {
            onSuccess: function(result){
                fetch("{{ route('peminjaman.pay.qris.confirm', $peminjaman->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ result: result })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.message){
                        window.location.href = "{{ route('peminjaman.index') }}" + '?success=' + encodeURIComponent(data.message);
                    } else {
                        window.location.href = "{{ route('peminjaman.index') }}";
                    }
                })
                .catch(() => {
                    window.location.href = "{{ route('peminjaman.index') }}";
                });
            },
            onPending: function(result){
                alert("Menunggu pembayaran...");
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                alert("Kamu menutup jendela pembayaran!");
            }
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
