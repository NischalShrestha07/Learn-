<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $note->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('notes.edit', $note) }}" class="btn-ghost text-sm">Edit</a>
                <a href="{{ route('notes.index') }}" class="btn-ghost text-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="card p-8">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-4">
                    <span>{{ $note->created_at->format('M d, Y g:i A') }}</span>
                    @if ($note->topic)
                        <span>&middot;</span>
                        <a href="{{ route('topics.show', $note->topic) }}" class="link text-xs">{{ $note->topic->title }}</a>
                    @endif
                    @if ($note->is_public)
                        <span class="badge-green">Public</span>
                    @endif
                </div>

                @if ($note->tags->isNotEmpty())
                    <div class="flex gap-2 mb-6">
                        @foreach ($note->tags as $tag)
                            <span class="rounded-full border border-slate-200/60 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:border-slate-600/60 dark:bg-slate-700 dark:text-slate-300">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="prose-custom">
                    {!! Str::of($note->content)->markdown() !!}
                </div>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('notes.edit', $note) }}" class="link">Edit note</a>
                <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
