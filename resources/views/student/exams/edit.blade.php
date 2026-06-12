<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Exam</h2>
            <a href="{{ route('exams.show', $exam) }}" class="btn-ghost text-sm">Cancel</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('exams.update', $exam) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="title">Title</x-input-label>
                            <input type="text" name="title" id="title" value="{{ old('title', $exam->title) }}" required class="input">
                            @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="course">Course</x-input-label>
                            <input type="text" name="course" id="course" value="{{ old('course', $exam->course) }}" required class="input">
                            @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="exam_type">Exam Type</x-input-label>
                            <select name="exam_type" id="exam_type" required class="input">
                                <option value="">Select type</option>
                                <option value="quiz" @selected(old('exam_type', $exam->exam_type) == 'quiz')>Quiz</option>
                                <option value="midterm" @selected(old('exam_type', $exam->exam_type) == 'midterm')>Midterm</option>
                                <option value="final" @selected(old('exam_type', $exam->exam_type) == 'final')>Final</option>
                                <option value="presentation" @selected(old('exam_type', $exam->exam_type) == 'presentation')>Presentation</option>
                                <option value="other" @selected(old('exam_type', $exam->exam_type) == 'other')>Other</option>
                            </select>
                            @error('exam_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="exam_date">Exam Date</x-input-label>
                            <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date', $exam->exam_date?->format('Y-m-d')) }}" required class="input">
                            @error('exam_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="start_time">Start Time</x-input-label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $exam->start_time?->format('H:i')) }}" class="input">
                            @error('start_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="duration_minutes">Duration (minutes)</x-input-label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" class="input">
                            @error('duration_minutes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="location">Location</x-input-label>
                            <input type="text" name="location" id="location" value="{{ old('location', $exam->location) }}" class="input">
                            @error('location') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status">Status</x-input-label>
                        <select name="status" id="status" class="input">
                            <option value="upcoming" @selected(old('status', $exam->status) == 'upcoming')>Upcoming</option>
                            <option value="taken" @selected(old('status', $exam->status) == 'taken')>Taken</option>
                            <option value="cancelled" @selected(old('status', $exam->status) == 'cancelled')>Cancelled</option>
                        </select>
                        @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="grade">Grade</x-input-label>
                            <input type="number" name="grade" id="grade" value="{{ old('grade', $exam->grade) }}" step="0.01" min="0" class="input">
                            @error('grade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="max_grade">Max Grade</x-input-label>
                            <input type="number" name="max_grade" id="max_grade" value="{{ old('max_grade', $exam->max_grade) }}" step="0.01" min="0" class="input">
                            @error('max_grade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes">Notes</x-input-label>
                        <textarea name="notes" id="notes" rows="4" class="input">{{ old('notes', $exam->notes) }}</textarea>
                        @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('exams.show', $exam) }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Update Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
