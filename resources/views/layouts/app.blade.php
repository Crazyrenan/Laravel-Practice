<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @stack('styles')
    </head>
<body class="bg-gray-900 text-white flex">
    @include('layouts.sidebar')
    <div class="flex-1 ml-64 p-6">
        @yield('content')
    </div>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>a\
</html>
