@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Produk - Game</h1>

    <div class="mb-4">
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="Cari nama produk atau game..."
            class="w-full md:w-1/3 border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
            
        <button id="refreshButton"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            🔄 Refresh
        </button>
    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Kategori</th>
                    <th class="py-3 px-4 text-left">Server</th>
                    <th class="py-3 px-4 text-left">Harga</th>
                    <th class="py-3 px-4 text-left">Kode SKU Pembeli</th>
                    <th class="py-3 px-4 text-left">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($games as $index => $game)
                    <tr>
                        <td class="py-3 px-4">{{ ($games->currentPage() - 1) * $games->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $game->product_name }}</td>
                        <td class="py-3 px-4">{{ $game->category }}</td>
                        <td class="py-3 px-4">{{ $game->server }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($game->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $game->buyer_sku_code }}</td>
                        <td class="py-3 px-4">{{ $game->desc }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada data untuk kategori "Game".</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $games->links('pagination::tailwind') }}
    </div>
</div>
@endsection

@section('script')
<script>
    const searchInput = document.getElementById('searchInput');

    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            const keyword = searchInput.value.trim();
            const params = new URLSearchParams(window.location.search);
            if (keyword) {
                params.set('search', keyword);
            } else {
                params.delete('search');
            }
            params.delete('page'); // reset ke halaman 1 saat search
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }, 700); // delay 700ms untuk mencegah spam request
    });
    
    document.getElementById('refreshButton').addEventListener('click', function () {
        location.reload(); // reload halaman
    });
</script>
@endsection
