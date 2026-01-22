@extends('layouts.homedash')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-gray-700">Profil Pengguna</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-600 text-sm">Nama</label>
            <p class="mt-1 p-2 bg-gray-100 rounded">{{ $user->name }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm">Email</label>
            <p class="mt-1 p-2 bg-gray-100 rounded">{{ $user->email }}</p>
        </div>
        
        <div>
            <label class="block text-gray-600 text-sm">Role</label>
            <p class="mt-1 p-2 bg-gray-100 rounded">{{ $user->role }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm">Tanggal Dibuat</label>
            <p class="mt-1 p-2 bg-gray-100 rounded">{{ $user->created_at->format('d-m-Y H:i') }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm">Terakhir Diperbarui</label>
            <p class="mt-1 p-2 bg-gray-100 rounded">{{ $user->updated_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
