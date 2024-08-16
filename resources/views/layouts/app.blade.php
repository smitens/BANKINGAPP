<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BankAPP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Other head elements -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col h-screen bg-gray-100">
<!-- Top Navigation Bar -->
<nav class="bg-white shadow-md border-b border-gray-200 p-4 flex items-center justify-between">
    <!-- Logo and Navigation Links -->
    <div class="flex items-center space-x-6">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <img src="{{ asset('images/Bank.svg') }}" alt="Bank Logo" class="h-8 w-auto">
            <span class="text-xl font-semibold">{{ __('BankApp') }}</span>
        </a>
    </div>

    <!-- Header Content -->
    <div class="flex-1 text-center">
        @isset($header)
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $header }}
            </h2>
        @endisset
    </div>

    <!-- Profile Dropdown -->
    <div class="flex items-center space-x-4">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center px-3 py-2 text-gray-600 hover:text-gray-900 bg-white hover:bg-gray-100 rounded-md transition duration-150">
                    <div>{{ Auth::user()->name }}</div>
                    <svg class="fill-current h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>

<!-- Main Content Area -->
<div class="flex flex-1">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Page Content -->
    <main class="flex-1 p-6 bg-gray-100">
        {{ $slot }}
    </main>
</div>
</body>
</html>
