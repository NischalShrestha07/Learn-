<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">New Deck</h2>
            <a href="{{ route('flashcards.index') }}" class="btn-ghost text-sm">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('flashcards.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title">Deck Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input" placeholder="e.g. Spanish Vocabulary, CS Fundamentals">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description (optional)</x-input-label>
                        <textarea name="description" id="description" rows="2" class="input">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="topic_id">Related Topic (optional)</x-input-label>
                        <select name="topic_id" id="topic_id" class="input">
                            <option value="">None</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('flashcards.index') }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Create Deck</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
