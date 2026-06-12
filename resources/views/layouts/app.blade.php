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
    <style>
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 4px; }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.15); }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell h-screen overflow-hidden">
    <div x-data="{ sidebarOpen: false }" class="h-full flex">
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

        {{-- Main content --}}
        <div
            class="flex min-w-0 flex-1 flex-col h-full overflow-y-auto transition-all duration-200"
            :class="$store.app.sidebarCollapsed ? 'lg:ml-[68px]' : 'lg:ml-72'"
        >
            {{-- Mobile topbar --}}
            <div class="app-topbar sticky top-0 z-30 lg:hidden">
                <div class="flex h-14 items-center justify-between px-4">
                    <button @click="sidebarOpen = true" class="btn-ghost -ml-2 p-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-cyan-950 dark:text-cyan-300">StudentLMS</a>
                    <div class="w-9"></div>
                </div>
            </div>

            {{-- Header --}}
            @isset($header)
                <div class="px-4 pt-5 sm:px-6 lg:px-8">
                    <div class="glass-panel mx-auto max-w-7xl rounded-[28px] px-5 py-4 sm:px-6">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            {{-- Page content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>