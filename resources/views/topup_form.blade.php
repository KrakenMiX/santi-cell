@extends('layouts.homedash')

@section('content')

<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Top Up Produk</h1>
    <form action="/topup" method="POST">
        @csrf
        <label for="buyer_sku_code">Buyer SKU Code:</label>
        <input type="text" name="buyer_sku_code" id="buyer_sku_code" required>
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
