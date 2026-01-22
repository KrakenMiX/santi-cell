@extends('layouts.homedash')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Kelola Provider</h1>
    
    {{-- Digiflazz --}}
    <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-md">
        <p class="text-3xl font-bold text-green-600">Koneksi Digiflazz</p>
        <h2 class="text-xl font-semibold mb-2" 
            style="color: {{ $digiflazzStatus['color'] }}">
            {{ ucfirst($digiflazzStatus['status']) }}
        </h2>
    </div>
    <br>

    {{-- VIPayment --}}
    <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-md">
        <p class="text-3xl font-bold text-blue-600">Koneksi VIPayment</p>
        <h2 class="text-xl font-semibold mb-2" 
            style="color: {{ $vipaymentStatus['color'] }}">
            {{ ucfirst($vipaymentStatus['status']) }}
        </h2>
    </div>
</div>
@endsection
