@if($cart)
<table class="table">
@foreach($cart as $id => $item)
<tr>
    <td>{{ $item['judul'] }}</td>
    <td>{{ $item['jumlah'] }}</td>
    <td>
        <form method="POST" action="{{ route('keranjang.hapus', $id) }}">
            @csrf
            <button class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </td>
</tr>
@endforeach
</table>

<form method="POST" action="{{ route('keranjang.submit') }}">
    @csrf
    <button class="btn btn-success">Ajukan Peminjaman</button>
</form>
@endif
