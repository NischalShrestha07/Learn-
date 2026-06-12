<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="eyebrow">Learning library</span>
                <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-slate-100">My Topics</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Organize subjects into clear learning tracks you can revisit and grow.</p>
            </div>
            <a href="{{ route('topics.create') }}" class="btn-primary text-sm">Create topic</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="metric-card">
                    <p class="section-title">Total topics</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $topics->total() }}</p>
                </div>
                <div class="metric-card">
                    <p class="section-title">This page</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $topics->count() }}</p>
                </div>
                <div class="metric-card">
                    <p class="section-title">Recent cadence</p>
                    <p class="mt-3 text-lg font-bold text-slate-950 dark:text-slate-100">{{ $topics->count() ? 'Keep building consistently' : 'Start your first learning track' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($topics as $topic)
                    <div class="card transition hover:-translate-y-0.5 hover:shadow-[0_22px_50px_-30px_rgba(8,47,73,0.38)]">
                        <a href="{{ route('topics.show', $topic) }}" class="block p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="section-title">Topic</p>
                                    <h3 class="mt-2 text-xl font-bold text-slate-950 dark:text-slate-100">{{ $topic->title }}</h3>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-700 dark:text-slate-400">{{ $topic->created_at->format('M d') }}</span>
                            </div>
                            @if ($topic->description)
                                <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $topic->description }}</p>
                            @else
                                <p class="mt-4 text-sm leading-6 text-slate-400 dark:text-slate-500">No description yet. Add one to define the scope of this topic.</p>
                            @endif
                            <div class="mt-5 flex items-center justify-between border-t border-slate-200/80 pt-4 text-xs text-slate-500 dark:border-slate-700/60 dark:text-slate-400">
                                <span>{{ $topic->sections_count }} {{ Str::plural('section', $topic->sections_count) }}</span>
                                <span>{{ $topic->learning_sessions_count }} {{ Str::plural('session', $topic->learning_sessions_count) }}</span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="empty-state col-span-full">
                        <p class="text-base font-semibold text-slate-950 dark:text-slate-100">No topics yet</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create your first learning topic to start building your study map.</p>
                        <a href="{{ route('topics.create') }}" class="btn-primary mt-5 text-sm">Create a topic</a>
                    </div>
                @endforelse
            </div>

            @if ($topics->hasPages())
                <div class="mt-6">{{ $topics->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
