@extends('layouts.homedash')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-200 text-black font-sans">

    <div class="flex flex-col md:flex-row justify-center min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
        </div>
    </div>

</body>

</html>
@endsection
