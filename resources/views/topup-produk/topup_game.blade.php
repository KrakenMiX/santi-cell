@extends('layouts.homedash')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Top Up Produk Game</h1>
        <form action="/topup" method="POST">
            @csrf
            <br>
            <label for="brand">Brand:</label>
            <select name="brand" id="brand">
                <option value="0" selected disabled>Pilih brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand }}">{{ $brand }}</option>
                @endforeach
            </select>
            <br>
            <label for="product_name">Nama Produk:</label>
            <select name="product_name" id="product_name">
                <option value="0" selected disabled>Pilih Nama Produk</option>
            </select>
            <br>
            <label for="buyer_sku_code">Buyer SKU Code(kode produk):</label>
            <input type="text" name="buyer_sku_code" id="buyer_sku_code" required>
            <br>
            <label for="price">Harga Produk:</label>
            <input type="text" name="price" id="price" required>
            <br>
            <label for="customer_no">Customer No:</label>
            <input type="text" name="customer_no" id="customer_no" required>
            <br>
            <label for="testing">Testing:</label>
            <select name="testing" id="testing">
                <option value="true">True</option>
                <option value="false">False</option>
            </select>
            <br>
            <button type="submit">Submit</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        const games = [
            @foreach($games as $game)
            {
                brand: "{{ $game->brand }}",
                product_name: "{{ $game->product_name }}",
                buyer_sku_code: "{{ $game->buyer_sku_code }}",
                price: "Rp{{ number_format($game->price, 0, ',', '.') }}",
            },
            @endforeach
        ]

        $('#brand').change(function () {
            const nama_brand = $(this).val()
            const new_games = games.filter(v => v.brand == nama_brand)
            $('#product_name').html('')
            $('#product_name').append('<option value="0" selected disabled>Pilih Nama Produk</option>')
            for (let i = 0; i < new_games.length; i++) {
                $('#product_name').append(`<option value="${ new_games[i].buyer_sku_code }">${ new_games[i].product_name }</option>`)
            }
            $('#buyer_sku_code').val('')
            $('#price').val('')
        })

        $('#product_name').change(function() {
            const product_code = $(this).val()
            const product = games.filter(v => v.buyer_sku_code == product_code)[0]
            $('#buyer_sku_code').val(product.buyer_sku_code)
            $('#price').val(product.price)
        })
    </script>
@endsection
