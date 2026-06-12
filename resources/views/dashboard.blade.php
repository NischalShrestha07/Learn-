<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Dashboard</h2>
            <span class="text-sm text-gray-500">{{ now()->format('l, F j') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Stats row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="card p-5">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Topics</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $topicCount }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Study Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ gmdate('G\hi', $weeklySeconds) }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">this week</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Notes</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $noteCount }}</p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium mb-1">Decks</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $deckCount }}</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('topics.index') }}" class="card p-4 text-center hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                    <p class="text-xl mb-1">📚</p>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Topics</p>
                </a>
                <a href="{{ route('notes.index') }}" class="card p-4 text-center hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                    <p class="text-xl mb-1">📝</p>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Notes</p>
                </a>
                <a href="{{ route('flashcards.index') }}" class="card p-4 text-center hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                    <p class="text-xl mb-1">🃏</p>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Flashcards</p>
                </a>
                <a href="{{ route('focus.index') }}" class="card p-4 text-center hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                    <p class="text-xl mb-1">⏱</p>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Focus</p>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent topics --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Recent Topics</h3>
                        <a href="{{ route('topics.index') }}" class="link">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($recentTopics as $topic)
                            <div class="px-6 py-3.5">
                                <a href="{{ route('topics.show', $topic) }}" class="font-medium text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">{{ $topic->title }}</a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $topic->sections_count }} {{ Str::plural('section', $topic->sections_count) }}</p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No topics yet. <a href="{{ route('topics.create') }}" class="link">Create one</a></div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent notes --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Recent Notes</h3>
                        <a href="{{ route('notes.index') }}" class="link">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($recentNotes as $note)
                            <div class="px-6 py-3.5">
                                <a href="{{ route('notes.show', $note) }}" class="font-medium text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">{{ $note->title }}</a>
                                <div class="flex gap-1 mt-1">
                                    @foreach ($note->tags as $tag)
                                        <span class="text-xs px-1.5 py-0.5 rounded" style="background-color: {{ $tag->color ?? '#e5e7eb' }}20; color: {{ $tag->color ?? '#374151' }}">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No notes yet. <a href="{{ route('notes.create') }}" class="link">Create one</a></div>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent sessions --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Study Sessions</h3>
                        <a href="{{ route('progress.index') }}" class="link">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($recentSessions as $session)
                            <div class="px-6 py-3.5 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('topics.show', $session->topic) }}" class="font-medium text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">{{ $session->topic->title }}</a>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $session->started_at->diffForHumans() }}</p>
                                </div>
                                @if ($session->duration_seconds)
                                    <span class="text-xs text-gray-500">{{ gmdate('i:s', $session->duration_seconds) }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No study sessions yet. <a href="{{ route('topics.index') }}" class="link">Start learning</a></div>
                        @endforelse
                    </div>
                </div>

                {{-- Activity summary --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Activity</h3>
                        <a href="{{ route('planner.index') }}" class="link">Planner</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        <div class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Active Goals</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $activeGoals }} {{ Str::plural('goal', $activeGoals) }} in progress</p>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Focus Today</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $todayFocusMinutes }} minutes focused</p>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Planned Today</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $todaySessions }} {{ Str::plural('session', $todaySessions) }} scheduled</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Bookmarks --}}
            @if ($bookmarkedTopics->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Bookmarks</h3>
                    </div>
                    <div class="flex gap-3 p-4 overflow-x-auto">
                        @foreach ($bookmarkedTopics as $bookmark)
                            <a href="{{ route('topics.show', $bookmark->topic) }}" class="shrink-0 px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-700 dark:hover:text-indigo-300 transition">
                                {{ $bookmark->topic->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
