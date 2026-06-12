<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $deck->title }}</h2>
                @if ($deck->topic)
                    <p class="text-sm text-gray-500">{{ $deck->topic->title }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('flashcards.review', $deck) }}" class="btn-primary text-sm">Review ({{ $dueCards }} due)</a>
                <a href="{{ route('flashcards.index') }}" class="btn-ghost text-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if ($deck->description)
                <div class="card p-4 text-sm text-gray-500 dark:text-gray-400">{{ $deck->description }}</div>
            @endif

            <div class="card p-6">
                <h3 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-4">Add Card</h3>
                <form method="POST" action="{{ route('flashcards.cards.store', $deck) }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Front (Question)</label>
                            <textarea name="front" rows="2" required class="input text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Back (Answer)</label>
                            <textarea name="back" rows="2" required class="input text-sm"></textarea>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Hint (optional)</label>
                        <input type="text" name="hint" class="input text-sm" maxlength="500">
                    </div>
                    <button type="submit" class="btn-primary text-sm">Add Card</button>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $deck->flashcards->count() }} Cards</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse ($deck->flashcards as $card)
                        <div class="px-6 py-4" x-data="{ show: false }">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $card->front }}</p>
                                    <div x-show="show" x-transition class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $card->back }}</p>
                                        @if ($card->hint)
                                            <p class="text-xs text-gray-500 mt-1">Hint: {{ $card->hint }}</p>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $card->total_reviews }} reviews</p>
                                </div>
                                <div class="flex gap-2 shrink-0 ml-3">
                                    <button @click="show = !show" class="link text-xs" x-text="show ? 'Hide' : 'Show'"></button>
                                    <form method="POST" action="{{ route('flashcards.cards.destroy', [$deck, $card]) }}" onsubmit="return confirm('Delete this card?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">No cards yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="card p-4">
                <details class="group">
                    <summary class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">Edit Deck</summary>
                    <form method="POST" action="{{ route('flashcards.update', $deck) }}" class="mt-3 space-y-3">
                        @csrf @method('PUT')
                        <input type="text" name="title" value="{{ $deck->title }}" required class="input text-sm">
                        <textarea name="description" rows="2" class="input text-sm">{{ $deck->description }}</textarea>
                        <div class="flex gap-2">
                            <button type="submit" class="btn-primary text-sm">Update Deck</button>
                            <form method="POST" action="{{ route('flashcards.destroy', $deck) }}" onsubmit="return confirm('Delete entire deck and all cards?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline px-4 py-2">Delete Deck</button>
                            </form>
                        </div>
                    </form>
                </details>
            </div>
        </div>
    </div>
</x-app-layout>
