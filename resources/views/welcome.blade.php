<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentLMS | Every student tool, one focused workspace</title>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell">
    <div class="relative overflow-hidden">
        <div class="hero-orb -left-24 top-16 h-64 w-64 bg-cyan-200 dark:bg-cyan-800/30"></div>
        <div class="hero-orb right-0 top-0 h-72 w-72 bg-amber-200 dark:bg-amber-800/30"></div>

        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <div>
                <span class="text-2xl font-bold tracking-tight text-cyan-950 dark:text-cyan-300">StudentLMS</span>
                <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Study Operating System</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <button @click="$store.app.toggleDarkMode()" class="btn-ghost !rounded-xl !p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" title="Toggle dark mode">
                    <svg x-show="!$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="$store.app.darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">Start free</a>
                @endauth
            </div>
        </nav>

        <main>
            <section class="mx-auto grid max-w-7xl items-center gap-12 px-4 pb-20 pt-10 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:px-8 lg:pb-28 lg:pt-14">
                <div>
                    <span class="eyebrow">For students who want structure</span>
                    <h1 class="mt-6 max-w-3xl text-5xl font-bold leading-tight text-slate-950 dark:text-slate-100 sm:text-6xl">
                        Track academics, build habits, and grow — all in one place.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-400">
                        StudentLMS brings together topics, notes, flashcards, assignments, exams, grades, planner, focus timer, journal, habits, achievements, and resources — so your entire student life finally feels connected.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Open dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">Create your workspace</a>
                            <a href="{{ route('login') }}" class="btn-secondary">See your study tools</a>
                        @endauth
                    </div>
                    <div class="mt-10 grid max-w-2xl grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="card p-4">
                            <p class="text-3xl font-bold text-slate-950 dark:text-slate-100">12</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Core student tools already connected</p>
                        </div>
                        <div class="card p-4">
                            <p class="text-3xl font-bold text-slate-950 dark:text-slate-100">1</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Workspace for academics, planning, habits, and growth</p>
                        </div>
                        <div class="card p-4">
                            <p class="text-3xl font-bold text-slate-950 dark:text-slate-100">0</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Reason to juggle scattered student tools</p>
                        </div>
                    </div>
                </div>

                <div class="glass-panel relative overflow-hidden rounded-[32px] p-6 sm:p-8">
                    <div class="absolute inset-x-6 top-6 h-28 rounded-[28px] bg-gradient-to-r from-cyan-600 via-sky-500 to-amber-400 opacity-90"></div>
                    <div class="relative mt-16 space-y-4">
                        <div class="card p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="section-title">Today</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-slate-100">Deep Work Block</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">45 min focus on Machine Learning basics</p>
                                </div>
                                <span class="badge-indigo">Focus</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="card p-5">
                                <p class="section-title">Topics</p>
                                <p class="mt-2 text-3xl font-bold text-slate-950 dark:text-slate-100">12</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Organized learning tracks</p>
                            </div>
                            <div class="card p-5">
                                <p class="section-title">Review</p>
                                <p class="mt-2 text-3xl font-bold text-slate-950 dark:text-slate-100">28</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Flashcards ready today</p>
                            </div>
                        </div>
                        <div class="card p-5">
                            <p class="section-title">Momentum</p>
                            <div class="mt-3 flex items-end gap-2">
                                <div class="h-10 w-8 rounded-t-2xl bg-cyan-200 dark:bg-cyan-800"></div>
                                <div class="h-16 w-8 rounded-t-2xl bg-cyan-300 dark:bg-cyan-700"></div>
                                <div class="h-12 w-8 rounded-t-2xl bg-cyan-400 dark:bg-cyan-600"></div>
                                <div class="h-20 w-8 rounded-t-2xl bg-cyan-500 dark:bg-cyan-500"></div>
                                <div class="h-24 w-8 rounded-t-2xl bg-amber-400 dark:bg-amber-600"></div>
                            </div>
                            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Consistent study becomes visible, not vague.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div class="card p-6">
                        <p class="section-title">Topics</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Organize your subjects</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Create topics with sections, track learning sessions, and build knowledge systematically.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Notes & Flashcards</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Capture & review</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Take rich notes with tags and turn them into flashcards with spaced repetition for lasting recall.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Assignments & Exams</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Never miss a deadline</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Track homework, projects, quizzes, and exams with due dates, priorities, and grades all in one view.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Grades & GPA</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Know your standing</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Log course grades by semester, calculate CGPA, and track your academic performance over time.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Planner & To-Do</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Plan with purpose</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Set study goals, schedule sessions, manage daily tasks, and build momentum with clear priorities.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Focus Timer</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Stay in the zone</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Use the Pomodoro timer to build deep work habits and review your focus history over time.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Journal & Reflection</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Process your journey</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Write daily journal entries with mood tracking, review progress, and celebrate achievements.</p>
                    </div>
                    <div class="card p-6">
                        <p class="section-title">Habits & Growth</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950 dark:text-slate-100">Build consistency</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">Track daily habits, build streaks, log wellness, and earn achievements that reflect your growth.</p>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
                <div class="glass-panel rounded-[36px] px-6 py-10 sm:px-10">
                    <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                        <div>
                            <span class="eyebrow">Why it feels better</span>
                            <h2 class="mt-5 text-3xl font-bold text-slate-950 dark:text-slate-100 sm:text-4xl">Less switching. Better student decisions.</h2>
                            <p class="mt-4 max-w-xl text-base leading-7 text-slate-600 dark:text-slate-400">
                                Most student workflows break because academics, planning, habits, and reflection live in separate tools. StudentLMS keeps everything together so your next action is always clear.
                            </p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="card p-5">
                                <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">Plan</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Set goals, schedule sessions, and decide what matters this week.</p>
                            </div>
                            <div class="card p-5">
                                <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">Study</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Work inside a structure that keeps topics, notes, assignments, and resources connected.</p>
                            </div>
                            <div class="card p-5">
                                <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">Review</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Use flashcards, exam tracking, and recent activity to revisit what needs reinforcement.</p>
                            </div>
                            <div class="card p-5">
                                <h3 class="text-lg font-bold text-slate-950 dark:text-slate-100">Reflect</h3>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Measure focus, grades, habits, and progress so your growth becomes visible.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @guest
                <section class="mx-auto max-w-5xl px-4 pb-24 text-center sm:px-6 lg:px-8">
                    <span class="eyebrow">Start now</span>
                    <h2 class="mt-5 text-4xl font-bold text-slate-950 dark:text-slate-100">Build a study system you will actually keep using.</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-400">
                        Start with topics, notes, assignments, planner, habits, and focus tracking today. Everything a student needs, built into one focused workspace.
                    </p>
                    <div class="mt-8 flex justify-center gap-3">
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-3 text-base">Create free account</a>
                        <a href="{{ route('login') }}" class="btn-secondary px-8 py-3 text-base">I already have an account</a>
                    </div>
                </section>
            @endguest
        </main>
    </div>
</body>
</html>