@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifikasi Alamat Email Anda</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif

                    Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
                    Jika Anda tidak menerima email,
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">klik di sini untuk meminta yang lain</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
