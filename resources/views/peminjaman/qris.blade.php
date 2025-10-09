@extends('layouts.app')
@section('title', 'E-Perpus - Bayar Denda dengan QRIS')
@section('content')
<div class="container">
    <h4>Bayar Denda dengan QRIS</h4>
    <p>Total Denda: <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong></p>

    <button id="pay-button" class="btn btn-success">
        Bayar Sekarang
    </button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('peminjaman.index') }}";
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
        })
    };
</script>
@endsection
