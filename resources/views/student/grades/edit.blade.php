<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Grade</h2>
            <a href="{{ route('grades.index') }}" class="btn-ghost text-sm">Back to grades</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('grades.update', $grade) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="course">Course</x-input-label>
                            <input type="text" name="course" id="course" value="{{ old('course', $grade->course) }}" required class="input">
                            @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="credits">Credits</x-input-label>
                            <input type="number" name="credits" id="credits" value="{{ old('credits', $grade->credits) }}" step="0.1" min="0" required class="input">
                            @error('credits') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="score">Score</x-input-label>
                            <input type="number" name="score" id="score" value="{{ old('score', $grade->score) }}" step="0.01" min="0" class="input">
                            @error('score') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="letter_grade">Letter Grade</x-input-label>
                            <select name="letter_grade" id="letter_grade" class="input">
                                <option value="">Select grade</option>
                                <option value="A+" @selected(old('letter_grade', $grade->letter_grade) == 'A+')>A+</option>
                                <option value="A" @selected(old('letter_grade', $grade->letter_grade) == 'A')>A</option>
                                <option value="A-" @selected(old('letter_grade', $grade->letter_grade) == 'A-')>A-</option>
                                <option value="B+" @selected(old('letter_grade', $grade->letter_grade) == 'B+')>B+</option>
                                <option value="B" @selected(old('letter_grade', $grade->letter_grade) == 'B')>B</option>
                                <option value="B-" @selected(old('letter_grade', $grade->letter_grade) == 'B-')>B-</option>
                                <option value="C+" @selected(old('letter_grade', $grade->letter_grade) == 'C+')>C+</option>
                                <option value="C" @selected(old('letter_grade', $grade->letter_grade) == 'C')>C</option>
                                <option value="C-" @selected(old('letter_grade', $grade->letter_grade) == 'C-')>C-</option>
                                <option value="D+" @selected(old('letter_grade', $grade->letter_grade) == 'D+')>D+</option>
                                <option value="D" @selected(old('letter_grade', $grade->letter_grade) == 'D')>D</option>
                                <option value="F" @selected(old('letter_grade', $grade->letter_grade) == 'F')>F</option>
                            </select>
                            @error('letter_grade') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="grade_points">Grade Points</x-input-label>
                            <input type="number" name="grade_points" id="grade_points" value="{{ old('grade_points', $grade->grade_points) }}" step="0.01" min="0" max="4" class="input">
                            @error('grade_points') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="semester">Semester</x-input-label>
                            <select name="semester" id="semester" required class="input">
                                <option value="">Select semester</option>
                                <option value="Spring" @selected(old('semester', $grade->semester) == 'Spring')>Spring</option>
                                <option value="Summer" @selected(old('semester', $grade->semester) == 'Summer')>Summer</option>
                                <option value="Fall" @selected(old('semester', $grade->semester) == 'Fall')>Fall</option>
                            </select>
                            @error('semester') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <x-input-label for="year">Year</x-input-label>
                            <select name="year" id="year" required class="input">
                                @foreach (range(now()->year, now()->year - 6) as $y)
                                    <option value="{{ $y }}" @selected(old('year', $grade->year) == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('year') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_elective" id="is_elective" value="1" @checked(old('is_elective', $grade->is_elective)) class="rounded border-slate-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                        <label for="is_elective" class="text-sm text-slate-600 dark:text-slate-400">This is an elective course</label>
                    </div>

                    <div>
                        <x-input-label for="notes">Notes</x-input-label>
                        <textarea name="notes" id="notes" rows="3" class="input">{{ old('notes', $grade->notes) }}</textarea>
                        @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('grades.index') }}" class="btn-secondary text-sm">Cancel</a>
                        <button type="submit" class="btn-primary text-sm">Update Grade</button>
                    </div>
                </form>

                <div class="mt-6 pt-4 border-t border-slate-200/70 dark:border-slate-700/60">
                    <form method="POST" action="{{ route('grades.destroy', $grade) }}" onsubmit="return confirm('Delete this grade?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger text-sm">Delete Grade</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
