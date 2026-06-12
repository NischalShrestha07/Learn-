<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="eyebrow">Attention management</span>
                <h2 class="mt-3 text-2xl font-bold text-slate-950">Focus Timer</h2>
                <p class="mt-1 text-sm text-slate-500">Use a simple rhythm for deep work, then log the session so your focus becomes measurable.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="metric-card">
                    <p class="section-title">Today</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $todayMinutes }} <span class="text-base font-medium text-slate-500">min</span></p>
                </div>
                <div class="metric-card">
                    <p class="section-title">This week</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $thisWeekMinutes }} <span class="text-base font-medium text-slate-500">min</span></p>
                </div>
                <div class="metric-card">
                    <p class="section-title">All time</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalMinutes }} <span class="text-base font-medium text-slate-500">min</span></p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="card p-8">
                    <div x-data="pomodoroTimer()" class="text-center">
                        <p class="section-title">Pomodoro</p>
                        <div class="mt-4 text-6xl font-bold text-slate-950" x-text="display"></div>

                        <div class="mt-8 flex justify-center gap-3">
                            <button @click="setMode('focus')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition" :class="mode === 'focus' ? 'bg-cyan-700 text-white' : 'bg-slate-100 text-slate-600'">
                                Focus
                            </button>
                            <button @click="setMode('break')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition" :class="mode === 'break' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-600'">
                                Break
                            </button>
                        </div>

                        <div class="mt-8 flex justify-center gap-3">
                            <button @click="toggle()" class="rounded-2xl px-8 py-3 text-sm font-semibold text-white transition" :class="running ? 'bg-red-600 hover:bg-red-700' : 'bg-cyan-700 hover:bg-cyan-800'" x-text="running ? 'Stop' : 'Start'">
                            </button>
                            <button @click="reset()" class="btn-secondary text-sm">Reset</button>
                        </div>

                        <div class="mt-5 text-xs text-slate-500">
                            <span x-show="mode === 'focus'">Focus: 25 min · Break: 5 min</span>
                            <span x-show="mode === 'break'">Break mode is active.</span>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <p class="section-title">Manual log</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-950">Save a completed focus session</h3>
                    <form method="POST" action="{{ route('focus.sessions.store') }}" class="mt-5 space-y-4">
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
                            <input type="text" name="notes" id="fnotes" class="input" placeholder="What did you work on?">
                        </div>
                        <button type="submit" class="btn-primary text-sm">Log session</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <div>
                        <p class="section-title">History</p>
                        <h3 class="mt-1 text-lg font-bold text-slate-950">Recent focus sessions</h3>
                    </div>
                    <a href="{{ route('focus.history') }}" class="link">View all</a>
                </div>
                <div class="divide-y divide-slate-200/70">
                    @forelse ($history as $session)
                        <div class="flex items-center justify-between gap-4 px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $session->notes ?: 'Focus session' }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $session->completed_at->format('M d, g:i A') }}
                                    @if ($session->topic) · {{ $session->topic->title }} @endif
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-slate-500">{{ $session->duration_minutes }} min</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-500">No focus sessions yet.</div>
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
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }
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
