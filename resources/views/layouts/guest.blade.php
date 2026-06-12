<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'StudentLMS') }}</title>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell min-h-screen flex flex-col">
    <div class="flex flex-1 items-center justify-center px-4 py-12">
        <div class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center gap-3">
                    <a href="/" class="text-3xl font-bold tracking-tight text-cyan-950 dark:text-cyan-300">StudentLMS</a>
                    <button @click="$store.app.toggleDarkMode()" class="btn-ghost !rounded-xl !p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" title="Toggle dark mode">
                        <svg x-show="!$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-2 text-sm text-slate-500">A calmer way to learn, plan, and stay consistent.</p>
            </div>
            <div class="card p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>