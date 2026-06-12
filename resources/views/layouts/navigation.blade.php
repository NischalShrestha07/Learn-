{{-- Collapse toggle — floating at top-right edge of sidebar --}}
<button
    @click="$store.app.toggleSidebar()"
    class="absolute -right-3 top-5 z-50 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500 dark:hover:text-slate-300"
    title="Toggle sidebar"
>
    <svg class="h-3 w-3 transition-transform duration-200" :class="$store.app.sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
    </svg>
</button>

{{-- Brand --}}
<div class="shrink-0 px-5 py-5 transition-all duration-200" :class="$store.app.sidebarCollapsed ? '!px-3 text-center' : ''">
    <a href="{{ route('dashboard') }}" class="block">
        <span x-show="!$store.app.sidebarCollapsed" class="text-xl font-bold tracking-tight text-cyan-950 dark:text-cyan-300">StudentLMS</span>
        <span x-show="$store.app.sidebarCollapsed" class="text-xl font-bold tracking-tight text-cyan-950 dark:text-cyan-300">S</span>
    </a>
    <p x-show="!$store.app.sidebarCollapsed" class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Study Operating System</p>
</div>

{{-- Navigation links --}}
<nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4 scrollbar-thin">
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Overview</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="grid">Dashboard</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Learning</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('topics.index')" :active="request()->routeIs('topics.*')" icon="book">Topics</x-nav-link>
            <x-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.*')" icon="file-text">Notes</x-nav-link>
            <x-nav-link :href="route('flashcards.index')" :active="request()->routeIs('flashcards.*')" icon="layers">Flashcards</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Academic</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('assignments.index')" :active="request()->routeIs('assignments.*')" icon="clipboard">Assignments</x-nav-link>
            <x-nav-link :href="route('exams.index')" :active="request()->routeIs('exams.*')" icon="alert-circle">Exams</x-nav-link>
            <x-nav-link :href="route('grades.index')" :active="request()->routeIs('grades.*')" icon="bar-chart">Grades</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Planning</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('planner.index')" :active="request()->routeIs('planner.*')" icon="calendar">Study Planner</x-nav-link>
            <x-nav-link :href="route('todos.index')" :active="request()->routeIs('todos.*')" icon="check-square">To-Do List</x-nav-link>
            <x-nav-link :href="route('focus.index')" :active="request()->routeIs('focus.*')" icon="clock">Focus Timer</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Reflection</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('journal.index')" :active="request()->routeIs('journal.*')" icon="edit">Journal</x-nav-link>
            <x-nav-link :href="route('progress.index')" :active="request()->routeIs('progress.*')" icon="trending-up">Progress</x-nav-link>
            <x-nav-link :href="route('achievements.index')" :active="request()->routeIs('achievements.*')" icon="award">Achievements</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Lifestyle</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('habits.index')" :active="request()->routeIs('habits.*')" icon="heart">Habits</x-nav-link>
        </div>
    </div>
    <div>
        <p x-show="!$store.app.sidebarCollapsed" class="sidebar-section">Resources</p>
        <div class="mt-1 space-y-0.5">
            <x-nav-link :href="route('resources.index')" :active="request()->routeIs('resources.*')" icon="link">Library</x-nav-link>
        </div>
    </div>
</nav>

{{-- Bottom: user avatar badge (dropdown is in topbar) --}}
<div class="shrink-0 border-t border-slate-200/80 dark:border-slate-700/60 p-3">
    <div class="flex items-center gap-3" :class="$store.app.sidebarCollapsed ? 'justify-center' : ''">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cyan-100 dark:bg-cyan-900/40 text-xs font-semibold text-cyan-900 dark:text-cyan-300">
            {{ substr(Auth::user()->name, 0, 1) }}
        </span>
        <span x-show="!$store.app.sidebarCollapsed" class="truncate text-sm font-semibold text-slate-600 dark:text-slate-400">{{ Auth::user()->name }}</span>
    </div>
</div>