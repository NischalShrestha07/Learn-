<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Review: {{ $deck->title }}</h2>
            <a href="{{ route('flashcards.show', $deck) }}" class="btn-ghost text-sm">Back to deck</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8" x-data="flashcardReview()">
            <template x-if="cards.length === 0">
                <div class="empty-state">
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100 mb-1">All caught up!</p>
                    <p class="text-sm text-slate-500 mb-4">No cards due for review.</p>
                    <a href="{{ route('flashcards.show', $deck) }}" class="btn-primary text-sm">Back to deck</a>
                </div>
            </template>

            <template x-if="cards.length > 0">
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-slate-500 mb-1">
                            <span x-text="`Card ${current + 1} of ${cards.length}`"></span>
                            <span x-text="`${Math.round((current / cards.length) * 100)}%`"></span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-cyan-600 h-2 rounded-full transition-all duration-300" :style="`width: ${(current / cards.length) * 100}%`"></div>
                        </div>
                    </div>

                    <div class="card p-8">
                        <template x-for="(card, index) in cards" :key="card.id">
                            <div x-show="index === current">
                                <div class="text-center">
                                    <p class="text-xs text-slate-400 mb-2">Front</p>
                                    <p class="text-xl font-medium text-slate-900 dark:text-slate-100 mb-6" x-html="card.front"></p>

                                    <button @click="flip" x-show="!flipped" class="btn-secondary text-sm mb-4">
                                        Show Answer
                                    </button>

                                    <div x-show="flipped" x-transition>
                                        <div class="border-t border-slate-200/70 dark:border-slate-700/60 pt-6 mb-4">
                                            <p class="text-xs text-slate-400 mb-2">Back</p>
                                            <p class="text-lg text-slate-700 dark:text-slate-300 mb-4" x-html="card.back"></p>
                                            <template x-if="card.hint">
                                                <p class="text-sm text-slate-500 italic" x-text="`Hint: ${card.hint}`"></p>
                                            </template>
                                        </div>

                                        <p class="text-sm text-slate-500 mb-3">How well did you know this?</p>
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <button @click="submitReview(card.id, 1)" :disabled="submitting" class="btn-secondary text-xs !px-3 !py-1.5 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800 hover:bg-red-100">1 - Forgot</button>
                                            <button @click="submitReview(card.id, 2)" :disabled="submitting" class="btn-secondary text-xs !px-3 !py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800 hover:bg-orange-100">2 - Hard</button>
                                            <button @click="submitReview(card.id, 3)" :disabled="submitting" class="btn-secondary text-xs !px-3 !py-1.5">3 - Okay</button>
                                            <button @click="submitReview(card.id, 4)" :disabled="submitting" class="btn-secondary text-xs !px-3 !py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800 hover:bg-blue-100">4 - Good</button>
                                            <button @click="submitReview(card.id, 5)" :disabled="submitting" class="btn-secondary text-xs !px-3 !py-1.5 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800 hover:bg-green-100">5 - Easy</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @push('scripts')
    <script>
        function flashcardReview() {
            return {
                cards: @json($cards->values()),
                current: 0,
                flipped: false,
                submitting: false,

                flip() { this.flipped = true; },

                submitReview(cardId, quality) {
                    if (this.submitting) return;
                    this.submitting = true;

                    fetch(`/flashcards/${@json($deck->id)}/cards/${cardId}/review`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ quality })
                    }).then(r => r.json()).then(() => {
                        this.flipped = false;
                        this.submitting = false;
                        if (this.current < this.cards.length - 1) {
                            this.current++;
                        } else {
                            this.cards = [];
                        }
                    }).catch(() => { this.submitting = false; });
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
