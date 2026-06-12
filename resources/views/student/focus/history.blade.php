<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Focus History</h2>
            <a href="{{ route('focus.index') }}" class="btn-ghost text-sm">Back to timer</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card">
                <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                    @forelse ($sessions as $session)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $session->notes ?: 'Focus session' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $session->completed_at->format('M d, Y g:i A') }}
                                    @if ($session->topic) · {{ $session->topic->title }} @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $session->duration_minutes }}min</span>
                                <p class="text-xs text-slate-500">{{ $session->break_minutes }}min break</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-sm text-slate-500">No focus sessions recorded yet.</div>
                    @endforelse
                </div>
            </div>
            @if ($sessions->hasPages())
                <div class="mt-6">{{ $sessions->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
