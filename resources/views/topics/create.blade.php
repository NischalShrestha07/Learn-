<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">New Topic</h2>
            <a href="{{ route('topics.index') }}" class="btn-ghost text-sm">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('topics.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title">Topic Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="input" placeholder="e.g. Calculus, Machine Learning, Spanish Grammar">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description</x-input-label>
                        <textarea name="description" id="description" rows="3"
                            class="input" placeholder="What do you want to learn?">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('topics.index') }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Create Topic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
