<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Grades</h2>
            <a href="{{ route('grades.create') }}" class="btn-primary text-sm">+ Add Grade</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @php
                $totalCredits = $grades->sum('credits');
                $totalPoints = $grades->sum(fn($g) => $g->credits * $g->grade_points);
                $cgpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card p-5 text-center">
                    <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400">{{ number_format($cgpa, 2) }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">CGPA</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalCredits, 1) }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total Credits</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="text-3xl font-bold text-emerald-500">{{ $grades->count() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Courses Taken</p>
                </div>
            </div>

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <select name="semester" class="input text-sm">
                            <option value="">All semesters</option>
                            <option value="Spring" @selected(request('semester') == 'Spring')>Spring</option>
                            <option value="Summer" @selected(request('semester') == 'Summer')>Summer</option>
                            <option value="Fall" @selected(request('semester') == 'Fall')>Fall</option>
                        </select>
                    </div>
                    <div>
                        <select name="year" class="input text-sm">
                            <option value="">All years</option>
                            @foreach (range(now()->year, now()->year - 6) as $y)
                                <option value="{{ $y }}" @selected((int)request('year') == $y)>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800 text-left text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 font-medium">Course</th>
                            <th class="px-4 py-3 font-medium">Credits</th>
                            <th class="px-4 py-3 font-medium">Score</th>
                            <th class="px-4 py-3 font-medium">Letter Grade</th>
                            <th class="px-4 py-3 font-medium">Grade Points</th>
                            <th class="px-4 py-3 font-medium">Semester</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                        @forelse ($grades as $grade)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-slate-900 dark:text-slate-100">{{ $grade->course }}</span>
                                        @if ($grade->is_elective)
                                            <span class="text-xs px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">Elective</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ number_format($grade->credits, 1) }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $grade->score !== null ? number_format($grade->score, 2) : '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold {{ $grade->grade_points >= 3 ? 'text-emerald-600 dark:text-emerald-400' : ($grade->grade_points >= 2 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $grade->letter_grade ?: '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $grade->grade_points !== null ? number_format($grade->grade_points, 2) : '—' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $grade->semester }} {{ $grade->year }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('grades.edit', $grade) }}" class="text-xs text-cyan-600 hover:underline dark:text-cyan-400">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12">
                                    <div class="empty-state">
                                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No grades yet</p>
                                        <p class="text-sm text-slate-500 mb-4">Add your grades to track your academic performance.</p>
                                        <a href="{{ route('grades.create') }}" class="btn-primary text-sm">Add your first grade</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($grades->hasPages())
                <div class="mt-6">{{ $grades->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
