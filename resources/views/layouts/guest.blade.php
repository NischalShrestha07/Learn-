<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'StudentLMS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-700 dark:text-gray-300 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <a href="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">StudentLMS</a>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your personal learning system</p>
            </div>
            <div class="card p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
