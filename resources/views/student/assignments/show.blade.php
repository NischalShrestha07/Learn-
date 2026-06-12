<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $assignment->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('assignments.edit', $assignment) }}" class="btn-ghost text-sm">Edit</a>
                <a href="{{ route('assignments.index') }}" class="btn-ghost text-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-4">
                    @php
                        $statusBadge = match($assignment->status) {
                            'pending' => 'badge-pending',
                            'submitted' => 'badge-submitted',
                            'graded' => 'badge-graded',
                            default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                        };
                        $priorityBadge = match($assignment->priority) {
                            'low' => 'badge-low',
                            'medium' => 'badge-medium',
                            'high' => 'badge-high',
                            default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                        };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ ucfirst($assignment->status) }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $priorityBadge }}">{{ ucfirst($assignment->priority) }} priority</span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs">Course</dt>
                        <dd class="text-slate-900 dark:text-slate-100 font-medium">{{ $assignment->course }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 dark:text-slate-400 text-xs">Due Date</dt>
                        <dd class="text-slate-900 dark:text-slate-100 font-medium">{{ $assignment->due_date->format('M d, Y') }}</dd>
                    </div>
                    @if ($assignment->due_time)
                        <div>
                            <dt class="text-slate-500 dark:text-slate-400 text-xs">Due Time</dt>
                            <dd class="text-slate-900 dark:text-slate-100 font-medium">{{ $assignment->due_time->format('g:i A') }}</dd>
                        </div>
                    @endif
                    @if ($assignment->grade !== null)
                        <div>
                            <dt class="text-slate-500 dark:text-slate-400 text-xs">Grade</dt>
                            <dd class="text-slate-900 dark:text-slate-100 font-medium">{{ $assignment->grade }} / {{ $assignment->max_grade ?? '—' }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($assignment->description)
                    <div class="mt-6">
                        <h3 class="text-xs text-slate-500 dark:text-slate-400 mb-1">Description</h3>
                        <p class="text-sm text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $assignment->description }}</p>
                    </div>
                @endif

                @if ($assignment->notes)
                    <div class="mt-4">
                        <h3 class="text-xs text-slate-500 dark:text-slate-400 mb-1">Notes</h3>
                        <p class="text-sm text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $assignment->notes }}</p>
                    </div>
                @endif

                <div class="mt-6 text-xs text-slate-500 dark:text-slate-400">
                    Created {{ $assignment->created_at->format('M d, Y g:i A') }}
                    @if ($assignment->updated_at > $assignment->created_at)
                        &middot; Updated {{ $assignment->updated_at->format('M d, Y g:i A') }}
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('assignments.edit', $assignment) }}" class="link">Edit assignment</a>
                <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Delete this assignment?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
