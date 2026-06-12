<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Flashcards</h2>
            <a href="{{ route('flashcards.create') }}" class="btn-primary text-sm">+ New Deck</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($decks as $deck)
                    <div class="card hover:shadow-md transition">
                        <a href="{{ route('flashcards.show', $deck) }}" class="block p-5">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $deck->title }}</h3>
                                <span class="badge-gray text-xs">{{ $deck->flashcards_count }} cards</span>
                            </div>
                            @if ($deck->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ $deck->description }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs">
                                @php
                                    $due = $deck->getRelation('flashcards') ? $deck->flashcards->sum('due_reviews') : 0;
                                @endphp
                                @if ($due > 0)
                                    <span class="badge-orange">{{ $due }} due for review</span>
                                @else
                                    <span class="text-green-600 dark:text-green-400 font-medium">All reviewed</span>
                                @endif
                                <span class="text-gray-400">{{ $deck->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full empty-state">
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1">No flashcard decks yet</p>
                        <p class="text-sm text-gray-500 mb-4">Create a deck to start studying with flashcards.</p>
                        <a href="{{ route('flashcards.create') }}" class="btn-primary text-sm">Create your first deck</a>
                    </div>
                @endforelse
            </div>

            @if ($decks->hasPages())
                <div class="mt-6">{{ $decks->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
