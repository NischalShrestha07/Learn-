<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $topic->title }}
            </h2>
            @auth
                <button
                    id="bookmark-btn"
                    hx-post="{{ route('bookmarks.toggle', $topic) }}"
                    onclick="toggleBookmark(this)"
                    data-topic="{{ $topic->id }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                >
                    🔖 Bookmark
                </button>
            @endauth
        </div>
    </x-slot>

    <div class="py-10" x-data="topicPage()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($topic->generation_status === 'pending')
                {{-- Polling state --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700" x-show="!ready">
                    <div class="animate-spin w-8 h-8 border-4 border-indigo-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                    <p class="text-gray-600 dark:text-gray-400">AI is generating content for this topic...</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">This usually takes 15–30 seconds.</p>
                </div>

            @elseif($topic->generation_status === 'failed')
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-8 text-center border border-red-200 dark:border-red-800">
                    <p class="text-red-700 dark:text-red-400 font-medium">Content generation failed.</p>
                    @auth
                        <form action="{{ route('topics.generate') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="title" value="{{ $topic->title }}">
                            <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Retry with AI →</button>
                        </form>
                    @endauth
                </div>

            @else
                {{-- Content ready --}}
                @if($topic->description)
                    <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">{{ $topic->description }}</p>
                @endif

                {{-- Section tabs --}}
                <div x-data="{ active: 0 }">
                    <div class="flex gap-1 mb-6 overflow-x-auto pb-1 border-b border-gray-200 dark:border-gray-700">
                        @foreach($sections as $i => $section)
                            <button
                                @click="active = {{ $i }}"
                                :class="active === {{ $i }} ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="shrink-0 px-4 py-2 text-sm font-medium border-b-2 capitalize transition"
                            >
                                {{ $section->type }}
                            </button>
                        @endforeach
                    </div>

                    @foreach($sections as $i => $section)
                        <div x-show="active === {{ $i }}" class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 prose dark:prose-invert max-w-none">
                            {!! (new \League\CommonMark\CommonMarkConverter())->convert($section->content) !!}
                        </div>
                    @endforeach
                </div>

                {{-- End session button --}}
                @auth
                    <div class="mt-8 flex justify-end">
                        <button
                            onclick="endSession()"
                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg transition"
                        >
                            Done studying
                        </button>
                    </div>
                @endauth
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        function topicPage() {
            return {
                ready: {{ $topic->isReady() ? 'true' : 'false' }},
                init() {
                    if (!this.ready) {
                        this.poll();
                    }
                },
                poll() {
                    const interval = setInterval(async () => {
                        const res = await fetch('{{ route('topics.status', $topic->slug) }}');
                        const data = await res.json();
                        if (data.ready || data.failed) {
                            clearInterval(interval);
                            window.location.reload();
                        }
                    }, 3000);
                }
            };
        }

        async function endSession() {
            await fetch('{{ route('sessions.end', $topic->slug) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            });
        }

        async function toggleBookmark(btn) {
            const res = await fetch('{{ route('bookmarks.toggle', $topic) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });
            const data = await res.json();
            btn.textContent = data.bookmarked ? '🔖 Bookmarked' : '🔖 Bookmark';
        }

        // End session on page unload
        window.addEventListener('beforeunload', () => {
            navigator.sendBeacon(
                '{{ route('sessions.end', $topic->slug) }}',
                new URLSearchParams({ _token: document.querySelector('meta[name="csrf-token"]').content })
            );
        });
    </script>
    @endpush
</x-app-layout>
