<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Focus Timer</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="card p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $todayMinutes }} <span class="text-sm font-normal text-gray-500">min</span></p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">This Week</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $thisWeekMinutes }} <span class="text-sm font-normal text-gray-500">min</span></p>
                </div>
                <div class="card p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">All Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalMinutes }} <span class="text-sm font-normal text-gray-500">min</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card p-8">
                    <div x-data="pomodoroTimer()" class="text-center">
                        <div class="text-6xl font-bold text-gray-900 dark:text-gray-100 mb-6" x-text="display"></div>

                        <div class="flex justify-center gap-3 mb-6">
                            <button @click="setMode('focus')"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                :class="mode === 'focus' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                Focus
                            </button>
                            <button @click="setMode('break')"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                :class="mode === 'break' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                Break
                            </button>
                        </div>

                        <div class="flex justify-center gap-3">
                            <button @click="toggle()"
                                class="px-8 py-3 rounded-lg text-sm font-medium transition"
                                :class="running ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                                x-text="running ? 'Stop' : 'Start'">
                            </button>
                            <button @click="reset()" class="btn-secondary text-sm">Reset</button>
                        </div>

                        <div class="text-xs text-gray-500 mt-4">
                            <span x-show="mode === 'focus'">Focus: 25min · Break: 5min</span>
                            <span x-show="mode === 'break'">Break time!</span>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Log Focus Session</h3>
                    <form method="POST" action="{{ route('focus.sessions.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <x-input-label for="duration_minutes">Duration (min)</x-input-label>
                                <input type="number" name="duration_minutes" id="duration_minutes" min="1" max="180" value="25" required class="input">
                            </div>
                            <div>
                                <x-input-label for="break_minutes">Break (min)</x-input-label>
                                <input type="number" name="break_minutes" id="break_minutes" min="0" max="60" value="5" class="input">
                            </div>
                        </div>
                        <div>
                            <x-input-label for="ftopic">Topic</x-input-label>
                            <select name="topic_id" id="ftopic" class="input">
                                <option value="">General</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="fnotes">Notes</x-input-label>
                            <input type="text" name="notes" id="fnotes" class="input">
                        </div>
                        <button type="submit" class="btn-primary text-sm">Log Session</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Recent Sessions</h3>
                    <a href="{{ route('focus.history') }}" class="link">View all</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse ($history as $session)
                        <div class="px-6 py-3.5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $session->notes ?: 'Focus session' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $session->completed_at->format('M d, g:i A') }}
                                    @if ($session->topic) · {{ $session->topic->title }} @endif
                                </p>
                            </div>
                            <span class="text-sm text-gray-500 font-medium">{{ $session->duration_minutes }} min</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">No focus sessions yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function pomodoroTimer() {
            return {
                mode: 'focus',
                minutes: 25,
                seconds: 0,
                running: false,
                interval: null,

                get display() {
                    return `${String(this.minutes).padStart(2, '0')}:${String(this.seconds).padStart(2, '0')}`;
                },

                setMode(mode) {
                    this.running = false;
                    if (this.interval) { clearInterval(this.interval); this.interval = null; }
                    this.mode = mode;
                    this.minutes = mode === 'focus' ? 25 : 5;
                    this.seconds = 0;
                },

                toggle() {
                    if (this.running) {
                        clearInterval(this.interval);
                        this.interval = null;
                        this.running = false;
                    } else {
                        this.running = true;
                        this.interval = setInterval(() => {
                            if (this.seconds === 0) {
                                if (this.minutes === 0) {
                                    clearInterval(this.interval);
                                    this.interval = null;
                                    this.running = false;
                                    this.mode = this.mode === 'focus' ? 'break' : 'focus';
                                    this.minutes = this.mode === 'focus' ? 25 : 5;
                                    this.seconds = 0;
                                    return;
                                }
                                this.minutes--;
                                this.seconds = 59;
                            } else {
                                this.seconds--;
                            }
                        }, 1000);
                    }
                },

                reset() {
                    clearInterval(this.interval);
                    this.interval = null;
                    this.running = false;
                    this.minutes = this.mode === 'focus' ? 25 : 5;
                    this.seconds = 0;
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
