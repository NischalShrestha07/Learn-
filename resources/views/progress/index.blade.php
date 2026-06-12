<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Progress
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if($progress->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">No progress tracked yet.</p>
                    <a href="{{ route('topics.search', ['q' => 'programming']) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Find a topic to start</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($progress as $record)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <a href="{{ route('topics.show', $record->topic->slug) }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $record->topic->title }}
                                    </a>
                                    @if($record->last_studied_at)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Last studied {{ $record->last_studied_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                                <span class="text-sm font-medium
                                    {{ $record->status === 'completed' ? 'text-green-600 dark:text-green-400' : ($record->status === 'in_progress' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400') }}">
                                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div
                                    class="bg-indigo-600 dark:bg-indigo-500 h-1.5 rounded-full transition-all"
                                    style="width: {{ $record->completion_percentage }}%"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">{{ $record->completion_percentage }}% complete</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $progress->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
