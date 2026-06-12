<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Note</h2>
            <a href="{{ route('notes.show', $note) }}" class="btn-ghost text-sm">Cancel</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title', $note->title) }}" required class="input">
                    </div>

                    <div>
                        <x-input-label for="content">Content</x-input-label>
                        <textarea name="content" id="content" rows="15" required class="input font-mono">{{ old('content', $note->content) }}</textarea>
                        <p class="text-xs text-slate-500 mt-1">Supports Markdown</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="topic_id">Related Topic</x-input-label>
                            <select name="topic_id" id="topic_id" class="input">
                                <option value="">None</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" @selected(old('topic_id', $note->topic_id) == $topic->id)>{{ $topic->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="tags">Tags</x-input-label>
                            <select name="tags[]" id="tags" multiple class="input">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $note->tags->pluck('id')->toArray())))>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_public" id="is_public" value="1" @checked(old('is_public', $note->is_public)) class="rounded border-slate-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                        <label for="is_public" class="text-sm text-slate-600 dark:text-slate-400">Make public</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('notes.show', $note) }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Update Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
