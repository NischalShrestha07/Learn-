<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Habits</h2>
            <a href="{{ route('habits.create') }}" class="btn-primary text-sm">+ New Habit</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($habits as $habit)
                    <div class="card hover:shadow-md transition group">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">{{ $habit->icon ?? '✅' }}</span>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100">{{ $habit->name }}</h3>
                                        @if ($habit->description)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">{{ $habit->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('habits.edit', $habit) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="text-sm">
                                    <span class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $habit->streak }}</span>
                                    <span class="text-xs text-slate-500">day streak</span>
                                </div>
                                @if ($habit->today_log)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">Done today</span>
                                @endif
                            </div>

                            <div x-data="{ showCheckMark: {{ $habit->today_log ? 'true' : 'false' }} }" class="flex items-center justify-between pt-3 border-t border-slate-200/70 dark:border-slate-700/60">
                                <a href="{{ route('habits.history', $habit) }}" class="link text-xs">History</a>
                                <form method="POST" action="{{ route('habits.log', $habit) }}" @submit="setTimeout(() => showCheckMark = true, 100)">
                                    @csrf
                                    <button type="submit" x-show="!showCheckMark" class="btn-primary text-xs">Log today</button>
                                    <span x-show="showCheckMark" class="text-green-500 text-lg font-bold">✓</span>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No habits yet</p>
                        <p class="text-sm text-slate-500 mb-4">Build consistent routines by tracking daily habits.</p>
                        <a href="{{ route('habits.create') }}" class="btn-primary text-sm">Create your first habit</a>
                    </div>
                @endforelse
            </div>

            @if ($habits->hasPages())
                <div class="mt-6">{{ $habits->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
