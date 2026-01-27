@extends('layouts.backend')
@section('title', 'E-Perpus - Berikan Rating & Ulasan')
@section('content')

{{-- Redirect ke rating_list --}}
<script>
    window.location.href = "{{ route('rating.show') }}";
</script>

<div class="col-md-12">
    <div class="alert alert-info text-center py-5" role="alert">
        <h4>Mengalihkan ke halaman Rating...</h4>
        <p class="mb-0">Jika halaman tidak berubah, <a href="{{ route('rating.show') }}">klik di sini</a></p>
    </div>
</div>

@endsection@endsection
