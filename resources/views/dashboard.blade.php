@extends('layouts.homedash')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-C6oFmr7T.css') }}">
</head>

<body class="bg-gray-200 text-black font-sans">

    <div class="flex flex-col md:flex-row justify-center ">
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-2">Saldo DigiFlazz</h2>
        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>
    <br>
    <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-2">Saldo VIPayment</h2>
        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($saldoVip, 0, ',', '.') }}</p>
    </div>
</body>

</html>
@endsection
