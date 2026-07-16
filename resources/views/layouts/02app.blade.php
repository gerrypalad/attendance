<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-100 min-h-screen">

    @include('layouts.navigation')

    <main class="w-full px-2 py-4">

        <!-- 1. Support for traditional @section('content') views -->
        @yield('content')

        <!-- 2. Support for modern component $slot views if no section is captured -->
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    <!-- Global Toast Notifications system -->
    <x-toast />
</body>
</html>
