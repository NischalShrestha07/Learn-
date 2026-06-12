<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="eyebrow">Workspace overview</span>
                <h2 class="mt-3 text-2xl font-bold text-slate-950">Dashboard</h2>
                <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, F j, Y') }} · Your learning system at a glance.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('topics.create') }}" class="btn-primary text-sm">New topic</a>
                <a href="{{ route('notes.create') }}" class="btn-secondary text-sm">New note</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 sm:px-6 lg:px-8">
            <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="metric-card">
                    <p class="section-title">Topics</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $topicCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Active learning tracks in your workspace.</p>
                </div>
                <div class="metric-card">
                    <p class="section-title">Weekly study</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ gmdate('G\hi', $weeklySeconds) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Tracked learning time since the start of the week.</p>
                </div>
                <div class="metric-card">
                    <p class="section-title">Notes</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $noteCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Captured ideas, summaries, and study references.</p>
                </div>
                <div class="metric-card">
                    <p class="section-title">Flashcards</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $deckCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Decks ready for review and spaced repetition.</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="card p-6">
                    <span class="eyebrow">Focus today</span>
                    <h3 class="mt-5 text-3xl font-bold text-slate-950">{{ $todayFocusMinutes }} minutes</h3>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600">
                        You have {{ $todaySessions }} planned {{ Str::plural('session', $todaySessions) }} and {{ $activeGoals }} active {{ Str::plural('goal', $activeGoals) }} supporting this week.
                    </p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="section-title">Goals</p>
                            <p class="mt-2 text-2xl font-bold text-slate-950">{{ $activeGoals }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="section-title">Planned today</p>
                            <p class="mt-2 text-2xl font-bold text-slate-950">{{ $todaySessions }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="section-title">Bookmarks</p>
                            <p class="mt-2 text-2xl font-bold text-slate-950">{{ $bookmarkedTopics->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="section-title">Quick access</p>
                            <h3 class="mt-2 text-xl font-bold text-slate-950">Jump back into the right tool</h3>
                        </div>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <a href="{{ route('topics.index') }}" class="rounded-2xl border border-slate-200 bg-white/80 p-4 transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-sm">
                            <p class="font-semibold text-slate-950">Topics</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $topicCount }} saved</p>
                        </a>
                        <a href="{{ route('flashcards.index') }}" class="rounded-2xl border border-slate-200 bg-white/80 p-4 transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-sm">
                            <p class="font-semibold text-slate-950">Flashcards</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $deckCount }} decks</p>
                        </a>
                        <a href="{{ route('planner.index') }}" class="rounded-2xl border border-slate-200 bg-white/80 p-4 transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-sm">
                            <p class="font-semibold text-slate-950">Planner</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $todaySessions }} today</p>
                        </a>
                        <a href="{{ route('focus.index') }}" class="rounded-2xl border border-slate-200 bg-white/80 p-4 transition hover:-translate-y-0.5 hover:border-cyan-200 hover:shadow-sm">
                            <p class="font-semibold text-slate-950">Focus</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $todayFocusMinutes }} min logged</p>
                        </a>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div>
                            <p class="section-title">Recent topics</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-950">What you started lately</h3>
                        </div>
                        <a href="{{ route('topics.index') }}" class="link">View all</a>
                    </div>
                    <div class="divide-y divide-slate-200/70">
                        @forelse ($recentTopics as $topic)
                            <div class="flex items-center gap-3 px-6 py-4">
                                <div class="h-2 w-2 shrink-0 rounded-full bg-cyan-500"></div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('topics.show', $topic) }}" class="block truncate text-sm font-semibold text-slate-900 hover:text-cyan-800">
                                        {{ $topic->title }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $topic->sections_count }} {{ Str::plural('section', $topic->sections_count) }}</p>
                                </div>
                                <span class="shrink-0 text-xs text-slate-400">{{ $topic->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-slate-500">
                                No topics yet. <a href="{{ route('topics.create') }}" class="link">Create your first topic</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div>
                            <p class="section-title">Recent notes</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-950">Ideas and summaries you captured</h3>
                        </div>
                        <a href="{{ route('notes.index') }}" class="link">View all</a>
                    </div>
                    <div class="divide-y divide-slate-200/70">
                        @forelse ($recentNotes as $note)
                            <div class="flex items-center gap-3 px-6 py-4">
                                <div class="h-2 w-2 shrink-0 rounded-full bg-amber-500"></div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('notes.show', $note) }}" class="block truncate text-sm font-semibold text-slate-900 hover:text-cyan-800">
                                        {{ $note->title }}
                                    </a>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach ($note->tags as $tag)
                                            <span class="rounded-full px-2 py-0.5 text-[10px]" style="background-color: {{ $tag->color ?? '#e5e7eb' }}20; color: {{ $tag->color ?? '#374151' }}">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <span class="shrink-0 text-xs text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-slate-500">
                                No notes yet. <a href="{{ route('notes.create') }}" class="link">Write your first note</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div>
                            <p class="section-title">Study sessions</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-950">Recent tracked work</h3>
                        </div>
                        <a href="{{ route('progress.index') }}" class="link">Progress</a>
                    </div>
                    <div class="divide-y divide-slate-200/70">
                        @forelse ($recentSessions as $session)
                            <div class="flex items-center gap-3 px-6 py-4">
                                <div class="h-2 w-2 shrink-0 rounded-full bg-green-500"></div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('topics.show', $session->topic) }}" class="block truncate text-sm font-semibold text-slate-900 hover:text-cyan-800">
                                        {{ $session->topic->title }}
                                    </a>
                                    <p class="text-xs text-slate-500">{{ $session->started_at->diffForHumans() }}</p>
                                </div>
                                @if ($session->duration_seconds)
                                    <span class="shrink-0 text-xs font-semibold text-slate-500">{{ gmdate('H:i:s', $session->duration_seconds) }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-slate-500">
                                No study sessions yet. <a href="{{ route('topics.index') }}" class="link">Start learning</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <p class="section-title">Current momentum</p>
                        <h3 class="mt-1 text-lg font-bold text-slate-950">Today and this week</h3>
                    </div>
                    <div class="space-y-4 p-6">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Active goals</p>
                                <p class="text-xs text-slate-500">{{ Str::plural('goal', $activeGoals) }} in progress</p>
                            </div>
                            <span class="badge-indigo">{{ $activeGoals }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Focus logged today</p>
                                <p class="text-xs text-slate-500">Minutes of intentional work</p>
                            </div>
                            <span class="text-sm font-bold text-slate-900">{{ $todayFocusMinutes }}m</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Planned sessions today</p>
                                <p class="text-xs text-slate-500">Your current schedule</p>
                            </div>
                            <span class="text-sm font-bold text-slate-900">{{ $todaySessions }}</span>
                        </div>
                    </div>
                </div>
            </section>

            @if ($bookmarkedTopics->isNotEmpty())
                <section class="card">
                    <div class="card-header">
                        <p class="section-title">Saved for later</p>
                        <h3 class="mt-1 text-lg font-bold text-slate-950">Bookmarked topics</h3>
                    </div>
                    <div class="flex gap-3 overflow-x-auto p-4">
                        @foreach ($bookmarkedTopics as $bookmark)
                            <a href="{{ route('topics.show', $bookmark->topic) }}" class="shrink-0 rounded-2xl bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-cyan-50 hover:text-cyan-900">
                                {{ $bookmark->topic->title }}
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
