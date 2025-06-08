@extends('layouts.homedash')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Daftar Produk - Game</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-2 px-4 border">#</th>
                    <th class="py-2 px-4 border">Nama Produk</th>
                    <th class="py-2 px-4 border">Kategori</th>
                    <th class="py-2 px-4 border">Brand</th>
                    <th class="py-2 px-4 border">Tipe</th>
                    <th class="py-2 px-4 border">Nama Penjual</th>
                    <th class="py-2 px-4 border">Harga</th>
                    <th class="py-2 px-4 border">Kode SKU Pembeli</th>
                    <th class="py-2 px-4 border">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($games as $index => $game)
                    <tr class="text-center">
                        <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border">{{ $game->product_name }}</td>
                        <td class="py-2 px-4 border">{{ $game->category }}</td>
                        <td class="py-2 px-4 border">{{ $game->brand }}</td>
                        <td class="py-2 px-4 border">{{ $game->type }}</td>
                        <td class="py-2 px-4 border">{{ $game->seller_name }}</td>
                        <td class="py-2 px-4 border">Rp{{ number_format($game->price, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 border">{{ $game->buyer_sku_code }}</td>
                        <td class="py-2 px-4 border">{{ $game->desc }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-4 text-center text-gray-500">Tidak ada data untuk kategori "Game".</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
