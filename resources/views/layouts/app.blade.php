<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Baso QR') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

<nav class="bg-white shadow px-6 py-4 mb-5">
    <div class="max-w-7xl mx-auto flex justify-between items-center">

        <!-- Left Side -->
        <div class="flex items-center gap-6">
            <a href="/panel/categories" class="font-bold text-lg">🍜 Baso QR</a>
<a href="{{ route('categories.index') }}">Category</a>
<a href="{{ route('menus.index') }}">Menu</a>
<a href="{{ route('transactions.index') }}">Transaction</a>

        </div>

        <!-- Right Side -->
        <div class="flex gap-3 items-center">
            <span class="text-sm text-gray-500">
                Login as: <b>{{ auth()->user()->name }}</b>
            </span>

            <form action="/logout" method="POST">
                @csrf
                <button class="px-3 py-1 bg-black text-white rounded-lg text-sm">
                    Logout
                </button>
            </form>
        </div>

    </div>
</nav>

<main>
    {{ $slot ?? '' }} 
    @yield('content')
</main>

</body>
</html>
