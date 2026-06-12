<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Exams</h2>
            <a href="{{ route('exams.create') }}" class="btn-primary text-sm">+ New Exam</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @php
                $upcoming = $exams->where('status', 'upcoming')->sortBy('exam_date')->take(3);
            @endphp

            @if ($upcoming->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 uppercase tracking-wider">Upcoming Exams</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($upcoming as $exam)
                            @php
                                $daysLeft = now()->startOfDay()->diffInDays($exam->exam_date, false);
                            @endphp
                            <div class="card border-l-4 border-cyan-500 p-5">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-slate-900 dark:text-slate-100">{{ $exam->title }}</h4>
                                    @if ($daysLeft >= 0)
                                        <span class="text-xs font-bold {{ $daysLeft <= 1 ? 'text-red-500' : 'text-cyan-500' }} shrink-0 ml-2">
                                            {{ $daysLeft == 0 ? 'Today!' : $daysLeft == 1 ? 'Tomorrow' : $daysLeft . ' days' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mb-1">{{ $exam->course }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $exam->exam_date->format('M d, Y') }} @if ($exam->start_time) at {{ $exam->start_time->format('g:i A') }} @endif</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300">{{ ucfirst($exam->exam_type) }}</span>
                                    @if ($exam->location)
                                        <span class="text-xs text-slate-400">{{ $exam->location }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search exams..." class="input text-sm">
                    </div>
                    <div>
                        <select name="status" class="input text-sm">
                            <option value="">All statuses</option>
                            <option value="upcoming" @selected(request('status') == 'upcoming')>Upcoming</option>
                            <option value="taken" @selected(request('status') == 'taken')>Taken</option>
                            <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <select name="exam_type" class="input text-sm">
                            <option value="">All types</option>
                            <option value="quiz" @selected(request('exam_type') == 'quiz')>Quiz</option>
                            <option value="midterm" @selected(request('exam_type') == 'midterm')>Midterm</option>
                            <option value="final" @selected(request('exam_type') == 'final')>Final</option>
                            <option value="presentation" @selected(request('exam_type') == 'presentation')>Presentation</option>
                            <option value="other" @selected(request('exam_type') == 'other')>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($exams as $exam)
                    <div class="card hover:shadow-md transition group">
                        <a href="{{ route('exams.show', $exam) }}" class="block p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100 line-clamp-1">{{ $exam->title }}</h3>
                                <span class="text-xs text-slate-400 ml-2 shrink-0">{{ $exam->exam_date->format('M d') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1">{{ $exam->course }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ Str::limit($exam->notes, 120) }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                @php
                                    $statusBadge = match($exam->status) {
                                        'upcoming' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                        'taken' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ ucfirst($exam->status) }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">{{ ucfirst($exam->exam_type) }}</span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No exams yet</p>
                        <p class="text-sm text-slate-500 mb-4">Add your first exam to stay on top of your schedule.</p>
                        <a href="{{ route('exams.create') }}" class="btn-primary text-sm">Create your first exam</a>
                    </div>
                @endforelse
            </div>

            @if ($exams->hasPages())
                <div class="mt-6">{{ $exams->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
