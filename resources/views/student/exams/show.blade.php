<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $exam->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('exams.edit', $exam) }}" class="btn-ghost text-sm">Edit</a>
                <a href="{{ route('exams.index') }}" class="btn-ghost text-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        @php
                            $statusBadge = match($exam->status) {
                                'upcoming' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                'taken' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                            };
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ ucfirst($exam->status) }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300">{{ ucfirst($exam->exam_type) }}</span>
                    </div>
                    @if ($exam->status == 'upcoming')
                        @php $daysLeft = now()->startOfDay()->diffInDays($exam->exam_date, false); @endphp
                        @if ($daysLeft >= 0)
                            <span class="text-sm font-semibold {{ $daysLeft <= 1 ? 'text-red-500' : 'text-cyan-600 dark:text-cyan-400' }}">
                                {{ $daysLeft == 0 ? 'Today!' : $daysLeft == 1 ? 'Tomorrow' : $daysLeft . ' days away' }}
                            </span>
                        @endif
                    @endif
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Course</dt>
                        <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->course }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Exam Date</dt>
                        <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->exam_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Start Time</dt>
                        <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->start_time ? $exam->start_time->format('g:i A') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Duration</dt>
                        <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->duration_minutes ? $exam->duration_minutes . ' min' : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Location</dt>
                        <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->location ?: '—' }}</dd>
                    </div>
                    @if ($exam->status == 'taken' && $exam->grade !== null)
                        <div>
                            <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Grade</dt>
                            <dd class="font-medium text-slate-900 dark:text-slate-100 mt-1">{{ $exam->grade }} / {{ $exam->max_grade ?? '—' }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($exam->notes)
                    <div class="mt-6 pt-4 border-t border-slate-200/70 dark:border-slate-700/60">
                        <dt class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider mb-2">Notes</dt>
                        <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $exam->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('exams.edit', $exam) }}" class="link">Edit exam</a>
                <form method="POST" action="{{ route('exams.destroy', $exam) }}" onsubmit="return confirm('Delete this exam?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
