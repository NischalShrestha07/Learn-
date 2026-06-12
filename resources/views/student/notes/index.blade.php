<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="eyebrow">Knowledge capture</span>
                <h2 class="mt-3 text-2xl font-bold text-slate-950">My Notes</h2>
                <p class="mt-1 text-sm text-slate-500">Search, tag, and connect study notes so they stay useful later.</p>
            </div>
            <a href="{{ route('notes.create') }}" class="btn-primary text-sm">Create note</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="card p-4 sm:p-5">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <div class="min-w-[220px] flex-1">
                        <label for="q" class="section-title">Search</label>
                        <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Search notes..." class="input mt-2 text-sm">
                    </div>
                    <div class="min-w-[200px]">
                        <label for="tag" class="section-title">Tag</label>
                        <select id="tag" name="tag" class="input mt-2 text-sm">
                            <option value="">All tags</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(request('tag') == $tag->id)>{{ $tag->name }} ({{ $tag->notes_count }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Apply filters</button>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($notes as $note)
                    <div class="card transition hover:-translate-y-0.5 hover:shadow-[0_22px_50px_-30px_rgba(8,47,73,0.38)]">
                        <a href="{{ route('notes.show', $note) }}" class="block p-6">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-2 text-lg font-bold text-slate-950">{{ $note->title }}</h3>
                                <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">{{ $note->created_at->format('M d') }}</span>
                            </div>
                            <p class="mt-4 line-clamp-4 text-sm leading-6 text-slate-600">{{ Str::limit(strip_tags($note->content), 170) }}</p>
                            <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-slate-200/80 pt-4">
                                @foreach ($note->tags as $tag)
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold" style="background-color: {{ $tag->color ?? '#e5e7eb' }}20; color: {{ $tag->color ?? '#374151' }}">{{ $tag->name }}</span>
                                @endforeach
                                @if ($note->topic)
                                    <span class="text-xs font-semibold text-cyan-800">{{ $note->topic->title }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="empty-state col-span-full">
                        <p class="text-base font-semibold text-slate-950">No notes yet</p>
                        <p class="mt-1 text-sm text-slate-500">Start taking notes on your topics so your study sessions leave behind useful material.</p>
                        <a href="{{ route('notes.create') }}" class="btn-primary mt-5 text-sm">Create your first note</a>
                    </div>
                @endforelse
            </div>

            @if ($notes->hasPages())
                <div class="mt-6">{{ $notes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
