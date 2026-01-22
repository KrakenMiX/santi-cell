@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow">
    
    <h1 class="text-2xl font-bold mb-4">Kelola Pengguna</h1>
    {{-- Search & Refresh --}}
    <div class="mb-4 flex items-center gap-2">
        <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
            placeholder="Cari nama, email, atau role..."
            class="w-full md:w-1/3 border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            
        <button id="refreshButton"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            🔄 Refresh
        </button>
        <button id="openModal"
            class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
            ✅<br>Tambah User</button>
    </div>

    {{-- Modal Tambah User --}}
    <div id="userModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4">Tambah Pengguna</h2>
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block mb-1">Nama</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Role</label>
                    <select name="role" class="w-full border rounded px-3 py-2" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal" class="px-4 py-2 bg-gray-500 text-black rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif


    {{-- Tabel Pengguna --}}
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Dibuat</th>
                    <th class="py-3 px-4">Update</th>
                    <th class="py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($users as $index => $user)
                    <tr>
                        <td class="py-3 px-4">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">{{ $user->role }}</td>
                        <td class="py-3 px-4">{{ $user->created_at }}</td>
                        <td class="py-3 px-4">{{ $user->updated_at }}</td>
                        <td class="py-3 px-4">
                            <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin hapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="px-4 py-2 bg-green-500 text-black rounded-lg shadow hover:bg-green-600 focus:outline-none">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links('pagination::tailwind') }}
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
    
    // Modal Tambah User
    const modal = document.getElementById('userModal');
    document.getElementById('openModal').addEventListener('click', () => modal.classList.remove('hidden'));
    document.getElementById('closeModal').addEventListener('click', () => modal.classList.add('hidden'));

</script>
@endsection