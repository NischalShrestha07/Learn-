<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentLMS — Your Personal Learning System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-700 dark:text-gray-300">

    <nav class="bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/60 dark:border-gray-800/60 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400 tracking-tight">StudentLMS</span>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">Get started</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6">
                Your personal<br><span class="text-indigo-600 dark:text-indigo-400">learning command center.</span>
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-16">
                Organize topics, take notes, build flashcards, track study time, journal your progress — everything a focused student needs in one place.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-left max-w-4xl mx-auto">
                <div class="card p-6">
                    <div class="text-2xl mb-3">📚</div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Study Topics</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create and organize topics with structured sections. Add your own notes, examples, and resources.</p>
                </div>
                <div class="card p-6">
                    <div class="text-2xl mb-3">🃏</div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Flashcards & Notes</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Build flashcards with spaced repetition. Take markdown notes and tag them for easy retrieval.</p>
                </div>
                <div class="card p-6">
                    <div class="text-2xl mb-3">📊</div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Track Everything</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Study timer, session tracking, goal setting, journal reflections — see your progress over time.</p>
                </div>
            </div>

            @guest
                <div class="mt-12">
                    <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-3">Start learning — it's free</a>
                </div>
            @endguest
        </section>
    </main>
</body>
</html>
