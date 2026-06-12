<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LearnAI — Learn Anything, Track Everything</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <span class="text-xl font-semibold tracking-tight">LearnAI</span>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Get started</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h1 class="text-5xl font-semibold tracking-tight mb-6">
            Learn anything.<br>Track everything.
        </h1>
        <p class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-10">
            Search any topic, get AI-structured learning content, and track your progress — all in one place built for focused students.
        </p>

        <form action="{{ route('topics.search') }}" method="GET" class="flex gap-3 max-w-xl mx-auto mb-16">
            <input
                type="text"
                name="q"
                placeholder="Search a topic to learn..."
                class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required
            />
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                Search
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl mb-3">🔍</div>
                <h3 class="font-semibold mb-2">AI-Structured Content</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Every topic is broken into overview, explanation, examples, quiz, and summary — generated on demand.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl mb-3">📊</div>
                <h3 class="font-semibold mb-2">Progress Tracking</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Track study time, completion percentage, and which topics you've mastered vs. still working on.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl mb-3">🔖</div>
                <h3 class="font-semibold mb-2">Bookmarks & Dashboard</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Save topics you want to return to and see your weekly study stats at a glance on your personal dashboard.</p>
            </div>
        </div>
    </main>
</body>
</html>
