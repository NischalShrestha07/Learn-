<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Topics</h2>
            <a href="{{ route('topics.create') }}" class="btn-primary text-sm">+ New Topic</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($topics as $topic)
                    <div class="card hover:shadow-md transition group">
                        <a href="{{ route('topics.show', $topic) }}" class="block p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $topic->title }}</h3>
                                <span class="text-xs text-gray-400">{{ $topic->created_at->format('M d') }}</span>
                            </div>
                            @if ($topic->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ $topic->description }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span>{{ $topic->sections_count }} {{ Str::plural('section', $topic->sections_count) }}</span>
                                <span>{{ $topic->learning_sessions_count }} sessions</span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No topics yet</p>
                        <p class="text-sm text-gray-500 mb-4">Create your first learning topic to get started.</p>
                        <a href="{{ route('topics.create') }}" class="btn-primary text-sm">Create a topic</a>
                    </div>
                @endforelse
            </div>

            @if ($topics->hasPages())
                <div class="mt-6">{{ $topics->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
