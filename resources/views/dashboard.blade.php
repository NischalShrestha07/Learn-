<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Stats row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">This week</p>
                    <p class="text-3xl font-semibold">{{ gmdate('H\h i\m', $weeklySeconds) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">study time</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">In progress</p>
                    <p class="text-3xl font-semibold">{{ $progressCounts['in_progress'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">topics</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Completed</p>
                    <p class="text-3xl font-semibold">{{ $progressCounts['completed'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">topics</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Recent sessions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Recent Sessions</h3>
                        <a href="{{ route('progress.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentSessions as $session)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('topics.show', $session->topic->slug) }}" class="font-medium text-sm hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $session->topic->title }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $session->started_at->diffForHumans() }}</p>
                                </div>
                                @if($session->duration_seconds)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ gmdate('i:s', $session->duration_seconds) }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No sessions yet. <a href="{{ route('topics.search', ['q' => 'python']) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Start learning</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Bookmarks --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Bookmarks</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($bookmarkedTopics as $bookmark)
                            <div class="px-6 py-4">
                                <a href="{{ route('topics.show', $bookmark->topic->slug) }}" class="font-medium text-sm hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $bookmark->topic->title }}
                                </a>
                                @if($bookmark->topic->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $bookmark->topic->description }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                No bookmarks yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick search --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold mb-4">Search a Topic</h3>
                <form action="{{ route('topics.search') }}" method="GET" class="flex gap-3 max-w-lg">
                    <input
                        type="text"
                        name="q"
                        placeholder="e.g. React hooks, SQL joins, neural networks..."
                        class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    />
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                        Search
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
