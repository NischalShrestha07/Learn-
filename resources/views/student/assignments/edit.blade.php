<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Assignment</h2>
            <a href="{{ route('assignments.show', $assignment) }}" class="btn-ghost text-sm">Cancel</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('assignments.update', $assignment) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="title">Title</x-input-label>
                        <input type="text" name="title" id="title" value="{{ old('title', $assignment->title) }}" required class="input">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="description">Description</x-input-label>
                        <textarea name="description" id="description" rows="5" required class="input">{{ old('description', $assignment->description) }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="course">Course</x-input-label>
                            <input type="text" name="course" id="course" value="{{ old('course', $assignment->course) }}" required class="input">
                            @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="priority">Priority</x-input-label>
                            <select name="priority" id="priority" class="input">
                                <option value="low" @selected(old('priority', $assignment->priority) == 'low')>Low</option>
                                <option value="medium" @selected(old('priority', $assignment->priority) == 'medium')>Medium</option>
                                <option value="high" @selected(old('priority', $assignment->priority) == 'high')>High</option>
                            </select>
                            @error('priority') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="due_date">Due Date</x-input-label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $assignment->due_date?->format('Y-m-d')) }}" class="input">
                            @error('due_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="due_time">Due Time (optional)</x-input-label>
                            <input type="time" name="due_time" id="due_time" value="{{ old('due_time', $assignment->due_time?->format('H:i')) }}" class="input">
                            @error('due_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status">Status</x-input-label>
                        <select name="status" id="status" class="input">
                            <option value="pending" @selected(old('status', $assignment->status) == 'pending')>Pending</option>
                            <option value="submitted" @selected(old('status', $assignment->status) == 'submitted')>Submitted</option>
                            <option value="graded" @selected(old('status', $assignment->status) == 'graded')>Graded</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="grade">Grade (optional)</x-input-label>
                            <input type="number" name="grade" id="grade" step="0.01" value="{{ old('grade', $assignment->grade) }}" class="input">
                            @error('grade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="max_grade">Max Grade (optional)</x-input-label>
                            <input type="number" name="max_grade" id="max_grade" step="0.01" value="{{ old('max_grade', $assignment->max_grade) }}" class="input">
                            @error('max_grade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes">Notes (optional)</x-input-label>
                        <textarea name="notes" id="notes" rows="4" class="input">{{ old('notes', $assignment->notes) }}</textarea>
                        @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('assignments.show', $assignment) }}" class="btn-ghost text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Update Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
