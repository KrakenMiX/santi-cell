<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<section class="bg-gray-200 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="{{ route('dashboard') }}" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            Santi Cell Dashboard
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Lupa Kata Sandi
                </h1>

                @if (session('success'))
                    <div class="p-4 mb-4 text-green-800 bg-green-50 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 mb-4 text-red-800 bg-red-50 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Masukkan Email Anda
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 
                               focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
                               dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 
                               dark:focus:border-blue-500">
                    </div>
                    <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 
                                   focus:outline-none focus:ring-blue-300 font-medium rounded-lg 
                                   text-sm px-5 py-2.5 text-center">
                        Kirim Link Reset
                    </button>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                            Kembali ke login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
</body>
</html>
