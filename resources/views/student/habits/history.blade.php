<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Habit History</h2>
            <a href="{{ route('habits.index') }}" class="btn-ghost text-sm">Back to habits</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="card p-5">
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-3xl">{{ $habit->icon ?? '✅' }}</span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $habit->name }}</h3>
                        @if ($habit->description)
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $habit->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $stats['total_logs'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Total logs</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $stats['current_streak'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Current streak</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['completion_rate'] ?? 0 }}%</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-medium">Completion rate</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="border-b border-slate-200/70 dark:border-slate-700/60">
                    <h3 class="font-semibold text-sm text-slate-900 dark:text-slate-100 py-3 px-6">Last 30 Days</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-slate-500 uppercase tracking-wider border-b border-slate-200/70 dark:border-slate-700/60">
                                <th class="text-left px-6 py-3 font-medium">Date</th>
                                <th class="text-center px-4 py-3 font-medium">Completed</th>
                                <th class="text-center px-4 py-3 font-medium">Value</th>
                                <th class="text-left px-4 py-3 font-medium">Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-3 text-slate-900 dark:text-slate-100 whitespace-nowrap">{{ $log->log_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @if ($log->completed)
                                            <span class="text-green-500 font-bold">✓</span>
                                        @else
                                            <span class="text-red-400 font-bold">✗</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400 whitespace-nowrap">{{ $log->value ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 max-w-[200px] truncate">{{ $log->note ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No logs recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($logs->hasPages())
                <div class="mt-4">{{ $logs->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
