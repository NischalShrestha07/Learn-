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
<body class="page-shell h-screen overflow-hidden">
    <div x-data="{ sidebarOpen: false }" class="flex h-full">
        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-950/30 backdrop-blur-sm lg:hidden"
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                $store.app.sidebarCollapsed ? 'w-[68px]' : 'w-72',
                $store.app.sidebarCollapsed ? 'sidebar-collapsed' : ''
            ]"
            class="app-sidebar fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-200 ease-in-out lg:translate-x-0"
        >
            @include('layouts.navigation')
        </aside>

        {{-- Main content area --}}
        <div
            class="flex min-w-0 flex-1 flex-col transition-all duration-200"
            :class="$store.app.sidebarCollapsed ? 'lg:ml-[68px]' : 'lg:ml-72'"
        >
            {{-- Unified topbar: mobile + desktop --}}
            <div class="app-topbar sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between border-b border-slate-200/80 px-4 dark:border-slate-700/60 sm:px-6 lg:h-12">
                {{-- Left: hamburger (mobile only) --}}
                <button @click="sidebarOpen = true" class="btn-ghost -ml-2 p-2 lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Center: brand on mobile only --}}
                <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-cyan-950 dark:text-cyan-300 lg:hidden">StudentLMS</a>
                {{-- Spacer on desktop --}}
                <div class="hidden lg:block"></div>

                {{-- Right: theme toggle + user --}}
                <div class="flex items-center gap-2">
                    {{-- Theme toggle --}}
                    <button @click="$store.app.toggleDarkMode()" class="btn-ghost !rounded-xl !p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" title="Toggle theme">
                        <svg x-show="!$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- User menu --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="btn-ghost !rounded-xl !px-2 !py-1.5 flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/40 text-xs font-semibold text-cyan-900 dark:text-cyan-300">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden text-sm font-semibold text-slate-700 dark:text-slate-300 sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="hidden h-3 w-3 text-slate-400 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            @click.outside="open = false"
                            class="absolute right-0 top-full z-50 mt-1 w-48 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg dark:border-slate-700/60 dark:bg-slate-800"
                        >
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700/50">
                                Profile settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 text-left text-sm text-red-600 transition hover:bg-slate-50 dark:text-red-400 dark:hover:bg-slate-700/50">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Optional header --}}
            @isset($header)
                <div class="px-4 pt-5 sm:px-6 lg:px-8">
                    <div class="glass-panel mx-auto max-w-7xl rounded-[28px] px-5 py-4 sm:px-6">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            {{-- Page content (scrolls) --}}
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>