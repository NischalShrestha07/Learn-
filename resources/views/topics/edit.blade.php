<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Topic</h2>
            <a href="{{ route('topics.show', $topic) }}" class="btn-ghost text-sm">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card p-6">
                <form method="POST" action="{{ route('topics.update', $topic) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title', $topic->title) }}" required class="input">
                    </div>

                    <div>
                        <x-input-label for="description">Description</x-input-label>
                        <textarea name="description" id="description" rows="3" class="input">{{ old('description', $topic->description) }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="status">Status</x-input-label>
                        <select name="status" id="status" class="input">
                            <option value="active" @selected(old('status', $topic->status) === 'active')>Active</option>
                            <option value="archived" @selected(old('status', $topic->status) === 'archived')>Archived</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('topics.show', $topic) }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Update Topic</button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-200/70 dark:border-slate-700/60">
                    <form method="POST" action="{{ route('topics.destroy', $topic) }}"
                        onsubmit="return confirm('Delete this topic and all its content?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Delete Topic</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
