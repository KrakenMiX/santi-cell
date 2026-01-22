@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Kelola Prefix</h1>

    {{-- Search & Refresh --}}
    <div class="mb-4 flex items-center gap-2">
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="Cari prefix, operator, atau keterangan..."
            class="w-full md:w-1/3 border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            
        <button id="refreshButton"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            🔄 Refresh
        </button>
    </div>

    {{-- Tabel Prefix --}}
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Prefix</th>
                    <th class="py-3 px-4">Operator</th>
                    <th class="py-3 px-4">Dibuat</th>
                    <th class="py-3 px-4">Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($prefixes as $index => $prefix)
                    <tr>
                        <td class="py-3 px-4">{{ ($prefixes->currentPage() - 1) * $prefixes->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $prefix->prefix }}</td>
                        <td class="py-3 px-4">{{ $prefix->operator }}</td>
                        <td class="py-3 px-4">{{ $prefix->created_at }}</td>
                        <td class="py-3 px-4">{{ $prefix->updated_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">Tidak ada data prefix.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $prefixes->links('pagination::tailwind') }}
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
