<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">My Progress</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($progress->isEmpty())
                <div class="empty-state">
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">No progress tracked yet</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Start learning to see your progress.</p>
                    <a href="{{ route('topics.index') }}" class="btn-primary text-sm">Browse your topics</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($progress as $record)
                        <div class="card p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <a href="{{ route('topics.show', $record->topic) }}" class="font-semibold text-sm text-slate-900 dark:text-slate-100 hover:text-cyan-600 dark:hover:text-cyan-400">
                                        {{ $record->topic->title }}
                                    </a>
                                    @if($record->last_studied_at)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Last studied {{ $record->last_studied_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                                <span class="text-sm font-medium
                                    {{ $record->status === 'completed' ? 'text-green-600 dark:text-green-400' : ($record->status === 'in_progress' ? 'text-cyan-600 dark:text-cyan-400' : 'text-slate-400') }}">
                                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-cyan-600 dark:bg-cyan-500 h-2 rounded-full transition-all" style="width: {{ $record->completion_percentage }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">{{ $record->completion_percentage }}% complete</p>
                        </div>
                    @endforeach
                </div>

                @if ($progress->hasPages())
                    <div class="mt-6">{{ $progress->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
