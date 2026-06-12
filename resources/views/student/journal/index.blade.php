<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reflection Journal</h2>
                @if ($streak > 0)
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-0.5">🔥 {{ $streak }} day streak!</p>
                @endif
            </div>
            <button @click="$dispatch('open-modal', 'journal-modal')" class="btn-primary text-sm">+ New Entry</button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="card p-4">
                <form method="GET" class="flex gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                        <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="input text-sm">
                    </div>
                    <button type="submit" class="btn-secondary text-sm">Filter</button>
                    @if (request('month'))
                        <a href="{{ route('journal.index') }}" class="btn-ghost text-sm">Clear</a>
                    @endif
                </form>
            </div>

            @forelse ($entries as $entry)
                <div class="card p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $entry->date->format('l, F j, Y') }}</p>
                            @if ($entry->prompt)
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $entry->prompt }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            @if ($entry->mood)
                                <span class="text-lg">
                                    @switch($entry->mood)
                                        @case(1) 😞 @break
                                        @case(2) 😕 @break
                                        @case(3) 😐 @break
                                        @case(4) 😊 @break
                                        @case(5) 🎉 @break
                                    @endswitch
                                </span>
                            @endif
                            <form method="POST" action="{{ route('journal.destroy', $entry) }}" onsubmit="return confirm('Delete this entry?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="prose-custom">
                        {!! Str::of($entry->content)->markdown() !!}
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No journal entries yet</p>
                    <p class="text-sm text-gray-500">Reflect on your learning journey.</p>
                </div>
            @endforelse

            @if ($entries->hasPages())
                <div class="mt-6">{{ $entries->links() }}</div>
            @endif
        </div>
    </div>

    <x-modal name="journal-modal" focusable>
        <form method="POST" action="{{ route('journal.store') }}" class="p-6 space-y-4">
            @csrf
            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">New Journal Entry</h3>
            <div>
                <x-input-label for="jdate">Date</x-input-label>
                <input type="date" name="date" id="jdate" value="{{ old('date', now()->format('Y-m-d')) }}" required class="input">
            </div>
            <div>
                <x-input-label>Mood</x-input-label>
                <div class="flex gap-3">
                    @foreach ([1 => '😞', 2 => '😕', 3 => '😐', 4 => '😊', 5 => '🎉'] as $val => $emoji)
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="mood" value="{{ $val }}" class="sr-only peer">
                            <span class="text-2xl peer-checked:ring-2 peer-checked:ring-indigo-500 rounded-full p-1 transition">{{ $emoji }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <x-input-label for="jprompt">Prompt (optional)</x-input-label>
                <select name="prompt" id="jprompt" class="input">
                    <option value="">No prompt</option>
                    @foreach ($prompts as $prompt)
                        <option value="{{ $prompt }}">{{ $prompt }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="jcontent">What's on your mind?</x-input-label>
                <textarea name="content" id="jcontent" rows="8" required class="input font-mono">{{ old('content') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Supports Markdown</p>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="$dispatch('close-modal', 'journal-modal')" class="btn-secondary text-sm">Cancel</button>
                <button type="submit" class="btn-primary text-sm">Save Entry</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
