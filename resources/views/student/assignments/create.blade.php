<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">New Assignment</h2>
            <a href="{{ route('assignments.index') }}" class="btn-ghost text-sm">Back to assignments</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('assignments.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description</x-input-label>
                        <textarea name="description" id="description" rows="5" required class="input">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="course">Course</x-input-label>
                            <input type="text" name="course" id="course" value="{{ old('course') }}" required class="input">
                            @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="priority">Priority</x-input-label>
                            <select name="priority" id="priority" class="input">
                                <option value="low" @selected(old('priority') == 'low')>Low</option>
                                <option value="medium" @selected(old('priority') == 'medium') selected>Medium</option>
                                <option value="high" @selected(old('priority') == 'high')>High</option>
                            </select>
                            @error('priority') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="due_date">Due Date</x-input-label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="input">
                            @error('due_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="due_time">Due Time (optional)</x-input-label>
                            <input type="time" name="due_time" id="due_time" value="{{ old('due_time') }}" class="input">
                            @error('due_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes">Notes (optional)</x-input-label>
                        <textarea name="notes" id="notes" rows="4" class="input">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('assignments.index') }}" class="btn-ghost text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Save Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
