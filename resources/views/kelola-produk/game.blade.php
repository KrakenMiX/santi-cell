@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Kelola Produk Game</h2>
        <button id="fetchGameBtn" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            Fetch Produk
        </button>
    </div>
    
    {{-- Search & Refresh --}}
    <div class="mb-4 flex items-center gap-2">
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="Cari nama produk atau game..."
            class="w-full md:w-1/3 border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            
        <button id="refreshButton"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            🔄 Refresh
        </button>
    </div>

    {{-- Tabel Produk Game --}}
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Kategori</th>
                    <th class="py-3 px-4 text-left">Server</th>
                    <th class="py-3 px-4 text-left">Harga</th>
                    <th class="py-3 px-4 text-left">Kode SKU</th>
                    <th class="py-3 px-4 text-left">Status</th>
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
                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada data produk game.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $games->links('pagination::tailwind') }}
    </div>
</div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('fetchGameBtn').addEventListener('click', function () {
        fetch ("/fetch-vipayment-games")
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: data.message,
                    showConfirmButton: true
                });
            })
            .catch(err => {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat menghubungi server',
                });
            });
    });

// Tombol Refresh
    document.getElementById('refreshButton').addEventListener('click', function () {
        location.reload();
    });

    // Search dengan delay
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
            params.delete('page');
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }, 700);
    });
</script>
@endsection
