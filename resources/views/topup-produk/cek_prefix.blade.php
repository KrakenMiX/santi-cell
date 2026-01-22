@extends('layouts.homedash')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <h1 class="text-xl font-bold mb-4 text-center">Cek Operator Berdasarkan Nomor</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/cek-prefix') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nomor" class="block mb-1 font-medium">Masukkan Nomor HP</label>
            <input type="text" name="nomor" id="nomor" class="w-full border p-2 rounded-md" placeholder="Contoh: 085812345678" required>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Cek Operator
            </button>
        </div>
    </form>
</div>
@endsection
