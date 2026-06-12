<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Assignments</h2>
            <a href="{{ route('assignments.create') }}" class="btn-primary text-sm">+ New Assignment</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pending</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-yellow-500">{{ $stats['submitted'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Submitted</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-500">{{ $stats['graded'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Graded</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-2xl font-bold text-red-500">{{ $stats['overdue'] ?? 0 }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Overdue</p>
                </div>
            </div>

            <div class="card p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search assignments..." class="input text-sm">
                    </div>
                    <div>
                        <select name="status" class="input text-sm">
                            <option value="">All statuses</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="submitted" @selected(request('status') == 'submitted')>Submitted</option>
                            <option value="graded" @selected(request('status') == 'graded')>Graded</option>
                        </select>
                    </div>
                    <div>
                        <select name="priority" class="input text-sm">
                            <option value="">All priorities</option>
                            <option value="low" @selected(request('priority') == 'low')>Low</option>
                            <option value="medium" @selected(request('priority') == 'medium')>Medium</option>
                            <option value="high" @selected(request('priority') == 'high')>High</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($assignments as $assignment)
                    <div class="card hover:shadow-md transition group">
                        <a href="{{ route('assignments.show', $assignment) }}" class="block p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100 line-clamp-1">{{ $assignment->title }}</h3>
                                <span class="text-xs text-slate-400 ml-2 shrink-0">{{ $assignment->due_date->format('M d') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1">{{ $assignment->course }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ Str::limit($assignment->description, 120) }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                @php
                                    $statusBadge = match($assignment->status) {
                                        'pending' => 'badge-pending',
                                        'submitted' => 'badge-submitted',
                                        'graded' => 'badge-graded',
                                        default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ ucfirst($assignment->status) }}</span>
                                @php
                                    $priorityBadge = match($assignment->priority) {
                                        'low' => 'badge-low',
                                        'medium' => 'badge-medium',
                                        'high' => 'badge-high',
                                        default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $priorityBadge }}">{{ ucfirst($assignment->priority) }}</span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No assignments yet</p>
                        <p class="text-sm text-slate-500 mb-4">Create your first assignment to get started.</p>
                        <a href="{{ route('assignments.create') }}" class="btn-primary text-sm">Create your first assignment</a>
                    </div>
                @endforelse
            </div>

            @if ($assignments->hasPages())
                <div class="mt-6">{{ $assignments->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
