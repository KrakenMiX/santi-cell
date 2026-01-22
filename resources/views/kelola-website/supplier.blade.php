@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Kelola Supplier</h1>
    {{-- Search & Refresh --}}
    <div class="mb-4 flex items-center gap-2">
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="Cari produk atau supplier..."
            class="w-full md:w-1/3 border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            
        <button id="refreshButton"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            🔄 Refresh
        </button>
    </div>

    {{-- Tabel Supplier --}}
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nama Produk</th>
                    <th class="py-3 px-4">Supplier</th>
                    <th class="py-3 px-4">Harga</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($suppliers as $index => $supplier)
                    <tr>
                        <td class="py-3 px-4">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $supplier->product_name }}</td>
                        <td class="py-3 px-4">{{ $supplier->seller_name }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($supplier->price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">Tidak ada data supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $suppliers->links('pagination::tailwind') }}
    </div>
</div>

<script>
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