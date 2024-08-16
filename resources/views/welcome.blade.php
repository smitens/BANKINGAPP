<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BankApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">
<div class="text-center max-w-4xl mx-auto px-6 py-12">
    <!-- Logo -->
    <div class="mb-12">
        <img src="{{ asset('images/Bank.svg') }}" alt="Bank Logo" class="h-36 mx-auto">
    </div>

    <!-- Welcome Message -->
    <h1 class="text-5xl font-bold mb-12">
        Welcome to <span class="text-[#FF2D20]">{{ config('app.name', 'BankAPP') }}</span>
    </h1>

    <!-- Authentication Buttons -->
    <div class="flex flex-col items-center gap-6">
        <!-- Login Button -->
        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 text-gray-900 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition duration-150 text-lg font-semibold">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Log In
        </a>

        <!-- Register Button -->
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 text-gray-900 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition duration-150 text-lg font-semibold">
                <i class="fas fa-user-plus mr-2"></i>
                Register
            </a>
        @endif
    </div>
</div>
</body>
</html>

