<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Search Results
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form action="{{ route('topics.search') }}" method="GET" class="flex gap-3">
                <input
                    type="text"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search topics..."
                    class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                    Search
                </button>
            </form>

            @if($topics->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl p-10 text-center border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">No topics found for "{{ $query }}"</p>

                    @auth
                        <form action="{{ route('topics.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="title" value="{{ $query }}">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                Generate "{{ $query }}" with AI
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Log in to generate this topic with AI</a>
                    @endauth
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $topics->total() }} result(s) for "{{ $query }}"</p>

                <div class="space-y-3">
                    @foreach($topics as $topic)
                        <a href="{{ route('topics.show', $topic->slug) }}" class="block bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $topic->title }}</h3>
                            @if($topic->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $topic->description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $topic->sections->count() }} sections</p>
                        </a>
                    @endforeach
                </div>

                <div>{{ $topics->links() }}</div>

                @auth
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-5 border border-dashed border-gray-300 dark:border-gray-600 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Don't see exactly what you need?</p>
                        <form action="{{ route('topics.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="title" value="{{ $query }}">
                            <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                Generate "{{ $query }}" with AI →
                            </button>
                        </form>
                    </div>
                @endauth
            @endif
        </div>
    </div>
</x-app-layout>
