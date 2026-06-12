<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Notes</h2>
            <a href="{{ route('notes.create') }}" class="btn-primary text-sm">+ New Note</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search notes..." class="input text-sm">
                    </div>
                    <div>
                        <select name="tag" class="input text-sm">
                            <option value="">All tags</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(request('tag') == $tag->id)>{{ $tag->name }} ({{ $tag->notes_count }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($notes as $note)
                    <div class="card hover:shadow-md transition group">
                        <a href="{{ route('notes.show', $note) }}" class="block p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-1">{{ $note->title }}</h3>
                                <span class="text-xs text-gray-400 ml-2 shrink-0">{{ $note->created_at->format('M d') }}</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 mb-3">{{ Str::limit(strip_tags($note->content), 150) }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach ($note->tags as $tag)
                                    <span class="text-xs px-2 py-0.5 rounded-full" style="background-color: {{ $tag->color ?? '#e5e7eb' }}20; color: {{ $tag->color ?? '#374151' }}">{{ $tag->name }}</span>
                                @endforeach
                                @if ($note->topic)
                                    <span class="text-xs text-indigo-600 dark:text-indigo-400">{{ $note->topic->title }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No notes yet</p>
                        <p class="text-sm text-gray-500 mb-4">Start taking notes on your learning topics.</p>
                        <a href="{{ route('notes.create') }}" class="btn-primary text-sm">Create your first note</a>
                    </div>
                @endforelse
            </div>

            @if ($notes->hasPages())
                <div class="mt-6">{{ $notes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
