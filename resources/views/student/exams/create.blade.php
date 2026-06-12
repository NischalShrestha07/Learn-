<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">New Exam</h2>
            <a href="{{ route('exams.index') }}" class="btn-ghost text-sm">Back to exams</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('exams.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="title">Title</x-input-label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input">
                            @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="course">Course</x-input-label>
                            <input type="text" name="course" id="course" value="{{ old('course') }}" required class="input">
                            @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="exam_type">Exam Type</x-input-label>
                            <select name="exam_type" id="exam_type" required class="input">
                                <option value="">Select type</option>
                                <option value="quiz" @selected(old('exam_type') == 'quiz')>Quiz</option>
                                <option value="midterm" @selected(old('exam_type') == 'midterm')>Midterm</option>
                                <option value="final" @selected(old('exam_type') == 'final')>Final</option>
                                <option value="presentation" @selected(old('exam_type') == 'presentation')>Presentation</option>
                                <option value="other" @selected(old('exam_type') == 'other')>Other</option>
                            </select>
                            @error('exam_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="exam_date">Exam Date</x-input-label>
                            <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}" required class="input">
                            @error('exam_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="start_time">Start Time</x-input-label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="input">
                            @error('start_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="duration_minutes">Duration (minutes)</x-input-label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="1" class="input">
                            @error('duration_minutes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="location">Location</x-input-label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" class="input">
                            @error('location') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes">Notes</x-input-label>
                        <textarea name="notes" id="notes" rows="4" class="input">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('exams.index') }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Save Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
