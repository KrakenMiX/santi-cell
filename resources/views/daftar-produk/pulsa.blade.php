@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Produk - Pulsa</h1>
    
        <div class="mb-4">
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk atau brand..."
                class="border border-gray-300 px-4 py-2 rounded w-full max-w-xs focus:outline-none focus:ring-2 focus:ring-blue-400 mb-3">
            
            <button id="refreshButton"
                class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
                🔄 Refresh
            </button>
        </div>
        
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-2 px-4 border text-left">#</th>
                    <th class="py-2 px-4 border text-left">Nama Produk</th>
                    <!--<th class="py-2 px-4 border">Kategori</th>-->
                    <th class="py-3 px-4 border text-left">Brand</th>
                    <th class="py-3 px-4 border text-left">Tipe</th>
                    <th class="py-3 px-4 border text-left">Nama Penjual</th>
                    <th class="py-3 px-4 border text-left">Harga</th>
                    <th class="py-3 px-4 border text-left">Kode SKU Pembeli</th>
                    <th class="py-3 px-4 border text-left">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($pulsas as $index => $pulsa)
                    <tr>
                        <td class="py-3 px-4">{{ ($pulsas->currentPage() - 1) * $pulsas->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $pulsa->product_name }}</td>
                        <!--<td class="py-2 px-4 border">{{ $pulsa->category }}</td>-->
                        <td class="py-3 px-4">{{ $pulsa->brand }}</td>
                        <td class="py-3 px-4">{{ $pulsa->type }}</td>
                        <td class="py-3 px-4">{{ $pulsa->seller_name }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($pulsa->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $pulsa->buyer_sku_code }}</td>
                        <td class="py-3 px-4">{{ $pulsa->desc }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada data untuk kategori "Pulsa".</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-6">
            {{ $pulsas->links('pagination::tailwind') }}
        </div>

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
            params.delete('page'); // reset ke halaman pertama
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }, 1000); // debounce 1 detik
    });
    
    document.getElementById('refreshButton').addEventListener('click', function () {
        location.reload(); // reload halaman
    });
</script>
@endsection
