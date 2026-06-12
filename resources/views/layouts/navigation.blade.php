<nav x-data="{ open: false, mobileNav: false }" class="bg-white/90 dark:bg-gray-900/90 border-b border-gray-200/60 dark:border-gray-800/60 backdrop-blur-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            <div class="flex items-center gap-1">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-indigo-600 dark:text-indigo-400 tracking-tight mr-6 shrink-0">StudentLMS</a>
                <div class="hidden md:flex items-center gap-0.5">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('topics.index')" :active="request()->routeIs('topics.*')">Topics</x-nav-link>
                    <x-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')">Notes</x-nav-link>
                    <x-nav-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')">Flashcards</x-nav-link>
                    <x-nav-link :href="route('planner.index')" :active="request()->routeIs('planner.*')">Planner</x-nav-link>
                    <x-nav-link :href="route('focus.index')" :active="request()->routeIs('focus.*')">Focus</x-nav-link>
                    <x-nav-link :href="route('journal.index')" :active="request()->routeIs('journal.*')">Journal</x-nav-link>
                    <x-nav-link :href="route('resources.index')" :active="request()->routeIs('resources.*')">Resources</x-nav-link>
                    <x-nav-link :href="route('progress.index')" :active="request()->routeIs('progress.*')">Progress</x-nav-link>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="hidden sm:flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="btn-ghost text-sm gap-1.5 inline-flex items-center">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold flex items-center justify-center">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <span class="hidden lg:inline">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-100 dark:border-gray-700">{{ Auth::user()->email }}</div>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <button @click="mobileNav = !mobileNav" class="md:hidden btn-ghost p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileNav, 'inline-flex': !mobileNav }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileNav, 'inline-flex': mobileNav }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': mobileNav, 'hidden': !mobileNav}" class="hidden md:hidden border-t border-gray-200/60 dark:border-gray-800/60 bg-white dark:bg-gray-900">
        <div class="px-4 py-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('topics.index')" :active="request()->routeIs('topics.*')">Topics</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')">Notes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')">Flashcards</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('planner.index')" :active="request()->routeIs('planner.*')">Planner</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('focus.index')" :active="request()->routeIs('focus.*')">Focus</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('journal.index')" :active="request()->routeIs('journal.*')">Journal</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('resources.index')" :active="request()->routeIs('resources.*')">Resources</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('progress.index')" :active="request()->routeIs('progress.*')">Progress</x-responsive-nav-link>
        </div>
        <div class="border-t border-gray-200/60 dark:border-gray-800/60 px-4 py-3 flex items-center justify-between">
            <div class="text-sm">
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost text-sm text-red-600 dark:text-red-400">Log out</button>
            </form>
        </div>
    </div>
</nav>
