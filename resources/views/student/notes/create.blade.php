<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">New Note</h2>
            <a href="{{ route('notes.index') }}" class="btn-ghost text-sm">Back to notes</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                <form method="POST" action="{{ route('notes.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="content">Content</x-input-label>
                        <textarea name="content" id="content" rows="15" required class="input font-mono">{{ old('content') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Supports Markdown format</p>
                        @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="topic_id">Related Topic (optional)</x-input-label>
                            <select name="topic_id" id="topic_id" class="input">
                                <option value="">None</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" @selected(old('topic_id') == $topic->id)>{{ $topic->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="tags">Tags</x-input-label>
                            <select name="tags[]" id="tags" multiple class="input">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', [])))>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl to select multiple</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_public" id="is_public" value="1" @checked(old('is_public')) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_public" class="text-sm text-gray-600 dark:text-gray-400">Make public</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('notes.index') }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Save Note</button>
                    </div>
                </form>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-3">Quick Tag</h3>
                <form method="POST" action="{{ route('notes.tags.store') }}" class="flex gap-3 items-end">
                    @csrf
                    <div>
                        <input type="text" name="name" placeholder="Tag name" required class="input text-sm">
                    </div>
                    <div>
                        <input type="color" name="color" value="#6366f1" class="h-[38px] w-[50px] rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Add</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
