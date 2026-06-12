<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Study Planner</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Weekly Progress --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">This Week</h3>
                    @if ($weeklyGoal)
                        <span class="text-sm text-gray-500">{{ $thisWeekMinutes }} / {{ $weeklyGoal->target_minutes }} min</span>
                    @endif
                </div>
                @if ($weeklyGoal)
                    @php $pct = min(100, round(($thisWeekMinutes / $weeklyGoal->target_minutes) * 100)); @endphp
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">{{ $pct }}% of weekly goal</p>
                @else
                    <p class="text-sm text-gray-500">Set a weekly goal to track your progress.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Today's sessions --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Today</h3>
                        <button @click="$dispatch('open-modal', 'session-modal')" class="link text-xs">+ Plan</button>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($todaySessions as $session)
                            <div class="px-6 py-3.5 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $session->title }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->scheduled_at)->format('g:i A') }} · {{ $session->duration_minutes }}min</p>
                                </div>
                                <div class="flex gap-2">
                                    @if ($session->status === 'scheduled')
                                        <form method="POST" action="{{ route('planner.sessions.complete', $session) }}">
                                            @csrf @method('PATCH')
                                            <button class="text-xs text-green-600 hover:underline">Done</button>
                                        </form>
                                        <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-red-600 hover:underline">Remove</button>
                                        </form>
                                    @else
                                        <span class="text-xs badge px-2 py-0.5
                                            @if($session->status === 'completed') badge-green
                                            @else badge-gray @endif">
                                            {{ $session->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No sessions planned for today.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Goals --}}
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Active Goals</h3>
                        <button @click="$dispatch('open-modal', 'goal-modal')" class="link text-xs">+ Goal</button>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse ($activeGoals as $goal)
                            <div class="px-6 py-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $goal->title }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($goal->goal_type) }} · {{ $goal->current_minutes }} / {{ $goal->target_minutes }} min</p>
                                    </div>
                                    <form method="POST" action="{{ route('planner.goals.complete', $goal) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs text-green-600 hover:underline">Complete</button>
                                    </form>
                                </div>
                                @php $pct = $goal->progressPercent(); @endphp
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $pct }}%</p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No active goals.</div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Upcoming --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Upcoming Sessions</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse ($upcomingSessions as $session)
                        <div class="px-6 py-3.5 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $session->title }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->scheduled_at)->format('D, M j · g:i A') }} · {{ $session->duration_minutes }}min</p>
                            </div>
                            <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-600 hover:underline">Remove</button>
                            </form>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">No upcoming sessions.</div>
                    @endforelse
                </div>
            </div>

            {{-- Past --}}
            @if ($pastSessions->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Uncompleted Sessions</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @foreach ($pastSessions as $session)
                        <div class="px-6 py-3.5 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $session->title }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->scheduled_at)->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('planner.sessions.complete', $session) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-green-600 hover:underline">Mark done</button>
                                </form>
                                <form method="POST" action="{{ route('planner.sessions.destroy', $session) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Goal Modal --}}
    <x-modal name="goal-modal" focusable>
        <form method="POST" action="{{ route('planner.goals.store') }}" class="p-6 space-y-4">
            @csrf
            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">New Goal</h3>
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
                <button type="submit" class="btn-primary text-sm">Create Goal</button>
            </div>
        </form>
    </x-modal>

    {{-- Session Modal --}}
    <x-modal name="session-modal" focusable>
        <form method="POST" action="{{ route('planner.sessions.store') }}" class="p-6 space-y-4">
            @csrf
            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">Plan Session</h3>
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
                    <x-input-label for="scheduled_at">Date & Time</x-input-label>
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
                <button type="submit" class="btn-primary text-sm">Plan Session</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
