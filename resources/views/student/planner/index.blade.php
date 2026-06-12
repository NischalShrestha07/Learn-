<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="eyebrow">Time and goals</span>
                <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-slate-100">Study Planner</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Turn vague intentions into sessions, goals, and a weekly rhythm you can follow.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button @click="$dispatch('open-modal', 'goal-modal')" class="btn-secondary text-sm">New goal</button>
                <button @click="$dispatch('open-modal', 'session-modal')" class="btn-primary text-sm">Plan session</button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="card p-6">
                    <p class="section-title">This week</p>
                    @if ($weeklyGoal)
                        @php $pct = min(100, round(($thisWeekMinutes / $weeklyGoal->target_minutes) * 100)); @endphp
                        <div class="mt-4 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $thisWeekMinutes }} / {{ $weeklyGoal->target_minutes }} min</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $pct }}% of your weekly goal is complete.</p>
                            </div>
                            <span class="badge-indigo">{{ ucfirst($weeklyGoal->goal_type) }}</span>
                        </div>
                        <div class="mt-5 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="h-3 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-800" style="width: {{ $pct }}%"></div>
                        </div>
                    @else
                        <h3 class="mt-4 text-2xl font-bold text-slate-950 dark:text-slate-100">No weekly goal yet</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Set a weekly target so your study time has a clear baseline.</p>
                    @endif
                </div>

                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="metric-card">
                        <p class="section-title">Today</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $todaySessions->count() }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Sessions on today's plan.</p>
                    </div>
                    <div class="metric-card">
                        <p class="section-title">Active goals</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $activeGoals->count() }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Ongoing targets you are working toward.</p>
                    </div>
                    <div class="metric-card">
                        <p class="section-title">Upcoming</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-slate-100">{{ $upcomingSessions->count() }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Scheduled sessions after today.</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div>
                            <p class="section-title">Today</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-950 dark:text-slate-100">Planned sessions</h3>
                        </div>
                        <button @click="$dispatch('open-modal', 'session-modal')" class="link text-xs">Add session</button>
                    </div>
                    <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                        @forelse ($todaySessions as $session)
                            <div class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $session->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($session->scheduled_at)->format('g:i A') }} · {{ $session->duration_minutes }} min</p>
                                </div>
                                <div class="flex gap-2">
                                    @if ($session->status === 'scheduled')
                                        <form method="POST" action="{{ route('planner.sessions.complete', $session) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-xs font-semibold text-green-700 dark:text-green-400 hover:underline">Done</button>
                                        </form>
                                        <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline">Remove</button>
                                        </form>
                                    @else
                                        <span class="@if($session->status === 'completed') badge-green @else badge-gray @endif">{{ ucfirst($session->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No sessions planned for today.</div>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div>
                            <p class="section-title">Goals</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-950 dark:text-slate-100">Active goals</h3>
                        </div>
                        <button @click="$dispatch('open-modal', 'goal-modal')" class="link text-xs">Add goal</button>
                    </div>
                    <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                        @forelse ($activeGoals as $goal)
                            <div class="px-6 py-4">
                                <div class="mb-3 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $goal->title }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($goal->goal_type) }} · {{ $goal->current_minutes }} / {{ $goal->target_minutes }} min</p>
                                    </div>
                                    <form method="POST" action="{{ route('planner.goals.complete', $goal) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-xs font-semibold text-green-700 dark:text-green-400 hover:underline">Complete</button>
                                    </form>
                                </div>
                                @php $pct = $goal->progressPercent(); @endphp
                                <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-cyan-500 to-cyan-800" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $pct }}% complete</p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No active goals.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <p class="section-title">Upcoming</p>
                    <h3 class="mt-1 text-lg font-bold text-slate-950 dark:text-slate-100">Scheduled after today</h3>
                </div>
                <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                    @forelse ($upcomingSessions as $session)
                        <div class="flex items-center justify-between gap-4 px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $session->title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($session->scheduled_at)->format('D, M j · g:i A') }} · {{ $session->duration_minutes }} min</p>
                            </div>
                            <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline">Remove</button>
                            </form>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No upcoming sessions.</div>
                    @endforelse
                </div>
            </div>

            @if ($pastSessions->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <p class="section-title">Needs review</p>
                        <h3 class="mt-1 text-lg font-bold text-slate-950 dark:text-slate-100">Uncompleted sessions</h3>
                    </div>
                    <div class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                        @foreach ($pastSessions as $session)
                            <div class="flex items-center justify-between gap-4 px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $session->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($session->scheduled_at)->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('planner.sessions.complete', $session) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-xs font-semibold text-green-700 dark:text-green-400 hover:underline">Mark done</button>
                                    </form>
                                    <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-modal name="goal-modal" focusable>
        <form method="POST" action="{{ route('planner.goals.store') }}" class="space-y-4 p-6">
            @csrf
            <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">New goal</h3>
            <div>
                <x-input-label for="gtitle">Title</x-input-label>
                <input type="text" name="title" id="gtitle" required class="input">
            </div>
            <div>
                <x-input-label for="gdesc">Description</x-input-label>
                <textarea name="description" id="gdesc" rows="2" class="input"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="goal_type">Type</x-input-label>
                    <select name="goal_type" id="goal_type" class="input">
                        <option value="daily">Daily</option>
                        <option value="weekly" selected>Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="target_minutes">Target (minutes)</x-input-label>
                    <input type="number" name="target_minutes" id="target_minutes" min="1" value="120" required class="input">
                </div>
            </div>
            <div>
                <x-input-label for="end_date">End date (optional)</x-input-label>
                <input type="date" name="end_date" id="end_date" class="input">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$dispatch('close-modal', 'goal-modal')" class="btn-secondary text-sm">Cancel</button>
                <button type="submit" class="btn-primary text-sm">Create goal</button>
            </div>
        </form>
    </x-modal>

    <x-modal name="session-modal" focusable>
        <form method="POST" action="{{ route('planner.sessions.store') }}" class="space-y-4 p-6">
            @csrf
            <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">Plan session</h3>
            <div>
                <x-input-label for="stitle">Title</x-input-label>
                <input type="text" name="title" id="stitle" required class="input">
            </div>
            <div>
                <x-input-label for="stopic">Topic</x-input-label>
                <select name="topic_id" id="stopic" class="input">
                    <option value="">General study</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="scheduled_at">Date and time</x-input-label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" required class="input">
                </div>
                <div>
                    <x-input-label for="duration_minutes">Duration (min)</x-input-label>
                    <input type="number" name="duration_minutes" id="duration_minutes" min="5" max="480" value="30" class="input">
                </div>
            </div>
            <div>
                <x-input-label for="snotes">Notes</x-input-label>
                <textarea name="notes" id="snotes" rows="2" class="input"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$dispatch('close-modal', 'session-modal')" class="btn-secondary text-sm">Cancel</button>
                <button type="submit" class="btn-primary text-sm">Save session</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
