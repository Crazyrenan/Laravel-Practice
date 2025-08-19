<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'New App Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body class="bg-gray-900 text-gray-200 flex min-h-screen">
    @include('layouts.crudbar')
    <div class="flex-1 ml-64 p-8">
        @yield('content')
    </div>
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>